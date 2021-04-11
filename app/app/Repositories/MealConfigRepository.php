<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\MealConfigRepositoryInterface;
use App\Models\MealConfig;

class MealConfigRepository implements MealConfigRepositoryInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return MealConfig::query()->orderBy('title')->get();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function create($attributes): MealConfig
    {
        $mealConfig = new MealConfig($attributes);
        $mealConfig->save();
        return $mealConfig->fresh();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function update(array $attributes, MealConfig $mealConfig): MealConfig
    {
        $mealConfig->fill($attributes);
        $mealConfig->save();
        return $mealConfig->fresh();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function findFirst(): ?MealConfig
    {
        return MealConfig::get()->first();
    }

    public function findByIdOrSlug(string $idOrTitle): ?MealConfig
    {
        /** @var MealConfig $item */
        $item = MealConfig::query()
            ->where('id', $idOrTitle)
            ->orWhere('title', $idOrTitle)
            ->first();
        return $item;
    }
}
