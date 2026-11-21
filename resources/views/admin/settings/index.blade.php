@extends('layouts.admin')

@section('title', 'Site & SEO Settings')
@section('page_title', 'Complete Website & Content Management')

@section('breadcrumb')
  <li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
<div class="row mb-3">
  <div class="col-12 d-flex justify-content-between align-items-center">
    <span class="text-muted small">
      <i class="fas fa-info-circle mr-1 text-primary"></i> এখান থেকে ওয়েবসাইটের প্রতিটি কনটেন্ট ও এসইও পরিবর্তন করতে পারবেন।
    </span>
    <a href="{{ route('admin.sections.index') }}" class="btn btn-warning btn-sm font-weight-bold shadow-sm">
      <i class="fas fa-sliders-h mr-1"></i> মাস্টার সেকশন অন/অফ কন্ট্রোল
    </a>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card card-success card-outline card-outline-tabs shadow-sm">
      <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="settingsTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active font-weight-bold" id="seo-tab" data-toggle="pill" href="#seo-pane" role="tab">
              <i class="fas fa-search mr-1 text-primary"></i> 1. SEO & Social Meta
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" id="hero-tab" data-toggle="pill" href="#hero-pane" role="tab">
              <i class="fas fa-desktop mr-1 text-success"></i> 2. Hero Banner & Visuals
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" id="problem-tab" data-toggle="pill" href="#problem-pane" role="tab">
              <i class="fas fa-check-circle mr-1 text-danger"></i> 3. Problem & Solution
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" id="why-tab" data-toggle="pill" href="#why-pane" role="tab">
              <i class="fas fa-award mr-1 text-info"></i> 4. Why Choose Us (6 Cards)
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" id="gallery-tab" data-toggle="pill" href="#gallery-pane" role="tab">
              <i class="fas fa-images mr-1 text-purple"></i> 5. Gallery Images
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" id="benefits-tab" data-toggle="pill" href="#benefits-pane" role="tab">
              <i class="fas fa-heartbeat mr-1 text-success"></i> 6. Honey Benefits (6 Cards)
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" id="video-tab" data-toggle="pill" href="#video-pane" role="tab">
              <i class="fas fa-play-circle mr-1 text-danger"></i> 7. Video & Promo
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" id="shipping-tab" data-toggle="pill" href="#shipping-pane" role="tab">
              <i class="fas fa-truck mr-1 text-warning"></i> 8. Shipping Rates
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" id="contact-tab" data-toggle="pill" href="#contact-pane" role="tab">
              <i class="fas fa-phone mr-1 text-info"></i> 9. Contacts & Footer
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" id="colors-tab" data-toggle="pill" href="#colors-pane" role="tab">
              <i class="fas fa-palette mr-1 text-success"></i> 10. Section Colors (কালার ও থিম)
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" id="mail-tab" data-toggle="pill" href="#mail-pane" role="tab">
              <i class="fas fa-envelope-open-text mr-1 text-danger"></i> 11. Mail / SMTP (ইমেইল ও ওটিপি সেটিংস)
            </a>
          </li>
        </ul>
      </div>

      <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="card-body">
          <div class="tab-content" id="settingsTabContent">
            
            <!-- 1. SEO & Social Metadata Tab -->
            <div class="tab-pane fade show active" id="seo-pane" role="tabpanel">
              <div class="callout callout-info py-2 mb-4">
                <h5><i class="fas fa-info-circle mr-1"></i> Search Engine & Social Optimization</h5>
                <p class="mb-0 text-muted">These meta tags control how your website appears on Google, Facebook, WhatsApp previews, and Twitter.</p>
              </div>

              <div class="form-group">
                <label for="meta_title">Primary Meta Title (SEO Title) <span class="text-danger">*</span></label>
                <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $settings['meta_title'] ?? '') }}" placeholder="e.g. খাঁটি সুন্দরবনের মধু | ১০০% প্রাকৃতিক ও ভেজালমুক্ত মধু">
                <small class="text-muted">Recommended: 50-60 characters. Appears as the clickable blue title in Google results.</small>
              </div>

              <div class="form-group">
                <label for="meta_description">Meta Description <span class="text-danger">*</span></label>
                <textarea name="meta_description" id="meta_description" rows="3" class="form-control" placeholder="Brief summary of your honey for search engines">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                <small class="text-muted">Recommended: 120-160 characters. Displayed under your title in Google and on social shares.</small>
              </div>

              <div class="form-group">
                <label for="meta_keywords">Meta Keywords (Comma separated)</label>
                <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="{{ old('meta_keywords', $settings['meta_keywords'] ?? '') }}" placeholder="খাঁটি মধু, সুন্দরবনের মধু, প্রাকৃতিক মধু, pure honey bangladesh">
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="meta_author">Meta Author / Brand Name</label>
                    <input type="text" name="meta_author" id="meta_author" class="form-control" value="{{ old('meta_author', $settings['meta_author'] ?? 'খাঁটি সুন্দরবনের মধু') }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="canonical_url">Canonical URL (Optional)</label>
                    <input type="url" name="canonical_url" id="canonical_url" class="form-control" value="{{ old('canonical_url', $settings['canonical_url'] ?? '') }}" placeholder="Leave blank to use current URL">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="og_image_file">Social Share Banner Image (OG Image)</label>
                    <div class="custom-file">
                      <input type="file" name="og_image_file" class="custom-file-input" id="og_image_file" accept="image/*">
                      <label class="custom-file-label" for="og_image_file">Choose social banner (1200x630 px)...</label>
                    </div>
                    <div class="mt-2 d-flex align-items-center">
                      <span class="text-muted small mr-2">Current Social Image:</span>
                      @php
                        $curOg = !empty($settings['og_image']) ? (str_starts_with($settings['og_image'], 'http') ? $settings['og_image'] : asset($settings['og_image'])) : asset('assets/images/hero-honey-jar.png');
                      @endphp
                      <img src="{{ $curOg }}" alt="Current OG" style="height: 45px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="site_favicon_file">Website Favicon (ওয়েবসাইট ফ্যাভিকন আইকন)</label>
                    <div class="custom-file">
                      <input type="file" name="site_favicon_file" class="custom-file-input" id="site_favicon_file" accept=".ico,.png,.svg,.jpg,.webp">
                      <label class="custom-file-label" for="site_favicon_file">Choose favicon (.png / .ico / .svg)...</label>
                    </div>
                    <div class="mt-2 d-flex align-items-center">
                      <span class="text-muted small mr-2">Current Favicon:</span>
                      @php
                        $curFavicon = !empty($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'http') ? $settings['site_favicon'] : asset($settings['site_favicon'])) : asset('assets/images/favicon.png');
                      @endphp
                      <img src="{{ $curFavicon }}" alt="Current Favicon" style="height: 38px; width: 38px; object-fit: contain; border: 1px solid #ddd; border-radius: 6px; padding: 2px; background: #ffffff;">
                      <small class="text-muted ml-2">ব্রাউজার ট্যাবে প্রদর্শিত হয় (32x32 বা 64x64 PNG/ICO)।</small>
                    </div>
                  </div>
                </div>
              </div>

              <hr>
              <h5 class="text-primary"><i class="fas fa-chart-line mr-1"></i> Webmaster & Analytics Tracking</h5>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="google_site_verification">Google Search Console Verification Code</label>
                    <input type="text" name="google_site_verification" id="google_site_verification" class="form-control @error('google_site_verification') is-invalid @enderror" value="{{ old('google_site_verification', $settings['google_site_verification'] ?? '') }}" placeholder="e.g. AbCdEfGhIjKlMnOpQrStUvWxYz">
                    @error('google_site_verification')
                      <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">HTML Tag-এর <code>content="..."</code> টোকেন দিন (পুরো ট্যাগ পেস্ট করলেও সিস্টেম স্বয়ংক্রিয়ভাবে টোকেন আলাদা করে নেবে)।</small>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="google_analytics_id">Google Analytics 4 ID (GA4)</label>
                    <input type="text" name="google_analytics_id" id="google_analytics_id" class="form-control @error('google_analytics_id') is-invalid @enderror" value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}" placeholder="e.g. G-XXXXXXXXXX">
                    @error('google_analytics_id')
                      <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">শুধু Measurement ID (যেমন: <code>G-XXXXXXXXXX</code>) দিন।</small>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="facebook_pixel_id">Facebook Pixel ID</label>
                    <input type="text" name="facebook_pixel_id" id="facebook_pixel_id" class="form-control @error('facebook_pixel_id') is-invalid @enderror" value="{{ old('facebook_pixel_id', $settings['facebook_pixel_id'] ?? '') }}" placeholder="e.g. 123456789012345">
                    @error('facebook_pixel_id')
                      <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">শুধু সংখ্যাসূচক পিক্সেল আইডি (যেমন: <code>123456789012345</code>) দিন।</small>
                  </div>
                </div>
              </div>

              <div class="alert alert-light border mt-2" style="color: #000000 !important;">
                <i class="fas fa-sitemap mr-1 text-success"></i> 
                <strong style="color: #000000 !important;">Automated SEO Assets:</strong>
                <a href="{{ route('sitemap') }}" target="_blank" class="ml-2 font-weight-bold seo-asset-link" style="color: #000000 !important;">View XML Sitemap (/sitemap.xml)</a>
                <span class="mx-2" style="color: #000000 !important;">|</span>
                <a href="{{ url('/robots.txt') }}" target="_blank" class="font-weight-bold seo-asset-link" style="color: #000000 !important;">View robots.txt</a>
              </div>
            </div>

            <!-- 2. Hero Banner & Visuals Tab -->
            <div class="tab-pane fade" id="hero-pane" role="tabpanel">
              <div class="card card-outline card-success bg-light mb-4 shadow-sm">
                <div class="card-body py-2 d-flex flex-wrap justify-content-between align-items-center">
                  <div class="mb-2 mb-md-0">
                    <strong class="text-dark"><i class="fas fa-desktop mr-1 text-success"></i> হিরো সেকশন অন/অফ (Hero Section Visibility):</strong>
                    <div class="text-muted small">হোমপেজে হিরো ব্যানার প্রদর্শন করতে চান কি না নির্বাচন করুন।</div>
                  </div>
                  <select name="section_hero_show" class="form-control font-weight-bold" style="width: auto; min-width: 220px;">
                    <option value="1" {{ ($settings['section_hero_show'] ?? '1') == '1' ? 'selected' : '' }}>✅ দৃশ্যমান (Show Section)</option>
                    <option value="0" {{ ($settings['section_hero_show'] ?? '1') == '0' ? 'selected' : '' }}>🚫 লুকিয়ে রাখুন (Hide Section)</option>
                  </select>
                </div>
              </div>

              <h5 class="text-success border-bottom pb-2 mb-3">Hero Section Text & Buttons</h5>

              <div class="form-group">
                <label for="hero_badge">Hero Top Pill Badge</label>
                <input type="text" name="hero_badge" id="hero_badge" class="form-control" value="{{ old('hero_badge', $settings['hero_badge'] ?? 'সুন্দরবনের সেরা মধু') }}">
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="hero_title_line1">Hero Headline (Line 1)</label>
                    <input type="text" name="hero_title_line1" id="hero_title_line1" class="form-control" value="{{ old('hero_title_line1', $settings['hero_title_line1'] ?? 'খাঁটি সুন্দরবনের মধু') }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="hero_title_line2">Hero Headline (Line 2 Accent)</label>
                    <input type="text" name="hero_title_line2" id="hero_title_line2" class="form-control" value="{{ old('hero_title_line2', $settings['hero_title_line2'] ?? '— প্রকৃতির আসল উপহার 🍯') }}">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="site_tagline">Hero Description / Tagline</label>
                <textarea name="site_tagline" id="site_tagline" rows="2" class="form-control">{{ old('site_tagline', $settings['site_tagline'] ?? '') }}</textarea>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="hero_order_btn_text">Hero Order Button Text</label>
                    <input type="text" name="hero_order_btn_text" id="hero_order_btn_text" class="form-control" value="{{ old('hero_order_btn_text', $settings['hero_order_btn_text'] ?? 'এখনই অর্ডার করুন') }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="hero_contact_btn_text">Hero Contact Button Text</label>
                    <input type="text" name="hero_contact_btn_text" id="hero_contact_btn_text" class="form-control" value="{{ old('hero_contact_btn_text', $settings['hero_contact_btn_text'] ?? 'যোগাযোগ করুন') }}">
                  </div>
                </div>
              </div>

              <h5 class="text-success border-bottom pb-2 mb-3 mt-4">Hero Trust Row Badges</h5>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="hero_trust_badge1">Trust Badge 1</label>
                    <input type="text" name="hero_trust_badge1" id="hero_trust_badge1" class="form-control" value="{{ old('hero_trust_badge1', $settings['hero_trust_badge1'] ?? '১৫০০+ হ্যাপি কাস্টমার') }}">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="hero_trust_badge2">Trust Badge 2</label>
                    <input type="text" name="hero_trust_badge2" id="hero_trust_badge2" class="form-control" value="{{ old('hero_trust_badge2', $settings['hero_trust_badge2'] ?? 'সারা দেশে ডেলিভারি') }}">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="hero_trust_badge3">Trust Badge 3</label>
                    <input type="text" name="hero_trust_badge3" id="hero_trust_badge3" class="form-control" value="{{ old('hero_trust_badge3', $settings['hero_trust_badge3'] ?? 'ক্যাশ অন ডেলিভারি') }}">
                  </div>
                </div>
              </div>

              <h5 class="text-success border-bottom pb-2 mb-3 mt-4">Hero Images (Upload New)</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="hero_bg_image_file">Change Hero Background Image</label>
                    <div class="custom-file">
                      <input type="file" name="hero_bg_image_file" class="custom-file-input" id="hero_bg_image_file" accept="image/*">
                      <label class="custom-file-label" for="hero_bg_image_file">Choose background image...</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="hero_product_image_file">Change Hero Honey Jar Image</label>
                    <div class="custom-file">
                      <input type="file" name="hero_product_image_file" class="custom-file-input" id="hero_product_image_file" accept="image/*">
                      <label class="custom-file-label" for="hero_product_image_file">Choose jar image...</label>
                    </div>
                  </div>
                </div>
              </div>

              <h5 class="text-success border-bottom pb-2 mb-3 mt-4">Trust Counters / Statistics</h5>
              <div class="row">
                <div class="col-md-4">
                  <div class="card p-2 bg-light">
                    <label>Stat 1 (Customers)</label>
                    <input type="text" name="hero_stat1_num" class="form-control mb-1" value="{{ old('hero_stat1_num', $settings['hero_stat1_num'] ?? '১৫০০+') }}">
                    <input type="text" name="hero_stat1_label" class="form-control" value="{{ old('hero_stat1_label', $settings['hero_stat1_label'] ?? 'সন্তুষ্ট গ্রাহক') }}">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card p-2 bg-light">
                    <label>Stat 2 (Purity)</label>
                    <input type="text" name="hero_stat2_num" class="form-control mb-1" value="{{ old('hero_stat2_num', $settings['hero_stat2_num'] ?? '১০০%') }}">
                    <input type="text" name="hero_stat2_label" class="form-control" value="{{ old('hero_stat2_label', $settings['hero_stat2_label'] ?? 'খাঁটি ও প্রাকৃতিক') }}">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card p-2 bg-light">
                    <label>Stat 3 (Districts)</label>
                    <input type="text" name="hero_stat3_num" class="form-control mb-1" value="{{ old('hero_stat3_num', $settings['hero_stat3_num'] ?? '৫০+') }}">
                    <input type="text" name="hero_stat3_label" class="form-control" value="{{ old('hero_stat3_label', $settings['hero_stat3_label'] ?? 'জেলায় ডেলিভারি') }}">
                  </div>
                </div>
              </div>
            </div>

            <!-- 3. Problem & Solution Tab -->
            <div class="tab-pane fade" id="problem-pane" role="tabpanel">
              <div class="card card-outline card-danger bg-light mb-4 shadow-sm">
                <div class="card-body py-2 d-flex flex-wrap justify-content-between align-items-center">
                  <div class="mb-2 mb-md-0">
                    <strong class="text-dark"><i class="fas fa-exclamation-triangle mr-1 text-danger"></i> সমস্যা ও সমাধান সেকশন অন/অফ (Problem & Solution Visibility):</strong>
                    <div class="text-muted small">হোমপেজে সমস্যা ও সমাধান সেকশন প্রদর্শন করতে চান কি না নির্বাচন করুন।</div>
                  </div>
                  <select name="section_problem_show" class="form-control font-weight-bold" style="width: auto; min-width: 220px;">
                    <option value="1" {{ ($settings['section_problem_show'] ?? '1') == '1' ? 'selected' : '' }}>✅ দৃশ্যমান (Show Section)</option>
                    <option value="0" {{ ($settings['section_problem_show'] ?? '1') == '0' ? 'selected' : '' }}>🚫 লুকিয়ে রাখুন (Hide Section)</option>
                  </select>
                </div>
              </div>

              <h5 class="text-danger border-bottom pb-2 mb-3">Section Titles</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="problem_title">Section Title</label>
                    <input type="text" name="problem_title" id="problem_title" class="form-control" value="{{ old('problem_title', $settings['problem_title'] ?? 'সমস্যা ও সমাধান') }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="problem_subtitle">Section Subtitle</label>
                    <input type="text" name="problem_subtitle" id="problem_subtitle" class="form-control" value="{{ old('problem_subtitle', $settings['problem_subtitle'] ?? 'আপনি কি সঠিক মধু পাচ্ছেন?') }}">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="card card-outline card-danger p-3">
                    <h6 class="font-weight-bold text-danger mb-3">😟 Problem Points (সমস্যাসমূহ — ৫টি পয়েন্ট)</h6>
                    @for($p = 1; $p <= 5; $p++)
                      <div class="form-group mb-3">
                        <label>Problem #{{ $p }}</label>
                        <input type="text" name="problem_item{{ $p }}" class="form-control" value="{{ old('problem_item' . $p, $settings['problem_item' . $p] ?? '') }}" placeholder="সমস্যা {{ $p }} লিখুন">
                      </div>
                    @endfor
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="card card-outline card-success p-3">
                    <h6 class="font-weight-bold text-success mb-3">😊 Our Solutions (আমাদের সমাধানসমূহ — ৫টি পয়েন্ট)</h6>
                    @for($s = 1; $s <= 5; $s++)
                      <div class="form-group mb-3">
                        <label>Solution #{{ $s }}</label>
                        <input type="text" name="solution_item{{ $s }}" class="form-control" value="{{ old('solution_item' . $s, $settings['solution_item' . $s] ?? '') }}" placeholder="সমাধান {{ $s }} লিখুন">
                      </div>
                    @endfor
                  </div>
                </div>
              </div>
            </div>

            <!-- 4. Why Choose Us Tab -->
            <div class="tab-pane fade" id="why-pane" role="tabpanel">
              <div class="card card-outline card-info bg-light mb-4 shadow-sm">
                <div class="card-body py-2 d-flex flex-wrap justify-content-between align-items-center">
                  <div class="mb-2 mb-md-0">
                    <strong class="text-dark"><i class="fas fa-award mr-1 text-info"></i> কেন আমাদের মধু সেরা সেকশন অন/অফ (Why Choose Us Visibility):</strong>
                    <div class="text-muted small">হোমপেজে ৬টি ফিচার কার্ড প্রদর্শন করতে চান কি না নির্বাচন করুন।</div>
                  </div>
                  <select name="section_why_us_show" class="form-control font-weight-bold" style="width: auto; min-width: 220px;">
                    <option value="1" {{ ($settings['section_why_us_show'] ?? '1') == '1' ? 'selected' : '' }}>✅ দৃশ্যমান (Show Section)</option>
                    <option value="0" {{ ($settings['section_why_us_show'] ?? '1') == '0' ? 'selected' : '' }}>🚫 লুকিয়ে রাখুন (Hide Section)</option>
                  </select>
                </div>
              </div>

              <h5 class="text-info border-bottom pb-2 mb-3">Why Choose Us Section</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="why_title">Section Title</label>
                    <input type="text" name="why_title" id="why_title" class="form-control" value="{{ old('why_title', $settings['why_title'] ?? 'কেন আমাদের মধু সেরা?') }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="why_subtitle">Section Subtitle</label>
                    <input type="text" name="why_subtitle" id="why_subtitle" class="form-control" value="{{ old('why_subtitle', $settings['why_subtitle'] ?? 'যে কারণে হাজারো মানুষ আমাদের বিশ্বাস করে') }}">
                  </div>
                </div>
              </div>

              <div class="row">
                @for($i = 1; $i <= 6; $i++)
                  <div class="col-md-6 mb-3">
                    <div class="card p-3 bg-light border">
                      <h6 class="font-weight-bold text-dark mb-2">Feature Card #{{ $i }}</h6>
                      <div class="form-group mb-2">
                        <label>Title</label>
                        <input type="text" name="why_feature{{ $i }}_title" class="form-control" value="{{ old('why_feature' . $i . '_title', $settings['why_feature' . $i . '_title'] ?? '') }}">
                      </div>
                      <div class="form-group mb-0">
                        <label>Description</label>
                        <textarea name="why_feature{{ $i }}_desc" rows="2" class="form-control">{{ old('why_feature' . $i . '_desc', $settings['why_feature' . $i . '_desc'] ?? '') }}</textarea>
                      </div>
                    </div>
                  </div>
                @endfor
              </div>
            </div>

            <!-- 5. Gallery Images Tab -->
            <div class="tab-pane fade" id="gallery-pane" role="tabpanel">
              <div class="card card-outline card-purple bg-light mb-4 shadow-sm">
                <div class="card-body py-2 d-flex flex-wrap justify-content-between align-items-center">
                  <div class="mb-2 mb-md-0">
                    <strong class="text-dark"><i class="fas fa-images mr-1 text-purple"></i> ফটো গ্যালারি সেকশন অন/অফ (Photo Gallery Visibility):</strong>
                    <div class="text-muted small">হোমপেজে সুন্দরবনের ৪টি গ্যালারি ছবি প্রদর্শন করতে চান কি না নির্বাচন করুন।</div>
                  </div>
                  <select name="section_gallery_show" class="form-control font-weight-bold" style="width: auto; min-width: 220px;">
                    <option value="1" {{ ($settings['section_gallery_show'] ?? '1') == '1' ? 'selected' : '' }}>✅ দৃশ্যমান (Show Section)</option>
                    <option value="0" {{ ($settings['section_gallery_show'] ?? '1') == '0' ? 'selected' : '' }}>🚫 লুকিয়ে রাখুন (Hide Section)</option>
                  </select>
                </div>
              </div>

              <h5 class="text-purple border-bottom pb-2 mb-3">Gallery Photos & Headings</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="gallery_title">Gallery Title</label>
                    <input type="text" name="gallery_title" id="gallery_title" class="form-control" value="{{ old('gallery_title', $settings['gallery_title'] ?? 'আমাদের গ্যালারি') }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="gallery_subtitle">Gallery Subtitle</label>
                    <input type="text" name="gallery_subtitle" id="gallery_subtitle" class="form-control" value="{{ old('gallery_subtitle', $settings['gallery_subtitle'] ?? 'সুন্দরবন থেকে আপনার খাবার টেবিল পর্যন্ত') }}">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                @for($g = 1; $g <= 4; $g++)
                  <div class="col-md-6 mb-3">
                    <div class="card p-3 bg-light border">
                      <h6 class="font-weight-bold">Gallery Photo #{{ $g }}</h6>
                      <div class="custom-file mb-2">
                        <input type="file" name="gallery_img{{ $g }}_file" class="custom-file-input" id="gallery_img{{ $g }}_file" accept="image/*">
                        <label class="custom-file-label" for="gallery_img{{ $g }}_file">Choose photo #{{ $g }}...</label>
                      </div>
                      <div class="d-flex align-items-center mt-2">
                        <span class="text-muted small mr-2">Current Photo:</span>
                        @php
                          $curG = !empty($settings['gallery_img' . $g]) ? (str_starts_with($settings['gallery_img' . $g], 'http') ? $settings['gallery_img' . $g] : asset($settings['gallery_img' . $g])) : asset('assets/images/gallery-main.jpg');
                        @endphp
                        <img src="{{ $curG }}" alt="Gallery {{ $g }}" style="height: 50px; width: 70px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc;">
                      </div>
                    </div>
                  </div>
                @endfor
              </div>
            </div>

            <!-- 6. Honey Benefits Tab -->
            <div class="tab-pane fade" id="benefits-pane" role="tabpanel">
              <div class="card card-outline card-success bg-light mb-4 shadow-sm">
                <div class="card-body py-2 d-flex flex-wrap justify-content-between align-items-center">
                  <div class="mb-2 mb-md-0">
                    <strong class="text-dark"><i class="fas fa-heartbeat mr-1 text-success"></i> মধুর স্বাস্থ্য উপকারিতা সেকশন অন/অফ (Benefits Section Visibility):</strong>
                    <div class="text-muted small">হোমপেজে মধুর ৬টি উপকারিতা কার্ড প্রদর্শন করতে চান কি না নির্বাচন করুন।</div>
                  </div>
                  <select name="section_benefits_show" class="form-control font-weight-bold" style="width: auto; min-width: 220px;">
                    <option value="1" {{ ($settings['section_benefits_show'] ?? '1') == '1' ? 'selected' : '' }}>✅ দৃশ্যমান (Show Section)</option>
                    <option value="0" {{ ($settings['section_benefits_show'] ?? '1') == '0' ? 'selected' : '' }}>🚫 লুকিয়ে রাখুন (Hide Section)</option>
                  </select>
                </div>
              </div>

              <h5 class="text-success border-bottom pb-2 mb-3">Honey Health Benefits</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="benefits_title">Section Title</label>
                    <input type="text" name="benefits_title" id="benefits_title" class="form-control" value="{{ old('benefits_title', $settings['benefits_title'] ?? 'মধুর উপকারিতা') }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="benefits_subtitle">Section Subtitle</label>
                    <input type="text" name="benefits_subtitle" id="benefits_subtitle" class="form-control" value="{{ old('benefits_subtitle', $settings['benefits_subtitle'] ?? 'প্রতিদিন এক চামচ খাঁটি মধু খাওয়ার অসাধারণ সব স্বাস্থ্য উপকারিতা') }}">
                  </div>
                </div>
              </div>

              <div class="row">
                @for($b = 1; $b <= 6; $b++)
                  <div class="col-md-6 mb-3">
                    <div class="card p-3 bg-light border">
                      <h6 class="font-weight-bold text-success mb-2">Benefit #{{ $b }}</h6>
                      <div class="form-group mb-2">
                        <label>Benefit Name</label>
                        <input type="text" name="benefit{{ $b }}_title" class="form-control" value="{{ old('benefit' . $b . '_title', $settings['benefit' . $b . '_title'] ?? '') }}">
                      </div>
                      <div class="form-group mb-0">
                        <label>Description</label>
                        <textarea name="benefit{{ $b }}_desc" rows="2" class="form-control">{{ old('benefit' . $b . '_desc', $settings['benefit' . $b . '_desc'] ?? '') }}</textarea>
                      </div>
                    </div>
                  </div>
                @endfor
              </div>
            </div>

            <!-- 7. Video & Promo Tab -->
            <div class="tab-pane fade" id="video-pane" role="tabpanel">
              <div class="card card-outline card-danger bg-light mb-4 shadow-sm">
                <div class="card-body py-2 d-flex flex-wrap justify-content-between align-items-center">
                  <div class="mb-2 mb-md-0">
                    <strong class="text-dark"><i class="fas fa-play-circle mr-1 text-danger"></i> ভিডিও সেকশন অন/অফ (Video Section Visibility):</strong>
                    <div class="text-muted small">হোমপেজে ইউটিউব ভিডিও প্লেয়ার সেকশন প্রদর্শন করতে চান কি না নির্বাচন করুন।</div>
                  </div>
                  <select name="section_video_show" class="form-control font-weight-bold" style="width: auto; min-width: 220px;">
                    <option value="1" {{ ($settings['section_video_show'] ?? '1') == '1' ? 'selected' : '' }}>✅ দৃশ্যমান (Show Section)</option>
                    <option value="0" {{ ($settings['section_video_show'] ?? '1') == '0' ? 'selected' : '' }}>🚫 লুকিয়ে রাখুন (Hide Section)</option>
                  </select>
                </div>
              </div>

              <h5 class="text-danger border-bottom pb-2 mb-3">Video Showcase Settings</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="video_title">Video Section Title</label>
                    <input type="text" name="video_title" id="video_title" class="form-control" value="{{ old('video_title', $settings['video_title'] ?? 'দেখুন কিভাবে সংগ্রহ করা হয়') }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="video_subtitle">Video Subtitle</label>
                    <input type="text" name="video_subtitle" id="video_subtitle" class="form-control" value="{{ old('video_subtitle', $settings['video_subtitle'] ?? 'সুন্দরবনের গহীন বন থেকে প্রাকৃতিকভাবে মধু সংগ্রহের দৃশ্য') }}">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="youtube_video_id">YouTube Video ID (or Video Code)</label>
                    <input type="text" name="youtube_video_id" id="youtube_video_id" class="form-control font-weight-bold" value="{{ old('youtube_video_id', $settings['youtube_video_id'] ?? 'M7lc1UVf-VE') }}">
                    <small class="text-muted">Example: for https://www.youtube.com/watch?v=M7lc1UVf-VE, the ID is <strong>M7lc1UVf-VE</strong></small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="youtube_video_title">Video Title Overlay</label>
                    <input type="text" name="youtube_video_title" id="youtube_video_title" class="form-control" value="{{ old('youtube_video_title', $settings['youtube_video_title'] ?? 'সুন্দরবনের খাঁটি মধু সংগ্রহের ভিডিও') }}">
                  </div>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="video_poster_file">ভিডিও পোস্টার / প্রিভিউ ইমেজ আপলোড (Custom Video Poster)</label>
                    <div class="custom-file">
                      <input type="file" name="video_poster_file" class="custom-file-input" id="video_poster_file" accept="image/*">
                      <label class="custom-file-label" for="video_poster_file">Choose poster image...</label>
                    </div>
                    <small class="text-muted">ইউটিউব ভিডিও প্লে করার পূর্বে এই ছবিটি হোমপেজে প্রদর্শিত হবে।</small>
                  </div>
                </div>
                <div class="col-md-6">
                  @php
                    $currentPoster = !empty($settings['video_poster_image']) ? asset($settings['video_poster_image']) : asset('assets/images/video-poster.jpg');
                  @endphp
                  <label class="d-block">বর্তমান ভিডিও পোস্টার প্রিভিউ:</label>
                  <img src="{{ $currentPoster }}" alt="Video Poster" style="max-height: 85px; border-radius: 6px; border: 1px solid #ddd;" class="shadow-sm">
                </div>
              </div>

              <div class="card card-outline card-primary bg-light mb-4 shadow-sm mt-4">
                <div class="card-body py-2 d-flex flex-wrap justify-content-between align-items-center">
                  <div class="mb-2 mb-md-0">
                    <strong class="text-dark"><i class="fas fa-tags mr-1 text-primary"></i> বিশেষ ডিসকাউন্ট ব্যানার অন/অফ (Discount Banner Visibility):</strong>
                    <div class="text-muted small">হোমপেজে ডিসকাউন্ট ও অফার ব্যানার প্রদর্শন করতে চান কি না নির্বাচন করুন।</div>
                  </div>
                  <select name="section_discount_show" class="form-control font-weight-bold" style="width: auto; min-width: 220px;">
                    <option value="1" {{ ($settings['section_discount_show'] ?? '1') == '1' ? 'selected' : '' }}>✅ দৃশ্যমান (Show Section)</option>
                    <option value="0" {{ ($settings['section_discount_show'] ?? '1') == '0' ? 'selected' : '' }}>🚫 লুকিয়ে রাখুন (Hide Section)</option>
                  </select>
                </div>
              </div>

              <h5 class="text-success border-bottom pb-2 mb-3 mt-4">Special Discount Banner</h5>
              <div class="form-group">
                <label for="discount_headline">Discount Banner Headline</label>
                <input type="text" name="discount_headline" id="discount_headline" class="form-control font-weight-bold" value="{{ old('discount_headline', $settings['discount_headline'] ?? 'আজ অর্ডার করলে পাচ্ছেন বিশেষ ডিসকাউন্ট!') }}">
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="card p-2 bg-light">
                    <label>Discount Card 1</label>
                    <input type="text" name="discount_card1_title" class="form-control mb-1" value="{{ old('discount_card1_title', $settings['discount_card1_title'] ?? 'ঢাকায় ডেলিভারি') }}">
                    <input type="text" name="discount_card1_desc" class="form-control" value="{{ old('discount_card1_desc', $settings['discount_card1_desc'] ?? 'ঢাকার ভিতরে দ্রুত হোম ডেলিভারি') }}">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card p-2 bg-light">
                    <label>Discount Card 2</label>
                    <input type="text" name="discount_card2_title" class="form-control mb-1" value="{{ old('discount_card2_title', $settings['discount_card2_title'] ?? 'ঢাকার বাইরে') }}">
                    <input type="text" name="discount_card2_desc" class="form-control" value="{{ old('discount_card2_desc', $settings['discount_card2_desc'] ?? 'সারা দেশে দ্রুত ক্যাশ অন ডেলিভারি') }}">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card p-2 bg-light">
                    <label>Discount Card 3</label>
                    <input type="text" name="discount_card3_title" class="form-control mb-1" value="{{ old('discount_card3_title', $settings['discount_card3_title'] ?? 'ক্যাশ অন ডেলিভারি') }}">
                    <input type="text" name="discount_card3_desc" class="form-control" value="{{ old('discount_card3_desc', $settings['discount_card3_desc'] ?? 'পণ্য হাতে পেয়ে চেক করে টাকা দিন') }}">
                  </div>
                </div>
              </div>
            </div>

            <!-- 8. Shipping & Rates Tab -->
            <div class="tab-pane fade" id="shipping-pane" role="tabpanel">
              <div class="card card-outline card-success bg-light mb-4 shadow-sm">
                <div class="card-body py-2 d-flex flex-wrap justify-content-between align-items-center">
                  <div class="mb-2 mb-md-0">
                    <strong class="text-dark"><i class="fas fa-shopping-cart mr-1 text-success"></i> সরাসরি চেকআউট অর্ডার ফর্ম অন/অফ (Order Form Visibility):</strong>
                    <div class="text-muted small">হোমপেজে সরাসরি অর্ডার ফর্ম প্রদর্শন করতে চান কি না নির্বাচন করুন।</div>
                  </div>
                  <select name="section_order_form_show" class="form-control font-weight-bold" style="width: auto; min-width: 220px;">
                    <option value="1" {{ ($settings['section_order_form_show'] ?? '1') == '1' ? 'selected' : '' }}>✅ দৃশ্যমান (Show Section)</option>
                    <option value="0" {{ ($settings['section_order_form_show'] ?? '1') == '0' ? 'selected' : '' }}>🚫 লুকিয়ে রাখুন (Hide Section)</option>
                  </select>
                </div>
              </div>

              <h5 class="text-warning border-bottom pb-2 mb-3"><i class="fas fa-truck mr-1"></i> Delivery Charges</h5>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="delivery_inside_dhaka">ঢাকার ভিতরে ডেলিভারি চার্জ (৳) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="delivery_inside_dhaka" id="delivery_inside_dhaka" class="form-control form-control-lg font-weight-bold" value="{{ old('delivery_inside_dhaka', $settings['delivery_inside_dhaka'] ?? 80) }}" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="delivery_outside_dhaka">ঢাকার বাইরে ডেলিভারি চার্জ (৳) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="delivery_outside_dhaka" id="delivery_outside_dhaka" class="form-control form-control-lg font-weight-bold" value="{{ old('delivery_outside_dhaka', $settings['delivery_outside_dhaka'] ?? 130) }}" required>
                  </div>
                </div>
              </div>

              <div class="alert alert-light border mt-3">
                <i class="fas fa-calculator mr-1 text-primary"></i> 
                These delivery fees automatically synchronize with the customer checkout form and update the subtotal and grand total dynamically in real time.
              </div>
            </div>

            <!-- 9. Contact & Social Tab -->
            <div class="tab-pane fade" id="contact-pane" role="tabpanel">
              <div class="card card-outline card-info bg-light mb-4 shadow-sm">
                <div class="card-body py-2">
                  <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                    <div class="mb-2 mb-md-0">
                      <strong class="text-dark"><i class="fas fa-shoe-prints mr-1 text-secondary"></i> ফুটার সেকশন অন/অফ (Footer Section Visibility):</strong>
                      <div class="text-muted small">হোমপেজে ফুটার ও কপিরাইট অংশ প্রদর্শন করতে চান কি না নির্বাচন করুন।</div>
                    </div>
                    <select name="section_footer_show" class="form-control font-weight-bold" style="width: auto; min-width: 220px;">
                      <option value="1" {{ ($settings['section_footer_show'] ?? '1') == '1' ? 'selected' : '' }}>✅ দৃশ্যমান (Show Section)</option>
                      <option value="0" {{ ($settings['section_footer_show'] ?? '1') == '0' ? 'selected' : '' }}>🚫 লুকিয়ে রাখুন (Hide Section)</option>
                    </select>
                  </div>
                  <hr class="my-2">
                  <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                      <strong class="text-dark"><i class="fab fa-whatsapp mr-1 text-success"></i> ভাসমান হোয়াটসঅ্যাপ বাটন অন/অফ (Floating WhatsApp Visibility):</strong>
                      <div class="text-muted small">স্ক্রিনের নিচে ভাসমান WhatsApp চ্যাট বাটন প্রদর্শন করতে চান কি না নির্বাচন করুন।</div>
                    </div>
                    <select name="section_whatsapp_widget_show" class="form-control font-weight-bold" style="width: auto; min-width: 220px;">
                      <option value="1" {{ ($settings['section_whatsapp_widget_show'] ?? '1') == '1' ? 'selected' : '' }}>✅ দৃশ্যমান (Show Section)</option>
                      <option value="0" {{ ($settings['section_whatsapp_widget_show'] ?? '1') == '0' ? 'selected' : '' }}>🚫 লুকিয়ে রাখুন (Hide Section)</option>
                    </select>
                  </div>
                </div>
              </div>

              <h5 class="text-info border-bottom pb-2 mb-3"><i class="fas fa-phone mr-1"></i> Contacts, WhatsApp & Footer</h5>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="contact_phone">Helpline Phone Number</label>
                    <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone', $settings['contact_phone'] ?? '01700-000000') }}" placeholder="01700-000000">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="whatsapp_number">Floating WhatsApp Number (e.g. 8801908793305)</label>
                    <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '8801908793305') }}" placeholder="8801908793305">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="contact_email">Support Email Address</label>
                    <input type="email" name="contact_email" id="contact_email" class="form-control" value="{{ old('contact_email', $settings['contact_email'] ?? 'info@modhu.com') }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="facebook_page_url">Facebook Business Page URL</label>
                    <input type="url" name="facebook_page_url" id="facebook_page_url" class="form-control" value="{{ old('facebook_page_url', $settings['facebook_page_url'] ?? 'https://facebook.com/flavortee') }}">
                  </div>
                </div>
              </div>

              <h5 class="text-secondary border-bottom pb-2 mb-3 mt-4">Footer Copyright & Branding</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="footer_brand_name">Footer Brand Title</label>
                    <input type="text" name="footer_brand_name" id="footer_brand_name" class="form-control" value="{{ old('footer_brand_name', $settings['footer_brand_name'] ?? 'খাঁটি মধু') }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="footer_copyright_text">Footer Copyright Notice</label>
                    <input type="text" name="footer_copyright_text" id="footer_copyright_text" class="form-control" value="{{ old('footer_copyright_text', $settings['footer_copyright_text'] ?? 'সর্বস্বত্ব সংরক্ষিত।') }}">
                  </div>
                </div>
              </div>
            </div>

            <!-- 10. Dynamic Section Colors Tab -->
            <div class="tab-pane fade" id="colors-pane" role="tabpanel">
              <div class="callout callout-success py-2 mb-4">
                <h5><i class="fas fa-palette mr-1 text-success"></i> ল্যান্ডিং পেজ সেকশন ব্যাকগ্রাউন্ড ও ফন্ট কালার নিয়ন্ত্রণ</h5>
                <p class="mb-0 text-muted">হিরো সেকশন ব্যতীত প্রতিটি সেকশনের ব্যাকগ্রাউন্ড কালার এবং ফন্ট কালার আপনার ইচ্ছামতো কাস্টমাইজ করুন।</p>
              </div>

              @php
                $colorSectionsList = [
                  [
                    'title'         => '২. সমস্যা ও সমাধান সেকশন (Problem & Solution)',
                    'selector'      => '#problemSolution',
                    'bg_key'        => 'section_problem_bg',
                    'color_key'     => 'section_problem_color',
                    'default_bg'    => '#e2f1e6',
                    'default_color' => '#111827',
                  ],
                  [
                    'title'         => '৩. কেন আমাদের মধু সেরা (Why Choose Us)',
                    'selector'      => '#whyUs',
                    'bg_key'        => 'section_why_us_bg',
                    'color_key'     => 'section_why_us_color',
                    'default_bg'    => '#e2f1e6',
                    'default_color' => '#111827',
                  ],
                  [
                    'title'         => '৪. ফটো গ্যালারি সেকশন (Photo Gallery)',
                    'selector'      => '#gallery',
                    'bg_key'        => 'section_gallery_bg',
                    'color_key'     => 'section_gallery_color',
                    'default_bg'    => '#ffffff',
                    'default_color' => '#111827',
                  ],
                  [
                    'title'         => '৫. মধু প্যাকেজ ও ফিচার্ড পণ্য (Products Section)',
                    'selector'      => '#featuredProduct',
                    'bg_key'        => 'section_products_bg',
                    'color_key'     => 'section_products_color',
                    'default_bg'    => '#f4f8f5',
                    'default_color' => '#111827',
                  ],
                  [
                    'title'         => '৬. মধুর স্বাস্থ্য উপকারিতা (Honey Benefits)',
                    'selector'      => '#benefits',
                    'bg_key'        => 'section_benefits_bg',
                    'color_key'     => 'section_benefits_color',
                    'default_bg'    => '#0f2e1e',
                    'default_color' => '#ffffff',
                  ],
                  [
                    'title'         => '৭. ভিডিও ও প্রমো সেকশন (Video Showcase)',
                    'selector'      => '#video',
                    'bg_key'        => 'section_video_bg',
                    'color_key'     => 'section_video_color',
                    'default_bg'    => '#ffffff',
                    'default_color' => '#111827',
                  ],
                  [
                    'title'         => '৮. গ্রাহকদের মতামত (Customer Reviews)',
                    'selector'      => '#reviews',
                    'bg_key'        => 'section_reviews_bg',
                    'color_key'     => 'section_reviews_color',
                    'default_bg'    => '#f8fafc',
                    'default_color' => '#111827',
                  ],
                  [
                    'title'         => '৯. বিশেষ ডিসকাউন্ট ব্যানার (Discount Banner)',
                    'selector'      => '#discount',
                    'bg_key'        => 'section_discount_bg',
                    'color_key'     => 'section_discount_color',
                    'default_bg'    => '#ffffff',
                    'default_color' => '#111827',
                  ],
                  [
                    'title'         => '১০. সরাসরি অর্ডার ফর্ম সেকশন (Order Form)',
                    'selector'      => '#orderForm',
                    'bg_key'        => 'section_order_form_bg',
                    'color_key'     => 'section_order_form_color',
                    'default_bg'    => '#ffffff',
                    'default_color' => '#111827',
                  ],
                  [
                    'title'         => '১১. ফুটার সেকশন (Footer Section)',
                    'selector'      => '#footer',
                    'bg_key'        => 'section_footer_bg',
                    'color_key'     => 'section_footer_color',
                    'default_bg'    => '#0f2e1e',
                    'default_color' => '#d1fae5',
                  ],
                ];
              @endphp

              <div class="row">
                @foreach($colorSectionsList as $cSec)
                  @php
                    $curBg = $settings[$cSec['bg_key']] ?? $cSec['default_bg'];
                    $curColor = $settings[$cSec['color_key']] ?? $cSec['default_color'];
                  @endphp
                  <div class="col-lg-6 col-12 mb-3">
                    <div class="card card-outline card-light shadow-sm h-100 border">
                      <div class="card-header bg-light py-2">
                        <h6 class="font-weight-bold mb-0 text-dark">
                          {{ $cSec['title'] }}
                        </h6>
                      </div>
                      <div class="card-body py-3">
                        <div class="row">
                          <div class="col-6">
                            <label class="small font-weight-bold text-dark d-block mb-1">
                              🎨 ব্যাকগ্রাউন্ড কালার:
                            </label>
                            <div class="d-flex align-items-center" style="gap: 6px;">
                              <input type="color" 
                                     id="set_{{ $cSec['bg_key'] }}_picker" 
                                     value="{{ $curBg }}" 
                                     class="form-control form-control-sm p-0 border shadow-sm" 
                                     style="width: 36px; height: 34px; cursor: pointer; border-radius: 6px;"
                                     onchange="document.getElementById('set_{{ $cSec['bg_key'] }}').value = this.value">
                              <input type="text" 
                                     name="{{ $cSec['bg_key'] }}" 
                                     id="set_{{ $cSec['bg_key'] }}" 
                                     value="{{ $curBg }}" 
                                     class="form-control form-control-sm text-center font-weight-bold" 
                                     placeholder="#hex"
                                     oninput="if(this.value.match(/^#[0-9A-Fa-f]{6}$/)) document.getElementById('set_{{ $cSec['bg_key'] }}_picker').value = this.value">
                            </div>
                          </div>
                          <div class="col-6">
                            <label class="small font-weight-bold text-dark d-block mb-1">
                              🔤 ফন্ট / টেক্সট কালার:
                            </label>
                            <div class="d-flex align-items-center" style="gap: 6px;">
                              <input type="color" 
                                     id="set_{{ $cSec['color_key'] }}_picker" 
                                     value="{{ $curColor }}" 
                                     class="form-control form-control-sm p-0 border shadow-sm" 
                                     style="width: 36px; height: 34px; cursor: pointer; border-radius: 6px;"
                                     onchange="document.getElementById('set_{{ $cSec['color_key'] }}').value = this.value">
                              <input type="text" 
                                     name="{{ $cSec['color_key'] }}" 
                                     id="set_{{ $cSec['color_key'] }}" 
                                     value="{{ $curColor }}" 
                                     class="form-control form-control-sm text-center font-weight-bold" 
                                     placeholder="#hex"
                                     oninput="if(this.value.match(/^#[0-9A-Fa-f]{6}$/)) document.getElementById('set_{{ $cSec['color_key'] }}_picker').value = this.value">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <!-- 11. Mail / SMTP (ইমেইল ও ওটিপি সেটিংস) -->
            <div class="tab-pane fade" id="mail-pane" role="tabpanel">
              <div class="callout callout-danger py-2 mb-4">
                <h5><i class="fas fa-envelope-open-text mr-1 text-danger"></i> ইমেইল ও ওটিপি কনফিগারেশন (Mail & SMTP Configuration)</h5>
                <p class="mb-0 text-muted">
                  অ্যাডমিন ড্যাশবোর্ডে লগইনের ওটিপি কোড এবং ওয়েবসাইটের সকল স্বয়ংক্রিয় ইমেইল পাঠানোর জন্য এই তথ্যগুলো ব্যবহৃত হবে। এই মানগুলো সরাসরি ডাটাবেজে সংরক্ষিত থাকে এবং ওটিপি পাঠাতে কাজ করে।
                </p>
              </div>

              @if($errors->has('smtp_error'))
                <div class="alert alert-danger py-2">
                  <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first('smtp_error') }}
                </div>
              @endif

              <div class="row">
                <!-- MAIL_MAILER -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mail_mailer" class="font-weight-bold">
                      MAIL_MAILER (মেইলার ড্রাইভার) <span class="text-danger">*</span>
                    </label>
                    <select name="mail_mailer" id="mail_mailer" class="form-control">
                      @php
                        $curMailer = $settings['mail_mailer'] ?? config('mail.default', 'smtp');
                      @endphp
                      <option value="smtp" {{ $curMailer === 'smtp' ? 'selected' : '' }}>smtp (Recommended for Live Email / Gmail / cPanel)</option>
                      <option value="log" {{ $curMailer === 'log' ? 'selected' : '' }}>log (Local Testing - writes to storage/logs)</option>
                      <option value="sendmail" {{ $curMailer === 'sendmail' ? 'selected' : '' }}>sendmail (Server native sendmail)</option>
                    </select>
                    <small class="form-text text-muted">সরাসরি ইমেইলে ওটিপি পাঠানোর জন্য <strong>smtp</strong> নির্বাচন করুন।</small>
                  </div>
                </div>

                <!-- MAIL_HOST -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mail_host" class="font-weight-bold">
                      MAIL_HOST (মেইল সার্ভার হোস্ট) <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="mail_host" 
                           id="mail_host" 
                           class="form-control" 
                           value="{{ $settings['mail_host'] ?? config('mail.mailers.smtp.host', 'smtp.gmail.com') }}" 
                           placeholder="e.g. smtp.gmail.com, mail.yourdomain.com">
                    <small class="form-text text-muted">যেমন: Gmail এর জন্য <code>smtp.gmail.com</code> অথবা আপনার ডোমেইনের SMTP হোস্ট।</small>
                  </div>
                </div>

                <!-- MAIL_PORT -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mail_port" class="font-weight-bold">
                      MAIL_PORT (পোর্ট নম্বর) <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           name="mail_port" 
                           id="mail_port" 
                           class="form-control" 
                           value="{{ $settings['mail_port'] ?? config('mail.mailers.smtp.port', 587) }}" 
                           placeholder="587, 465, বা 2525">
                    <small class="form-text text-muted">TLS এর জন্য সাধারণ পোর্ট <code>587</code> এবং SSL এর জন্য <code>465</code>।</small>
                  </div>
                </div>

                <!-- MAIL_ENCRYPTION -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mail_encryption" class="font-weight-bold">
                      MAIL_ENCRYPTION (সিকিউরিটি এনক্রিপশন)
                    </label>
                    @php
                      $curEnc = strtolower($settings['mail_encryption'] ?? (config('mail.mailers.smtp.encryption') ?? 'tls'));
                    @endphp
                    <select name="mail_encryption" id="mail_encryption" class="form-control">
                      <option value="tls" {{ $curEnc === 'tls' ? 'selected' : '' }}>tls (Recommended for Port 587)</option>
                      <option value="ssl" {{ $curEnc === 'ssl' ? 'selected' : '' }}>ssl (Recommended for Port 465)</option>
                      <option value="none" {{ in_array($curEnc, ['none', 'null', '']) ? 'selected' : '' }}>none (No Encryption)</option>
                    </select>
                    <small class="form-text text-muted">Gmail বা আধুনিক মেইল সার্ভারে সাধারণত <strong>tls</strong> প্রযোজ্য।</small>
                  </div>
                </div>

                <!-- MAIL_USERNAME -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mail_username" class="font-weight-bold">
                      MAIL_USERNAME (মেইল ইউজারনেম / ইমেইল আইডি)
                    </label>
                    <input type="text" 
                           name="mail_username" 
                           id="mail_username" 
                           class="form-control" 
                           value="{{ $settings['mail_username'] ?? config('mail.mailers.smtp.username', '') }}" 
                           placeholder="e.g. yourname@gmail.com">
                    <small class="form-text text-muted">যে ইমেইল অ্যাকাউন্ট থেকে মেইল যাবে তার ইউজারনেম বা ইমেইল।</small>
                  </div>
                </div>

                <!-- MAIL_PASSWORD -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mail_password" class="font-weight-bold">
                      MAIL_PASSWORD (মেইল পাসওয়ার্ড / অ্যাপ পাসওয়ার্ড)
                    </label>
                    <div class="input-group">
                      <input type="password" 
                             name="mail_password" 
                             id="mail_password" 
                             class="form-control" 
                             value="{{ $settings['mail_password'] ?? config('mail.mailers.smtp.password', '') }}" 
                             placeholder="••••••••••••">
                      <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleMailPasswordVisibility()">
                          <i class="fas fa-eye" id="toggleMailPasswordIcon"></i>
                        </button>
                      </div>
                    </div>
                    <small class="form-text text-muted">Gmail ব্যবহার করলে সাধারণ পাসওয়ার্ডের বদলে Google Account এর <strong>16-digit App Password</strong> ব্যবহার করুন।</small>
                  </div>
                </div>

                <!-- MAIL_FROM_ADDRESS -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mail_from_address" class="font-weight-bold">
                      MAIL_FROM_ADDRESS (প্রেরকের ইমেইল ঠিকানা) <span class="text-danger">*</span>
                    </label>
                    <input type="email" 
                           name="mail_from_address" 
                           id="mail_from_address" 
                           class="form-control" 
                           value="{{ $settings['mail_from_address'] ?? config('mail.from.address', 'hello@example.com') }}" 
                           placeholder="e.g. info@modhu.com">
                    <small class="form-text text-muted">ওটিপি প্রাপকের ইনবক্সে প্রেরক হিসেবে এই ইমেইলটি দেখা যাবে।</small>
                  </div>
                </div>

                <!-- MAIL_FROM_NAME -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mail_from_name" class="font-weight-bold">
                      MAIL_FROM_NAME (প্রেরকের নাম) <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="mail_from_name" 
                           id="mail_from_name" 
                           class="form-control" 
                           value="{{ $settings['mail_from_name'] ?? (config('mail.from.name') ?: ($settings['site_title'] ?? 'মধু')) }}" 
                           placeholder="e.g. সুন্দরবনের মধু">
                    <small class="form-text text-muted">ইমেইল নোটিফিকেশনে প্রেরক কোম্পানি বা সাইটের নাম।</small>
                  </div>
                </div>
              </div>

              <!-- Test SMTP Box -->
              <div class="card card-outline card-secondary bg-white shadow-none mt-3">
                <div class="card-header py-2">
                  <span class="font-weight-bold text-dark">
                    <i class="fas fa-paper-plane text-info mr-1"></i> SMTP কানেকশন টেস্ট (Test SMTP Delivery)
                  </span>
                </div>
                <div class="card-body py-3">
                  <p class="text-muted small mb-3">
                    কনফিগারেশন সংরক্ষণের পর এখানে আপনার যেকোনো ইমেইল লিখে টেস্ট বাটনে ক্লিক করে নিশ্চিত হতে পারেন যে সার্ভার সঠিকভাবে ইমেইল পাঠাতে পারছে।
                  </p>
                  <div class="form-inline">
                    <div class="input-group mb-2 mr-sm-2" style="max-width: 380px; width: 100%;">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                      </div>
                      <input type="email" 
                             id="testEmailInput" 
                             class="form-control" 
                             placeholder="আপনার টেস্ট ইমেইল ঠিকানা দিন" 
                             value="{{ auth()->user()->email ?? '' }}">
                    </div>
                    <button type="button" class="btn btn-outline-info mb-2 font-weight-bold shadow-sm" onclick="triggerTestEmail()">
                      <i class="fas fa-paper-plane mr-1"></i> টেস্ট ইমেইল পাঠান (Send Test Mail)
                    </button>
                  </div>
                </div>
              </div>

            </div>

          </div>
        </div>

        <div class="card-footer bg-light text-right">
          <button type="submit" class="btn btn-success px-5 font-weight-bold">
            <i class="fas fa-save mr-1"></i> Save All Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .alert a.seo-asset-link,
  .seo-asset-link {
    color: #000000 !important;
    text-decoration: underline !important;
  }
  .alert a.seo-asset-link:hover,
  .seo-asset-link:hover {
    color: #28a745 !important;
    text-decoration: underline !important;
  }
</style>
@endpush

@push('scripts')
<script>
function toggleMailPasswordVisibility() {
  const input = document.getElementById('mail_password');
  const icon = document.getElementById('toggleMailPasswordIcon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}

function triggerTestEmail() {
  const email = document.getElementById('testEmailInput').value;
  if (!email) {
    alert('অনুগ্রহ করে একটি টেস্ট ইমেইল ঠিকানা প্রদান করুন।');
    return;
  }
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '{{ route('admin.settings.send-test-email') }}';
  
  const csrfInput = document.createElement('input');
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = '{{ csrf_token() }}';
  form.appendChild(csrfInput);

  const emailInput = document.createElement('input');
  emailInput.type = 'hidden';
  emailInput.name = 'test_email';
  emailInput.value = email;
  form.appendChild(emailInput);

  document.body.appendChild(form);
  form.submit();
}
</script>
@endpush
