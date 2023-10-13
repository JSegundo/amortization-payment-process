<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;


class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */


    protected $model = Project::class;

    public function definition()
    {
      return [
            'wallet_balance' => $this->faker->randomFloat(2, 2000, 7000),
            'promoter' => $this->faker->name,
            'promoter_email' => $this->faker->email,
        ];
    }
}
