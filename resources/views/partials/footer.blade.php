@if(($settings['section_footer_show'] ?? '1') == '1')
<footer id="footer" class="footer-section">
  <div class="container">
    <div class="row g-4 justify-content-between">
      
      <!-- Column 1: Brand & Bio -->
      <div class="col-lg-4 col-md-6">
        <div class="footer-brand">
          <span>🍯</span> {{ $settings['footer_brand_name'] ?? $settings['site_title'] ?? 'খাঁটি মধু' }}
        </div>
        <p class="footer-about-text">
          {{ $settings['site_tagline'] ?? 'সুন্দরবনের গহীন বন থেকে সরাসরি আপনার ঘরে — ১০০% খাঁটি ও প্রাকৃতিক মধু।' }}
        </p>
      </div>

      <!-- Column 2: Quick Links -->
      <div class="col-lg-3 col-md-6">
        <h3 class="footer-col-title">লিংক</h3>
        <ul class="footer-links-list">
          @if(($settings['section_hero_show'] ?? '1') == '1')
            <li><a href="{{ route('home') }}#hero">হোম</a></li>
          @endif
          @if(($settings['section_products_show'] ?? '1') == '1')
            <li><a href="{{ route('home') }}#featuredProduct">মধু পরিচিতি</a></li>
          @endif
          @if(($settings['section_order_form_show'] ?? '1') == '1')
            <li><a href="{{ route('home') }}#orderForm">অর্ডার করুন</a></li>
          @endif
          @if(($settings['section_why_us_show'] ?? '1') == '1')
            <li><a href="{{ route('home') }}#whyUs">কেন আমরা</a></li>
          @endif
          <li><a href="{{ route('order.track') }}">অর্ডার ট্র্যাক করুন</a></li>
        </ul>
      </div>

      <!-- Column 3: Contact Details -->
      <div class="col-lg-4 col-md-6">
        <h3 class="footer-col-title">যোগাযোগ</h3>
        <ul class="footer-contact-list">
          <li>
            <i class="bi bi-telephone footer-contact-icon"></i>
            <a href="tel:{{ $settings['contact_phone'] ?? '01700-000000' }}">{{ $settings['contact_phone'] ?? '01700-000000' }}</a>
          </li>
          @if(!empty($settings['whatsapp_number']))
          <li>
            <i class="bi bi-whatsapp footer-contact-icon"></i>
            <a href="https://wa.me/{{ $settings['whatsapp_number'] }}" target="_blank" rel="noopener">WhatsApp Chat</a>
          </li>
          @endif
          @if(!empty($settings['facebook_page_url']))
          <li>
            <i class="bi bi-facebook footer-contact-icon"></i>
            <a href="{{ $settings['facebook_page_url'] }}" target="_blank" rel="noopener">Facebook Page</a>
          </li>
          @endif
          <li>
            <i class="bi bi-envelope footer-contact-icon"></i>
            <a href="mailto:{{ $settings['contact_email'] ?? 'info@modhu.com' }}">{{ $settings['contact_email'] ?? 'info@modhu.com' }}</a>
          </li>
        </ul>
      </div>

    </div>

    <!-- Horizontal Divider -->
    <div class="footer-divider"></div>

    <!-- Copyright Note -->
    <div class="footer-copyright">
      © {{ date('Y') }} {{ $settings['footer_brand_name'] ?? $settings['site_title'] ?? config('app.name', 'Modhu') }}। {{ $settings['footer_copyright_text'] ?? 'সর্বস্বত্ব সংরক্ষিত।' }}
    </div>
  </div>
</footer>
@endif
