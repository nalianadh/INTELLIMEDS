@extends('layouts.main_store_layout')

@section('title', 'Stock Request - Request List')

@section('content')
<div class="main">
    <div class="header">
        <h2>Welcome To INTELLIMEDS</h2>
        <p>Home / Stock Request - Request List</p>
    </div>

    <div style="margin-bottom:24px;">
        <form method="GET" action="{{ route('stock-request.pending') }}"
            style="display: flex; align-items: center; background: #cbd9e5; padding: 8px; border-radius: 12px; max-width: 900px;">
            <input type="text" name="search"
                placeholder="Search Request ID"
                style="flex: 1; padding: 10px 16px; font-size: 1rem; border: 1px solid #00c389; border-right: none;
                        border-radius: 8px 0 0 8px; outline: none; background: white; height: 40px; box-sizing: border-box;" />

            <button type="submit"
                    style="background: #1900cc; color: white; border: 1px solid #1900cc;
                        border-radius: 0 8px 8px 0; padding: 0 16px; height: 40px;
                        display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>


    <table class="table" style="width:100%; background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.04); margin-bottom:24px;">
        <thead style="background:#e3f0fc;">
            <tr>
                <th>#</th>
                <th>DATE REQUESTED</th>
                <th>DEPARTMENT</th>
                <th>STATUS</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingRequests as $index => $request)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $request->rq_date_requested }}</td>
                    <td>{{ $request->requestedBy->u_name ?? 'Unknown' }}</td>
                    <td>
                        <span class="status-badge pending">{{ $request->rq_status }}</span>
                    </td>
                    <td>
                        <a href="{{ route('stock-request.view', ['id' => $request->request_id]) }}">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No pending stock requests found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Optional: Add pagination --}}
    {{-- {{ $pendingRequests->links() }} --}}

    {{-- Optional: Section for showing selected request details in future --}}
    {{-- You can implement a detail view for each row later --}}
</div>
@endsection
