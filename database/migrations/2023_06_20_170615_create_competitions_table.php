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
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_category_id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->dateTime('deadline');
            $table->integer('max_members');
            $table->integer('price');
            $table->text('description');
            $table->string('guide_book_link');
            $table->timestamps();

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
        Schema::dropIfExists('competitions');
    }
};
