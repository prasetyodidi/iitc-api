<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participant>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isStudent = fake()->boolean;
        $isMale = fake()->boolean;
        return [
            'grade' => $isStudent ? Grade::Student : Grade::CollegeStudent,
            'institution' => fake()->company,
            'gender' => $isMale ? Gender::Male : Gender::Female,
            'student_id_number' => fake()->bothify('??##??##??###'),
            'avatar' => fake()->imageUrl,
            'photo_identity' => fake()->imageUrl,
        ];
    }
}
