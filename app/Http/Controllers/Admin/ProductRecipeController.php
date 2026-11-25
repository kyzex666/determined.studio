<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Material;
use App\Models\ProductMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductRecipeController extends Controller
{
    // Menampilkan halaman form atur resep
    public function edit($productId)
    {
        $product = Product::findOrFail($productId);
        $materials = Material::all(); // Ambil semua bahan baku
        
        // Ambil resep yang sudah ada untuk produk ini (biar form terisi otomatis)
        $existingRecipe = ProductMaterial::where('product_id', $productId)
                            ->pluck('quantity', 'material_id')
                            ->toArray();

        return view('admin.products.recipe', compact('product', 'materials', 'existingRecipe'));
    }

    // Menyimpan perubahan resep
    public function update(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $recipes = $request->input('recipes', []); // Array [material_id => quantity]

        DB::transaction(function () use ($product, $recipes) {
            // 1. Hapus resep lama (cara paling gampang untuk update)
            ProductMaterial::where('product_id', $product->id)->delete();

            // 2. Simpan resep baru
            foreach ($recipes as $materialId => $quantity) {
                // Hanya simpan jika quantity diisi dan lebih dari 0
                if ($quantity > 0) {
                    ProductMaterial::create([
                        'product_id' => $product->id,
                        'material_id' => $materialId,
                        'quantity' => $quantity
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Resep bahan untuk ' . $product->name . ' berhasil diperbarui!');
    }
}