@extends('warga.layouts.app')

@section('title', $title)

@section('konten')

    {{-- Jangan dihapus, ntar dipake buat refrensi klo buat .jsx-nya --}}
    {{-- <div class="p-4">
        <h2 class="font-bold text-lg mb-2">{{ $item->judul }}</h2>

        <div class="text-sm text-gray-500 mb-2 flex justify-between">
            <span><i class="fas fa-user"></i> {{ $item->warga->nama }}</span>
            <span><i class="fas fa-clock"></i> {{ $item->created_at->format('d M Y') }}</span>
        </div>

        <p class="text-gray-700 text-sm mb-3 line-clamp-3">
            {{ Str::limit($item->isi, 100) }}
        </p>

        @php
            $statusColor =
                [
                    'belum' => 'bg-yellow-100 text-yellow-800',
                    'diproses' => 'bg-blue-100 text-blue-800',
                    'selesai' => 'bg-green-100 text-green-800',
                ][$item->status] ?? 'bg-gray-100 text-gray-800';
        @endphp

        <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusColor }}">
            {{ $item->status }}
        </span>

        <div class="mt-4">
            <button type="button" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded"
                data-bs-toggle="modal" data-bs-target="#modalDetailPengaduan{{ $item->id }}">
                Lihat Detail
            </button>
        </div>
    </div> --}}
    <div id="content">
        @include('warga.layouts.topbar')
        <div class="container-fluid">
            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-3 mb-3" role="alert">
                        {{ session('success') }}
                        <i class="fas fa-paper-plane"></i>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-3 mb-3" role="alert">
                        {{ session('error') }}
                        <i class="fas fa-exclamation-triangle"></i>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="d-flex flex-wrap align-items-center justify-content-between px-3 pb-3">
                    <!-- Form Pencarian -->
                    <form action="{{ route('warga.pengaduan.index') }}" method="GET"
                        class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Judul Pengaduan...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <a href="{{ route('warga.pengaduan.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-undo"></i>
                        </a>
                    </form>

                    <!-- Tombol Tambah -->
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modalTambahPengaduan">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </div>
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Pengaduan Saya</h6>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center gap-1 mb-3">
                                <i class="fas fa-paper-plane me-2 text-primary"></i>
                                <span class="fw-semibold text-dark">{{ $total_pengaduan ?? 0 }} Pengaduan</span>
                            </div>

                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">No</th>
                                            <th scope="col" class="text-center">Tanggal</th>
                                            <th scope="col" class="text-center">Judul</th>
                                            <th scope="col" class="text-center">Isi</th>
                                            <th scope="col" class="text-center">Status</th>
                                            <th scope="col" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pengaduan as $item)
                                            <tr>
                                                <th scope="row" class="text-center">
                                                    {{ $loop->iteration }}</th>
                                                <td class="text-center">
                                                    @if ($item->status === 'diproses' || $item->status === 'selesai')
                                                        {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d F Y H:i') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i') }}
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $item->judul }}</td>
                                                <td class="text-center">
                                                    {{ \Illuminate\Support\Str::limit($item->isi, 50, '...') }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->status === 'belum')
                                                        <span class="badge bg-secondary">Belum dibaca</span>
                                                    @elseif ($item->status === 'diproses')
                                                        <span class="badge bg-primary">Sedang diproses</span>
                                                        @if ($item->konfirmasi_rw === 'sudah')
                                                            <span class="badge bg-info">Sudah dikonfirmasi</span>
                                                        @elseif ($item->konfirmasi_rw === 'menunggu')
                                                            <span class="badge bg-warning">Menunggu
                                                                konfirmasi RW</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-success">Selesai</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-item-center">
                                                    <form action="{{ route('warga.pengaduan.destroy', $item->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i
                                                                class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditPengaduan{{ $item->id }}">
                                                        <i class="fas fa-edit"></i> <!-- Ikon edit -->
                                                    </button>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalDetailPengaduan{{ $item->id }}">
                                                        <i class="fas fa-info"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @include('warga.pengaduan.komponen.edit_pengaduan')
                                            @include('warga.pengaduan.komponen.detail_pengaduan')
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

                            @include('warga.pengaduan.komponen.tambah_pengaduan')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
