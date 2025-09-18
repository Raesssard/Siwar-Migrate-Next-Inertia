<style>
    /* ------------------------------------------- */
    /* General Card Styling                        */
    /* ------------------------------------------- */
    .card-clickable {
        transition: transform 0.1s ease-out, box-shadow 0.1s ease-out;
    }

    .card-clickable:active {
        transform: translateY(2px);
        box-shadow: 0 0.3rem 0.6rem rgba(0,0,0,.1) !important;
    }

    .card-clickable .text-decoration-none {
        display: block; /* Ensures the link covers the entire card body area */
    }

    /* ------------------------------------------- */
    /* Responsive Adjustments                      */
    /* ------------------------------------------- */

    /* For screens below medium (tablets and phones) */
    @media (max-width: 767.98px) {
        .card-body {
            padding: 0.7rem !important; /* Slightly more compact padding */
        }
        .card-body .h4 {
            font-size: 1.2rem !important; /* Slightly smaller h4 font size */
            margin-bottom: 0.1rem !important; /* More compact bottom margin for h4 */
        }
        .card-body .text-xs {
            font-size: 0.58rem !important; /* Slightly smaller title text size for better fit */
            margin-bottom: 0.1rem !important; /* More compact bottom margin for title text */
        }
        .card-body .bi, /* Target Bootstrap SVG Icons */
        .card-body .fa-3x { /* Target Font Awesome icons */
            font-size: 1.7em !important; /* Slightly smaller icon size */
            width: 26px !important; /* Slightly smaller SVG width */
            height: 26px !important; /* Slightly smaller SVG height */
        }

        /* Crucial fixes for alignment and offset within card-body */
        .card-body > .row { /* Target the direct child row of card-body */
            display: flex; /* Ensure it's a flex container */
            align-items: center; /* Vertically align items in the center */
            flex-wrap: nowrap; /* Prevent columns from wrapping to a new line */
            justify-content: space-between; /* Push content to ends of the row */
            gap: 0.5rem; /* Add consistent space between columns */

            /* Ensure no default Bootstrap negative margins interfere */
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        /* Resetting Bootstrap's default column spacing for more control */
        .card-body .col,
        .card-body .col-auto {
            padding: 0 !important; /* Remove all padding from cols */
            margin: 0 !important; /* Remove all margins from cols */
        }

        .card-body .col { /* For the text column */
            flex-basis: 0; /* Base size before growing */
            flex-grow: 1; /* Allow text column to take available space */
            min-width: 0; /* Important to prevent content overflow (text wrapping is allowed now) */
        }

        .card-body .col-auto { /* For the icon column */
            flex-shrink: 0; /* Prevent icon column from shrinking */
            display: flex; /* Use flexbox to center icon content */
            align-items: center;
            justify-content: center;
        }
    }

    /* For extra small screens (very small phones) */
    @media (max-width: 575.98px) {
        .card-body {
            padding: 0.4rem !important; /* Even more compact padding */
        }
        .card-body .h4 {
            font-size: 0.95rem !important; /* Further reduce for very small screens */
            margin-bottom: 0.05rem !important;
        }
        .card-body .text-xs {
            font-size: 0.5rem !important; /* Further reduce for very small screens */
            margin-bottom: 0.05rem !important;
        }
        .card-body .bi,
        .card-body .fa-3x {
            font-size: 1.3em !important;
            width: 20px !important;
            height: 20px !important;
        }
        .card-body > .row {
            gap: 0.2rem; /* Slightly smaller gap for very small screens */
        }
    }
</style>

<div class="row">

    {{-- Card Jumlah RT --}}
    @if(Auth::user()->canRw('rt.view'))
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card border-left-info shadow h-100 py-2 card-clickable">
            <a href="{{ route('rw.rukun_tetangga.index') }}" class="text-decoration-none">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah RT</div>
                            <div class="h4 mb-0 font-weight-bolder text-gray-800 me-2">{{ $jumlah_rt }}</div>
                        </div>
                        <div class="col-auto mr-2">
                            <i class="fas fa-house-user fa-3x text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    {{-- Card Jumlah KK --}}
    @if(Auth::user()->canRw('kk.view'))
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card border-left-info shadow h-100 py-2 card-clickable">
            <a href="{{ route('rw.kartu_keluarga.index') }}" class="text-decoration-none">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah KK</div>
                            <div class="h4 mb-0 font-weight-bolder text-gray-800 me-2">{{ $jumlah_kk }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-3x text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    {{-- Card Jumlah Warga --}}
    @if(Auth::user()->canRw('warga.view'))
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 card-clickable">
            <a href="{{ route('rw.warga.index') }}" class="text-decoration-none">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Warga</div>
                            <div class="h4 mb-0 font-weight-bolder text-gray-800">{{ $jumlah_warga }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill text-gray-400" style="font-size:36px"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    {{-- Card Pengaduan --}}
    @if(Auth::user()->canRw('pengaduan.rwrt.view'))
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 card-clickable">
            <a href="{{ route('rw.pengaduan.index') }}" class="text-decoration-none">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pengaduan</div>
                            <div class="h4 mb-0 font-weight-bolder text-gray-800">{{ $pengaduan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paper-plane fa-3x text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    {{-- Card Pengumuman RW --}}
    @if(Auth::user()->canRw('pengumuman.rw.manage'))
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 card-clickable">
            <a href="{{ route('rw.pengumuman.index') }}" class="text-decoration-none">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pengumuman RW</div>
                            <div class="h4 mb-0 font-weight-bolder text-gray-800">{{ $pengumuman_rw }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-3x text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    {{-- Card Pengumuman RT --}}
    @if(Auth::user()->canRw('pengumuman.rwrt.view'))
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 card-clickable">
            <a href="{{ route('rw.pengumuman-rt.index') }}" class="text-decoration-none">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pengumuman RT</div>
                            <div class="h4 mb-0 font-weight-bolder text-gray-800">{{ $pengumuman_rt }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-3x text-gray-400"></i>
                        </div>  
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    {{-- Card Jumlah Warga Penduduk --}}
    @if(Auth::user()->canRw('warga.view'))
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 card-clickable">
            <a href="{{ route('rw.warga.index') }}" class="text-decoration-none">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Warga Sebagai Penduduk</div>
                            <div class="h4 mb-0 font-weight-bolder text-gray-800">{{ $jumlah_warga_penduduk }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-home fa-3x text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    {{-- Card Jumlah Warga Pendatang --}}
    @if(Auth::user()->canRw('warga.view'))
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 card-clickable">
            <a href="{{ route('rw.warga.index') }}" class="text-decoration-none">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Warga Sebagai Pendatang</div>
                            <div class="h4 mb-0 font-weight-bolder text-gray-800">{{ $jumlah_warga_pendatang }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-walking fa-3x text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    {{-- Card Total Iuran Bulan Ini --}}
    @if(Auth::user()->canRw('iuran.rw.manage'))
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card border-left-success shadow h-100 py-2 card-clickable">
            <a href="{{ route('rw.iuran.index') }}" class="text-decoration-none">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Iuran Masuk Bulan Ini</div>
                            <div class="h4 mb-0 font-weight-bolder text-gray-800">
                                Rp{{ number_format($total_iuran_bulan_ini, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-3x text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    {{-- Card Total Pemasukan --}}
    @if(Auth::user()->canRw('transaksi.rwrt.view'))
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card border-left-success shadow h-100 py-2 card-clickable">
            <a href="{{ route('rw.transaksi.index') }}" class="text-decoration-none">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pemasukan</div>
                            <div class="h4 mb-0 font-weight-bolder text-gray-800">
                                Rp{{ number_format($total_pemasukan, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-3x text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    {{-- Card Total Pengeluaran --}}
    @if(Auth::user()->canRw('transaksi.rwrt.view'))
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card border-left-danger shadow h-100 py-2 card-clickable">
            <a href="{{ route('rw.transaksi.index') }}" class="text-decoration-none">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                            <div class="h4 mb-0 font-weight-bolder text-gray-800">
                                Rp{{ number_format($total_pengeluaran, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-3x text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

    {{-- Card Saldo Akhir --}}
    @if(Auth::user()->canRw('transaksi.rwrt.view'))
    <div class="col-6 col-md-4 col-xl-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 card-clickable">
            <a href="{{ route('rw.transaksi.index') }}" class="text-decoration-none">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Saldo Akhir</div>
                            <div class="h4 mb-0 font-weight-bolder text-gray-800">
                                Rp{{ number_format($total_saldo_akhir, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-3x text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif

</div>

