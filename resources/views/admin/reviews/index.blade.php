@extends('layouts.admin')

@section('title', 'Customer Reviews')
@section('page_title', 'Customer Testimonials & Reviews')

@section('breadcrumb')
  <li class="breadcrumb-item active">Reviews</li>
@endsection

@section('content')
<div class="row">
  <!-- Left Column: Add New Review -->
  <div class="col-md-4">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Add New Review</h3>
      </div>
      <form action="{{ route('admin.reviews.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="form-group">
            <label for="customer_name">Customer Name <span class="text-danger">*</span></label>
            <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="e.g. মোঃ রফিকুল ইসলাম" required>
          </div>

          <div class="form-group">
            <label for="customer_location">Location / City</label>
            <input type="text" name="customer_location" id="customer_location" class="form-control" placeholder="e.g. ধানমন্ডি, ঢাকা">
          </div>

          <div class="form-group">
            <label for="rating">Rating (Stars)</label>
            <select name="rating" id="rating" class="form-control">
              <option value="5" selected>★★★★★ (5 Stars - Excellent)</option>
              <option value="4">★★★★☆ (4 Stars - Good)</option>
              <option value="3">★★★☆☆ (3 Stars - Average)</option>
            </select>
          </div>

          <div class="form-group">
            <label for="review_text">Review Feedback <span class="text-danger">*</span></label>
            <textarea name="review_text" id="review_text" rows="3" class="form-control" placeholder="Customer's comments about the honey..." required></textarea>
          </div>

          <div class="custom-control custom-checkbox">
            <input type="checkbox" name="is_approved" class="custom-control-input" id="is_approved" value="1" checked>
            <label class="custom-control-label" for="is_approved">Publish immediately on website</label>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success btn-block"><i class="fas fa-save mr-1"></i> Save Review</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Right Column: All Reviews Table -->
  <div class="col-md-8">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-star mr-1"></i> Published Testimonials</h3>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped m-0">
            <thead>
              <tr>
                <th>Customer</th>
                <th>Rating</th>
                <th>Feedback</th>
                <th>Status</th>
                <th style="width: 100px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($reviews as $rev)
                <tr>
                  <td>
                    <strong>{{ $rev->customer_name }}</strong>
                    <br><small class="text-muted">{{ $rev->customer_location ?? 'Bangladesh' }}</small>
                  </td>
                  <td class="text-warning text-nowrap">
                    @for($i = 0; $i < $rev->rating; $i++)
                      ★
                    @endfor
                  </td>
                  <td>
                    <small class="text-secondary fst-italic">"{{ Str::limit($rev->review_text, 100) }}"</small>
                  </td>
                  <td>
                    <form action="{{ route('admin.reviews.toggle', $rev->id) }}" method="POST">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="btn btn-xs {{ $rev->is_approved ? 'btn-success' : 'btn-secondary' }}" title="Click to toggle">
                        {{ $rev->is_approved ? 'Published' : 'Hidden' }}
                      </button>
                    </form>
                  </td>
                  <td>
                    <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Delete this review?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4 text-muted">No reviews found. Add one on the left!</td>
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
