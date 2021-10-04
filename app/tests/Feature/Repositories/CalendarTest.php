<?php

use App\Models\Calendar;
use App\Repositories\CalendarRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(
    DatabaseTransactions::class,
    DatabaseMigrations::class
);

beforeEach(function () {
    $this->seed('DatabaseSeeder');
});

it('Can insert a new calendar', function () {
    $subject = new CalendarRepository();
    $calendar = $subject->create(['title' => 'Pest Calendar', 'color' => '#ffffff']);

    $actual = Calendar::find($calendar['id']);
    expect($actual->getAttribute('color'))->toBe('#ffffff');
    expect($actual->getAttribute('title'))->toBe('Pest Calendar');
});
