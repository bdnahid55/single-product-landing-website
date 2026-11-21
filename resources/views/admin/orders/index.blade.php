@extends('layouts.admin')

@section('title', 'Orders')
@section('page_title', 'Customer Orders')

@section('breadcrumb')
  <li class="breadcrumb-item active">Orders</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <!-- Status Filter Buttons -->
    <div class="mb-3">
      <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-dark' }}">All Orders</a>
      <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">Pending</a>
      <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="btn btn-sm {{ request('status') == 'processing' ? 'btn-primary' : 'btn-outline-primary' }}">Processing</a>
      <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="btn btn-sm {{ request('status') == 'delivered' ? 'btn-success' : 'btn-outline-success' }}">Delivered</a>
      <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') == 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">Cancelled</a>
    </div>

    <div class="card card-outline card-success shadow-sm">
      <div class="card-header">
        <h3 class="card-title">Order Records</h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="ordersTable">
            <thead>
              <tr>
                <th>Order #</th>
                <th>তারিখ ও সময় (BST)</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Delivery Area</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Order Status</th>
                <th style="width: 120px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $order)
                <tr>
                  <td>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="font-weight-bold text-success">
                      {{ $order->order_number }}
                    </a>
                  </td>
                  <td>{{ $order->created_at->timezone('Asia/Dhaka')->format('d M, Y h:i A') }}</td>
                  <td><strong>{{ $order->customer_name }}</strong></td>
                  <td><a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a></td>
                  <td>
                    @if($order->delivery_area == 'inside_dhaka')
                      <span class="badge badge-info">Inside Dhaka</span>
                    @else
                      <span class="badge badge-secondary">Outside Dhaka</span>
                    @endif
                  </td>
                  <td><strong>৳{{ number_format($order->total_amount, 2) }}</strong></td>
                  <td>
                    <span class="badge {{ $order->payment_status == 'paid' ? 'badge-success' : 'badge-light border' }}">
                      {{ strtoupper($order->payment_method) }} ({{ ucfirst($order->payment_status) }})
                    </span>
                  </td>
                  <td>
                    @if($order->order_status == 'pending')
                      <span class="badge badge-warning">Pending</span>
                    @elseif($order->order_status == 'processing')
                      <span class="badge badge-primary">Processing</span>
                    @elseif($order->order_status == 'delivered')
                      <span class="badge badge-success">Delivered</span>
                    @else
                      <span class="badge badge-danger">Cancelled</span>
                    @endif
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm">
                      <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info" title="View & Manage">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-secondary" title="Print Invoice">
                        <i class="fas fa-print"></i>
                      </a>
                      <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই অর্ডারটি (#{{ $order->order_number }}) সম্পূর্ণ ডিলিট করতে চান?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" title="Delete Order">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(function () {
    $("#ordersTable").DataTable({
      "responsive": true,
      "autoWidth": false,
      "order": [[1, "desc"]],
      "pageLength": 15
    });
  });
</script>
@endpush
