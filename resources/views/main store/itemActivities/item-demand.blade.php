@extends('layouts.main_store_layout')

@section('title', 'Stock Demand Prediction')

@section('content')
<style>
    /* Existing CSS retained */
    .demand-header { margin-bottom: 32px; }
    .demand-header h2 { margin: 0 0 4px 0; font-size: 28px; color: #0f3e59; font-weight: 600; }
    .demand-header p { margin: 0; color: #6c757d; font-size: 14px; }
    .demand-section { background: white; border-radius: 12px; padding: 24px; margin-bottom: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #e9ecef; }
    .section-title { font-size: 20px; font-weight: 600; margin: 0 0 20px 0; padding-bottom: 12px; border-bottom: 2px solid #e9ecef; display: flex; align-items: center; gap: 10px; color: #0f3e59; }
    .section-title.high::before { content: "📈"; font-size: 24px; }
    .section-title.mid_high::before { content: "⚡"; font-size: 24px; }
    .section-title.medium::before { content: "🔹"; font-size: 24px; }
    .section-title.mid_low::before { content: "🔸"; font-size: 24px; }
    .section-title.low::before { content: "📉"; font-size: 24px; }
    .section-title.others::before { content: "❓"; font-size: 24px; }

    /* Scrollable table container */
    .table-scroll-container {
        max-height: 400px;
        overflow-y: auto;
        overflow-x: auto;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    /* Custom scrollbar styling */
    .table-scroll-container::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-scroll-container::-webkit-scrollbar-thumb {
        background: #0f3e59;
        border-radius: 4px;
    }

    .table-scroll-container::-webkit-scrollbar-thumb:hover {
        background: #0a2d42;
    }

    .demand-table { width: 100%; border-collapse: separate; border-spacing: 0; background: #fff; }
    .demand-table thead th { background-color: #0f3e59; color: white; padding: 14px 16px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border: none; position: sticky; top: 0; z-index: 10; }
    .demand-table tbody td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #e9ecef; color: #212529; font-size: 14px; }
    .demand-table tbody tr:last-child td { border-bottom: none; }
    .demand-table tbody tr { transition: all 0.2s ease; }
    .demand-table tbody tr:hover { background-color: #f8f9fa; }

    .demand-badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
    .demand-badge.high { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.25); }
    .demand-badge.mid_high { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.25); }
    .demand-badge.medium { background: linear-gradient(135deg, #facc15 0%, #eab308 100%); color: white; box-shadow: 0 2px 4px rgba(250, 204, 21, 0.25); }
    .demand-badge.mid_low { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; box-shadow: 0 2px 4px rgba(249, 115, 22, 0.25); }
    .demand-badge.low { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.25); }
    .demand-badge.others { background: #6c757d; color: white; box-shadow: 0 2px 4px rgba(108, 117, 125, 0.25); }

    @media (max-width: 768px) {
        .demand-section { padding: 16px; }
        .demand-table thead th, .demand-table tbody td { padding: 10px 12px; font-size: 13px; }
        .section-title { font-size: 18px; }
        .table-scroll-container { max-height: 300px; }
    }

    .refresh-btn { margin-bottom: 20px; padding: 10px 18px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.2s ease; }
    .refresh-btn:hover { background: #2563eb; }
</style>

<div class="main">
    <div class="demand-header">
        <h2>Stock Activities</h2>
        <p>Home / Stock Activities</p>
    </div>

    <!-- Refresh Predictions Button -->
    <form method="GET" action="{{ route('demand.predict') }}">
        <button type="submit" class="refresh-btn">🔄 Refresh Predictions</button>
    </form>

    @php
        $demandLevels = [
            'High'      => 'high',
            'Mid High'  => 'mid_high',
            'Medium'    => 'medium',
            'Mid Low'   => 'mid_low',
            'Low'       => 'low',
            'Others'    => 'others',
        ];
    @endphp

    @foreach($demandLevels as $level => $cssClass)
        @php $items = $$cssClass ?? []; @endphp
        <div class="demand-section">
            <h3 class="section-title {{ $cssClass }}">{{ $level }} Demand Items</h3>

            @if(!empty($items))
            <div class="table-scroll-container">
                <table class="demand-table">
                    <thead>
                        <tr>
                            <th>Stock</th>
                            <th>Total Quantity (By latest month)</th>
                            <th>Overall Average Quantity</th>
                            <th>Demand</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item['stock'] }}</td>
                            <td>{{ $item['total_quantity'] }}</td>
                            <td>{{ number_format($item['avg_quantity'], 2) }}</td>
                            <td><span class="demand-badge {{ $cssClass }}">{{ $item['demand'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p style="color: #6c757d; font-style: italic;">No items found for {{ $level }} demand.</p>
            @endif
        </div>
    @endforeach
</div>
@endsection