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

  <!-- Main content -->
  <section class="invoice border p-4 rounded bg-white shadow-sm">
    <!-- title row -->
    <div class="row">
      <div class="col-12 pb-3 mb-3 border-bottom">
        <h2 class="page-header">
          <i class="fas fa-globe text-success"></i> 🍯 খাঁটি সুন্দরবনের মধু
          <small class="float-right text-muted" style="font-size: 1rem;">Date: {{ $order->created_at->format('d/m/Y') }}</small>
        </h2>
      </div>
    </div>

    <!-- info row -->
    <div class="row invoice-info mb-4">
      <div class="col-sm-4 invoice-col">
        From
        <address>
          <strong>খাঁটি মধু (Sundarban Pure Honey)</strong><br>
          Phone: {{ \App\Models\Setting::get('contact_phone', '01700-000000') }}<br>
          WhatsApp: {{ \App\Models\Setting::get('whatsapp_number', '01700-000000') }}<br>
          Email: {{ \App\Models\Setting::get('contact_email', 'info@modhu.com') }}
        </address>
      </div>

      <div class="col-sm-4 invoice-col">
        To (Customer)
        <address>
          <strong>{{ $order->customer_name }}</strong><br>
          {{ $order->customer_address }}<br>
          Phone: <strong>{{ $order->customer_phone }}</strong><br>
          Area: {{ $order->delivery_area == 'inside_dhaka' ? 'ঢাকার ভিতরে (Inside Dhaka)' : 'ঢাকার বাইরে (Outside Dhaka)' }}
        </address>
      </div>

      <div class="col-sm-4 invoice-col">
        <b>Invoice #{{ $order->order_number }}</b><br>
        <br>
        <b>Order Date:</b> {{ $order->created_at->format('d M, Y h:i A') }}<br>
        <b>Payment:</b> {{ strtoupper($order->payment_method) }} ({{ ucfirst($order->payment_status) }})<br>
        <b>Status:</b> {{ ucfirst($order->order_status) }}
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
        <p class="lead">Payment Method:</p>
        <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
          Cash on Delivery (COD). Please collect <strong>৳{{ number_format($order->total_amount, 2) }}</strong> upon parcel delivery.
        </p>
        @if($order->customer_notes)
          <p class="text-muted"><strong>Customer Note:</strong> {{ $order->customer_notes }}</p>
        @endif
      </div>

      <!-- amount column -->
      <div class="col-6">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Subtotal:</th>
              <td class="text-right">৳{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
              <th>Delivery Charge:</th>
              <td class="text-right">৳{{ number_format($order->delivery_charge, 2) }}</td>
            </tr>
            <tr class="bg-light">
              <th class="h5">Total Payable:</th>
              <td class="text-right h5 font-weight-bold text-success">৳{{ number_format($order->total_amount, 2) }}</td>
            </tr>
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
