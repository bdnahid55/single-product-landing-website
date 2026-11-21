@extends('layouts.app')

@section('title', 'পেমেন্ট স্ট্যাটাস — ' . config('app.name', 'Modhu'))

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">

      <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 text-center mt-3" style="background: #ffffff;">
        
        @if($order->payment_status === 'failed')
          <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" 
               style="width: 80px; height: 80px; background-color: #fee2e2; color: #dc2626;">
            <i class="bi bi-x-circle-fill fs-1"></i>
          </div>
          <h2 class="fw-bold text-danger mb-2">অনলাইন পেমেন্ট সম্পন্ন হয়নি</h2>
          <p class="text-secondary mb-4">আপনার পেমেন্টটি কোনো কারণে সম্পন্ন হতে পারেনি। আপনি আবার চেষ্টা করতে পারেন অথবা ক্যাশ অন ডেলিভারিতে অর্ডার নিশ্চিত করতে পারেন।</p>
        @elseif($order->payment_status === 'cancelled')
          <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" 
               style="width: 80px; height: 80px; background-color: #fef3c7; color: #d97706;">
            <i class="bi bi-exclamation-triangle-fill fs-1"></i>
          </div>
          <h2 class="fw-bold text-warning mb-2" style="color: #b45309 !important;">অনলাইন পেমেন্ট বাতিল করা হয়েছে</h2>
          <p class="text-secondary mb-4">আপনি পেমেন্ট প্রক্রিয়াটি বাতিল করেছেন। আপনি চাইলে আবার পেমেন্ট করার চেষ্টা করতে পারেন অথবা ক্যাশ অন ডেলিভারি বেছে নিতে পারেন।</p>
        @else
          <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" 
               style="width: 80px; height: 80px; background-color: #e0f2fe; color: #0284c7;">
            <i class="bi bi-clock-history fs-1"></i>
          </div>
          <h2 class="fw-bold text-primary mb-2">অনলাইন পেমেন্ট অপেক্ষমান</h2>
          <p class="text-secondary mb-4">আপনার এই অর্ডারের অনলাইন পেমেন্টটি এখনও সম্পন্ন করা হয়নি। অনুগ্রহ করে পেমেন্ট সম্পন্ন করুন।</p>
        @endif

        @if(session('error'))
          <div class="alert alert-danger border-0 rounded-3 mb-4 text-start">
            <i class="bi bi-exclamation-octagon-fill me-2"></i>{{ session('error') }}
          </div>
        @endif

        @if(session('warning'))
          <div class="alert alert-warning border-0 rounded-3 mb-4 text-start">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
          </div>
        @endif

        @if(session('info'))
          <div class="alert alert-info border-0 rounded-3 mb-4 text-start">
            <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
          </div>
        @endif

        <div class="alert alert-light border rounded-3 p-3 text-start mb-4">
          <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
            <span class="text-muted">অর্ডার নম্বর:</span>
            <span class="fw-bold text-dark fs-5">{{ $order->order_number }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">গ্রাহকের নাম:</span>
            <span class="fw-bold text-dark">{{ $order->customer_name }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">ফোন নম্বর:</span>
            <span class="fw-bold text-dark">{{ $order->customer_phone }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">মোট প্রদেয় পরিমাণ:</span>
            <span class="fw-bold text-success fs-5">৳{{ number_format($order->total_amount, 2) }}</span>
          </div>
          <div class="d-flex justify-content-between">
            <span class="text-muted">পেমেন্ট স্ট্যাটাস:</span>
            @if($order->payment_status === 'failed')
              <span class="badge bg-danger">ব্যর্থ (Failed)</span>
            @elseif($order->payment_status === 'cancelled')
              <span class="badge bg-warning text-dark">বাতিল (Cancelled)</span>
            @else
              <span class="badge bg-secondary">অপরিশোধিত (Unpaid)</span>
            @endif
          </div>
        </div>

        <div class="d-grid gap-3 mb-4">
          <a href="{{ route('orders.pay', $order->id) }}" class="btn btn-warning btn-lg fw-bold rounded-pill text-dark py-3 shadow-sm">
            <i class="bi bi-credit-card-2-front-fill me-2"></i> পুনরায় অনলাইন পেমেন্ট করুন (SSLCommerz)
          </a>

          <form action="{{ route('orders.convert_to_cod', $order->id) }}" method="POST" class="d-grid m-0">
            @csrf
            <button type="submit" class="btn btn-success btn-lg fw-bold rounded-pill py-3 shadow-sm">
              <i class="bi bi-cash-stack me-2"></i> ক্যাশ অন ডেলিভারিতে (COD) অর্ডার কনফার্ম করুন
            </button>
          </form>
        </div>

        <div class="d-flex justify-content-center">
          <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> হোম পেজে ফিরে যান
          </a>
        </div>

        @if(!empty($settings['site_phone']))
          <hr class="my-4">
          <p class="text-muted small mb-0">
            কোনো সমস্যা হলে সরাসরি আমাদের হেল্পলাইনে যোগাযোগ করুন: 
            <a href="tel:{{ $settings['site_phone'] }}" class="text-decoration-none fw-bold text-dark">
              <i class="bi bi-telephone-fill text-success me-1"></i>{{ $settings['site_phone'] }}
            </a>
          </p>
        @endif

      </div>

    </div>
  </div>
</div>
@endsection
