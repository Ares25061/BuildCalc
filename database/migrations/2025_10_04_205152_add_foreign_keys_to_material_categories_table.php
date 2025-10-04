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
        Schema::table('material_categories', function (Blueprint $table) {
            $table->foreign(['parent_id'], 'material_categories_parent_id_fkey')->references(['id'])->on('material_categories')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_categories', function (Blueprint $table) {
            $table->dropForeign('material_categories_parent_id_fkey');
        });
    }
};
