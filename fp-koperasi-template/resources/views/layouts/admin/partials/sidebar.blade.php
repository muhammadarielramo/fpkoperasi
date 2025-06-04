<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <h5>KOKITA</h5>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li>
                <a class="nav-link" href="{{route('admin.dashboard')}}"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>

            {{-- Manajemen User Start --}}
            <li class="menu-header">Manajemen User</li>
            <li class="nav-item dropdown">
                <a class="nav-link has-dropdown"><i class="far fa-user"></i>
                    <span>Anggota</span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{route('admin.data-anggota')}}">Daftar Anggota</a></li>
                    <li><a href="{{route('admin.pendaftaran-anggota')}}" class="beep beep-sidebar">Pendaftaran Anggota</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{route('admin.data-kolektor')}}" class="nav-link" ><i class="far fa-user"></i>
                    <span>Kolektor</span></a>
            </li>
            {{-- <li class="nav-item">
                <a href="{{route('admin.data-admin')}}" class="nav-link"><i class="far fa-user"></i>
                    <span>Admin</span></a>
            </li> --}}
            {{-- Manajemen User End --}}

            {{-- Manajemen Simpanan Start --}}
            <li class="menu-header">Manajemen Simpanan</li>
            <li class="nav-item">
                <a href="{{route('simpanan.index')}}" class="nav-link"><i class="fas fa-piggy-bank"></i>
                    <span>Data Simpanan Anggota</span></a>
            </i>
            <li class="nav-item">
                <a href="{{route('simpanan.history')}}" class="nav-link"><i class="fas fa-clock"></i>
                    <span>History Simpanan</span></a>
            </i>
            {{-- Manajemen Simpanan End --}}

            {{-- Manajemen Pinjaman Start --}}
            <li class="menu-header">Manajemen Pinjaman</li>
            <li class="nav-item">
                <a href="{{route('pinjaman.pengajuan')}}" class="beep beep-sidebar"><i class="fas fa-wallet"></i>
                    <span>Pengajuan Pinjaman</span></a>
            </i>
            <li class="nav-item">
                <a href="{{route('pinjaman.index')}}" class="nav-link"><i class="fas fa-wallet"></i>
                    <span>Data Pinjaman Anggota</span></a>
            </i>
            <li class="nav-item">
                <a href="{{route('pinjaman.history')}}" class="nav-link"><i class="fas fa-clock"></i>
                    <span>History Pinjaman</span></a>
            </i>
            {{-- Manajemen pinjaman End --}}

            {{-- Laporan Keuangan Start --}}
            <li class="menu-header">Laporan Keuangan</li>
            <li class="nav-item">
                  <a href="#" class="nav-link"><i class="fas fa-th-large"></i>
                    <span>Laporan Harian</span></a>
            </i>
            <li class="nav-item">
                  <a href="#" class="nav-link"><i class="fas fa-th-large"></i>
                    <span>Laporan Bulanan</span></a>
            </i>
            {{-- Laporan Keuangan End --}}
        </ul>
    </aside>
</div>
