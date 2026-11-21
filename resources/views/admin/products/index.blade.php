@extends('layouts.admin')

@section('title', 'Products')
@section('page_title', 'Product Management')

@section('breadcrumb')
  <li class="breadcrumb-item active">Products</li>
@endsection

@section('content')
<!-- Website Selling Mode Card (Single Product vs Multiple Products) -->
<div class="row mb-3">
  <div class="col-12">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header bg-white py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
          <h3 class="card-title font-weight-bold text-dark mb-0">
            <i class="fas fa-store text-success mr-2"></i> ওয়েবসাইট বিক্রয় মোড (Website Selling Mode)
          </h3>
          <span class="badge {{ $storeMode === 'single' ? 'badge-primary' : 'badge-success' }} px-3 py-2 font-weight-bold">
            {{ $storeMode === 'single' ? '🎯 Single Product Mode (একক পণ্য মোড সক্রিয়)' : '🛒 Multi-Product Mode (একাধিক পণ্য / প্যাকেজ মোড সক্রিয়)' }}
          </span>
        </div>
      </div>
      <div class="card-body bg-light">
        <form action="{{ route('admin.products.update-mode') }}" method="POST">
          @csrf
          @method('PUT')
          <div class="row align-items-center">
            <div class="col-lg-5 col-md-6 mb-3 mb-lg-0">
              <label class="font-weight-bold text-dark d-block mb-2">বিক্রয় মোড নির্বাচন করুন:</label>
              <div class="custom-control custom-radio custom-control-inline mb-2">
                <input type="radio" id="mode_multiple" name="store_mode" value="multiple" class="custom-control-input" {{ $storeMode === 'multiple' ? 'checked' : '' }}>
                <label class="custom-control-label font-weight-bold" for="mode_multiple">
                  🛒 Multi-Product Mode (একাধিক পণ্য / প্যাকেজ গ্রিড)
                </label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="mode_single" name="store_mode" value="single" class="custom-control-input" {{ $storeMode === 'single' ? 'checked' : '' }}>
                <label class="custom-control-label font-weight-bold" for="mode_single">
                  🎯 Single Product Mode (একটিমাত্র পণ্য ফোকাস)
                </label>
              </div>
              <small class="text-muted d-block mt-1">
                <i class="fas fa-info-circle mr-1"></i> <strong>Single Product:</strong> শুধুমাত্র নির্বাচিত পণ্যটি ফোকাস থাকবে, অন্য পণ্যের গ্রিড লুকানো থাকবে।<br>
                <i class="fas fa-info-circle mr-1"></i> <strong>Multi-Product:</strong> সকল প্যাকেজের তালিকা থাকবে এবং চেকআউটেও প্যাকেজ সুইচ করা যাবে।
              </small>
            </div>

            <div class="col-lg-4 col-md-6 mb-3 mb-lg-0" id="singleProductSelectCol" style="{{ $storeMode === 'single' ? '' : 'display: none;' }}">
              <label class="font-weight-bold text-dark d-block mb-2">একক পণ্য হিসেবে কোনটি বিক্রি করবেন?</label>
              <select name="single_product_id" class="form-control font-weight-bold">
                @foreach($allProducts as $p)
                  <option value="{{ $p->id }}" {{ $singleProductId == $p->id ? 'selected' : '' }}>
                    {{ $p->name }} (৳{{ number_format($p->sale_price, 0) }}) {{ $p->is_active ? '— [Active]' : '— [Inactive]' }}
                  </option>
                @endforeach
              </select>
              <small class="text-muted">হোমপেজের হিরো ও অর্ডার ফর্ম সরাসরি এই পণ্যটি নিয়েই লোড হবে।</small>
            </div>

            <div class="col-lg-3 col-md-12 text-lg-right">
              <button type="submit" class="btn btn-success font-weight-bold px-4 shadow-sm">
                <i class="fas fa-save mr-1"></i> মোড সংরক্ষণ করুন
              </button>
            </div>
          </div>

          <!-- Multi-Product Mode: Homepage Packages Grid Display Controls -->
          <div id="multiProductOptionsBox" class="mt-3 p-3 bg-white rounded border shadow-sm" style="{{ $storeMode === 'multiple' ? '' : 'display: none;' }}">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
              <label class="font-weight-bold text-dark mb-0">
                <i class="fas fa-th-large text-success mr-2"></i> হোমপেজে 'মধু প্যাকেজ গ্রিড' সেকশন প্রদর্শন অপশন:
              </label>
              <span class="badge badge-success px-2 py-1">Packages Grid Option</span>
            </div>
            <p class="text-muted small mb-3">
              মাল্টি-প্রোডাক্ট মোড সক্রিয় থাকা অবস্থায় হোমপেজের <strong>"All Available Honey Packages Grid"</strong> সেকশনে সকল সক্রিয় পণ্য দেখাবেন কি না তা নির্ধারণ করুন:
            </p>

            <div class="d-flex flex-wrap align-items-center mb-2">
              <div class="custom-control custom-radio mr-4 mb-2">
                <input type="radio" id="grid_mode_all" name="show_packages_grid" value="all" class="custom-control-input" {{ ($showPackagesGrid ?? 'all') === 'all' ? 'checked' : '' }}>
                <label class="custom-control-label font-weight-bold text-success" for="grid_mode_all">
                  <i class="fas fa-check-circle mr-1"></i> হ্যাঁ, সকল সক্রিয় পণ্য প্যাকেজ গ্রিডে দেখান (Show All Active Products)
                </label>
              </div>

              <div class="custom-control custom-radio mr-4 mb-2">
                <input type="radio" id="grid_mode_none" name="show_packages_grid" value="none" class="custom-control-input" {{ ($showPackagesGrid ?? 'all') === 'none' ? 'checked' : '' }}>
                <label class="custom-control-label font-weight-bold text-danger" for="grid_mode_none">
                  <i class="fas fa-eye-slash mr-1"></i> না, প্যাকেজ গ্রিড সম্পূর্ণ লুকান (Hide Packages Grid from Homepage)
                </label>
              </div>

              <div class="custom-control custom-radio mb-2">
                <input type="radio" id="grid_mode_selected" name="show_packages_grid" value="selected" class="custom-control-input" {{ ($showPackagesGrid ?? 'all') === 'selected' ? 'checked' : '' }}>
                <label class="custom-control-label font-weight-bold text-primary" for="grid_mode_selected">
                  <i class="fas fa-filter mr-1"></i> নির্দিষ্ট কিছু পণ্য নির্বাচন করুন (Select Specific Products)
                </label>
              </div>
            </div>

            <!-- Specific Products Checklist (shown only if 'selected' chosen) -->
            <div id="selectedGridProductsContainer" class="mt-2 pt-2 border-top" style="{{ ($showPackagesGrid ?? 'all') === 'selected' ? '' : 'display: none;' }}">
              <small class="font-weight-bold text-dark d-block mb-2">
                হোমপেজ প্যাকেজ গ্রিডে যে যে পণ্য দেখাতে চান টিক দিন:
              </small>
              <div class="row">
                @foreach($allProducts as $p)
                  <div class="col-md-4 col-sm-6 mb-2">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="grid_product_ids[]" value="{{ $p->id }}" id="grid_prod_{{ $p->id }}" class="custom-control-input" {{ in_array($p->id, $selectedGridProductIds ?? []) ? 'checked' : '' }}>
                      <label class="custom-control-label font-weight-bold text-dark" for="grid_prod_{{ $p->id }}">
                        {{ $p->name }} <span class="text-success">(৳{{ number_format($p->sale_price, 0) }})</span>
                        @if(!$p->is_active)
                          <span class="badge badge-secondary ml-1">Inactive</span>
                        @endif
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <small class="text-muted d-block mt-2">
              <i class="fas fa-lightbulb text-warning mr-1"></i> <strong>টিপ:</strong> গ্রিড বন্ধ রাখলেও গ্রাহক সরাসরি অর্ডার ফর্মের মাল্টি-কার্ট সিলেকশন থেকে যেকোনো প্যাকেজ নির্বাচন করে একই সাথে অর্ডার করতে পারবেন।
            </small>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header">
        <h3 class="card-title font-weight-bold text-dark">All Honey Products (পণ্য তালিকা)</h3>
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

    // Toggle Selling Mode Sub-Options
    $('input[name="store_mode"]').on('change', function () {
      if ($(this).val() === 'single') {
        $('#singleProductSelectCol').slideDown();
        $('#multiProductOptionsBox').slideUp();
      } else {
        $('#singleProductSelectCol').slideUp();
        $('#multiProductOptionsBox').slideDown();
      }
    });

    // Toggle specific products checklist when grid mode is 'selected'
    $('input[name="show_packages_grid"]').on('change', function () {
      if ($(this).val() === 'selected') {
        $('#selectedGridProductsContainer').slideDown();
      } else {
        $('#selectedGridProductsContainer').slideUp();
      }
    });
  });
</script>
@endpush
