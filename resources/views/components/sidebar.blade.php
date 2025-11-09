<div class="sidebar">
    <h2>INTELLIMEDS</h2>
    <a class="active" href="{{ route('mainstore.dashboard') }}">Home</a>

    {{-- Inbox with badge --}}
    <a href="{{ route('mainstore.inbox') }}" 
        class="{{ request()->routeIs('mainstore.inbox') ? 'active' : '' }}" 
        style="position: relative;">
        Inbox
        @if(!empty($unreadInbox) && $unreadInbox > 0)
            <span class="badge">{{ $unreadInbox }}</span>
        @endif
    </a>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle" onclick="event.preventDefault();this.nextElementSibling.classList.toggle('show');">
            Item Register &#9662;
        </a>
        <div class="dropdown-menu">
            <a href="{{ route('items.create') }}">Add Item</a>
            <a href="{{ route('items.list') }}">Item List</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle" onclick="event.preventDefault();this.nextElementSibling.classList.toggle('show');">
            Stock Receive &#9662;
        </a>
        <div class="dropdown-menu">
            <a href="{{ route('stock.receive') }}">Add GRN</a>
            <a href="{{ route('stock.receive.grnlist') }}">GRN List</a>
        </div>
    </div>

    {{-- Stock Request with badge --}}
    <a href="{{ route('stock.request.list') }}" 
       class="{{ request()->routeIs('stock.request.list') ? 'active' : '' }}" 
       style="position: relative;">
        Stock Request
        @if(!empty($unreadRequests) && $unreadRequests > 0)
            <span class="badge">{{ $unreadRequests }}</span>
        @endif
    </a>

    <div class="dropdown">
        <a href="#" class="dropdown-toggle" onclick="event.preventDefault();this.nextElementSibling.classList.toggle('show');">
            Stock Transfer &#9662;
        </a>
        <div class="dropdown-menu">
            <a href="{{ route('stock.transfer.in') }}">Transfer In</a>
            <a href="{{ route('stock.transfer.out') }}">Transfer Out</a>
            <a href="{{ route('stock.transfer.list') }}">Transfer List</a>
        </div>
    </div>

    <!--a href="#">Stock Adjustment</a-->
    <a href="#">Reports</a>

    <div class="logout-btn">
        <a href="{{ route('logout') }}">Logout</a>
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
</style>
