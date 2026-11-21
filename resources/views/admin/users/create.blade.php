@extends('layouts.admin')

@section('title', 'Add New Admin')
@section('page_title', 'Create Admin Account')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Admin Accounts</a></li>
  <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8 col-md-10">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header bg-white py-3">
        <h3 class="card-title font-weight-bold text-dark m-0">
          <i class="fas fa-user-plus text-success mr-2"></i> নতুন অ্যাডমিন তৈরি করুন (New Admin Account)
        </h3>
      </div>

      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <p class="text-muted small mb-4">
            নতুন অ্যাডমিনের নাম, ইমেইল এবং পাসওয়ার্ড দিয়ে অ্যাকাউন্ট তৈরি করুন। তৈরির সাথে সাথে এই তথ্য দিয়ে অ্যাডমিন প্যানেলে লগইন করা যাবে।
          </p>

          <!-- Admin Name -->
          <div class="form-group">
            <label for="name" class="font-weight-bold text-dark">
              অ্যাডমিনের নাম (Full Name) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
              </div>
              <input type="text" 
                     name="name" 
                     id="name" 
                     class="form-control @error('name') is-invalid @enderror" 
                     value="{{ old('name') }}" 
                     placeholder="e.g. সাইফুল ইসলাম" 
                     required 
                     autofocus>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Email -->
          <div class="form-group">
            <label for="email" class="font-weight-bold text-dark">
              ইমেইল অ্যাড্রেস (Login Email) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input type="email" 
                     name="email" 
                     id="email" 
                     class="form-control @error('email') is-invalid @enderror" 
                     value="{{ old('email') }}" 
                     placeholder="e.g. admin2@modhu.com" 
                     required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <small class="form-text text-muted">এই ইমেইলটি লগইন করার সময় ইউজার আইডি হিসেবে ব্যবহৃত হবে।</small>
          </div>

          <!-- Phone (Optional) -->
          <div class="form-group">
            <label for="phone" class="font-weight-bold text-dark">
              মোবাইল নম্বর (Phone Number) <small class="text-muted">(ঐচ্ছিক)</small>
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
              </div>
              <input type="text" 
                     name="phone" 
                     id="phone" 
                     class="form-control @error('phone') is-invalid @enderror" 
                     value="{{ old('phone') }}" 
                     placeholder="e.g. 017XXXXXXXX">
              @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Administrative Role -->
          <div class="form-group">
            <label for="role" class="font-weight-bold text-dark">
              অ্যাডমিন রোল / দায়িত্ব (Role & Permissions) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
              </div>
              <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                <option value="">-- রোল নির্বাচন করুন --</option>
                @foreach($roles as $r)
                  <option value="{{ $r->name }}" {{ old('role', 'Manager') === $r->name ? 'selected' : '' }}>
                    {{ $r->name }}
                    @if($r->name === 'Super Admin')
                      (সম্পূর্ণ অ্যাক্সেস - All Capabilities)
                    @elseif($r->name === 'Manager')
                      (অর্ডার, পণ্য, রিভিউ ও সেকশন ম্যানেজমেন্ট)
                    @elseif($r->name === 'Editor')
                      (পণ্য ও রিভিউ ম্যানেজমেন্ট)
                    @else
                      ({{ $r->permissions->count() > 0 ? $r->permissions->count() . 'টি পারমিশন' : 'কোনো পারমিশন নেই' }})
                    @endif
                  </option>
                @endforeach
              </select>
              @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="d-flex justify-content-between align-items-center mt-1">
              <small class="form-text text-muted">রোল অনুযায়ী এই অ্যাডমিন কোন কোন মেনু ও ফিচার ব্যবহার করতে পারবেন তা নির্ধারিত হবে।</small>
              @can('manage-roles')
                <small><a href="{{ route('admin.roles.create') }}" target="_blank" class="text-primary font-weight-bold"><i class="fas fa-plus-circle mr-1"></i> নতুন রোল তৈরি করুন</a></small>
              @endcan
            </div>
          </div>

          <div class="row">
            <!-- Password -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="password" class="font-weight-bold text-dark">
                  পাসওয়ার্ড (Password) <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  </div>
                  <input type="password" 
                         name="password" 
                         id="password" 
                         class="form-control @error('password') is-invalid @enderror" 
                         placeholder="কমপক্ষে ৬ অক্ষর" 
                         required>
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <!-- Password Confirmation -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="password_confirmation" class="font-weight-bold text-dark">
                  পাসওয়ার্ড নিশ্চিতকরণ (Confirm Password) <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                  </div>
                  <input type="password" 
                         name="password_confirmation" 
                         id="password_confirmation" 
                         class="form-control" 
                         placeholder="একই পাসওয়ার্ড পুনরায় লিখুন" 
                         required>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="card-footer bg-light d-flex justify-content-between align-items-center">
          <a href="{{ route('admin.users.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> ফিরে যান (Cancel)
          </a>
          <button type="submit" class="btn btn-success px-4 font-weight-bold shadow-sm">
            <i class="fas fa-check-circle mr-1"></i> অ্যাকাউন্ট তৈরি সম্পন্ন করুন (Create Admin)
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
