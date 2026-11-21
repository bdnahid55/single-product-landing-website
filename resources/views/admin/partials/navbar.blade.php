<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('home') }}" target="_blank" class="nav-link">
        <i class="fas fa-external-link-alt mr-1"></i> View Website
      </a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto align-items-center">
    <!-- Bangladesh Standard Time (GMT+6) Badge -->
    <li class="nav-item d-none d-md-inline-block mr-3">
      <span class="badge badge-light border text-muted px-2 py-1 font-weight-normal" style="font-size: 0.82rem;">
        <i class="far fa-clock text-success mr-1"></i> <span id="bdLiveClock">{{ now()->timezone('Asia/Dhaka')->format('d M, Y h:i A') }}</span> (GMT+6)
      </span>
    </li>

    <!-- Full Screen Button -->
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    <!-- User Profile & Logout -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-user mr-1"></i> {{ auth()->user()->name ?? 'Admin' }}
      </a>

      <div class="dropdown-menu dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{ auth()->user()->email ?? 'admin@modhu.com' }}</span>
        <div class="dropdown-divider"></div>
        <form action="{{ route('admin.logout') }}" method="POST">
          @csrf
          <button type="submit" class="dropdown-item text-danger">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </button>
        </form>
      </div>
    </li>
  </ul>
</nav>
