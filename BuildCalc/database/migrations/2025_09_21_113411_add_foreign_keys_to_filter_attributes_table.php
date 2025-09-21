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
        Schema::table('filter_attributes', function (Blueprint $table) {
            $table->foreign(['category_id'], 'FK__filter_at__categ__534D60F1')->references(['category_id'])->on('categories')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filter_attributes', function (Blueprint $table) {
            $table->dropForeign('FK__filter_at__categ__534D60F1');
        });
    }
};
