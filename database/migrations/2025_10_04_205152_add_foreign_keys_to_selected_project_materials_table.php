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
        Schema::table('selected_project_materials', function (Blueprint $table) {
            $table->foreign(['material_id'], 'selected_project_materials_material_id_fkey')->references(['id'])->on('materials')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['project_item_id'], 'selected_project_materials_project_item_id_fkey')->references(['id'])->on('project_items')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('selected_project_materials', function (Blueprint $table) {
            $table->dropForeign('selected_project_materials_material_id_fkey');
            $table->dropForeign('selected_project_materials_project_item_id_fkey');
        });
    }
};
