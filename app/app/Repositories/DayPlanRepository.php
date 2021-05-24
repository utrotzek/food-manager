<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\DayPlanRepositoryInterface;
use App\Models\Day;
use App\Models\DayPlan;
use App\Models\Meal;
use App\Models\Recipe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DayPlanRepository extends BaseRepository implements DayPlanRepositoryInterface
{
    public function createForDay(Day $day, ?Recipe $recipe, Meal $meal, array $attributes): DayPlan
    {
        $dayPlan = DayPlan::make($attributes);
        if ($recipe) {
            $dayPlan->recipe()->associate($recipe);
        } else {
            $dayPlan->recipe()->disassociate();
        }

        if (!empty($attributes['description']) && !$recipe) {
            $dayPlan['added_to_cart'] = true;
        }

        $dayPlan->day()->associate($day);
        $dayPlan->meal()->associate($meal);
        $dayPlan->save();
        return $dayPlan->fresh();
    }

    public function updateForDay(DayPlan $dayPlan, Day $day, ?Recipe $recipe, Meal $meal, array $attributes): DayPlan
    {
        $dayPlan->fill($attributes);
        $dayPlan->recipe()->disassociate();
        if ($recipe) {
            $dayPlan->recipe()->associate($recipe);
        }
        $dayPlan->day()->disassociate();
        $dayPlan->day()->associate($day);
        $dayPlan->meal()->disassociate();
        $dayPlan->meal()->associate($meal);
        $dayPlan->save();
        return $dayPlan->fresh();
    }

    public function setAddedToCart(DayPlan $dayPlan): DayPlan
    {
        $dayPlan['added_to_cart'] = true;
        $dayPlan->save();
        return $dayPlan->fresh();
    }

    public function findByRange(Carbon $from, Carbon $to): Collection
    {
        return DayPlan::whereHas('day', function (Builder $query) use ($from, $to) {
            $query->whereBetween(
                'date',
                [
                    $from->format('Y-m-d'),
                    $to->format('Y-m-d'),
                ]
            );
        })->get();
    }
}
