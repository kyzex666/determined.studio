<?php

namespace App\Http\Controllers\Gudang;

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
        $this->middleware(['auth', 'role:gudang,admin']);
    }

    public function index(Request $request)
    {
        $query = MaterialOut::with([
            'material',
            'productionOrder.product',
            'issuedBy'
        ]);

        // Filter tanggal
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Filter Production Order
        if ($request->filled('production_order_id')) {
            $query->where('production_order_id', $request->production_order_id);
        }

        // Filter material
        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }

        $query->orderBy('date', 'desc');

        $materialOut = $query->paginate(20)->withQueryString();

        $materials = Material::orderBy('name')->get();
        $productionOrders = ProductionOrder::with('product')->get();

        $totalQty = $materialOut->sum('qty');

        return view('gudang.material-out.index', compact(
            'materialOut',
            'materials',
            'productionOrders',
            'totalQty'
        ));
    }

    public function create()
    {
        $materials = Material::orderBy('name')->get();

        // PO yang boleh keluar material
        $productionOrders = ProductionOrder::whereIn('status', [
                'waiting_material',
                'process'
            ])
            ->with(['product', 'productionMaterials.material'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('gudang.material-out.create', compact(
            'materials',
            'productionOrders'
        ));
    }

   public function store(Request $request)
{
    $request->validate([
        'production_order_id' => 'required|exists:production_orders,id',
        'materials' => 'required|array',
        'materials.*.material_id' => 'required|exists:materials,id',
        'materials.*.qty' => 'required|numeric|min:0',
        'date' => 'required|date',
    ]);

    DB::transaction(function () use ($request) {

        $po = ProductionOrder::with('productionMaterials.material')
            ->findOrFail($request->production_order_id);

        if ($po->status !== 'waiting_material') {
            throw new \Exception('PO tidak dalam status menunggu bahan.');
        }

        foreach ($request->materials as $item) {

            if ($item['qty'] <= 0) continue;

            $pm = $po->productionMaterials
                ->where('material_id', $item['material_id'])
                ->first();

            if (!$pm) {
                throw new \Exception('Material tidak terdaftar di PO.');
            }

            $issued = MaterialOut::where('production_order_id', $po->id)
                ->where('material_id', $item['material_id'])
                ->sum('qty');

            $remaining = $pm->required_qty - $issued;

            if ($item['qty'] > $remaining) {
                throw new \Exception(
                    "Qty {$pm->material->name} melebihi kebutuhan. Sisa: {$remaining}"
                );
            }

            if ($pm->material->current_stock < $item['qty']) {
                throw new \Exception(
                    "Stok {$pm->material->name} tidak cukup."
                );
            }

            MaterialOut::create([
                'production_order_id' => $po->id,
                'material_id' => $item['material_id'],
                'qty' => $item['qty'],
                'date' => $request->date,
                'issued_by' => auth()->id(),
            ]);

            $pm->material->decrement('current_stock', $item['qty']);
        }

        // 🔁 UPDATE STATUS PO
        $allIssued = true;
        foreach ($po->productionMaterials as $pm) {
            $issued = MaterialOut::where('production_order_id', $po->id)
                ->where('material_id', $pm->material_id)
                ->sum('qty');

            if ($issued < $pm->required_qty) {
                $allIssued = false;
                break;
            }
        }

        if ($allIssued) {
            $po->update(['status' => 'process']);
        }
    });

    return redirect()->route('gudang.material-out.index')
        ->with('success', 'Material berhasil dikeluarkan.');
}


    public function show($id)
    {
        $materialOut = MaterialOut::with([
            'material',
            'productionOrder.product',
            'issuedBy'
        ])->findOrFail($id);

        return view('gudang.material-out.show', compact('materialOut'));
    }

    public function print($id)
    {
        $materialOut = MaterialOut::with([
            'material',
            'productionOrder.product',
            'issuedBy'
        ])->findOrFail($id);

        return view('gudang.material-out.print', compact('materialOut'));
    }

    private function updateProductionOrderStatus(ProductionOrder $productionOrder)
    {
        $allFulfilled = true;

        foreach ($productionOrder->productionMaterials as $pm) {
            $issuedQty = $productionOrder->materialOut()
                ->where('material_id', $pm->material_id)
                ->sum('qty');

            if ($issuedQty < $pm->required_qty) {
                $allFulfilled = false;
                break;
            }
        }

        if ($allFulfilled && $productionOrder->status === 'waiting_material') {
            $productionOrder->status = 'process';
            $productionOrder->save();
        }
    }
}
