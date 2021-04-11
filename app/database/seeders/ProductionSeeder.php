<?php
namespace Database\Seeders;

use Database\Seeders\Production\AppStateSeeder;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
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
        ]);
    }
}
