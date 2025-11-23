<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'                  => null, // Will be set later if needed
            'employee_id'              => 'EMP-'.fake()->unique()->numerify('###'),
            'first_name'               => fake()->firstName(),
            'last_name'                => fake()->lastName(),
            'middle_name'              => fake()->optional(0.7)->firstName(),
            'email'                    => fake()->unique()->safeEmail(),
            'phone_number'             => fake()->optional(0.8)->phoneNumber(),
            'department_id'            => \App\Models\Department::factory(),
            'shift_id'                 => \App\Models\Shift::factory(),
            'biometric_fingerprint_id' => 'BIO-'.fake()->unique()->numerify('######'),
            'date_hired'               => fake()->dateTimeBetween('-5 years', '-1 month'),
            'status'                   => fake()->randomElement(['active', 'active', 'active', 'inactive', 'suspended']), // 60% active
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }
}
