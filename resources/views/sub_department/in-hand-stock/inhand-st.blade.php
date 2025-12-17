@extends('layouts.subdept-layout')

@section('title', 'In-Hand Stock')
@section('page_title', 'Sub Department In-Hand Stock')
@section('breadcrumb', 'Home / In-Hand Stock')

@section('content')
<div style="background: #fff; padding: 20px; border-radius: 8px;">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr>
                <th style="border-bottom: 1px solid #ccc; padding: 8px;">Item Name</th>
                <th style="border-bottom: 1px solid #ccc; padding: 8px; text-align:center;">Quantity In Hand</th>
            </tr>
        </thead>
        <tbody>
            @forelse($finalStock as $stock)
                <tr>
                    <td style="padding: 8px;">{{ $stock->i_name }}</td>

                    <td style="padding: 8px; text-align:center;">
                        {{ abs($stock->sd_quantityInHand) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding: 8px;">No in-hand stock available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
