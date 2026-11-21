<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @php
    $siteTitle = \App\Models\Setting::get('meta_title') ?: \App\Models\Setting::get('site_title', 'মধু');
    $brandTitle = \App\Models\Setting::get('site_title', 'মধু');
    $favicon = \App\Models\Setting::get('site_favicon');
  @endphp
  <title>Admin Login | {{ $siteTitle }} Admin</title>

  <!-- Favicon -->
  @include('partials.favicon')

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page" style="background-color: #f4f6f9;">
<div class="login-box">
  <div class="card card-outline card-success shadow-lg">
    <div class="card-header text-center py-3">
      <a href="{{ route('home') }}" class="h2 text-dark font-weight-bold d-inline-flex align-items-center justify-content-center text-decoration-none">
        @if($favicon)
          <img src="{{ asset($favicon) }}" alt="Logo" style="height: 38px; width: 38px; object-fit: contain; margin-right: 8px; border-radius: 4px;">
        @else
          <span class="mr-2">🍯</span>
        @endif
        <span><b>{{ $brandTitle }}</b> <span class="font-weight-light text-secondary">Admin</span></span>
      </a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to manage orders, products & settings</p>

      @if($errors->any())
        <div class="alert alert-danger py-2">
          {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('admin.login.submit') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required autocomplete="current-password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" name="remember" class="custom-control-input" id="rememberMe">
              <label class="custom-control-label" for="rememberMe">Remember Me</label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-success btn-block">Sign In</button>
          </div>
        </div>
      </form>

      <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="text-muted small">
          <i class="fas fa-arrow-left mr-1"></i> {{ $brandTitle }}-এ ফিরে যান (Back to Store)
        </a>
      </div>

    </div>
  </div>
</div>

<script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
