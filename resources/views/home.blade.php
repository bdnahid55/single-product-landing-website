@extends('layouts.app')

@section('title', $settings['meta_title'] ?? $settings['site_title'] ?? 'খাঁটি সুন্দরবনের মধু — প্রকৃতির আসল উপহার')
@section('meta_description', $settings['meta_description'] ?? 'সুন্দরবনের গহীন বন থেকে সংগৃহীত ১০০% খাঁটি ও প্রাকৃতিক খলিসা ও গরান ফুলের মধু।')
@section('meta_keywords', $settings['meta_keywords'] ?? 'খাঁটি মধু, সুন্দরবনের মধু, প্রাকৃতিক মধু')

@push('schema')
@if($featuredProduct)
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "Product",
  "name": "{{ $featuredProduct->name }}",
  "image": [
    "{{ $featuredProduct->image_url }}"
  ],
  "description": "{{ $featuredProduct->short_description ?? 'সুন্দরবনের খাঁটি প্রাকৃতিক মধু' }}",
  "sku": "HONEY-{{ $featuredProduct->id }}",
  "brand": {
    "@@type": "Brand",
    "name": "{{ $settings['site_title'] ?? 'সুন্দরবনের মধু' }}"
  },
  "offers": {
    "@@type": "Offer",
    "url": "{{ url('/') }}#orderForm",
    "priceCurrency": "BDT",
    "price": "{{ $featuredProduct->sale_price }}",
    "priceValidUntil": "{{ date('Y-12-31') }}",
    "itemCondition": "https://schema.org/NewCondition",
    "availability": "https://schema.org/InStock",
    "seller": {
      "@@type": "Organization",
      "name": "{{ $settings['site_title'] ?? 'সুন্দরবনের মধু' }}"
    }
  }
  @if($reviews->count() > 0)
  ,
  "aggregateRating": {
    "@@type": "AggregateRating",
    "ratingValue": "{{ round($reviews->avg('rating'), 1) }}",
    "reviewCount": "{{ $reviews->count() }}"
  }
  @endif
}
</script>
@endif
@endpush

@push('styles')
<style id="dynamic-section-styles">
  @php
    $dynamicStyles = [
      'problem' => [
        'selector' => '#problemSolution',
        'bg'       => $settings['section_problem_bg'] ?? null,
        'color'    => $settings['section_problem_color'] ?? null,
      ],
      'why_us' => [
        'selector' => '#whyUs',
        'bg'       => $settings['section_why_us_bg'] ?? null,
        'color'    => $settings['section_why_us_color'] ?? null,
      ],
      'gallery' => [
        'selector' => '#gallery',
        'bg'       => $settings['section_gallery_bg'] ?? null,
        'color'    => $settings['section_gallery_color'] ?? null,
      ],
      'products' => [
        'selector' => '#featuredProduct',
        'bg'       => $settings['section_products_bg'] ?? null,
        'color'    => $settings['section_products_color'] ?? null,
      ],
      'benefits' => [
        'selector' => '#benefits',
        'bg'       => $settings['section_benefits_bg'] ?? null,
        'color'    => $settings['section_benefits_color'] ?? null,
      ],
      'video' => [
        'selector' => '#video',
        'bg'       => $settings['section_video_bg'] ?? null,
        'color'    => $settings['section_video_color'] ?? null,
      ],
      'reviews' => [
        'selector' => '#reviews',
        'bg'       => $settings['section_reviews_bg'] ?? null,
        'color'    => $settings['section_reviews_color'] ?? null,
      ],
      'discount' => [
        'selector' => '#discount',
        'bg'       => $settings['section_discount_bg'] ?? null,
        'color'    => $settings['section_discount_color'] ?? null,
      ],
      'order_form' => [
        'selector' => '#orderForm',
        'bg'       => $settings['section_order_form_bg'] ?? null,
        'color'    => $settings['section_order_form_color'] ?? null,
      ],
      'footer' => [
        'selector' => '#footer',
        'bg'       => $settings['section_footer_bg'] ?? null,
        'color'    => $settings['section_footer_color'] ?? null,
      ],
    ];
  @endphp

  @foreach($dynamicStyles as $sKey => $sVal)
    @if(!empty($sVal['bg']))
      {{ $sVal['selector'] }} {
        background-color: {{ $sVal['bg'] }} !important;
      }
    @endif
    @if(!empty($sVal['color']))
      {{ $sVal['selector'] }},
      {{ $sVal['selector'] }} .section-title,
      {{ $sVal['selector'] }} .section-subtitle {
        color: {{ $sVal['color'] }} !important;
      }
      @if($sKey === 'footer')
        {{ $sVal['selector'] }} .footer-brand,
        {{ $sVal['selector'] }} .footer-about-text,
        {{ $sVal['selector'] }} .footer-col-title,
        {{ $sVal['selector'] }} .footer-links-list a,
        {{ $sVal['selector'] }} .footer-contact-list a,
        {{ $sVal['selector'] }} .footer-contact-icon,
        {{ $sVal['selector'] }} .footer-copyright {
          color: {{ $sVal['color'] }} !important;
        }
      @endif
      @if($sKey === 'benefits')
        {{ $sVal['selector'] }} .benefit-card-title,
        {{ $sVal['selector'] }} .benefit-card-desc {
          color: {{ $sVal['color'] }} !important;
        }
      @endif
    @endif
  @endforeach
</style>
@endpush

