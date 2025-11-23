<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // 1. Seed roles and permissions first (required for user role assignment)
        $this->command->info('📋 Seeding roles and permissions...');
        $this->call(RolePermissionSeeder::class);

        // 2. Seed departments (required for employees)
        $this->command->info('🏢 Seeding departments...');
        $this->call(DepartmentSeeder::class);

        // 3. Seed shifts (required for employees)
        $this->command->info('⏰ Seeding shifts...');
        $this->call(ShiftSeeder::class);

        // 4. Seed employees with users (depends on departments, shifts, and roles)
        $this->command->info('👥 Seeding employees and users...');
        $this->call(EmployeeSeeder::class);

        // 5. Seed biometric devices (optional, for attendance records)
        $this->command->info('🔐 Seeding biometric devices...');
        $this->call(BiometricDeviceSeeder::class);

        // 6. Seed attendance records (optional, depends on employees and devices)
        $this->command->info('📊 Seeding attendance records...');
        $this->call(AttendanceSeeder::class);

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('🔑 Default login credentials:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@biometric.test', 'password'],
                ['HR Manager', 'hr@biometric.test', 'password'],
                ['IT Manager', 'it.manager@biometric.test', 'password'],
                ['Sales Manager', 'sales.manager@biometric.test', 'password'],
                ['Operations Manager', 'ops.manager@biometric.test', 'password'],
            ]
        );
    }
}
