<?php
namespace Database\Seeders\Development;

use App\Models\Calendar;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CalendarSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        Calendar::create(
            [
                'title' => 'Testcalendar',
                'color' => '#d81a1a',
                'token' => md5(rand()),
                'refresh_token' => md5(rand())
            ]
        );
    }
}
