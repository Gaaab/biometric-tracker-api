<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shifts = [
            [
                'name'     => 'Morning Shift',
                'time_in'  => '08:00',
                'time_out' => '17:00',
            ],
            [
                'name'     => 'Night Shift',
                'time_in'  => '20:00',
                'time_out' => '05:00',
            ],
            [
                'name'     => 'Flexible Shift',
                'time_in'  => '09:00',
                'time_out' => '18:00',
            ],
        ];

        $shift = fake()->randomElement($shifts);

        return [
            'name'                   => $shift['name'],
            'time_in'                => $shift['time_in'],
            'time_out'               => $shift['time_out'],
            'grace_period_minutes'   => fake()->randomElement([10, 15, 20]),
            'break_duration_minutes' => fake()->randomElement([30, 45, 60]),
            'is_active'              => fake()->boolean(90), // 90% chance of being active
        ];
    }
}
