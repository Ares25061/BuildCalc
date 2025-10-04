<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('material_consumption_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('work_type_id');
            $table->integer('material_id');
            $table->decimal('consumption_rate', 8, 3);
            $table->timestampTz('created_at')->nullable()->default(DB::raw("now()"));

            $table->unique(['work_type_id', 'material_id'], 'material_consumption_rates_work_type_id_material_id_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_consumption_rates');
    }
};
