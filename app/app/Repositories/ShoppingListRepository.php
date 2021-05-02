<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\MealRepositoryInterface;
use App\Models\ShoppingList;
use Illuminate\Support\Collection;

class ShoppingListRepository extends BaseRepository implements MealRepositoryInterface
{
    public function findActive(): Collection
    {
        return ShoppingList::where('done', false)->get();
    }
}
