<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->error('⚠️  This seeder creates test users with default passwords and should NOT run in production!');
            $this->command->warn('If you need to seed production data, create a separate production-specific seeder.');

            return;
        }

        $departments = Department::all();
        $shifts = Shift::all();

        if ($departments->isEmpty() || $shifts->isEmpty()) {
            $this->command->error('Please run DepartmentSeeder and ShiftSeeder first!');

            return;
        }

        DB::transaction(function () use ($departments, $shifts) {
            // Create Admin User and Employee
            $adminUser = User::firstOrCreate(
                ['email' => 'admin@biometric.test'],
                [
                    'name'     => 'System Administrator',
                    'password' => Hash::make('password'),
                ]
            );
            $adminUser->assignRole('admin');

            $adminEmployee = Employee::firstOrCreate(
                ['employee_id' => 'EMP-001'],
                [
                    'user_id'                  => $adminUser->id,
                    'first_name'               => 'System',
                    'last_name'                => 'Administrator',
                    'email'                    => 'admin@biometric.test',
                    'phone_number'             => '+1234567890',
                    'department_id'            => $departments->where('code', 'IT')->first()->id,
                    'shift_id'                 => $shifts->where('name', 'Flexible Shift')->first()->id,
                    'biometric_fingerprint_id' => 'BIO-000001',
                    'date_hired'               => now()->subYears(5),
                    'status'                   => 'active',
                ]
            );

            // Create HR Manager
            $hrUser = User::firstOrCreate(
                ['email' => 'hr@biometric.test'],
                [
                    'name'     => 'HR Manager',
                    'password' => Hash::make('password'),
                ]
            );
            $hrUser->assignRole('hr_manager');

            $hrEmployee = Employee::firstOrCreate(
                ['employee_id' => 'EMP-002'],
                [
                    'user_id'                  => $hrUser->id,
                    'first_name'               => 'Human',
                    'last_name'                => 'Resources',
                    'email'                    => 'hr@biometric.test',
                    'phone_number'             => '+1234567891',
                    'department_id'            => $departments->where('code', 'HR')->first()->id,
                    'shift_id'                 => $shifts->where('name', 'Morning Shift')->first()->id,
                    'biometric_fingerprint_id' => 'BIO-000002',
                    'date_hired'               => now()->subYears(4),
                    'status'                   => 'active',
                ]
            );

            // Set HR Manager as department manager for HR
            $hrDept = $departments->where('code', 'HR')->first();
            $hrDept->manager_id = $hrEmployee->id;
            $hrDept->save();

            // Create Department Managers for IT, Sales, and Operations
            $employeeCounter = 3;
            $managerDepts = $departments->whereIn('code', ['IT', 'SALES', 'OPS']);

            foreach ($managerDepts as $dept) {
                $user = User::create([
                    'name'     => $dept->name.' Manager',
                    'email'    => strtolower($dept->code).'.manager@biometric.test',
                    'password' => Hash::make('password'),
                ]);
                $user->assignRole('department_manager');

                $employee = Employee::create([
                    'user_id'                  => $user->id,
                    'employee_id'              => 'EMP-'.str_pad($employeeCounter++, 3, '0', STR_PAD_LEFT),
                    'first_name'               => $dept->name,
                    'last_name'                => 'Manager',
                    'email'                    => $user->email,
                    'phone_number'             => '+123456789'.$employeeCounter,
                    'department_id'            => $dept->id,
                    'shift_id'                 => $shifts->random()->id,
                    'biometric_fingerprint_id' => 'BIO-'.str_pad($employeeCounter - 1, 6, '0', STR_PAD_LEFT),
                    'date_hired'               => now()->subYears(rand(2, 4)),
                    'status'                   => 'active',
                ]);

                // Set as department manager
                $dept->manager_id = $employee->id;
                $dept->save();
            }

            // Create 15 regular employees distributed across departments
            for ($i = 0; $i < 15; $i++) {
                $dept = $departments->random();
                $shift = $shifts->random();

                $firstName = fake()->firstName();
                $lastName = fake()->lastName();
                $email = strtolower($firstName.'.'.$lastName.'@biometric.test');

                $user = User::create([
                    'name'     => $firstName.' '.$lastName,
                    'email'    => $email,
                    'password' => Hash::make('password'),
                ]);
                $user->assignRole('employee');

                Employee::create([
                    'user_id'                  => $user->id,
                    'employee_id'              => 'EMP-'.str_pad($employeeCounter++, 3, '0', STR_PAD_LEFT),
                    'first_name'               => $firstName,
                    'last_name'                => $lastName,
                    'middle_name'              => fake()->optional(0.5)->firstName(),
                    'email'                    => $email,
                    'phone_number'             => fake()->optional(0.8)->phoneNumber(),
                    'department_id'            => $dept->id,
                    'shift_id'                 => $shift->id,
                    'biometric_fingerprint_id' => 'BIO-'.str_pad($employeeCounter - 1, 6, '0', STR_PAD_LEFT),
                    'date_hired'               => fake()->dateTimeBetween('-3 years', '-1 month'),
                    'status'                   => fake()->randomElement(['active', 'active', 'active', 'active', 'inactive']),
                ]);
            }

            $this->command->info('Created 20 employees across '.$departments->count().' departments.');
            $this->command->info('Default password for all users: password');
        });
    }
}
