@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('breadcrumb')
  <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat card) -->
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $totalOrders }}</h3>
        <p>Total Orders</p>
      </div>
      <div class="icon">
        <i class="fas fa-shopping-cart"></i>
      </div>
      <a href="{{ route('admin.orders.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $pendingOrders }}</h3>
        <p>Pending Orders</p>
      </div>
      <div class="icon">
        <i class="fas fa-clock"></i>
      </div>
      <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="small-box-footer">View Pending <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>৳{{ number_format($totalRevenue, 0) }}</h3>
        <p>Total Completed Sales</p>
      </div>
      <div class="icon">
        <i class="fas fa-chart-line"></i>
      </div>
      <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $totalProducts }}</h3>
        <p>Active Products</p>
      </div>
      <div class="icon">
        <i class="fas fa-box-open"></i>
      </div>
      <a href="{{ route('admin.products.index') }}" class="small-box-footer">Manage Products <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>

<!-- Section Control Banner -->
<div class="row mb-3">
  <div class="col-12">
    <div class="alert alert-light border shadow-sm d-flex flex-wrap justify-content-between align-items-center mb-0 py-2">
      <div class="mb-2 mb-md-0">
        <i class="fas fa-sliders-h text-warning mr-2 fa-lg"></i>
        <strong>ওয়েবসাইট সেকশন অন/অফ কন্ট্রোল:</strong> হোমপেজের যেকোনো সেকশন (হিরো, অফার, ভিডিও, গ্যালারি, রিভিউ, অর্ডার ফর্ম ইত্যাদি) ইচ্ছেমতো অন অথবা অফ করতে পারবেন।
      </div>
      <a href="{{ route('admin.sections.index') }}" class="btn btn-sm btn-warning font-weight-bold">
        <i class="fas fa-toggle-on mr-1"></i> সেকশন কন্ট্রোল প্যানেল
      </a>
    </div>
  </div>
</div>

<!-- Main row -->
<div class="row">
  <div class="col-12">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header border-transparent">
        <h3 class="card-title"><i class="fas fa-list mr-1"></i> Latest Customer Orders</h3>
        <div class="card-tools">
          <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-success">View All Orders</a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table m-0 table-striped">
            <thead>
              <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Area</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentOrders as $order)
                <tr>
                  <td><a href="{{ route('admin.orders.show', $order->id) }}" class="font-weight-bold text-success">{{ $order->order_number }}</a></td>
                  <td>{{ $order->customer_name }}</td>
                  <td>{{ $order->customer_phone }}</td>
                  <td>
                    @if($order->delivery_area == 'inside_dhaka')
                      <span class="badge badge-info">Inside Dhaka</span>
                    @else
                      <span class="badge badge-secondary">Outside Dhaka</span>
                    @endif
                  </td>
                  <td><strong>৳{{ number_format($order->total_amount, 2) }}</strong></td>
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
                  <td>{{ $order->created_at->timezone('Asia/Dhaka')->format('d M, Y h:i A') }}</td>
                  <td>
                    <div class="btn-group btn-group-xs">
                      <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-xs btn-info" title="View Details">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-xs btn-secondary" title="Print Invoice">
                        <i class="fas fa-print"></i>
                      </a>
                      <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই অর্ডারটি (#{{ $order->order_number }}) সম্পূর্ণ ডিলিট করতে চান?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-xs btn-danger" title="Delete Order">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-4 text-muted">No orders found yet. Place a test order from the website!</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
