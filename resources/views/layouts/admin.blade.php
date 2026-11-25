<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin | Determined Studio</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #9b59b6;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 400;
            line-height: 1.6;
            color: #4a5568;
            overflow-x: hidden;
        }
        
        /* Sidebar Enhanced */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #1e2a38 100%);
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.08);
            position: relative;
            z-index: 10;
            transition: all 0.3s ease;
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 120px;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.15) 0%, rgba(155, 89, 182, 0.1) 100%);
            z-index: -1;
        }
        
        .sidebar .logo {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background-color: rgba(255, 255, 255, 0.02);
        }
        
        .sidebar .logo h4 {
            color: white;
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 1.4rem;
        }
        
        .sidebar .logo small {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            font-weight: 400;
        }
        
        .sidebar .user-info {
            padding: 24px 20px;
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background-color: rgba(255, 255, 255, 0.02);
        }
        
        .sidebar .user-info .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .user-info h6 {
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .sidebar .user-info .badge {
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu .menu-section {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 20px;
            margin-top: 10px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .sidebar-menu .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 14px 20px;
            margin: 2px 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-menu .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: var(--secondary-color);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .sidebar-menu .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }
        
        .sidebar-menu .nav-link:hover::before {
            transform: translateX(0);
        }
        
        .sidebar-menu .nav-link.active {
            color: white;
            background-color: rgba(52, 152, 219, 0.2);
            box-shadow: inset 2px 0 8px rgba(52, 152, 219, 0.3);
        }
        
        .sidebar-menu .nav-link.active::before {
            transform: translateX(0);
        }
        
        .sidebar-menu .nav-link i {
            width: 24px;
            margin-right: 12px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .sidebar-menu .nav-link .menu-arrow {
            margin-left: auto;
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }
        
        .sidebar-menu .nav-link:hover .menu-arrow {
            transform: translateX(3px);
        }
        
        /* Main Content Enhanced */
        .main-content {
            padding: 24px;
            background-color: var(--light-bg);
            min-height: 100vh;
        }
        
        .page-header {
            background: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
        }
        
        .page-header h3 {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
            font-size: 1.8rem;
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
        }
        
        .breadcrumb-item a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .breadcrumb-item a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }
        
        .breadcrumb-item.active {
            color: #718096;
            font-weight: 500;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            color: #cbd5e0;
        }
        
        /* Cards Enhanced */
        .card {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 12px;
            margin-bottom: 24px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--hover-shadow);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 24px;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-header i {
            color: var(--secondary-color);
            margin-right: 8px;
        }
        
        .card-body {
            padding: 24px;
        }
        
        /* Table Enhanced */
        .table-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 700;
            color: var(--primary-color);
            border-top: none;
            background-color: #f7fafc;
            padding: 16px 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .table td {
            padding: 16px 20px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }
        
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f7fafc;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfdfe;
        }
        
        /* Badges Enhanced */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
        }
        
        .badge-admin { 
            background-color: var(--danger-color);
            color: white;
        }
        
        .badge-gudang { 
            background-color: var(--success-color);
            color: white;
        }
        
        .badge-produksi { 
            background-color: var(--warning-color);
            color: white;
        }
        
        /* Status badges */
        .badge-status-draft { 
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        
        .badge-status-waiting { 
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .badge-status-process { 
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }
        
        .badge-status-done { 
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }
        
        .badge-status-cancelled { 
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        /* Priority badges */
        .badge-priority-low { 
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        
        .badge-priority-medium { 
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }
        
        .badge-priority-high { 
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        /* Buttons Enhanced */
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-sm {
            padding: 6px 14px;
            font-size: 0.85rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color), #2c80c9);
            border: none;
            box-shadow: 0 4px 6px rgba(52, 152, 219, 0.2);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9, #2569a8);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(52, 152, 219, 0.3);
        }
        
        .btn-outline-danger {
            border: 2px solid var(--danger-color);
            color: var(--danger-color);
        }
        
        .btn-outline-danger:hover {
            background-color: var(--danger-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(231, 76, 60, 0.2);
        }
        
        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 4px;
        }
        
        /* Stats Cards Enhanced */
        .stat-card {
            padding: 24px;
            border-radius: 12px;
            color: white;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
            z-index: -1;
        }
        
        .stat-card .stat-icon {
            font-size: 2.8rem;
            opacity: 0.9;
            margin-bottom: 16px;
        }
        
        .stat-card .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 8px 0;
            letter-spacing: 0.5px;
        }
        
        .stat-card .stat-label {
            font-size: 0.95rem;
            opacity: 0.95;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        .stat-card .stat-change {
            font-size: 0.85rem;
            margin-top: 8px;
            display: flex;
            align-items: center;
        }
        
        /* Color variants for stats cards */
        .stat-card-primary {
            background: linear-gradient(135deg, var(--secondary-color), #2c80c9);
        }
        
        .stat-card-success {
            background: linear-gradient(135deg, var(--success-color), #229954);
        }
        
        .stat-card-warning {
            background: linear-gradient(135deg, var(--warning-color), #e67e22);
        }
        
        .stat-card-accent {
            background: linear-gradient(135deg, var(--accent-color), #8e44ad);
        }
        
        /* Alert Enhanced */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 16px 20px;
            box-shadow: var(--card-shadow);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .alert i {
            font-size: 1.2rem;
            margin-right: 12px;
        }
        
        .alert-success {
            background-color: rgba(39, 174, 96, 0.1);
            color: #27ae60;
            border-left: 4px solid #27ae60;
        }
        
        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
            border-left: 4px solid #e74c3c;
        }
        
        .alert-info {
            background-color: rgba(52, 152, 219, 0.1);
            color: #3498db;
            border-left: 4px solid #3498db;
        }
        
        /* Form Enhanced */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fcfdfe;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
            background-color: white;
        }
        
        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
        }
        
        /* Pagination Enhanced */
        .pagination {
            margin-bottom: 0;
        }
        
        .pagination .page-link {
            color: var(--secondary-color);
            border: 1px solid #e2e8f0;
            padding: 10px 16px;
            margin: 0 4px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .pagination .page-link:hover {
            background-color: #edf2f7;
            border-color: #cbd5e0;
            transform: translateY(-2px);
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--secondary-color), #2c80c9);
            border-color: var(--secondary-color);
            box-shadow: 0 4px 6px rgba(52, 152, 219, 0.2);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1000;
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .page-header {
                padding: 20px;
            }
            
            .card-body {
                padding: 20px;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        /* Date Display */
        .current-date {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
            font-size: 1.1rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 p-0 sidebar">
                <div class="logo">
                    <h4>Determined Studio</h4>
                    <small class="text-white-50">Admin Panel</small>
                </div>
                
                <div class="user-info">
                    <div class="d-flex align-items-center">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                            <span class="badge badge-admin">ADMINISTRATOR</span>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-menu">
                    <div class="menu-section">NAVIGASI UTAMA</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                                <span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="menu-section">MASTER DATA</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}" href="{{ route('admin.vendors.index') }}">
                                <i class="bi bi-building"></i> Vendor
                                <span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}" href="{{ route('admin.materials.index') }}">
                                <i class="bi bi-box-seam"></i> Bahan Baku
                                <span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                                <i class="bi bi-tshirt"></i> Produk
                                <span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people-fill"></i> User Management
                                <span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="menu-section">GUDANG</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.material-in.*') ? 'active' : '' }}" href="{{ route('admin.material-in.index') }}">
                                <i class="bi bi-inbox-fill"></i> Material Masuk
                                <span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.material-out.*') ? 'active' : '' }}" href="{{ route('admin.material-out.index') }}">
                                <i class="bi bi-outbox-fill"></i> Material Keluar
                                <span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.product-in.*') ? 'active' : '' }}" href="{{ route('admin.product-in.index') }}">
                                <i class="bi bi-warehouse"></i> Barang Jadi Masuk
                                <span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="menu-section">PRODUKSI</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.production-orders.*') ? 'active' : '' }}" href="{{ route('admin.production-orders.index') }}">
                                <i class="bi bi-clipboard-check"></i> Production Orders
                                <span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="menu-section">LAPORAN</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
                                <i class="bi bi-bar-chart-fill"></i> Semua Laporan
                                <span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-10 main-content">
                <!-- Header -->
                <div class="page-header fade-in">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">@yield('title')</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="current-date me-4">{{ date('d F Y') }}</span>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Notifications -->
                <div class="fade-in">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
                
                <!-- Content -->
                <div class="fade-in">
                    @yield('content')
                </div>
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
        // Initialize DataTables with enhanced options
        $(document).ready(function() {
            $('.datatable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                },
                "pageLength": 25,
                "responsive": true,
                "order": [],
                "columnDefs": [
                    { "orderable": false, "targets": 'no-sort' }
                ],
                "drawCallback": function(settings) {
                    // Add animation to table rows
                    $('tbody tr').each(function(i) {
                        $(this).css('animation-delay', (i * 0.05) + 's');
                        $(this).addClass('fade-in');
                    });
                }
            });
        });
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
        
        // Confirm before delete with enhanced UI
        function confirmDelete(event, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            event.preventDefault();
            const form = event.target.closest('form');
            
            // Create custom confirmation modal
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                            <h4 class="modal-title mt-3 mb-2">Konfirmasi Hapus</h4>
                            <p class="text-muted">${message}</p>
                        </div>
                        <div class="modal-footer border-0 pt-0 justify-content-center">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">Hapus</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
            
            modal.querySelector('#confirmDeleteBtn').addEventListener('click', function() {
                form.submit();
            });
            
            modal.addEventListener('hidden.bs.modal', function() {
                document.body.removeChild(modal);
            });
        }
        
        // Add smooth scrolling to page anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add hover effects to all buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Add loading state to form submit buttons
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';
                    submitBtn.disabled = true;
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>