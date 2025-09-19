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
            background: rgb(255, 255, 255);
            z-index: 10;
        }

        .table-container {
            max-height: 380px;
            overflow-y: auto;
        }

        /* Sidebar Mobile */
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

            .modal-header .close {
                margin-top: -1.5rem;
                margin-bottom: 0;
                padding: 0.5rem;
            }

            .modal-header .modal-title,
            .modal-header .sidebar-brand-icon-logo {
                margin-top: 0;
                margin-bottom: 0;
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
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-md-block"
            id="accordionSidebar">
            @include('rw.layouts.sidebar')
        </ul>

        <!-- Sidebar Mobile -->
<!-- Sidebar Mobile -->
<div class="modal fade" id="mobileSidebarModal" tabindex="-1" role="dialog" aria-labelledby="mobileSidebarLabel" aria-hidden="true">
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

                    {{-- Dashboard --}}
                    <li class="nav-item {{ Request::is('rw') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('rw.dashboard') }}">
                            <i class="fas fa-fw fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    {{-- Menu RW --}}
                    @if(auth()->user()->canRw('rt.view'))
                        <li class="nav-item {{ Request::is('rw/rukun_tetangga*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('rw.rukun_tetangga.index') }}">
                                <i class="fas fa-house-user"></i>
                                <span>Rukun Tetangga</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->canRw('warga.view'))
                        <li class="nav-item {{ Request::is('rw/warga*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('rw.warga.index') }}">
                                <i class="fas fa-users"></i>
                                <span>Manajemen Warga</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->canRw('kk.view'))
                        <li class="nav-item {{ Request::is('rw/kartu_keluarga*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('rw.kartu_keluarga.index') }}">
                                <i class="fas fa-id-card"></i>
                                <span>Kartu Keluarga</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->canRw('pengaduan.rwrt.view'))
                        <li class="nav-item {{ Request::is('rw/pengaduan*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('rw.pengaduan.index') }}">
                                <i class="fas fa-paper-plane"></i>
                                <span>Lihat Pengaduan</span>
                            </a>
                        </li>
                    @endif
                    
                    @if(auth()->user()->canRw('pengumuman.rw.manage'))
                        <li class="nav-item {{ Request::is('rw/pengumuman') && !Request::is('rw/pengumuman-rt*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('rw.pengumuman.index') }}">
                                <i class="fas fa-bullhorn"></i>
                                <span>Pengumuman RW</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->canRw('pengumuman.rwrt.view'))
                        <li class="nav-item {{ Request::is('rw/pengumuman-rt*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('rw.pengumuman-rt.index') }}">
                                <i class="fas fa-bullhorn"></i>
                                <span>Pengumuman RT</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->canRw('iuran.rwrt.view'))
                        <li class="nav-item {{ Request::is('rw/iuran*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('rw.iuran.index') }}">
                                <i class="fas fa-coins"></i>
                                <span>Iuran</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->canRw('tagihan.rwrt.view'))
                        <li class="nav-item {{ Request::is('rw/tagihan*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('rw.tagihan.index') }}">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>Tagihan</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->canRw('transaksi.rwrt.view'))
                        <li class="nav-item {{ Request::is('rw/transaksi*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('rw.transaksi.index') }}">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Transaksi</span>
                            </a>
                        </li>
                    @endif



                    <hr class="sidebar-divider d-none d-md-block">
                </ul>
            </div>
        </div>
    </div>
</div>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            @yield('content')
            <!-- End of Main Content -->

            {{-- footer --}}
            @include('rw.layouts.footer')
        </div>
    </div>
    <!-- End of Page Wrapper -->

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
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword(inputId, buttonElement) {
            const passwordInput = document.getElementById(inputId);
            const icon = buttonElement.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('modalUbahPassword');
            if (modalElement) {
                const modalUbahPassword = new bootstrap.Modal(modalElement);

                @if ($errors->hasAny(['current_password', 'password', 'password_confirmation']))
                    modalUbahPassword.show();
                @endif

                @if (session('success'))
                    setTimeout(function() {
                        modalUbahPassword.hide();
                    }, 3000);
                @endif
            }
        });
    </script>
</body>
</html>
