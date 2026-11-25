<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductIn;
use App\Models\Product;
use App\Models\ProductionOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductInController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index(Request $request)
    {
        $query = ProductIn::with(['product', 'productionOrder', 'receivedBy']);
        
        // Filter
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        }
        
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('production_order_id')) {
            $query->where('production_order_id', $request->production_order_id);
        }
        
        $productIn = $query->orderBy('date', 'desc')->paginate(20);
        $products = Product::orderBy('name')->get();
        $productionOrders = ProductionOrder::with('product')->get();
        
        return view('admin.product-in.index', compact('productIn', 'products', 'productionOrders'));
    }
    
    public function create()
    {
        $products = Product::orderBy('name')->get();
        $productionOrders = ProductionOrder::where('status', 'done')
            ->with('product')
            ->get();
        
        return view('admin.product-in.create', compact('products', 'productionOrders'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'date' => 'required|date',
            'condition' => 'required|in:good,defect',
            'defect_notes' => 'nullable|required_if:condition,defect|string'
        ]);
        
        DB::transaction(function () use ($request) {
            // Cek production order
            $productionOrder = ProductionOrder::find($request->production_order_id);
            
            // Cek sisa yang belum masuk gudang
            $alreadyIn = $productionOrder->productIn()->sum('qty');
            $remaining = $productionOrder->actual_qty - $alreadyIn;
            
            if ($request->qty > $remaining) {
                throw new \Exception('Jumlah melebihi sisa produksi. Sisa: ' . $remaining);
            }
            
            // Generate transaction code
            $year = date('Y', strtotime($request->date));
            $lastTransaction = ProductIn::whereYear('date', $year)->count();
            $transactionCode = 'PI/' . $year . '/' . str_pad($lastTransaction + 1, 4, '0', STR_PAD_LEFT);
            
            $productIn = ProductIn::create([
                'transaction_code' => $transactionCode,
                'production_order_id' => $request->production_order_id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'date' => $request->date,
                'condition' => $request->condition,
                'defect_notes' => $request->defect_notes,
                'received_by' => Auth::id()
            ]);
            
            // Update product stock jika kondisi good
            if ($request->condition == 'good') {
                $product = Product::find($request->product_id);
                $product->current_stock += $request->qty;
                $product->save();
            }
        });
        
        return redirect()->route('admin.product-in.index')
            ->with('success', 'Barang jadi masuk berhasil dicatat.');
    }
    
    public function show(ProductIn $productIn)
    {
        $productIn->load(['product', 'productionOrder', 'receivedBy']);
        return view('admin.product-in.show', compact('productIn'));
    }
    
    public function edit(ProductIn $productIn)
    {
        $products = Product::orderBy('name')->get();
        $productionOrders = ProductionOrder::with('product')->get();
        
        return view('admin.product-in.edit', compact('productIn', 'products', 'productionOrders'));
    }
    
    public function update(Request $request, ProductIn $productIn)
    {
        $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'date' => 'required|date',
            'condition' => 'required|in:good,defect',
            'defect_notes' => 'nullable|required_if:condition,defect|string'
        ]);
        
        DB::transaction(function () use ($request, $productIn) {
            $oldQty = $productIn->qty;
            $oldCondition = $productIn->condition;
            $oldProductId = $productIn->product_id;
            
            // Adjust old stock jika kondisi good
            if ($oldCondition == 'good') {
                $oldProduct = Product::find($oldProductId);
                $oldProduct->current_stock -= $oldQty;
                $oldProduct->save();
            }
            
            // Update transaction
            $productIn->update([
                'production_order_id' => $request->production_order_id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'date' => $request->date,
                'condition' => $request->condition,
                'defect_notes' => $request->defect_notes
            ]);
            
            // Update new stock jika kondisi good
            if ($request->condition == 'good') {
                $product = Product::find($request->product_id);
                $product->current_stock += $request->qty;
                $product->save();
            }
        });
        
        return redirect()->route('admin.product-in.show', $productIn)
            ->with('success', 'Barang jadi masuk berhasil diperbarui.');
    }
    
    public function destroy(ProductIn $productIn)
    {
        DB::transaction(function () use ($productIn) {
            // Kembalikan stock jika kondisi good
            if ($productIn->condition == 'good') {
                $product = Product::find($productIn->product_id);
                $product->current_stock -= $productIn->qty;
                $product->save();
            }
            
            // Hapus transaksi
            $productIn->delete();
        });
        
        return redirect()->route('admin.product-in.index')
            ->with('success', 'Barang jadi masuk berhasil dihapus.');
    }
    
    public function getProductionOrderInfo($id)
    {
        $productionOrder = ProductionOrder::with('product')->findOrFail($id);
        $alreadyIn = $productionOrder->productIn()->sum('qty');
        $remaining = $productionOrder->actual_qty - $alreadyIn;
        
        return response()->json([
            'product_id' => $productionOrder->product_id,
            'product_name' => $productionOrder->product->name,
            'actual_qty' => $productionOrder->actual_qty,
            'already_in' => $alreadyIn,
            'remaining' => $remaining
        ]);
    }
}