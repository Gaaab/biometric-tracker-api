<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Employee Management
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'view own employee record',

            // Department Management
            'view departments',
            'create departments',
            'edit departments',
            'delete departments',
            'view own department',

            // Shift Management
            'view shifts',
            'create shifts',
            'edit shifts',
            'delete shifts',

            // Attendance Management
            'view all attendance',
            'view own attendance',
            'view department attendance',
            'create attendance',
            'edit attendance',
            'delete attendance',
            'check in',
            'check out',

            // Leave Request Management
            'view all leave requests',
            'view own leave requests',
            'view department leave requests',
            'create leave request',
            'edit own leave request',
            'delete own leave request',
            'approve leave requests',
            'reject leave requests',
            'approve department leave requests',

            // Overtime Request Management
            'view all overtime requests',
            'view own overtime requests',
            'view department overtime requests',
            'create overtime request',
            'edit own overtime request',
            'delete own overtime request',
            'approve overtime requests',
            'reject overtime requests',
            'approve department overtime requests',

            // Biometric Device Management
            'view biometric devices',
            'create biometric devices',
            'edit biometric devices',
            'delete biometric devices',

            // Reports & Analytics
            'view all reports',
            'view department reports',
            'export reports',
            'generate payroll data',

            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Audit Logs
            'view audit logs',

            // System Configuration
            'manage system settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Admin Role - Full system access
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // HR Manager Role - All employees, approvals, reports
        $hrManager = Role::firstOrCreate(['name' => 'hr_manager']);
        $hrManager->syncPermissions([
            'view employees',
            'create employees',
            'edit employees',
            'view departments',
            'create departments',
            'edit departments',
            'view shifts',
            'create shifts',
            'edit shifts',
            'view all attendance',
            'create attendance',
            'edit attendance',
            'view all leave requests',
            'create leave request',
            'approve leave requests',
            'reject leave requests',
            'view all overtime requests',
            'create overtime request',
            'approve overtime requests',
            'reject overtime requests',
            'view biometric devices',
            'view all reports',
            'export reports',
            'generate payroll data',
            'view users',
            'create users',
            'edit users',
            'view audit logs',
        ]);

        // Department Manager Role - Team access only
        $departmentManager = Role::firstOrCreate(['name' => 'department_manager']);
        $departmentManager->syncPermissions([
            'view own department',
            'view department attendance',
            'view department leave requests',
            'approve department leave requests',
            'view department overtime requests',
            'approve department overtime requests',
            'view department reports',
            'export reports',
            'check in',
            'check out',
            'view own attendance',
            'view own leave requests',
            'create leave request',
            'edit own leave request',
            'view own overtime requests',
            'create overtime request',
            'edit own overtime request',
        ]);

        // Employee Role - Own records only
        $employee = Role::firstOrCreate(['name' => 'employee']);
        $employee->syncPermissions([
            'view own employee record',
            'check in',
            'check out',
            'view own attendance',
            'view own leave requests',
            'create leave request',
            'edit own leave request',
            'delete own leave request',
            'view own overtime requests',
            'create overtime request',
            'edit own overtime request',
            'delete own overtime request',
        ]);
    }
}
