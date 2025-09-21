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
        Schema::create('material_attributes', function (Blueprint $table) {
            $table->increments('attribute_id');
            $table->integer('material_id');
            $table->string('attribute_name', 100);
            $table->string('attribute_value');
            $table->string('attribute_type', 20);
            $table->string('display_name', 100)->nullable();
            $table->string('unit', 20)->nullable();

            $table->unique(['material_id', 'attribute_name'], 'uq_material_attribute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_attributes');
    }
};
