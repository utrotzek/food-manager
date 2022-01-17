<?php
namespace Database\Seeders\Development;

use App\Models\Account;
use App\Models\Calendar;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CalendarSeeder extends Seeder
{
    public function run()
    {
        Calendar::create(
            [
                'title' => 'Testcalendar',
                'color' => '#d81a1a',
                'account_id' => 1,
                'external_id' => 'calendar1'
            ]
        );
        Calendar::create(
            [
                'title' => 'Testcalendar 2',
                'color' => '#ffeeee',
                'account_id' => 2,
                'external_id' => 'calendar2'
            ]
        );
    }
}
