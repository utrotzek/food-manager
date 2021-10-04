<?php

use App\Models\Appointment;
use App\Models\Calendar;
use App\Models\Day;
use App\Repositories\AppointmentRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(
    DatabaseTransactions::class,
    DatabaseMigrations::class
);

beforeEach(function () {
    $this->seed('DatabaseSeeder');
});

it('Can create an appointment for a calendar', function () {
    $subject = new AppointmentRepository();
    $calendar = Calendar::all()->random()->first();
    $day = Day::all()->random()->first();

    $appointment = $subject->createForCalendarAndDay(
        [
            'title' => 'My important appointment'
        ],
        $calendar,
        $day
    );

    $actual = Appointment::find($appointment['id']);
    expect($actual['title'])->toBe('My important appointment');
});
