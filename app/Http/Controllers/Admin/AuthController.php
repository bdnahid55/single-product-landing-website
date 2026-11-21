<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show admin login form.
     */
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return Auth::user()->can('view-dashboard')
                ? redirect()->route('admin.dashboard')
                : redirect('/admin');
        }

        return view('admin.auth.login');
    }

    /**
     * Authenticate admin credentials.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            if (!Auth::user()->is_admin) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'You do not have administrative access privileges.',
                ]);
            }

            $request->session()->regenerate();

            $target = Auth::user()->can('view-dashboard')
                ? route('admin.dashboard')
                : url('/admin');

            return redirect()->intended($target)
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log out admin session.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
}
