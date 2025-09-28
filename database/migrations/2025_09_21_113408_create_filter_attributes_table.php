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
        Schema::create('filter_attributes', function (Blueprint $table) {
            $table->id('attribute_id');
            $table->integer('category_id');
            $table->string('attribute_name', 100);
            $table->string('display_name', 100);
            $table->string('data_type', 20);
            $table->boolean('filterable')->nullable()->default(true);

            $table->unique(['category_id', 'attribute_name'], 'uq_category_filter_attribute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filter_attributes');
    }
};
