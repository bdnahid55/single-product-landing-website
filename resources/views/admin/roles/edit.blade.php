@extends('layouts.admin')

@section('title', 'Edit Role: ' . $role->name)
@section('page_title', 'Edit Role & Permissions')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles & Permissions</a></li>
  <li class="breadcrumb-item active">Edit Role</li>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-10 col-md-12">
    <div class="card card-outline card-info shadow-sm">
      <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
        <div>
          <h3 class="card-title font-weight-bold text-dark m-0">
            <i class="fas fa-edit text-info mr-2"></i> রোল ও পারমিশন সম্পাদনা (Edit Role: {{ $role->name }})
          </h3>
          <p class="text-muted small mb-0 mt-1">
            রোলের নাম পরিবর্তন করুন বা যে যে ফিচারের পারমিশন দেওয়া প্রয়োজন তা টিক দিন।
          </p>
        </div>
        <div class="card-tools mt-2 mt-md-0">
          <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> ফিরে যান (Back)
          </a>
        </div>
      </div>

      <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card-body">

          @if($role->name === 'Super Admin')
            <div class="alert alert-warning border-0 shadow-sm mb-4">
              <div class="d-flex align-items-center">
                <i class="fas fa-crown text-warning fa-2x mr-3"></i>
                <div>
                  <h6 class="font-weight-bold mb-1">সুপার অ্যাডমিন প্রোটেকশন (Super Admin Protection)</h6>
                  <p class="mb-0 small">
                    <strong>Super Admin</strong> হলো সর্বোচ্চ ক্ষমতার রোল। সিস্টেম আর্কিটেকচার অনুযায়ী সুপার অ্যাডমিন সর্বদা স্বয়ংক্রিয়ভাবে সকল পারমিশন পেয়ে থাকেন।
                  </p>
                </div>
              </div>
            </div>
          @endif

          <!-- Role Name -->
          <div class="form-group mb-4">
            <label for="name" class="font-weight-bold text-dark">
              রোলের নাম (Role Name) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-shield-alt text-info"></i></span>
              </div>
              <input type="text" 
                     name="name" 
                     id="name" 
                     class="form-control @error('name') is-invalid @enderror" 
                     value="{{ old('name', $role->name) }}" 
                     {{ $role->name === 'Super Admin' ? 'readonly' : '' }} 
                     required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            @if($role->name === 'Super Admin')
              <small class="form-text text-muted">সুপার অ্যাডমিন রোলের নাম পরিবর্তনযোগ্য নয়।</small>
            @endif
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3 pt-2 border-top">
            <h5 class="font-weight-bold text-dark m-0">
              <i class="fas fa-key text-warning mr-1"></i> পারমিশনসমূহ (Permissions)
            </h5>
            @if($role->name !== 'Super Admin')
              <div>
                <button type="button" class="btn btn-sm btn-outline-primary mr-2" id="selectAllBtn">
                  <i class="fas fa-check-double mr-1"></i> সব নির্বাচন করুন (Select All)
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllBtn">
                  <i class="fas fa-times mr-1"></i> সব মুছুন (Deselect All)
                </button>
              </div>
            @endif
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
                    @if($role->name !== 'Super Admin')
                      <button type="button" class="btn btn-xs btn-link text-primary toggle-group-btn" data-group="{{ $groupKey }}">
                        Toggle All
                      </button>
                    @endif
                  </div>
                  <div class="card-body py-3">
                    <p class="text-muted small mb-3">{{ $group['description'] }}</p>
                    @foreach($group['permissions'] as $permName => $permInfo)
                      @php
                        $isChecked = $role->name === 'Super Admin' || in_array($permName, old('permissions', $rolePermissions));
                      @endphp
                      <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" 
                               name="permissions[]" 
                               class="custom-control-input perm-checkbox group-{{ $groupKey }}" 
                               id="perm_{{ $permName }}" 
                               value="{{ $permName }}"
                               {{ $isChecked ? 'checked' : '' }}
                               {{ $role->name === 'Super Admin' ? 'disabled' : '' }}>
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
          @if($role->name !== 'Super Admin')
            <button type="submit" class="btn btn-info px-4 font-weight-bold shadow-sm">
              <i class="fas fa-save mr-1"></i> পরিবর্তন সংরক্ষণ করুন (Update Role)
            </button>
          @else
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary px-4 font-weight-bold">
              ফিরে যান (Back to List)
            </a>
          @endif
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const checkboxes = document.querySelectorAll('.perm-checkbox:not(:disabled)');
  const selectAllBtn = document.getElementById('selectAllBtn');
  const deselectAllBtn = document.getElementById('deselectAllBtn');
  
  if (selectAllBtn) {
    selectAllBtn.addEventListener('click', function() {
      checkboxes.forEach(cb => cb.checked = true);
    });
  }

  if (deselectAllBtn) {
    deselectAllBtn.addEventListener('click', function() {
      checkboxes.forEach(cb => cb.checked = false);
    });
  }

  document.querySelectorAll('.toggle-group-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const group = this.getAttribute('data-group');
      const groupCheckboxes = document.querySelectorAll('.group-' + group + ':not(:disabled)');
      const anyUnchecked = Array.from(groupCheckboxes).some(cb => !cb.checked);
      groupCheckboxes.forEach(cb => cb.checked = anyUnchecked);
    });
  });
});
</script>
@endsection
