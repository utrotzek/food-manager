<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\DayPlanRepositoryInterface;
use App\Models\Day;
use App\Models\DayPlan;
use App\Models\Meal;
use App\Models\Recipe;

class DayPlanRepository extends BaseRepository implements DayPlanRepositoryInterface
{
    public function createForDay(Day $day, Recipe $recipe, Meal $meal, array $attributes): DayPlan
    {
        $dayPlan = DayPlan::make($attributes);
        $dayPlan->recipe()->associate($recipe);
        $dayPlan->day()->associate($day);
        $dayPlan->meal()->associate($meal);
        $dayPlan->save();
        return $dayPlan->fresh();
    }

    public function updateForDay(DayPlan $dayPlan, Day $day, Recipe $recipe, Meal $meal, array $attributes): DayPlan
    {
        $dayPlan->fill($attributes);
        $dayPlan->recipe()->disassociate();
        $dayPlan->recipe()->associate($recipe);
        $dayPlan->day()->disassociate();
        $dayPlan->day()->associate($day);
        $dayPlan->meal()->disassociate();
        $dayPlan->meal()->associate($meal);
        $dayPlan->save();
        return $dayPlan->fresh();
    }
}
