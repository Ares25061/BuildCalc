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
        Schema::create('materials', function (Blueprint $table) {
            $table->increments('material_id');
            $table->integer('category_id');
            $table->string('name');
            $table->string('article_number', 100)->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 12);
            $table->string('unit', 20);
            $table->decimal('rating', 2, 1)->nullable()->default(0);
            $table->integer('review_count')->nullable()->default(0);
            $table->string('image_url', 500)->nullable();
            $table->string('product_url', 500)->nullable();
            $table->string('review_url', 500)->nullable();
            $table->boolean('is_in_stock')->nullable()->default(false);
            $table->integer('stock_quantity')->nullable()->default(0);
            $table->dateTime('created_at', 7)->nullable()->useCurrent();
            $table->dateTime('updated_at', 7)->nullable()->useCurrent();
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
