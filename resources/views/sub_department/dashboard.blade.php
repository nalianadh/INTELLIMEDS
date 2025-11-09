@extends('layouts.subdept-layout')

@section('title', 'Sub Department Dashboard - INTELLIMEDS')
@section('page_title', 'Welcome Sub Department')
@section('breadcrumb', 'Home / Dashboard')

@section('content')
<div class="cards" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
    <div class="card" style="background-color: #ffffff; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h4>STOCK REQUESTED</h4>
        <h2>--</h2>
        <p>Pending</p>
    </div>
    <div class="card" style="background-color: #ffffff; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h4>RECEIVED STOCKS</h4>
        <h2>--</h2>
        <p>Completed</p>
    </div>
    <div class="card" style="background-color: #ffffff; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h4>LOW STOCK ITEMS</h4>
        <h2 style="color: red;">--</h2>
    </div>
</div>
@endsection
