<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BiometricDevice>
 */
class BiometricDeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locations = [
            'Main Office - Entrance',
            'Main Office - Exit',
            'Branch Office - Front Desk',
            'Warehouse - Loading Bay',
            'Factory Floor - Section A',
            'HR Department',
        ];

        return [
            'device_id'    => 'DEV-'.fake()->unique()->numerify('####'),
            'name'         => 'Biometric Scanner '.fake()->randomNumber(2),
            'location'     => fake()->randomElement($locations),
            'api_key'      => 'sk_'.fake()->unique()->regexify('[A-Za-z0-9]{40}'),
            'status'       => fake()->randomElement(['active', 'active', 'active', 'inactive', 'maintenance']), // 60% active
            'last_ping_at' => fake()->optional(0.8)->dateTimeBetween('-1 week', 'now'),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'       => 'active',
            'last_ping_at' => now()->subMinutes(fake()->numberBetween(1, 60)),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'       => 'inactive',
            'last_ping_at' => fake()->optional(0.3)->dateTimeBetween('-1 month', '-1 week'),
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'       => 'maintenance',
            'last_ping_at' => fake()->dateTimeBetween('-3 days', '-1 day'),
        ]);
    }
}
