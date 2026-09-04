<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminRegisterController extends Controller
{
    /**
     * Tampilkan Halaman Register (auth/register.blade.php)
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Proses Simpan Data Register dari Guest Page
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins,username',
            'email'    => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:4|confirmed',
        ]);

        $admin = Admin::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'is_confirmed' => 'not_confirmed',
            'confirmed_at' => null,
        ]);

        $defaultRole = Role::where('role_name', 'Partner')->first();
        if ($defaultRole) {
            $admin->roles()->attach($defaultRole->id);
        }

        //event(new Registered($admin));
        //Auth::guard('admin')->login($admin);

        return redirect()->route('login')->with('success', 'Registration successful! Please wait for confirmation.');
    }
}