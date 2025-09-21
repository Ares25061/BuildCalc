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
        Schema::table('calculation_formulas', function (Blueprint $table) {
            $table->foreign(['category_id'], 'FK__calculati__categ__4222D4EF')->references(['category_id'])->on('categories')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calculation_formulas', function (Blueprint $table) {
            $table->dropForeign('FK__calculati__categ__4222D4EF');
        });
    }
};
