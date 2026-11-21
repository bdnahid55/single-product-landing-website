<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminLoginOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
     * Authenticate initial admin credentials and dispatch OTP.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', strtolower(trim($credentials['email'])))->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withInput($request->only('email', 'remember'))->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        if (!$user->is_admin) {
            return back()->withErrors([
                'email' => 'You do not have administrative access privileges.',
            ]);
        }

        // Generate a 6-digit OTP code valid for 10 minutes
        $otp = $user->generateTwoFactorCode();

        // Store pending authentication state in session
        session([
            'admin_otp_user_id'  => $user->id,
            'admin_otp_remember' => $request->boolean('remember'),
            'admin_otp_sent_at'  => now()->timestamp,
        ]);

        // Dispatch OTP notification email using database-configured mailer
        \App\Services\MailConfigService::apply();
        try {
            Mail::to($user->email)->send(new AdminLoginOtpMail($user, $otp));
        } catch (\Throwable $e) {
            Log::warning('Could not dispatch Admin OTP email: ' . $e->getMessage());
        }

        Log::info("Admin Login OTP generated for {$user->email}");

        return redirect()->route('admin.otp.show')
            ->with('info', 'আপনার ইমেইলে (' . $user->masked_email . ') একটি ৬-সংখ্যার ওটিপি কোড পাঠানো হয়েছে।');
    }

    /**
     * Show the OTP verification form.
     */
    public function showOtpForm()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return Auth::user()->can('view-dashboard')
                ? redirect()->route('admin.dashboard')
                : redirect('/admin');
        }

        if (!session()->has('admin_otp_user_id')) {
            return redirect()->route('admin.login');
        }

        $user = User::find(session('admin_otp_user_id'));

        if (!$user || !$user->is_admin) {
            session()->forget(['admin_otp_user_id', 'admin_otp_remember', 'admin_otp_sent_at']);
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'সেশন শেষ হয়ে গেছে। অনুগ্রহ করে পুনরায় লগইন করুন।']);
        }

        return view('admin.auth.otp', compact('user'));
    }

    /**
     * Verify the submitted OTP and complete authentication.
     */
    public function verifyOtp(Request $request)
    {
        if (!session()->has('admin_otp_user_id')) {
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'সেশন শেষ হয়ে গেছে। অনুগ্রহ করে আবার লগইন করুন।']);
        }

        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'অনুগ্রহ করে ৬ ডিজিটের ওটিপি কোডটি লিখুন।',
            'otp.size'     => 'ওটিপি কোডটি অবশ্যই ৬ ডিজিটের হতে হবে।',
        ]);

        $user = User::find(session('admin_otp_user_id'));

        if (!$user) {
            session()->forget(['admin_otp_user_id', 'admin_otp_remember', 'admin_otp_sent_at']);
            return redirect()->route('admin.login');
        }

        // Check if OTP has expired
        if (empty($user->two_factor_expires_at) || now()->greaterThan($user->two_factor_expires_at)) {
            return back()->withErrors([
                'otp' => 'ওটিপি কোডের মেয়াদ উত্তীর্ণ হয়ে গেছে। অনুগ্রহ করে "পুনরায় কোড পাঠান" বাটনে ক্লিক করুন।',
            ]);
        }

        // Check max failed attempts (Max 5 attempts)
        if ($user->two_factor_attempts >= 5) {
            return back()->withErrors([
                'otp' => 'আপনি সর্বোচ্চ সংখ্যক বার ভুল কোড দিয়েছেন। অনুগ্রহ করে নতুন ওটিপি কোড চেয়ে নিন।',
            ]);
        }

        // Compare OTP
        if (trim($request->otp) !== $user->two_factor_code) {
            $user->increment('two_factor_attempts');
            $remaining = max(0, 5 - $user->two_factor_attempts);
            $msg = 'ভুল ওটিপি কোড প্রদান করেছেন!';
            if ($remaining > 0) {
                $msg .= ' আর ' . $remaining . ' বার চেষ্টা করতে পারবেন।';
            }
            return back()->withErrors(['otp' => $msg]);
        }

        // Success: Reset OTP and finalize login
        $user->resetTwoFactorCode();
        $remember = session('admin_otp_remember', false);
        session()->forget(['admin_otp_user_id', 'admin_otp_remember', 'admin_otp_sent_at']);

        Auth::login($user, $remember);
        $request->session()->regenerate();
        session(['admin_last_activity' => time()]);

        $target = $user->can('view-dashboard')
            ? route('admin.dashboard')
            : url('/admin');

        return redirect()->intended($target)
            ->with('success', 'ওটিপি যাচাই সফল হয়েছে! স্বাগতম, ' . $user->name . '!');
    }

    /**
     * Resend a fresh OTP to the admin's email.
     */
    public function resendOtp(Request $request)
    {
        if (!session()->has('admin_otp_user_id')) {
            return redirect()->route('admin.login');
        }

        // Rate-limit resend requests to once per 60 seconds
        $lastSentAt = session('admin_otp_sent_at');
        if ($lastSentAt && (now()->timestamp - $lastSentAt) < 60) {
            $secondsRemaining = 60 - (now()->timestamp - $lastSentAt);
            return back()->withErrors([
                'otp' => "অনুগ্রহ করে পুনরায় নতুন ওটিপি চাওয়ার পূর্বে {$secondsRemaining} সেকেন্ড অপেক্ষা করুন।",
            ]);
        }

        $user = User::find(session('admin_otp_user_id'));

        if (!$user) {
            return redirect()->route('admin.login');
        }

        $otp = $user->generateTwoFactorCode();
        session(['admin_otp_sent_at' => now()->timestamp]);

        \App\Services\MailConfigService::apply();
        try {
            Mail::to($user->email)->send(new AdminLoginOtpMail($user, $otp));
        } catch (\Throwable $e) {
            Log::warning('Could not dispatch Admin OTP email: ' . $e->getMessage());
        }

        Log::info("Admin Login OTP resent for {$user->email}");

        return back()->with('success', 'নতুন ওটিপি কোড আপনার ইমেইলে পাঠানো হয়েছে!');
    }

    /**
     * Cancel the OTP verification and return to login.
     */
    public function cancelOtp()
    {
        session()->forget(['admin_otp_user_id', 'admin_otp_remember', 'admin_otp_sent_at']);
        return redirect()->route('admin.login')->with('info', 'লগইন প্রক্রিয়া বাতিল করা হয়েছে।');
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
