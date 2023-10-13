<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Amortization;
use App\Models\Payment;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $projects = Project::factory(10)->create();

        // For each project, create 5 amortizations
        foreach ($projects as $project) {
            $amortizations = Amortization::factory(5)->create([
                'project_id' => $project->id,
            ]);

            // For each amortization, create 3 payments
            foreach ($amortizations as $amortization) {
                Payment::factory(3)->create([
                    'amortization_id' => $amortization->id,
                ]);
            }
        }

    }
}
