<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('material_out', function (Blueprint $table) {
            if (! Schema::hasColumn('material_out', 'transaction_code')) {
                $table->string('transaction_code', 50)->nullable()->after('date');
            }
            if (! Schema::hasColumn('material_out', 'notes')) {
                $table->text('notes')->nullable()->after('transaction_code');
            }
        });
    }

    public function down()
    {
        Schema::table('material_out', function (Blueprint $table) {
            if (Schema::hasColumn('material_out', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('material_out', 'transaction_code')) {
                $table->dropColumn('transaction_code');
            }
        });
    }
};
