<style>
    /* CSS untuk logo di sidebar */
    .sidebar-brand-icon-logo {
        width: 110px;
        /* Ukuran normal logo */
        height: 110px;
        object-fit: contain;
        /* margin-right: 200px; Ini terlalu besar dan akan menyebabkan masalah saat dikecilkan. Kita akan menghapusnya atau menguranginya. */
        filter: brightness(0) invert(1);
        /* Ini membuat logo hitam menjadi putih */
    }

    /* Rotasi yang sebelumnya ada pada ikon Font Awesome tidak relevan lagi untuk gambar */
    .sidebar-brand-icon {
        transform: none !important;
        /* Batalkan rotasi jika ada dari kelas lain */
    }

    /* Aturan untuk Sidebar yang Dikecilkan */
    /* Targetkan saat #accordionSidebar memiliki kelas .toggled */
    #accordionSidebar.toggled .sidebar-brand-icon-logo {
        width: 50px;
        /* Ukuran logo lebih kecil saat sidebar dikecilkan */
        height: 50px;
        margin-left: 26px;
        /* Hapus margin kanan agar logo menjadi fokus utama */
    }


    /* Sesuaikan juga margin pada .sidebar-brand secara keseluruhan jika perlu */
    /* Saat sidebar dikecilkan, pastikan branding area juga pas */
    #accordionSidebar.toggled .sidebar-brand {
        width: 4.375rem !important;
        /* Lebar sidebar yang dikecilkan biasanya sekitar 4.375rem */
        padding-left: 0;
        padding-right: 0;
    }

    /* Jika Anda ingin logo berada di tengah secara horizontal di area icon saja saat dikecilkan */
    #accordionSidebar.toggled .sidebar-brand .sidebar-brand-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        /* Pastikan pembungkus icon mengisi lebar */
    }

    /* Hapus margin-right 200px dari .sidebar-brand-icon-logo */
    /* Ini akan konflik saat sidebar dikecilkan */
    .sidebar-brand-icon-logo {
        margin-right: 0;
        /* Setel ulang margin-right ke 0 */
        /* Jika Anda ingin margin kanan pada mode normal, tambahkan di sini */
        /* contoh: margin-right: 15px; */
    }

    /* Untuk kasus mobile atau breakpoint tertentu, kita juga bisa menyesuaikan */
    @media (max-width: 767.98px) {
        .sidebar-brand-icon-logo {
            width: 50px;
            /* Ukuran logo di mobile (jika sidebar mobile juga muncul) */
            height: 50px;
            margin-right: 10px;
            /* Sesuaikan margin untuk mobile */
        }
    }


    /* Asumsi CSS dasar dari template SB Admin 2 untuk sidebar sudah dimuat */
    /* .sidebar-dark.accordion { ... } */
    /* .sidebar-brand { ... } */
    /* .sidebar-brand-icon { ... } */
    /* .sidebar-brand-text { ... } */
    /* .nav-item { ... } */
    /* .nav-link { ... } */
    /* dll. */
</style>
<!-- Sidebar Desktop -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-md-block" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('rw.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Siwar RW</div>
    </a>

    <hr class="sidebar-divider my-0">

    {{-- Dashboard --}}
    <li class="nav-item {{ Request::is('rw') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('rw.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    {{-- Rukun Tetangga --}}
    @if(auth()->user()->canRw('rt.view'))
        <li class="nav-item {{ Request::is('rw/rukun_tetangga*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('rw.rukun_tetangga.index') }}">
                <i class="fas fa-house-user"></i>
                <span>Rukun Tetangga</span>
            </a>
        </li>
    @endif

    {{-- Manajemen Warga --}}
    @if(auth()->user()->canRw('warga.view'))
        <li class="nav-item {{ Request::is('rw/warga*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('rw.warga.index') }}">
                <i class="fas fa-users"></i>
                <span>Manajemen Warga</span>
            </a>
        </li>
    @endif

    {{-- Kartu Keluarga --}}
    @if(auth()->user()->canRw('kk.view'))
        <li class="nav-item {{ Request::is('rw/kartu_keluarga*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('rw.kartu_keluarga.index') }}">
                <i class="fas fa-id-card"></i>
                <span>Kartu Keluarga</span>
            </a>
        </li>
    @endif

    {{-- Pengumuman RW --}}
    @if(auth()->user()->canRw('pengumuman.rw.manage'))
        <li class="nav-item {{ Request::is('rw/pengumuman') && !Request::is('rw/pengumuman-rt*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('rw.pengumuman.index') }}">
                <i class="fas fa-bullhorn"></i>
                <span>Pengumuman RW</span>
            </a>
        </li>
    @endif

    {{-- Pengumuman RT --}}
    @if(auth()->user()->canRw('pengumuman.rwrt.view'))
        <li class="nav-item {{ Request::is('rw/pengumuman-rt*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('rw.pengumuman-rt.index') }}">
                <i class="fas fa-bullhorn"></i>
                <span>Pengumuman RT</span>
            </a>
        </li>
    @endif

    {{-- Iuran --}}
    @if(auth()->user()->canRw('iuran.rwrt.view'))
        <li class="nav-item {{ Request::is('rw/iuran*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('rw.iuran.index') }}">
                <i class="fas fa-coins"></i>
                <span>Iuran</span>
            </a>
        </li>
    @endif

    {{-- Tagihan --}}
    @if(auth()->user()->canRw('tagihan.rwrt.view'))
        <li class="nav-item {{ Request::is('rw/tagihan*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('rw.tagihan.index') }}">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Tagihan</span>
            </a>
        </li>
    @endif

    {{-- Transaksi --}}
    @if(auth()->user()->canRw('transaksi.rwrt.view'))
        <li class="nav-item {{ Request::is('rw/transaksi*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('rw.transaksi.index') }}">
                <i class="fas fa-money-bill-wave"></i>
                <span>Transaksi</span>
            </a>
        </li>
    @endif

    {{-- Pengaduan --}}
    @if(auth()->user()->canRw('pengaduan.rwrt.view'))
        <li class="nav-item {{ Request::is('rw/pengaduan*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('rw.pengaduan.index') }}">
                <i class="fas fa-paper-plane"></i>
                <span>Lihat Pengaduan</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider d-none d-md-block">
</ul>