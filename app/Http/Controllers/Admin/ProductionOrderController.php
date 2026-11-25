<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionOrder;
use App\Models\Product;
use App\Models\Material;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductionOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index(Request $request)
    {
        $query = ProductionOrder::with('product');
        
        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }
        
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        $products = Product::orderBy('name')->get();
        
        return view('admin.production-orders.index', compact('orders', 'products'));
    }
    
    public function create()
    {
        $products = Product::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();
        
        return view('admin.production-orders.create', compact('products', 'materials'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'target_qty' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:draft,waiting_material,process,done,cancelled',
            'notes' => 'nullable|string',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.required_qty' => 'required|numeric|min:0.01'
        ]);
        
        DB::transaction(function () use ($request) {
            // Generate order code
            $year = date('Y');
            $lastOrder = ProductionOrder::whereYear('created_at', $year)->count();
            $orderCode = 'PO/' . $year . '/' . str_pad($lastOrder + 1, 4, '0', STR_PAD_LEFT);
            
            $productionOrder = ProductionOrder::create([
                'order_code' => $orderCode,
                'product_id' => $request->product_id,
                'target_qty' => $request->target_qty,
                'actual_qty' => 0,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status,
                'priority' => $request->priority,
                'notes' => $request->notes,
                'created_by' => Auth::id()
            ]);
            
            // Simpan material requirements
            foreach ($request->materials as $materialData) {
                $productionOrder->productionMaterials()->create([
                    'material_id' => $materialData['material_id'],
                    'required_qty' => $materialData['required_qty'],
                    'unit_consumption' => $materialData['unit_consumption'] ?? null,
                    'notes' => $materialData['notes'] ?? null
                ]);
            }
        });
        
        return redirect()->route('admin.production-orders.index')
            ->with('success', 'Production Order berhasil dibuat.');
    }
    
    public function show(ProductionOrder $productionOrder)
    {
        $productionOrder->load([
            'product', 
            'productionMaterials.material', 
            'materialOut.material',
            'productIn',
            'createdByUser'
        ]);
        
        // Hitung progress
        $materialProgress = $this->calculateMaterialProgress($productionOrder);
        $productionProgress = $productionOrder->target_qty > 0 
            ? ($productionOrder->actual_qty / $productionOrder->target_qty) * 100 
            : 0;
        
        return view('admin.production-orders.show', compact('productionOrder', 'materialProgress', 'productionProgress'));
    }
    
    public function edit(ProductionOrder $productionOrder)
    {
        $products = Product::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();
        $productionOrder->load('productionMaterials');
        
        return view('admin.production-orders.edit', compact('productionOrder', 'products', 'materials'));
    }
    
    public function update(Request $request, ProductionOrder $productionOrder)
    {
        // Tidak bisa edit jika sudah ada material keluar
        if ($productionOrder->materialOut()->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak bisa mengedit PO yang sudah ada pengeluaran material.');
        }
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'target_qty' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:draft,waiting_material,process,done,cancelled',
            'notes' => 'nullable|string',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.required_qty' => 'required|numeric|min:0.01'
        ]);
        
        DB::transaction(function () use ($request, $productionOrder) {
            // Update order
            $productionOrder->update([
                'product_id' => $request->product_id,
                'target_qty' => $request->target_qty,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'priority' => $request->priority,
                'status' => $request->status,
                'notes' => $request->notes
            ]);
            
            // Hapus material lama
            $productionOrder->productionMaterials()->delete();
            
            // Tambah material baru
            foreach ($request->materials as $materialData) {
                $productionOrder->productionMaterials()->create([
                    'material_id' => $materialData['material_id'],
                    'required_qty' => $materialData['required_qty'],
                    'unit_consumption' => $materialData['unit_consumption'] ?? null,
                    'notes' => $materialData['notes'] ?? null
                ]);
            }
        });
        
        return redirect()->route('admin.production-orders.show', $productionOrder)
            ->with('success', 'Production Order berhasil diperbarui.');
    }
    
    public function destroy(ProductionOrder $productionOrder)
    {
        // Cek apakah sudah ada transaksi
        if ($productionOrder->materialOut()->exists() || $productionOrder->productIn()->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus PO yang sudah ada transaksi.');
        }
        
        DB::transaction(function () use ($productionOrder) {
            // Hapus material requirements
            $productionOrder->productionMaterials()->delete();
            // Hapus order
            $productionOrder->delete();
        });
        
        return redirect()->route('admin.production-orders.index')
            ->with('success', 'Production Order berhasil dihapus.');
    }
    
    public function updateStatus(Request $request, ProductionOrder $productionOrder)
    {
        $request->validate([
            'status' => 'required|in:draft,waiting_material,process,done,cancelled',
            'actual_qty' => 'nullable|integer|min:0'
        ]);
        
        $data = ['status' => $request->status];
        
        if ($request->status == 'done' && $request->filled('actual_qty')) {
            $data['actual_qty'] = $request->actual_qty;
        }
        
        $productionOrder->update($data);
        
        return redirect()->route('admin.production-orders.show', $productionOrder)
            ->with('success', 'Status Production Order berhasil diperbarui.');
    }
    
    public function print($id)
    {
        $productionOrder = ProductionOrder::with([
            'product', 
            'productionMaterials.material',
            'createdByUser'
        ])->findOrFail($id);
        
        return view('admin.production-orders.print', compact('productionOrder'));
    }
    
    private function calculateMaterialProgress($productionOrder)
    {
        $totalRequired = $productionOrder->productionMaterials->sum('required_qty');
        $totalIssued = $productionOrder->materialOut->sum('qty');
        
        if ($totalRequired > 0) {
            return ($totalIssued / $totalRequired) * 100;
        }
        
        return 0;
    }
}