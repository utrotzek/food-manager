<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\MealRepositoryInterface;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use Illuminate\Support\Collection;

class ShoppingListRepository extends BaseRepository implements MealRepositoryInterface
{
    public function findActive(): Collection
    {
        return ShoppingList::where('done', false)->get();
    }

    /**
     * @throws \Exception
     */
    public function clearAllItems(ShoppingList  $shoppingList): void
    {
        /** @var ShoppingListItem $item */
        foreach ($shoppingList->items as $item) {
            $item->delete();
        }
    }
}
