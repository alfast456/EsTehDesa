      <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand my-3 d-flex align-items-center justify-content-center" href="/">

          <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-store"></i>
          </div>
          <div class="sidebar-brand-text mx-3">
            @yield('title', @config('app.name'))
          </div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
          <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
          Data
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Data Master</span>
          </a>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{ route('categories.index') }}">
                <i class="fas fa-fw fa-tags"></i> Kategori
              </a>
            </div>
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{ route('products.index') }}">
                <i class="fas fa-fw fa-box"></i> Produk
              </a>
          </div>
        </li>

        <!-- Nav Item - create order -->
        <li class="nav-item">
          <a class="nav-link" href="{{ route('orders.create') }}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Pesanan</span></a>
        </li>

        <!-- Nav Item - Transaction -->
        <li class="nav-item">
          <a class="nav-link" href="{{ route('transactions.index') }}">
            <i class="fas fa-fw fa-receipt"></i>
            <span>Transaksi</span></a>
        </li>

        <div class="text-center d-none d-md-inline">
          <br>
          <button class="rounded-sm border-0" id="sidebarToggle"></button>
        </div>
      </ul>