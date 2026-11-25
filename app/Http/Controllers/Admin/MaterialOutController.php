<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaterialOut;
use App\Models\Material;
use App\Models\ProductionOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MaterialOutController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index(Request $request)
    {
        $query = MaterialOut::with(['material', 'productionOrder.product', 'issuedBy']);
        
        // Filter
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        }
        
        if ($request->filled('production_order_id')) {
            $query->where('production_order_id', $request->production_order_id);
        }
        
        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }
        
        $materialOut = $query->orderBy('date', 'desc')->paginate(20);
        $materials = Material::orderBy('name')->get();
        $productionOrders = ProductionOrder::with('product')->get();
        
        return view('admin.material-out.index', compact('materialOut', 'materials', 'productionOrders'));
    }
    
    public function create()
    {
        $materials = Material::orderBy('name')->get();
        $productionOrders = ProductionOrder::whereIn('status', ['waiting_material', 'process'])
            ->with('product')
            ->get();
        
        return view('admin.material-out.create', compact('materials', 'productionOrders'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'material_id' => 'required|exists:materials,id',
            'qty' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);
        
        DB::transaction(function () use ($request) {
            // Cek stok
            $material = Material::find($request->material_id);
            if ($material->current_stock < $request->qty) {
                throw new \Exception('Stok tidak cukup. Stok tersedia: ' . $material->current_stock);
            }
            
            // Generate transaction code
            $year = date('Y', strtotime($request->date));
            $lastTransaction = MaterialOut::whereYear('date', $year)->count();
            $transactionCode = 'MO/' . $year . '/' . str_pad($lastTransaction + 1, 4, '0', STR_PAD_LEFT);
            
            $materialOut = MaterialOut::create([
                'transaction_code' => $transactionCode,
                'production_order_id' => $request->production_order_id,
                'material_id' => $request->material_id,
                'qty' => $request->qty,
                'date' => $request->date,
                'notes' => $request->notes,
                'issued_by' => Auth::id()
            ]);
            
            // Update stock
            $material->current_stock -= $request->qty;
            $material->save();
            
            // Update production order status jika perlu
            $this->updateProductionOrderStatus($request->production_order_id);
        });
        
        return redirect()->route('admin.material-out.index')
            ->with('success', 'Material keluar berhasil dicatat.');
    }
    
    public function show(MaterialOut $materialOut)
    {
        $materialOut->load(['material', 'productionOrder.product', 'issuedBy']);
        return view('admin.material-out.show', compact('materialOut'));
    }
    
    public function edit(MaterialOut $materialOut)
    {
        $materials = Material::orderBy('name')->get();
        $productionOrders = ProductionOrder::with('product')->get();
        
        return view('admin.material-out.edit', compact('materialOut', 'materials', 'productionOrders'));
    }
    
    public function update(Request $request, MaterialOut $materialOut)
    {
        $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'material_id' => 'required|exists:materials,id',
            'qty' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);
        
        DB::transaction(function () use ($request, $materialOut) {
            $oldQty = $materialOut->qty;
            $oldMaterialId = $materialOut->material_id;
            
            // Adjust old material stock
            $oldMaterial = Material::find($oldMaterialId);
            $oldMaterial->current_stock += $oldQty;
            $oldMaterial->save();
            
            // Cek stok baru
            $newMaterial = Material::find($request->material_id);
            if ($newMaterial->current_stock < $request->qty) {
                // Rollback old material adjustment
                $oldMaterial->current_stock -= $oldQty;
                $oldMaterial->save();
                throw new \Exception('Stok tidak cukup. Stok tersedia: ' . $newMaterial->current_stock);
            }
            
            // Update transaction
            $materialOut->update([
                'production_order_id' => $request->production_order_id,
                'material_id' => $request->material_id,
                'qty' => $request->qty,
                'date' => $request->date,
                'notes' => $request->notes
            ]);
            
            // Update new material stock
            $newMaterial->current_stock -= $request->qty;
            $newMaterial->save();
        });
        
        return redirect()->route('admin.material-out.show', $materialOut)
            ->with('success', 'Material keluar berhasil diperbarui.');
    }
    
    public function destroy(MaterialOut $materialOut)
    {
        DB::transaction(function () use ($materialOut) {
            // Kembalikan stock
            $material = Material::find($materialOut->material_id);
            $material->current_stock += $materialOut->qty;
            $material->save();
            
            // Hapus transaksi
            $materialOut->delete();
        });
        
        return redirect()->route('admin.material-out.index')
            ->with('success', 'Material keluar berhasil dihapus.');
    }
    
    private function updateProductionOrderStatus($productionOrderId)
    {
        $productionOrder = ProductionOrder::with('productionMaterials')->find($productionOrderId);
        
        // Logika untuk update status production order
        // Bisa ditambahkan sesuai kebutuhan bisnis
    }
}