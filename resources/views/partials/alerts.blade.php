@if(session('success'))
  <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div class="alert alert-success alert-dismissible fade show shadow-lg rounded-3" role="alert">
      <i class="bi bi-check-circle-fill me-2 fs-5"></i>
      <strong>সফল!</strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
@endif

@if(session('error'))
  <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div class="alert alert-danger alert-dismissible fade show shadow-lg rounded-3" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
      <strong>ত্রুটি!</strong> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
@endif

@if($errors->any())
  <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div class="alert alert-danger alert-dismissible fade show shadow-lg rounded-3" role="alert">
      <div class="d-flex align-items-center mb-1">
        <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
        <strong>অনুগ্রহ করে নিচের ত্রুটিগুলো সংশোধন করুন:</strong>
      </div>
      <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
@endif
