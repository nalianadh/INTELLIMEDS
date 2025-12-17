<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/box-icon.png') }}" alt="Logo" class="sidebar-logo">
        <h2>INTELLIMEDS</h2>
        <div class="subtitle">Sub Department System</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <a href="{{ route('subdept.dashboard') }}" class="{{ request()->routeIs('subdept.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Inventory</div>

            <a href="{{ route('subdept.inhand.stock') }}" class="{{ request()->routeIs('subdept.inhand.stock') ? 'active' : '' }}">
                <i class="fas fa-warehouse"></i>
                <span>In-Hand Stock</span>
            </a>

            <a href="{{ route('subdept.request') }}" class="{{ request()->routeIs('subdept.request') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                <span>Stock Request</span>
            </a>

            <a href="{{ route('stock.transfer.subdept') }}" class="{{ request()->routeIs('stock.transfer.subdept') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i>
                <span>Stock Transfer</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Communication</div>
            <a href="{{ route('subdept.inbox') }}" class="{{ request()->routeIs('subdept.inbox') ? 'active' : '' }}">
                <i class="fas fa-inbox"></i>
                <span>Inbox</span>
            </a>
        </div>

        <div class="nav-section">
            <a href="{{ route('logout') }}">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</div>
