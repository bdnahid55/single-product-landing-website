<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show settings configuration form.
     */
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings in storage.
     */
    public function update(Request $request)
    {
        // Handle Image Uploads
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
                $path = $request->file($fileKey)->store('uploads', 'public');
                Setting::set($settingKey, 'storage/' . $path, 'content');

                if ($fileKey === 'site_favicon_file') {
                    @copy(storage_path('app/public/' . $path), public_path('favicon.ico'));
                    @copy(storage_path('app/public/' . $path), public_path('assets/images/favicon.png'));
                }
            }
        }

        // Exclude system and file tokens
        $ignoreFields = array_merge(array_keys($fileInputs), ['_token', '_method']);

        foreach ($request->except($ignoreFields) as $key => $value) {
            Setting::set($key, $value !== null ? trim($value) : '');
        }

        return back()->with('success', 'Settings updated successfully! Blank items will be hidden on the website.');
    }
}
