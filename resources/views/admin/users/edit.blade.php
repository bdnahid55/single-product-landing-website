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

          <div class="alert alert-light border mt-4 mb-3">
            <i class="fas fa-key text-warning mr-1"></i>
            <strong>পাসওয়ার্ড পরিবর্তন (Password Change):</strong> বর্তমান পাসওয়ার্ড অপরিবর্তিত রাখতে চাইলে নিচের ঘরগুলো ফাঁকা রাখুন।
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
