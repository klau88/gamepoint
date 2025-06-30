<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 99999999),
            'date' => fake()->date(),
            'time' => fake()->time(),
            'country' => fake()->countryCode(),
            'currency' => fake()->randomElement(['EUR', 'USD', 'CAD', 'GBP', 'NOK']),
            'amount_in_cents' => fake()->numberbetween(1, 999999)
        ];
    }
}
