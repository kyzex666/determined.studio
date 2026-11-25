<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionOrder;
use App\Models\ProductIn;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductionResultController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:produksi,admin']);
    }
    
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ProductionOrder::where('created_by', $user->id);
        
        // Untuk admin, tampilkan semua
        if ($user->role === 'admin') {
            $query = ProductionOrder::query();
        }
        
        // Filter hanya yang status done
        $query->where('status', 'done')
              ->with(['product', 'productIn']);
        
        // Filter berdasarkan tanggal
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('updated_at', [$request->date_from, $request->date_to]);
        } else {
            // Default: bulan ini
            $query->whereMonth('updated_at', date('m'));
        }
        
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        // Sort
        $query->orderBy('updated_at', 'desc');
        
        $orders = $query->paginate(15)->withQueryString();
        $products = Product::orderBy('name')->get();
        
        // Hitung statistik
        $stats = [
            'total_results' => $orders->total(),
            'total_production' => $orders->sum('actual_qty'),
            'total_to_warehouse' => $orders->sum(function($order) {
                return $order->productIn->sum('qty');
            }),
            'pending_warehouse' => $orders->sum('actual_qty') - $orders->sum(function($order) {
                return $order->productIn->sum('qty');
            })
        ];
        
        return view('produksi.results.index', compact('orders', 'products', 'stats'));
    }
    
    public function create($order_id = null)
    {
        $user = Auth::user();
        
        // Ambil production orders milik user yang statusnya process
        $orders = ProductionOrder::where('created_by', $user->id)
            ->where('status', 'process')
            ->with('product')
            ->get();
        
        $selectedOrder = null;
        if ($order_id) {
            $selectedOrder = ProductionOrder::with('product')->find($order_id);
            
            // Cek authorization
            if ($selectedOrder && $selectedOrder->created_by !== $user->id && $user->role !== 'admin') {
                abort(403, 'Anda tidak memiliki akses ke Production Order ini.');
            }
        }
        
        return view('produksi.results.create', compact('orders', 'selectedOrder'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'actual_qty' => 'required|integer|min:1',
            'completion_date' => 'required|date',
            'defect_qty' => 'nullable|integer|min:0',
            'defect_reason' => 'nullable|required_with:defect_qty|string|max:500',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $productionOrder = ProductionOrder::find($request->production_order_id);
        
        // Cek authorization
        if ($productionOrder->created_by !== $user->id && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke Production Order ini.');
        }
        
        // Validasi status
        if ($productionOrder->status !== 'process') {
            return redirect()->back()
                ->with('error', 'Hanya Production Order dengan status process yang bisa dilaporkan hasilnya.');
        }
        
        // Validasi jumlah
        if ($request->actual_qty > $productionOrder->target_qty) {
            return redirect()->back()
                ->with('error', 'Jumlah aktual tidak boleh melebihi target produksi.');
        }
        
        DB::transaction(function () use ($request, $productionOrder) {
            // Hitung defect jika ada
            $good_qty = $request->actual_qty - ($request->defect_qty ?? 0);
            
            // Update production order
            $notes = $productionOrder->notes ?? '';
            $resultNote = "\n\n[Hasil produksi dilaporkan pada " . date('Y-m-d H:i') . "]\n" .
                         "Jumlah aktual: " . $request->actual_qty . " pcs\n" .
                         "Jumlah baik: " . $good_qty . " pcs\n" .
                         ($request->defect_qty ? "Jumlah defect: " . $request->defect_qty . " pcs\n" : "") .
                         ($request->defect_reason ? "Alasan defect: " . $request->defect_reason . "\n" : "") .
                         ($request->notes ? "Catatan: " . $request->notes . "\n" : "");
            
            $productionOrder->update([
                'actual_qty' => $request->actual_qty,
                'status' => 'done',
                'notes' => $notes . $resultNote
            ]);
            
            // Jika ada defect, catat sebagai product in dengan kondisi defect
            if ($request->defect_qty && $request->defect_qty > 0) {
                ProductIn::create([
                    'production_order_id' => $productionOrder->id,
                    'product_id' => $productionOrder->product_id,
                    'qty' => $request->defect_qty,
                    'date' => $request->completion_date,
                    'condition' => 'defect',
                    'defect_notes' => $request->defect_reason,
                    'received_by' => Auth::id()
                ]);
            }
        });
        
        return redirect()->route('produksi.results.index')
            ->with('success', 'Hasil produksi berhasil dilaporkan.');
    }
    
    public function show($id)
    {
        $productionOrder = ProductionOrder::with([
            'product',
            'productIn',
            'materialOut.material',
            'createdByUser'
        ])->findOrFail($id);
        
        $user = Auth::user();
        
        // Cek authorization
        if ($productionOrder->created_by !== $user->id && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke hasil produksi ini.');
        }
        
        // Validasi status
        if ($productionOrder->status !== 'done') {
            return redirect()->route('produksi.orders.show', $id)
                ->with('error', 'Production Order belum selesai.');
        }
        
        // Hitung barang yang sudah masuk gudang
        $warehouseIn = $productionOrder->productIn()->sum('qty');
        $warehouseGood = $productionOrder->productIn()->where('condition', 'good')->sum('qty');
        $warehouseDefect = $productionOrder->productIn()->where('condition', 'defect')->sum('qty');
        $remainingForWarehouse = $productionOrder->actual_qty - $warehouseIn;
        
        // Material usage
        $materialUsage = [];
        foreach ($productionOrder->materialOut as $mo) {
            $usagePerUnit = $productionOrder->actual_qty > 0 
                ? $mo->qty / $productionOrder->actual_qty 
                : 0;
                
            $materialUsage[] = [
                'material' => $mo->material,
                'qty_used' => $mo->qty,
                'usage_per_unit' => round($usagePerUnit, 3),
                'unit' => $mo->material->unit
            ];
        }
        
        return view('produksi.results.show', compact(
            'productionOrder',
            'warehouseIn',
            'warehouseGood',
            'warehouseDefect',
            'remainingForWarehouse',
            'materialUsage'
        ));
    }
    
    public function edit($id)
    {
        $productionOrder = ProductionOrder::findOrFail($id);
        
        $user = Auth::user();
        
        // Cek authorization
        if ($productionOrder->created_by !== $user->id && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit hasil produksi ini.');
        }
        
        // Validasi status dan waktu
        if ($productionOrder->status !== 'done') {
            return redirect()->route('produksi.results.show', $id)
                ->with('error', 'Hanya hasil produksi yang sudah selesai yang bisa diedit.');
        }
        
        // Batasi waktu edit (misalnya maksimal 24 jam setelah selesai)
        $hoursSinceCompletion = Carbon::parse($productionOrder->updated_at)->diffInHours(Carbon::now());
        if ($hoursSinceCompletion > 24 && $user->role !== 'admin') {
            return redirect()->route('produksi.results.show', $id)
                ->with('error', 'Waktu untuk mengedit hasil produksi sudah habis (maksimal 24 jam).');
        }
        
        return view('produksi.results.edit', compact('productionOrder'));
    }
    
    public function update(Request $request, $id)
    {
        $productionOrder = ProductionOrder::findOrFail($id);
        
        $user = Auth::user();
        
        // Cek authorization
        if ($productionOrder->created_by !== $user->id && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk mengedit hasil produksi ini.');
        }
        
        $request->validate([
            'actual_qty' => 'required|integer|min:1|max:' . $productionOrder->target_qty,
            'update_reason' => 'required|string|max:500'
        ]);
        
        DB::transaction(function () use ($request, $productionOrder) {
            // Simpan qty lama
            $oldQty = $productionOrder->actual_qty;
            
            // Update production order
            $notes = $productionOrder->notes ?? '';
            $updateNote = "\n\n[Hasil produksi diupdate pada " . date('Y-m-d H:i') . "]\n" .
                         "Jumlah lama: " . $oldQty . " pcs\n" .
                         "Jumlah baru: " . $request->actual_qty . " pcs\n" .
                         "Alasan update: " . $request->update_reason;
            
            $productionOrder->update([
                'actual_qty' => $request->actual_qty,
                'notes' => $notes . $updateNote
            ]);
        });
        
        return redirect()->route('produksi.results.show', $productionOrder)
            ->with('success', 'Hasil produksi berhasil diperbarui.');
    }
    
    public function report(Request $request)
    {
        $user = Auth::user();
        $query = ProductionOrder::where('created_by', $user->id)
            ->where('status', 'done');
        
        // Untuk admin, tampilkan semua
        if ($user->role === 'admin') {
            $query = ProductionOrder::where('status', 'done');
        }
        
        // Filter
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('updated_at', [$request->date_from, $request->date_to]);
        } else {
            // Default: bulan ini
            $startDate = $request->filled('start_date') ? $request->start_date : date('Y-m-01');
            $endDate = $request->filled('end_date') ? $request->end_date : date('Y-m-t');
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        }
        
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        $orders = $query->with('product')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        // Hitung statistik
        $totalProduction = $orders->sum('actual_qty');
        $totalTarget = $orders->sum('target_qty');
        $efficiencyRate = $totalTarget > 0 ? ($totalProduction / $totalTarget) * 100 : 0;
        
        // Kelompokkan berdasarkan produk
        $productGroups = [];
        foreach ($orders as $order) {
            $productId = $order->product_id;
            if (!isset($productGroups[$productId])) {
                $productGroups[$productId] = [
                    'product' => $order->product,
                    'total_orders' => 0,
                    'total_production' => 0,
                    'total_target' => 0
                ];
            }
            
            $productGroups[$productId]['total_orders']++;
            $productGroups[$productId]['total_production'] += $order->actual_qty;
            $productGroups[$productId]['total_target'] += $order->target_qty;
        }
        
        // Hitung efficiency per produk
        foreach ($productGroups as &$group) {
            $group['efficiency'] = $group['total_target'] > 0 
                ? ($group['total_production'] / $group['total_target']) * 100 
                : 0;
        }
        
        return view('produksi.results.report', compact(
            'orders',
            'totalProduction',
            'totalTarget',
            'efficiencyRate',
            'productGroups',
            'startDate',
            'endDate'
        ));
    }
    
    public function exportReport(Request $request)
    {
        // Untuk implementasi export Excel/PDF
        // Bisa menggunakan Laravel Excel atau PDF library
        
        return redirect()->route('produksi.results.report', $request->all())
            ->with('info', 'Fitur export akan segera tersedia.');
    }
}