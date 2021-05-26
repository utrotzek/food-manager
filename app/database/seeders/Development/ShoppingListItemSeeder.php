<?php

namespace Database\Seeders\Development;

use App\Models\DayPlan;
use App\Models\Good;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Models\Unit;
use Faker\Generator;
use Illuminate\Database\Seeder;

class ShoppingListItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $shoppingListOne = ShoppingList::find(1);
        $shoppingListTwo = ShoppingList::find(2);

        ShoppingListItem::factory()
            ->count(30)
            ->for($shoppingListOne)
            ->create()
        ;

        ShoppingListItem::factory()
            ->freeText()
            ->count(8)
            ->for($shoppingListOne)
            ->create()
        ;

        ShoppingListItem::factory()
            ->count(12)
            ->for($shoppingListTwo)
            ->create()
        ;
    }
}
