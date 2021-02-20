<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\RecipeRepositoryInterface;
use App\Models\Recipe;

class RecipeRepository implements RecipeRepositoryInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return Recipe::query()->orderBy('title')->get();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function create($attributes): Recipe
    {
        $tag = new Recipe($attributes);
        $tag->save();
        return $tag->fresh();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function update(array $attributes, Recipe $recipe): Recipe
    {
        $recipe->fill($attributes);
        $recipe->save();
        return $recipe->fresh();
    }
}
