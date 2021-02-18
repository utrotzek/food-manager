<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('good_id')->references('id')->on('goods');
            $table->foreignId('unit_id')->references('id')->on('units');
            $table->integer('unit_amount');
            $table->foreignId('recipe_id')->references('id')->on('recipes');
            $table->string('title');
            $table->integer('carbs');
            $table->integer('fat');
            $table->integer('protein');
            $table->integer('kcal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
}
