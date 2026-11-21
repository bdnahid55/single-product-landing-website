<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Define the registry of all landing page sections.
     */
    public static function getSectionsRegistry(): array
    {
        return [
            [
                'key'         => 'section_hero_show',
                'title_bn'    => 'হিরো ব্যানার ও মূল অফার (Hero Banner)',
                'title_en'    => 'Hero Banner & Visuals',
                'description' => 'ওয়েবসাইটের সর্বপ্রথমে বড় ব্যানার, মধুর প্রধান ছবি, অর্ডার ও যোগাযোগ বাটন এবং ৩টি পরিসংখ্যান কার্ড।',
                'icon'        => 'fas fa-desktop text-success',
                'badge'       => 'হেডার ব্যানার',
                'edit_url'    => route('admin.settings.index') . '#hero-pane',
                'edit_label'  => 'হিরো সেটিংস সম্পাদনা',
                'has_colors'  => false, // Explicitly Excluded as requested!
            ],
            [
                'key'           => 'section_problem_show',
                'title_bn'      => 'সমস্যা ও আমাদের সমাধান (Problem & Solution)',
                'title_en'      => 'Problem & Solution Comparison',
                'description'   => 'বাজারের ভেজাল মধুর সমস্যা এবং আমাদের সুন্দরবনের খাঁটি মধুর গ্যারান্টিযুক্ত সমাধান তালিকা।',
                'icon'          => 'fas fa-exclamation-triangle text-danger',
                'badge'         => 'সমস্যা ও সমাধান',
                'edit_url'      => route('admin.settings.index') . '#problem-pane',
                'edit_label'    => 'সমস্যা/সমাধান সম্পাদনা',
                'has_colors'    => true,
                'bg_key'        => 'section_problem_bg',
                'color_key'     => 'section_problem_color',
                'default_bg'    => '#e2f1e6',
                'default_color' => '#111827',
                'selector'      => '#problemSolution',
            ],
            [
                'key'           => 'section_why_us_show',
                'title_bn'      => 'কেন আমাদের মধু সেরা (Why Choose Us)',
                'title_en'      => 'Why Choose Us (6 Feature Cards)',
                'description'   => '১০০% খাঁটি, প্রাকৃতিক সুবাস, দ্রুত ডেলিভারি ও রিটার্ন গ্যারান্টি ইত্যাদি ৬টি ফিচার কার্ড।',
                'icon'          => 'fas fa-award text-info',
                'badge'         => 'কেন আমরা',
                'edit_url'      => route('admin.settings.index') . '#why-pane',
                'edit_label'    => 'ফিচার কার্ড সম্পাদনা',
                'has_colors'    => true,
                'bg_key'        => 'section_why_us_bg',
                'color_key'     => 'section_why_us_color',
                'default_bg'    => '#e2f1e6',
                'default_color' => '#111827',
                'selector'      => '#whyUs',
            ],
            [
                'key'           => 'section_gallery_show',
                'title_bn'      => 'ফটো গ্যালারি (Photo Gallery)',
                'title_en'      => 'Photo Gallery & Sundarban Shots',
                'description'   => 'সুন্দরবনের গহীন বন থেকে মৌয়ালদের মধু সংগ্রহের ৪টি প্রাকৃতিক ছবির গ্যালারি।',
                'icon'          => 'fas fa-images text-purple',
                'badge'         => 'গ্যালারি',
                'edit_url'      => route('admin.settings.index') . '#gallery-pane',
                'edit_label'    => 'গ্যালারি ছবি সম্পাদনা',
                'has_colors'    => true,
                'bg_key'        => 'section_gallery_bg',
                'color_key'     => 'section_gallery_color',
                'default_bg'    => '#ffffff',
                'default_color' => '#111827',
                'selector'      => '#gallery',
            ],
            [
                'key'           => 'section_products_show',
                'title_bn'      => 'সেরা মধু প্যাকেজসমূহ (Products Section)',
                'title_en'      => 'Featured Product & Packages Grid',
                'description'   => 'ফিচার্ড প্রোডাক্ট ব্যানার এবং সকল সক্রিয় মধু প্যাকেজের তালিকা। মাল্টি-প্রোডাক্ট মোডে গ্রিড অন/অফ করতে প্রোডাক্টস পেজ ব্যবহার করুন।',
                'icon'          => 'fas fa-box text-warning',
                'badge'         => 'পণ্যসমূহ',
                'edit_url'      => route('admin.products.index'),
                'edit_label'    => 'পণ্য তালিকা ও গ্রিড মোড পরিচালনা',
                'has_colors'    => true,
                'bg_key'        => 'section_products_bg',
                'color_key'     => 'section_products_color',
                'default_bg'    => '#f4f8f5',
                'default_color' => '#111827',
                'selector'      => '#featuredProduct',
            ],
            [
                'key'           => 'section_benefits_show',
                'title_bn'      => 'মধুর স্বাস্থ্য উপকারিতা (Honey Benefits)',
                'title_en'      => 'Health Benefits (6 Health Cards)',
                'description'   => 'রোগ প্রতিরোধ, সর্দি-কাশি নিরাময়, তাৎক্ষণিক শক্তি ও ত্বকের যত্ন সহ ৬টি উপকারিতা কার্ড।',
                'icon'          => 'fas fa-heartbeat text-success',
                'badge'         => 'উপকারিতা',
                'edit_url'      => route('admin.settings.index') . '#benefits-pane',
                'edit_label'    => 'উপকারিতা সম্পাদনা',
                'has_colors'    => true,
                'bg_key'        => 'section_benefits_bg',
                'color_key'     => 'section_benefits_color',
                'default_bg'    => '#0f2e1e',
                'default_color' => '#ffffff',
                'selector'      => '#benefits',
            ],
            [
                'key'           => 'section_video_show',
                'title_bn'      => 'মধু সংগ্রহের ভিডিও (Video Showcase)',
                'title_en'      => 'Video Showcase & Collector Story',
                'description'   => 'মধু সংগ্রহের লাইভ ইউটিউব ভিডিও প্লেয়ার ও বিবরণী কার্ড।',
                'icon'          => 'fas fa-play-circle text-danger',
                'badge'         => 'ভিডিও',
                'edit_url'      => route('admin.settings.index') . '#video-pane',
                'edit_label'    => 'ভিডিও সেটিংস সম্পাদনা',
                'has_colors'    => true,
                'bg_key'        => 'section_video_bg',
                'color_key'     => 'section_video_color',
                'default_bg'    => '#ffffff',
                'default_color' => '#111827',
                'selector'      => '#video',
            ],
            [
                'key'           => 'section_reviews_show',
                'title_bn'      => 'গ্রাহকদের মতামত ও রেটিং (Customer Reviews)',
                'title_en'      => 'Customer Testimonials & Reviews',
                'description'   => 'সন্তুষ্ট গ্রাহকদের স্টার রেটিং, নাম, ঠিকানা ও বাস্তব অভিজ্ঞতার রিভিউ কার্ডসমূহ।',
                'icon'          => 'fas fa-star text-warning',
                'badge'         => 'রিভিউ',
                'edit_url'      => route('admin.reviews.index'),
                'edit_label'    => 'রিভিউ পরিচালনা',
                'has_colors'    => true,
                'bg_key'        => 'section_reviews_bg',
                'color_key'     => 'section_reviews_color',
                'default_bg'    => '#f8fafc',
                'default_color' => '#111827',
                'selector'      => '#reviews',
            ],
            [
                'key'           => 'section_discount_show',
                'title_bn'      => 'বিশেষ ডিসকাউন্ট ব্যানার (Discount Banner)',
                'title_en'      => 'Discount & Delivery Offer Banner',
                'description'   => 'আজকের বিশেষ ছাড় ও ঢাকা/ঢাকার বাইরের ডেলিভারি সুবিধার হাইলাইট অফার ব্যানার।',
                'icon'          => 'fas fa-tags text-primary',
                'badge'         => 'অফার ব্যানার',
                'edit_url'      => route('admin.settings.index') . '#shipping-pane',
                'edit_label'    => 'ডেলিভারি অফার সম্পাদনা',
                'has_colors'    => true,
                'bg_key'        => 'section_discount_bg',
                'color_key'     => 'section_discount_color',
                'default_bg'    => '#ffffff',
                'default_color' => '#111827',
                'selector'      => '#discount',
            ],
            [
                'key'           => 'section_order_form_show',
                'title_bn'      => 'সরাসরি অর্ডার ফর্ম (Direct Order Form)',
                'title_en'      => 'Interactive Checkout Order Form',
                'description'   => 'পণ্য পরিমাণ পরিবর্তন, ডেলিভারি এরিয়া, নাম, ফোন ও সম্পূর্ণ ঠিকানার টেক্সটএরিয়া সহ সরাসরি অর্ডার ফর্ম।',
                'icon'          => 'fas fa-shopping-cart text-success',
                'badge'         => 'অর্ডার ফর্ম',
                'edit_url'      => route('admin.settings.index') . '#shipping-pane',
                'edit_label'    => 'ডেলিভারি চার্জ সম্পাদনা',
                'has_colors'    => true,
                'bg_key'        => 'section_order_form_bg',
                'color_key'     => 'section_order_form_color',
                'default_bg'    => '#ffffff',
                'default_color' => '#111827',
                'selector'      => '#orderForm',
            ],
            [
                'key'           => 'section_footer_show',
                'title_bn'      => 'ফুটার সেকশন ও যোগাযোগ (Footer Section)',
                'title_en'      => 'Footer & Contact Details',
                'description'   => 'ব্র্যান্ড পরিচিতি, ওয়েবসাইট পেজ লিঙ্ক, ফোন, ইমেইল ও সোশ্যাল পেজের লিংক।',
                'icon'          => 'fas fa-shoe-prints text-secondary',
                'badge'         => 'ফুটার',
                'edit_url'      => route('admin.settings.index') . '#contact-pane',
                'edit_label'    => 'যোগাযোগ তথ্য সম্পাদনা',
                'has_colors'    => true,
                'bg_key'        => 'section_footer_bg',
                'color_key'     => 'section_footer_color',
                'default_bg'    => '#0f2e1e',
                'default_color' => '#d1fae5',
                'selector'      => '#footer',
            ],
            [
                'key'         => 'section_whatsapp_widget_show',
                'title_bn'    => 'ভাসমান হোয়াটসঅ্যাপ বাটন (Floating WhatsApp)',
                'title_en'    => 'Floating WhatsApp Quick Chat',
                'description' => 'স্ক্রিনের নিচের ডান কোণে গ্রাহকের সুবিধার্থে ভাসমান দ্রুত WhatsApp যোগাযোগ বাটন।',
                'icon'        => 'fab fa-whatsapp text-success',
                'badge'       => 'হোয়াটসঅ্যাপ বাটন',
                'edit_url'    => route('admin.settings.index') . '#contact-pane',
                'edit_label'  => 'হোয়াটসঅ্যাপ নম্বর সম্পাদনা',
                'has_colors'  => false,
            ],
        ];
    }

    /**
     * Display Section Controls Management page.
     */
    public function index()
    {
        $settings = Setting::getAllSettings();
        $sections = self::getSectionsRegistry();

        return view('admin.sections.index', compact('settings', 'sections'));
    }

    /**
     * Update section visibility status and color styles.
     */
    public function update(Request $request)
    {
        $sections = self::getSectionsRegistry();

        $validationRules = [];
        foreach ($sections as $sec) {
            $validationRules[$sec['key']] = 'nullable|in:0,1';
            if (!empty($sec['has_colors'])) {
                if (!empty($sec['bg_key'])) {
                    $validationRules[$sec['bg_key']] = ['nullable', 'string', 'max:20', 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'];
                }
                if (!empty($sec['color_key'])) {
                    $validationRules[$sec['color_key']] = ['nullable', 'string', 'max:20', 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'];
                }
            }
        }
        $request->validate($validationRules, [
            '*.regex' => 'কালার কোডটি অবশ্যই বৈধ হেক্সাডেসিমাল কোড হতে হবে (যেমন: #ffffff বা #11827)।',
        ]);

        foreach ($sections as $sec) {
            $key = $sec['key'];
            // If request has value, use it; otherwise default to '0' if explicitly submitted through form
            $value = $request->input($key, '0');
            Setting::set($key, $value === '1' ? '1' : '0', 'section_control');

            if (!empty($sec['has_colors'])) {
                if (!empty($sec['bg_key']) && $request->has($sec['bg_key'])) {
                    Setting::set($sec['bg_key'], trim($request->input($sec['bg_key'])), 'section_style');
                }
                if (!empty($sec['color_key']) && $request->has($sec['color_key'])) {
                    Setting::set($sec['color_key'], trim($request->input($sec['color_key'])), 'section_style');
                }
            }
        }

        return redirect()->route('admin.sections.index')->with('success', 'ওয়েবসাইটের সেকশন কন্ট্রোল ও কালার স্টাইল সফলভাবে সংরক্ষণ করা হয়েছে!');
    }
}
