<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- JANGAN LUPA INI

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Buat Tabel Struktur
        Schema::create('product_materials', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel products
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // Relasi ke tabel materials
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            
            // Jumlah material yang dibutuhkan
            $table->decimal('quantity', 10, 2); 
            
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('product_materials');
    }
};