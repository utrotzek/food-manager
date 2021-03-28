<?php
namespace Database\Seeders;

use Database\Seeders\Development\GoodGroupSeeder;
use Database\Seeders\Development\GoodSeeder;
use Database\Seeders\Development\RecipeSeeder;
use Database\Seeders\Development\TagSeeder;
use Database\Seeders\Development\UnitSeeder;
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
            GoodGroupSeeder::class,
            GoodSeeder::class,
            TagSeeder::class,
            RecipeSeeder::class,
        ]);
    }
}