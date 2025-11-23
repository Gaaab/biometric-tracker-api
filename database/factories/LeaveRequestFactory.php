<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $leaveTypes = ['vacation', 'sick', 'personal', 'emergency', 'bereavement'];
        $status = fake()->randomElement(['pending', 'approved', 'approved', 'rejected']);

        $startDate = fake()->dateTimeBetween('-2 months', '+1 month');
        $daysCount = fake()->numberBetween(1, 10);
        $endDate = (clone $startDate)->modify("+{$daysCount} days");

        return [
            'employee_id'      => \App\Models\Employee::factory(),
            'leave_type'       => fake()->randomElement($leaveTypes),
            'start_date'       => $startDate,
            'end_date'         => $endDate,
            'days_count'       => $daysCount,
            'reason'           => fake()->sentence(10),
            'status'           => $status,
            'approver_id'      => $status !== 'pending' ? \App\Models\User::factory() : null,
            'approved_at'      => $status !== 'pending' ? fake()->dateTimeBetween($startDate, 'now') : null,
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

    public function vacation(): static
    {
        return $this->state(fn (array $attributes) => [
            'leave_type' => 'vacation',
        ]);
    }

    public function sick(): static
    {
        return $this->state(fn (array $attributes) => [
            'leave_type' => 'sick',
        ]);
    }
}
