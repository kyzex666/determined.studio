<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_in', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_code')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->decimal('qty', 16, 2)->default(0);
            $table->decimal('price_per_unit', 16, 2)->nullable();
            $table->decimal('total_price', 16, 2)->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('date')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('set null');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_in');
    }
};
