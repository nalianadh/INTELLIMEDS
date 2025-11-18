@extends('layouts.main_store_layout')

@section('title', 'Report')

@section('content')
<div class="main">
    <div class="header">
        <h2>Report</h2>
        <p>Home / Report</p>
    </div>
    <div class="report-menu" style="display: flex; flex-direction: column; gap: 20px; margin-top: 24px;">
        <a href="{{ route('reports.supply-transaction') }}" style="padding: 15px; background-color: #f0f0f0; border-radius: 8px; text-decoration: none; color: #000;">Supply Transaction Report</a>
        <a href="#" style="padding: 15px; background-color: #f0f0f0; border-radius: 8px; text-decoration: none; color: #000;">Forecasting</a>
        <a href="{{ route('reports.stock-request.list') }}" style="padding: 15px; background-color: #f0f0f0; border-radius: 8px; text-decoration: none; color: #000;">Stock Request</a>
    </div>
</div>
@endsection