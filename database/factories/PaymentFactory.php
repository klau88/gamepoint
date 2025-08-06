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
            'user_id' => $this->faker->numberBetween(1, 99999999),
            'date' => $this->faker->date(),
            'time' => $this->faker->time(),
            'country' => $this->faker->countryCode(),
            'currency' => $this->faker->randomElement(['EUR', 'USD', 'CAD', 'GBP', 'NOK']),
            'amount_in_cents' => $this->faker->numberbetween(1, 999999)
        ];
    }
}
