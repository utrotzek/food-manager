<?php
namespace App\Interfaces\RepositoryInterfaces;

use App\Models\Appointment;
use App\Models\Calendar;
use App\Models\Day;

interface AppointmentRepositoryInterface
{
    public function createForCalendarAndDay(array $attributes, Calendar $calendar, Day $day): Appointment;
}
