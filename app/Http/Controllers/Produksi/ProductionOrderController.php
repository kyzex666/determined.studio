<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use App\Models\ProductIn;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductMaterial;
use App\Models\ProductionMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductionOrderController extends Controller
{
    public function index()
    {
        $orders = ProductionOrder::with('product')
            ->latest()
            ->get();

        return view('produksi.production-orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::all();
        return view('produksi.production-orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'target_qty' => 'required|integer|min:1',
            'start_date' => 'required|date',            // Input ini harus ada di View
            'priority'   => 'required|in:low,medium,high', // Validasi priority
            'notes'      => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                // 2. Cek apakah Product punya resep (BOM) sebelum membuat order
                // Sesuaikan nama kolom quantity (misal: 'quantity' atau 'qty_per_unit')
                $productMaterials = ProductMaterial::where('product_id', $request->product_id)->get();

                if ($productMaterials->isEmpty()) {
                    throw new \Exception('Gagal: Produk ini belum memiliki resep bahan (BOM). Silakan atur Master Data terlebih dahulu.');
                }

                // 3. Create Production Order
                $order = ProductionOrder::create([
                    'order_code' => 'PO-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4)),
                    'product_id' => $request->product_id,
                    'target_qty' => $request->target_qty,
                    'start_date' => $request->start_date,
                    'priority'   => $request->priority, // Simpan Priority
                    'status'     => 'draft',            // Default status
                    'notes'      => $request->notes,
                    'created_by' => Auth::id(),
                ]);

                // 4. Hitung & Copy ke production_materials
                foreach ($productMaterials as $pm) {
                    // Asumsi: kolom jumlah di tabel product_materials adalah 'quantity' atau 'qty_per_unit'
                    // Ganti $pm->qty_per_unit sesuai nama kolom di database kamu (misal: $pm->quantity)
                    $qtyPerUnit = $pm->qty_per_unit ?? $pm->quantity; 

                    ProductionMaterial::create([
                        'production_order_id' => $order->id,
                        'material_id'         => $pm->material_id,
                        'required_qty'        => $qtyPerUnit * $request->target_qty,
                        'issued_qty'          => 0,
                        'status'              => 'pending',
                    ]);
                }
            });

            return redirect()
                ->route('produksi.production-orders.index')
                ->with('success', 'Production Order berhasil dibuat dan kebutuhan bahan telah dihitung.');

        } catch (\Exception $e) {
            // Jika ada error (misal resep kosong), kembali ke form dengan pesan error
            return back()
                ->withInput() // Mengembalikan isian form sebelumnya
                ->withErrors(['general_error' => $e->getMessage()]);
        }
    }

    public function show(ProductionOrder $productionOrder)
    {
        $order = $productionOrder->load([
            'product',
            'materials.material'
        ]);

        return view('produksi.production-orders.show', compact('order'));
    }

    public function complete(Request $request, ProductionOrder $productionOrder)
    {
        $request->validate([
            'actual_qty' => 'required|integer|min:0',
            'condition' => 'required|in:good,defect',
            'defect_notes' => 'nullable|string|max:500',
            'completion_date' => 'required|date'
        ]);

        if ($productionOrder->status !== 'process') {
            return redirect()->back()->with('error', 'Production order harus dalam status process untuk dapat diselesaikan.');
        }

        DB::transaction(function () use ($request, $productionOrder) {
            // Update production order
            $productionOrder->update([
                'actual_qty' => $request->actual_qty,
                'end_date' => $request->completion_date,
                'status' => 'done'
            ]);

            // Create ProductIn record
            ProductIn::create([
                'production_order_id' => $productionOrder->id,
                'product_id' => $productionOrder->product_id,
                'qty' => $request->actual_qty,
                'date' => $request->completion_date,
                'condition' => $request->condition,
                'defect_notes' => $request->condition === 'defect' ? $request->defect_notes : null,
                'received_by' => Auth::id()
            ]);
        });

        return redirect()->route('produksi.production-orders.index')
            ->with('success', 'Production order berhasil diselesaikan dan produk dikirim ke gudang.');
    }
}