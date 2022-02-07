<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(20),
            'year_of_publication' => $this->faker->dateTimeBetween('-8 years', 'now'),
            'description' => $this->faker->paragraph(),
            'quantity' => $this->faker->numberBetween(1, 50),
        ];
    }
}
