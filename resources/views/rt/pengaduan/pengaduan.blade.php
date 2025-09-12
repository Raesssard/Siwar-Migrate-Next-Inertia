@extends('rt.layouts.app')

@section('title', $title)

@section('content')

<div id="content">
    @include('rt.layouts.topbar')

    <div class="container-fluid">
        <div class="row">
            {{-- Alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-2" role="alert">
                    {{ session('success') }}
                    <i class="fas fa-check-circle"></i>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-2" role="alert">
                    {{ session('error') }}
                    <i class="fas fa-exclamation-triangle"></i>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Search & Filter --}}
            <form action="{{ route('rt.pengaduan.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="form-control" placeholder="Cari Judul / Warga...">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Filter Status --</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <a href="{{ route('rt.pengaduan.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                </div>
            </form>

            {{-- Card --}}
            <div class="col-xl-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Pengaduan Warga</h6>
                    </div>

                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-users me-2 text-primary"></i>
                                <span class="fw-semibold">{{ $total_pengaduan ?? 0 }} Pengaduan</span>
                            </div>
                        </div>

                        {{-- Tabel --}}
                        <div class="table-responsive">
                            <table class="table table-hover table-sm text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">NIK</th> {{-- kolom baru --}}
                                        <th class="text-center">Warga</th>
                                        <th class="text-center">Judul</th>
                                        <th class="text-center">Isi</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengaduan as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i') }}
                                            </td>
                                            <td class="text-center">{{ $item->warga->nik ?? '-' }}</td> {{-- tampilkan nik --}}
                                            <td class="text-center">{{ $item->warga->nama ?? '-' }}</td>
                                            <td class="text-center">{{ $item->judul }}</td>
                                            <td class="text-center">{{ \Illuminate\Support\Str::limit($item->isi, 40, '...') }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ $item->status === 'diproses' ? 'bg-warning' : 'bg-success' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modalDetail{{ $item->id }}">
                                                    <i class="fas fa-info"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        {{-- Modal detail --}}
                                        @include('rt.pengaduan.komponen.detail_pengaduan', ['item' => $item])
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada pengaduan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="text-muted">
                                Menampilkan {{ $pengaduan->firstItem() ?? 0 }} - {{ $pengaduan->lastItem() }}
                                dari {{ $pengaduan->total() }} data
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
