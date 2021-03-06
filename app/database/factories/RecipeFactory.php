<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->streetName,
            'rating' => $this->faker->numberBetween(1, 5),
            'image' => $this->faker->randomElement(['1.jpg', '2.png']),
            'portion' => $this->faker->numberBetween(2, 6),
            'comments' => $this->faker->text(250),
            'favorite' => $this->faker->boolean(30),
            'remember' => $this->faker->boolean(30)
        ];
    }
}
