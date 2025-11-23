<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\BiometricDevice;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::where('status', 'active')->get();
        $devices = BiometricDevice::all();

        if ($employees->isEmpty()) {
            $this->command->warn('No active employees found. Skipping attendance seeding.');

            return;
        }

        DB::transaction(function () use ($employees, $devices) {
            $deviceIds = $devices->pluck('id')->toArray();
            $attendanceCount = 0;

            // Create attendance records for the past 30 days
            foreach ($employees as $employee) {
                // Generate unique dates for this employee (20-25 days in the past 30 days)
                $daysToGenerate = rand(20, 25);
                $startDate = now()->subDays(30);
                $availableDates = collect(range(0, 29))->map(fn ($i) => $startDate->copy()->addDays($i));
                $selectedDates = $availableDates->random($daysToGenerate);

                foreach ($selectedDates as $date) {

                    // Generate realistic attendance patterns
                    $status = fake()->randomElement([
                        'present', 'present', 'present', 'present', 'present',
                        'late', 'late',
                        'absent',
                        'on_leave',
                    ]);

                    $timeIn = null;
                    $timeOut = null;
                    $lateMinutes = 0;
                    $overtimeMinutes = 0;
                    $undertimeMinutes = 0;

                    if (in_array($status, ['present', 'late'])) {
                        $baseTimeIn = (clone $date)->setTime(8, 0);

                        if ($status === 'late') {
                            $lateMinutes = fake()->numberBetween(5, 90);
                            $timeIn = (clone $baseTimeIn)->addMinutes($lateMinutes);
                        } else {
                            $timeIn = (clone $baseTimeIn)->addMinutes(fake()->numberBetween(-5, 10));
                        }

                        $baseTimeOut = (clone $date)->setTime(17, 0);
                        $timeOut = (clone $baseTimeOut)->addMinutes(fake()->numberBetween(-20, 60));

                        $diff = $timeOut->diffInMinutes($baseTimeOut, false);
                        if ($diff > 0) {
                            $overtimeMinutes = $diff;
                        } elseif ($diff < -15) {
                            $undertimeMinutes = abs($diff);
                        }
                    }

                    Attendance::create([
                        'employee_id'         => $employee->id,
                        'date'                => $date,
                        'time_in'             => $timeIn,
                        'time_out'            => $timeOut,
                        'status'              => $status,
                        'late_minutes'        => $lateMinutes,
                        'overtime_minutes'    => $overtimeMinutes,
                        'undertime_minutes'   => $undertimeMinutes,
                        'remarks'             => $status === 'late' ? 'Traffic delay' : null,
                        'biometric_device_id' => ! empty($deviceIds) && $timeIn ? fake()->randomElement($deviceIds) : null,
                    ]);

                    $attendanceCount++;
                }
            }

            $this->command->info("Created {$attendanceCount} attendance records for ".$employees->count().' employees.');
        });
    }
}
