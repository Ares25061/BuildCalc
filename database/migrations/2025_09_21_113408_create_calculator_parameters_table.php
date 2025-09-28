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
        Schema::create('calculator_parameters', function (Blueprint $table) {
            $table->id('parameter_id');
            $table->integer('category_id');
            $table->string('parameter_name', 100);
            $table->string('display_name', 100);
            $table->string('parameter_type', 20);

            $table->unique(['category_id', 'parameter_name'], 'uq_category_parameter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculator_parameters');
    }
};
