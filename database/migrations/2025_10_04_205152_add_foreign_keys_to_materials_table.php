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
        Schema::table('materials', function (Blueprint $table) {
            $table->foreign(['category_id'], 'materials_category_id_fkey')->references(['id'])->on('material_categories')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['supplier_id'], 'materials_supplier_id_fkey')->references(['id'])->on('suppliers')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign('materials_category_id_fkey');
            $table->dropForeign('materials_supplier_id_fkey');
        });
    }
};
