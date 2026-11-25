<div class="menu-gudang">
    <a href="{{ route('gudang.dashboard') }}" class="{{ request()->routeIs('gudang.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    
    <div class="p-2 text-white-50 small">TRANSAKSI</div>
    <a href="{{ route('gudang.material-in.index') }}" class="{{ request()->routeIs('gudang.material-in.*') ? 'active' : '' }}">
        <i class="fas fa-inbox"></i> Material Masuk
    </a>
    <a href="{{ route('gudang.material-out.index') }}" class="{{ request()->routeIs('gudang.material-out.*') ? 'active' : '' }}">
        <i class="fas fa-outbox"></i> Material Keluar
    </a>
    <a href="{{ route('gudang.product-in.index') }}" class="{{ request()->routeIs('gudang.product-in.*') ? 'active' : '' }}">
        <i class="fas fa-warehouse"></i> Barang Jadi Masuk
    </a>
    
    <div class="p-2 text-white-50 small">PERMINTAAN</div>
    <a href="{{ route('gudang.requests') }}" class="{{ request()->routeIs('gudang.requests') ? 'active' : '' }}">
        <i class="fas fa-bell"></i> Request Bahan
        @php
            $pendingRequests = \App\Models\ProductionOrder::where('status', 'waiting_material')->count();
        @endphp
        @if($pendingRequests > 0)
            <span class="badge bg-danger float-end">{{ $pendingRequests }}</span>
        @endif
    </a>
    
    <div class="p-2 text-white-50 small">DATA</div>
    <a href="#">
        <i class="fas fa-building"></i> Vendor
    </a>
    <a href="#">
        <i class="fas fa-box"></i> Bahan Baku
    </a>
    <a href="#">
        <i class="fas fa-tshirt"></i> Produk
    </a>
    
    <div class="p-2 text-white-50 small">LAPORAN</div>
    <a href="{{ route('gudang.reports.stocks') }}">
        <i class="fas fa-chart-bar"></i> Laporan Stok
    </a>
</div>