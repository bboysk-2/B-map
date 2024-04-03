<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Spot>
 */
class SpotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'user_id' => 2,
            'name' => fake()->text(10),
            'address' => fake()->address,
            'latitude' => fake()->latitude,
            'longitude' => fake()->longitude,
        ];
    }
}
