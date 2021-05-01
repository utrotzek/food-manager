<?php

use App\Repositories\MealConfigRepository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealConfigsTable extends Migration
{
    protected MealConfigRepository $mealConfigRepository;

    public function __construct(){
        $this->mealConfigRepository = app(MealConfigRepository::class);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_configs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title')->unique();
        });
        $this->addDefaultData();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meal_configs');
    }

    private function addDefaultData()
    {
        $this->mealConfigRepository->create([
            'title' => 'Default'
        ]);
    }
}
