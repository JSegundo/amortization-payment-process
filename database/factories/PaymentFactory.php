<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Amortization;
use Illuminate\Database\Eloquent\Factories\Factory;


class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Payment::class;

    public function definition()
    {
       return [
            'amortization_id' => Amortization::factory(),
            'amount' => $this->faker->randomFloat(2, 50, 500),
            'state' => $this->faker->randomElement(['pending', 'paid']),
            'profile_id' => $this->faker->randomNumber(),
            'profile_email' => $this->faker->email,
        ];
    }
}
