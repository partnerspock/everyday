<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['role_name' => 'Super Admin', 'is_default' => true]);
        $viewOnly   = Role::firstOrCreate(['role_name' => 'User (View Only)', 'is_default' => true]);
        $createOnly = Role::firstOrCreate(['role_name' => 'User (Create Only)', 'is_default' => true]);
        $editOnly   = Role::firstOrCreate(['role_name' => 'User (Edit Only)', 'is_default' => true]);
        $deleteOnly = Role::firstOrCreate(['role_name' => 'User (Delete Only)', 'is_default' => true]);
        $partner = Role::firstOrCreate(['role_name' => 'Partner', 'is_default' => true]);

        $superAdminPermissions = Permission::whereNotIn('id', [19, 20, 21])->pluck('id');
        $viewPermissionIds   = Permission::where('permission_name', 'like', 'View%')->pluck('id');
        $createPermissionIds = Permission::where('permission_name', 'like', 'Create%')->pluck('id');
        $editPermissionIds   = Permission::where('permission_name', 'like', 'Edit%')->pluck('id');
        $deletePermissionIds = Permission::where('permission_name', 'like', 'Delete%')->pluck('id');
        $allPermissions = Permission::pluck('id');

        $superAdmin->permissions()->sync($superAdminPermissions);
        $viewOnly->permissions()->sync($viewPermissionIds);
        $createOnly->permissions()->sync($createPermissionIds);
        $editOnly->permissions()->sync($editPermissionIds);
        $deleteOnly->permissions()->sync($deletePermissionIds);
        $partner->permissions()->sync($allPermissions);
    }
}