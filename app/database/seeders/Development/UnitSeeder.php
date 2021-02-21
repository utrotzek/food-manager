<?php
namespace Database\Seeders\Development;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run()
    {
        Unit::create([
            'title' => 'Stück',
            'is_piece' => true
        ]);

        Unit::create([
            'title' => 'Gramm',
            'average_gram' => '1'
        ]);

        Unit::create([
            'title' => 'Msp.',
            'average_gram' => '5'
        ]);

        Unit::create([
            'title' => 'El',
            'average_gram' => '20'
        ]);

        Unit::create([
            'title' => 'Tl',
            'average_gram' => '5'
        ]);

        Unit::create([
            'title' => 'L',
            'average_gram' => '1000'
        ]);

        Unit::create([
            'title' => 'ml',
            'average_gram' => '1'
        ]);
    }
}
