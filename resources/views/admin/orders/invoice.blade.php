<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice #{{ $order->order_number }} | {{ config('app.name', 'Modhu') }}</title>

  <!-- Favicon -->
  @include('partials.favicon')

  <!-- Google Font: Hind Siliguri (Bengali) & Inter (English) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- AdminLTE Theme style -->
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">

  <style>
    :root {
      --primary-color: #0f766e;
      --primary-light: #f0fdfa;
      --accent-color: #d97706;
      --text-main: #1f2937;
      --text-muted: #6b7280;
      --border-color: #e5e7eb;
    }

    body {
      background-color: #f3f4f6;
      font-family: 'Inter', 'Hind Siliguri', sans-serif;
      color: var(--text-main);
      -webkit-font-smoothing: antialiased;
    }

    .invoice-wrapper {
      max-width: 860px;
      margin: 30px auto;
      padding: 0 15px;
    }

    .invoice-card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
      border: 1px solid #e5e7eb;
      padding: 40px 45px;
      position: relative;
    }

    /* Action bar */
    .action-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .btn-print {
      background: #0f766e;
      color: #ffffff;
      font-weight: 600;
      border-radius: 8px;
      padding: 10px 22px;
      border: none;
      transition: all 0.2s ease;
      box-shadow: 0 4px 10px rgba(15, 118, 110, 0.2);
    }
    .btn-print:hover {
      background: #115e59;
      color: #ffffff;
      transform: translateY(-1px);
    }

    .btn-back {
      background: #ffffff;
      color: #374151;
      border: 1px solid #d1d5db;
      font-weight: 500;
      border-radius: 8px;
      padding: 10px 20px;
      transition: all 0.2s ease;
    }
    .btn-back:hover {
      background: #f9fafb;
      color: #111827;
    }

    /* Invoice Header */
    .invoice-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      padding-bottom: 25px;
      border-bottom: 2px solid #f3f4f6;
      margin-bottom: 25px;
    }

    .brand-logo {
      height: 48px;
      width: 48px;
      object-fit: contain;
      border-radius: 8px;
      margin-right: 12px;
    }

    .brand-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #111827;
      margin: 0;
      line-height: 1.2;
    }

    .brand-tagline {
      font-size: 0.85rem;
      color: #6b7280;
      margin-top: 3px;
    }

    .invoice-title-badge {
      text-align: right;
    }

    .invoice-title-badge h1 {
      font-size: 2rem;
      font-weight: 800;
      color: #0f766e;
      letter-spacing: 1px;
      margin: 0;
      line-height: 1;
    }

    .invoice-number-pill {
      display: inline-block;
      background: #f0fdfa;
      color: #0f766e;
      font-weight: 700;
      font-size: 0.95rem;
      padding: 4px 12px;
      border-radius: 6px;
      border: 1px solid #ccfbf1;
      margin-top: 6px;
    }

    /* Meta Info Grid */
    .invoice-meta-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 20px;
      margin-bottom: 30px;
    }

    .meta-card {
      background: #fafafa;
      border-radius: 8px;
      padding: 16px 18px;
      border: 1px solid #f0f0f0;
    }

    .meta-card-title {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #9ca3af;
      margin-bottom: 8px;
    }

    .meta-card-body {
      font-size: 0.9rem;
      line-height: 1.5;
      color: #1f2937;
    }

    .meta-card-body strong {
      color: #111827;
    }

    /* Paid Stamp */
    .paid-stamp {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border: 2px solid #16a34a;
      background: #dcfce7;
      color: #15803d;
      font-weight: 800;
      font-size: 0.85rem;
      letter-spacing: 1.5px;
      padding: 3px 10px;
      border-radius: 6px;
      text-transform: uppercase;
      margin-top: 4px;
    }

    .unpaid-stamp {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border: 2px solid #d97706;
      background: #fef3c7;
      color: #b45309;
      font-weight: 800;
      font-size: 0.85rem;
      letter-spacing: 1.5px;
      padding: 3px 10px;
      border-radius: 6px;
      text-transform: uppercase;
      margin-top: 4px;
    }

    /* Table styling */
    .invoice-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin-bottom: 25px;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      overflow: hidden;
    }

    .invoice-table thead th {
      background: #f9fafb;
      color: #4b5563;
      font-size: 0.82rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 12px 16px;
      border-bottom: 1px solid #e5e7eb;
      border-top: none;
    }

    .invoice-table tbody td {
      padding: 14px 16px;
      font-size: 0.92rem;
      border-bottom: 1px solid #f3f4f6;
      vertical-align: middle;
      color: #374151;
    }

    .invoice-table tbody tr:last-child td {
      border-bottom: none;
    }

    .invoice-table tbody tr:nth-child(even) {
      background-color: #fafbfc;
    }

    /* Totals & Notes Section */
    .invoice-summary-grid {
      display: grid;
      grid-template-columns: 1.2fr 1fr;
      gap: 30px;
      align-items: flex-start;
      margin-bottom: 30px;
    }

    .payment-instruction-box {
      border-radius: 8px;
      padding: 18px 20px;
    }

    .instruction-paid {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
    }

    .instruction-cod {
      background: #eff6ff;
      border: 1px solid #bfdbfe;
    }

    .instruction-pending {
      background: #fffbeb;
      border: 1px solid #fde68a;
    }

    .totals-table {
      width: 100%;
      border-collapse: collapse;
    }

    .totals-table td, .totals-table th {
      padding: 10px 14px;
      font-size: 0.92rem;
    }

    .totals-table tr.total-row {
      background: #f0fdfa;
      border-top: 2px solid #0f766e;
    }

    .totals-table tr.total-row th,
    .totals-table tr.total-row td {
      font-size: 1.15rem;
      font-weight: 800;
      color: #0f766e;
      padding: 12px 14px;
    }

    .totals-table tr.due-row {
      background: #15803d;
      color: #ffffff;
    }
    .totals-table tr.due-row th,
    .totals-table tr.due-row td {
      color: #ffffff !important;
      font-size: 1.15rem;
      font-weight: 800;
      padding: 12px 14px;
    }

    /* Footer */
    .invoice-footer {
      border-top: 1px solid #f3f4f6;
      margin-top: 30px;
      padding-top: 18px;
      text-align: center;
      font-size: 0.82rem;
      color: #9ca3af;
    }

    /* Print Styles */
    @media print {
      @page {
        size: A4 portrait;
        margin: 12mm 15mm;
      }

      body {
        background: #ffffff !important;
        color: #000000 !important;
        font-size: 12pt;
      }

      .no-print {
        display: none !important;
      }

      .invoice-wrapper {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
      }

      .invoice-card {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        border-radius: 0 !important;
      }

      /* Force background color printing across browsers */
      * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }

      .invoice-table {
        page-break-inside: auto;
      }

      .invoice-table tr {
        page-break-inside: avoid;
        page-break-after: auto;
      }

      .totals-table tr.due-row {
        background: #15803d !important;
        color: #ffffff !important;
      }
    }
  </style>
