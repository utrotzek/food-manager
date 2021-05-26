<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\MealRepositoryInterface;
use App\Models\Meal;
use App\Models\MealConfig;

class MealRepository extends BaseRepository implements MealRepositoryInterface
{
    protected $sortColumn = 'sort';

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function createForMealConfig(array $attributes, MealConfig $mealConfig): Meal
    {
        $model = Meal::make($attributes);
        $model->mealConfig()->associate($mealConfig);
        $model->save();
        return $model->fresh();
    }
}
