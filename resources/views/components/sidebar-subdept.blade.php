<div class="sidebar" style="background:#226699; color:#fff;">
    <h2>INTELLIMEDS</h2>
    <a href="{{ route('subdept.dashboard') }}" class="{{ request()->routeIs('subdept.dashboard') ? 'active' : '' }}">Home</a>
    <a href="{{ route('subdept.inhand.stock') }}" class="{{ request()->routeIs('subdept.inhand.stock') ? 'active' : '' }}">In-Hand Stock</a>
    <a href="{{ route('subdept.request') }}" class="{{ request()->routeIs('subdept.request') ? 'active' : '' }}">Stock Request</a>
    <a href="{{ route('stock.transfer.subdept') }}" class="{{ request()->routeIs('stock.transfer.subdept') ? 'active' : '' }}">Stock Transfer</a>
    <a href="{{ route('subdept.inbox') }}">Inbox</a>

    <div class="logout-btn">
        <a href="{{ route('logout') }}">Logout</a>
    </div>
</div>
