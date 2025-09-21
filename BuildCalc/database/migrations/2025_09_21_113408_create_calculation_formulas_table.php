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
        Schema::create('calculation_formulas', function (Blueprint $table) {
            $table->increments('formula_id');
            $table->integer('category_id');
            $table->string('formula_name', 100);
            $table->text('formula_expression');
            $table->string('description', 500)->nullable();
            $table->boolean('is_default')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculation_formulas');
    }
};
