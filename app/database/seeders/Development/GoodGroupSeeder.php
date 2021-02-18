<?php


namespace Database\Seeders\Development;

use App\Models\GoodGroup;
use Illuminate\Database\Seeder;

class GoodGroupSeeder extends Seeder
{
    public function run()
    {
        GoodGroup::create([
            'title' => 'Getreideprodukte',
            'sort' => 10
        ]);

        GoodGroup::create([
            'title' => 'Obst und Gemüse',
            'sort' => 20
        ]);

        GoodGroup::create([
            'title' => 'Fleisch',
            'sort' => 30
        ]);

        GoodGroup::create([
            'title' => 'Fisch',
            'sort' => 40
        ]);

        GoodGroup::create([
            'title' => 'Kaffee und Tee',
            'sort' => 50
        ]);

        GoodGroup::create([
            'title' => 'Süßwaren',
            'sort' => 60
        ]);

        GoodGroup::create([
            'title' => 'Käse',
            'sort' => 70
        ]);
    }
}
