<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ url('/admin') }}" class="brand-link text-center">
    <span class="brand-text font-weight-light">Admin Panel</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- Dashboard -->
        @can('view-dashboard')
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        @endcan

        <!-- Orders -->
        @can('manage-orders')
        <li class="nav-item">
          <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>
              Orders
              @php
                $pendingOrdersCount = \App\Models\Order::where('order_status', 'pending')->count();
              @endphp
              @if($pendingOrdersCount > 0)
                <span class="badge badge-warning right">{{ $pendingOrdersCount }}</span>
              @endif
            </p>
          </a>
        </li>
        @endcan

        <!-- Products -->
        @can('manage-products')
        <li class="nav-item {{ request()->routeIs('admin.products.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-box"></i>
            <p>
              Products
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>All Products</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.products.create') }}" class="nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add New Product</p>
              </a>
            </li>
          </ul>
        </li>
        @endcan

        <!-- Reviews -->
        @can('manage-reviews')
        <li class="nav-item">
          <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-star"></i>
            <p>Customer Reviews</p>
          </a>
        </li>
        @endcan

        <!-- Section Controls -->
        @can('manage-sections')
        <li class="nav-item">
          <a href="{{ route('admin.sections.index') }}" class="nav-link {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-sliders-h text-warning"></i>
            <p class="font-weight-bold">Section Controls</p>
          </a>
        </li>
        @endcan

        <!-- Settings -->
        @can('manage-settings')
        <li class="nav-item">
          <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cog"></i>
            <p>Site & SEO Settings</p>
          </a>
        </li>
        @endcan

        @if(auth()->user()->can('manage-admins') || auth()->user()->can('manage-roles'))
        <li class="nav-header">SYSTEM</li>
        @endif

        <!-- Admin Accounts -->
        @can('manage-admins')
        <li class="nav-item">
          <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-shield text-info"></i>
            <p>Admin Accounts</p>
          </a>
        </li>
        @endcan

        <!-- Roles & Permissions -->
        @can('manage-roles')
        <li class="nav-item">
          <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-tag text-primary"></i>
            <p>Roles & Permissions</p>
          </a>
        </li>
        @endcan

        <!-- Logout -->
        <li class="nav-item">
          <form action="{{ route('admin.logout') }}" method="POST" id="sidebarLogoutForm">
            @csrf
            <a href="#" onclick="event.preventDefault(); document.getElementById('sidebarLogoutForm').submit();" class="nav-link text-danger">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </form>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
