<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Di file migration
public function up()
{
    Schema::table('production_materials', function (Blueprint $table) {
        // Opsi 1: Tambah default value
        $table->string('unit')->default('pcs')->change();
        
        // Opsi 2: Buat nullable dulu
        // $table->string('unit')->nullable()->change();
    });
}

public function down()
{
    Schema::table('production_materials', function (Blueprint $table) {
        $table->string('unit')->default(null)->change();
    });
}
};
