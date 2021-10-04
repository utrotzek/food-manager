<?php
namespace Database\Seeders\Development;

use App\Models\Appointment;
use App\Models\Calendar;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run()
    {
        Appointment::factory()->count(40)->create();
    }
}
