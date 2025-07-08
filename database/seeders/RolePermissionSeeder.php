<?php

namespace Database\Seeders;

use App\Models\User;
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
            // Library management
            'manage library',
            'add books',
            'remove books',
            'update book status',
            'sync books',

            // User management (for admins)
            'manage users',
            'view all libraries',
            'assign roles',

            // System administration
            'manage system',
            'view analytics',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Regular user role
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'manage library',
            'add books',
            'remove books',
            'update book status',
            'sync books',
        ]);

        // Librarian role (can help manage books but not users)
        $librarianRole = Role::create(['name' => 'librarian']);
        $librarianRole->givePermissionTo([
            'manage library',
            'add books',
            'remove books',
            'update book status',
            'sync books',
            'view all libraries',
        ]);

        // Admin role (full access)
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Assign default role to existing users
        $users = User::all();
        foreach ($users as $user) {
            if (! $user->hasAnyRole(['user', 'librarian', 'admin'])) {
                $user->assignRole('user');
            }
        }

        // You can manually assign admin role to a specific user if needed
        // $adminUser = User::where('email', 'admin@example.com')->first();
        // if ($adminUser) {
        //     $adminUser->assignRole('admin');
        // }
    }
}
