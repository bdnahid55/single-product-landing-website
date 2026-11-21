<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        date_default_timezone_set(config('app.timezone', 'Asia/Dhaka'));

        Schema::defaultStringLength(191);

        // Dynamically load SMTP / Mail credentials from database
        \App\Services\MailConfigService::apply();

        // Implicitly grant "Super Admin" role all permissions
        Gate::before(function ($user, $ability) {
            return method_exists($user, 'hasRole') && $user->hasRole('Super Admin') ? true : null;
        });

        // 1. Rate limiter for customer checkout (Max 5 attempts per minute per IP)
        RateLimiter::for('orders', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function (Request $request, array $headers) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'অতিরিক্ত অর্ডার অনুরোধ শনাক্ত করা হয়েছে। অনুগ্রহ করে ১ মিনিট পর পুনরায় চেষ্টা করুন।',
                    ], 429, $headers);
                }

                return back()
                    ->withInput()
                    ->with('error', 'অতিরিক্ত অর্ডার অনুরোধ শনাক্ত করা হয়েছে। অনুগ্রহ করে ১ মিনিট পর পুনরায় চেষ্টা করুন।');
            });
        });

        // 2. Rate limiter for order tracking (Max 30 attempts per minute per IP)
        RateLimiter::for('order-tracking', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip())->response(function (Request $request, array $headers) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'অতিরিক্ত অনুরোধ শনাক্ত করা হয়েছে। অনুগ্রহ করে কিছুক্ষণ অপেক্ষা করুন।',
                    ], 429, $headers);
                }

                return back()
                    ->withInput()
                    ->with('error', 'অতিরিক্ত অনুসন্ধান অনুরোধ শনাক্ত করা হয়েছে। অনুগ্রহ করে কিছুক্ষণ পর পুনরায় চেষ্টা করুন।');
            });
        });
    }
}
