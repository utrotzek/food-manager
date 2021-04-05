<?php

namespace Database\Seeders\Development;

use App\Models\MealConfig;
use Illuminate\Database\Seeder;

class MealConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MealConfig::create([
           'title' => 'Default'
        ]);
    }
}
