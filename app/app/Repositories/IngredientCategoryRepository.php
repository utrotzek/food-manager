<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\IngredientCategoryRepositoryInterface;
use App\Models\IngredientCategory;
use App\Models\Recipe;
use App\Traits\DiffDeletableTrait;

class IngredientCategoryRepository implements IngredientCategoryRepositoryInterface
{
    use DiffDeletableTrait;

    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return IngredientCategory::query()->orderBy('sort')->get();
    }

    /**
     * @codeCoverageIgnore
     */
    public function findById(int $id): ?IngredientCategory
    {
        return IngredientCategory::find($id);
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function create($title, $recipe): IngredientCategory
    {
        $ingredientCategory = new IngredientCategory(['title' => $title]);
        $ingredientCategory->recipe()->associate($recipe);
        $ingredientCategory->save();
        return $ingredientCategory->fresh();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function findByRecipeAndTitle(Recipe $recipe, string $title): ?IngredientCategory
    {
        return IngredientCategory::query()
                ->where('title', $title)
                ->where('recipe_id', $recipe['id'])
                ->get()->first();
    }
}
