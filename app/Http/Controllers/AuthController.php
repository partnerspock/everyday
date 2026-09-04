<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = $request->input('login');
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $admin = Admin::where($field, $loginInput)->first();

        if ($admin && ($admin->is_confirmed === 'not_confirmed') || is_null($admin->confirmed_at)) {
            return back()->withErrors([
                'login' => 'Please confirm you account first.',
            ])->onlyInput('login');
        }


        $credentials = [
            $field     => $loginInput,
            'password' => $request->input('password'),
        ];

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        Auth::user()->update(['last_login_at' => now()]);

        ActivityLog::record('Auths', 'Login', 'Login Users ' . $admin->name);
        return redirect()->intended(route('home'));
    }

    return back()->withErrors([
        'login' => 'Invalid login information.',
    ])->onlyInput('login');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}