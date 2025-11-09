@extends('layouts.subdept-layout')

@section('title', 'View Inbox Message')
@section('page_title', 'Inbox Message')
@section('breadcrumb', 'Home / Inbox / View')

@section('content')
<div style="max-width:970px; margin:auto; background:#fff; border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,0.04); padding:32px;">
    <div style="max-width:965px; margin:auto; background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.1); padding:24px;">

        {{-- Decide which view to include --}}
        @if(isset($transfer))
            @include('sub_department.inbox_SD.view-inbox-transfer', ['transfer' => $transfer])
        @elseif(isset($requestData))
            @include('sub_department.inbox_SD.view-inbox-request', ['requestData' => $requestData])
        @else
            <p style="color:red;">⚠ No valid message found.</p>
        @endif

    </div>
</div>
@endsection
