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
        Schema::table('material_prices', function (Blueprint $table) {
            $table->foreign(['material_id'], 'material_prices_material_id_fkey')->references(['id'])->on('materials')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_prices', function (Blueprint $table) {
            $table->dropForeign('material_prices_material_id_fkey');
        });
    }
};
