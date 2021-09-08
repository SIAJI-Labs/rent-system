<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('adm.index') }}" class="brand-link d-flex justify-content-center">
        <span class="brand-text font-weight-light">{{ $wtitle ?? 'SIABAS' }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ getAvatar(\Auth::guard('admin')->user()->name) }}" class="img-circle elevation-2" alt="User Avatar">
            </div>
            <div class="info">
                <a href="#" class="{{ !empty($sidebar_menu) ? ($sidebar_menu == 'profile' ? 'active' : '') : '' }}">
                    {{ Auth::user()->name }}
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="sidebar-menu nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('adm.index') }}" class="nav-link d-flex align-items-center {{ !empty($sidebar_menu) ? ($sidebar_menu == 'dashboard' ? 'active' : '') : '' }}">
                        <i class="nav-icon fas fa-tv"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-header">DATA PRODUK</li>
                <li class="nav-item">
                    <a href="{{ route('adm.product.brand.index') }}" class="nav-link d-flex align-items-center {{ !empty($sidebar_menu) ? ($sidebar_menu == 'brand' ? 'active' : '') : '' }}">
                        <i class="nav-icon fas fa-hashtag"></i>
                        <p>
                            Merek
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('adm.product.category.index') }}" class="nav-link d-flex align-items-center {{ !empty($sidebar_menu) ? ($sidebar_menu == 'category' ? 'active' : '') : '' }}">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>
                            Kategori
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('adm.product.index') }}" class="nav-link d-flex align-items-center {{ !empty($sidebar_menu) ? ($sidebar_menu == 'product' ? 'active' : '') : '' }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Produk
                        </p>
                    </a>
                </li>

                <li class="nav-header">DATA TRANSAKSI</li>
                <li class="nav-item">
                    <a href="{{ route('adm.transaction.index') }}" class="nav-link d-flex align-items-center {{ !empty($sidebar_menu) ? ($sidebar_menu == 'transaction' ? 'active' : '') : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Transaksi
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('adm.accounting.yearly') }}" class="nav-link d-flex align-items-center {{ !empty($sidebar_menu) ? ($sidebar_menu == 'accounting' ? 'active' : '') : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            Keuangan
                        </p>
                    </a>
                </li>

                <li class="nav-header">DATA MASTER</li>
                <li class="nav-item">
                    <a href="{{ route('adm.store.index') }}" class="nav-link d-flex align-items-center {{ !empty($sidebar_menu) ? ($sidebar_menu == 'store' ? 'active' : '') : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Toko
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('adm.customer.index') }}" class="nav-link d-flex align-items-center {{ !empty($sidebar_menu) ? ($sidebar_menu == 'customer' ? 'active' : '') : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Kostumer
                        </p>
                    </a>
                </li>

                <li class="nav-header">MISCELLANEOUS</li>
                <li class="nav-item">
                    <a href="{{ route('larecipe.index') }}" class="nav-link d-flex align-items-center" target="_blank">
                        <i class="nav-icon far fa-circle text-info"></i>
                        <p>Dokumentasi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('log-viewer::dashboard') }}" class="nav-link d-flex align-items-center" target="_blank">
                        <i class="nav-icon far fa-circle text-info"></i>
                        <p>Sistem Log</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link d-flex align-items-center" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                        <p>Log Out</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
