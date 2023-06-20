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
        Schema::create('competition_competition_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_id');
            $table->unsignedBigInteger('competition_category_id');
            $table->timestamps();

            $table->foreign('competition_id')
                ->references('id')
                ->on('competitions');
            $table->foreign('competition_category_id')
                ->references('id')
                ->on('competition_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_competition_categories');
    }
};
