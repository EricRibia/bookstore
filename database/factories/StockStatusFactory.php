<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StockStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'max' => $this->faker->randomNumber(),
            'min' => $this->faker->randomNumber(),
            'message' => $this->faker->text(),
        ];
    }
}
