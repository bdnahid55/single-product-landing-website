@extends('layouts.app')

@section('title', 'অর্ডার সফল হয়েছে! — ' . config('app.name', 'Modhu'))

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
      
      <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 text-center mt-4" style="background: #ffffff;">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" 
             style="width: 80px; height: 80px; background-color: #dcfce7; color: #16a34a;">
          <i class="bi bi-check-circle-fill fs-1"></i>
        </div>

        <h2 class="fw-bold text-success mb-2">ধন্যবাদ! আপনার অর্ডারটি গৃহীত হয়েছে</h2>
        <p class="text-secondary mb-4">আমাদের প্রতিনিধি শীঘ্রই আপনার সাথে ফোনে যোগাযোগ করে অর্ডারটি কনফার্ম করবেন।</p>

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
            <span class="text-muted">ডেলিভারি ঠিকানা:</span>
            <span class="fw-bold text-dark text-end" style="max-width: 60%;">{{ $order->customer_address }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">পেমেন্ট মেথড:</span>
            @if($order->payment_method === 'sslcommerz')
              <span class="badge bg-success">
                <i class="bi bi-check-circle-fill me-1"></i> অনলাইন পেমেন্ট (SSLCommerz) - পরিশোধিত (Paid)
              </span>
            @else
              <span class="badge bg-secondary">
                <i class="bi bi-cash me-1"></i> ক্যাশ অন ডেলিভারি (COD) - ডেলিভারির সময় প্রদেয়
              </span>
            @endif
          </div>
          @if($order->transaction_id)
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">ট্রানজেকশন আইডি:</span>
            <span class="fw-bold text-success font-monospace">{{ $order->transaction_id }}</span>
          </div>
          @endif
          @if($order->card_type)
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">পেমেন্ট মাধ্যম / কার্ড:</span>
            <span class="fw-bold text-dark">{{ $order->card_type }}</span>
          </div>
          @endif
        </div>

        <div class="table-responsive mb-4">
          <table class="table table-bordered text-start align-middle">
            <thead class="table-light">
              <tr>
                <th>পণ্য</th>
                <th class="text-center">পরিমাণ</th>
                <th class="text-end">মূল্য</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->items as $item)
                <tr>
                  <td>
                    <strong>{{ $item->product_name }}</strong>
                    @if($item->weight)
                      <span class="badge bg-light text-muted ms-1">{{ $item->weight }}</span>
                    @endif
                  </td>
                  <td class="text-center">{{ $item->quantity }}</td>
                  <td class="text-end">৳{{ number_format($item->subtotal, 2) }}</td>
                </tr>
              @endforeach
              <tr>
                <td colspan="2" class="text-end fw-bold">সাবটোটাল:</td>
                <td class="text-end fw-bold">৳{{ number_format($order->subtotal, 2) }}</td>
              </tr>
              <tr>
                <td colspan="2" class="text-end text-muted">ডেলিভারি চার্জ ({{ $order->delivery_area == 'inside_dhaka' ? 'ঢাকার ভিতরে' : 'ঢাকার বাইরে' }}):</td>
                <td class="text-end">৳{{ number_format($order->delivery_charge, 2) }}</td>
              </tr>
              <tr class="table-success">
                <td colspan="2" class="text-end fw-bolder fs-5 text-success">সর্বমোট প্রদেয়:</td>
                <td class="text-end fw-bolder fs-5 text-success">৳{{ number_format($order->total_amount, 2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="d-flex flex-wrap gap-2 justify-content-center">
          <a href="{{ route('order.track', ['order_number' => $order->order_number, 'phone' => $order->customer_phone]) }}" class="btn btn-success rounded-pill px-4 py-2">
            <i class="bi bi-geo-alt me-1"></i> অর্ডার ট্র্যাক করুন
          </a>
          <a href="{{ route('home') }}" class="btn btn-outline-success rounded-pill px-4 py-2">
            <i class="bi bi-arrow-left me-1"></i> আরও অর্ডার করুন
          </a>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
