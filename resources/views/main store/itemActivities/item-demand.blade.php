@extends('layouts.main_store_layout')

@section('title', 'Stock Demand Prediction')

@section('content')
<style>
    /* Professional color palette */
    :root {
        --primary-navy: #1e3a5f;
        --primary-blue: #2563eb;
        --accent-teal: #0891b2;
        --bg-light: #f8fafc;
        --bg-white: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --border-light: #e2e8f0;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .main { background: var(--bg-light); min-height: 100vh; padding: 32px; }



    /* Fix white background behind Refresh button */
    .action-bar {
        margin-bottom: 40px;
        background: transparent !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }


    .refresh-btn { 
        padding: 12px 28px; 
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-teal) 100%);
        color: white; 
        border: none; 
        border-radius: 10px; 
        cursor: pointer; 
        font-weight: 700; 
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.3px;
    }
    .refresh-btn:hover { 
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
        background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
    }
    .refresh-btn:active {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    /* Demand Section Cards */
    .demand-section { 
        background: var(--bg-white); 
        border-radius: 16px; 
        padding: 28px; 
        margin-bottom: 28px; 
        box-shadow: var(--shadow-md); 
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
    }
    .demand-section:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    /* Section Titles with Enhanced Icons */
    .section-title { 
        font-size: 22px; 
        font-weight: 700; 
        margin: 0 0 24px 0; 
        padding-bottom: 16px; 
        border-bottom: 3px solid;
        display: flex; 
        align-items: center; 
        gap: 12px; 
        color: var(--text-primary);
        letter-spacing: -0.3px;
    }
    .section-title::before {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 10px;
        font-size: 22px;
        box-shadow: var(--shadow-sm);
    }
    .section-title.high { border-color: #10b981; }
    .section-title.high::before { content: "📈"; background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); }
    .section-title.mid_high { border-color: #3b82f6; }
    .section-title.mid_high::before { content: "⚡"; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); }
    .section-title.medium { border-color: #facc15; }
    .section-title.medium::before { content: "🔹"; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); }
    .section-title.mid_low { border-color: #f97316; }
    .section-title.mid_low::before { content: "🔸"; background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%); }
    .section-title.low { border-color: #ef4444; }
    .section-title.low::before { content: "📉"; background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%); }
    .section-title.others { border-color: #94a3b8; }
    .section-title.others::before { content: "❓"; background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%); }

    /* Scrollable Table Container */
    .table-scroll-container {
        max-height: 450px;
        overflow-y: auto;
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    /* Premium Scrollbar Styling */
    .table-scroll-container::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    .table-scroll-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .table-scroll-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--primary-navy) 0%, var(--accent-teal) 100%);
        border-radius: 10px;
        border: 2px solid #f1f5f9;
    }
    .table-scroll-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--accent-teal) 0%, var(--primary-navy) 100%);
    }

    /* Professional Table Styling */
    .demand-table { 
        width: 100%; 
        border-collapse: separate; 
        border-spacing: 0; 
        background: var(--bg-white);
    }
    .demand-table thead th { 
        background: linear-gradient(135deg, var(--primary-navy) 0%, #2d5a8f 100%);
        color: white; 
        padding: 16px 20px; 
        text-align: left; 
        font-weight: 700; 
        font-size: 12px; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        border: none; 
        position: sticky; 
        top: 0; 
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .demand-table tbody td { 
        padding: 16px 20px; 
        text-align: left; 
        border-bottom: 1px solid var(--border-light); 
        color: var(--text-primary); 
        font-size: 14px;
        font-weight: 500;
    }
    .demand-table tbody tr:last-child td { 
        border-bottom: none; 
    }
    .demand-table tbody tr { 
        transition: all 0.2s ease; 
    }
    .demand-table tbody tr:hover { 
        background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
        transform: scale(1.002);
    }

    /* Premium Demand Badges */
    .demand-badge { 
        display: inline-flex;
        align-items: center;
        padding: 7px 16px; 
        border-radius: 24px; 
        font-weight: 700; 
        font-size: 11px; 
        text-transform: uppercase; 
        letter-spacing: 0.8px;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
    }
    .demand-badge:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }
    .demand-badge.high { 
        background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
        color: white;
    }
    .demand-badge.mid_high { 
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); 
        color: white;
    }
    .demand-badge.medium { 
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
        color: white;
    }
    .demand-badge.mid_low { 
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); 
        color: white;
    }
    .demand-badge.low { 
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); 
        color: white;
    }
    .demand-badge.others { 
        background: linear-gradient(135deg, #64748b 0%, #475569 100%); 
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: var(--text-secondary);
        font-style: italic;
        font-size: 15px;
        background: var(--bg-light);
        border-radius: 8px;
        border: 2px dashed var(--border-light);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main { padding: 20px; }
        .demand-section { padding: 20px; }
        .demand-header h2 { font-size: 26px; }
        .section-title { font-size: 18px; }
        .section-title::before { width: 36px; height: 36px; font-size: 18px; }
        .demand-table thead th, .demand-table tbody td { 
            padding: 12px 16px; 
            font-size: 13px; 
        }
        .table-scroll-container { max-height: 350px; }
        .action-bar { flex-direction: column; gap: 12px; }
    }
</style>

<div class="main">
    <div class="header">
        <h2>Stock Activities Dashboard</h2>
        <p>Home / Stock Activities / Demand Predictions</p>
    </div>

    <!-- Refresh Button -->
    <form method="GET" action="{{ route('demand.predict') }}" class="action-bar">
        <button type="submit" class="refresh-btn">
            <span>🔄</span>
            <span>Refresh Predictions</span>
        </button>
    </form>

    @php
        $demandLevels = [
            'High Demand'      => 'high',
            'Mid-High Demand'  => 'mid_high',
            'Medium Demand'    => 'medium',
            'Mid-Low Demand'   => 'mid_low',
            'Low Demand'       => 'low',
            'Others'           => 'others',
        ];
    @endphp

    @foreach($demandLevels as $level => $cssClass)
        @php $items = $$cssClass ?? []; @endphp
        <div class="demand-section">
            <h3 class="section-title {{ $cssClass }}">{{ $level }}</h3>

            @if(!empty($items))
            <div class="table-scroll-container">
                <table class="demand-table">
                    <thead>
                        <tr>
                            <th>Stock Item</th>
                            <th>Latest Month Qty</th>
                            <th>Average Quantity</th>
                            <th>Demand Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td><strong>{{ $item['stock'] }}</strong></td>
                            <td>{{ number_format($item['total_quantity']) }}</td>
                            <td>{{ number_format($item['avg_quantity'], 2) }}</td>
                            <td><span class="demand-badge {{ $cssClass }}">{{ $item['demand'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                No items found for {{ $level }}
            </div>
            @endif
        </div>
    @endforeach
</div>
@endsection