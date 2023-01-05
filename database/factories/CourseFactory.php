<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->name(),
            'slug' => 'slug',
            'duration' => '1 ano',
            'description' => fake()->paragraph(3),
            'type' => 'setbal',
            'polo_id' => fake()->numberBetween(1,3),
            'status' => 'sim'
        ];
    }
}
