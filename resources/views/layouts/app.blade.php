<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="referrer" content="strict-origin-when-cross-origin">

  <!-- Primary SEO Meta Tags -->
  <title>@yield('title', $settings['meta_title'] ?? $settings['site_title'] ?? 'খাঁটি সুন্দরবনের মধু — প্রকৃতির আসল উপহার')</title>
  <meta name="description" content="@yield('meta_description', $settings['meta_description'] ?? 'সুন্দরবনের গহীন বন থেকে মৌয়ালদের সংগৃহীত ১০০% খাঁটি ও প্রাকৃতিক খলিসা ও গরান ফুলের মধু। ক্যাশ অন ডেলিভারিতে সারা বাংলাদেশে দ্রুত হোম ডেলিভারি।')">
  <meta name="keywords" content="@yield('meta_keywords', $settings['meta_keywords'] ?? 'খাঁটি মধু, সুন্দরবনের মধু, প্রাকৃতিক মধু, মধুর উপকারিতা, pure honey bangladesh')">
  <meta name="author" content="{{ $settings['meta_author'] ?? 'খাঁটি সুন্দরবনের মধু' }}">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="@yield('canonical_url', !empty($settings['canonical_url']) ? $settings['canonical_url'] : url()->current())">

  <!-- Website Favicon -->
  @include('partials.favicon')

  @php
    $ogImage = !empty($settings['og_image'])
      ? (str_starts_with($settings['og_image'], 'http') ? $settings['og_image'] : asset($settings['og_image']))
      : asset('assets/images/hero-honey-jar.png');
  @endphp

  <!-- Open Graph / Facebook / WhatsApp Meta Tags -->
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:locale" content="bn_BD">
  <meta property="og:title" content="@yield('og_title', $settings['meta_title'] ?? $settings['site_title'] ?? 'খাঁটি সুন্দরবনের মধু')">
  <meta property="og:description" content="@yield('og_description', $settings['meta_description'] ?? 'সুন্দরবনের গহীন বন থেকে সংগৃহীত ১০০% খাঁটি প্রাকৃতিক মধু।')">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:site_name" content="{{ $settings['site_title'] ?? 'খাঁটি সুন্দরবনের মধু' }}">
  <meta property="og:image" content="@yield('og_image', $ogImage)">
  <meta property="og:image:secure_url" content="@yield('og_image', $ogImage)">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="{{ $settings['site_title'] ?? 'খাঁটি সুন্দরবনের মধু' }}">

  <!-- Twitter Card Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('twitter_title', $settings['meta_title'] ?? $settings['site_title'] ?? 'খাঁটি সুন্দরবনের মধু')">
  <meta name="twitter:description" content="@yield('twitter_description', $settings['meta_description'] ?? 'সুন্দরবনের গহীন বন থেকে সংগৃহীত ১০০% খাঁটি প্রাকৃতিক মধু।')">
  <meta name="twitter:image" content="@yield('twitter_image', $ogImage)">

  @if(!empty($settings['google_site_verification']) && preg_match('/^[a-zA-Z0-9_\-]+$/', $settings['google_site_verification']))
    <!-- Google Search Console Verification -->
    <meta name="google-site-verification" content="{{ $settings['google_site_verification'] }}">
  @endif

  @if(!empty($settings['google_analytics_id']) && preg_match('/^(G-[A-Za-z0-9]+|UA-[0-9]+-[0-9]+)$/', $settings['google_analytics_id']))
    <!-- Google Analytics (GA4) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['google_analytics_id'] }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ $settings['google_analytics_id'] }}');
    </script>
  @endif

  @if(!empty($settings['facebook_pixel_id']) && preg_match('/^[0-9]{5,25}$/', $settings['facebook_pixel_id']))
    <!-- Meta Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '{{ $settings['facebook_pixel_id'] }}');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id={{ $settings['facebook_pixel_id'] }}&ev=PageView&noscript=1"
    /></noscript>
  @endif

  <!-- Local Google Fonts: Hind Siliguri (Bengali) -->
  <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">

  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ filemtime(public_path('assets/css/style.css')) }}">

  <!-- Organization & WebSite JSON-LD Schema -->
  <script type="application/ld+json">
  {
    "@@context": "https://schema.org",
    "@@graph": [
      {
        "@type": "Organization",
        "@id": "{{ url('/') }}#organization",
        "name": "{{ $settings['site_title'] ?? 'সুন্দরবনের মধু' }}",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('assets/images/hero-honey-jar.png') }}",
        "contactPoint": {
          "@type": "ContactPoint",
          "telephone": "{{ $settings['contact_phone'] ?? '+8801700000000' }}",
          "contactType": "customer service",
          "areaServed": "BD",
          "availableLanguage": ["Bengali", "English"]
        }
      },
      {
        "@type": "WebSite",
        "@id": "{{ url('/') }}#website",
        "url": "{{ url('/') }}",
        "name": "{{ $settings['site_title'] ?? 'সুন্দরবনের মধু' }}",
        "description": "{{ $settings['meta_description'] ?? 'সুন্দরবনের খাঁটি মধু' }}",
        "publisher": {
          "@id": "{{ url('/') }}#organization"
        },
        "inLanguage": "bn-BD"
      }
    ]
  }
  </script>

  @stack('schema')
  @stack('styles')
</head>
<body>

  @include('partials.alerts')

  @yield('content')

  <!-- Floating WhatsApp Widget -->
  @if(($settings['section_whatsapp_widget_show'] ?? '1') == '1' && !empty($settings['whatsapp_number']))
  @php
    $whatsapp = $settings['whatsapp_number'] ?? '8801908793305';
  @endphp
  <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener" class="floating-whatsapp-widget" aria-label="Contact us on WhatsApp">
    <span class="wa-speech-bubble">Contact us</span>
    <span class="wa-btn-circle">
      <i class="bi bi-whatsapp"></i>
    </span>
  </a>
  @endif

  <!-- Bootstrap 5 Bundle JS -->
  <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom Vanilla JS -->
  <script src="{{ asset('assets/js/main.js') }}"></script>
  @stack('scripts')
</body>
</html>
