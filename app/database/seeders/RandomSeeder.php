<?php
namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RandomSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Recipe::factory()->count(20)->create();
    }
}
