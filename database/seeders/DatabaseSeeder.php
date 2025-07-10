<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\Service;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'System',
        //     'email' => 'system@example.com',
        // ]);

        User::firstOrCreate(
            ['email' => env('SYSTEM_USER_EMAIL', 'system@example.com')], 
            [
                'name' => env('SYSTEM_USER_NAME', 'System'),
                'password' => bcrypt(env('SYSTEM_USER_PASSWORD', 'password'))
            ]);

        Group::firstOrCreate(['name' => 'default']);

        Service::firstOrCreate(
            ['name' => 'Microsoft 365'],
            ['description' => 'Microsoft 365 (O365) Services']
        );

    }
}
