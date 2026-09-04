<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\ActivityLog;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::with('roles')->orderBy('name')->get();

        return view('admins.index', compact('admins'));
    }

    public function create()
    {
        $roles = Role::orderBy('role_name')->get();

        return view('admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:admins,username',
        'email'    => 'required|email|max:255|unique:admins,email',
        'role_id'  => 'required|exists:admins_roles,id',
        'password' => 'required|string|min:4',
    ]);

    $admin = Admin::create([
        'name'     => $validated['name'],
        'username' => $validated['username'],
        'email'    => $validated['email'],
        'password' => bcrypt($validated['password']),
        'is_confirmed'   => 'confirmed',
        'confirmed_at' => now(),
    ]);

    $admin->roles()->attach($validated['role_id']);
    ActivityLog::record('Admin Users', 'Create', 'Created Admin User: ' . $admin->name);
    return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id); 
        $roles = Role::all();

        return view('admins.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, $id)
    {
    $admin = Admin::findOrFail($id);

    $request->validate([
        'name'     => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,
        'email'    => 'required|email|max:255|unique:admins,email,' . $admin->id,
        'role_id'  => 'required|exists:admins_roles,id',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    $admin->name = $request->name;
    $admin->username = $request->username;
    $admin->email = $request->email;

    if ($request->filled('password')) {
        $admin->password = bcrypt($request->password);
    }
    $admin->save();

    $admin->roles()->sync([$request->role_id]);

    ActivityLog::record('Admin Users', 'Update', 'Updated Admin User: ' . $admin->name);
    return redirect()->route('admins.index')->with('status', 'Admin user updated successfully.');
    }

    public function roles()
    {
    return $this->belongsToMany(
        Role::class, 
        'admins_has_role', 
        'id_user',         
        'id_role'          
    );
    }

    public function getRoleAttribute()
    {
        return $this->roles->first();
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();
        
        ActivityLog::record('Admin Users', 'Status', 'Deactivated Admin User: ' . $admin->name);
        return redirect()->route('admins.index')->with('success', 'Admin deactivated successfully!');
    }

    public function activate(Admin $admin)
    {
        $admin->update(['deactivated_at' => null]);

        ActivityLog::record('Admin Users', 'Status', 'Re-Activate Admin User: ' . $admin->name);
        return back()->with('status', 'Admin activated.');
    }
}