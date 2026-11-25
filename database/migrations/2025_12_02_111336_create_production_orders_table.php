<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            
            // Relasi produk
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // Kode PO
            $table->string('order_code')->unique();

            // Target dan hasil produksi
            $table->integer('target_qty');
            $table->integer('actual_qty')->default(0);

            // Tanggal produksi
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Status produksi (HARUS sama dengan controller)
            $table->enum('status', [
                'draft',
                'waiting_material',
                'process',
                'done',
                'cancelled'
            ])->default('draft');

            // Prioritas
            $table->enum('priority', ['low','medium','high'])->default('medium');

            // Catatan
            $table->text('notes')->nullable();

            // User pembuat
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_orders');
    }
};
