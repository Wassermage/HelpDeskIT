<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default roles
        $services = [
            ['name' => 'Hardware', 'description' => 'Hardware issues'],
            ['name' => 'Microsoft 365', 'description' => 'Microsoft 365 (O365) Services'],
            ['name' => 'Network connectivity', 'description' => 'Network connectivity issues'],
            ['name' => 'SAP HR', 'description' => 'SAP - HR module'],
        ];

        foreach ($services as $serviceData) {
            Service::firstOrCreate(
                ['name' => $serviceData['name']],
                ['description' => $serviceData['description']],
            );
        }
    }
}
