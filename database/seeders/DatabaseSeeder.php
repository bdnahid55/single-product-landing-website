<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Seed Spatie Roles & Permissions
        $permissions = [
            'view-dashboard',
            'manage-orders',
            'manage-products',
            'manage-reviews',
            'manage-sections',
            'manage-settings',
            'manage-admins',
            'manage-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superAdminRole = Role::findOrCreate('Super Admin', 'web');
        $superAdminRole->syncPermissions($permissions);

        $managerRole = Role::findOrCreate('Manager', 'web');
        $managerRole->syncPermissions([
            'view-dashboard',
            'manage-orders',
            'manage-products',
            'manage-reviews',
            'manage-sections',
        ]);

        $editorRole = Role::findOrCreate('Editor', 'web');
        $editorRole->syncPermissions([
            'view-dashboard',
            'manage-products',
            'manage-reviews',
        ]);

        // 1. Create Default Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'phone' => '01700000000',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );
        $admin->syncRoles(['Super Admin']);

        // 2. Create Initial Honey Products
        $products = [
            [
                'name' => 'খাঁটি সুন্দরবনের মধু (১ কেজি)',
                'slug' => 'sundarban-pure-honey-1kg',
                'weight' => '১ কেজি',
                'regular_price' => 1200.00,
                'sale_price' => 1050.00,
                'stock' => 150,
                'badge' => 'হট ডিল',
                'image' => 'assets/images/featured-honey-bottle.png',
                'tags' => '১০০% খাঁটি, সুগন্ধি, পুষ্টিকর',
                'short_description' => 'সুন্দরবনের খলিসা ফুলের প্রাকৃতিক খাঁটি মধু। কোনোরূপ চিনি বা ভেজাল মিশ্রণ নেই।',
                'description' => 'আমাদের মধু সরাসরি সুন্দরবনের মৌয়ালদের মাধ্যমে সংগৃহীত হয়। এটি প্রক্রিয়াজাতকরণ ছাড়া সম্পূর্ণ প্রাকৃতিক উপায়ে ফিল্টার করে বোতলজাত করা হয়।',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'সুন্দরবনের খলিসা ফুলের মধু (৫০০ গ্রাম)',
                'slug' => 'khalisa-flower-honey-500g',
                'weight' => '৫০০ গ্রাম',
                'regular_price' => 650.00,
                'sale_price' => 550.00,
                'stock' => 80,
                'badge' => 'জনপ্রিয়',
                'image' => 'assets/images/hero-honey-jar.png',
                'tags' => 'প্রাকৃতিক, সুস্বাদু',
                'short_description' => 'প্রথম মৌসুমের তাজা খলিসা ফুলের মধু। হালকা মিষ্টি ও অতুলনীয় সুবাস।',
                'description' => 'মধুর রঙ হালকা অ্যাম্বার এবং অত্যন্ত সুস্বাদু। বাচ্চাদের পুষ্টি ও রোগ প্রতিরোধ ক্ষমতা বৃদ্ধিতে অত্যন্ত কার্যকর।',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'সুন্দরবনের গরান ও কেওড়া ফুলের মধু (১ কেজি)',
                'slug' => 'goran-keora-flower-honey-1kg',
                'weight' => '১ কেজি',
                'regular_price' => 1100.00,
                'sale_price' => 950.00,
                'stock' => 120,
                'badge' => 'সেরা অফার',
                'image' => 'assets/images/gallery-main.jpg',
                'tags' => 'গহীন বনের খাঁটি মধু',
                'short_description' => 'সুন্দরবনের গভীর অরণ্যের বিভিন্ন বুনো ফুলের খাঁটি মিশ্র মধু।',
                'description' => 'এই মধুতে রয়েছে প্রচুর অ্যান্টিঅক্সিডেন্ট ও ঔষধি গুণাবলি যা প্রতিদিনের ক্লান্তি দূর করে।',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'প্রিমিয়াম ফ্যামিলি প্যাক (২ কেজি মধু + বিশেষ উপহার)',
                'slug' => 'premium-family-pack-2kg',
                'weight' => '২ কেজি',
                'regular_price' => 2400.00,
                'sale_price' => 1990.00,
                'stock' => 50,
                'badge' => 'মেগা অফার',
                'image' => 'assets/images/hero-honey-jar.png',
                'tags' => 'ফ্যামিলি প্যাক, ফ্রি ডেলিভারি অফার',
                'short_description' => 'পরিবারের সুস্বাস্থ্যের জন্য ২ কেজির স্পেশাল কম্বো প্যাক।',
                'description' => 'একসাথে ২ কেজি খাঁটি মধু কিনুন এবং পান বিশেষ ছাড়। স্টক সীমিত!',
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(['slug' => $prod['slug']], $prod);
        }

        // 3. Create General & SEO Settings
        $settings = [
            ['key' => 'site_title', 'value' => 'খাঁটি সুন্দরবনের মধু — প্রকৃতির আসল উপহার', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'সুন্দরবনের গহীন বন থেকে সরাসরি আপনার ঘরে — ১০০% খাঁটি ও প্রাকৃতিক মধু।', 'group' => 'general'],
            ['key' => 'contact_phone', 'value' => '01700-000000', 'group' => 'contact'],
            ['key' => 'whatsapp_number', 'value' => '01700000000', 'group' => 'contact'],
            ['key' => 'contact_email', 'value' => 'info@modhu.com', 'group' => 'contact'],
            ['key' => 'facebook_page_url', 'value' => 'https://facebook.com/flavortee', 'group' => 'contact'],
            ['key' => 'delivery_inside_dhaka', 'value' => '80', 'group' => 'shipping'],
            ['key' => 'delivery_outside_dhaka', 'value' => '130', 'group' => 'shipping'],
            ['key' => 'free_shipping_threshold', 'value' => '3000', 'group' => 'shipping'],
            ['key' => 'promo_headline', 'value' => 'আজকের স্পেশাল ধামাকা অফার!', 'group' => 'promo'],

            // SEO Meta Settings
            ['key' => 'meta_title', 'value' => 'খাঁটি সুন্দরবনের মধু | ১০০% প্রাকৃতিক ও ভেজালমুক্ত মধু', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'সুন্দরবনের গহীন বন থেকে মৌয়ালদের সংগৃহীত ১০০% খাঁটি ও প্রাকৃতিক খলিসা ও গরান ফুলের মধু। ক্যাশ অন ডেলিভারিতে সারা বাংলাদেশে দ্রুত হোম ডেলিভারি।', 'group' => 'seo'],
            ['key' => 'meta_keywords', 'value' => 'খাঁটি মধু, সুন্দরবনের মধু, প্রাকৃতিক মধু, মধুর উপকারিতা, মধু চেনার উপায়, honey bangladesh, pure honey online', 'group' => 'seo'],
            ['key' => 'meta_author', 'value' => 'খাঁটি সুন্দরবনের মধু', 'group' => 'seo'],
            ['key' => 'og_image', 'value' => 'assets/images/hero-honey-jar.png', 'group' => 'seo'],
            ['key' => 'canonical_url', 'value' => '', 'group' => 'seo'],
            ['key' => 'google_site_verification', 'value' => '', 'group' => 'seo'],
            ['key' => 'google_analytics_id', 'value' => '', 'group' => 'seo'],
            ['key' => 'facebook_pixel_id', 'value' => '', 'group' => 'seo'],

            // Dynamic Hero & Content
            ['key' => 'hero_badge', 'value' => 'সুন্দরবনের সেরা মধু', 'group' => 'content'],
            ['key' => 'hero_title_line1', 'value' => 'খাঁটি সুন্দরবনের মধু', 'group' => 'content'],
            ['key' => 'hero_title_line2', 'value' => '— প্রকৃতির আসল উপহার 🍯', 'group' => 'content'],
            ['key' => 'hero_order_btn_text', 'value' => 'এখনই অর্ডার করুন', 'group' => 'content'],
            ['key' => 'hero_contact_btn_text', 'value' => 'যোগাযোগ করুন', 'group' => 'content'],
            ['key' => 'hero_trust_badge1', 'value' => '১৫০০+ হ্যাপি কাস্টমার', 'group' => 'content'],
            ['key' => 'hero_trust_badge2', 'value' => 'সারা দেশে ডেলিভারি', 'group' => 'content'],
            ['key' => 'hero_trust_badge3', 'value' => 'ক্যাশ অন ডেলিভারি', 'group' => 'content'],
            ['key' => 'hero_bg_image', 'value' => 'assets/images/hero-bg.jpg', 'group' => 'content'],
            ['key' => 'hero_product_image', 'value' => 'assets/images/hero-honey-jar.png', 'group' => 'content'],
            ['key' => 'hero_stat1_num', 'value' => '১৫০০+', 'group' => 'content'],
            ['key' => 'hero_stat1_label', 'value' => 'সন্তুষ্ট গ্রাহক', 'group' => 'content'],
            ['key' => 'hero_stat2_num', 'value' => '১০০%', 'group' => 'content'],
            ['key' => 'hero_stat2_label', 'value' => 'খাঁটি ও প্রাকৃতিক', 'group' => 'content'],
            ['key' => 'hero_stat3_num', 'value' => '৫০+', 'group' => 'content'],
            ['key' => 'hero_stat3_label', 'value' => 'জেলায় ডেলিভারি', 'group' => 'content'],

            // Problem & Solution
            ['key' => 'problem_title', 'value' => 'সমস্যা ও সমাধান', 'group' => 'content'],
            ['key' => 'problem_subtitle', 'value' => 'আপনি কি সঠিক মধু পাচ্ছেন?', 'group' => 'content'],
            ['key' => 'problem_item1', 'value' => 'বাজারে ভেজাল ও চিনি মেশানো মধু পাওয়া যায়', 'group' => 'content'],
            ['key' => 'problem_item2', 'value' => 'ল্যাব টেস্ট ছাড়া আসল মধু চেনা অত্যন্ত কঠিন', 'group' => 'content'],
            ['key' => 'problem_item3', 'value' => 'স্বাদ ও ঔষধি গুণমান বেশিরভাগ সময় ঠিক থাকে না', 'group' => 'content'],
            ['key' => 'problem_item4', 'value' => 'অতিরিক্ত তাপে প্রক্রিয়াজাত করায় মধুর পুষ্টিগুণ নষ্ট হয়', 'group' => 'content'],
            ['key' => 'problem_item5', 'value' => 'অনলাইনে অর্ডার করে সঠিক পণ্য না পাওয়ার ভয় থাকে', 'group' => 'content'],
            ['key' => 'solution_item1', 'value' => '১০০% খাঁটি সুন্দরবনের প্রাকৃতিক মধু', 'group' => 'content'],
            ['key' => 'solution_item2', 'value' => 'কোনো রাসায়নিক, কৃত্রিম চিনি বা ফ্লেভার মিক্স নেই', 'group' => 'content'],
            ['key' => 'solution_item3', 'value' => 'বুনো ফুলের আসল ঘ্রাণ, প্রাকৃতিক ঘনত্ব ও দীর্ঘস্থায়ী পুষ্টি', 'group' => 'content'],
            ['key' => 'solution_item4', 'value' => 'সম্পূর্ণ কাঁচা ও প্রাকৃতিক (Raw Honey), পুষ্টিগুণ ১০০% অক্ষুণ্ণ', 'group' => 'content'],
            ['key' => 'solution_item5', 'value' => 'সারা দেশে ক্যাশ অন ডেলিভারি — হাতে পেয়ে চেক করে টাকা দিন', 'group' => 'content'],

            // Why Choose Us
            ['key' => 'why_title', 'value' => 'কেন আমাদের মধু সেরা?', 'group' => 'content'],
            ['key' => 'why_subtitle', 'value' => 'যে কারণে হাজারো মানুষ আমাদের বিশ্বাস করে', 'group' => 'content'],
            ['key' => 'why_feature1_title', 'value' => '১০০% খাঁটি ও কাঁচা মধু', 'group' => 'content'],
            ['key' => 'why_feature1_desc', 'value' => 'কোনোরূপ রাসায়নিক বা তাপ প্রক্রিয়াজাতকরণ ছাড়া সরাসরি সংগৃহীত', 'group' => 'content'],
            ['key' => 'why_feature2_title', 'value' => 'প্রাকৃতিক সুবাস ও স্বাদ', 'group' => 'content'],
            ['key' => 'why_feature2_desc', 'value' => 'খলিসা ও গরান ফুলের গভীর বনের প্রাকৃতিক খাঁটি অনন্য সুবাস', 'group' => 'content'],
            ['key' => 'why_feature3_title', 'value' => 'দ্রুত হোম ডেলিভারি', 'group' => 'content'],
            ['key' => 'why_feature3_desc', 'value' => 'সারা বাংলাদেশে দ্রুত ও নিরাপদ ক্যাশ অন ডেলিভারি সার্ভিস', 'group' => 'content'],
            ['key' => 'why_feature4_title', 'value' => 'হাতে পেয়ে মূল্য পরিশোধ', 'group' => 'content'],
            ['key' => 'why_feature4_desc', 'value' => 'পণ্য হাতে পেয়ে চেক করে টাকা দিন, কোনো অগ্রিম পেমেন্টের দরকার নেই', 'group' => 'content'],
            ['key' => 'why_feature5_title', 'value' => '১০০% রিটার্ন গ্যারান্টি', 'group' => 'content'],
            ['key' => 'why_feature5_desc', 'value' => 'পণ্যে যেকোনো সন্দেহ থাকলে ডেলিভারি ম্যানের সামনেই সাথে সাথে ফেরত দিন', 'group' => 'content'],
            ['key' => 'why_feature6_title', 'value' => '১৫০০+ সন্তুষ্ট গ্রাহক', 'group' => 'content'],
            ['key' => 'why_feature6_desc', 'value' => 'সারাদেশে হাজারো সন্তুষ্ট পরিবার আমাদের নিয়মিত গ্রাহক', 'group' => 'content'],

            // Gallery
            ['key' => 'gallery_title', 'value' => 'আমাদের গ্যালারি', 'group' => 'content'],
            ['key' => 'gallery_subtitle', 'value' => 'সুন্দরবন থেকে আপনার খাবার টেবিল পর্যন্ত', 'group' => 'content'],
            ['key' => 'gallery_img1', 'value' => 'assets/images/gallery-main.jpg', 'group' => 'content'],
            ['key' => 'gallery_img2', 'value' => 'assets/images/gallery-thumb-2.jpg', 'group' => 'content'],
            ['key' => 'gallery_img3', 'value' => 'assets/images/gallery-thumb-3.jpg', 'group' => 'content'],
            ['key' => 'gallery_img4', 'value' => 'assets/images/gallery-thumb-4.jpg', 'group' => 'content'],

            // Honey Benefits
            ['key' => 'benefits_title', 'value' => 'মধুর উপকারিতা', 'group' => 'content'],
            ['key' => 'benefits_subtitle', 'value' => 'প্রতিদিন এক চামচ খাঁটি মধু খাওয়ার অসাধারণ সব স্বাস্থ্য উপকারিতা', 'group' => 'content'],
            ['key' => 'benefit1_title', 'value' => 'রোগ প্রতিরোধ ক্ষমতা বাড়ায়', 'group' => 'content'],
            ['key' => 'benefit1_desc', 'value' => 'প্রচুর অ্যান্টিঅক্সিডেন্ট ও এনজাইম সমৃদ্ধ যা ইমিউন সিস্টেমকে শক্তিশালী রাখে', 'group' => 'content'],
            ['key' => 'benefit2_title', 'value' => 'হজমে সাহায্য করে', 'group' => 'content'],
            ['key' => 'benefit2_desc', 'value' => 'পেটের সমস্যা দূর করে, গ্যাস্ট্রিক প্রতিরোধে সাহায্য করে এবং স্বাভাবিক হজম বাড়ায়', 'group' => 'content'],
            ['key' => 'benefit3_title', 'value' => 'তাৎক্ষণিক শক্তি বৃদ্ধি করে', 'group' => 'content'],
            ['key' => 'benefit3_desc', 'value' => 'প্রাকৃতিক ফ্রুক্টোজ ও গ্লুকোজ সরাসরি রক্তের মাধ্যমে ক্লান্তি দূর করে এনার্জি যোগায়', 'group' => 'content'],
            ['key' => 'benefit4_title', 'value' => 'স্মৃতিশক্তি উন্নত করে', 'group' => 'content'],
            ['key' => 'benefit4_desc', 'value' => 'মস্তিষ্কের স্নায়ুকোষ সতেজ রাখে ও একাগ্রতা বৃদ্ধি করতে সাহায্য করে', 'group' => 'content'],
            ['key' => 'benefit5_title', 'value' => 'শিশুদের বৃদ্ধিতে সহায়ক', 'group' => 'content'],
            ['key' => 'benefit5_desc', 'value' => '১ বছরের বেশি বয়সী শিশুদের হাড়ের গঠন ও শারীরিক বিকাশে অত্যন্ত কার্যকরী', 'group' => 'content'],
            ['key' => 'benefit6_title', 'value' => 'ত্বক ও চুলের প্রাকৃতিক যত্ন', 'group' => 'content'],
            ['key' => 'benefit6_desc', 'value' => 'ত্বক উজ্জ্বল, ময়েশ্চারাইজড ও চুল সিল্কি করতে প্রাচীনকাল থেকেই বিশ্বস্ত', 'group' => 'content'],

            // Video Section
            ['key' => 'video_title', 'value' => 'দেখুন কিভাবে সংগ্রহ করা হয়', 'group' => 'content'],
            ['key' => 'video_subtitle', 'value' => 'সুন্দরবনের গহীন বন থেকে প্রাকৃতিকভাবে মধু সংগ্রহের দৃশ্য', 'group' => 'content'],
            ['key' => 'youtube_video_id', 'value' => 'M7lc1UVf-VE', 'group' => 'content'],
            ['key' => 'youtube_video_title', 'value' => 'সুন্দরবনের খাঁটি মধু সংগ্রহের ভিডিও', 'group' => 'content'],

            // Discount Section
            ['key' => 'discount_headline', 'value' => 'আজ অর্ডার করলে পাচ্ছেন বিশেষ ডিসকাউন্ট!', 'group' => 'content'],
            ['key' => 'discount_card1_title', 'value' => 'ঢাকায় ডেলিভারি', 'group' => 'content'],
            ['key' => 'discount_card1_desc', 'value' => 'ঢাকার ভিতরে দ্রুত হোম ডেলিভারি', 'group' => 'content'],
            ['key' => 'discount_card2_title', 'value' => 'ঢাকার বাইরে', 'group' => 'content'],
            ['key' => 'discount_card2_desc', 'value' => 'সারা দেশে দ্রুত ক্যাশ অন ডেলিভারি', 'group' => 'content'],
            ['key' => 'discount_card3_title', 'value' => 'ক্যাশ অন ডেলিভারি', 'group' => 'content'],
            ['key' => 'discount_card3_desc', 'value' => 'পণ্য হাতে পেয়ে চেক করে টাকা দিন', 'group' => 'content'],

            // Footer
            ['key' => 'footer_brand_name', 'value' => 'খাঁটি মধু', 'group' => 'content'],
            ['key' => 'footer_copyright_text', 'value' => 'সর্বস্বত্ব সংরক্ষিত।', 'group' => 'content'],

            // Landing Page Section Visibility Controls
            ['key' => 'section_hero_show', 'value' => '1', 'group' => 'section_control'],
            ['key' => 'section_problem_show', 'value' => '1', 'group' => 'section_control'],
            ['key' => 'section_why_us_show', 'value' => '1', 'group' => 'section_control'],
            ['key' => 'section_gallery_show', 'value' => '1', 'group' => 'section_control'],
            ['key' => 'section_products_show', 'value' => '1', 'group' => 'section_control'],
            ['key' => 'section_benefits_show', 'value' => '1', 'group' => 'section_control'],
            ['key' => 'section_video_show', 'value' => '1', 'group' => 'section_control'],
            ['key' => 'section_reviews_show', 'value' => '1', 'group' => 'section_control'],
            ['key' => 'section_discount_show', 'value' => '1', 'group' => 'section_control'],
            ['key' => 'section_order_form_show', 'value' => '1', 'group' => 'section_control'],
            ['key' => 'section_footer_show', 'value' => '1', 'group' => 'section_control'],
            ['key' => 'section_whatsapp_widget_show', 'value' => '1', 'group' => 'section_control'],

            // Selling Mode: 'multiple' (all packages + selector) vs 'single' (focused single product)
            ['key' => 'store_mode', 'value' => 'multiple', 'group' => 'store_mode'],
            ['key' => 'single_product_id', 'value' => '1', 'group' => 'store_mode'],
        ];

        foreach ($settings as $item) {
            Setting::updateOrCreate(['key' => $item['key']], $item);
        }

        // 4. Create Initial Customer Reviews
        $reviews = [
            [
                'customer_name' => 'মোঃ রফিকুল ইসলাম',
                'customer_location' => 'মিরপুর, ঢাকা',
                'rating' => 5,
                'review_text' => 'মধুটির ঘ্রাণ এবং স্বাদ অসাধারণ! খাঁটি মধুর আসল চেনা যায় এর সুবাসেই। ২ দিনের মধ্যেই হাতে পেয়েছি। ধন্যবাদ!',
                'avatar' => null,
                'is_approved' => true,
            ],
            [
                'customer_name' => 'তানজিলা রহমান',
                'customer_location' => 'চট্টগ্রাম',
                'rating' => 5,
                'review_text' => 'বাচ্চার জন্য নিয়েছিলাম, পানির সাথে মিশিয়ে সকালে খাওয়াই। চমৎকার কোয়ালিটি এবং প্যাকেজিং খুবই সুরক্ষিত ছিল।',
                'avatar' => null,
                'is_approved' => true,
            ],
            [
                'customer_name' => 'আব্দুল্লাহ আল মামুন',
                'customer_location' => 'সিলেট',
                'rating' => 5,
                'review_text' => 'ক্যাশ অন ডেলিভারিতে সুন্দরবন থেকে পাঠানো এই মধুটা আসলেই ১০০% নির্ভেজাল। আমি আবার অর্ডার করব ইনশাআল্লাহ।',
                'avatar' => null,
                'is_approved' => true,
            ],
        ];

        foreach ($reviews as $rev) {
            Review::create($rev);
        }

        // 5. Create Initial Sample Orders if none exist
        if (\App\Models\Order::count() == 0) {
            $mainProd = Product::first();

            $order1 = \App\Models\Order::create([
                'order_number'    => \App\Models\Order::generateOrderNumber(),
                'customer_name'   => 'তানভীর আহমেদ',
                'customer_phone'  => '01711223344',
                'customer_address'=> 'বাড়ি ১৫, রোড ৪, সেক্টর ৭, উত্তরা, ঢাকা',
                'delivery_area'   => 'inside_dhaka',
                'delivery_charge' => 80.00,
                'subtotal'        => 1050.00,
                'total_amount'    => 1130.00,
                'payment_method'  => 'cod',
                'payment_status'  => 'unpaid',
                'order_status'    => 'pending',
                'customer_notes'  => 'অফিস সময়ে ফোন দিয়ে আসবেন।',
            ]);

            \App\Models\OrderItem::create([
                'order_id'     => $order1->id,
                'product_id'   => $mainProd->id,
                'product_name' => $mainProd->name,
                'weight'       => $mainProd->weight,
                'unit_price'   => $mainProd->sale_price,
                'quantity'     => 1,
                'subtotal'     => 1050.00,
            ]);

            $order2 = \App\Models\Order::create([
                'order_number'    => \App\Models\Order::generateOrderNumber(),
                'customer_name'   => 'মাকসুদা আক্তার',
                'customer_phone'  => '01899887766',
                'customer_address'=> 'জিইসি মোড়, নাসিরাবাদ, চট্টগ্রাম',
                'delivery_area'   => 'outside_dhaka',
                'delivery_charge' => 130.00,
                'subtotal'        => 2100.00,
                'total_amount'    => 2230.00,
                'payment_method'  => 'cod',
                'payment_status'  => 'paid',
                'order_status'    => 'delivered',
                'customer_notes'  => 'প্যাকেজিং ভালো ছিল, ধন্যবাদ।',
            ]);

            \App\Models\OrderItem::create([
                'order_id'     => $order2->id,
                'product_id'   => $mainProd->id,
                'product_name' => $mainProd->name,
                'weight'       => $mainProd->weight,
                'unit_price'   => $mainProd->sale_price,
                'quantity'     => 2,
                'subtotal'     => 2100.00,
            ]);
        }
    }
}
