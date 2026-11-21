@extends('layouts.admin')

@section('title', 'Add New Role')
@section('page_title', 'Create Role & Assign Permissions')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles & Permissions</a></li>
  <li class="breadcrumb-item active">Add New Role</li>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-10 col-md-12">
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
        <div>
          <h3 class="card-title font-weight-bold text-dark m-0">
            <i class="fas fa-plus-circle text-primary mr-2"></i> নতুন রোল তৈরি ও পারমিশন নির্ধারণ (Create Role)
          </h3>
          <p class="text-muted small mb-0 mt-1">
            রোলের নাম দিন এবং এই রোলের অধিকারভুক্ত অ্যাডমিনরা কোন কোন ফিচার ব্যবহার করতে পারবেন তা টিক দিন।
          </p>
        </div>
        <div class="card-tools mt-2 mt-md-0">
          <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> ফিরে যান (Back)
          </a>
        </div>
      </div>

      <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <!-- Role Name -->
          <div class="form-group mb-4">
            <label for="name" class="font-weight-bold text-dark">
              রোলের নাম (Role Name) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-shield-alt text-primary"></i></span>
              </div>
              <input type="text" 
                     name="name" 
                     id="name" 
                     class="form-control @error('name') is-invalid @enderror" 
                     value="{{ old('name') }}" 
                     placeholder="e.g. Sales Executive, Delivery Boy, Content Manager" 
                     required 
                     autofocus>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <small class="form-text text-muted">
              একটি স্বতন্ত্র ও বোধগম্য নাম দিন যা অ্যাডমিন তৈরিতে ড্রপডাউনে প্রদর্শিত হবে।
            </small>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3 pt-2 border-top">
            <h5 class="font-weight-bold text-dark m-0">
              <i class="fas fa-key text-warning mr-1"></i> পারমিশন নির্বাচন করুন (Select Permissions)
            </h5>
            <div>
              <button type="button" class="btn btn-sm btn-outline-primary mr-2" id="selectAllBtn">
                <i class="fas fa-check-double mr-1"></i> সব নির্বাচন করুন (Select All)
              </button>
              <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllBtn">
                <i class="fas fa-times mr-1"></i> সব মুছুন (Deselect All)
              </button>
            </div>
          </div>

          <!-- Grouped Permissions -->
          <div class="row">
            @foreach($permissionGroups as $groupKey => $group)
              <div class="col-md-6 mb-4">
                <div class="card h-100 border shadow-none bg-light">
                  <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-dark">
                      <i class="{{ $group['icon'] }} mr-1"></i> {{ $group['title'] }}
                    </span>
                    <button type="button" class="btn btn-xs btn-link text-primary toggle-group-btn" data-group="{{ $groupKey }}">
                      Toggle All
                    </button>
                  </div>
                  <div class="card-body py-3">
                    <p class="text-muted small mb-3">{{ $group['description'] }}</p>
                    @foreach($group['permissions'] as $permName => $permInfo)
                      <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" 
                               name="permissions[]" 
                               class="custom-control-input perm-checkbox group-{{ $groupKey }}" 
                               id="perm_{{ $permName }}" 
                               value="{{ $permName }}"
                               {{ is_array(old('permissions')) && in_array($permName, old('permissions')) ? 'checked' : '' }}>
                        <label class="custom-control-label font-weight-bold text-dark" for="perm_{{ $permName }}">
                          {{ $permInfo['bn_label'] }}
                          <span class="text-muted font-weight-normal font-italic ml-1">({{ $permName }})</span>
                        </label>
                        <small class="d-block text-secondary mt-1">
                          {{ $permInfo['desc'] }}
                        </small>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            @endforeach
          </div>

        </div>

        <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
          <a href="{{ route('admin.roles.index') }}" class="btn btn-light border">
            বাতিল (Cancel)
          </a>
          <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">
            <i class="fas fa-save mr-1"></i> রোল সংরক্ষণ করুন (Save Role)
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const checkboxes = document.querySelectorAll('.perm-checkbox');
  
  document.getElementById('selectAllBtn').addEventListener('click', function() {
    checkboxes.forEach(cb => cb.checked = true);
  });

  document.getElementById('deselectAllBtn').addEventListener('click', function() {
    checkboxes.forEach(cb => cb.checked = false);
  });

  document.querySelectorAll('.toggle-group-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const group = this.getAttribute('data-group');
      const groupCheckboxes = document.querySelectorAll('.group-' + group);
      const anyUnchecked = Array.from(groupCheckboxes).some(cb => !cb.checked);
      groupCheckboxes.forEach(cb => cb.checked = anyUnchecked);
    });
  });
});
</script>
@endsection
