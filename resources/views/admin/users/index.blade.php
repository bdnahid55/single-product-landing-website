@extends('layouts.admin')

@section('title', 'Admin Accounts')
@section('page_title', 'Admin Accounts Management')

@section('breadcrumb')
  <li class="breadcrumb-item active">Admin Accounts</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold text-dark m-0">
          <i class="fas fa-user-shield text-success mr-2"></i> সকল অ্যাডমিন অ্যাকাউন্ট তালিকা
        </h3>
        <div class="card-tools">
          <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm font-weight-bold shadow-sm">
            <i class="fas fa-user-plus mr-1"></i> Add New Admin (নতুন অ্যাডমিন তৈরি)
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped align-middle" id="adminsTable">
            <thead class="thead-light">
              <tr>
                <th style="width: 50px;" class="text-center">#</th>
                <th>নাম (Name)</th>
                <th>ইমেইল (Email / Login ID)</th>
                <th>ফোন নম্বর (Phone)</th>
                <th class="text-center" style="width: 130px;">ভূমিকা (Role)</th>
                <th style="width: 160px;">তৈরির তারিখ</th>
                <th style="width: 140px;" class="text-center">অ্যাকশন (Actions)</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $index => $u)
                <tr>
                  <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                  <td>
                    <span class="font-weight-bold text-dark">{{ $u->name }}</span>
                    @if(auth()->id() === $u->id)
                      <span class="badge badge-success ml-2 font-weight-bold">
                        <i class="fas fa-user-check mr-1"></i> You (আপনার আইডি)
                      </span>
                    @endif
                  </td>
                  <td>
                    <a href="mailto:{{ $u->email }}" class="text-primary font-weight-bold">{{ $u->email }}</a>
                  </td>
                  <td>
                    {{ $u->phone ?: '—' }}
                  </td>
                  <td class="text-center">
                    @forelse($u->roles as $role)
                      <span class="badge {{ $role->name === 'Super Admin' ? 'badge-danger' : ($role->name === 'Manager' ? 'badge-primary' : 'badge-info') }} px-2 py-1 font-weight-bold">
                        <i class="fas fa-shield-alt mr-1"></i> {{ $role->name }}
                      </span>
                    @empty
                      <span class="badge badge-secondary px-2 py-1">No Role</span>
                    @endforelse
                  </td>
                  <td>
                    <small class="text-muted">
                      <i class="far fa-clock mr-1"></i>{{ $u->created_at ? $u->created_at->timezone('Asia/Dhaka')->format('d M, Y h:i A') : '—' }}
                    </small>
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-info" title="Edit Admin">
                        <i class="fas fa-edit"></i>
                      </a>

                      @if(auth()->id() !== $u->id)
                        <button type="button" 
                                class="btn btn-danger" 
                                title="Delete Admin"
                                onclick="confirmDeleteAdmin({{ $u->id }}, '{{ addslashes($u->name) }}')">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                        <form id="delete-admin-form-{{ $u->id }}" 
                              action="{{ route('admin.users.destroy', $u->id) }}" 
                              method="POST" 
                              style="display: none;">
                          @csrf
                          @method('DELETE')
                        </form>
                      @else
                        <button type="button" class="btn btn-secondary" disabled title="You cannot delete yourself">
                          <i class="fas fa-ban"></i>
                        </button>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">
                    কোনো অ্যাডমিন অ্যাকাউন্ট পাওয়া যায়নি।
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="card-footer bg-light">
        <small class="text-muted">
          <i class="fas fa-info-circle mr-1 text-info"></i>
          লগইন থাকা অবস্থায় নিজের অ্যাকাউন্ট মুছে ফেলা যাবে না। সিস্টেমে অন্তত একজন অ্যাডমিন সবসময় সংরক্ষিত থাকবে।
        </small>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function confirmDeleteAdmin(id, name) {
    if (confirm('Are you sure you want to delete admin account "' + name + '"?\nএই অ্যাডমিন অ্যাকাউন্টটি মুছে ফেলতে চান?')) {
      document.getElementById('delete-admin-form-' + id).submit();
    }
  }
</script>
@endpush
