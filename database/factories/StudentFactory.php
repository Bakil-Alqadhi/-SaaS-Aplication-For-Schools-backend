<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\ParentStudent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = ['Male', 'Female'];
        $grade = Grade::inRandomOrder()->first();
        return [
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'parent_id'=> ParentStudent::inRandomOrder()->first()->id,
            'grade_id'=> $grade->id,
            'classroom_id'=> $grade->classrooms->first()->id,
            'section_id' => null,
            'academic_year' => fake()->date('Y-m-d'),
            'sex' => $gender[array_rand($gender)],
            'birthday' => fake()->date("Y-m-d"),
            "isJoined"=> fake()->boolean(),
            'image' => fake()->imageUrl(),
            // 'parent_id'=>fake()->unique(),
            "address" => fake()->address(),
            "phone" => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),

        ];
    }
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
