<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = [
            ['name' => 'Information Technology', 'code' => 'IT'],
            ['name' => 'Human Resources', 'code' => 'HR'],
            ['name' => 'Sales', 'code' => 'SALES'],
            ['name' => 'Operations', 'code' => 'OPS'],
            ['name' => 'Finance', 'code' => 'FIN'],
            ['name' => 'Marketing', 'code' => 'MKT'],
        ];

        $dept = fake()->randomElement($departments);

        return [
            'name'        => $dept['name'],
            'code'        => $dept['code'],
            'manager_id'  => null, // Will be set after employees are created
            'description' => fake()->optional()->sentence(10),
        ];
    }
}
