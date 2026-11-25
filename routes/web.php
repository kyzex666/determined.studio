<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Gudang\DashboardController as GudangDashboardController;
use App\Http\Controllers\Produksi\DashboardController as ProduksiDashboardController;

use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\MaterialInController as AdminMaterialInController;
use App\Http\Controllers\Admin\MaterialOutController as AdminMaterialOutController;
use App\Http\Controllers\Admin\ProductInController as AdminProductInController;
use App\Http\Controllers\Admin\ProductionOrderController as AdminProductionOrderController;
use App\Http\Controllers\Admin\ProductRecipeController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Gudang\MaterialInController as GudangMaterialInController;
use App\Http\Controllers\Gudang\MaterialOutController as GudangMaterialOutController;
use App\Http\Controllers\Gudang\ProductInController as GudangProductInController;
use App\Http\Controllers\Gudang\RequestController as GudangRequestController;

use App\Http\Controllers\Produksi\ProductionOrderController as ProduksiProductionOrderController;
use App\Http\Controllers\Produksi\MaterialRequestController as ProduksiMaterialRequestController;
use App\Http\Controllers\Produksi\ProductionResultController as ProduksiProductionResultController;

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// Redirect homepage to login
Route::get('/', fn() => redirect()->route('login'));

// Dashboard (role redirect)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// ======================================================================
// ============================== ADMIN =================================
// ======================================================================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // ----- MASTER -----
    Route::resource('vendors', AdminVendorController::class)->names('admin.vendors');
    Route::resource('materials', AdminMaterialController::class)->names('admin.materials');
    Route::resource('products', AdminProductController::class)->names('admin.products');
    Route::resource('users', AdminUserController::class)->names('admin.users');

    // ----- PROFILE -----
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');
    
    // ----- GUDANG (Admin akses full) -----
    Route::resource('material-in', AdminMaterialInController::class)->names('admin.material-in');
    Route::resource('material-out', AdminMaterialOutController::class)->names('admin.material-out');
    Route::resource('product-in', AdminProductInController::class)->names('admin.product-in');
    
    // ----- PRODUKSI -----
    Route::resource('production-orders', AdminProductionOrderController::class)->names('admin.production-orders');
    
    Route::post('/production-orders/{productionOrder}/update-status',
        [AdminProductionOrderController::class, 'updateStatus'])
        ->name('admin.production-orders.update-status');
    
    Route::get('/production-orders/{id}/print',
        [AdminProductionOrderController::class, 'print'])
        ->name('admin.production-orders.print');
    
    Route::get('/production-orders/{id}/info',
        [AdminProductionOrderController::class, 'getProductionOrderInfo'])
        ->name('admin.production-orders.info');
    
    // Reports
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/stocks', [AdminDashboardController::class, 'stockReport'])->name('admin.reports.stocks');
    Route::get('/reports/production', [AdminDashboardController::class, 'productionReport'])->name('admin.reports.production');
    Route::get('/reports/transactions', [AdminDashboardController::class, 'transactionReport'])->name('admin.reports.transactions');

    //RecipeM
   Route::get('/products/{product}/recipe', [ProductRecipeController::class, 'edit'])->name('admin.products.recipe.edit');
        Route::put('/products/{product}/recipe', [ProductRecipeController::class, 'update'])->name('admin.products.recipe.update');
});

// ======================================================================
// ============================== GUDANG ================================
// ======================================================================
Route::prefix('gudang')->middleware(['auth', 'role:gudang,admin'])->group(function () {
    Route::get('/', [GudangDashboardController::class, 'index'])->name('gudang.dashboard');
    
    // ----- MATERIAL IN -----
    Route::resource('material-in', GudangMaterialInController::class)->names('gudang.material-in');
    
    // ----- MATERIAL OUT -----
    Route::resource('material-out', GudangMaterialOutController::class)->names('gudang.material-out');
    
    // ----- PRODUCT IN -----
    Route::resource('product-in', GudangProductInController::class)->names('gudang.product-in');
    
    // ----- REQUESTS -----
    Route::get('/requests', [GudangRequestController::class, 'index'])->name('gudang.requests.index');
    Route::get('/requests/{id}', [GudangRequestController::class, 'show'])->name('gudang.requests.show');
    Route::post('/requests/{id}/process', [GudangRequestController::class, 'process'])->name('gudang.requests.process');
    Route::post('/requests/{id}/reject', [GudangRequestController::class, 'reject'])->name('gudang.requests.reject');
    Route::get('/requests/history', [GudangRequestController::class, 'history'])->name('gudang.requests.history');
    
    // ----- AJAX -----
    Route::get('/production-orders/{id}/info', [GudangMaterialOutController::class, 'getProductionOrderInfo'])
        ->name('gudang.production-orders.info');
    
    Route::get('/materials/{id}/stock', [GudangMaterialOutController::class, 'checkStock'])
        ->name('gudang.materials.check-stock');
    
         Route::get(
            'material-in/{id}/print',
            [GudangMaterialInController::class, 'print']
        )->name('gudang.material-in.print');

    Route::get(
        'product-in/{productIn}/print',
        [GudangProductInController::class, 'print']
    )->name('gudang.product-in.print');

    // ----- REPORTS -----
    Route::get('/reports/stocks', [GudangDashboardController::class, 'stockReport'])->name('gudang.reports.stocks');
    Route::get('/reports/transactions', [GudangDashboardController::class, 'transactionReport'])->name('gudang.reports.transactions');
    Route::get('/reports/low-stock', [GudangDashboardController::class, 'lowStockReport'])->name('gudang.reports.low-stock');
});

