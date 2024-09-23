<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('img/s3shop.png') }}" alt="">
            </a>
            <p>&nbsp;</p>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">RB</a>
        </div>

        <ul class="sidebar-menu">

            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="far fa-fire"></i>
                    <span>Beranda</span></a>
            </li>
            {{--
            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('history.index') }}"><i class="far fa-fire"></i>
                    <span>Kelola Riwayat</span></a>
            </li>
            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('categories.index') }}"><i class="far fa-fire"></i> <span>Kelola
                        Kategori</span></a>
            </li> --}}
            {{-- <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Kelola
                        Produk</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('dashboard-general-dashboard') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('staffproduct.index') }}">Kelola Bahan Baku</a>
                    </li>
                </ul>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('dashboard-general-dashboard') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('storage_one.index') }}">Kelola Storage 1</a>
                    </li>
                </ul>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('dashboard-general-dashboard') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('storage_two.index') }}">Kelola Storage 2</a>
                    </li>
                </ul>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('dashboard-general-dashboard') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('product_result.index') }}">Kelola Barang Jadi</a>
                    </li>
                </ul>
            </li> --}}
</div>
