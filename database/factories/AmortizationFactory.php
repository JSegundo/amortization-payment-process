<?php

namespace Database\Factories;


use App\Models\Amortization;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

use Faker\Generator as Faker;

class AmortizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Amortization::class;

    public function definition()
    {

        return [
            'schedule_date' => $this->faker->dateTimeBetween('-1 years', '+2 years'),
            'state' => $this->faker->randomElement(['pending', 'paid']),
            'amount' => $this->faker->randomFloat(2, 100, 2000),
            'project_id' => function () {
                        return factory(Project::class)->create()->id;
                    },        ];
    }
}
