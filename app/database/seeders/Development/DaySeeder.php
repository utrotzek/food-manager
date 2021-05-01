<?php

namespace Database\Seeders\Development;

use App\Models\Day;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = new Carbon('-7 days');
        $days = Day::factory()->count('15')->make();
        /** @var Model $day */
        foreach($days as $day){
            $day['date'] = $date;
            $day->save();
            $date->addDays(1);
        }
    }
}
