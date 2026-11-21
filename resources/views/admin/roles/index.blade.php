@extends('layouts.admin')

@section('title', 'Roles & Permissions')
@section('page_title', 'Roles & Permissions Management')

@section('breadcrumb')
  <li class="breadcrumb-item active">Roles & Permissions</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
        <div>
          <h3 class="card-title font-weight-bold text-dark m-0">
            <i class="fas fa-user-tag text-primary mr-2"></i> অ্যাডমিন রোল ও পারমিশন তালিকা (Roles & Permissions)
          </h3>
          <p class="text-muted small mb-0 mt-1">
            বিভিন্ন অ্যাডমিন রোল তৈরি করুন এবং প্রতিটি রোলের জন্য পৃথক পারমিশন নির্ধারণ করুন।
          </p>
        </div>
        <div class="card-tools mt-2 mt-md-0">
          <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm font-weight-bold shadow-sm">
            <i class="fas fa-plus-circle mr-1"></i> Add New Role (নতুন রোল তৈরি করুন)
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped align-middle" id="rolesTable">
            <thead class="thead-light">
              <tr>
                <th style="width: 50px;" class="text-center">#</th>
                <th style="width: 180px;">রোলের নাম (Role Name)</th>
                <th>বরাদ্দকৃত পারমিশনসমূহ (Assigned Permissions)</th>
                <th style="width: 130px;" class="text-center">অ্যাডমিন সংখ্যা (Users)</th>
                <th style="width: 140px;" class="text-center">অ্যাকশন (Actions)</th>
              </tr>
            </thead>
            <tbody>
              @forelse($roles as $index => $role)
                <tr>
                  <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                  <td>
                    <span class="badge {{ $role->name === 'Super Admin' ? 'badge-danger' : ($role->name === 'Manager' ? 'badge-primary' : ($role->name === 'Editor' ? 'badge-info' : 'badge-dark')) }} px-2 py-1 font-weight-bold" style="font-size: 0.95rem;">
                      <i class="fas fa-shield-alt mr-1"></i> {{ $role->name }}
                    </span>
                    @if($role->name === 'Super Admin')
                      <span class="d-block text-danger small font-weight-bold mt-1">
                        <i class="fas fa-crown"></i> সম্পূর্ণ অ্যাক্সেস (Full Control)
                      </span>
                    @endif
                  </td>
                  <td>
                    @if($role->name === 'Super Admin')
                      <span class="badge badge-success px-2 py-1 mr-1 mb-1">
                        <i class="fas fa-check-double mr-1"></i> সমস্ত পারমিশন অন্তর্ভুক্ত (All Capabilities Granted)
                      </span>
                    @else
                      <div class="d-flex flex-wrap" style="gap: 4px;">
                        @forelse($role->permissions as $perm)
                          <span class="badge badge-light border text-dark font-weight-normal px-2 py-1" title="{{ $perm->name }}">
                            <i class="fas fa-check text-success mr-1"></i>
                            {{ $perm->name }}
                          </span>
                        @empty
                          <span class="text-muted small font-italic">কোনো পারমিশন বরাদ্দ করা হয়নি (No Permissions)</span>
                        @endforelse
                      </div>
                    @endif
                  </td>
                  <td class="text-center">
                    <span class="badge badge-pill badge-secondary px-3 py-1 font-weight-bold" style="font-size: 0.85rem;">
                      <i class="fas fa-users mr-1"></i> {{ $role->users_count }}
                    </span>
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-info" title="Edit Role & Permissions">
                        <i class="fas fa-edit"></i> Edit
                      </a>

                      @if($role->name !== 'Super Admin')
                        <button type="button" 
                                class="btn btn-danger" 
                                title="Delete Role"
                                onclick="confirmRoleDelete({{ $role->id }}, '{{ addslashes($role->name) }}', {{ $role->users_count }})">
                          <i class="fas fa-trash-alt"></i>
                        </button>

                        <form id="delete-role-form-{{ $role->id }}" 
                              action="{{ route('admin.roles.destroy', $role->id) }}" 
                              method="POST" 
                              style="display: none;">
                          @csrf
                          @method('DELETE')
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4 text-muted">
                    <i class="fas fa-info-circle mr-1"></i> কোনো রোল পাওয়া যায়নি।
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function confirmRoleDelete(roleId, roleName, usersCount) {
  if (usersCount > 0) {
    alert('এই রোলটিতে ' + usersCount + ' জন অ্যাডমিন অ্যাকাউন্ট যুক্ত আছে! ডিলিট করার আগে অনুগ্রহ করে তাদের অন্য কোনো রোলে পরিবর্তন করুন।');
    return;
  }
  if (confirm('আপনি কি নিশ্চিত যে "' + roleName + '" রোলটি ডিলিট করতে চান?')) {
    document.getElementById('delete-role-form-' + roleId).submit();
  }
}
</script>
@endsection
