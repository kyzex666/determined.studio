<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionOrder;
use App\Models\Material;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MaterialRequestController extends Controller
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
        
        // Filter hanya yang status waiting_material atau process
        $query->whereIn('status', ['waiting_material', 'process', 'done'])
              ->with('product');
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }
        
        // Sort berdasarkan priority dan tanggal
        $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
              ->orderBy('created_at', 'desc');
        
        $orders = $query->paginate(15)->withQueryString();
        
        // Hitung statistik
        $stats = [
            'total_requests' => $orders->total(),
            'waiting' => $orders->where('status', 'waiting_material')->count(),
            'processing' => $orders->where('status', 'process')->count(),
            'completed' => $orders->where('status', 'done')->count()
        ];
        
        return view('produksi.material-requests.index', compact('orders', 'stats'));
    }
    
    public function create($order_id = null)
    {
        $user = Auth::user();
        
        // Ambil production orders milik user yang statusnya draft
        $orders = ProductionOrder::where('created_by', $user->id)
            ->where('status', 'draft')
            ->with('product')
            ->get();
        
        $selectedOrder = null;
        if ($order_id) {
            $selectedOrder = ProductionOrder::with(['product', 'productionMaterials.material'])
                ->find($order_id);
                
            // Cek authorization
            if ($selectedOrder && $selectedOrder->created_by !== $user->id && $user->role !== 'admin') {
                abort(403, 'Anda tidak memiliki akses ke Production Order ini.');
            }
        }
        
        return view('produksi.material-requests.create', compact('orders', 'selectedOrder'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'production_order_id' => 'required|exists:production_orders,id'
        ]);
        
        $productionOrder = ProductionOrder::with('productionMaterials')->find($request->production_order_id);
        
        // Cek authorization
        if ($productionOrder->created_by !== $user->id && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke Production Order ini.');
        }
        
        // Validasi status
        if ($productionOrder->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Hanya Production Order dengan status draft yang bisa request material.');
        }
        
        // Validasi material requirements
        if ($productionOrder->productionMaterials->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Tambahkan material requirement terlebih dahulu sebelum request.');
        }
        
        DB::transaction(function () use ($productionOrder) {
            // Update status ke waiting material
            $notes = $productionOrder->notes ?? '';
            $requestNote = "\n\n[Request material dikirim pada " . date('Y-m-d H:i') . "]";
            
            $productionOrder->update([
                'status' => 'waiting_material',
                'notes' => $notes . $requestNote
            ]);
        });
        
        return redirect()->route('produksi.material-requests.index')
            ->with('success', 'Request bahan berhasil dikirim ke gudang.');
    }
    
    public function show($id)
    {
        $productionOrder = ProductionOrder::with([
            'product',
            'productionMaterials.material',
            'materialOut.material',
            'createdByUser'
        ])->findOrFail($id);
        
        $user = Auth::user();
        
        // Cek authorization
        if ($productionOrder->created_by !== $user->id && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke request ini.');
        }
        
        // Hitung material yang sudah dikeluarkan
        $materialStatus = [];
        foreach ($productionOrder->productionMaterials as $pm) {
            $issuedQty = $productionOrder->materialOut()
                ->where('material_id', $pm->material_id)
                ->sum('qty');
                
            $materialStatus[] = [
                'material' => $pm->material,
                'required_qty' => $pm->required_qty,
                'issued_qty' => $issuedQty,
                'remaining' => $pm->required_qty - $issuedQty,
                'percentage' => $pm->required_qty > 0 ? ($issuedQty / $pm->required_qty) * 100 : 0,
                'unit' => $pm->material->unit
            ];
        }
        
        // History material out
        $materialOutHistory = $productionOrder->materialOut()
            ->with('material')
            ->orderBy('date', 'desc')
            ->get();
        
        return view('produksi.material-requests.show', compact(
            'productionOrder',
            'materialStatus',
            'materialOutHistory'
        ));
    }
    
    public function cancel(Request $request, $id)
    {
        $user = Auth::user();
        $productionOrder = ProductionOrder::findOrFail($id);
        
        // Cek authorization
        if ($productionOrder->created_by !== $user->id && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk membatalkan request ini.');
        }
        
        // Validasi status
        if ($productionOrder->status !== 'waiting_material') {
            return redirect()->route('produksi.material-requests.show', $id)
                ->with('error', 'Hanya request dengan status waiting material yang bisa dibatalkan.');
        }
        
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);
        
        DB::transaction(function () use ($request, $productionOrder) {
            $notes = $productionOrder->notes ?? '';
            $cancellationNote = "\n\n[Request dibatalkan pada " . date('Y-m-d H:i') . "]\n" .
                              "Alasan: " . $request->cancellation_reason;
            
            $productionOrder->update([
                'status' => 'draft',
                'notes' => $notes . $cancellationNote
            ]);
        });
        
        return redirect()->route('produksi.material-requests.index')
            ->with('success', 'Request bahan berhasil dibatalkan.');
    }
    
    public function status($id)
    {
        $productionOrder = ProductionOrder::with('product')->findOrFail($id);
        
        $user = Auth::user();
        
        // Cek authorization
        if ($productionOrder->created_by !== $user->id && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke request ini.');
        }
        
        // Hitung progress material
        $materialProgress = 0;
        $totalRequired = $productionOrder->productionMaterials->sum('required_qty');
        if ($totalRequired > 0) {
            $totalIssued = $productionOrder->materialOut()->sum('qty');
            $materialProgress = ($totalIssued / $totalRequired) * 100;
        }
        
        return response()->json([
            'status' => $productionOrder->status,
            'material_progress' => round($materialProgress, 2),
            'order_code' => $productionOrder->order_code,
            'product_name' => $productionOrder->product->name,
            'created_at' => $productionOrder->created_at->format('Y-m-d H:i'),
            'updated_at' => $productionOrder->updated_at->format('Y-m-d H:i')
        ]);
    }
    
    public function checkMaterialAvailability($orderId)
    {
        $productionOrder = ProductionOrder::with('productionMaterials.material')->findOrFail($orderId);
        
        $user = Auth::user();
        
        // Cek authorization
        if ($productionOrder->created_by !== $user->id && $user->role !== 'admin') {
            abort(403);
        }
        
        $availability = [];
        $allAvailable = true;
        
        foreach ($productionOrder->productionMaterials as $pm) {
            $issuedQty = $productionOrder->materialOut()
                ->where('material_id', $pm->material_id)
                ->sum('qty');
                
            $remainingRequired = $pm->required_qty - $issuedQty;
            $currentStock = $pm->material->current_stock;
            $isAvailable = $currentStock >= $remainingRequired;
            
            if (!$isAvailable) {
                $allAvailable = false;
            }
            
            $availability[] = [
                'material_id' => $pm->material_id,
                'material_name' => $pm->material->name,
                'required_qty' => $pm->required_qty,
                'issued_qty' => $issuedQty,
                'remaining_required' => $remainingRequired,
                'current_stock' => $currentStock,
                'is_available' => $isAvailable,
                'unit' => $pm->material->unit
            ];
        }
        
        return response()->json([
            'all_available' => $allAvailable,
            'materials' => $availability
        ]);
    }
}