<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaterialIn;
use App\Models\Material;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialInController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:gudang,admin']);
    }

    public function index(Request $request)
    {
        $query = MaterialIn::with(['material', 'vendor', 'receivedBy']);

        // Filter tanggal
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Filter vendor
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Filter material
        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }

        $query->orderBy('date', 'desc');

        $materialIn = $query->paginate(20)->withQueryString();

        $vendors   = Vendor::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();

        // Ringkasan (tanpa nilai uang)
        $totalQty   = $materialIn->sum('qty');
        $totalValue = 0; // supaya blade tidak error

        return view('gudang.material-in.index', compact(
            'materialIn',
            'vendors',
            'materials',
            'totalQty',
            'totalValue'
        ));
    }

    public function create()
    {
        $vendors   = Vendor::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();

        return view('gudang.material-in.create', compact('vendors', 'materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id'   => 'required|exists:vendors,id',
            'material_id' => 'required|exists:materials,id',
            'qty'         => 'required|numeric|min:0.01',
            'date'        => 'required|date',
            'note'        => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {

            MaterialIn::create([
                'vendor_id'   => $request->vendor_id,
                'material_id' => $request->material_id,
                'qty'         => $request->qty,
                'date'        => $request->date,
                'note'        => $request->note,
                'received_by' => Auth::id(),
            ]);

            // Update stok material
            $material = Material::findOrFail($request->material_id);
            $material->increaseStock($request->qty);
        });

        return redirect()
            ->route('gudang.material-in.index')
            ->with('success', 'Material masuk berhasil dicatat.');
    }

    public function show($id)
    {
        $materialIn = MaterialIn::with(['material', 'vendor', 'receivedBy'])
            ->findOrFail($id);

        return view('gudang.material-in.show', compact('materialIn'));
    }

    public function print($id)
    {
        $materialIn = MaterialIn::with(['material', 'vendor', 'receivedBy'])
            ->findOrFail($id);

        return view('gudang.material-in.print', compact('materialIn'));
    }

    public function getMaterialInfo($id)
    {
        $material = Material::findOrFail($id);

        return response()->json([
            'id'            => $material->id,
            'name'          => $material->name,
            'unit'          => $material->unit,
            'current_stock' => $material->current_stock,
            'min_stock'     => $material->min_stock,
        ]);
    }

    public function getVendorInfo($id)
    {
        $vendor = Vendor::findOrFail($id);

        return response()->json([
            'id'      => $vendor->id,
            'name'    => $vendor->name,
            'address' => $vendor->address,
            'phone'   => $vendor->phone,
        ]);
    }
}
