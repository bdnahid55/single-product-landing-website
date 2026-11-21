@extends('layouts.admin')

@section('title', 'Edit Product')
@section('page_title', 'Edit Product: ' . $product->name)

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
  <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-9">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header">
        <h3 class="card-title">Update Product Information</h3>
      </div>
      <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label for="name">Product Name (Bengali/English) <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $product->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="weight">Weight / Package Size</label>
                <input type="text" name="weight" class="form-control" id="weight" value="{{ old('weight', $product->weight) }}">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="regular_price">Regular Price (৳) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="regular_price" class="form-control @error('regular_price') is-invalid @enderror" id="regular_price" value="{{ old('regular_price', $product->regular_price) }}" required>
                @error('regular_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="sale_price">Sale / Offer Price (৳) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" value="{{ old('sale_price', $product->sale_price) }}" required>
                @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="stock">Available Stock Quantity <span class="text-danger">*</span></label>
                <input type="number" name="stock" class="form-control" id="stock" value="{{ old('stock', $product->stock) }}" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="badge">Product Badge / Tag (Optional)</label>
                <input type="text" name="badge" class="form-control" id="badge" value="{{ old('badge', $product->badge) }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="image">Change Product Image</label>
                <div class="custom-file">
                  <input type="file" name="image" class="custom-file-input" id="image" accept="image/*">
                  <label class="custom-file-label" for="image">Choose file...</label>
                </div>
                <div class="mt-2">
                  <small class="text-muted mr-2">Current Image:</small>
                  <img src="{{ $product->image_url }}" alt="Current image" style="height: 50px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="short_description">Short Summary</label>
            <textarea name="short_description" id="short_description" rows="2" class="form-control">{{ old('short_description', $product->short_description) }}</textarea>
          </div>

          <div class="form-group">
            <label for="description">Full Description</label>
            <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="card card-outline card-primary p-3 bg-light shadow-sm">
                <div class="custom-control custom-switch">
                  <input type="hidden" name="is_featured" value="0">
                  <input type="checkbox" name="is_featured" class="custom-control-input" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                  <label class="custom-control-label font-weight-bold text-dark" for="is_featured">
                    <i class="fas fa-star text-warning mr-1"></i> Set as Main Featured Product
                  </label>
                </div>
                <small class="text-muted mt-1 d-block">This product will be prominently highlighted in the top feature section of the landing page.</small>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="card card-outline card-success p-3 bg-light shadow-sm">
                <div class="custom-control custom-switch">
                  <input type="hidden" name="is_active" value="0">
                  <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                  <label class="custom-control-label font-weight-bold text-dark" for="is_active">
                    <i class="fas fa-globe text-success mr-1"></i> Publish on Website (Active)
                  </label>
                </div>
                <small class="text-muted mt-1 d-block">Uncheck to unpublish/hide this product from customer order selection and the homepage.</small>
              </div>
            </div>
          </div>

        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success px-4"><i class="fas fa-save mr-1"></i> Update Product</button>
          <a href="{{ route('admin.products.index') }}" class="btn btn-default ml-2">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
