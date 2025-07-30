<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default permissions
        $permissions = [
            // Menu panels
            ['name' => 'panel.admin', 'label' => 'Admin Panel'],
            ['name' => 'panel.agent', 'label' => 'Support Agent Panel'],
            // Groups
            ['name' => 'groups.assign', 'label' => 'Assign users to support groups'],
            ['name' => 'groups.create', 'label' => 'Create support groups'],
            ['name' => 'groups.delete', 'label' => 'Delete support groups'],
            ['name' => 'groups.update', 'label' => 'Update support groups'],
            ['name' => 'groups.view', 'label' => 'View all support groups'],
            // Roles
            ['name' => 'roles.assign', 'label' => 'Assign users to roles'],
            ['name' => 'roles.create', 'label' => 'Create roles'],
            ['name' => 'roles.delete', 'label' => 'Delete roles'],
            ['name' => 'roles.update', 'label' => 'Update roles'],
            ['name' => 'roles.view', 'label' => 'View all roles'],
            // Services
            ['name' => 'services.create', 'label' => 'Create services'],
            ['name' => 'services.delete', 'label' => 'Delete services'],
            ['name' => 'services.update', 'label' => 'Update services'],
            ['name' => 'services.view', 'label' => 'View all services'],
            // Tickets
            ['name' => 'tickets.assign', 'label' => 'Assign tickets'],
            ['name' => 'tickets.close', 'label' => 'Close tickets'],
            ['name' => 'tickets.create.others', 'label' => 'Create tickets for others'],
            ['name' => 'tickets.create.self', 'label' => 'Create tickets'],
            ['name' => 'tickets.view.own', 'label' => 'View created tickets'],
            ['name' => 'tickets.view.direct', 'label' => 'View tickets assinged to the user'],
            ['name' => 'tickets.view.group', 'label' => 'View tickets assinged to user\'s groups'],
            ['name' => 'tickets.view.all', 'label' => 'View all tickets'],
        ];

        foreach ($permissions as $permData) {
            Permission::firstOrCreate(
                ['name' => $permData['name']],
                ['label' => $permData['label']],
            );
        }

        // Create default roles
        $roles = [
            ['name' => 'admin', 'label' => 'Administrator'],
            ['name' => 'manager', 'label' => 'Support Manager'],
            ['name' => 'agent', 'label' => 'Support Agent'],
            ['name' => 'user', 'label' => 'User'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                [
                    'label' => $roleData['label'],
                    'is_locked' => true,
                ],
            );
        }

        // Assign permissions to roles

        // Grant all permissions to the admin role
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->permissions()->sync(
                Permission::pluck('id')->toArray() 
            );
        }

        // Grant default permissions to other groups
        $rolePermissions = [
            'manager' => [
                'groups.assign',
                'groups.create',
                'groups.delete',
                'groups.update',
                'groups.view',
                'services.create',
                'services.delete',
                'services.update',
                'services.view',
                'tickets.create.others',
                'tickets.create.self',
                'tickets.view.all',
                'tickets.view.group',
                'tickets.view.own',
            ],
            'agent' => [
                'groups.view',
                'services.view',
                'tickets.assign',
                'tickets.create.others',
                'tickets.create.self',
                'tickets.view.all',
                'tickets.view.group',
                'tickets.view.own',
            ],
            'user' => [
                'services.view',
                'tickets.create.self',
                'tickets.view.own',
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $permissionIds = Permission::whereIn('name', $permissionNames)->pluck('id')->toArray();
                $role->permissions()->sync($permissionIds);
            }
        }
    }
}
