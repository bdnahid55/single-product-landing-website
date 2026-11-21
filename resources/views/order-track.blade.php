@extends('layouts.app')

@section('title', 'অর্ডার ট্র্যাকিং | ' . ($settings['site_title'] ?? ''))

@push('styles')
<style>
  .track-hero {
    background: linear-gradient(135deg, #1e3a1e 0%, #2e5a2e 100%);
    color: #ffffff;
    padding: 48px 0 36px;
    border-radius: 0 0 24px 24px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  }
  .track-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
  }
  .track-stepper {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 30px 0 20px;
  }
  .track-stepper::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 40px;
    right: 40px;
    height: 4px;
    background: #e2e8f0;
    z-index: 1;
  }
  .stepper-progress {
    position: absolute;
    top: 20px;
    left: 40px;
    height: 4px;
    background: #16a34a;
    z-index: 2;
    transition: width 0.5s ease;
  }
  .step-node {
    position: relative;
    z-index: 3;
    text-align: center;
    width: 90px;
  }
  .step-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #64748b;
    border: 3px solid #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-size: 1.1rem;
    font-weight: bold;
    transition: all 0.3s ease;
  }
  .step-node.completed .step-circle {
    background: #16a34a;
    color: #ffffff;
    border-color: #16a34a;
  }
  .step-node.active .step-circle {
    background: #ffffff;
    color: #16a34a;
    border-color: #16a34a;
    box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.2);
  }
  .step-label {
    font-size: 0.82rem;
    color: #64748b;
    font-weight: 600;
  }
  .step-node.completed .step-label,
  .step-node.active .step-label {
    color: #1e293b;
    font-weight: 700;
  }
  @media (max-width: 576px) {
    .track-stepper::before, .stepper-progress {
      left: 20px;
      right: 20px;
    }
    .step-circle {
      width: 36px;
      height: 36px;
      font-size: 0.95rem;
    }
    .step-node {
      width: 70px;
    }
    .step-label {
      font-size: 0.72rem;
    }
  }
</style>
@endpush

@section('content')
@php
  $seoTitle = !empty($settings['meta_title']) ? $settings['meta_title'] : (!empty($settings['site_title']) ? $settings['site_title'] : 'খাঁটি সুন্দরবনের মধু');
  $siteFavicon = !empty($settings['site_favicon'])
    ? (str_starts_with($settings['site_favicon'], 'http') ? $settings['site_favicon'] : asset($settings['site_favicon']))
    : asset('assets/images/favicon.png');
@endphp
<!-- Top Navbar -->
<header class="bg-white border-bottom py-3 sticky-top shadow-sm">
  <div class="container d-flex justify-content-between align-items-center">
    <a href="{{ route('home') }}" class="text-decoration-none d-flex align-items-center gap-2">
      <img src="{{ $siteFavicon }}" alt="Logo" style="height: 36px; width: 36px; object-fit: contain; border-radius: 6px;">
      <span class="fw-bold text-dark fs-5">{{ $seoTitle }}</span>
    </a>
    <a href="{{ route('home') }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
      <i class="bi bi-house-door me-1"></i> হোমপেজে যান
    </a>
  </div>
</header>

<!-- Hero Banner -->
<section class="track-hero text-center">
  <div class="container">
    <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-circle p-3 mb-3">
      <i class="bi bi-geo-alt-fill fs-2 text-warning"></i>
    </div>
    <h1 class="fw-bold mb-2">অর্ডার ট্র্যাকিং (Order Tracking)</h1>
    <p class="text-light mb-0 opacity-90">আপনার অর্ডার নম্বর ও মোবাইল নম্বর প্রদান করে বর্তমান ডেলিভারি স্ট্যাটাস চেক করুন</p>
  </div>
