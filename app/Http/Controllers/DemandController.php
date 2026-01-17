<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SupplyTransaction;
use Illuminate\Support\Facades\DB;
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

            // 6. API Payload (matches FastAPI model)
            $payload = [
                "stock"         => $latestTransaction->Stock,
                "brand"         => $latestTransaction->Brand,
                "site_supplier" => $latestTransaction->Site_Supplier,
                "activity"      => $latestTransaction->Activity,
                "quantity"      => (float) $latestTransaction->Quantity,
                "unit"          => $latestTransaction->Unit,
                "year"          => $latestYear,
                "month"         => $latestMonth
            ];

            // 7. Send to FastAPI
            try {
                $response = Http::timeout(10)->post('http://72.61.148.31:8000/predict', $payload);

                if ($response->successful()) {
                    $predictionRaw = $response->json()['predicted_demand'] ?? "Unknown";

                    // Normalize prediction to match allowed levels
                    $prediction = ucwords(strtolower(trim($predictionRaw)));
                    $allowedLevels = ['High','Mid High','Medium','Mid Low','Low'];
                    if (!in_array($prediction, $allowedLevels)) {
                        $prediction = 'Others';
                    }

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

        session([
            'demand_high'      => $grouped['High'],
            'demand_mid_high'  => $grouped['Mid High'],
            'demand_medium'    => $grouped['Medium'],
            'demand_mid_low'   => $grouped['Mid Low'],
            'demand_low'       => $grouped['Low'],
            'demand_others'    => $grouped['Others'],
        ]);
        // -------------------------------------------------------
        // 10. RETURN TO VIEW
        // -------------------------------------------------------
        return view('main store.itemActivities.item-demand', [
            'high'      => $grouped['High'],
            'mid_high'  => $grouped['Mid High'],
            'medium'    => $grouped['Medium'],
            'mid_low'   => $grouped['Mid Low'],
            'low'       => $grouped['Low'],
            'others'    => $grouped['Others']
        ]);
    }
}
