<div class="sidebar">
    <h2>
        <img src="{{ asset('images/box-icon.png') }}" alt="IntelliMeds Logo" class="sidebar-logo">
        INTELLIMEDS
    </h2>
    <a class="active" href="{{ route('mainstore.dashboard') }}">
        <i class="fas fa-home"></i> Home
    </a>

    {{-- Inbox with badge --}}
    <a href="{{ route('mainstore.inbox') }}" 
        class="{{ request()->routeIs('mainstore.inbox') ? 'active' : '' }}" 
        style="position: relative;">
        <i class="fas fa-inbox"></i> Inbox
    </a>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle" onclick="event.preventDefault();this.nextElementSibling.classList.toggle('show');">
            <i class="fas fa-box"></i> Item Register &#9662;
        </a>
        <div class="dropdown-menu">
            <a href="{{ route('items.create') }}"><i class="fas fa-plus-circle"></i> Add Item</a>
            <a href="{{ route('items.list') }}"><i class="fas fa-list"></i> Item List</a>
        </div>
    </div>

    <a href="{{ route('demand.predict') }}">
        <i class="fas fa-history"></i> Stock Activities
    </a>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle" onclick="event.preventDefault();this.nextElementSibling.classList.toggle('show');">
            <i class="fas fa-truck-loading"></i> Stock Receive &#9662;
        </a>
        <div class="dropdown-menu">
            <a href="{{ route('stock.receive') }}"><i class="fas fa-file-invoice"></i> Add GRN</a>
            <a href="{{ route('stock.receive.grnlist') }}"><i class="fas fa-clipboard-list"></i> GRN List</a>
        </div>
    </div>

    {{-- Stock Request with badge --}}
    <a href="{{ route('stock.request.list') }}" 
       class="{{ request()->routeIs('stock.request.list') ? 'active' : '' }}" 
       style="position: relative;">
        <i class="fas fa-hand-paper"></i> Stock Request
        @if(!empty($pendingRequests) && $pendingRequests > 0)
            <span class="badge">{{ $pendingRequests }}</span>
        @endif
    </a>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle" onclick="event.preventDefault();this.nextElementSibling.classList.toggle('show');">
            <i class="fas fa-exchange-alt"></i> Stock Transfer &#9662;
        </a>
        <div class="dropdown-menu">
            <a href="{{ route('stock.transfer.in') }}"><i class="fas fa-arrow-circle-down"></i> Transfer In</a>
            <a href="{{ route('stock.transfer.out') }}"><i class="fas fa-arrow-circle-up"></i> Transfer Out</a>
            <a href="{{ route('stock.transfer.list') }}"><i class="fas fa-list-alt"></i> Transfer List</a>
        </div>
    </div>

    <!--a href="#">Stock Adjustment</a-->

    <div class="logout-btn">
        <a href="{{ route('logout') }}">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<style>
.dropdown { position: relative; }
.dropdown-menu {
    display: none;
    position: absolute;
    left: 0;
    top: 100%;
    background: #fff;
    min-width: 180px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    z-index: 10;
    border-radius: 0 0 6px 6px;
    overflow: hidden;
}
.dropdown-menu a {
    display: block;
    padding: 10px 20px;
    color: #20425c;
    text-decoration: none;
    background: #f9f9f9;
}
.dropdown-menu a:hover {
    background: #e6e6e6;
}
.dropdown .show {
    display: block;
}
.dropdown-toggle {
    cursor: pointer;
}

/* ✅ Badge style */
.badge {
    background: #d6336c;
    color: white;
    font-size: 0.75rem;
    font-weight: bold;
    border-radius: 50%;
    padding: 3px 7px;
    margin-left: 6px;
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
}

/* Icon spacing */
.sidebar a i {
    margin-right: 8px;
    width: 18px;
    text-align: center;
}

/* Logo styling */
.sidebar-logo {
    height: 24px;
    width: auto;
    margin-right: 10px;
    vertical-align: middle;
}

.sidebar h2 {
    display: flex;
    align-items: center;
}
</style>