<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredient_categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->foreignId('recipe_id')->references('id')->on('recipes')->cascadeOnDelete();
            $table->unique(['title', 'recipe_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredient_categories');
    }
}
