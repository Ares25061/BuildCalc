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
        Schema::table('material_consumption_rates', function (Blueprint $table) {
            $table->foreign(['material_id'], 'material_consumption_rates_material_id_fkey')->references(['id'])->on('materials')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['work_type_id'], 'material_consumption_rates_work_type_id_fkey')->references(['id'])->on('work_types')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_consumption_rates', function (Blueprint $table) {
            $table->dropForeign('material_consumption_rates_material_id_fkey');
            $table->dropForeign('material_consumption_rates_work_type_id_fkey');
        });
    }
};
