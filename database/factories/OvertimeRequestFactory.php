<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OvertimeRequest>
 */
class OvertimeRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'approved', 'approved', 'rejected']);
        $date = fake()->dateTimeBetween('-2 months', '+1 week');

        return [
            'employee_id'      => \App\Models\Employee::factory(),
            'date'             => $date,
            'hours'            => fake()->randomFloat(2, 1, 8), // 1-8 hours with 2 decimal places
            'reason'           => fake()->sentence(10),
            'status'           => $status,
            'approver_id'      => $status !== 'pending' ? \App\Models\User::factory() : null,
            'approved_at'      => $status !== 'pending' ? fake()->dateTimeBetween($date, 'now') : null,
            'rejection_reason' => $status === 'rejected' ? fake()->sentence(8) : null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'           => 'pending',
            'approver_id'      => null,
            'approved_at'      => null,
            'rejection_reason' => null,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'           => 'approved',
            'approver_id'      => \App\Models\User::factory(),
            'approved_at'      => now(),
            'rejection_reason' => null,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'           => 'rejected',
            'approver_id'      => \App\Models\User::factory(),
            'approved_at'      => now(),
            'rejection_reason' => fake()->sentence(8),
        ]);
    }
}
