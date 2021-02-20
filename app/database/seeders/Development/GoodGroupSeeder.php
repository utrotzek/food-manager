<?php


namespace Database\Seeders\Development;

use App\Models\GoodGroup;
use Illuminate\Database\Seeder;

class GoodGroupSeeder extends Seeder
{
    public function run()
    {
        GoodGroup::create([
            'id' => 1,
            'title' => 'Getreideprodukte',
            'sort' => 10
        ]);

        GoodGroup::create([
            'id' => 2,
            'title' => 'Obst und Gemüse',
            'sort' => 20
        ]);

        GoodGroup::create([
            'id' => 3,
            'title' => 'Fleisch',
            'sort' => 30
        ]);

        GoodGroup::create([
            'id' => 4,
            'title' => 'Fisch',
            'sort' => 40
        ]);

        GoodGroup::create([
            'id' => 5,
            'title' => 'Kaffee und Tee',
            'sort' => 50
        ]);

        GoodGroup::create([
            'id' => 6,
            'title' => 'Süßwaren',
            'sort' => 60
        ]);

        GoodGroup::create([
            'id' => 7,
            'title' => 'Käse',
            'sort' => 70
        ]);
    }
}
