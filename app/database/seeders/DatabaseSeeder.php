<?php
namespace Database\Seeders;

use Database\Seeders\Development\AppointmentSeeder;
use Database\Seeders\Development\CalendarSeeder;
use Database\Seeders\Development\DayPlanSeeder;
use Database\Seeders\Development\DaySeeder;
use Database\Seeders\Development\GoodGroupSeeder;
use Database\Seeders\Development\GoodSeeder;
use Database\Seeders\Development\RecipeSeeder;
use Database\Seeders\Development\ShoppingListItemSeeder;
use Database\Seeders\Development\ShoppingListSeeder;
use Database\Seeders\Development\TagSeeder;
use Database\Seeders\Production\AppStateSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AppStateSeeder::class,
            GoodGroupSeeder::class,
            GoodSeeder::class,
            TagSeeder::class,
            RecipeSeeder::class,
            DaySeeder::class,
            DayPlanSeeder::class,
            ShoppingListSeeder::class,
            ShoppingListItemSeeder::class,
            CalendarSeeder::class,
            AppointmentSeeder::class
        ]);
    }
}
