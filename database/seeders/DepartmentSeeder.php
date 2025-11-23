<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name'        => 'Information Technology',
                'code'        => 'IT',
                'description' => 'Manages all technology infrastructure, software development, and IT support services.',
            ],
            [
                'name'        => 'Human Resources',
                'code'        => 'HR',
                'description' => 'Handles recruitment, employee relations, benefits, and organizational development.',
            ],
            [
                'name'        => 'Sales',
                'code'        => 'SALES',
                'description' => 'Drives revenue through customer acquisition, account management, and business development.',
            ],
            [
                'name'        => 'Operations',
                'code'        => 'OPS',
                'description' => 'Oversees daily business operations, logistics, and process optimization.',
            ],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['code' => $department['code']],
                $department
            );
        }
    }
}
