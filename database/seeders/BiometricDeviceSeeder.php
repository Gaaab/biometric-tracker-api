<?php

namespace Database\Seeders;

use App\Models\BiometricDevice;
use Illuminate\Database\Seeder;

class BiometricDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = [
            [
                'device_id'    => 'DEV-0001',
                'name'         => 'Main Office Scanner',
                'location'     => 'Main Office - Entrance',
                'api_key'      => 'sk_main_office_'.bin2hex(random_bytes(20)),
                'status'       => 'active',
                'last_ping_at' => now(),
            ],
            [
                'device_id'    => 'DEV-0002',
                'name'         => 'HR Department Scanner',
                'location'     => 'HR Department',
                'api_key'      => 'sk_hr_dept_'.bin2hex(random_bytes(20)),
                'status'       => 'active',
                'last_ping_at' => now()->subMinutes(5),
            ],
            [
                'device_id'    => 'DEV-0003',
                'name'         => 'Warehouse Scanner',
                'location'     => 'Warehouse - Loading Bay',
                'api_key'      => 'sk_warehouse_'.bin2hex(random_bytes(20)),
                'status'       => 'active',
                'last_ping_at' => now()->subMinutes(30),
            ],
        ];

        foreach ($devices as $device) {
            BiometricDevice::firstOrCreate(
                ['device_id' => $device['device_id']],
                $device
            );
        }

        $this->command->info('Created '.count($devices).' biometric devices.');
    }
}
