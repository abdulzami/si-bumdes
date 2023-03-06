<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        @if (Auth::user()->level == 'admin')
            <li class="nav-item">
                <h5><a class="nav-link"><span class="badge badge-info">{{ Auth::user()->name }}</span></a></h3>
            </li>
        @endif
        @php
            $wujud_usaha = App\Models\User::find(Auth::user()->parent_id);
        @endphp
        @if (Auth::user()->level == 'kasir')
        <li class="nav-item">
            <h5><a class="nav-link"><span class="badge badge-info">{{ $wujud_usaha->name }}</span></a></h3>
        </li>
    @endif
    </ul>
</nav>
<!-- /.navbar -->
