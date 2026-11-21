@extends('layouts.admin')

@section('title', 'Products')
@section('page_title', 'Product Management')

@section('breadcrumb')
  <li class="breadcrumb-item active">Products</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header">
        <h3 class="card-title">All Honey Products</h3>
        <div class="card-tools">
          <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus mr-1"></i> Add New Product
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="productsTable">
            <thead>
              <tr>
                <th style="width: 60px;">Image</th>
                <th>Product Name</th>
                <th>Weight</th>
                <th>Regular Price</th>
                <th>Sale Price</th>
                <th>Stock</th>
                <th>Featured</th>
                <th>Status</th>
                <th style="width: 120px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($products as $product)
                <tr>
                  <td class="text-center">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="height: 45px; width: 45px; object-fit: contain; border-radius: 6px; border: 1px solid #ddd;">
                  </td>
                  <td>
                    <strong>{{ $product->name }}</strong>
                    @if($product->badge)
                      <span class="badge badge-warning ml-1">{{ $product->badge }}</span>
                    @endif
                  </td>
                  <td>{{ $product->weight ?? 'N/A' }}</td>
                  <td><del>৳{{ number_format($product->regular_price, 2) }}</del></td>
                  <td><strong class="text-success">৳{{ number_format($product->sale_price, 2) }}</strong></td>
                  <td>
                    @if($product->stock > 10)
                      <span class="badge badge-success">{{ $product->stock }} in stock</span>
                    @elseif($product->stock > 0)
                      <span class="badge badge-warning">{{ $product->stock }} left</span>
                    @else
                      <span class="badge badge-danger">Out of Stock</span>
                    @endif
                  </td>
                  <td>
                    @if($product->is_featured)
                      <span class="badge badge-primary">Featured</span>
                    @else
                      <span class="text-muted">No</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <form action="{{ route('admin.products.toggle-status', $product->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="btn btn-xs {{ $product->is_active ? 'btn-success' : 'btn-secondary' }}" title="Click to {{ $product->is_active ? 'Unpublish/Deactivate' : 'Publish/Activate' }}">
                        <i class="fas {{ $product->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                      </button>
                    </form>
                  </td>
                  <td>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-info" title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
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
    $("#productsTable").DataTable({
      "responsive": true,
      "autoWidth": false,
      "pageLength": 10
    });
  });
</script>
@endpush
