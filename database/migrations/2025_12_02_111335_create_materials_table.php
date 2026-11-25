<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('material_code')->unique();     // kode otomatis
            $table->string('name');                        // nama bahan
            $table->string('unit', 20);                    // satuan
            $table->decimal('current_stock', 10, 2)->default(0);   // stok saat ini
            $table->decimal('min_stock', 10, 2)->default(0);       // stok minimum
            $table->string('location', 50)->nullable();    // lokasi rak / gudang
            $table->string('category', 50)->nullable();    // kategori bahan
            $table->text('notes')->nullable();             // catatan
            $table->timestamps();
            $table->softDeletes();                         // deleted_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
};
