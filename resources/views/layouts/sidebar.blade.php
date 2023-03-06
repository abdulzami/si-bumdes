<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <div class="brand-link">
        <img src="{{ asset('assets/dist/img/logo-bumdes.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">BUMDes
            @if (Auth::user()->level == 'super-admin')
                <span class="badge badge-light">Super Admin</span>
            @elseif(Auth::user()->level == 'admin')
                <span class="badge badge-success">Admin</span>
            @elseif(Auth::user()->level == 'kasir')
                <span class="badge badge-danger">Kasir</span>
            @endif
        </span>

    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <h5 class="text-light text-center">
                    
                </h1>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link @yield('sb-dashboard')">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if (Auth::user()->level == 'super-admin')
                    <li class="nav-item">
                        <a href="{{ route('jenis-usaha') }}" class="nav-link @yield('sb-jenis-usaha')">
                            <i class="nav-icon fas fa-store"></i>
                            <p>
                                Manajemen Jenis Usaha
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kasir') }}" class="nav-link @yield('sb-kasir')">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Manajemen Kasir
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('inventaris-all') }}" class="nav-link @yield('sb-inventaris')">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>
                                Data Inventaris
                            </p>
                        </a>
                    </li>
                @endif
                @php
                $wujud_usaha = App\Models\User::find(Auth::user()->parent_id);
                @endphp
                @if (Auth::user()->level == 'admin'  && Auth::user()->wujud_usaha == 'Produk')
                <li class="nav-item">
                    <a href="{{ route('produk') }}" class="nav-link @yield('sb-produk')">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            List Produk
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'kasir' && $wujud_usaha->wujud_usaha == 'Produk')
                <li class="nav-item">
                    <a href="{{ route('produk-kasir') }}" class="nav-link @yield('sb-produk')">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            List Produk
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'kasir' && $wujud_usaha->wujud_usaha == 'Jasa')
                <li class="nav-item">
                    <a href="{{ route('jasa-kasir') }}" class="nav-link @yield('sb-jasa')">
                        <i class="nav-icon fas fa-hand-holding"></i>
                        <p>
                            List Jasa
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'admin' && Auth::user()->wujud_usaha == 'Jasa')
                <li class="nav-item">
                    <a href="{{ route('jasa') }}" class="nav-link @yield('sb-jasa')">
                        <i class="nav-icon fas fa-hand-holding"></i>
                        <p>
                            Manajemen Jasa
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'kasir' && $wujud_usaha->wujud_usaha == 'Produk')
                <li class="nav-item">
                    <a href="{{ route('transaksi-produk') }}" class="nav-link @yield('sb-transaksi')">
                        <i class="nav-icon fas fa-money-check"></i>
                        <p>
                            Transaksi
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'kasir' && $wujud_usaha->wujud_usaha == 'Jasa')
                <li class="nav-item">
                    <a href="{{ route('transaksi-jasa') }}" class="nav-link @yield('sb-transaksi')">
                        <i class="nav-icon fas fa-money-check"></i>
                        <p>
                            Transaksi
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'kasir' )
                <li class="nav-item">
                    <a href="{{ route('hutang') }}" class="nav-link @yield('sb-hutang')">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>
                            Manajemen Hutang
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'admin')
                <li class="nav-item has-treeview @yield('sb-open-pemasukan')">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-long-arrow-alt-right"></i>
                      <p>
                        Pemasukan
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview" @yield('sb-block-pemasukan')>
                      <li class="nav-item">
                        <a href="{{route('pemasukan-bebas')}}" class="nav-link @yield('sb-pemasukan-bebas')" >
                          <i class="far fa-circle nav-icon"></i>
                          <p>Pemasukan Bebas</p>
                        </a>
                      </li>
                    </ul>
                </li>
                @endif
                @if (Auth::user()->level == 'admin')
                <li class="nav-item has-treeview @yield('sb-open-pengeluaran')">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-long-arrow-alt-left"></i>
                      <p>
                        Pengeluaran
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview" @yield('sb-block-pengeluaran')>
                    @if (Auth::user()->level == 'admin' && Auth::user()->wujud_usaha == 'Produk')
                      <li class="nav-item">
                        <a href="{{route('belanja-produk')}}" class="nav-link @yield('sb-belanja_produk')" >
                          <i class="far fa-circle nav-icon"></i>
                          <p>Belanja Produk</p>
                        </a>
                      </li>
                    @endif
                      <li class="nav-item">
                        <a href="{{route('beban-operasional')}}" class="nav-link @yield('sb-beban-operasional')" >
                          <i class="far fa-circle nav-icon"></i>
                          <p>Beban Operasional</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="{{route('beban-gaji')}}" class="nav-link @yield('sb-beban-gaji')" >
                        <i class="far fa-circle nav-icon"></i>
                        <p>Beban Gaji</p>
                      </a>
                    </li>
                    </ul>
                </li>
                @endif
                @if (Auth::user()->level == 'admin')
                <li class="nav-item">
                    <a href="{{ route('inventaris') }}" class="nav-link @yield('sb-inventaris')">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Manajemen Inventaris
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'admin' )
                <li class="nav-item">
                    <a href="{{ route('pelanggan') }}" class="nav-link @yield('sb-pelanggan')">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Manajemen Pelanggan
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'admin')
                <li class="nav-item">
                    <a href="{{ route('kas') }}" class="nav-link @yield('sb-kas-produk')">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>
                            Laporan Kas
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'admin' && Auth::user()->wujud_usaha == 'Produk' )
                <li class="nav-item">
                    <a href="{{ route('laporan-transaksi-produk') }}" class="nav-link @yield('sb-laporan-transaksi')">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>
                            Laporan Transaksi
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'admin' && Auth::user()->wujud_usaha == 'Jasa' )
                <li class="nav-item">
                    <a href="{{ route('laporan-transaksi-jasa') }}" class="nav-link @yield('sb-laporan-transaksi')">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>
                            Laporan Transaksi
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'admin' && Auth::user()->wujud_usaha == 'Produk' )
                <li class="nav-item">
                    <a href="{{ route('laporan-labarugi-produk') }}" class="nav-link @yield('sb-laporan-labarugi')">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>
                            Laporan Laba Rugi
                        </p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'admin' && Auth::user()->wujud_usaha == 'Jasa' )
                <li class="nav-item">
                    <a href="{{ route('laporan-labarugi-jasa') }}" class="nav-link @yield('sb-laporan-labarugi')">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>
                            Laporan Laba Rugi
                        </p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('profil') }}" class="nav-link @yield('sb-profil')">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            Profil anda
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
