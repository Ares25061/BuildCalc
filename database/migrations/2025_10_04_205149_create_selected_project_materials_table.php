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
        Schema::create('selected_project_materials', function (Blueprint $table) {
            $table->id();
            $table->integer('project_item_id');
            $table->integer('material_id');
            $table->decimal('quantity', 10, 3);
            $table->timestampTz('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestampTz('updated_at')->nullable()->default(DB::raw("now()"));

            $table->unique(['project_item_id', 'material_id'], 'selected_project_materials_project_item_id_material_id_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selected_project_materials');
    }
};