@section('content')

  @php
    $heroBg = !empty($settings['hero_bg_image'])
      ? (str_starts_with($settings['hero_bg_image'], 'http') ? $settings['hero_bg_image'] : asset($settings['hero_bg_image']))
      : asset('assets/images/hero-bg.jpg');

    $heroProdImg = !empty($settings['hero_product_image'])
      ? (str_starts_with($settings['hero_product_image'], 'http') ? $settings['hero_product_image'] : asset($settings['hero_product_image']))
      : asset('assets/images/hero-honey-jar.png');

    $defaultProd = $featuredProduct ?? $products->first();
    $deliveryInside = floatval($settings['delivery_inside_dhaka'] ?? 80);
    $deliveryOutside = floatval($settings['delivery_outside_dhaka'] ?? 130);
  @endphp

  <!-- ==========================================================================
       1. Hero Section (with default Bootstrap Carousel)
       ========================================================================== -->
  @if(($settings['section_hero_show'] ?? '1') == '1')
  <header id="hero" class="hero-section" style="background-image: url('{{ $heroBg }}');">
    <div id="heroCarousel" class="carousel slide w-100 h-100" data-bs-ride="carousel">
      <div class="carousel-inner h-100">
        <div class="carousel-item active h-100">
          <div class="container h-100 position-relative z-1 d-flex align-items-center">
            <div class="row align-items-center w-100">

              <!-- Left Column: Hero Content & CTA -->
              <div class="col-lg-7 col-md-12 hero-left-content">
                <div class="hero-badge-pill">
                    {{ $settings['hero_badge'] ?? 'সুন্দরবনের সেরা মধু' }}
                </div>

                <h1 class="hero-title">
                  {{ $settings['hero_title_line1'] ?? 'খাঁটি সুন্দরবনের মধু' }}<br>
                  <span class="accent-orange">{{ $settings['hero_title_line2'] ?? '— প্রকৃতির আসল উপহার' }}</span>
                </h1>

                <div class="hero-desc">
                  <p>{{ $settings['site_tagline'] ?? '১০০% প্রাকৃতিক ও ভেজালমুক্ত মধু, যা আপনার শরীরের শক্তি বাড়াবে এবং প্রতিদিনের জীবনে এনে দেবে সুস্থতা।' }}</p>
                </div>

                <div class="hero-btn-group">
                  <a href="#orderForm" class="btn-hero-order">
                    <span>{{ $settings['hero_order_btn_text'] ?? 'এখনই অর্ডার করুন' }}</span>
                  </a>
                  <a href="#footer" class="btn-hero-contact">
                    {{ $settings['hero_contact_btn_text'] ?? 'যোগাযোগ করুন' }}
                  </a>
                </div>

                <div class="hero-trust-row">
                  <div class="hero-trust-item">
                    <span class="hero-trust-stars">★★★★★</span>
                    <span>{{ $settings['hero_trust_badge1'] ?? '১৫০০+ হ্যাপি কাস্টমার' }}</span>
                  </div>
                  <div class="hero-trust-item">
                    <span>🚚</span>
                    <span>{{ $settings['hero_trust_badge2'] ?? 'সারা দেশে ডেলিভারি' }}</span>
                  </div>
                  <div class="hero-trust-item">
                    <span>💵</span>
                    <span>{{ $settings['hero_trust_badge3'] ?? 'ক্যাশ অন ডেলিভারি' }}</span>
                  </div>
                </div>
              </div>

              <!-- Right Column: Visual & Stats -->
              <div class="col-lg-5 col-md-12">
                <div class="hero-right-col">
                  <!-- Honey Jar Product Visual with SEO Alt -->
                  <img src="{{ $heroProdImg }}"
                       alt="{{ $settings['hero_title_line1'] ?? 'খাঁটি সুন্দরবনের মধু' }}"
                       class="hero-product-img img-fluid"
                       fetchpriority="high"
                       width="400"
                       height="400">

                  <!-- 3 Stats Cards -->
                  <div class="hero-stats-row">
                    <div class="hero-stat-card">
                      <div class="hero-stat-num">{{ $settings['hero_stat1_num'] ?? '১৫০০+' }}</div>
                      <div class="hero-stat-label">{{ $settings['hero_stat1_label'] ?? 'সন্তুষ্ট গ্রাহক' }}</div>
                    </div>
                    <div class="hero-stat-card">
                      <div class="hero-stat-num">{{ $settings['hero_stat2_num'] ?? '১০০%' }}</div>
                      <div class="hero-stat-label">{{ $settings['hero_stat2_label'] ?? 'খাঁটি ও প্রাকৃতিক' }}</div>
                    </div>
                    <div class="hero-stat-card">
                      <div class="hero-stat-num">{{ $settings['hero_stat3_num'] ?? '৫০+' }}</div>
                      <div class="hero-stat-label">{{ $settings['hero_stat3_label'] ?? 'জেলায় ডেলিভারি' }}</div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  @endif

  <main>
    <!-- ==========================================================================
         2. Problem & Solution Section (#problemSolution)
         ========================================================================== -->
    @php
      $activeProblems = [];
      for($p = 1; $p <= 5; $p++) {
        $val = trim($settings['problem_item' . $p] ?? '');
        if (!empty($val)) {
          $activeProblems[] = $val;
        }
      }
      $activeSolutions = [];
      for($s = 1; $s <= 5; $s++) {
        $val = trim($settings['solution_item' . $s] ?? '');
        if (!empty($val)) {
          $activeSolutions[] = $val;
        }
      }
    @endphp

    @if(($settings['section_problem_show'] ?? '1') == '1' && (count($activeProblems) > 0 || count($activeSolutions) > 0))
    <section id="problemSolution" class="problem-solution-section" aria-label="সমস্যা ও সমাধান">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">{{ $settings['problem_title'] ?? 'সমস্যা ও সমাধান' }}</h2>
          <p class="section-subtitle">{{ $settings['problem_subtitle'] ?? 'আপনি কি সঠিক মধু পাচ্ছেন?' }}</p>
        </div>

        <div class="problem-solution-container">
          <div class="row g-4 justify-content-center">
            <!-- Problem Box (Shows ONLY the items you have written) -->
            @if(count($activeProblems) > 0)
            <div class="{{ count($activeSolutions) > 0 ? 'col-md-6' : 'col-md-8' }}">
              <div class="prob-card problem-card">
                <h3 class="prob-card-title">
                  <span>😟</span> সমস্যা
                </h3>
                <ul class="prob-list">
                  @foreach($activeProblems as $probItem)
                    <li>
                      <i class="bi bi-x-square prob-icon-cross"></i>
                      <span>{{ $probItem }}</span>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
            @endif

            <!-- Solution Box (Shows ONLY the items you have written) -->
            @if(count($activeSolutions) > 0)
            <div class="{{ count($activeProblems) > 0 ? 'col-md-6' : 'col-md-8' }}">
              <div class="prob-card solution-card">
                <h3 class="prob-card-title">
                  <span>😊</span> আমাদের সমাধান
                </h3>
                <ul class="prob-list">
                  @foreach($activeSolutions as $solItem)
                    <li>
                      <i class="bi bi-check-square-fill prob-icon-check"></i>
                      <span>{{ $solItem }}</span>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </section>
    @endif

    <!-- ==========================================================================
         3. Why Choose Us Section (#whyUs)
         ========================================================================== -->
    @php
      $whyIcons = [
        1 => 'bi-shield-check',
        2 => 'bi-flower1',
        3 => 'bi-truck',
        4 => 'bi-cash-stack',
        5 => 'bi-arrow-repeat',
        6 => 'bi-people-fill',
      ];
      $activeWhyCount = 0;
      for($i = 1; $i <= 6; $i++) {
        if(!empty(trim($settings['why_feature' . $i . '_title'] ?? ''))) {
          $activeWhyCount++;
        }
      }
    @endphp
    @if(($settings['section_why_us_show'] ?? '1') == '1' && $activeWhyCount > 0)
    <section id="whyUs" class="why-us-section" aria-label="কেন আমাদের মধু সেরা">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">{{ $settings['why_title'] ?? 'কেন আমাদের মধু সেরা?' }}</h2>
          <p class="section-subtitle">{{ $settings['why_subtitle'] ?? 'যে কারণে হাজারো মানুষ আমাদের বিশ্বাস করে' }}</p>
        </div>

        <div class="row g-4">
          @for($i = 1; $i <= 6; $i++)
            @if(!empty(trim($settings['why_feature' . $i . '_title'] ?? '')))
              <div class="col-lg-4 col-md-6">
                <div class="why-card">
                  <div class="why-icon-circle">
                    <i class="bi {{ $whyIcons[$i] ?? 'bi-check-circle' }}"></i>
                  </div>
                  <h3 class="why-card-title">{{ $settings['why_feature' . $i . '_title'] }}</h3>
                  <p class="why-card-desc">{{ $settings['why_feature' . $i . '_desc'] ?? '' }}</p>
                </div>
              </div>
            @endif
          @endfor
        </div>
      </div>
    </section>
    @endif

    <!-- ==========================================================================
         4. Gallery Section (#gallery)
         ========================================================================== -->
    @php
      $gImg1 = !empty($settings['gallery_img1']) ? (str_starts_with($settings['gallery_img1'], 'http') ? $settings['gallery_img1'] : asset($settings['gallery_img1'])) : asset('assets/images/gallery-main.jpg');
      $gImg2 = !empty($settings['gallery_img2']) ? (str_starts_with($settings['gallery_img2'], 'http') ? $settings['gallery_img2'] : asset($settings['gallery_img2'])) : asset('assets/images/gallery-thumb-2.jpg');
      $gImg3 = !empty($settings['gallery_img3']) ? (str_starts_with($settings['gallery_img3'], 'http') ? $settings['gallery_img3'] : asset($settings['gallery_img3'])) : asset('assets/images/gallery-thumb-3.jpg');
      $gImg4 = !empty($settings['gallery_img4']) ? (str_starts_with($settings['gallery_img4'], 'http') ? $settings['gallery_img4'] : asset($settings['gallery_img4'])) : asset('assets/images/gallery-thumb-4.jpg');
    @endphp
    @if(($settings['section_gallery_show'] ?? '1') == '1')
    <section id="gallery" class="gallery-section" aria-label="ফটো গ্যালারি">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">{{ $settings['gallery_title'] ?? 'আমাদের গ্যালারি' }}</h2>
          <p class="section-subtitle">{{ $settings['gallery_subtitle'] ?? 'সুন্দরবন থেকে আপনার খাবার টেবিল পর্যন্ত' }}</p>
        </div>

        <div class="gallery-card-frame">
          <!-- Main Preview Image -->
          <div class="gallery-main-view">
            <img id="galleryMainImage" src="{{ $gImg1 }}" alt="সুন্দরবনের প্রাকৃতিক খাঁটি মধু গ্যালারি" loading="lazy" width="800" height="500">
          </div>

          <!-- 4 Interactive Thumbnails -->
          <div class="gallery-thumbs-row">
            <div class="gallery-thumb-item active" data-full-img="{{ $gImg1 }}">
              <img src="{{ $gImg1 }}" alt="মধু ছবি ১" loading="lazy" width="120" height="90">
            </div>
            <div class="gallery-thumb-item" data-full-img="{{ $gImg2 }}">
              <img src="{{ $gImg2 }}" alt="মধু ছবি ২" loading="lazy" width="120" height="90">
            </div>
            <div class="gallery-thumb-item" data-full-img="{{ $gImg3 }}">
              <img src="{{ $gImg3 }}" alt="মধু ছবি ৩" loading="lazy" width="120" height="90">
            </div>
            <div class="gallery-thumb-item" data-full-img="{{ $gImg4 }}">
              <img src="{{ $gImg4 }}" alt="মধু ছবি ৪" loading="lazy" width="120" height="90">
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

    <!-- ==========================================================================
         5. Dynamic Products Section (#featuredProduct)
         ========================================================================== -->
    @if(($settings['section_products_show'] ?? '1') == '1' && ($featuredProduct || $products->count() > 0))
    <section id="featuredProduct" class="featured-product-section" aria-label="আমাদের পণ্যসমূহ">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">আমাদের <span class="text-primary-green">পণ্যসমূহ</span></h2>
          <p class="section-subtitle">পছন্দের মধু নির্বাচন করে সরাসরি অর্ডার করুন</p>
        </div>

        @if($featuredProduct)
        <!-- Featured Highlighted Product Card -->
        <article class="featured-product-card mb-5">
          <div class="row align-items-center">
            <div class="col-md-5 col-12">
              <div class="featured-bottle-wrapper">
                <img src="{{ $featuredProduct->image_url }}" alt="{{ $featuredProduct->name }} - খাঁটি সুন্দরবনের মধু" class="img-fluid" loading="lazy" width="350" height="400">
              </div>
            </div>
            <div class="col-md-7 col-12">
              <div class="featured-badges-row">
                @if($featuredProduct->regular_price > $featuredProduct->sale_price)
                  @php
                    $discountPercent = round((($featuredProduct->regular_price - $featuredProduct->sale_price) / $featuredProduct->regular_price) * 100);
                  @endphp
                  <span class="badge-pill-discount">🏷️ {{ $discountPercent }}% ছাড়</span>
                @endif
                <span class="badge-pill-limited">🔥 {{ $featuredProduct->badge ?? 'হট ডিল' }}</span>
              </div>

              <h3 class="featured-prod-title">{{ $featuredProduct->name }}</h3>
              <p class="featured-prod-desc">
                {{ $featuredProduct->short_description ?? 'সরাসরি সুন্দরবন থেকে সংগ্রহ করা খাঁটি মধু, যা সম্পূর্ণ প্রাকৃতিক এবং স্বাস্থ্যকর।' }}
              </p>

              <div class="featured-price-row">
                <span class="featured-current-price">৳{{ number_format($featuredProduct->sale_price, 0) }}</span>
                @if($featuredProduct->regular_price > $featuredProduct->sale_price)
                  <span class="featured-old-price">৳{{ number_format($featuredProduct->regular_price, 0) }}</span>
                @endif
              </div>

              <div class="featured-tags-row">
                <span class="featured-tag-item tag-pure">✅ ১০০% খাঁটি</span>
                <span class="featured-tag-item tag-fragrant">🌸 সুগন্ধি</span>
                <span class="featured-tag-item tag-nutritious">💪 পুষ্টিকর</span>
              </div>

              <div>
                <button type="button" class="btn-order-featured select-product-btn"
                        data-id="{{ $featuredProduct->id }}"
                        data-name="{{ $featuredProduct->name }}"
                        data-price="{{ $featuredProduct->sale_price }}"
                        data-img="{{ $featuredProduct->image_url }}">
                  অর্ডার করুন
                </button>
              </div>
            </div>
          </div>
        </article>
        @endif

        <!-- All Available Honey Packages Grid (Shown in Multi-Product Mode) -->
        @php
          $displayGridProds = $gridProducts ?? $products;
          $gridMode = $showPackagesGrid ?? ($settings['show_packages_grid'] ?? 'all');
          if ($gridMode === '1') $gridMode = 'all';
          if ($gridMode === '0') $gridMode = 'none';
        @endphp
        @if(($storeMode ?? 'multiple') !== 'single' && $gridMode !== 'none' && $displayGridProds->count() > 0)
        <div class="row g-4 mt-2">
          @foreach($displayGridProds as $product)
            <div class="col-lg-4 col-md-6">
              <article class="card h-100 border-0 shadow-sm rounded-4 p-3 text-center position-relative" style="background: #ffffff; border: 1px solid #f0fdf4 !important;">
                @if($product->badge)
                  <span class="position-absolute top-0 start-0 m-3 badge rounded-pill bg-warning text-dark px-3 py-2 fw-bold">
                    {{ $product->badge }}
                  </span>
                @endif

                <div style="height: 200px; display: flex; align-items: center; justify-content: center;" class="mb-3">
                  <img src="{{ $product->image_url }}" alt="{{ $product->name }} - খাঁটি মধু" style="max-height: 180px; max-width: 100%; object-fit: contain;" loading="lazy">
                </div>

                <h3 class="fs-5 fw-bold text-dark mb-1">{{ $product->name }}</h3>
                @if($product->weight)
                  <span class="badge bg-light text-secondary mb-2 align-self-center">{{ $product->weight }}</span>
                @endif

                <p class="text-muted small mb-3 flex-grow-1">
                  {{ Str::limit($product->short_description ?? '১০০% খাঁটি ও প্রাকৃতিক সুন্দরবনের মধু।', 70) }}
                </p>

                <div class="d-flex justify-content-center align-items-baseline gap-2 mb-3">
                  <span class="fs-4 fw-bolder text-success">৳{{ number_format($product->sale_price, 0) }}</span>
                  @if($product->regular_price > $product->sale_price)
                    <span class="text-decoration-line-through text-muted small">৳{{ number_format($product->regular_price, 0) }}</span>
                  @endif
                </div>

                <button type="button" class="btn btn-success rounded-pill fw-bold py-2 w-100 select-product-btn"
                        style="background-color: var(--primary-green, #16a34a); border-color: var(--primary-green, #16a34a);"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $product->sale_price }}"
                        data-img="{{ $product->image_url }}">
                  এই পণ্যটি কিনুন 🛒
                </button>
              </article>
            </div>
          @endforeach
        </div>
        @endif

      </div>
    </section>
    @endif

    <!-- ==========================================================================
         6. Honey Benefits Section (#benefits)
         ========================================================================== -->
    @php
      $benefitIcons = [
        1 => 'bi-shield-plus',
        2 => 'bi-cup-hot-fill',
        3 => 'bi-lightning-charge-fill',
        4 => 'bi-brain',
        5 => 'bi-heart-pulse-fill',
        6 => 'bi-stars',
      ];
      $activeBenefitsCount = 0;
      for($b = 1; $b <= 6; $b++) {
        if(!empty(trim($settings['benefit' . $b . '_title'] ?? ''))) {
          $activeBenefitsCount++;
        }
      }
    @endphp
    @if(($settings['section_benefits_show'] ?? '1') == '1' && $activeBenefitsCount > 0)
    <section id="benefits" class="benefits-section" aria-label="মধুর স্বাস্থ্য উপকারিতা">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">{{ $settings['benefits_title'] ?? 'মধুর উপকারিতা' }}</h2>
          <p class="section-subtitle" style="color: #a7f3d0;">{{ $settings['benefits_subtitle'] ?? 'প্রতিদিন এক চামচ খাঁটি মধু খাওয়ার অসাধারণ সব স্বাস্থ্য উপকারিতা' }}</p>
        </div>

        <div class="row g-4">
          @for($b = 1; $b <= 6; $b++)
            @if(!empty(trim($settings['benefit' . $b . '_title'] ?? '')))
              <div class="col-lg-4 col-md-6">
                <div class="benefit-card">
                  <div class="benefit-icon-circle">
                    <i class="bi {{ $benefitIcons[$b] ?? 'bi-heart-fill' }}"></i>
                  </div>
                  <h3 class="benefit-card-title">{{ $settings['benefit' . $b . '_title'] }}</h3>
                  <p class="benefit-card-desc">{{ $settings['benefit' . $b . '_desc'] ?? '' }}</p>
                </div>
              </div>
            @endif
          @endfor
        </div>
      </div>
    </section>
    @endif

    <!-- ==========================================================================
         7. Video Showcase Section (#video)
         ========================================================================== -->
    @php
      $videoId = $settings['youtube_video_id'] ?? 'M7lc1UVf-VE';
      $videoTitle = $settings['youtube_video_title'] ?? 'সুন্দরবনের খাঁটি মধু সংগ্রহের ভিডিও';
    @endphp
    @if(($settings['section_video_show'] ?? '1') == '1' && !empty($videoId))
    <section id="video" class="video-section" aria-label="মধু সংগ্রহের ভিডিও">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">{{ $settings['video_title'] ?? 'দেখুন কিভাবে সংগ্রহ করা হয়' }}</h2>
          <p class="section-subtitle">{{ $settings['video_subtitle'] ?? 'সুন্দরবনের গহীন বন থেকে প্রাকৃতিকভাবে মধু সংগ্রহের দৃশ্য' }}</p>
        </div>

        <div class="video-card-frame">
          <div class="ratio ratio-16x9 video-inner-wrapper" id="videoContainer" data-video-id="{{ $videoId }}">
            <div class="video-facade" id="videoFacade">
              @php
                $videoPoster = !empty($settings['video_poster_image']) ? asset($settings['video_poster_image']) : asset('assets/images/video-poster.jpg');
              @endphp
              <img src="{{ $videoPoster }}" alt="{{ $videoTitle }}" class="video-poster-img" loading="lazy" width="800" height="450">
              <div class="video-play-overlay">
                <button type="button" class="video-play-btn" aria-label="Play YouTube Video" id="videoPlayBtn">
                  <svg viewBox="0 0 68 48" class="yt-play-svg">
                    <path class="yt-play-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"></path>
                    <path d="M 45,24 27,14 27,34" fill="#ffffff"></path>
                  </svg>
                </button>
              </div>
              <div class="video-top-bar">
                <span class="video-title-text">{{ $videoTitle }}</span>
              </div>
            </div>

            <iframe
              id="youtubeIframe"
              class="d-none"
              data-src="https://www.youtube.com/embed/{{ $videoId }}?rel=0"
              title="{{ $videoTitle }}"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              referrerpolicy="strict-origin-when-cross-origin"
              allowfullscreen>
            </iframe>
          </div>
        </div>
      </div>
    </section>
    @endif

    <!-- ==========================================================================
         8. Customer Testimonials / Reviews Section
         ========================================================================== -->
    @if(($settings['section_reviews_show'] ?? '1') == '1')
    <section id="reviews" class="py-5 reviews-section" aria-label="গ্রাহকদের মতামত">
      <div class="container py-3">
        <div class="section-header text-center mb-5">
          <h2 class="section-title">সম্মানিত গ্রাহকদের <span class="text-primary-green">মতামত</span></h2>
          <p class="section-subtitle">আমাদের মধুর অভিজ্ঞতা নিয়ে সন্তুষ্ট কাস্টমাররা যা বলছেন</p>
        </div>

        <div class="row g-4">
          @forelse($reviews as $rev)
            <div class="col-md-4">
              <div class="card h-100 border-0 shadow-sm p-4 rounded-4" style="background: #ffffff;">
                <div class="d-flex text-warning mb-2 fs-5" aria-label="{{ $rev->rating }} out of 5 stars">
                  @for($i = 0; $i < $rev->rating; $i++)
                    ★
                  @endfor
                </div>
                <p class="text-secondary mb-3 fst-italic">"{{ $rev->review_text }}"</p>
                <div class="d-flex align-items-center mt-auto">
                  <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold me-3" style="width: 45px; height: 45px; font-size: 1.1rem;">
                    {{ mb_substr($rev->customer_name, 0, 1) }}
                  </div>
                  <div>
                    <h4 class="h6 mb-0 fw-bold text-dark">{{ $rev->customer_name }}</h4>
                    <small class="text-muted">{{ $rev->customer_location ?? 'বাংলাদেশ' }}</small>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12 text-center text-muted py-3">
              <p>শীঘ্রই আরও রিভিউ যুক্ত হবে।</p>
            </div>
          @endforelse
        </div>
      </div>
    </section>
    @endif

    <!-- ==========================================================================
         9. Special Discount Features Banner (#discount)
         ========================================================================== -->
    @if(($settings['section_discount_show'] ?? '1') == '1')
    <section id="discount" class="discount-banner-section" aria-label="বিশেষ ছাড় অফার">
      <div class="container">
        <div class="discount-card-frame">
          <h2 class="discount-banner-title">
            <span>🎁 </span> <span class="text-primary-green">{{ $settings['discount_headline'] ?? 'আজ অর্ডার করলে পাচ্ছেন বিশেষ ডিসকাউন্ট!' }}</span>
          </h2>

          <div class="discount-features-row">
            <div class="discount-feature-card">
              <div class="discount-feature-icon">
                <i class="bi bi-cart-fill"></i>
              </div>
              <h3 class="discount-feature-title">{{ $settings['discount_card1_title'] ?? 'ঢাকায় ডেলিভারি' }}</h3>
              <p class="discount-feature-desc">{{ $settings['discount_card1_desc'] ?? 'ঢাকার ভিতরে দ্রুত হোম ডেলিভারি' }}</p>
            </div>

            <div class="discount-feature-card">
              <div class="discount-feature-icon">
                <i class="bi bi-truck"></i>
              </div>
              <h3 class="discount-feature-title">{{ $settings['discount_card2_title'] ?? 'ঢাকার বাইরে' }}</h3>
              <p class="discount-feature-desc">{{ $settings['discount_card2_desc'] ?? 'সারা দেশে দ্রুত ক্যাশ অন ডেলিভারি' }}</p>
            </div>

            <div class="discount-feature-card">
              <div class="discount-feature-icon">
                <i class="bi bi-cash-stack"></i>
              </div>
              <h3 class="discount-feature-title">{{ $settings['discount_card3_title'] ?? 'ক্যাশ অন ডেলিভারি' }}</h3>
              <p class="discount-feature-desc">{{ $settings['discount_card3_desc'] ?? 'পণ্য হাতে পেয়ে চেক করে টাকা দিন' }}</p>
            </div>
          </div>

          <div>
            <a href="#orderForm" class="discount-order-link">
              এখনই অর্ডার করুন
            </a>
          </div>
        </div>
      </div>
    </section>
    @endif

    <!-- ==========================================================================
         10. Interactive Order Form Section (#orderForm)
         ========================================================================== -->
    @if(($settings['section_order_form_show'] ?? '1') == '1' && $defaultProd)
    <section id="orderForm" class="order-form-section" aria-label="অর্ডার ফর্ম">
      <div class="container">
        <div class="order-form-card">

          <!-- Real Order Submission Form wrapping products and fields -->
          <form id="checkoutOrderForm" action="{{ route('orders.store') }}" method="POST">
            @csrf

            <!-- Anti-Bot Honeypot Field (Invisible to real users, catches automated spam bots) -->
            <div style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
              <input type="text" name="website_hp" value="" tabindex="-1" autocomplete="off">
            </div>

            @if ($errors->any())
              <div class="alert alert-danger shadow-sm mb-4" role="alert">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>অর্ডার ফর্মে কিছু ত্রুটি পাওয়া গেছে:</div>
                <ul class="mb-0 ps-3">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <!-- Header 1: Selected Products -->
            <div class="form-header-bar">
              নির্বাচিত পণ্যসমূহ
            </div>

            @if(($storeMode ?? 'multiple') !== 'single' && $products->count() > 1)
              <!-- Multi-Product Mode: Multi-Item Cart Selection (Option B) -->
              <div class="multi-cart-wrapper">
                <p class="text-muted small mb-3">
                  <i class="bi bi-info-circle text-success me-1"></i> আপনার পছন্দের মধু প্যাকেজ নির্বাচন করুন (এক বা একাধিক নির্বাচন করতে পারেন):
                </p>

                <div class="multi-products-list" id="multiProductsList">
                  @foreach($products as $prod)
                    <div class="multi-product-card d-flex align-items-center justify-content-between flex-wrap gap-2 {{ $loop->first ? 'selected' : '' }}" id="multiProdCard{{ $prod->id }}">
                      <div class="d-flex align-items-center gap-3">
                        <input type="checkbox"
                               name="selected_products[]"
                               value="{{ $prod->id }}"
                               id="chkProd{{ $prod->id }}"
                               class="multi-prod-checkbox"
                               data-id="{{ $prod->id }}"
                               data-name="{{ $prod->name }}"
                               data-price="{{ $prod->sale_price }}"
                               {{ $loop->first ? 'checked' : '' }}
                               onchange="window.onMultiCheckChange({{ $prod->id }})">
                        <label for="chkProd{{ $prod->id }}" class="d-flex align-items-center gap-2 mb-0" style="cursor: pointer;">
                          <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="multi-prod-img">
                          <div>
                            <div class="multi-prod-name">{{ $prod->name }}</div>
                            <div class="text-muted small">
                              @if($prod->weight)
                                <span class="badge bg-light text-secondary me-1">{{ $prod->weight }}</span>
                              @endif
                              <span class="text-success fw-bold">৳{{ number_format($prod->sale_price, 0) }}</span> / টি
                            </div>
                          </div>
                        </label>
                      </div>

                      <div class="d-flex align-items-center gap-3 ms-auto">
                        <!-- Quantity Controls -->
                        <div class="qty-control-box">
                          <button type="button" class="qty-btn" onclick="window.adjustMultiQty({{ $prod->id }}, -1)" aria-label="Decrease quantity">−</button>
                          <span id="qtyDisplay{{ $prod->id }}" class="qty-number">1</span>
                          <button type="button" class="qty-btn" onclick="window.adjustMultiQty({{ $prod->id }}, 1)" aria-label="Increase quantity">+</button>
                          <input type="hidden" name="quantities[{{ $prod->id }}]" id="qtyInput{{ $prod->id }}" value="1">
                        </div>

                        <!-- Subtotal for this line -->
                        <div class="text-end" style="min-width: 90px;">
                          <div class="small text-muted" style="font-size: 0.75rem;">সাবটোটাল</div>
                          <div id="lineTotal{{ $prod->id }}" class="fw-bold multi-line-subtotal">
                            ৳{{ number_format($prod->sale_price, 2) }}
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @else
              <!-- Single Product Mode: Single Item Cart -->
              <input type="hidden" name="product_id" id="formProductId" value="{{ $defaultProd->id }}">
              <input type="hidden" name="quantity" id="formQuantity" value="1">

              <!-- Selected Product Row in Cart -->
              <div id="selectedProductBox" class="selected-product-box">
                <div class="selected-prod-left">
                  <img id="cartProductImg" src="{{ $defaultProd->image_url }}" alt="{{ $defaultProd->name }}" class="selected-prod-img" width="60" height="60">
                  <div>
                    <div id="cartProductName" class="selected-prod-name">{{ $defaultProd->name }}</div>
                    <div id="cartProductUnitPrice" class="selected-prod-unit-price">৳{{ number_format($defaultProd->sale_price, 2) }}</div>
                  </div>
                </div>

                <div class="selected-prod-qty-col">
                  <div class="qty-label">পরিমাণ</div>
                  <div class="qty-control-box">
                    <button type="button" id="btnQtyMinus" class="qty-btn" aria-label="Decrease quantity">−</button>
                    <span id="cartProductQty" class="qty-number">1</span>
                    <button type="button" id="btnQtyPlus" class="qty-btn" aria-label="Increase quantity">+</button>
                  </div>
                </div>

                <div class="selected-prod-total-col">
                  <div>
                    <div class="subtotal-label">সর্বমোট</div>
                    <div id="cartProductSubtotal" class="subtotal-val">৳{{ number_format($defaultProd->sale_price, 2) }}</div>
                  </div>
                </div>
              </div>
            @endif

            <!-- Hidden Delivery Area -->
            <input type="hidden" name="delivery_area" id="formDeliveryArea" value="outside_dhaka">

            <!-- Delivery Area Selection -->
            <label class="form-field-label">ডেলিভারি এরিয়া নির্বাচন করুন</label>
            <div class="delivery-options-group">

              <!-- Outside Dhaka (Selected by Default) -->
              <div class="delivery-radio-card active" id="deliveryOutsideCard" data-area="outside_dhaka" data-charge="{{ $deliveryOutside }}">
                <div class="delivery-card-left">
                  <span class="delivery-radio-dot"></span>
                  <span>ঢাকার বাইরে ডেলিভারি চার্জ</span>
                </div>
                <div class="delivery-price">৳{{ number_format($deliveryOutside, 2) }}</div>
              </div>

              <!-- Inside Dhaka -->
              <div class="delivery-radio-card" id="deliveryInsideCard" data-area="inside_dhaka" data-charge="{{ $deliveryInside }}">
                <div class="delivery-card-left">
                  <span class="delivery-radio-dot"></span>
                  <span>ঢাকার ভিতরে ডেলিভারি চার্জ</span>
                </div>
                <div class="delivery-price">৳{{ number_format($deliveryInside, 2) }}</div>
              </div>

            </div>

            <!-- Price Calculation Breakdown -->
            <div class="price-breakdown-row">
              <span>পণ্যের মূল্য</span>
              <span id="summaryProductPrice" class="fw-bold">৳{{ number_format($defaultProd->sale_price, 2) }}</span>
            </div>
            <div class="price-breakdown-row">
              <span>ডেলিভারি চার্জ</span>
              <span id="summaryDeliveryPrice" class="fw-bold">৳{{ number_format($deliveryOutside, 2) }}</span>
            </div>

            <!-- Total Highlight Bar -->
            <div class="total-price-bar">
              <span class="total-price-label">মোট মূল্য:</span>
              <span id="summaryTotalPrice" class="total-price-val">৳{{ number_format($defaultProd->sale_price + $deliveryOutside, 2) }}</span>
            </div>

            <!-- Header 2: Customer Details -->
            <div class="form-header-bar">
              আপনার তথ্য দিন
            </div>

            <div class="order-input-group">
              <label for="customerName" class="order-input-label">আপনার নাম <span class="text-danger">*</span></label>
              <input type="text" name="customer_name" id="customerName" class="order-form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" placeholder="আপনার নাম লিখুন" required autocomplete="name">
              @error('customer_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="order-input-group">
              <label for="customerPhone" class="order-input-label">ফোন নম্বর <span class="text-danger">*</span></label>
              <input type="tel" name="customer_phone" id="customerPhone" class="order-form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone') }}" placeholder="017xxxxxxxx" required autocomplete="tel">
              @error('customer_phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="order-input-group">
              <label for="customerAddress" class="order-input-label">সম্পূর্ণ ঠিকানা (বাসা নং, রোড নং, এলাকা/জেলা) <span class="text-danger">*</span></label>
              <textarea name="customer_address" id="customerAddress" rows="3" class="order-form-control @error('customer_address') is-invalid @enderror" placeholder="আপনার সম্পূর্ণ ঠিকানা লিখুন (বাসা নং, রোড নং, এলাকা/জেলা)" required autocomplete="street-address">{{ old('customer_address') }}</textarea>
              @error('customer_address')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="order-input-group">
              <label for="customerNotes" class="order-input-label">বিশেষ নির্দেশনাবলী (ঐচ্ছিক)</label>
              <textarea name="customer_notes" id="customerNotes" rows="2" class="order-form-control" placeholder="কোনো বিশেষ মন্তব্য থাকলে লিখুন">{{ old('customer_notes') }}</textarea>
            </div>

            <!-- Payment Method -->
            <div class="payment-section-box">
              <label class="order-input-label">পেমেন্ট মেথড</label>
              <div class="payment-radio-box">
                <div class="delivery-radio-dot" style="border-color: var(--primary-green, #16a34a);">
                  <span style="display:block; width:8px; height:8px; background:var(--primary-green, #16a34a); border-radius:50%;"></span>
                </div>
                <span class="fw-bold">ক্যাশ অন ডেলিভারি (Cash on Delivery)</span>
              </div>
              <div class="payment-note-box">
                পণ্য হাতে পেয়ে মূল্য পরিশোধ করবেন। কোনো অগ্রিম পেমেন্টের প্রয়োজন নেই।
              </div>
            </div>

            <!-- Submit Order Button -->
            <button type="submit" class="btn-confirm-order-submit" id="btnSubmitOrder">
              অর্ডার কনফার্ম করুন
            </button>
          </form>

        </div>
      </div>
    </section>
    @endif
  </main>

  <!-- ==========================================================================
       11. Footer Section (#footer)
       ========================================================================== -->
  @include('partials.footer')

@endsection

@push('scripts')
@if($defaultProd)
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const isMultiMode = document.getElementById('multiProductsList') !== null;

    let currentDeliveryCharge = {{ $deliveryOutside }};
    let currentDeliveryArea = 'outside_dhaka';

    const summaryProductPrice = document.getElementById('summaryProductPrice');
    const summaryDeliveryPrice = document.getElementById('summaryDeliveryPrice');
    const summaryTotalPrice = document.getElementById('summaryTotalPrice');
    const formDeliveryArea = document.getElementById('formDeliveryArea');

    if (isMultiMode) {
      // -------------------------------------------------------------
      // MULTI-PRODUCT MODE (Option B: Pick multiple products together)
      // -------------------------------------------------------------
      function calculateMultiProductTotal() {
        let subtotal = 0;
        const checkboxes = document.querySelectorAll('.multi-prod-checkbox');

        checkboxes.forEach(chk => {
          const pId = chk.getAttribute('data-id');
          const card = document.getElementById('multiProdCard' + pId);
          const qtyInput = document.getElementById('qtyInput' + pId);
          const price = parseFloat(chk.getAttribute('data-price')) || 0;
          const qty = qtyInput ? parseInt(qtyInput.value, 10) || 1 : 1;
          const lineTotalEl = document.getElementById('lineTotal' + pId);

          const lineTotal = price * qty;
          if (lineTotalEl) {
            lineTotalEl.textContent = '৳' + lineTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
          }

          if (chk.checked) {
            if (card) card.classList.add('selected');
            subtotal += lineTotal;
          } else {
            if (card) card.classList.remove('selected');
          }
        });

        const grandTotal = subtotal > 0 ? (subtotal + currentDeliveryCharge) : 0;

        if (summaryProductPrice) {
          summaryProductPrice.textContent = '৳' + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        if (summaryDeliveryPrice) {
          summaryDeliveryPrice.textContent = '৳' + currentDeliveryCharge.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        if (summaryTotalPrice) {
          summaryTotalPrice.textContent = '৳' + grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        if (formDeliveryArea) {
          formDeliveryArea.value = currentDeliveryArea;
        }
      }

      window.onMultiCheckChange = function(pId) {
        calculateMultiProductTotal();
      };

      window.adjustMultiQty = function(pId, delta) {
        const qtyInput = document.getElementById('qtyInput' + pId);
        const qtyDisplay = document.getElementById('qtyDisplay' + pId);
        const chk = document.getElementById('chkProd' + pId);

        if (qtyInput) {
          let qty = (parseInt(qtyInput.value, 10) || 1) + delta;
          if (qty < 1) qty = 1;
          if (qty > 50) qty = 50;
          qtyInput.value = qty;
          if (qtyDisplay) qtyDisplay.textContent = qty;
        }

        // Auto check if quantity was modified
        if (chk && !chk.checked) {
          chk.checked = true;
        }

        calculateMultiProductTotal();
      };

      // Grid/Featured button click in Multi Mode
      document.querySelectorAll('.select-product-btn').forEach(button => {
        button.addEventListener('click', function (e) {
          e.preventDefault();
          const pId = parseInt(this.getAttribute('data-id'), 10);
          const targetChk = document.getElementById('chkProd' + pId);
          if (targetChk) {
            targetChk.checked = true;
            calculateMultiProductTotal();
          }

          const orderSection = document.getElementById('orderForm');
          if (orderSection) {
            orderSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      });

      // Checkout form submit validation in Multi Mode
      const checkoutForm = document.getElementById('checkoutOrderForm');
      if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
          const checkedCount = document.querySelectorAll('.multi-prod-checkbox:checked').length;
          if (checkedCount === 0) {
            e.preventDefault();
            alert('অনুগ্রহ করে কমপক্ষে একটি মধু প্যাকেজ নির্বাচন করুন।');
            const list = document.getElementById('multiProductsList');
            if (list) list.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
          }
        });
      }

      // Initial calculation
      calculateMultiProductTotal();

    } else {
      // -------------------------------------------------------------
      // SINGLE PRODUCT MODE
      // -------------------------------------------------------------
      let activeProduct = {
        id: {{ $defaultProd->id }},
        name: "{{ addslashes($defaultProd->name) }}",
        price: {{ $defaultProd->sale_price }},
        img: "{{ $defaultProd->image_url }}"
      };

      let currentQty = 1;

      const cartProductName = document.getElementById('cartProductName');
      const cartProductUnitPrice = document.getElementById('cartProductUnitPrice');
      const cartProductImg = document.getElementById('cartProductImg');
      const cartQtyDisplay = document.getElementById('cartProductQty');
      const cartProductSubtotal = document.getElementById('cartProductSubtotal');
      const formProductId = document.getElementById('formProductId');
      const formQuantity = document.getElementById('formQuantity');

      function updateOrderCalculations() {
        if (!cartProductName || !formProductId) return;
        const subtotal = activeProduct.price * currentQty;
        const total = subtotal + currentDeliveryCharge;

        cartProductName.textContent = activeProduct.name;
        cartProductUnitPrice.textContent = '৳' + activeProduct.price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        cartProductImg.src = activeProduct.img;
        cartQtyDisplay.textContent = currentQty;
        cartProductSubtotal.textContent = '৳' + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        summaryProductPrice.textContent = '৳' + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        summaryDeliveryPrice.textContent = '৳' + currentDeliveryCharge.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        summaryTotalPrice.textContent = '৳' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        formProductId.value = activeProduct.id;
        formQuantity.value = currentQty;
        formDeliveryArea.value = currentDeliveryArea;
      }

      document.querySelectorAll('.select-product-btn').forEach(button => {
        button.addEventListener('click', function (e) {
          e.preventDefault();
          const pId = parseInt(this.getAttribute('data-id'), 10);
          activeProduct.id = pId;
          activeProduct.name = this.getAttribute('data-name');
          activeProduct.price = parseFloat(this.getAttribute('data-price'));
          activeProduct.img = this.getAttribute('data-img');

          updateOrderCalculations();

          const orderSection = document.getElementById('orderForm');
          if (orderSection) {
            orderSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      });

      const btnMinus = document.getElementById('btnQtyMinus');
      const btnPlus = document.getElementById('btnQtyPlus');

      if (btnMinus) {
        btnMinus.addEventListener('click', function () {
          if (currentQty > 1) {
            currentQty--;
            updateOrderCalculations();
          }
        });
      }

      if (btnPlus) {
        btnPlus.addEventListener('click', function () {
          currentQty++;
          updateOrderCalculations();
        });
      }

      updateOrderCalculations();
    }

    // Delivery Area Selection Toggle (Shared for both Single & Multi modes)
    const deliveryCards = document.querySelectorAll('.delivery-radio-card');
    deliveryCards.forEach(card => {
      card.addEventListener('click', function () {
        deliveryCards.forEach(c => c.classList.remove('active'));
        this.classList.add('active');

        currentDeliveryCharge = parseFloat(this.getAttribute('data-charge')) || 0;
        currentDeliveryArea = this.getAttribute('data-area') || 'outside_dhaka';

        if (isMultiMode) {
          calculateMultiProductTotal();
        } else {
          updateOrderCalculations();
        }
      });
    });

    // Prevent duplicate rapid submissions (double-click lock)
    const orderFormEl = document.getElementById('checkoutOrderForm');
    if (orderFormEl) {
      orderFormEl.addEventListener('submit', function (e) {
        if (e.defaultPrevented) return;
        const submitBtn = document.getElementById('btnSubmitOrder');
        if (submitBtn) {
          setTimeout(() => {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.75';
            submitBtn.style.cursor = 'not-allowed';
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>অর্ডার প্রসেস হচ্ছে...';
          }, 10);
        }
      });
    }
  });
</script>
@endif
@endpush
