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
        $entries = ShoppingListItem::query()
            ->where('shopping_list_id', $shoppingListId)
            ->get()
        ;

        $entries = $entries->sort(function ($el1, $el2) {
            $titleA = $el1->good->title ?? $el1->description;
            $titleB = $el2->good->title ?? $el2->description;
            return $titleA <=> $titleB;
        });
        return $entries;
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
        $shoppingListItem->shoppingList()->associate($shoppingList);

        if ($unit) {
            $shoppingListItem->unit()->associate($unit);
        } else {
            $shoppingListItem->unit()->disassociate();
        }

        if ($dayPlan) {
            $shoppingListItem->dayPlan()->associate($dayPlan);
        } else {
            $shoppingListItem->dayPlan()->disassociate();
        }

        if ($good) {
            $shoppingListItem->good()->associate($good);
        } else {
            $shoppingListItem->good()->disassociate();
        }
        $shoppingListItem->save();
        return $shoppingListItem->fresh();
    }

    public function moveToShoppingList(ShoppingListItem $shoppingListItem, ShoppingList $shoppingList): ShoppingListItem
    {
        $shoppingListItem->shoppingList()->disassociate();
        $shoppingListItem->shoppingList()->associate($shoppingList);
        $shoppingListItem->save();
        return $shoppingListItem->fresh();
    }
}
