<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_plans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('recipe_id')->nullable()->references('id')->on('recipes')->cascadeOnDelete();
            $table->foreignId('meal_id')->references('id')->on('meals')->cascadeOnDelete();
            $table->foreignId('day_id')->references('id')->on('days')->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->boolean('done')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_plans');
    }
}
