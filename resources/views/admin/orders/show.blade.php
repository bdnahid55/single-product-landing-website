@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)
@section('page_title', 'Order Details: ' . $order->order_number)

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
  <li class="breadcrumb-item active">{{ $order->order_number }}</li>
@endsection

@section('content')
<div class="row">
  <!-- Left Column: Customer & Items -->
  <div class="col-md-8">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user mr-1"></i> Customer Information</h3>
        <div class="card-tools">
          <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-sm btn-secondary">
            <i class="fas fa-print mr-1"></i> Print Invoice
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-sm-6">
            <p class="mb-1 text-muted">Customer Name:</p>
            <h5><strong>{{ $order->customer_name }}</strong></h5>
            <p class="mb-1 text-muted">Phone Number:</p>
            <h5><strong><a href="tel:{{ $order->customer_phone }}" class="text-success">{{ $order->customer_phone }}</a></strong></h5>
          </div>
          <div class="col-sm-6">
            <p class="mb-1 text-muted">Delivery Address:</p>
            <p class="lead mb-2">{{ $order->customer_address }}</p>
            <p class="mb-1 text-muted">Delivery Area:</p>
            <h5>
              @if($order->delivery_area == 'inside_dhaka')
                <span class="badge badge-info">ঢাকার ভিতরে (Inside Dhaka)</span>
              @else
                <span class="badge badge-secondary">ঢাকার বাইরে (Outside Dhaka)</span>
              @endif
            </h5>
          </div>
        </div>

        @if($order->customer_notes)
          <div class="callout callout-info py-2">
            <h6><i class="fas fa-comment-dots mr-1"></i> Customer Note:</h6>
            <p class="mb-0">{{ $order->customer_notes }}</p>
          </div>
        @endif
      </div>
    </div>

    <!-- Ordered Items Table -->
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-boxes mr-1"></i> Ordered Items</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Item</th>
              <th class="text-center">Weight</th>
              <th class="text-center">Unit Price</th>
              <th class="text-center">Quantity</th>
              <th class="text-right">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->items as $item)
              <tr>
                <td><strong>{{ $item->product_name }}</strong></td>
                <td class="text-center">{{ $item->weight ?? '-' }}</td>
                <td class="text-center">৳{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right font-weight-bold">৳{{ number_format($item->subtotal, 2) }}</td>
              </tr>
            @endforeach
            <tr>
              <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
              <td class="text-right">৳{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
              <td colspan="4" class="text-right"><strong>Delivery Charge:</strong></td>
              <td class="text-right">৳{{ number_format($order->delivery_charge, 2) }}</td>
            </tr>
            <tr class="table-success">
              <td colspan="4" class="text-right"><h4><strong>Grand Total:</strong></h4></td>
              <td class="text-right"><h4 class="text-success"><strong>৳{{ number_format($order->total_amount, 2) }}</strong></h4></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Right Column: Status & Admin Controls -->
  <div class="col-md-4">
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-tasks mr-1"></i> Order Status Management</h3>
      </div>
      <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="card-body">
          <div class="form-group">
            <label for="order_status">Order Fulfillment Status</label>
            <select name="order_status" id="order_status" class="form-control">
              <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending (অপেক্ষমাণ)</option>
              <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing / Dispatched</option>
              <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered (সম্পন্ন)</option>
              <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled (বাতিল)</option>
            </select>
          </div>

          <div class="form-group">
            <label for="payment_status">Payment Status</label>
            <select name="payment_status" id="payment_status" class="form-control">
              <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid (বাকি)</option>
              <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid (পরিশোধিত)</option>
            </select>
          </div>

          <div class="form-group">
            <label for="admin_notes">Admin / Courier Note</label>
            <textarea name="admin_notes" id="admin_notes" rows="3" class="form-control" placeholder="e.g. Steadfast Consignment ID, Delivery notes">{{ old('admin_notes', $order->admin_notes) }}</textarea>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-save mr-1"></i> Update Order Status
          </button>
        </div>
      </form>
    </div>

    <!-- Quick Meta Box -->
    <div class="card card-outline card-secondary shadow-sm">
      <div class="card-body">
        <p class="mb-1 text-muted">Placed At:</p>
        <p><strong>{{ $order->created_at->format('F d, Y h:i:s A') }}</strong></p>
        <p class="mb-1 text-muted">Payment Method:</p>
        <p><strong>{{ strtoupper($order->payment_method) }} (Cash on Delivery)</strong></p>
      </div>
    </div>
  </div>
</div>
@endsection