// ======================================================================
// ============================== PRODUKSI ==============================
// ======================================================================
// ======================================================================
// ============================== PRODUKSI ==============================
// ======================================================================
Route::prefix('produksi')
    ->middleware(['auth', 'role:produksi,admin'])
    ->group(function () {

        Route::get('/', [ProduksiDashboardController::class, 'index'])
            ->name('produksi.dashboard');

        Route::resource(
            'production-orders',
            ProduksiProductionOrderController::class
        )->names('produksi.production-orders');

        // ===== ACTION PRODUKSI =====
        Route::post(
            '/production-orders/{productionOrder}/complete',
            [ProduksiProductionOrderController::class, 'complete']
        )->name('produksi.production-orders.complete');

        Route::post(
            '/production-orders/{productionOrder}/request-material',
            [ProduksiProductionOrderController::class, 'requestMaterial']
        )->name('produksi.production-orders.request-material');

        Route::post(
            '/production-orders/{productionOrder}/cancel',
            [ProduksiProductionOrderController::class, 'cancel']
        )->name('produksi.production-orders.cancel');

        // ===== MATERIAL REQUESTS =====
        Route::resource('material-requests', ProduksiMaterialRequestController::class)
            ->names('produksi.material-requests');

        // ===== PRODUCTION RESULTS =====
        Route::resource('results', ProduksiProductionResultController::class)
            ->names('produksi.results');

        Route::get(
            '/results/report',
            [ProduksiProductionResultController::class, 'report']
        )->name('produksi.results.report');

        // ===== REPORTS =====
        Route::get(
            '/reports/progress',
            [ProduksiDashboardController::class, 'progressReport']
        )->name('produksi.reports.progress');

        Route::get(
            '/reports/efficiency',
            [ProduksiDashboardController::class, 'efficiencyReport']
        )->name('produksi.reports.efficiency');

        Route::get(
            '/reports/daily',
            [ProduksiDashboardController::class, 'dailyReport']
        )->name('produksi.reports.daily');
    });

    Route::get('/api/products/{product}/bom', function (App\Models\Product $product) {
    $bomItems = $product->materials()->with('material')->get()->map(function ($item) {
        return [
            'material_name' => $item->material->name ?? 'Unknown',
            'quantity' => $item->quantity,
            'unit' => $item->material->unit ?? 'pcs',
        ];
    });
    
    return response()->json($bomItems);
})->middleware('auth');


// ======================================================================
// ============================= SHARED =================================
// ======================================================================
Route::middleware('auth')->group(function () {
    Route::get('/vendors', fn() =>
        Auth::user()->role === 'admin'
            ? redirect()->route('admin.vendors.index')
            : view('shared.vendors.index')
    )->name('vendors.index');
    
    Route::get('/materials', fn() =>
        Auth::user()->role === 'admin'
            ? redirect()->route('admin.materials.index')
            : view('shared.materials.index')
    )->name('materials.index');
    
    Route::get('/products', fn() =>
        Auth::user()->role === 'admin'
            ? redirect()->route('admin.products.index')
            : view('shared.products.index')
    )->name('products.index');
    
    Route::get('/admin/products/generate-sku', 
    [App\Http\Controllers\Admin\ProductController::class, 'generateSku']
)->name('admin.products.generate-sku');


    // Profile
    Route::view('/profile', 'profile.edit')->name('profile.edit');
    Route::view('/change-password', 'profile.change-password')->name('change-password');
});


// ======================================================================
// =============================== API ==================================
// ======================================================================
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/materials/{id}', function ($id) {
        return \App\Models\Material::select('id','name','current_stock','unit','min_stock')->find($id);
    });
    
    Route::get('/production-orders/{id}', function ($id) {
        return \App\Models\ProductionOrder::with('product')->find($id);
    });
    
    Route::get('/check-stock/{id}', function ($id) {
        $m = \App\Models\Material::find($id);
        return [
            'current_stock' => $m->current_stock,
            'is_low' => $m->current_stock <= $m->min_stock
        ];
    });
});