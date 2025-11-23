<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            [
                'name'                   => 'Morning Shift',
                'time_in'                => '08:00',
                'time_out'               => '17:00',
                'grace_period_minutes'   => 15,
                'break_duration_minutes' => 60,
                'is_active'              => true,
            ],
            [
                'name'                   => 'Night Shift',
                'time_in'                => '20:00',
                'time_out'               => '05:00',
                'grace_period_minutes'   => 15,
                'break_duration_minutes' => 60,
                'is_active'              => true,
            ],
            [
                'name'                   => 'Flexible Shift',
                'time_in'                => '09:00',
                'time_out'               => '18:00',
                'grace_period_minutes'   => 20,
                'break_duration_minutes' => 60,
                'is_active'              => true,
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::firstOrCreate(
                ['name' => $shift['name']],
                $shift
            );
        }
    }
}