</head>
<body>

<div class="invoice-wrapper">
  <!-- Top Bar for actions -->
  <div class="action-bar no-print">
    <div>
      <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-back">
        <i class="fas fa-arrow-left mr-1"></i> Back to Order
      </a>
    </div>
    <div class="d-flex align-items-center gap-2">
      <button onclick="window.print();" class="btn-print">
        <i class="fas fa-print mr-1"></i> Print Invoice (A4)
      </button>
    </div>
  </div>

  @php
    $settings = $settings ?? \App\Models\Setting::getAllSettings();
    $siteTitle = !empty($settings['site_title']) ? $settings['site_title'] : (!empty($settings['meta_title']) ? $settings['meta_title'] : '');
    $siteFavicon = !empty($settings['site_favicon'])
      ? (str_starts_with($settings['site_favicon'], 'http') ? $settings['site_favicon'] : asset($settings['site_favicon']))
      : asset('assets/images/favicon.png');
    $isPaidOnline = ($order->payment_method === 'sslcommerz' && $order->payment_status === 'paid');
    $isUnpaidOnline = ($order->payment_method === 'sslcommerz' && $order->payment_status !== 'paid');
    $isCod = ($order->payment_method === 'cod');
  @endphp

  <!-- Main Invoice Card -->
  <div class="invoice-card">

    <!-- Invoice Header -->
    <div class="invoice-header">
      <div class="d-flex align-items-center">
        <img src="{{ $siteFavicon }}" alt="Logo" class="brand-logo">
        <div>
          <h2 class="brand-title">{{ $siteTitle }}</h2>
        </div>
      </div>
      <div class="invoice-title-badge">
        <h1>INVOICE</h1>
        <div class="invoice-number-pill">#{{ $order->order_number }}</div>
      </div>
    </div>

    <!-- Meta Information Cards -->
    <div class="invoice-meta-grid">
      <!-- 1. Billed By -->
      <div class="meta-card">
        <div class="meta-card-title">Billed By (Merchant)</div>
        <div class="meta-card-body">
          <strong>{{ $siteTitle }}</strong><br>
          @if(!empty($settings['contact_phone']))
            <i class="fas fa-phone-alt fa-xs text-muted mr-1"></i> {{ $settings['contact_phone'] }}<br>
          @endif
          @if(!empty($settings['whatsapp_number']))
            <i class="fab fa-whatsapp fa-xs text-muted mr-1"></i> {{ $settings['whatsapp_number'] }}<br>
          @endif
          @if(!empty($settings['contact_email']))
            <i class="fas fa-envelope fa-xs text-muted mr-1"></i> {{ $settings['contact_email'] }}<br>
          @endif
          <i class="fas fa-globe fa-xs text-muted mr-1"></i> {{ url('/') }}
        </div>
      </div>

      <!-- 2. Ship To -->
      <div class="meta-card">
        <div class="meta-card-title">Ship To (Customer)</div>
        <div class="meta-card-body">
          <strong style="font-size: 1rem; color: #111827;">{{ $order->customer_name }}</strong><br>
          <i class="fas fa-phone-alt fa-xs text-muted mr-1"></i> <strong>{{ $order->customer_phone }}</strong><br>
          <i class="fas fa-map-marker-alt fa-xs text-muted mr-1"></i> {{ $order->customer_address }}<br>
          <span class="badge badge-light border mt-1">
            <i class="fas fa-map-pin mr-1 text-primary"></i>
            {{ $order->delivery_area == 'inside_dhaka' ? 'ঢাকার ভিতরে (Inside Dhaka)' : 'ঢাকার বাইরে (Outside Dhaka)' }}
          </span>
        </div>
      </div>

      <!-- 3. Invoice Details -->
      <div class="meta-card">
        <div class="meta-card-title">Order Information</div>
        <div class="meta-card-body">
          <div><span class="text-muted">Order Date:</span> <strong>{{ $order->created_at->format('d M, Y') }}</strong></div>
          <div><span class="text-muted">Order Time:</span> {{ $order->created_at->format('h:i A') }}</div>
          <div class="mt-2">
            @if($isPaidOnline)
              <div class="paid-stamp">
                <i class="fas fa-check-circle"></i> PAID ONLINE
              </div>
            @elseif($isUnpaidOnline)
              <div class="unpaid-stamp">
                <i class="fas fa-clock"></i> UNPAID (ONLINE)
              </div>
            @else
              <span class="badge badge-secondary px-2 py-1 font-weight-bold">
                <i class="fas fa-truck mr-1"></i> CASH ON DELIVERY
              </span>
            @endif
          </div>
          <div class="mt-1 small text-muted">
            Status: <span class="badge badge-light border text-capitalize font-weight-bold">{{ $order->order_status }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Items Table -->
    <table class="invoice-table">
      <thead>
        <tr>
          <th style="width: 50px; text-align: center;">SL</th>
          <th>Product Description</th>
          <th style="width: 150px; text-align: center;">Variant / Weight</th>
          <th style="width: 80px; text-align: center;">Qty</th>
          <th style="width: 140px; text-align: right;">Unit Price</th>
          <th style="width: 140px; text-align: right;">Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $idx => $item)
          <tr>
            <td style="text-align: center; font-weight: 600; color: #6b7280;">{{ sprintf('%02d', $idx + 1) }}</td>
            <td>
              <div class="font-weight-bold" style="color: #111827;">{{ $item->product_name }}</div>
              <small class="text-muted">১০০% প্রাকৃতিক ও খাঁটি সুন্দরবনের মধু</small>
            </td>
            <td style="text-align: center;">
              <span class="badge badge-light border px-2 py-1">{{ $item->weight ?: 'Standard' }}</span>
            </td>
            <td style="text-align: center; font-weight: 700; font-size: 1rem;">{{ $item->quantity }}</td>
            <td style="text-align: right; font-weight: 500;">৳{{ number_format($item->unit_price, 2) }}</td>
            <td style="text-align: right; font-weight: 700; color: #111827;">৳{{ number_format($item->subtotal, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Bottom Summary & Instructions -->
    <div class="invoice-summary-grid">

      <!-- Left: Instructions for Delivery & Courier -->
      <div>
        <div class="meta-card-title mb-2">Payment & Delivery Instruction</div>

        @if($isPaidOnline)
          <div class="payment-instruction-box instruction-paid">
            <div class="d-flex align-items-center text-success mb-2">
              <i class="fas fa-check-circle fa-lg mr-2"></i>
              <strong style="font-size: 1.05rem;">Online Payment Received (SSLCommerz)</strong>
            </div>
            <p class="text-dark mb-0" style="font-size: 0.95rem; line-height: 1.5;">
              This parcel has already been <strong>PAID IN FULL</strong>.<br>
              <span class="text-danger font-weight-bold" style="font-size: 1rem;">
                <i class="fas fa-exclamation-triangle mr-1"></i> Do NOT collect any cash from customer upon delivery.
              </span>
            </p>
          </div>
        @elseif($isUnpaidOnline)
          <div class="payment-instruction-box instruction-pending">
            <div class="d-flex align-items-center text-warning mb-2" style="color: #b45309 !important;">
              <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
              <strong style="font-size: 1.05rem;">Online Payment Pending / Unpaid</strong>
            </div>
            <p class="text-dark mb-0" style="font-size: 0.95rem; line-height: 1.5;">
              Customer initiated online payment but it was not completed. If dispatching as COD, please collect <strong>৳{{ number_format($order->total_amount, 2) }}</strong> upon parcel delivery.
            </p>
          </div>
        @else
          <div class="payment-instruction-box instruction-cod">
            <div class="d-flex align-items-center text-primary mb-2">
              <i class="fas fa-hand-holding-usd fa-lg mr-2"></i>
              <strong style="font-size: 1.05rem;" class="text-dark">Cash on Delivery (COD)</strong>
            </div>
            <p class="text-dark mb-0" style="font-size: 0.95rem; line-height: 1.5;">
              Please collect <strong class="text-primary" style="font-size: 1.05rem;">৳{{ number_format($order->total_amount, 2) }}</strong> in cash from the customer upon parcel delivery.
            </p>
          </div>
        @endif

        @if($order->customer_notes)
          <div class="mt-3 p-3 bg-light rounded border">
            <strong class="d-block text-dark small mb-1"><i class="fas fa-comment-alt mr-1 text-muted"></i> Customer Special Note:</strong>
            <span style="font-size: 0.9rem; color: #4b5563;">{{ $order->customer_notes }}</span>
          </div>
        @endif
      </div>

      <!-- Right: Financial Breakdown -->
      <div>
        <table class="totals-table border rounded">
          <tr>
            <td class="text-muted">Items Subtotal:</td>
            <td class="text-right font-weight-bold">৳{{ number_format($order->subtotal, 2) }}</td>
          </tr>
          <tr>
            <td class="text-muted">Delivery Charge:</td>
            <td class="text-right font-weight-bold">৳{{ number_format($order->delivery_charge, 2) }}</td>
          </tr>
          <tr class="border-top">
            <td class="font-weight-bold" style="font-size: 1rem; color: #111827;">Total Order Amount:</td>
            <td class="text-right font-weight-bold" style="font-size: 1.05rem; color: #111827;">৳{{ number_format($order->total_amount, 2) }}</td>
          </tr>

          @if($isPaidOnline)
            <tr class="text-success bg-light">
              <td>Paid Online (SSLCommerz):</td>
              <td class="text-right font-weight-bold">-৳{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            <tr class="due-row">
              <th>Total Due / Collect:</th>
              <td class="text-right">৳0.00</td>
            </tr>
          @else
            <tr class="text-muted">
              <td>Paid Amount:</td>
              <td class="text-right">৳0.00</td>
            </tr>
            <tr class="total-row">
              <th>Total to Collect (COD):</th>
              <td class="text-right">৳{{ number_format($order->total_amount, 2) }}</td>
            </tr>
          @endif
        </table>
      </div>

    </div>

    <!-- Footer Note -->
    <div class="invoice-footer">
      <div>ধন্যবাদ আমাদের সাথে থাকার জন্য</div>
      <div class="mt-1" style="font-size: 0.75rem;">
        Generated on {{ now()->format('d M, Y h:i A') }} • Powered by {{ config('app.name', '') }}
      </div>
    </div>

  </div>
</div>

<script>
  window.addEventListener("load", function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('auto') === '1') {
      window.print();
    }
  });
</script>
</body>
</html>
