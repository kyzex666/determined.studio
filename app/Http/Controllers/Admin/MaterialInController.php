<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaterialIn;
use App\Models\Material;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MaterialInController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index(Request $request)
    {
        $query = MaterialIn::with(['material', 'vendor', 'receivedBy']);
        
        // Filter berdasarkan tanggal
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }
        
        // Filter berdasarkan vendor
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }
        
        // Filter berdasarkan material
        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }
        
        $materialIn = $query->orderBy('date', 'desc')->paginate(20);
        $vendors = Vendor::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();
        
        return view('admin.material-in.index', compact('materialIn', 'vendors', 'materials'));
    }
    
    public function create()
    {
        $vendors = Vendor::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();
        
        return view('admin.material-in.create', compact('vendors', 'materials'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'material_id' => 'required|exists:materials,id',
            'qty' => 'required|numeric|min:0.01',
            'price_per_unit' => 'required|numeric|min:0',
            'invoice_number' => 'nullable|string|max:50',
            'date' => 'required|date',
            'note' => 'nullable|string'
        ]);
        
        DB::transaction(function () use ($request) {
            // Generate transaction code
            $year = date('Y', strtotime($request->date));
            $lastTransaction = MaterialIn::whereYear('date', $year)->count();
            $transactionCode = 'MI/' . $year . '/' . str_pad($lastTransaction + 1, 4, '0', STR_PAD_LEFT);
            
            $materialIn = MaterialIn::create([
                'transaction_code' => $transactionCode,
                'vendor_id' => $request->vendor_id,
                'material_id' => $request->material_id,
                'qty' => $request->qty,
                'price_per_unit' => $request->price_per_unit,
                'total_price' => $request->qty * $request->price_per_unit,
                'invoice_number' => $request->invoice_number,
                'date' => $request->date,
                'note' => $request->note,
                'received_by' => Auth::id()
            ]);
            
            // Update material stock
            $material = Material::find($request->material_id);
            $material->current_stock += $request->qty;
            $material->save();
        });
        
        return redirect()->route('admin.material-in.index')
            ->with('success', 'Material masuk berhasil dicatat.');
    }
    
    public function show(MaterialIn $materialIn)
    {
        $materialIn->load(['material', 'vendor', 'receivedBy']);
        return view('admin.material-in.show', compact('materialIn'));
    }
    
    public function edit(MaterialIn $materialIn)
    {
        $vendors = Vendor::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();
        
        return view('admin.material-in.edit', compact('materialIn', 'vendors', 'materials'));
    }
    
    public function update(Request $request, MaterialIn $materialIn)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'material_id' => 'required|exists:materials,id',
            'qty' => 'required|numeric|min:0.01',
            'price_per_unit' => 'required|numeric|min:0',
            'invoice_number' => 'nullable|string|max:50',
            'date' => 'required|date',
            'note' => 'nullable|string'
        ]);
        
        DB::transaction(function () use ($request, $materialIn) {
            // Simpan qty lama untuk adjustment
            $oldQty = $materialIn->qty;
            $oldMaterialId = $materialIn->material_id;
            
            // Update transaksi
            $materialIn->update([
                'vendor_id' => $request->vendor_id,
                'material_id' => $request->material_id,
                'qty' => $request->qty,
                'price_per_unit' => $request->price_per_unit,
                'total_price' => $request->qty * $request->price_per_unit,
                'invoice_number' => $request->invoice_number,
                'date' => $request->date,
                'note' => $request->note
            ]);
            
            // Adjust stock material lama
            if ($oldMaterialId != $request->material_id) {
                $oldMaterial = Material::find($oldMaterialId);
                $oldMaterial->current_stock -= $oldQty;
                $oldMaterial->save();
                
                // Tambah ke material baru
                $newMaterial = Material::find($request->material_id);
                $newMaterial->current_stock += $request->qty;
                $newMaterial->save();
            } else {
                // Material sama, adjust selisih
                $material = Material::find($request->material_id);
                $material->current_stock += ($request->qty - $oldQty);
                $material->save();
            }
        });
        
        return redirect()->route('admin.material-in.show', $materialIn)
            ->with('success', 'Material masuk berhasil diperbarui.');
    }
    
    public function destroy(MaterialIn $materialIn)
    {
        DB::transaction(function () use ($materialIn) {
            // Kurangi stock material
            $material = Material::find($materialIn->material_id);
            $material->current_stock -= $materialIn->qty;
            $material->save();
            
            // Hapus transaksi
            $materialIn->delete();
        });
        
        return redirect()->route('admin.material-in.index')
            ->with('success', 'Material masuk berhasil dihapus.');
    }
    
    public function print($id)
    {
        $materialIn = MaterialIn::with(['material', 'vendor', 'receivedBy'])->findOrFail($id);
        return view('admin.material-in.print', compact('materialIn'));
    }
}