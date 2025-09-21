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
        Schema::table('parameter_options', function (Blueprint $table) {
            $table->foreign(['parameter_id'], 'FK__parameter__param__3F466844')->references(['parameter_id'])->on('calculator_parameters')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameter_options', function (Blueprint $table) {
            $table->dropForeign('FK__parameter__param__3F466844');
        });
    }
};
