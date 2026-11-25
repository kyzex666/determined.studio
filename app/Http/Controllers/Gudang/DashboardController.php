<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\MaterialIn;
use App\Models\MaterialOut;
use App\Models\ProductIn;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:gudang,admin']);
    }
    
    public function index()
    {
        $today = Carbon::today();
        $weekStart = $today->copy()->startOfWeek();
        $weekEnd = $today->copy()->endOfWeek();
        $monthStart = $today->copy()->startOfMonth();
        
        $data = [
            // Statistik utama
            'total_materials' => Material::count(),
            'total_products' => Product::count(),
            'material_stock_value' => Material::sum('current_stock'),
            'product_stock_value' => Product::sum('current_stock'),
            
            // Pending requests
            'pending_requests' => ProductionOrder::where('status', 'waiting_material')->count(),
            
            // Alert stok minimum
            'low_stock_alerts' => Material::whereRaw('current_stock <= min_stock')->limit(5)->get(),
            'out_of_stock_alerts' => Material::where('current_stock', '<=', 0)->limit(5)->get(),
            
            // Aktivitas hari ini
            'today_material_in' => MaterialIn::where('date', '>=', $today)->where('date', '<', $today->copy()->addDay())->count(),
            'today_material_out' => MaterialOut::where('date', '>=', $today)->where('date', '<', $today->copy()->addDay())->count(),
            'today_product_in' => ProductIn::where('date', '>=', $today)->where('date', '<', $today->copy()->addDay())->count(),
            
            // Aktivitas minggu ini
            'weekly_material_in' => MaterialIn::whereBetween('date', [$weekStart, $weekEnd])->count(),
            'weekly_material_out' => MaterialOut::whereBetween('date', [$weekStart, $weekEnd])->count(),
            
            // Recent transactions
            'recent_material_in' => MaterialIn::with(['material', 'vendor'])
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get(),
            'recent_material_out' => MaterialOut::with(['material', 'productionOrder.product'])
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get(),
            'recent_product_in' => ProductIn::with(['product', 'productionOrder'])
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get(),
            
            // Pending material requests
            'pending_material_requests' => ProductionOrder::where('status', 'waiting_material')
                ->with('product')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),

            // Incoming finished products (recently completed production orders)
            'incoming_finished_products' => ProductionOrder::where('status', 'done')
                ->whereHas('productIn', function($query) {
                    $query->where('date', '>=', Carbon::now()->subDays(7)); // Last 7 days
                })
                ->with(['product', 'productIn' => function($query) {
                    $query->latest('date');
                }])
                ->orderBy('end_date', 'desc')
                ->limit(5)
                ->get(),
            
            // Material dengan stock terendah
            'lowest_stock_materials' => Material::orderBy('current_stock', 'asc')
                ->limit(5)
                ->get(),
                
            // Material dengan pergerakan tinggi
            'top_moving_materials' => Material::withCount(['materialIn as total_in' => function($query) use ($monthStart) {
                    $query->where('date', '>=', $monthStart);
                }])
                ->withCount(['materialOut as total_out' => function($query) use ($monthStart) {
                    $query->where('date', '>=', $monthStart);
                }])
                ->orderBy('total_in', 'desc')
                ->limit(5)
                ->get()
        ];
        
        return view('gudang.dashboard', $data);
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
            } elseif ($request->stock_level == 'critical') {
                $query->whereRaw('current_stock <= (min_stock * 0.5)');
            }
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('material_code', 'LIKE', "%{$search}%");
            });
        }
        
        $materials = $query->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('gudang.reports.stocks', compact('materials', 'products'));
    }
    
    public function transactionReport(Request $request)
    {
        $materialIn = MaterialIn::with(['material', 'vendor']);
        $materialOut = MaterialOut::with(['material', 'productionOrder.product']);
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
        
        return view('gudang.reports.transactions', [
            'materialIn' => $materialIn->orderBy('date', 'desc')->get(),
            'materialOut' => $materialOut->orderBy('date', 'desc')->get(),
            'productIn' => $productIn->orderBy('date', 'desc')->get(),
            'dateFrom' => $request->date_from ?? date('Y-m-01'),
            'dateTo' => $request->date_to ?? date('Y-m-t')
        ]);
    }
    
    public function lowStockReport(Request $request)
    {
        $materials = Material::whereRaw('current_stock <= min_stock')
            ->orderBy('current_stock', 'asc')
            ->get();
            
        $criticalMaterials = Material::whereRaw('current_stock <= (min_stock * 0.5)')
            ->orderBy('current_stock', 'asc')
            ->get();
            
        $outOfStockMaterials = Material::where('current_stock', '<=', 0)
            ->orderBy('name')
            ->get();
            
        return view('gudang.reports.low-stock', compact(
            'materials', 
            'criticalMaterials', 
            'outOfStockMaterials'
        ));
    }
    
    public function exportStockReport(Request $request)
    {
        $materials = Material::orderBy('name')->get();
        
        // Untuk implementasi export Excel/PDF bisa ditambahkan
        // menggunakan Laravel Excel atau PDF library
        
        return view('gudang.reports.export-stocks', compact('materials'));
    }
}