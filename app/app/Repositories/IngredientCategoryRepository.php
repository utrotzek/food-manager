<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\IngredientCategoryRepositoryInterface;
use App\Models\IngredientCategory;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Collection;

class IngredientCategoryRepository implements IngredientCategoryRepositoryInterface
{
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
     * Deletes all items of $existingItems which are not present in $actualItems
     *
     * @throws \Exception
     */
    public function deleteRemovedItems(Collection $existingItems, array $actualItems): void
    {
        /** @var IngredientCategory $existingItem */
        foreach ($existingItems as $existingItem) {
            $found = false;
            foreach ($actualItems as $actualItem) {
                if ($existingItem == $actualItem) {
                    $found = true;
                }
            }

            if (!$found) {
                $existingItem->delete();
            }
        }
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
