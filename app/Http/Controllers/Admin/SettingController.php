<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Show settings configuration form.
     */
    public function index()
    {
        $settings = Setting::getAllSettings();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings in storage following modern best practices.
     */
    public function update(Request $request)
    {
        // 1. Normalize Tracking & Analytics inputs (extract clean IDs if full script tags were pasted)
        $normalizedTracking = $this->normalizeTrackingInputs($request);
        $request->merge($normalizedTracking);

        // 2. Validate tracking fields strictly to prevent syntax errors and stored XSS vulnerabilities
        $request->validate([
            'google_site_verification' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_\-]+$/'],
            'google_analytics_id'      => ['nullable', 'string', 'max:50', 'regex:/^(G-[A-Za-z0-9]+|UA-[0-9]+-[0-9]+)$/'],
            'facebook_pixel_id'        => ['nullable', 'string', 'max:30', 'regex:/^[0-9]{5,25}$/'],
        ], [
            'google_site_verification.regex' => 'গুগল সার্চ কনসোল ভেরিফিকেশন কোডে শুধু বৈধ টোকেন ক্যারেক্টার (বর্ণ, সংখ্যা, ড্যাশ বা আন্ডারস্কোর) থাকতে পারবে।',
            'google_analytics_id.regex'      => 'গুগল অ্যানালিটিক্স ৪ আইডি অবশ্যই সঠিক ফরম্যাটে (যেমন: G-XXXXXXXXXX) হতে হবে।',
            'facebook_pixel_id.regex'        => 'ফেসবুক পিক্সেল আইডি অবশ্যই শুধুমাত্র সংখ্যার (যেমন: 123456789012345) হতে হবে।',
        ]);

        // 3. Handle Image Uploads
        $fileInputs = [
            'site_favicon_file'        => 'site_favicon',
            'og_image_file'            => 'og_image',
            'hero_bg_image_file'       => 'hero_bg_image',
            'hero_product_image_file'  => 'hero_product_image',
            'gallery_img1_file'        => 'gallery_img1',
            'gallery_img2_file'        => 'gallery_img2',
            'gallery_img3_file'        => 'gallery_img3',
            'gallery_img4_file'        => 'gallery_img4',
            'video_poster_file'        => 'video_poster_image',
        ];

        foreach ($fileInputs as $fileKey => $settingKey) {
            if ($request->hasFile($fileKey)) {
                $oldPath = Setting::get($settingKey);

                $path = $request->file($fileKey)->store('uploads', 'public');
                Setting::set($settingKey, 'storage/' . $path, 'content');

                if ($fileKey === 'site_favicon_file') {
                    @copy(storage_path('app/public/' . $path), public_path('favicon.ico'));
                    @copy(storage_path('app/public/' . $path), public_path('assets/images/favicon.png'));
                }

                // Delete previous uploaded file from disk (guarding default assets)
                if ($oldPath && str_starts_with($oldPath, 'storage/')) {
                    $relativeDiskPath = substr($oldPath, strlen('storage/'));
                    if (Storage::disk('public')->exists($relativeDiskPath)) {
                        Storage::disk('public')->delete($relativeDiskPath);
                    }
                }
            }
        }

        // 4. Save Webmaster & Analytics Tracking in database with dedicated 'analytics' group
        $trackingKeys = ['google_site_verification', 'google_analytics_id', 'facebook_pixel_id'];
        foreach ($trackingKeys as $tKey) {
            Setting::set($tKey, $normalizedTracking[$tKey] ?? '', 'analytics');
        }

        // 5. Exclude system tokens, file inputs, and tracking fields from generic update loop
        $ignoreFields = array_merge(array_keys($fileInputs), $trackingKeys, ['_token', '_method']);

        foreach ($request->except($ignoreFields) as $key => $value) {
            Setting::set($key, $value !== null ? trim($value) : '');
        }

        // 6. Invalidate application settings cache & reload mail config
        Setting::clearCache();
        \App\Services\MailConfigService::apply();

        return back()->with('success', 'Settings updated successfully! Blank items will be hidden on the website.');
    }

    /**
     * Send a test email using currently configured SMTP credentials.
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ], [
            'test_email.required' => 'অনুগ্রহ করে টেস্ট ইমেইল ঠিকানা প্রদান করুন।',
            'test_email.email'    => 'একটি বৈধ ইমেইল ঠিকানা প্রদান করুন।',
        ]);

        try {
            \App\Services\MailConfigService::apply();

            $to = trim($request->input('test_email'));
            $siteTitle = Setting::get('site_title', 'মধু স্টোর');

            \Illuminate\Support\Facades\Mail::raw(
                "অভিনন্দন! আপনার SMTP / Mail কনফিগারেশন ডাটাবেজ থেকে সফলভাবে কাজ করছে।\n\nসাইটের নাম: {$siteTitle}\nসময়: " . now()->format('d M, Y h:i A') . "\nপ্রেরক: " . config('mail.from.address'),
                function ($message) use ($to, $siteTitle) {
                    $message->to($to)
                            ->subject("SMTP Test Mail - {$siteTitle}");
                }
            );

            return back()->with('success', "টেস্ট ইমেইল সফলভাবে পাঠানো হয়েছে: {$to}। আপনার ইনবক্স অথবা স্প্যাম ফোল্ডার চেক করুন।");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("SMTP Test Email Error: " . $e->getMessage());
            return back()->withErrors(['smtp_error' => "ইমেইল পাঠানো ব্যর্থ হয়েছে! ত্রুটির বিবরণ: " . $e->getMessage()]);
        }
    }

    /**
     * Normalize & extract clean IDs if user pasted full script snippets.
     */
    protected function normalizeTrackingInputs(Request $request): array
    {
        $gVerification = $request->input('google_site_verification');
        $gaId = $request->input('google_analytics_id');
        $pixelId = $request->input('facebook_pixel_id');

        // Extract Google Search Console token
        if (!empty($gVerification)) {
            $raw = trim($gVerification);
            if (preg_match('/content=[\'"]([^\'"]+)[\'"]/i', $raw, $matches)) {
                $gVerification = $matches[1];
            }
            $gVerification = trim(strip_tags($gVerification), " \t\n\r\0\x0B\"'\\");
        } else {
            $gVerification = '';
        }

        // Extract GA4 or UA ID
        if (!empty($gaId)) {
            $raw = trim($gaId);
            if (preg_match('/(G-[A-Za-z0-9]+|UA-[0-9]+-[0-9]+)/i', $raw, $matches)) {
                $gaId = strtoupper($matches[1]);
            } else {
                $gaId = strtoupper(trim(strip_tags($raw)));
            }
        } else {
            $gaId = '';
        }

        // Extract Facebook Pixel numeric ID
        if (!empty($pixelId)) {
            $raw = trim($pixelId);
            if (preg_match('/(?:init)?[^\d]*(\d{5,25})/i', $raw, $matches)) {
                $pixelId = $matches[1];
            } else {
                $pixelId = $raw;
            }
        } else {
            $pixelId = '';
        }

        return [
            'google_site_verification' => $gVerification,
            'google_analytics_id'      => $gaId,
            'facebook_pixel_id'        => $pixelId,
        ];
    }
}
