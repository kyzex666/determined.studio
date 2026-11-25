<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index()
    {
        $vendors = Vendor::orderBy('name')->get();
        return view('admin.vendors.index', compact('vendors'));
    }
    
    public function create()
    {
        return view('admin.vendors.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'pic_name' => 'nullable|string|max:100',
            'pic_phone' => 'nullable|string|max:20'
        ]);
        
        $vendor = Vendor::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'pic_name' => $request->pic_name,
            'pic_phone' => $request->pic_phone
        ]);
        
        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil ditambahkan.');
    }
    
    public function show(Vendor $vendor)
    {
        return view('admin.vendors.show', compact('vendor'));
    }
    
    public function edit(Vendor $vendor)
    {
        return view('admin.vendors.edit', compact('vendor'));
    }
    
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string'
        ]);
        
        $vendor->update($request->all());
        
        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil diperbarui.');
    }
    
    public function destroy(Vendor $vendor)
    {
        // Cek apakah vendor punya transaksi
        $hasTransactions = $vendor->materialIn()->exists();
        
        if ($hasTransactions) {
            return redirect()->route('vendors.index')
                ->with('error', 'Vendor tidak bisa dihapus karena sudah memiliki transaksi.');
        }
        
        $vendor->delete();
        
        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil dihapus.');
    }
}