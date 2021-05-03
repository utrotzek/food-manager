<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\ShoppingListItemInterface;
use App\Models\DayPlan;
use App\Models\Good;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ShoppingListItemRepository extends BaseRepository implements ShoppingListItemInterface
{
    public function findByShoppingListId(int $shoppingListId): Collection
    {
        return ShoppingListItem::where('shopping_list_id', $shoppingListId)->get();
    }

    public function createForShoppingList(
        ShoppingList $shoppingList,
        ?Good $good,
        ?Unit $unit,
        ?DayPlan $dayPlan,
        array $attributes
    ): ShoppingListItem {
        $newItem = ShoppingListItem::make($attributes);
        $newItem->unit()->associate($unit);
        $newItem->shoppingList()->associate($shoppingList);
        if ($dayPlan) {
            $newItem->dayPlan()->associate($dayPlan);
        }
        if ($good) {
            $newItem->good()->associate($good);
        }
        $newItem->save();
        return $newItem->fresh();
    }

    public function updateForShoppingList(
        ShoppingListItem $shoppingListItem,
        ShoppingList $shoppingList,
        ?Good $good,
        ?Unit $unit,
        ?DayPlan $dayPlan,
        array $attributes
    ): ShoppingListItem {
        $shoppingListItem->fill($attributes);
        $shoppingListItem->unit()->associate($unit);
        $shoppingListItem->shoppingList()->associate($shoppingList);
        if ($dayPlan) {
            $shoppingListItem->dayPlan()->associate($dayPlan);
        }
        if ($good) {
            $shoppingListItem->good()->associate($good);
        }
        $shoppingListItem->save();
        return $shoppingListItem->fresh();
    }
}
