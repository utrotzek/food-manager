<?php

namespace Database\Seeders\Development;

use App\Repositories\DayPlanRepository;
use App\Repositories\DayRepository;
use App\Repositories\MealRepository;
use App\Repositories\RecipeRepository;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DayPlanSeeder extends Seeder
{
    protected DayPlanRepository $dayPlanRepository;
    protected DayRepository $dayRepository;
    protected RecipeRepository $recipeRepository;
    protected MealRepository $mealRepository;

    public function __construct(
        DayPlanRepository $dayPlanRepository,
        RecipeRepository $recipeRepository,
        DayRepository $dayRepository,
        MealRepository $mealRepository
    ) {
        $this->dayPlanRepository = $dayPlanRepository;
        $this->recipeRepository = $recipeRepository;
        $this->dayRepository = $dayRepository;
        $this->mealRepository = $mealRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $todayDate = Carbon::now();
        $tomorrow = Carbon::now()->addDay();
        $this->createDayPlan($todayDate, 'Chili con Carne', 3);
        $this->createDayPlan($tomorrow, 'Gnocchi-Pfanne mit Spinat', 3);
    }

    private function createDayPlan(Carbon $date, string $recipeSlug, int $mealId){

        $today = $this->dayRepository->findByIdOrDate($date);
        $recipe = $this->recipeRepository->findByIdOrSlug($recipeSlug);
        $meal = $this->mealRepository->findById($mealId);

        $this->dayPlanRepository->createForDay(
            $today,
            $recipe,
            $meal,
            []
        );
    }
}
