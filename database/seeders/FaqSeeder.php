<?php

namespace Secretwebmaster\WncmsFaqs\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin roles
        $superadmin = Role::where('name', 'superadmin')->first();
        $admin = Role::where('name', 'admin')->first();

        if (!$superadmin || !$admin) {
            $this->command->warn('Roles not found. Run RolesSeeder first.');
            return;
        }

        // Create FAQ permissions
        foreach ($this->default_permission_suffixes() as $suffix) {
            $permissionName = "faq_{$suffix}";
            $permission = Permission::firstOrCreate(['name' => $permissionName]);

            $superadmin->givePermissionTo($permission);
            $admin->givePermissionTo($permission);
        }
    }

    /**
     * Permission suffixes.
     */
    protected function default_permission_suffixes(): array
    {
        return [
            'list',
            'index',
            'show',
            'create',
            'clone',
            'bulk_create',
            'edit',
            'bulk_edit',
            'delete',
            'bulk_delete',
        ];
    }
}
