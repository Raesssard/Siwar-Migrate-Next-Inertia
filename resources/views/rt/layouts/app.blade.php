<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ $title ?? 'WargaKita' }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media (min-width: 768px) {
            .sidebar {
                transition: all 0.3s ease;
            }

            .sidebar.toggled {
                width: 100px !important;
            }

            .sidebar .nav-item .nav-link span {
                transition: opacity 0.3s ease;
            }

            body.sidebar-toggled .sidebar {
                width: 80px;
            }
        }

        .scroll-table thead th {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 10;
        }

        .table-container {
            max-height: 380px;
            overflow-y: auto;
        }

        /* Sidebar Modal Mobile */
        .modal.fade .modal-dialog.modal-dialog-slideout-left {
            transform: translateX(-100%);
            transition: transform .3s ease-out;
            margin-left: 0;
            margin-right: auto;
            pointer-events: none;
        }

        .modal.show .modal-dialog.modal-dialog-slideout-left {
            transform: translateX(0);
            pointer-events: auto;
        }

        .modal-backdrop.fade {
            opacity: 0;
            transition: opacity .15s linear;
        }

        .modal-backdrop.show {
            opacity: .5;
        }

        @media (max-width: 575.98px) {
            .modal-dialog.modal-dialog-slideout-left.modal-sm {
                max-width: 35%;
                height: auto;
            }

            .modal-content {
                border-radius: .3rem;
                height: auto;
                max-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            .modal-body {
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                flex-grow: 1;
            }

            .modal-header {
                align-items: flex-start;
                padding-top: 1rem;
                padding-bottom: 0.5rem;
                flex-shrink: 0;
                border-bottom: 2px solid #e3e6f0;
            }

            .sidebar-brand-icon-logo {
                width: 35px;
                height: 35px;
                margin-left: 10px;
            }
        }
    </style>
</head>

@php
    function isActive($pattern, $output = 'active')
    {
        return Request::is($pattern) ? $output : '';
    }
@endphp

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar Desktop -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-md-block" id="accordionSidebar">
            @include('rt.layouts.sidebar')
        </ul>

        <!-- Sidebar Mobile Modal -->
        <div class="modal fade" id="mobileSidebarModal" tabindex="-1" role="dialog"
            aria-labelledby="mobileSidebarLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-slideout-left modal-sm" role="document">
                <div class="modal-content bg-primary text-white">
                    <div class="modal-header border-0">
                        <img src="{{ asset('img/logo.png') }}" class="sidebar-brand-icon-logo" alt="Logo">
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <ul class="navbar-nav sidebar sidebar-dark accordion">
                            <hr class="sidebar-divider my-0">

                            <li class="nav-item {{ Request::is('rt') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('rt.dashboard') }}">
                                    <i class="fas fa-fw fa-tachometer-alt"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('rt/rt.warga*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('rt.warga.index') }}">
                                    <i class="fas fa-users"></i>
                                    <span>Manajemen Warga</span>
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('rt/rt.kartu_keluarga*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('rt.kartu_keluarga.index') }}">
                                    <i class="fas fa-users "></i>
                                    <span>Kartu Keluarga</span>
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('rt/rt.pengumuman*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('rt.pengumuman.index') }}">
                                    <i class="fas fa-bullhorn"></i>
                                    <span>Pengumuman</span>
                                </a>
                            </li>
                            <li class="nav-item {{ Request::is('rt/rt.iuran*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('rt.iuran.index') }}">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <span>Iuran</span>
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('rt/rt.tagihan*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('rt.tagihan.index') }}">
                                    <i class="fas fa-hand-holding-usd"></i>
                                    <span>Tagihan</span>
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('rt/rt.transaksi*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('rt.transaksi.index') }}">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>Transaksi</span>
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('rt/rt.pengaduan*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('rt.pengaduan.index') }}">
                                    <i class="fas fa-comment-lines"></i>
                                    <span>Pengaduan</span>
                                </a>
                            </li>

                            <hr class="sidebar-divider d-none d-md-block">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                {{-- Page Content --}}
                @yield('content')
            </div>
            <!-- End Main Content -->

            {{-- Footer --}}
            @include('rt.layouts.footer')

        </div>
        <!-- End Content Wrapper -->

    </div>
    <!-- End Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
