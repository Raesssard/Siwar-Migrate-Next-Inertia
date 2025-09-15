@extends('rt.layouts.app')

@section('title', $title)

@section('content')
    <div id="content">
        @include('rt.layouts.topbar')
        <div class="container-fluid">
            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-2" role="alert">
                        {{ session('success') }}
                        <i class="fas fa-paper-plane"></i>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-2" role="alert">
                        {{ session('error') }}
                        <i class="fas fa-exclamation-triangle"></i>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="{{ route('rt.pengaduan.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Judul Pengaduan...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 d-flex gap-2">
                        <a href="{{ route('rt.pengaduan.index') }}" class="btn btn-secondary btn-sm">Reset Pencarian</a>
                    </div>
                </form>
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Pengaduan RT Saya</h6>
                        </div>

                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                                {{-- Total Pengumuman (kiri) --}}
                                <div class="d-flex align-items-center gap-1 mb-1 mb-sm-0">
                                    <i class="fas fa-paper-plane me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">{{ $total_pengaduan ?? 0 }} Pengaduan</span>
                                </div>
                            </div>
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">No</th>
                                            <th scope="col" class="text-center">NIK Warga</th>
                                            <th scope="col" class="text-center">Nama</th>
                                            <th scope="col" class="text-center">Judul</th>
                                            <th scope="col" class="text-center">Isi</th>
                                            <th scope="col" class="text-center">Tanggal</th>
                                            <th scope="col" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pengaduan as $item)
                                            <tr>
                                                <th scope="row" class="text-center">
                                                    {{ $loop->iteration }}</th>
                                                <td class="text-center">{{ $item->nik_warga }}</td>
                                                <td class="text-center">{{ $item->warga->nama }}</td>
                                                <td class="text-center">{{ $item->judul }}</td>
                                                <td class="text-center">
                                                    {{ \Illuminate\Support\Str::limit($item->isi, 50, '...') }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->status === 'sudah')
                                                        {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d F Y H:i') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i') }}
                                                    @endif
                                                </td>
                                                <td class="text-center align-item-center">
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalDetailPengaduan{{ $item->id }}">
                                                        <i class="fas fa-info"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @include('rt.pengaduan.komponen.detail_pengaduan')
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada pengaduan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <div class="text-muted mb-2">
                                    Menampilkan {{ $pengaduan->firstItem() ?? '0' }}-{{ $pengaduan->lastItem() }}
                                    dari total
                                    {{ $pengaduan->total() }} data
                                </div>

                                <div>
                                    {{ $pengaduan->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
