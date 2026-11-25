<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_in', function (Blueprint $table) {
            $table->string('condition')->after('date');
            $table->text('defect_notes')->nullable()->after('condition');
            $table->string('transaction_code')->after('defect_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_in', function (Blueprint $table) {
            $table->dropColumn(['condition', 'defect_notes', 'transaction_code']);
        });
    }
};
