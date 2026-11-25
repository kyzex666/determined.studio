<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $products = Product::orderBy('name')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * ==============================
     * 🔥 GENERATE SKU OTOMATIS
     * ==============================
     */
    protected function generateSkuValue(): string
    {
        $year = Carbon::now()->year;

        $lastProduct = Product::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 0;

        if ($lastProduct && preg_match('/(\d+)$/', $lastProduct->sku, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "PRD-{$year}-{$nextNumber}";
    }

    /**
     * (OPSIONAL) untuk AJAX / preview
     */
    public function generateSku()
    {
        return response()->json([
            'sku' => $this->generateSkuValue()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'sku'   => 'nullable|string|unique:products,sku',
            'unit'  => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // 🔥 AUTO SKU JIKA KOSONG (SAMA KAYAK VENDOR)
        if (empty($validated['sku'])) {
            $validated['sku'] = $this->generateSkuValue();
        }

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Product $product)
    {
        $productionOrders = $product->productionOrders()
            ->orderBy('created_at', 'desc')
            ->get();

        $productIn = $product->productIn()
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.products.show', compact('product', 'productionOrders', 'productIn'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'sku'  => 'required|string|max:50|unique:products,sku,' . $product->id,
            'unit' => 'required|string|max:10',
            'current_stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $hasTransactions =
            $product->productionOrders()->exists() ||
            $product->productIn()->exists();

        if ($hasTransactions) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Produk tidak bisa dihapus karena sudah memiliki transaksi.');
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
