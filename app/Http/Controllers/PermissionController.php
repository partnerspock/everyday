<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('module_name')->paginate(10);
        
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_key' => ['required', 'string', 'max:255'],
            'module_name' => ['required', 'in:general_notes,spaces,roles,permissions,admin_users,activity_logs'],
            'permission_name' => ['required', 'string', 'max:255'],
            'permission_status' => ['required', 'in:active,inactive'],
        ]);

        Permission::create($validated);
        ActivityLog::record('Permissions', 'Create', 'Created Permission: ' . $validated['permission_name']);
        return redirect()->route('permissions.index')->with('status', 'Permission created.');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        if ($permission->is_default == 1) {
            return redirect()->route('permissions.index')->with('error', 'Default permissions cannot be edited.');
        }
        
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
    $permission = Permission::findOrFail($id);

    ActivityLog::record('Permissions', 'Update', 'Updated Permission' . $permission->permission_name);
    if ($permission->is_default == 1) {
        return redirect()->route('permissions.index')->with('error', 'Default permissions cannot be edited.');
    }

    $request->validate([
        'module_name' => 'required|in:general_notes,spaces,roles,permissions,admin_users,activity_logs',
        'module_key' => 'required|string|max:255',
        'permission_name' => 'required|string|max:255',
        'permission_status' => 'required|in:active,inactive', 
    ]);

    // Handle the deactivated_at timestamp based on the selected radio value
    $deactivated_At = $permission->deactivated_at;
    if ($request->permission_status === 'inactive' && !$deactivated_At) {
        $deactivated_At = Carbon::now();
    } elseif ($request->permission_status === 'active') {
        $updated_at = Carbon::now();
    }

    $permission->update([
        'module_key' => $request->module_key,
        'module_name' => $request->module_name,
        'permission_name' => $request->permission_name,
        'permission_status' => $request->permission_status,
        'deactivated_at' => $deactivated_At,
    ]);

    ActivityLog::record('Permissions', 'Update', 'Updated Permission: ' . $permission->permission_name);
    return redirect()->route('permissions.index')->with('success', 'Permission updated successfully!');
}
}