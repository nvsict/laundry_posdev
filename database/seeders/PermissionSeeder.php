<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // A minimal list of permissions that are actually used in the app
        $permissions = [
            'create order',
            'view all orders',
            'update order status',
            'collect payment', // Corresponds to changing payment status
            'manage stock',
            'view reports',
            'manage services',
            'manage staff',
            'access settings',
            'manage permissions',
        ];

        // Create the permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Admin Role and assign all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Create Staff Role and assign a subset of permissions
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $staffRole->syncPermissions([
            'create order',
            'view all orders',
            'update order status',
            'collect payment',
        ]);
    }
}