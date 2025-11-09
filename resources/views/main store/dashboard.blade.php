<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f6f9fc;
        }

        .sidebar {
            position: fixed;
            width: 200px;
            height: 100vh;
            background-color: #0f3e59;
            color: white;
            padding: 20px 0;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background-color: #14506e;
        }

        .main {
            margin-left: 200px;
            padding: 30px;
        }

        .header {
            margin-bottom: 20px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .summary-alerts {
            display: flex;
            gap: 20px;
        }

        .summary,
        .alerts {
            background-color: white;
            flex: 1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .summary h3,
        .alerts h3 {
            margin-top: 0;
        }

        .chart-placeholder {
            height: 200px;
            background: #f0f0f0;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #eaeaea;
        }

        .logout-btn {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
        }

        .logout-btn a {
            color: #fff;
            text-decoration: underline;
            font-size: 14px;
        }
    </style>
</head>
<body>
    {{-- Include sidebar --}}
    @include('components.sidebar')

    @yield('content')

</body>
</html>
@extends('layouts.main_store_layout')

@section('title', 'Main Store Dashboard - INTELLIMEDS')

@section('content')
<div class="main">
    <div class="header">
        <h2>Welcome to INTELLIMEDS</h2>
        <p>Home / Dashboard</p>
    </div>
    <div class="cards">
        <div class="card">
            <h4>STOCK RECEIVED</h4>
            <h2>{{ $stockReceived ?? '--' }}</h2>
            <p>Total Received</p>
        </div>
        <div class="card">
            <h4>STOCK TRANSFERRED</h4>
            <h2>{{ $stockTransferred ?? '--' }}</h2>
            <p>Total Transferred</p>
        </div>
        <div class="card">
            <h4>LOW STOCK ITEMS</h4>
            <h2 style="color: orange;">{{ $lowStockCount ?? '--' }}</h2>
            <p>Items</p>
        </div>
        <div class="card">
            <h4>EXPIRED ITEMS</h4>
            <h2 style="color: red;">{{ $expiredCount ?? '--' }}</h2>
            <p>Total Expired</p>
        </div>
    </div>

    <div class="summary-alerts" style="margin-top:40px;">
        <div class="alerts" style="flex:1;">
            <h3>List of Expired Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Stock</th>
                        <th>Expiry Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expiredItems ?? [] as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td style="color:red;">{{ $item->stock }}</td>
                            <td>{{ $item->expiry_date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No expired items.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
