<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SupplyTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class DemandController extends Controller
{
    public function predict(Request $request)
    {
        // 1. Get ALL distinct stock names
        $stocks = SupplyTransaction::select('Stock')->distinct()->pluck('Stock');

        // 2. Pre-calc overall average quantity per stock (avg_qty_stock)
        $avgQuantities = SupplyTransaction::select('Stock', DB::raw('AVG(Quantity) as avg_qty_stock'))
            ->groupBy('Stock')
            ->pluck('avg_qty_stock', 'Stock');

        $results = [];

        foreach ($stocks as $stockName) {

            // 3. Get the latest transaction for context (Brand, Year, Month, etc.)
            $latestTransaction = SupplyTransaction::where('Stock', $stockName)
                ->latest('Date')
                ->first();

            if (!$latestTransaction) {
                $results[] = [
                    'stock'          => $stockName,
                    'demand'         => 'No Data',
                    'num_entries'    => 0,
                    'total_quantity' => 0,
                    'avg_quantity'   => 0
                ];
                continue;
            }

            // Extract latest year/month
            $latestYear = (int) date('Y', strtotime($latestTransaction->Date));
            $latestMonth = (int) date('m', strtotime($latestTransaction->Date));

            // 4. Calculate TOTAL_QTY_MONTH
            $totalQtyMonth = SupplyTransaction::where('Stock', $stockName)
                ->whereYear('Date', $latestYear)
                ->whereMonth('Date', $latestMonth)
                ->sum('Quantity');

            // 5. Get avg_qty_stock from pre-calc
            $avgQtyStock = (float) ($avgQuantities[$stockName] ?? 0);

            // 6. API Payload (matches your Python model EXACTLY)
            $payload = [
                "Stock"           => $latestTransaction->Stock,
                "Brand"           => $latestTransaction->Brand,
                "Site_Supplier"   => $latestTransaction->Site_Supplier,
                "Activity"        => $latestTransaction->Activity,
                "Quantity"        => (float) $latestTransaction->Quantity,
                "Unit"            => $latestTransaction->Unit,

                "Year"            => $latestYear,
                "Month"           => $latestMonth,

                "total_qty_month" => (float) $totalQtyMonth,
                "avg_qty_stock"   => $avgQtyStock,
            ];

            // 7. Send to FastAPI
            try {
                $response = Http::timeout(10)->post('http://localhost/predict', $payload);

                if ($response->successful()) {

                    /**
                     * Your Python API returns JSON:
                     * { "predicted_demand_level": "High" }
                     */
                    $prediction = $response->json()['predicted_demand_level'] ?? "Unknown";

                } else {
                    $prediction = "API Error";
                    Log::error("[PREDICT] FastAPI returned error for {$stockName}. Status: {$response->status()}");
                }

            } catch (\Exception $e) {
                $prediction = "Connection Error";
                Log::error("[PREDICT] FastAPI Connection Error: " . $e->getMessage());
            }

            // 8. Store the result
            $results[] = [
                'stock'          => $stockName,
                'demand'         => $prediction,
                'num_entries'    => 1,
                'total_quantity' => $totalQtyMonth,
                'avg_quantity'   => $avgQtyStock,
            ];
        }

        // -------------------------------------------------------
        // 9. GROUP BY DEMAND LEVEL
        // -------------------------------------------------------
        $grouped = [
            'High'      => [],
            'Mid High'  => [],
            'Medium'    => [],
            'Mid Low'   => [],
            'Low'       => [],
            'Others'    => []
        ];

        foreach ($results as $r) {
            $level = $r['demand'];
            if (isset($grouped[$level])) {
                $grouped[$level][] = $r;
            } else {
                $grouped['Others'][] = $r;
            }
        }

        // -------------------------------------------------------
        // 10. PAGINATE EACH GROUP
        // -------------------------------------------------------
        $perPage = 10;
        $paginated = [];

        foreach ($grouped as $level => $items) {
            $pageKey = strtolower(str_replace(' ', '_', $level)) . '_page';
            $currentPage = request()->get($pageKey, 1);

            $paginated[$level] = new LengthAwarePaginator(
                collect($items)->forPage($currentPage, $perPage),
                count($items),
                $perPage,
                $currentPage,
                [
                    'path'  => request()->url(),
                    'query' => request()->except($pageKey)
                ]
            );
        }

        // -------------------------------------------------------
        // 11. RETURN TO VIEW
        // -------------------------------------------------------
        return view('main store.itemActivities.item-demand', [
            'high'      => $paginated['High'],
            'mid_high'  => $paginated['Mid High'],
            'medium'    => $paginated['Medium'],
            'mid_low'   => $paginated['Mid Low'],
            'low'       => $paginated['Low'],
            'others'    => $paginated['Others']
        ]);
    }
}
