<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop dulu biar bersih jika tabel sudah ada (Opsional, tapi aman untuk dev)
        Schema::dropIfExists('products');

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Data Utama
            $table->string('name');
            $table->string('sku')->unique(); // Primary ID buat user
            
            // Stok & Satuan
            $table->integer('current_stock')->default(0); // Default 0
            $table->integer('min_stock')->default(0);     // Tambahan (Best practice)
            $table->string('unit');
            
            // Tambahan
            $table->text('notes')->nullable();   // <--- INI YANG TADINYA HILANG

            // System
            $table->softDeletes(); // Wajib karena di Model pakai SoftDeletes
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};