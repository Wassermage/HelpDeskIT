<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create default groups
        $groups = [
            'Network Operations',
            'Database Admins',
            'Business Applications',
            'Shop Floor Applications',
            'Microsoft 365 Services',
        ];

        foreach ($groups as $groupName) {
            Group::firstOrCreate(['name' => $groupName]);
        }
    }
}
