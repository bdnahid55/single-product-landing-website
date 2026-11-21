<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\SSLCommerzPaymentController;

/*
|--------------------------------------------------------------------------
| Frontend Customer Routes & SEO
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/orders', [OrderController::class, 'store'])
    ->middleware('throttle:orders')
    ->name('orders.store');
Route::get('/orders/{id}/success', [OrderController::class, 'success'])->name('orders.success');
Route::get('/orders/{order}/pay', [SSLCommerzPaymentController::class, 'initiate'])
    ->middleware('throttle:15,1')
    ->name('orders.pay');
Route::get('/orders/{order}/payment-status', [SSLCommerzPaymentController::class, 'paymentStatus'])->name('orders.payment_status');
Route::post('/orders/{order}/convert-to-cod', [SSLCommerzPaymentController::class, 'convertToCod'])
    ->middleware('throttle:15,1')
    ->name('orders.convert_to_cod');
Route::match(['get', 'post'], '/track-order', [OrderController::class, 'track'])
    ->middleware('throttle:order-tracking')
    ->name('order.track');

// SSLCommerz Payment Gateway Callbacks
Route::match(['get', 'post'], '/sslcommerz/success', [SSLCommerzPaymentController::class, 'success'])->name('sslcommerz.success');
Route::match(['get', 'post'], '/sslcommerz/fail', [SSLCommerzPaymentController::class, 'fail'])->name('sslcommerz.fail');
Route::match(['get', 'post'], '/sslcommerz/cancel', [SSLCommerzPaymentController::class, 'cancel'])->name('sslcommerz.cancel');
Route::match(['get', 'post'], '/sslcommerz/ipn', [SSLCommerzPaymentController::class, 'ipn'])->name('sslcommerz.ipn');

Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.show');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');
    Route::get('/cancel-otp', [AuthController::class, 'cancelOtp'])->name('otp.cancel');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Back-Office Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        $user = auth()->user();
        if ($user->can('view-dashboard')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->can('manage-orders')) {
            return redirect()->route('admin.orders.index');
        }
        if ($user->can('manage-products')) {
            return redirect()->route('admin.products.index');
        }
        if ($user->can('manage-reviews')) {
            return redirect()->route('admin.reviews.index');
        }
        if ($user->can('manage-sections')) {
            return redirect()->route('admin.sections.index');
        }
        if ($user->can('manage-settings')) {
            return redirect()->route('admin.settings.index');
        }
        if ($user->can('manage-admins')) {
            return redirect()->route('admin.users.index');
        }
        if ($user->can('manage-roles')) {
            return redirect()->route('admin.roles.index');
        }

        abort(403, 'আপনার কোনো প্রশাসনিক সেকশন ব্যবহারের অনুমতি নেই।');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('can:view-dashboard');

    // Products Management
    Route::middleware('can:manage-products')->group(function () {
        Route::put('/products-mode', [ProductController::class, 'updateMode'])->name('products.update-mode');
        Route::patch('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::resource('products', ProductController::class);
    });

    // Orders Management
    Route::middleware('can:manage-orders')->group(function () {
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->middleware('can:update-order-status')
            ->name('orders.update-status');
        Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])
            ->name('orders.destroy');
        Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
    });

    // Customer Reviews Management
    Route::middleware('can:manage-reviews')->group(function () {
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::patch('/reviews/{review}/toggle', [ReviewController::class, 'toggleApproval'])->name('reviews.toggle');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });

    // Landing Page Section Visibility Controls
    Route::middleware('can:manage-sections')->group(function () {
        Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
        Route::put('/sections', [SectionController::class, 'update'])->name('sections.update');
    });

    // Site & SEO Settings Management
    Route::middleware('can:manage-settings')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/send-test-email', [SettingController::class, 'sendTestEmail'])->name('settings.send-test-email');
    });

    // Admin Accounts Management
    Route::middleware('can:manage-admins')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Roles & Permissions Management
    Route::middleware('can:manage-roles')->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
    });

    // Automated Backups Management
    Route::middleware('can:manage-settings')->group(function () {
        Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups', [BackupController::class, 'store'])->name('backups.store');
        Route::get('/backups/{fileName}/download', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('/backups/{fileName}', [BackupController::class, 'destroy'])->name('backups.destroy');
    });
});
