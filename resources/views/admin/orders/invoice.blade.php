<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice #{{ $order->order_number }} | মধু Admin</title>

  <!-- Favicon -->
  @include('partials.favicon')

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
  <style>
    @media print {
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body>
<div class="wrapper p-4">
  <!-- Top Bar for actions -->
  <div class="row no-print mb-4">
    <div class="col-12 text-right">
      <button onclick="window.print();" class="btn btn-primary"><i class="fas fa-print mr-1"></i> Print Invoice</button>
      <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-default ml-2">Back to Order</a>
    </div>
  </div>

  @php
    $settings = $settings ?? \App\Models\Setting::getAllSettings();
    $seoTitle = !empty($settings['meta_title']) ? $settings['meta_title'] : (!empty($settings['site_title']) ? $settings['site_title'] : '');
    $siteFavicon = !empty($settings['site_favicon'])
      ? (str_starts_with($settings['site_favicon'], 'http') ? $settings['site_favicon'] : asset($settings['site_favicon']))
      : asset('assets/images/favicon.png');
  @endphp

  <!-- Main content -->
  <section class="invoice border p-4 rounded bg-white shadow-sm">
    <!-- title row -->
    <div class="row">
      <div class="col-12 pb-3 mb-3 border-bottom d-flex justify-content-between align-items-center">
        <h2 class="page-header m-0 d-inline-flex align-items-center" style="font-size: 1.6rem; font-weight: 700;">
          <img src="{{ $siteFavicon }}" alt="Logo" style="height: 38px; width: 38px; object-fit: contain; margin-right: 12px; border-radius: 6px; vertical-align: middle;">
          <span>{{ $seoTitle }}</span>
        </h2>
        <span class="text-muted" style="font-size: 1rem;">Date: {{ $order->created_at->format('d/m/Y') }}</span>
      </div>
    </div>

    <!-- info row -->
    <div class="row invoice-info mb-4">
      <div class="col-sm-4 invoice-col">
        From
        <address>
          <strong>{{ $settings['meta_title'] ?? '' }}</strong><br>
          <strong>Phone:</strong> {{ \App\Models\Setting::get('contact_phone', '') }}<br>
          <strong>WhatsApp:</strong> {{ \App\Models\Setting::get('whatsapp_number', '') }}<br>
          <strong>Email:</strong> {{ \App\Models\Setting::get('contact_email', '') }}
        </address>
      </div>

      <div class="col-sm-4 invoice-col">
        To (Customer)
        <address>
          <strong>Name: </strong>{{ $order->customer_name }}<br>
          <strong>Address: </strong>{{ $order->customer_address }}<br>
          <strong>Phone: </strong>{{ $order->customer_phone }}<br>
          <strong>Area: </strong>{{ $order->delivery_area == 'inside_dhaka' ? 'ঢাকার ভিতরে (Inside Dhaka)' : 'ঢাকার বাইরে (Outside Dhaka)' }}
        </address>
      </div>

      <div class="col-sm-4 invoice-col">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <b>Invoice #{{ $order->order_number }}</b><br>
            <b>Order Date:</b> {{ $order->created_at->format('d M, Y h:i A') }}<br>
            <b>Payment:</b> 
            @if($order->payment_method === 'sslcommerz')
              <span class="badge {{ $order->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }} px-2 py-1">
                ONLINE (SSLCOMMERZ) - {{ strtoupper($order->payment_status) }}
              </span>
            @else
              <span class="badge badge-secondary px-2 py-1">CASH ON DELIVERY (COD)</span>
            @endif
            <br>
            <b>Status:</b> <span class="badge badge-light border text-capitalize">{{ $order->order_status }}</span>
          </div>
          @if($order->payment_status === 'paid')
            <div class="text-center ml-2 border border-success rounded p-2" style="border: 2px solid #28a745 !important; background-color: #f6ffed;">
              <span class="text-success font-weight-bold d-block" style="font-size: 1.1rem; letter-spacing: 2px;">PAID</span>
              <small class="text-success d-block" style="font-size: 0.75rem;">পরিশোধিত</small>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Table row -->
    <div class="row mb-4">
      <div class="col-12 table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="thead-light">
          <tr>
            <th style="width: 10px;">#</th>
            <th>Product</th>
            <th class="text-center">Weight / Variant</th>
            <th class="text-center">Qty</th>
            <th class="text-right">Unit Price</th>
            <th class="text-right">Subtotal</th>
          </tr>
          </thead>
          <tbody>
          @foreach($order->items as $idx => $item)
            <tr>
              <td>{{ $idx + 1 }}</td>
              <td><strong>{{ $item->product_name }}</strong></td>
              <td class="text-center">{{ $item->weight ?? '-' }}</td>
              <td class="text-center">{{ $item->quantity }}</td>
              <td class="text-right">৳{{ number_format($item->unit_price, 2) }}</td>
              <td class="text-right">৳{{ number_format($item->subtotal, 2) }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-6">
        <p class="lead font-weight-bold mb-2">Payment Details:</p>
        
        @if($order->payment_method === 'sslcommerz' && $order->payment_status === 'paid')
          <div class="p-3 rounded border border-success" style="background-color: #f0fdf4;">
            <div class="d-flex align-items-center text-success mb-2">
              <i class="fas fa-check-circle fa-lg mr-2"></i>
              <strong style="font-size: 1.05rem;">Online Payment Received (SSLCommerz)</strong>
            </div>
            <p class="text-dark mb-0" style="font-size: 0.95rem;">
              This parcel has already been <strong>PAID IN FULL</strong>.<br>
              <span class="text-danger font-weight-bold"><i class="fas fa-exclamation-circle mr-1"></i> Do NOT collect any cash from the customer upon delivery.</span>
            </p>
          </div>
        @elseif($order->payment_method === 'sslcommerz' && $order->payment_status !== 'paid')
          <div class="p-3 rounded border border-warning" style="background-color: #fffbeb;">
            <div class="d-flex align-items-center text-warning mb-2" style="color: #b45309 !important;">
              <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
              <strong style="font-size: 1.05rem;">Online Payment Pending (Unpaid)</strong>
            </div>
            <p class="text-dark mb-0" style="font-size: 0.95rem;">
              The customer selected SSLCommerz but online payment was not completed. If fulfilling as Cash on Delivery, please collect <strong>৳{{ number_format($order->total_amount, 2) }}</strong> upon parcel delivery.
            </p>
          </div>
        @else
          <div class="p-3 rounded border bg-light">
            <div class="d-flex align-items-center text-secondary mb-2">
              <i class="fas fa-truck fa-lg mr-2 text-primary"></i>
              <strong style="font-size: 1.05rem;" class="text-dark">Cash on Delivery (COD)</strong>
            </div>
            <p class="text-dark mb-0" style="font-size: 0.95rem;">
              Please collect <strong>৳{{ number_format($order->total_amount, 2) }}</strong> in cash from the customer upon parcel delivery.
            </p>
          </div>
        @endif

        @if($order->customer_notes)
          <div class="mt-3 p-2 bg-light rounded border small">
            <strong>Customer Note:</strong> {{ $order->customer_notes }}
          </div>
        @endif
      </div>

      <!-- amount column -->
      <div class="col-6">
        <div class="table-responsive">
          <table class="table mb-0">
            <tr>
              <th style="width:55%">Subtotal:</th>
              <td class="text-right">৳{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
              <th>Delivery Charge ({{ $order->delivery_area == 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }}):</th>
              <td class="text-right">৳{{ number_format($order->delivery_charge, 2) }}</td>
            </tr>
            <tr class="border-top">
              <th class="font-weight-bold">Total Amount:</th>
              <td class="text-right font-weight-bold">৳{{ number_format($order->total_amount, 2) }}</td>
            </tr>

            @if($order->payment_method === 'sslcommerz' && $order->payment_status === 'paid')
              <tr class="text-success" style="background-color: #f0fdf4;">
                <th class="align-middle">Paid (Online - SSLCommerz):</th>
                <td class="text-right font-weight-bold align-middle">-৳{{ number_format($order->total_amount, 2) }}</td>
              </tr>
              <tr class="bg-success text-white">
                <th class="h5 m-0 align-middle font-weight-bold">Due / Collect from Customer:</th>
                <td class="text-right h5 m-0 font-weight-bold align-middle">৳0.00</td>
              </tr>
            @else
              <tr class="text-muted">
                <th>Paid Amount:</th>
                <td class="text-right">৳0.00</td>
              </tr>
              <tr class="bg-light">
                <th class="h5 m-0 align-middle font-weight-bold">Total Payable (COD):</th>
                <td class="text-right h5 m-0 font-weight-bold text-danger align-middle">৳{{ number_format($order->total_amount, 2) }}</td>
              </tr>
            @endif
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  window.addEventListener("load", function() {
    // Optionally trigger print automatically if url parameter print=1
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('auto') === '1') {
      window.print();
    }
  });
</script>
</body>
</html>
