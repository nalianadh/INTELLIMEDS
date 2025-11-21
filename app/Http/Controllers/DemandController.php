<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SupplyTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class DemandController extends Controller
{
    public function predictDemand($stockName) // pass the Stock name
    {
        // Fetch all supply transactions for this stock
        $transactions = SupplyTransaction::where('Stock', $stockName)->get();

        if ($transactions->isEmpty()) {
            return "No transactions found for stock: {$stockName}";
        }

        // Calculate features for the Random Forest model
        $num_entries = $transactions->count();
        $total_quantity = $transactions->sum('Quantity');
        $avg_quantity = $transactions->avg('Quantity');

        // Send features to Python API
        $response = Http::post('http://localhost:5000/predict', [
            'num_entries' => $num_entries,
            'total_quantity' => $total_quantity,
            'avg_quantity' => $avg_quantity
        ]);

        $prediction = $response->json()['prediction'];

        return "Predicted demand for {$stockName}: " . $prediction;
    }
    
    public function predictAllDemand()
    {
        // Get distinct stock names
        $stocks = SupplyTransaction::select('Stock')->distinct()->pluck('Stock');

        $results = [];

        foreach ($stocks as $stockName) {
            // Fetch transactions for this stock
            $transactions = SupplyTransaction::where('Stock', $stockName)->get();

            if ($transactions->isEmpty()) continue;

            // 1. Number of entries
            $num_entries = $transactions->count();

            // 2. Total quantity
            $total_quantity = $transactions->sum('Quantity');

            // 3. Average quantity per month
            $avg_quantity_per_month = SupplyTransaction::select(
                    DB::raw('YEAR(Date) as year'),
                    DB::raw('MONTH(Date) as month'),
                    DB::raw('AVG(Quantity) as avg_quantity')
                )
                ->where('Stock', $stockName)
                ->groupBy('year', 'month')
                ->get();

            // Overall average across months
            $avg_quantity = $avg_quantity_per_month->avg('avg_quantity');

            // Send features to Python API
            $response = Http::post('http://localhost:5000/predict', [
                'num_entries' => $num_entries,
                'total_quantity' => $total_quantity,
                'avg_quantity' => $avg_quantity
            ]);

            $prediction = $response->json()['prediction'];

            // Store result
            $results[] = [
                'stock' => $stockName,
                'num_entries' => $num_entries,
                'total_quantity' => $total_quantity,
                'avg_quantity' => $avg_quantity,
                'demand' => $prediction
            ];
        }

        // Separate High and Low demand
        $high = array_filter($results, fn($r) => $r['demand'] === 'High Demand');
        $low = array_filter($results, fn($r) => $r['demand'] === 'Low Demand');

        // -----------------------------
        // PAGINATION
        // -----------------------------
        $perPage = 10;

        // Paginate High Demand items
        $highCollection = collect($high);
        $highCurrentPage = request()->get('high_page', 1);
        $highPaginator = new LengthAwarePaginator(
            $highCollection->forPage($highCurrentPage, $perPage),
            $highCollection->count(),
            $perPage,
            $highCurrentPage,
            ['path' => request()->url(), 'query' => ['low_page' => request()->get('low_page', 1)]]
        );

        // Paginate Low Demand items
        $lowCollection = collect($low);
        $lowCurrentPage = request()->get('low_page', 1);
        $lowPaginator = new LengthAwarePaginator(
            $lowCollection->forPage($lowCurrentPage, $perPage),
            $lowCollection->count(),
            $perPage,
            $lowCurrentPage,
            ['path' => request()->url(), 'query' => ['high_page' => request()->get('high_page', 1)]]
        );

        // Return view with paginated data
        return view('main store.itemActivities.item-demand', [
            'high' => $highPaginator,
            'low' => $lowPaginator
        ]);
    }



}
