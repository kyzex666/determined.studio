<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionOrder;
use App\Models\Material;
use App\Models\MaterialOut;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:gudang,admin']);
    }
    
public function index(Request $request)
{
    $query = ProductionOrder::whereIn('status', ['draft', 'waiting_material'])
        ->where('status', '!=', 'cancelled')
        ->with(['product', 'creator']);

    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    $query->orderByRaw("
        CASE priority
            WHEN 'high' THEN 1
            WHEN 'medium' THEN 2
            WHEN 'low' THEN 3
        END
    ")->orderBy('created_at');

    $requests = $query->paginate(15)->withQueryString();

    // Statistik
    $totalPending = ProductionOrder::whereIn('status', ['draft', 'waiting_material'])
        ->where('status', '!=', 'cancelled')
        ->count();

    $totalPriority = [
        'high'   => ProductionOrder::whereIn('status', ['draft', 'waiting_material'])->where('status', '!=', 'cancelled')->where('priority', 'high')->count(),
        'medium' => ProductionOrder::whereIn('status', ['draft', 'waiting_material'])->where('status', '!=', 'cancelled')->where('priority', 'medium')->count(),
        'low'    => ProductionOrder::whereIn('status', ['draft', 'waiting_material'])->where('status', '!=', 'cancelled')->where('priority', 'low')->count(),
    ];
    return view('gudang.requests.index', compact(
        'requests',
        'totalPending',
        'totalPriority'
    ));
}



    
    public function show($id)
    {
        $productionOrder = ProductionOrder::with([
            'product',
            'materials.material',
            'creator'
        ])->findOrFail($id);

        if (! in_array($productionOrder->status, ['draft', 'waiting_material'], true)) {
            return redirect()->route('gudang.requests.index')
                ->with('error', 'Request ini sudah diproses atau tidak dalam status yang dapat diproses.');
        }

        // Hitung material yang sudah dikeluarkan
        $issuedMaterials = [];
        $allMaterialsAvailable = true;

        foreach ($productionOrder->materials as $pm) {
            $issuedQty = $productionOrder->materialOuts()
                ->where('material_id', $pm->material_id)
                ->sum('qty');

            $remainingRequired = $pm->required_qty - $issuedQty;
            $materialStock = $pm->material->current_stock;
            $isAvailable = $materialStock >= $remainingRequired;

            if (!$isAvailable) {
                $allMaterialsAvailable = false;
            }

            $issuedMaterials[] = [
                'material' => $pm->material,
                'required_qty' => $pm->required_qty,
                'issued_qty' => $issuedQty,
                'remaining_required' => $remainingRequired,
                'current_stock' => $materialStock,
                'is_available' => $isAvailable,
                'unit' => $pm->material->unit
            ];
        }
        
        $product = $productionOrder->product;

        return view('gudang.requests.show', compact(
            'productionOrder',
            'product',
            'issuedMaterials',
            'allMaterialsAvailable'
        ));
    }
    
    public function process(Request $request, $id)
    {
        $productionOrder = ProductionOrder::with([
            'materials.material'
        ])->findOrFail($id);

        if (! in_array($productionOrder->status, ['draft', 'waiting_material'], true)) {
            return redirect()->route('gudang.requests.index')
                ->with('error', 'Request sudah diproses sebelumnya atau tidak dalam status yang dapat diproses.');
        }

        $unavailableMaterials = [];

        foreach ($productionOrder->materials as $pm) {
            $issuedQty = $productionOrder->materialOuts()
                ->where('material_id', $pm->material_id)
                ->sum('qty');

            $remainingRequired = $pm->required_qty - $issuedQty;

            if ($remainingRequired > 0) {
                $materialStock = $pm->material->current_stock;

                if ($materialStock < $remainingRequired) {
                    $unavailableMaterials[] = [
                        'name'      => $pm->material->name,
                        'required'  => $remainingRequired,
                        'available' => $materialStock,
                        'unit'      => $pm->material->unit,
                    ];
                }
            }
        }

        if (!empty($unavailableMaterials)) {
            return redirect()->route('gudang.requests.show', $id)
                ->with('error', 'Beberapa material tidak tersedia dalam jumlah cukup.')
                ->with('unavailableMaterials', $unavailableMaterials);
        }

        DB::transaction(function () use ($productionOrder) {
            foreach ($productionOrder->materials as $pm) {
                $issuedQty = $productionOrder->materialOuts()
                    ->where('material_id', $pm->material_id)
                    ->sum('qty');

                $remainingRequired = $pm->required_qty - $issuedQty;

                if ($remainingRequired <= 0) {
                    continue;
                }

                $material = $pm->material;

                // Create MaterialOut
                MaterialOut::create([
                    'production_order_id' => $productionOrder->id,
                    'material_id' => $pm->material_id,
                    'qty' => $remainingRequired,
                    'date' => now(),
                    'issued_by' => Auth::id(),
                    'notes' => 'Issue from gudang process'
                ]);

                // Deduct stock
                $material->current_stock -= $remainingRequired;
                $material->save();

                // Update issued_qty on production material
                $pm->issued_qty = ($pm->issued_qty ?? 0) + $remainingRequired;
                $pm->save();
            }

            $productionOrder->status = 'process';
            $productionOrder->save();
        });

        return redirect()->route('gudang.requests.index')
            ->with('success', 'Request bahan berhasil diproses.');
    }

    
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);
        
        $productionOrder = ProductionOrder::findOrFail($id);

        if (! in_array($productionOrder->status, ['draft', 'waiting_material'], true)) {
            return redirect()->route('gudang.requests.index')
                ->with('error', 'Request sudah diproses atau diselesaikan.');
        }

        DB::transaction(function () use ($productionOrder, $request) {
            $notes = $productionOrder->notes ?? '';
            $userName = Auth::user()?->name ?? 'Gudang';
            $rejectionNote = "\n\n[Ditolak oleh Gudang - " . date('Y-m-d H:i') . " oleh " . $userName . "]\nAlasan: " . $request->rejection_reason;

            $productionOrder->update([
                'status' => 'cancelled',
                'notes' => $notes . $rejectionNote
            ]);
        });

        return redirect()->route('gudang.requests.index')
            ->with('success', 'Request bahan berhasil ditolak.');
    }
    
    public function partialProcess(Request $request, $id)
    {
        $productionOrder = ProductionOrder::with('materials.material')->findOrFail($id);

        if ($productionOrder->status != 'waiting_material') {
            return redirect()->route('gudang.requests.show', $id)
                ->with('error', 'Request sudah diproses sebelumnya.');
        }

        $request->validate([
            'materials' => 'required|array',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.qty' => 'required|numeric|min:0.01'
        ]);

        DB::transaction(function () use ($productionOrder, $request) {
            foreach ($request->materials as $materialData) {
                $material = Material::find($materialData['material_id']);

                if ($materialData['qty'] > 0) {
                    if ($material->current_stock < $materialData['qty']) {
                        throw new \Exception("Stok {$material->name} tidak cukup. Tersedia: {$material->current_stock}");
                    }

                    MaterialOut::create([
                        'production_order_id' => $productionOrder->id,
                        'material_id' => $materialData['material_id'],
                        'qty' => $materialData['qty'],
                        'date' => now(),
                        'issued_by' => Auth::id(),
                        'notes' => 'Partial issue dari request bahan'
                    ]);

                    $material->current_stock -= $materialData['qty'];
                    $material->save();

                    // Update corresponding production material issued_qty
                    $pm = $productionOrder->materials->firstWhere('material_id', $materialData['material_id']);
                    if ($pm) {
                        $pm->issued_qty = ($pm->issued_qty ?? 0) + $materialData['qty'];
                        $pm->save();
                    }
                }
            }

            $allMaterialsFulfilled = true;
            foreach ($productionOrder->materials as $pm) {
                if (($pm->issued_qty ?? 0) < $pm->required_qty) {
                    $allMaterialsFulfilled = false;
                    break;
                }
            }

            if ($allMaterialsFulfilled) {
                $productionOrder->status = 'process';
            } else {
                $productionOrder->status = 'waiting_material';
                $productionOrder->notes = ($productionOrder->notes ?? '') . "\n[Partial material issued on " . date('Y-m-d H:i') . "]";
            }

            $productionOrder->save();
        });

        return redirect()->route('gudang.requests.show', $id)
            ->with('success', 'Material berhasil dikeluarkan sebagian.');
    }
    
    public function history(Request $request)
    {
        $query = ProductionOrder::where('status', '!=', 'waiting_material')
            ->with(['product', 'creator'])
            ->whereHas('materialOuts');

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('updated_at', [$request->date_from, $request->date_to]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $history = $query->orderBy('updated_at', 'desc')->paginate(20);

        return view('gudang.requests.history', compact('history'));
    }
    
    public function getMaterialAvailability($orderId, $materialId)
    {
        $productionOrder = ProductionOrder::with(['materials' => function($q) use ($materialId) {
            $q->where('material_id', $materialId);
        }])->findOrFail($orderId);

        $material = Material::findOrFail($materialId);

        $issuedQty = $productionOrder->materialOuts()
            ->where('material_id', $materialId)
            ->sum('qty');

        $requiredQty = $productionOrder->materials->first()->required_qty ?? 0;
        $remainingRequired = $requiredQty - $issuedQty;

        return response()->json([
            'material_name' => $material->name,
            'current_stock' => $material->current_stock,
            'required_qty' => $requiredQty,
            'issued_qty' => $issuedQty,
            'remaining_required' => $remainingRequired,
            'unit' => $material->unit,
            'is_available' => $material->current_stock >= $remainingRequired
        ]);
    }
}
