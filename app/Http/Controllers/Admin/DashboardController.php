<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\MaterialIn;
use App\Models\MaterialOut;
use App\Models\ProductIn;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index()
    {
        $today = Carbon::today();
        $weekStart = $today->copy()->startOfWeek();
        $weekEnd = $today->copy()->endOfWeek();
        
        $data = [
            'total_materials' => Material::count(),
            'total_products' => Product::count(),
            'total_users' => User::count(),
            'active_orders' => ProductionOrder::whereIn('status', ['process', 'waiting_material'])->count(),
            'pending_requests' => ProductionOrder::where('status', 'waiting_material')->count(),
            'today_material_in' => MaterialIn::whereDate('date', $today)->count(),
            'today_product_in' => ProductIn::whereDate('date', $today)->count(),
            'weekly_production' => ProductionOrder::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
            'low_stock_materials' => Material::whereRaw('current_stock <= min_stock')->limit(5)->get(),
            'recent_orders' => ProductionOrder::with('product')
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get(),
            'recent_material_in' => MaterialIn::with(['material', 'vendor'])
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get()
        ];
        
        return view('admin.dashboard', $data);
    }
    
    public function reports()
    {
        return view('admin.reports.index');
    }
    
    public function stockReport(Request $request)
    {
        $query = Material::query();
        
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->has('stock_level')) {
            if ($request->stock_level == 'low') {
                $query->whereRaw('current_stock <= min_stock');
            } elseif ($request->stock_level == 'out') {
                $query->where('current_stock', '<=', 0);
            }
        }
        
        $materials = $query->get();
        $products = Product::all();
        
        return view('admin.reports.stocks', compact('materials', 'products'));
    }
    
    public function productionReport(Request $request)
    {
        $query = ProductionOrder::with('product');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [
                $request->date_from,
                $request->date_to
            ]);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        $total_production = $orders->sum('actual_qty');
        $total_target = $orders->sum('target_qty');
        
        return view('admin.reports.production', compact('orders', 'total_production', 'total_target'));
    }
    
    public function transactionReport(Request $request)
    {
        $materialIn = MaterialIn::with(['material', 'vendor']);
        $materialOut = MaterialOut::with(['material', 'productionOrder']);
        $productIn = ProductIn::with(['product', 'productionOrder']);
        
        if ($request->has('date_from') && $request->has('date_to')) {
            $dateFrom = $request->date_from;
            $dateTo = $request->date_to;
            
            $materialIn->whereBetween('date', [$dateFrom, $dateTo]);
            $materialOut->whereBetween('date', [$dateFrom, $dateTo]);
            $productIn->whereBetween('date', [$dateFrom, $dateTo]);
        } else {
            // Default: bulan ini
            $materialIn->whereMonth('date', date('m'));
            $materialOut->whereMonth('date', date('m'));
            $productIn->whereMonth('date', date('m'));
        }
        
        return view('admin.reports.transactions', [
            'materialIn' => $materialIn->get(),
            'materialOut' => $materialOut->get(),
            'productIn' => $productIn->get()
        ]);
    }
}