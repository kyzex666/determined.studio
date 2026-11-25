<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('production_materials', function (Blueprint $table) {
            $table->id();

            // Relasi ke production order
            $table->foreignId('production_order_id')
                  ->constrained('production_orders')
                  ->onDelete('cascade');

            // Relasi ke material
            $table->foreignId('material_id')
                  ->constrained('materials')
                  ->onDelete('cascade');

            // Kebutuhan bahan
            $table->decimal('required_qty', 10, 2);

            // Jumlah bahan yang sudah dikeluarkan gudang
            $table->decimal('issued_qty', 10, 2)->default(0);

            // Satuan
            $table->string('unit', 20);

            // Catatan opsional
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_materials');
    }
};
