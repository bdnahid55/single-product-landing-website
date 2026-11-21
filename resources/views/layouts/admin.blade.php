<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Dashboard') | Admin</title>

  <!-- Favicon -->
  @include('partials.favicon')

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/sweetalert2/sweetalert2.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
  <!-- Summernote -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/summernote/summernote-bs4.min.css') }}">
  <!-- OverlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

  <!-- jQuery -->
  <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
  <style>
    .alert a.seo-asset-link,
    a.seo-asset-link,
    .seo-asset-link {
      color: #000000 !important;
      text-decoration: underline !important;
    }
    .alert a.seo-asset-link:hover,
    a.seo-asset-link:hover,
    .seo-asset-link:hover {
      color: #28a745 !important;
    }
  </style>
  @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  @include('admin.partials.navbar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('admin.partials.sidebar')
  <!-- /.Main Sidebar Container -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              @yield('breadcrumb')
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="icon fas fa-check"></i> {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="icon fas fa-ban"></i> {{ session('error') }}
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Alert!</h5>
            <ul class="mb-0">
              @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @yield('content')

      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} - </strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- Bootstrap 4 -->
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- OverlayScrollbars -->
<script src="{{ asset('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('admin/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
<!-- DataTables & Plugins -->
<script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('admin/plugins/summernote/summernote-bs4.min.js') }}"></script>

@stack('scripts')
</body>
</html>
