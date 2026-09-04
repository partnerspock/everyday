<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'general_notes' => [
                'view_notes'   => 'View General Notes',
                'create_notes' => 'Create General Notes',
                'edit_notes'   => 'Edit General Notes',
                'delete_notes' => 'Delete General Notes',
            ],
            'spaces' => [
                'view_spaces'   => 'View Spaces',
                'create_spaces' => 'Create Spaces',
                'edit_spaces'   => 'Edit Spaces',
                'delete_spaces' => 'Delete Spaces',
            ],
            'activity_logs' => [
                'view_logs'   => 'View System Activity Logs',
                'export_logs' => 'Export Activity Logs Data',
            ],
            'roles' => [
                'view_roles'   => 'View Roles',
                'create_roles' => 'Create Roles',
                'edit_roles'   => 'Edit Roles',
                'delete_roles' => 'Delete Roles',
            ],
            'permissions' => [
                'view_permissions'   => 'View Permissions',
                'create_permissions' => 'Create Permissions',
                'edit_permissions'   => 'Edit Permissions',
            ],
            'admin_users' => [
                'view_admin_users'   => 'View Admin Users',
                'create_admin_users' => 'Create Admin Users',
                'edit_admin_users'   => 'Edit Admin Users',
                'delete_admin_users' => 'Delete Admin Users',
            ],
        ];

        foreach ($modules as $moduleName => $subPermissions) { //foreach buat kasih alias name dari modules sblmnya jadi modulename
            foreach ($subPermissions as $keyIdentifier => $friendlyName) { //foreach buat kasih alias name dari subpermissions sblmnya jadi permissionname
                
                Permission::firstOrCreate([
                    'module_name'     => $moduleName,     // e.g., 'general_notes'
                    'permission_name' => $friendlyName,   // e.g., 'View General Notes'
                ], [
                    'module_key'        => $keyIdentifier . '_key', // e.g., 'view_notes_key'
                    'permission_status' => 'active',
                    'is_default' => true,
                ]);

            }
        }
    }
}