<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
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
            // المستخدمين
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // المحتوى
            'view_content',
            'create_content',
            'edit_content',
            'delete_content',
            
            // الإعدادات
            'view_settings',
            'edit_settings',
            
            // رسائل الاتصال
            'view_contactus',
            'reply_contactus',
            'delete_contactus',
            
            // المدفوعات
            'view_payments',
            'manage_payments',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo([
            'view_users', 'create_users', 'edit_users',
            'view_content', 'create_content', 'edit_content',
            'view_settings',
            'view_contactus', 'reply_contactus', 'delete_contactus',
            'view_payments'
        ]);

        $role = Role::create(['name' => 'manager']);
        $role->givePermissionTo([
            'view_users',
            'view_content', 'create_content', 'edit_content',
            'view_contactus', 'reply_contactus'
        ]);

        // Assign super-admin role to existing admin users
        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $user) {
            $user->assignRole('super-admin');
        }

        // Assign manager role to existing manager users
        $managerUsers = User::where('role', 'manager')->get();
        foreach ($managerUsers as $user) {
            $user->assignRole('manager');
        }
    }
}