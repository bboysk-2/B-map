<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'spot_id' => fake()->numberBetween(224, 233),
            'user_id' => fake()->numberBetween(1, 4),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->text(20),
        ];
    }
}
