<?php

use App\Repositories\MealConfigRepository;
use App\Repositories\MealRepository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealsTable extends Migration
{
    protected MealConfigRepository $mealConfigRepository;
    protected MealRepository $mealRepository;

    public function __construct(){
        $this->mealConfigRepository = app(MealConfigRepository::class);
        $this->mealRepository = app(MealRepository::class);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->integer('sort')->unique()->default(0);
            $table->foreignId('meal_config_id')->references('id')->on('meal_configs')->onDelete('cascade');
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
        Schema::dropIfExists('meals');
    }

    private function addDefaultData()
    {
        $mealConfig = $this->mealConfigRepository->findFirst();
        $this->mealRepository->createForMealConfig(
            [
                'title' => 'Frühstück',
                'sort' => 0
            ],
            $mealConfig
        );

        $this->mealRepository->createForMealConfig(
            [
                'title' => 'Mittagessen',
                'sort' => 10
            ],
            $mealConfig
        );

        $this->mealRepository->createForMealConfig(
            [
                'title' => 'Abendessen',
                'sort' => 20
            ],
            $mealConfig
        );
    }
}
