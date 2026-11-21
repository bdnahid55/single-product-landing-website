<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            return redirect()->route('admin.login')->with('error', 'Please log in with admin privileges to access this area.');
        }

        // Automatic admin logout after 1 hour (3600 seconds) inactivity
        $inactivityLimit = 3600;
        $lastActivity = session('admin_last_activity');

        if ($lastActivity && (time() - $lastActivity) > $inactivityLimit) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('warning', 'নিষ্ক্রিয়তার কারণে (১ ঘণ্টা নিষ্ক্রিয় থাকার পর) আপনাকে স্বয়ংক্রিয়ভাবে লগআউট করা হয়েছে। অনুগ্রহ করে পুনরায় লগইন করুন। (Automatically logged out due to 1 hour of inactivity).');
        }

        // Update last activity timestamp on each active request
        session(['admin_last_activity' => time()]);

        return $next($request);
    }
}
