<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'subject_id' => fake()->numberBetween(5,34),
            'title' => fake()->name(),
            'slug' => 'slug',
            'description' => fake()->paragraph(10),
        ];
    }
}
