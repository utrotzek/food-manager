<?php

namespace Database\Factories;

use App\Models\DayPlan;
use App\Models\Good;
use App\Models\ShoppingListItem;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppingListItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShoppingListItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'unit_amount' => $this->faker->randomFloat(2, 1, 500),
            'unit_id' => Unit::all()->random()->id,
            'good_id' => Good::all()->random()->id,
            'day_plan_id' => DayPlan::all()->random()->id
        ];
    }

    public function freeText() {
        return $this->state(function (array $attributes){
            return [
                'descriptionAmount' => $this->faker->randomFloat(2, 1, 500). " ".$this->faker->text('5'),
                'unit_id' => null,
                'good_id' => null,
                'day_plan_id' => null,
                'description' => $this->faker->text(40)
            ];
        });
    }
}
