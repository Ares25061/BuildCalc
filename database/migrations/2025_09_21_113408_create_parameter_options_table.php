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
        Schema::create('parameter_options', function (Blueprint $table) {
            $table->id('option_id');
            $table->integer('parameter_id');
            $table->string('option_value', 100);
            $table->string('option_label', 100);

            $table->unique(['parameter_id', 'option_value'], 'uq_parameter_option');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameter_options');
    }
};
