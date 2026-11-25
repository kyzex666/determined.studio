<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index()
    {
        $materials = Material::orderBy('name')->get();
        return view('admin.materials.index', compact('materials'));
    }
    
    public function create()
    {
        return view('admin.materials.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
            'current_stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
            'notes' => 'nullable|string'
        ]);
        
        Material::create($request->all());
        
        return redirect()->route('materials.index')
            ->with('success', 'Bahan baku berhasil ditambahkan.');
    }
    
    public function show(Material $material)
    {
        $materialIn = $material->materialIn()->orderBy('date', 'desc')->limit(10)->get();
        $materialOut = $material->materialOut()->orderBy('date', 'desc')->limit(10)->get();
        
        return view('admin.materials.show', compact('material', 'materialIn', 'materialOut'));
    }
    
    public function edit(Material $material)
    {
        return view('admin.materials.edit', compact('material'));
    }
    
    public function update(Request $request, Material $material)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
            'current_stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
            'notes' => 'nullable|string'
        ]);
        
        $material->update($request->all());
        
        return redirect()->route('materials.index')
            ->with('success', 'Bahan baku berhasil diperbarui.');
    }
    
    public function destroy(Material $material)
    {
        // Cek apakah material punya transaksi
        $hasTransactions = $material->materialIn()->exists() || $material->materialOut()->exists();
        
        if ($hasTransactions) {
            return redirect()->route('materials.index')
                ->with('error', 'Material tidak bisa dihapus karena sudah memiliki transaksi.');
        }
        
        $material->delete();
        
        return redirect()->route('materials.index')
            ->with('success', 'Material berhasil dihapus.');
    }
    
    public function adjustStock(Request $request, Material $material)
    {
        $request->validate([
            'adjustment_type' => 'required|in:opname,correction,defect,sample',
            'new_stock' => 'required|numeric|min:0',
            'reason' => 'required|string'
        ]);
        
        $old_stock = $material->current_stock;
        $material->current_stock = $request->new_stock;
        $material->save();
        
        // Log adjustment (bisa dibuat tabel khusus jika perlu)
        
        return redirect()->route('materials.show', $material)
            ->with('success', 'Stok berhasil disesuaikan dari ' . $old_stock . ' menjadi ' . $request->new_stock);
    }
}