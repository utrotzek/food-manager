<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\IngredientRepositoryInterface;
use App\Models\Ingredient;
use App\Traits\DiffDeletableTrait;

class IngredientRepository implements IngredientRepositoryInterface
{
    use DiffDeletableTrait;

    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return Ingredient::query()->orderBy('sort')->get();
    }

    /**
     * @codeCoverageIgnore
     */
    public function findById(int $id): ?Ingredient
    {
        return Ingredient::find($id);
    }
}
