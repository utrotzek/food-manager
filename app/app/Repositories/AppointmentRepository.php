<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\AppointmentRepositoryInterface;
use App\Models\Appointment;
use App\Models\Calendar;
use App\Models\Day;

class AppointmentRepository extends BaseRepository implements AppointmentRepositoryInterface
{
    public function createForCalendarAndDay(array $attributes, Calendar $calendar, Day $day): Appointment
    {
        $appointment = Appointment::make($attributes);
        $appointment->calendar()->associate($calendar);
        $appointment->day()->associate($day);
        $appointment->save();
        return $appointment->fresh();
    }
}
