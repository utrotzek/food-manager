<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Calendar;
use App\Models\Day;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text("50"),
            'day_id' => Day::inRandomOrder()->first()->id,
            'calendar_id' => Calendar::first()->id
        ];
    }
}
