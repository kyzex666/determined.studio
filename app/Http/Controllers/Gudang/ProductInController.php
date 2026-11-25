<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\ProductIn;
use App\Models\Product;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productIns = ProductIn::with([
                'product',
                'productionOrder',
                'receivedBy'
            ])
            ->latest('date')
            ->paginate(10);

        return view('gudang.product-in.index', compact('productIns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productionOrders = ProductionOrder::whereIn('status', ['process', 'done'])->get();
        $products = Product::all();

        return view('gudang.product-in.create', compact(
            'productionOrders',
            'products'
        ));
    }

    /**
     * STEP 5 — STORE
     * Menyimpan produk masuk ke gudang
     */
    public function store(Request $request)
    {
        $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'product_id'          => 'required|exists:products,id',
            'qty'                 => 'required|integer|min:1',
            'date'                => 'required|date',
            'condition'           => 'required|in:good,defect',
            'defect_notes'        => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {

            ProductIn::create([
                'production_order_id' => $request->production_order_id,
                'product_id'          => $request->product_id,
                'qty'                 => $request->qty,
                'date'                => $request->date,
                'condition'           => $request->condition,
                'defect_notes'        => $request->condition === 'defect'
                                            ? $request->defect_notes
                                            : null,
                'received_by'         => Auth::id()
            ]);

            // update actual_qty di production order
            $productionOrder = ProductionOrder::find($request->production_order_id);
            $totalGoodQty = $productionOrder->productIn()
                ->where('condition', 'good')
                ->sum('qty');

            $productionOrder->update([
                'actual_qty' => $totalGoodQty
            ]);
        });

        return redirect()
            ->route('gudang.product-in.index')
            ->with('success', 'Produk masuk berhasil disimpan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductIn $productIn)
    {
        $productionOrders = ProductionOrder::all();
        $products = Product::all();

        return view('gudang.product-in.edit', compact(
            'productIn',
            'productionOrders',
            'products'
        ));
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, ProductIn $productIn)
    {
        $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'product_id'          => 'required|exists:products,id',
            'qty'                 => 'required|integer|min:1',
            'date'                => 'required|date',
            'condition'           => 'required|in:good,defect',
            'defect_notes'        => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $productIn) {

            $productIn->update([
                'production_order_id' => $request->production_order_id,
                'product_id'          => $request->product_id,
                'qty'                 => $request->qty,
                'date'                => $request->date,
                'condition'           => $request->condition,
                'defect_notes'        => $request->condition === 'defect'
                                            ? $request->defect_notes
                                            : null,
            ]);

            $productionOrder = $productIn->productionOrder;

            $totalGoodQty = $productionOrder->productIn()
                ->where('condition', 'good')
                ->sum('qty');

            $productionOrder->update([
                'actual_qty' => $totalGoodQty
            ]);
        });

        return redirect()
            ->route('gudang.product-in.index')
            ->with('success', 'Data produk masuk berhasil diperbarui');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductIn $productIn)
    {
        $productIn->load(['product', 'productionOrder', 'receivedBy']);

        return view('gudang.product-in.show', compact('productIn'));
    }

    /**
     * Print view for the specified resource.
     */
    public function print(ProductIn $productIn)
    {
        $productIn->load(['product', 'productionOrder', 'receivedBy']);

        return view('gudang.product-in.print', compact('productIn'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductIn $productIn)
    {
        DB::transaction(function () use ($productIn) {

            $productionOrder = $productIn->productionOrder;

            $productIn->delete();

            $totalGoodQty = $productionOrder->productIn()
                ->where('condition', 'good')
                ->sum('qty');

            $productionOrder->update([
                'actual_qty' => $totalGoodQty
            ]);
        });

        return redirect()
            ->route('gudang.product-in.index')
            ->with('success', 'Data produk masuk berhasil dihapus');
    }
}
