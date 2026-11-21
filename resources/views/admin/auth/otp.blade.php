<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @php
    $siteTitle = \App\Models\Setting::get('meta_title') ?: \App\Models\Setting::get('site_title', 'মধু');
    $brandTitle = \App\Models\Setting::get('site_title', 'মধু');
    $favicon = \App\Models\Setting::get('site_favicon');
  @endphp
  <title>ওটিপি যাচাইকরণ (OTP Verification) | {{ $siteTitle }} Admin</title>

  <!-- Favicon -->
  @include('partials.favicon')

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
  <style>
    .otp-input {
      font-size: 30px !important;
      letter-spacing: 12px !important;
      text-align: center !important;
      font-weight: 700 !important;
      font-family: 'Courier New', Courier, monospace !important;
      border: 2px solid #28a745 !important;
      border-radius: 8px !important;
      padding: 10px !important;
      color: #155724 !important;
      background-color: #fdfefe !important;
    }
    .otp-input:focus {
      border-color: #218838 !important;
      box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
    }
  </style>
</head>
<body class="hold-transition login-page" style="background-color: #f4f6f9;">
<div class="login-box" style="width: 440px; max-width: 95%;">
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
      <div class="text-success small font-weight-bold mt-1">
        <i class="fas fa-shield-alt mr-1"></i> দ্বিমুখী প্রমাণীকরণ (2-Factor Authentication)
      </div>
    </div>
    
    <div class="card-body p-4">
      <div class="text-center mb-3">
        <h5 class="font-weight-bold text-dark mb-1">ওটিপি যাচাইকরণ (Verify OTP)</h5>
        <p class="text-muted small mb-0">
          স্বাগতম, <strong>{{ $user->name }}</strong>! ড্যাশবোর্ডে প্রবেশের জন্য আপনার ইমেইলে প্রেরিত ৬-সংখ্যার ওটিপি কোডটি লিখুন।
        </p>
      </div>

      <div class="alert alert-light border text-center py-2 mb-3">
        <i class="fas fa-envelope text-primary mr-1"></i>
        প্রেরিত ইমেইল: <strong class="text-dark">{{ $user->masked_email }}</strong>
        @if($user->masked_phone)
          <div class="small text-muted mt-1">
            <i class="fas fa-phone mr-1"></i> রেজিস্টার্ড মোবাইল: {{ $user->masked_phone }}
          </div>
        @endif
      </div>

      @if(session('info'))
        <div class="alert alert-info py-2 small">
          <i class="fas fa-info-circle mr-1"></i> {{ session('info') }}
        </div>
      @endif

      @if(session('success'))
        <div class="alert alert-success py-2 small">
          <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger py-2 small">
          <i class="fas fa-exclamation-triangle mr-1"></i> {{ $errors->first() }}
        </div>
      @endif

      <!-- OTP Submit Form -->
      <form action="{{ route('admin.otp.verify') }}" method="POST" id="otpForm">
        @csrf
        <div class="form-group mb-4">
          <label for="otpInput" class="text-center d-block font-weight-bold text-dark mb-2">
            ৬ ডিজিটের ওটিপি লিখুন (Enter 6-Digit Code)
          </label>
          <input type="text" 
                 name="otp" 
                 id="otpInput" 
                 class="form-control otp-input @error('otp') is-invalid @enderror" 
                 placeholder="••••••" 
                 maxlength="6" 
                 inputmode="numeric" 
                 pattern="[0-9]*"
                 autocomplete="one-time-code" 
                 required 
                 autofocus>
        </div>

        <button type="submit" class="btn btn-success btn-block py-2 font-weight-bold shadow-sm" style="font-size: 1.05rem;">
          <i class="fas fa-check-circle mr-1"></i> যাচাই করুন ও প্রবেশ করুন (Verify & Login)
        </button>
      </form>

      <!-- Resend & Cancel Options -->
      <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <form action="{{ route('admin.otp.resend') }}" method="POST" id="resendForm">
          @csrf
          <button type="submit" class="btn btn-link text-primary p-0 small font-weight-bold" id="resendBtn">
            <i class="fas fa-redo-alt mr-1"></i> পুনরায় কোড পাঠান (Resend)
          </button>
          <span id="countdownText" class="text-muted small ml-1" style="display: none;"></span>
        </form>

        <a href="{{ route('admin.otp.cancel') }}" class="text-danger small font-weight-bold">
          <i class="fas fa-times-circle mr-1"></i> বাতিল করুন (Cancel)
        </a>
      </div>

    </div>
  </div>

  <div class="text-center mt-3">
    <a href="{{ route('home') }}" class="text-muted small">
      <i class="fas fa-arrow-left mr-1"></i> {{ $brandTitle }}-এ ফিরে যান (Back to Store)
    </a>
  </div>
</div>

<script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
<script>
  // Auto-focus and filter non-numeric characters
  const otpInput = document.getElementById('otpInput');
  otpInput.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value.length === 6) {
      // Optional auto-submit when 6 digits are typed
      document.getElementById('otpForm').submit();
    }
  });

  // Countdown timer for resend
  let timeLeft = 60;
  const resendBtn = document.getElementById('resendBtn');
  const countdownText = document.getElementById('countdownText');

  // Cooldown logic
  function startCooldown() {
    resendBtn.style.pointerEvents = 'none';
    resendBtn.style.opacity = '0.5';
    countdownText.style.display = 'inline';
    countdownText.textContent = '(' + timeLeft + 's)';

    const timer = setInterval(function() {
      timeLeft--;
      if (timeLeft <= 0) {
        clearInterval(timer);
        resendBtn.style.pointerEvents = 'auto';
        resendBtn.style.opacity = '1';
        countdownText.style.display = 'none';
        timeLeft = 60;
      } else {
        countdownText.textContent = '(' + timeLeft + 's)';
      }
    }, 1000);
  }

  // Start cooldown on load if just sent
  startCooldown();
</script>
</body>
</html>
