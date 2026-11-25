<div class="menu-admin">
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    
    <div class="p-2 text-white-50 small">MASTER DATA</div>
    <a href="{{ route('vendors.index') }}" class="{{ request()->routeIs('vendors.*') ? 'active' : '' }}">
        <i class="fas fa-building"></i> Vendor
    </a>
    <a href="{{ route('materials.index') }}" class="{{ request()->routeIs('materials.*') ? 'active' : '' }}">
        <i class="fas fa-box"></i> Bahan Baku
    </a>
    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
        <i class="fas fa-tshirt"></i> Produk
    </a>
    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i> User Management
    </a>
    
    <div class="p-2 text-white-50 small">GUDANG</div>
    <a href="{{ route('material-in.index') }}" class="{{ request()->routeIs('material-in.*') ? 'active' : '' }}">
        <i class="fas fa-inbox"></i> Material Masuk
    </a>
    <a href="{{ route('material-out.index') }}" class="{{ request()->routeIs('material-out.*') ? 'active' : '' }}">
        <i class="fas fa-outbox"></i> Material Keluar
    </a>
    <a href="{{ route('product-in.index') }}" class="{{ request()->routeIs('product-in.*') ? 'active' : '' }}">
        <i class="fas fa-warehouse"></i> Barang Jadi Masuk
    </a>
    
    <div class="p-2 text-white-50 small">PRODUKSI</div>
    <a href="{{ route('production-orders.index') }}" class="{{ request()->routeIs('production-orders.*') ? 'active' : '' }}">
        <i class="fas fa-clipboard-list"></i> Production Orders
    </a>
    
    <div class="p-2 text-white-50 small">LAPORAN</div>
    <a href="{{ route('admin.reports') }}">
        <i class="fas fa-chart-bar"></i> Semua Laporan
    </a>
</div>