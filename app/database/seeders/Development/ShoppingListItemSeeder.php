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
        $shoppingList = ShoppingList::find(1)->first();

        ShoppingListItem::factory()
            ->count(10)
            ->for($shoppingList)
            ->create()
        ;
    }
}
