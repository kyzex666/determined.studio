<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Sistem Manajemen Produksi')

@section('content')
<!-- Role-specific welcome message -->
@if(auth()->user()->role === 'admin')
<div class="alert alert-primary">
    <h5><i class="fas fa-crown me-2"></i> Selamat datang, Administrator!</h5>
    <p class="mb-0">Anda memiliki akses penuh ke semua fitur sistem.</p>
</div>
@elseif(auth()->user()->role === 'produksi')
<div class="alert alert-warning">
    <h5><i class="fas fa-industry me-2"></i> Selamat datang, Staff Produksi!</h5>
    <p class="mb-0">Anda dapat mengelola production orders dan request bahan ke gudang.</p>
</div>
@elseif(auth()->user()->role === 'gudang')
<div class="alert alert-success">
    <h5><i class="fas fa-warehouse me-2"></i> Selamat datang, Staff Gudang!</h5>
    <p class="mb-0">Anda dapat mengelola stok bahan dan barang jadi.</p>
</div>
@endif

<!-- Stats Cards -->
<div class="row mb-4">
    <!-- Bahan Baku Card (Admin & Gudang) -->
    @if(in_array(auth()->user()->role, ['admin', 'gudang']))
    <div class="col-md-3">
        <div class="card stat-card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Bahan Baku</h6>
                        <h3 class="mb-0">24</h3>
                        <small class="text-danger">
                            <i class="fas fa-exclamation-circle"></i> 3 low stock
                        </small>
                    </div>
                    <div class="bg-primary text-white rounded-circle p-3">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Produk Jadi Card (All Roles) -->
    <div class="col-md-3">
        <div class="card stat-card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Produk Jadi</h6>
                        <h3 class="mb-0">156</h3>
                        <small class="text-success">
                            <i class="fas fa-arrow-up"></i> 12% dari bulan lalu
                        </small>
                    </div>
                    <div class="bg-success text-white rounded-circle p-3">
                        <i class="fas fa-tshirt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Production Orders Card (Admin & Produksi) -->
    @if(in_array(auth()->user()->role, ['admin', 'produksi']))
    <div class="col-md-3">
        <div class="card stat-card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Active Production Orders</h6>
                        <h3 class="mb-0">8</h3>
                        <small class="text-warning">
                            <i class="fas fa-clock"></i> 3 menunggu bahan
                        </small>
                    </div>
                    <div class="bg-warning text-white rounded-circle p-3">
                        <i class="fas fa-industry fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Vendor Card (Admin Only) -->
    @if(auth()->user()->role === 'admin')
    <div class="col-md-3">
        <div class="card stat-card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Vendor</h6>
                        <h3 class="mb-0">7</h3>
                        <small class="text-info">
                            <i class="fas fa-check-circle"></i> Semua aktif
                        </small>
                    </div>
                    <div class="bg-info text-white rounded-circle p-3">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Pending Requests Card (Produksi Only) -->
    @if(auth()->user()->role === 'produksi')
    <div class="col-md-3">
        <div class="card stat-card border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Pending Material Requests</h6>
                        <h3 class="mb-0">3</h3>
                        <small class="text-danger">
                            <i class="fas fa-exclamation-circle"></i> Perlu follow up
                        </small>
                    </div>
                    <div class="bg-danger text-white rounded-circle p-3">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Quick Actions (Role-specific) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-bolt text-warning me-2"></i> Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Admin Actions -->
                    @if(auth()->user()->role === 'admin')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.production.create') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <span>Buat PO</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.materials.create') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-box fa-2x mb-2"></i>
                            <span>Tambah Bahan</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.gudang.material-in') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <span>Bahan Masuk</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.reports.stock') }}" class="btn btn-outline-dark w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <span>Laporan Stok</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-plus fa-2x mb-2"></i>
                            <span>Tambah Produk</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-building fa-2x mb-2"></i>
                            <span>Kelola Vendor</span>
                        </a>
                    </div>
                    @endif
                    
                    <!-- Produksi Actions -->
                    @if(auth()->user()->role === 'produksi')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('production.create') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <span>Buat PO Baru</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('produksi.request.material') }}" class="btn btn-outline-danger w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-box fa-2x mb-2"></i>
                            <span>Request Bahan</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('production.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-list fa-2x mb-2"></i>
                            <span>Lihat PO</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-edit fa-2x mb-2"></i>
                            <span>Update Progress</span>
                        </a>
                    </div>
                    @endif
                    
                    <!-- Gudang Actions -->
                    @if(auth()->user()->role === 'gudang')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('gudang.material-in') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <span>Bahan Masuk</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('gudang.material-out') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-outbox fa-2x mb-2"></i>
                            <span>Bahan Keluar</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('gudang.product-in') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-warehouse fa-2x mb-2"></i>
                            <span>Barang Jadi Masuk</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activities -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-history text-primary me-2"></i> Aktivitas Terbaru</h6>
                <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <!-- Show activities based on role -->
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'gudang')
                    <div class="list-group-item">
                        <div class="d-flex">
                            <div class="bg-success text-white rounded-circle p-2 me-3">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Bahan "Kain Katun 30s" masuk 50 meter</h6>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i> 2 jam yang lalu • Vendor: Textile Mandiri
                                </small>
                            </div>
                            <span class="badge bg-success">Completed</span>
                        </div>
                    </div>
                    @endif
                    
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'produksi')
                    <div class="list-group-item">
                        <div class="d-flex">
                            <div class="bg-warning text-white rounded-circle p-2 me-3">
                                <i class="fas fa-industry"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">PO #DS001 status berubah ke "Production"</h6>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i> 5 jam yang lalu • Produk: Kaos DS Premium
                                </small>
                            </div>
                            <span class="badge bg-warning">In Progress</span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="list-group-item">
                        <div class="d-flex">
                            <div class="bg-danger text-white rounded-circle p-2 me-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Stok "Benang Nilon" hampir habis (3.5 roll)</h6>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i> 1 hari yang lalu • Min. Stock: 5 roll
                                </small>
                            </div>
                            <span class="badge bg-danger">Alert</span>
                        </div>
                    </div>
                    
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'gudang')
                    <div class="list-group-item">
                        <div class="d-flex">
                            <div class="bg-info text-white rounded-circle p-2 me-3">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Barang jadi "Kaos Polos" masuk gudang (100 pcs)</h6>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i> 1 hari yang lalu • PO #DS002
                                </small>
                            </div>
                            <span class="badge bg-info">Completed</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Role-specific Right Panel -->
    <div class="col-md-4">
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'gudang')
        <!-- Low Stock Alert -->
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Low Stock Alert</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Bahan</th>
                                <th>Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Benang Nilon</td>
                                <td class="fw-bold text-danger">3.5 roll</td>
                                <td><span class="badge bg-danger">Critical</span></td>
                            </tr>
                            <tr>
                                <td>Tinta Sablon Hitam</td>
                                <td class="fw-bold text-warning">8.2 kg</td>
                                <td><span class="badge bg-warning">Warning</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        
        @if(auth()->user()->role === 'produksi')
        <!-- Pending Material Requests -->
        <div class="card border-warning">
            <div class="card-header bg-warning text-white">
                <h6 class="mb-0"><i class="fas fa-clock me-2"></i> Pending Material Requests</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">PO#DS003 - Kemeja Casual</h6>
                                <small class="text-muted">Menunggu: 2 hari</small>
                            </div>
                            <span class="badge bg-danger">Urgent</span>
                        </div>
                    </div>
                    
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">PO#DS007 - Kaos Polo</h6>
                                <small class="text-muted">Menunggu: 1 hari</small>
                            </div>
                            <span class="badge bg-warning">Pending</span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('produksi.request.material') }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-exclamation-circle me-1"></i> Follow Up
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection