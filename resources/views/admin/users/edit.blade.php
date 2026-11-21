@extends('layouts.admin')

@section('title', 'Edit Admin: ' . $user->name)
@section('page_title', 'Edit Admin Account')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Admin Accounts</a></li>
  <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8 col-md-10">
    <div class="card card-outline card-info shadow-sm">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold text-dark m-0">
          <i class="fas fa-user-edit text-info mr-2"></i> অ্যাডমিন অ্যাকাউন্ট সম্পাদনা: {{ $user->name }}
        </h3>
        @if(auth()->id() === $user->id)
          <span class="badge badge-success px-2 py-1 font-weight-bold">
            <i class="fas fa-user-check mr-1"></i> You are editing your own profile
          </span>
        @endif
      </div>

      <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">

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
                     value="{{ old('name', $user->name) }}" 
                     required>
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
                     value="{{ old('email', $user->email) }}" 
                     required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <small class="form-text text-muted">লগইন ইউজার আইডি পরিবর্তন হলে নতুন ইমেইল দিয়ে লগইন করতে হবে।</small>
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
                     value="{{ old('phone', $user->phone) }}" 
                     placeholder="e.g. 017XXXXXXXX">
              @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Administrative Role -->
          @php
            $currentRole = $user->roles->first()?->name ?? 'Manager';
          @endphp
          <div class="form-group">
            <label for="role" class="font-weight-bold text-dark">
              অ্যাডমিন রোল / দায়িত্ব (Role & Permissions) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
              </div>
              <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                @foreach($roles as $r)
                  <option value="{{ $r->name }}" {{ old('role', $currentRole) === $r->name ? 'selected' : '' }}>
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
            @can('manage-roles')
              <div class="text-right mt-1">
                <small><a href="{{ route('admin.roles.index') }}" target="_blank" class="text-primary font-weight-bold"><i class="fas fa-external-link-alt mr-1"></i> রোল ও পারমিশন পরিচালনা করুন</a></small>
              </div>
            @endcan
          </div>

          <div class="alert alert-light border mt-4 mb-3">
            <i class="fas fa-shield-alt text-warning mr-1"></i>
            <strong>পাসওয়ার্ড পরিবর্তন ও রি-অথেনটিকেশন (Password Change & Re-authentication):</strong>
            নিরাপত্তার স্বার্থে নতুন পাসওয়ার্ড সেট করার পূর্বে আপনার বর্তমান পাসওয়ার্ড প্রদান করে রি-অথেনটিকেশন (Re-authentication) করা বাধ্যতামূলক। অপরিবর্তিত রাখতে চাইলে নিচের ঘরগুলো ফাঁকা রাখুন।
          </div>

          <!-- Current Password (Required for Re-authentication) -->
          <div class="form-group mb-4">
            <label for="current_password" class="font-weight-bold text-dark">
              আপনার বর্তমান পাসওয়ার্ড (Current Password for Re-authentication)
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-light"><i class="fas fa-key text-warning"></i></span>
              </div>
              <input type="password" 
                     name="current_password" 
                     id="current_password" 
                     class="form-control @error('current_password') is-invalid @enderror" 
                     placeholder="নতুন পাসওয়ার্ড দিতে চাইলে এখানে আপনার বর্তমান পাসওয়ার্ডটি লিখুন"
                     autocomplete="current-password">
              @error('current_password')
                <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
              @enderror
            </div>
            <small class="form-text text-muted">
              <i class="fas fa-info-circle text-info mr-1"></i> নতুন পাসওয়ার্ড সংরক্ষণ করার পূর্বে এই ঘরে আপনার বর্তমান কার্যকর পাসওয়ার্ডটি দিয়ে যাচাই করুন।
            </small>
          </div>

          <div class="row">
            <!-- New Password -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="password" class="font-weight-bold text-dark">
                  নতুন পাসওয়ার্ড (New Password) <small class="text-muted">(ঐচ্ছিক)</small>
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  </div>
                  <input type="password" 
                         name="password" 
                         id="password" 
                         class="form-control @error('password') is-invalid @enderror" 
                         placeholder="পরিবর্তন না করতে চাইলে ফাঁকা রাখুন">
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
                  পাসওয়ার্ড নিশ্চিতকরণ (Confirm New Password)
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                  </div>
                  <input type="password" 
                         name="password_confirmation" 
                         id="password_confirmation" 
                         class="form-control" 
                         placeholder="একই নতুন পাসওয়ার্ড পুনরায় লিখুন">
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="card-footer bg-light d-flex justify-content-between align-items-center">
          <a href="{{ route('admin.users.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> ফিরে যান (Cancel)
          </a>
          <button type="submit" class="btn btn-info px-4 font-weight-bold shadow-sm">
            <i class="fas fa-save mr-1"></i> তথ্য সংরক্ষণ করুন (Update Admin)
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
