<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OpenQuestion>
 */
class OpenQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'question' => fake()->paragraph(3),
            'punctuation' => 5,
            'course_id' => 11,
            'subject_id' => 31
        ];
    }
}