</section>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">

      <!-- Tracking Search Form -->
      <div class="track-card p-4 p-md-5 mb-5">
        <h4 class="fw-bold text-dark mb-3 text-center">
          <i class="bi bi-search text-success me-2"></i>আপনার অর্ডারের তথ্য খুঁজুন
        </h4>

        @if ($errors->any())
          <div class="alert alert-danger rounded-3 mb-4">
            <ul class="mb-0 small">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('order.track') }}" method="POST">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label for="order_number" class="form-label fw-bold text-dark">
                অর্ডার নম্বর (Order ID) <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-receipt"></i></span>
                <input type="text"
                       name="order_number"
                       id="order_number"
                       class="form-control"
                       placeholder="যেমন: ORD-261121-9BE109"
                       value="{{ old('order_number', $orderNumberInput ?? '') }}"
                       required>
              </div>
              <small class="text-muted">অর্ডার করার পর স্ক্রিনে প্রদর্শিত নম্বরটি দিন।</small>
            </div>

            <div class="col-md-6">
              <label for="customer_phone" class="form-label fw-bold text-dark">
                মোবাইল নম্বর (Customer Phone) <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                <input type="text"
                       name="customer_phone"
                       id="customer_phone"
                       class="form-control"
                       placeholder="যেমন: 017XXXXXXXX"
                       value="{{ old('customer_phone', $phoneInput ?? '') }}"
                       required>
              </div>
              <small class="text-muted">অর্ডারের সময় যে ফোন নম্বরটি দিয়েছিলেন।</small>
            </div>

            <div class="col-12 text-center mt-4">
              <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow-sm fw-bold">
                <i class="bi bi-search me-2"></i>অর্ডার স্ট্যাটাস দেখুন
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Error / Not Found Message -->
      @if($searched && $errorMessage)
        <div class="alert alert-warning border-warning shadow-sm rounded-4 p-4 text-center mb-5">
          <div class="fs-1 text-warning mb-2"><i class="bi bi-exclamation-triangle-fill"></i></div>
          <h5 class="fw-bold text-dark">{{ $errorMessage }}</h5>
          <p class="text-muted small mb-3">
            অনুগ্রহ করে নিশ্চিত করুন যে অর্ডার নম্বর (যেমন: <code>ORD-261121-XXXXXX</code>) এবং মোবাইল নম্বরটি সঠিকভাবে দেওয়া হয়েছে।
          </p>
          @if(!empty($settings['whatsapp_number']))
            <a href="https://wa.me/{{ $settings['whatsapp_number'] }}?text=Hello,%20I%20need%20help%20tracking%20my%20order"
               target="_blank"
               class="btn btn-success btn-sm rounded-pill px-3">
              <i class="bi bi-whatsapp me-1"></i> হোয়াটসঅ্যাপে সহায়তা নিন
            </a>
          @endif
        </div>
      @endif

      <!-- Order Details Display -->
      @if($order)
        <div class="track-card p-4 p-md-5 mb-5">

          <!-- Header status block -->
          <div class="d-flex flex-wrap justify-content-between align-items-center pb-3 border-bottom mb-4">
            <div>
              <span class="text-muted small d-block">অর্ডার ট্র্যাকিং ফলাফল:</span>
              <h3 class="fw-bold text-dark mb-0">অর্ডার #{{ $order->order_number }}</h3>
              <small class="text-muted">
                <i class="bi bi-calendar-check me-1"></i>{{ $order->created_at->format('d M, Y — h:i A') }}
              </small>
            </div>
            <div class="mt-3 mt-md-0">
              @if($order->order_status === 'pending')
                <span class="badge bg-warning text-dark px-3 py-2 fs-6 rounded-pill">
                  <i class="bi bi-hourglass-split me-1"></i> অপেক্ষমাণ (Pending)
                </span>
              @elseif($order->order_status === 'processing')
                <span class="badge bg-info text-dark px-3 py-2 fs-6 rounded-pill">
                  <i class="bi bi-gear-wide-connected me-1"></i> প্রসেসিং চলছে (Processing)
                </span>
              @elseif($order->order_status === 'shipped')
                <span class="badge bg-primary text-white px-3 py-2 fs-6 rounded-pill">
                  <i class="bi bi-truck me-1"></i> ডেলিভারির পথে (Shipped)
                </span>
              @elseif($order->order_status === 'delivered')
                <span class="badge bg-success text-white px-3 py-2 fs-6 rounded-pill">
                  <i class="bi bi-check-circle-fill me-1"></i> ডেলিভারি সম্পন্ন (Delivered)
                </span>
              @elseif($order->order_status === 'cancelled')
                <span class="badge bg-danger text-white px-3 py-2 fs-6 rounded-pill">
                  <i class="bi bi-x-circle-fill me-1"></i> বাতিল (Cancelled)
                </span>
              @else
                <span class="badge bg-secondary px-3 py-2 fs-6 rounded-pill">
                  {{ ucfirst($order->order_status) }}
                </span>
              @endif
            </div>
          </div>

          <!-- Visual Progress Stepper -->
          @if($order->order_status === 'cancelled')
            <div class="alert alert-danger rounded-3 p-3 my-4">
              <i class="bi bi-info-circle-fill me-1"></i> এই অর্ডারটি বাতিল করা হয়েছে। বিস্তারিত জানতে আমাদের কাস্টমার সার্ভিসের সাথে যোগাযোগ করুন।
            </div>
          @else
            @php
              $stepIndex = 1;
              if ($order->order_status === 'processing') $stepIndex = 2;
              elseif ($order->order_status === 'shipped') $stepIndex = 3;
              elseif ($order->order_status === 'delivered') $stepIndex = 4;

              $progressWidth = match($stepIndex) {
                1 => '0%',
                2 => '33%',
                3 => '66%',
                4 => '100%',
                default => '0%',
              };
            @endphp

            <div class="track-stepper">
              <div class="stepper-progress" style="width: {{ $progressWidth }};"></div>

              <!-- Step 1: Placed -->
              <div class="step-node {{ $stepIndex >= 1 ? ($stepIndex == 1 ? 'active' : 'completed') : '' }}">
                <div class="step-circle">
                  @if($stepIndex > 1) <i class="bi bi-check-lg"></i> @else 1 @endif
                </div>
                <div class="step-label">অর্ডার গৃহীত</div>
              </div>

              <!-- Step 2: Processing -->
              <div class="step-node {{ $stepIndex >= 2 ? ($stepIndex == 2 ? 'active' : 'completed') : '' }}">
                <div class="step-circle">
                  @if($stepIndex > 2) <i class="bi bi-check-lg"></i> @else 2 @endif
                </div>
                <div class="step-label">প্রসেসিং চলছে</div>
              </div>

              <!-- Step 3: Shipped -->
              <div class="step-node {{ $stepIndex >= 3 ? ($stepIndex == 3 ? 'active' : 'completed') : '' }}">
                <div class="step-circle">
                  @if($stepIndex > 3) <i class="bi bi-check-lg"></i> @else 3 @endif
                </div>
                <div class="step-label">ডেলিভারির পথে</div>
              </div>

              <!-- Step 4: Delivered -->
              <div class="step-node {{ $stepIndex >= 4 ? 'completed' : '' }}">
                <div class="step-circle">
                  @if($stepIndex >= 4) <i class="bi bi-check-lg"></i> @else 4 @endif
                </div>
                <div class="step-label">ডেলিভারি সম্পন্ন</div>
              </div>
            </div>
          @endif

          <!-- Customer & Delivery Information Grid -->
          <div class="row g-3 bg-light rounded-3 p-3 my-4 border">
            <div class="col-md-6">
              <span class="text-muted small d-block">গ্রাহকের নাম:</span>
              <strong class="text-dark">{{ $order->customer_name }}</strong>
            </div>
            <div class="col-md-6">
              <span class="text-muted small d-block">মোবাইল নম্বর:</span>
              <strong class="text-dark">{{ $order->customer_phone }}</strong>
            </div>
            <div class="col-md-6">
              <span class="text-muted small d-block">ডেলিভারি এলাকা:</span>
              <strong class="text-dark">
                {{ $order->delivery_area === 'inside_dhaka' ? 'ঢাকার ভিতরে (হোম ডেলিভারি)' : 'ঢাকার বাইরে (কুরিয়ার হোম ডেলিভারি)' }}
              </strong>
            </div>
            <div class="col-md-6">
              <span class="text-muted small d-block">পেমেন্ট মেথড:</span>
              <strong class="text-dark">
                ক্যাশ অন ডেলিভারি (COD)
                <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} ms-1">
                  {{ $order->payment_status === 'paid' ? 'পরিশোধিত' : 'অপরিশোধিত' }}
                </span>
              </strong>
            </div>
            <div class="col-12">
              <span class="text-muted small d-block">ডেলিভারি ঠিকানা:</span>
              <span class="text-dark">{{ $order->customer_address }}</span>
            </div>
          </div>

          <!-- Ordered Products Table -->
          <h5 class="fw-bold text-dark mt-4 mb-3">
            <i class="bi bi-basket2 text-success me-1"></i> অর্ডারের পণ্যসমূহ
          </h5>
          <div class="table-responsive">
            <table class="table table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th>পণ্য</th>
                  <th class="text-center" style="width: 15%;">পরিমাণ</th>
                  <th class="text-end" style="width: 25%;">মূল্য</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->items as $item)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        @if($item->product && $item->product->image)
                          <img src="{{ $item->product->image_url }}"
                               alt="{{ $item->product_name }}"
                               style="width: 45px; height: 45px; object-fit: contain; border-radius: 6px; border: 1px solid #e2e8f0;">
                        @endif
                        <div>
                          <strong class="text-dark d-block">{{ $item->product_name }}</strong>
                          @if($item->weight)
                            <span class="badge bg-light text-muted border">{{ $item->weight }}</span>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td class="text-center fw-bold">{{ $item->quantity }}</td>
                    <td class="text-end fw-bold">৳{{ number_format($item->subtotal, 2) }}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="2" class="text-end text-muted">সাবটোটাল:</td>
                  <td class="text-end fw-bold">৳{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                  <td colspan="2" class="text-end text-muted">ডেলিভারি চার্জ:</td>
                  <td class="text-end">৳{{ number_format($order->delivery_charge, 2) }}</td>
                </tr>
                <tr class="table-success">
                  <td colspan="2" class="text-end fw-bold text-success fs-5">সর্বমোট প্রদেয়:</td>
                  <td class="text-end fw-bold text-success fs-5">৳{{ number_format($order->total_amount, 2) }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Support & Helpline -->
          <div class="d-flex flex-wrap justify-content-between align-items-center bg-light rounded-3 p-3 mt-4 border">
            <div>
              <strong class="text-dark d-block">অর্ডার সংক্রান্ত কোনো প্রশ্ন বা সহায়তার প্রয়োজন?</strong>
              <small class="text-muted">আমাদের কাস্টমার কেয়ার প্রতিনিধি আপনাকে সাহায্য করতে প্রস্তুত।</small>
            </div>
            <div class="d-flex gap-2 mt-2 mt-md-0">
              @if(!empty($settings['contact_phone']))
                <a href="tel:{{ $settings['contact_phone'] }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                  <i class="bi bi-telephone me-1"></i> কল করুন
                </a>
              @endif
              @if(!empty($settings['whatsapp_number']))
                <a href="https://wa.me/{{ $settings['whatsapp_number'] }}?text={{ urlencode('হ্যালো! আমার অর্ডার #' . $order->order_number . ' এর ব্যাপারে জানতে চাচ্ছি।') }}"
                   target="_blank"
                   class="btn btn-success btn-sm rounded-pill px-3">
                  <i class="bi bi-whatsapp me-1"></i> হোয়াটসঅ্যাপ চ্যাট
                </a>
              @endif
            </div>
          </div>

        </div>
      @endif

    </div>
  </div>
</div>

<!-- Footer Partial -->
@include('partials.footer')
@endsection
