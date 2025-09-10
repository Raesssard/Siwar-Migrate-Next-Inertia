@extends('warga.layouts.app') {{-- Asumsi ada layout untuk warga --}}

@section('title', $title)

@section('konten')

    <div id="content">

        {{-- top bar --}}
        @include('warga.layouts.topbar') {{-- Asumsi ada topbar untuk warga --}}

        {{-- top bar end --}}

        <div class="container-fluid">
            <div class="row">

                {{-- Session messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Form Pencarian --}}
                <form action="{{ route('warga.transaksi') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Nama Transaksi atau Keterangan...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 d-flex gap-2">
                        <a href="{{ route('warga.transaksi') }}" class="btn btn-secondary btn-sm">Reset Pencarian</a>
                    </div>
                </form>

                <!--tabel transaksi-->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Keuangan RT Saya</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">TANGGAL</th>
                                            <th scope="col">NAMA TRANSAKSI</th>
                                            <th scope="col">NOMINAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transaksi as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ $item->nama_transaksi }}</td>
                                                <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data transaksi untuk RT
                                                    Anda.</td> {{-- colspan disesuaikan --}}
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <div class="text-muted mb-2">
                                    Menampilkan {{ $transaksi->firstItem() ?? '0' }}-{{ $transaksi->lastItem() }}
                                    dari total
                                    {{ $transaksi->total() }} data
                                </div>

                                <div>
                                    {{ $transaksi->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
