<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\Formation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    public function definition(): array
    {
        return [
            'utilisateur_id' => User::factory()->apprenant(),
            'formation_id'   => Formation::factory(),
            'progression'    => fake()->numberBetween(0, 100),
        ];
    }
}