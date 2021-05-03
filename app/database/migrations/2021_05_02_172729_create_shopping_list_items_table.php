<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingListItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_list_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('unit_id')->references('id')->on('units')->cascadeOnDelete();
            $table->float('unit_amount');
            $table->foreignId('good_id')->nullable()->references('id')->on('goods')->cascadeOnDelete();
            $table->foreignId('day_plan_id')->nullable()->references('id')->on('day_plans')->cascadeOnDelete();
            $table->foreignId('shopping_list_id')->references('id')->on('shopping_lists')->cascadeOnDelete();
            $table->string('description', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopping_list_items');
    }
}
