<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            color: #2c3e50;
        }

        .sidebar {
            position: fixed;
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #0a2e43 0%, #0f3e59 100%);
            color: white;
            padding: 0;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .sidebar h2 {
            text-align: center;
            padding: 30px 20px;
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            letter-spacing: 1px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 16px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-size: 15px;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left-color: #4fc3f7;
            padding-left: 30px;
        }

        .main {
            margin-left: 250px;
            padding: 40px;
            min-height: 100vh;
        }

        .header {
            margin-bottom: 35px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e6ed;
        }

        .header h2 {
            margin: 0 0 8px 0;
            font-size: 28px;
            color: #0f3e59;
            font-weight: 600;
        }

        .header p {
            margin: 0;
            color: #7f8c9a;
            font-size: 14px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .card {
            background: white;
            padding: 28px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4fc3f7 0%, #2196f3 100%);
        }

        .card:nth-child(2)::before {
            background: linear-gradient(90deg, #66bb6a 0%, #43a047 100%);
        }

        .card:nth-child(3)::before {
            background: linear-gradient(90deg, #ffa726 0%, #fb8c00 100%);
        }

        .card:nth-child(4)::before {
            background: linear-gradient(90deg, #ef5350 0%, #e53935 100%);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .card h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            color: #7f8c9a;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .card h2 {
            margin: 0 0 10px 0;
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
        }

        .card p {
            margin: 0;
            color: #95a5a6;
            font-size: 14px;
        }

        .summary-alerts {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .summary,
        .alerts {
            background: white;
            flex: 1;
            min-width: 300px;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .summary h3,
        .alerts h3 {
            margin: 0 0 25px 0;
            font-size: 20px;
            color: #0f3e59;
            font-weight: 600;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e6ed;
        }

        .chart-placeholder {
            height: 200px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c9a;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
            font-size: 14px;
        }

        table th, table td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #e0e6ed;
        }

        table th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-weight: 600;
            color: #0f3e59;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        table tbody tr {
            transition: background-color 0.2s ease;
        }

        table tbody tr:hover {
            background-color: #f8f9fa;
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        .logout-btn {
            position: absolute;
            bottom: 30px;
            left: 25px;
            right: 25px;
            text-align: center;
        }

        .logout-btn a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 14px;
            padding: 12px;
            display: block;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .logout-btn a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }

        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-expired {
            background-color: #ffebee;
            color: #c62828;
        }
        .table-scroll {
            max-height: 300px;
            overflow-y: auto;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #e0e6ed;
        }

        .card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .card-link .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            cursor: pointer;
        }

        

        @media (max-width: 1200px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            
            .main {
                margin-left: 200px;
                padding: 20px;
            }
            
            .cards {
                grid-template-columns: 1fr;
            }
            
            .summary-alerts {
                flex-direction: column;
            }

        }
    </style>
</head>
<body>
    {{-- Include sidebar --}}
    @include('components.sidebar')

    @yield('content')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const expiredCount = {{ $expiredCount ?? 0 }};
            const lowStockCount = {{ $lowStockItems ?? 0 }};

            if (expiredCount > 0 || lowStockCount > 0) {
                let message = 'Attention Required:\n';
                if (expiredCount > 0) {
                    message += `• ${expiredCount} expired item(s)\n`;
                }
                if (lowStockCount > 0) {
                    message += `• ${lowStockCount} low stock item(s)`;
                }

                Swal.fire({
                    icon: 'warning',
                    title: 'Inventory Alert',
                    text: message,
                    confirmButtonColor: '#0f3e59',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
</body>
</html>
@extends('layouts.main_store_layout')

@section('title', 'Main Store Dashboard - INTELLIMEDS')

@section('content')
<div class="main">

    <!-- HEADER -->
    <div class="header">
        <h2>Welcome to INTELLIMEDS</h2>
        <p>Home / Dashboard</p>
    </div>

    <!-- KPI CARDS -->
    <div class="cards">

        <a href="{{ route('items.list') }}" class="card-link">
            <div class="card">
                <h4>TOTAL STOCK</h4>
                <h2>{{ $stockReceived ?? '--' }}</h2>
                <p>Total Received</p>
            </div>
        </a>

        <a href="{{ route('stock.transfer.list') }}" class="card-link">
            <div class="card">
                <h4>STOCK TRANSFERRED</h4>
                <h2>{{ $stockTransferred ?? '--' }}</h2>
                <p>Total Transferred</p>
            </div>
        </a>

        <div class="card">
            <h4>LOW STOCK ITEMS</h4>
            <h2 style="color:#fb8c00">{{ $lowStockItems ?? '--' }}</h2>
            <p>Items</p>
        </div>

        <div class="card">
            <h4>EXPIRED ITEMS</h4>
            <h2 style="color:#e53935">{{ $expiredCount ?? '--' }}</h2>
            <p>Total Expired</p>
        </div>

    </div>

    <!-- ================= DEMAND VISUALIZATION ================= -->
    <div class="summary-alerts" style="margin-top:40px;">

        <!-- DEMAND DISTRIBUTION -->
        <div class="summary">
            <h3>Stock Demand Distribution</h3>
            <canvas id="demandChart" height="200"></canvas>
        </div>

        <!-- TOP HIGH DEMAND -->
        <div class="summary">
            <h3>Top 5 High Demand Supplied Items (Prev.Month)</h3>
            <canvas id="topDemandChart" height="200"></canvas>
        </div>

    </div>
    <!-- ================= STOCK SUMMARY (HORIZONTAL BAR) ================= -->
    <div class="summary-alerts" style="margin-top:40px;">
        <div class="summary">
            <h3>Stock Summary Overview</h3>
            <canvas id="stockSummaryChart" height="180"></canvas>
        </div>
    </div>

    <!-- LOW STOCK TABLE -->
    <div class="alerts" style="margin-top:40px;">
        <h3>Low Stock Items</h3>
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Stock ID</th>
                        <th>In Stock</th>
                        <th>Min Level</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($lowStockList ?? [] as $item)
                    <tr>
                        <td>{{ $item->i_name }}</td>
                        <td><span class="status-badge status-expired">{{ $item->i_stockID }}</span></td>
                        <td style="color:#e53935;font-weight:600">{{ $item->quantity_in_stock }}</td>
                        <td>{{ $item->i_minLevel }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;color:#7f8c9a;padding:30px;">
                            No low stock items.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- EXPIRED ITEMS -->
    <div class="summary-alerts" style="margin-top:40px;">
        <div class="alerts">
            <h3>List of Expired Items</h3>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Stock ID</th>
                            <th>Batch Number</th>
                            <th>Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($expiredItems ?? [] as $item)
                        <tr>
                            <td>{{ $item->i_name }}</td>
                            <td><span class="status-badge status-expired">{{ $item->i_stockID }}</span></td>
                            <td>{{ $item->grn_itemBatchNumber }}</td>
                            <td>{{ $item->grn_itemExpiredDate }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:#7f8c9a;padding:30px;">
                                No expired items.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ================= SCRIPTS ================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    /* DEMAND DISTRIBUTION */
    const demandCtx = document.getElementById('demandChart');
    new Chart(demandCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($demandStats ?? [])) !!},
            datasets: [{
                data: {!! json_encode(array_values($demandStats ?? [])) !!}
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    /* TOP HIGH DEMAND */
    const topCtx = document.getElementById('topDemandChart');

    new Chart(topCtx, {
        type: 'bar',
        data: {
            labels: ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5'],
            datasets: [{
                label: 'Total Quantity',
                data: {!! json_encode(collect($topHighDemand ?? [])->pluck('total_quantity')) !!}
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const names = {!! json_encode(collect($topHighDemand ?? [])->pluck('stock')) !!};
                            return names[context.dataIndex] + ': ' + context.raw;
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: { display: false } // 🔥 hide long names
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    /*Horizontal Bar - STOCK SUMMARY OVERVIEW with Enhanced Styling*/
    const stockSummaryCtx = document.getElementById('stockSummaryChart');

    new Chart(stockSummaryCtx, {
        type: 'bar',
        data: {
            labels: ['Total Stock', 'Healthy Stock', 'Expired Stock'],
            datasets: [{
                data: [
                    {{ $totalStock ?? 0 }},
                    {{ $healthyStock ?? 0 }},
                    {{ $expiredStockQty ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(33, 150, 243, 0.8)',   // Blue for Total Stock
                    'rgba(102, 187, 106, 0.8)',  // Green for Healthy Stock
                    'rgba(239, 83, 80, 0.8)'     // Red for Expired Stock
                ],
                borderColor: [
                    'rgba(33, 150, 243, 1)',
                    'rgba(102, 187, 106, 1)',
                    'rgba(239, 83, 80, 1)'
                ],
                borderWidth: 2,
                borderRadius: 12,
                barPercentage: 0.6,
                categoryPercentage: 0.7
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13
                    },
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw.toLocaleString() + ' units';
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        precision: 0,
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        color: '#7f8c9a',
                        padding: 8
                    },
                    border: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 14,
                            weight: '600'
                        },
                        color: '#0f3e59',
                        padding: 12
                    },
                    border: {
                        display: false
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    bottom: 10
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });

</script>

@endsection
