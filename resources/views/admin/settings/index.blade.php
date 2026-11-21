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

              <hr>
              <h5 class="text-primary"><i class="fas fa-chart-line mr-1"></i> Webmaster & Analytics Tracking</h5>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="google_site_verification">Google Search Console Verification Code</label>
                    <input type="text" name="google_site_verification" id="google_site_verification" class="form-control" value="{{ old('google_site_verification', $settings['google_site_verification'] ?? '') }}" placeholder="e.g. AbCdEfGhIjKlMnOpQrStUvWxYz">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="google_analytics_id">Google Analytics 4 ID (GA4)</label>
                    <input type="text" name="google_analytics_id" id="google_analytics_id" class="form-control" value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}" placeholder="e.g. G-XXXXXXXXXX">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="facebook_pixel_id">Facebook Pixel ID</label>
                    <input type="text" name="facebook_pixel_id" id="facebook_pixel_id" class="form-control" value="{{ old('facebook_pixel_id', $settings['facebook_pixel_id'] ?? '') }}" placeholder="e.g. 123456789012345">
                  </div>
                </div>
              </div>

              <div class="alert alert-light border mt-2">
                <i class="fas fa-sitemap mr-1 text-success"></i> 
                <strong>Automated SEO Assets:</strong>
                <a href="{{ route('sitemap') }}" target="_blank" class="ml-2 font-weight-bold">View XML Sitemap (/sitemap.xml)</a>
                <span class="mx-2">|</span>
                <a href="{{ url('/robots.txt') }}" target="_blank" class="font-weight-bold">View robots.txt</a>
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
