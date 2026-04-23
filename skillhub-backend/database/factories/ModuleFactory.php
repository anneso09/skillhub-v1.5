<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleFactory extends Factory
{
    protected $model = Module::class;

    public function definition(): array
    {
        return [
            'titre'        => fake()->sentence(3),
            'contenu'      => fake()->paragraph(),
            'formation_id' => Formation::factory(),
            'ordre'        => fake()->numberBetween(1, 10),
        ];
    }
}