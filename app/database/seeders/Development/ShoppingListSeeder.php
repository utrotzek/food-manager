<?php

namespace Database\Seeders\Development;

use App\Models\ShoppingList;
use Illuminate\Database\Seeder;

class ShoppingListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShoppingList::factory()->count(1)->create();
        ShoppingList::factory()->done()->count(1)->create();
    }
}
