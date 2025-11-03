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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('category_id')->nullable();
            $table->text('description')->nullable();
            $table->string('unit', 20); // piece, kg, gram, meter, square_meter, liter
            $table->string('article', 100)->nullable();
            $table->string('image_url')->nullable();

            // dimensions in mm
            $table->decimal('length_mm', 8, 2)->nullable();
            $table->decimal('width_mm', 8, 2)->nullable();
            $table->decimal('height_mm', 8, 2)->nullable();
            $table->decimal('weight_kg', 8, 2)->nullable();

            // extra stuff
            $table->string('color', 50)->nullable();
            $table->string('brand', 100)->nullable();
            $table->decimal('volume_m3', 10, 4)->nullable();

            $table->timestampTz('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestampTz('updated_at')->nullable()->default(DB::raw("now()"));
            $table->integer('supplier_id');
            $table->string('external_id', 100)->nullable();

            $table->unique(['supplier_id', 'external_id'], 'materials_supplier_id_external_id_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
