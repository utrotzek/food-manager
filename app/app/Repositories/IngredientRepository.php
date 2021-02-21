<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\IngredientRepositoryInterface;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Collection;

class IngredientRepository implements IngredientRepositoryInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return Ingredient::query()->orderBy('sort')->get();
    }

    /**
     * Deletes all items of $existingItems which are not present in $actualItems
     *
     * @throws \Exception
     */
    public function deleteRemovedItems(Collection $existingItems, Collection $actualItems): void
    {
        /** @var Ingredient $existingItem */
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
}
