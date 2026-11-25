<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Gudang | Determined Studio</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --gudang-primary: #27ae60;
            --gudang-secondary: #2ecc71;
            --gudang-warning: #f39c12;
            --gudang-danger: #e74c3c;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Sidebar Gudang */
        .sidebar-gudang {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a535c 0%, #0d3b45 100%);
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-gudang .logo {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-gudang .logo h4 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar-gudang .logo small {
            color: rgba(255,255,255,0.7);
        }
        
        .sidebar-gudang .user-info {
            padding: 20px 15px;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-gudang .user-info .badge {
            font-size: 0.75em;
            padding: 3px 8px;
        }
        
        .sidebar-menu-gudang {
            padding: 15px 0;
        }
        
        .sidebar-menu-gudang .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 0;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        
        .sidebar-menu-gudang .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left: 3px solid var(--gudang-secondary);
        }
        
        .sidebar-menu-gudang .nav-link.active {
            color: white;
            background-color: rgba(46, 204, 113, 0.2);
            border-left: 3px solid var(--gudang-secondary);
        }
        
        .sidebar-menu-gudang .nav-link i {
            width: 24px;
            margin-right: 10px;
            text-align: center;
            font-size: 1.1em;
        }
        
        .sidebar-menu-gudang .badge-notification {
            margin-left: auto;
            font-size: 0.7em;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Main Content */
        .main-content-gudang {
            padding: 20px;
        }
        
        .page-header-gudang {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid var(--gudang-primary);
        }
        
        .card-gudang {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
            margin-bottom: 20px;
            border-top: 3px solid var(--gudang-primary);
        }
        
        .card-header-gudang {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
            font-weight: 600;
            color: #1a535c;
        }
        
        /* Stats Cards Gudang */
        .stat-card-gudang {
            padding: 20px;
            border-radius: 8px;
            color: white;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .stat-card-gudang:hover {
            transform: translateY(-5px);
        }
        
        .stat-card-gudang .stat-icon {
            font-size: 2.5em;
            opacity: 0.8;
            margin-bottom: 10px;
        }
        
        .stat-card-gudang .stat-value {
            font-size: 2em;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .stat-card-gudang .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }
        
        /* Buttons Gudang */
        .btn-gudang-primary {
            background-color: var(--gudang-primary);
            border-color: var(--gudang-primary);
            color: white;
        }
        
        .btn-gudang-primary:hover {
            background-color: #219653;
            border-color: #219653;
        }
        
        .btn-gudang-secondary {
            background-color: var(--gudang-secondary);
            border-color: var(--gudang-secondary);
            color: white;
        }
        
        .btn-gudang-secondary:hover {
            background-color: #27ae60;
            border-color: #27ae60;
        }
        
        /* Alert Stok */
        .stock-alert {
            border-left: 4px solid #e74c3c;
            background-color: #fff5f5;
        }
        
        .stock-warning {
            border-left: 4px solid #f39c12;
            background-color: #fff9e6;
        }
        
        .stock-good {
            border-left: 4px solid #27ae60;
            background-color: #f0fff4;
        }
        
        /* Table */
        .table-gudang th {
            background-color: #f8f9fa;
            color: #1a535c;
            font-weight: 600;
            border-top: none;
        }
        
        .table-gudang td {
            vertical-align: middle;
        }
        
        /* Progress bars */
        .progress-gudang {
            height: 10px;
            border-radius: 5px;
        }
        
        .progress-gudang .progress-bar {
            border-radius: 5px;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .quick-action-btn {
            padding: 20px;
            background: white;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 120px;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: var(--gudang-secondary);
            color: var(--gudang-primary);
        }
        
        .quick-action-btn i {
            font-size: 2em;
            margin-bottom: 10px;
            color: var(--gudang-primary);
        }
        
        .quick-action-btn .badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        
        /* Material Cards */
        .material-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        
        .material-card:hover {
            border-color: var(--gudang-secondary);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .material-card.low-stock {
            border-left: 4px solid #f39c12;
            background-color: #fff9e6;
        }
        
        .material-card.out-stock {
            border-left: 4px solid #e74c3c;
            background-color: #fff5f5;
        }
        
        /* Form Styles */
        .form-control-gudang:focus {
            border-color: var(--gudang-secondary);
            box-shadow: 0 0 0 0.2rem rgba(46, 204, 113, 0.25);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar-gudang {
                min-height: auto;
            }
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 p-0 sidebar-gudang">
                <div class="logo">
                    <h4>Determined Studio</h4>
                    <small>Gudang Panel</small>
                </div>
                
                <div class="user-info">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                            <span class="badge bg-success">GUDANG</span>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-menu-gudang">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('gudang.dashboard') ? 'active' : '' }}" 
                               href="{{ route('gudang.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item mt-3">
                            <small class="text-white-50 px-3">TRANSAKSI</small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('gudang.material-in.*') ? 'active' : '' }}" 
                               href="{{ route('gudang.material-in.index') }}">
                                <i class="bi bi-inbox"></i> Material Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('gudang.material-out.*') ? 'active' : '' }}" 
                               href="{{ route('gudang.material-out.index') }}">
                                <i class="bi bi-outbox"></i> Material Keluar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('gudang.product-in.*') ? 'active' : '' }}" 
                               href="{{ route('gudang.product-in.index') }}">
                                <i class="bi bi-warehouse"></i> Barang Jadi Masuk
                            </a>
                        </li>
                        
                        <li class="nav-item mt-3">
                            <small class="text-white-50 px-3">PERMINTAAN</small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('gudang.requests.*') ? 'active' : '' }}" 
                               href="{{ route('gudang.requests.index') }}">
                                <i class="bi bi-bell"></i> Request Bahan
                                @php
                                    $pendingRequests = \App\Models\ProductionOrder::where('status', 'waiting_material')->count();
                                @endphp
                                @if($pendingRequests > 0)
                                    <span class="badge bg-danger badge-notification">{{ $pendingRequests }}</span>
                                @endif
                            </a>
                        </li>
                        
                        <li class="nav-item mt-3">
                            <small class="text-white-50 px-3">MONITORING</small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('vendors.index') ? 'active' : '' }}" 
                               href="{{ route('vendors.index') }}">
                                <i class="bi bi-building"></i> Vendor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('materials.index') ? 'active' : '' }}" 
                               href="{{ route('materials.index') }}">
                                <i class="bi bi-box"></i> Bahan Baku
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}" 
                               href="{{ route('products.index') }}">
                                <i class="bi bi-tshirt"></i> Produk
                            </a>
                        </li>
                        
                        <li class="nav-item mt-3">
                            <small class="text-white-50 px-3">LAPORAN</small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('gudang.reports.stocks') ? 'active' : '' }}" 
                               href="{{ route('gudang.reports.stocks') }}">
                                <i class="bi bi-bar-chart"></i> Laporan Stok
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('gudang.reports.transactions') ? 'active' : '' }}" 
                               href="{{ route('gudang.reports.transactions') }}">
                                <i class="bi bi-file-text"></i> Laporan Transaksi
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 main-content-gudang">
                <!-- Header -->
                <div class="page-header-gudang">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">@yield('title')</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('gudang.dashboard') }}">Dashboard Gudang</a></li>
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-3">{{ date('d F Y, H:i') }}</span>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Notifications -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(isset($unavailableMaterials) && count($unavailableMaterials) > 0)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <h6><i class="bi bi-exclamation-triangle"></i> Material Tidak Tersedia</h6>
                        <ul class="mb-0">
                            @foreach($unavailableMaterials as $material)
                            <li>
                                {{ $material['name'] }}: Butuh {{ $material['required'] }} {{ $material['unit'] }}, 
                                Tersedia {{ $material['available'] }} {{ $material['unit'] }}
                            </li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <!-- Content -->
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                },
                "pageLength": 25,
                "order": []
            });
        });
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
        
        // Confirm before action
        function confirmAction(message, event) {
            if (!confirm(message || 'Apakah Anda yakin?')) {
                event.preventDefault();
            }
        }
        
        // Format number
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        }
    </script>
    
    @stack('scripts')
</body>
</html>