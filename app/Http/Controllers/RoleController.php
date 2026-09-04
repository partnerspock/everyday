<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('role_name')->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $groupedPermissions = Permission::where('permission_status', 'active')
                                    ->get()
                                    ->groupBy('module_name'); // Or 'module_key' based on your schema

        return view('roles.create', compact('groupedPermissions'));
        
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => ['required', 'string', 'max:255', 'unique:admins_roles,role_name'],
            'permissions' => 'array',
            'permissions.*' => 'exists:admins_permissions,id',
        ]);

        $role = Role::create([
            'role_name' => $validated['role_name']
        ]);

        if ($request->has('permissions') && !empty($request->permissions)) {
            $role->permissions()->sync($request->permissions);
        }

        ActivityLog::record('Roles', 'Create', 'Created Role ' . $role->role_name);
        return redirect()->route('roles.index')->with('success', 'New role and permissions created successfully!');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $groupedPermissions = Permission::where('permission_status', 'active')
                                        ->get()
                                        ->groupBy('module_name');

        $assignedPermissionIds = $role->permissions()->pluck('admins_permissions.id')->toArray();

        return view('roles.edit', compact('role', 'groupedPermissions', 'assignedPermissionIds'));

    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'role_name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:admins_permissions,id',
        ]);

        $role->update([
            'role_name' => $request->role_name,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        ActivityLog::record('Roles', 'Update', 'Updated Role' . $role->role_name);
        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->is_default) {
            return back()->withErrors([
                'error' => 'Default role cannot be deleted!'
            ]);
       }

        $role->delete();

        ActivityLog::record('Roles', 'Delete', 'Deleted Role' . $role->role_name);
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
    }
}