<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-3 months', 'now');
        $status = fake()->randomElement(['present', 'present', 'present', 'late', 'absent', 'on_leave', 'half_day']);

        $timeIn = null;
        $timeOut = null;
        $lateMinutes = 0;
        $overtimeMinutes = 0;
        $undertimeMinutes = 0;

        if (in_array($status, ['present', 'late', 'half_day'])) {
            $baseTimeIn = (clone $date)->setTime(8, 0); // Default 8:00 AM

            if ($status === 'late') {
                $lateMinutes = fake()->numberBetween(5, 120);
                $timeIn = (clone $baseTimeIn)->addMinutes($lateMinutes);
            } else {
                $timeIn = (clone $baseTimeIn)->addMinutes(fake()->numberBetween(-10, 15));
            }

            if ($status !== 'half_day') {
                $baseTimeOut = (clone $date)->setTime(17, 0); // Default 5:00 PM
                $timeOut = (clone $baseTimeOut)->addMinutes(fake()->numberBetween(-30, 120));

                $diff = $timeOut->diffInMinutes($baseTimeOut, false);
                if ($diff > 0) {
                    $overtimeMinutes = $diff;
                } elseif ($diff < -15) {
                    $undertimeMinutes = abs($diff);
                }
            } else {
                $timeOut = (clone $timeIn)->addHours(4);
            }
        }

        return [
            'employee_id'         => \App\Models\Employee::factory(),
            'date'                => $date,
            'time_in'             => $timeIn,
            'time_out'            => $timeOut,
            'status'              => $status,
            'late_minutes'        => $lateMinutes,
            'overtime_minutes'    => $overtimeMinutes,
            'undertime_minutes'   => $undertimeMinutes,
            'remarks'             => fake()->optional(0.2)->sentence(),
            'biometric_device_id' => null, // Set in seeder when needed to avoid N+1 queries
        ];
    }

    public function present(): static
    {
        return $this->state(function (array $attributes) {
            $date = $attributes['date'] ?? now();
            $timeIn = (clone $date)->setTime(8, 0)->addMinutes(fake()->numberBetween(-10, 5));
            $timeOut = (clone $date)->setTime(17, 0)->addMinutes(fake()->numberBetween(-15, 30));

            return [
                'status'       => 'present',
                'time_in'      => $timeIn,
                'time_out'     => $timeOut,
                'late_minutes' => 0,
            ];
        });
    }

    public function late(): static
    {
        return $this->state(function (array $attributes) {
            $lateMinutes = fake()->numberBetween(5, 120);
            $date = $attributes['date'] ?? now();
            $timeIn = (clone $date)->setTime(8, 0)->addMinutes($lateMinutes);
            $timeOut = (clone $date)->setTime(17, 0);

            return [
                'status'       => 'late',
                'time_in'      => $timeIn,
                'time_out'     => $timeOut,
                'late_minutes' => $lateMinutes,
            ];
        });
    }

    public function absent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'            => 'absent',
            'time_in'           => null,
            'time_out'          => null,
            'late_minutes'      => 0,
            'overtime_minutes'  => 0,
            'undertime_minutes' => 0,
        ]);
    }

    public function onLeave(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'            => 'on_leave',
            'time_in'           => null,
            'time_out'          => null,
            'late_minutes'      => 0,
            'overtime_minutes'  => 0,
            'undertime_minutes' => 0,
        ]);
    }
}
