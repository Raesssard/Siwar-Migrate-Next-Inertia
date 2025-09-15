@extends('rt.layouts.app')

@section('title', $title)

@section('content')

    <div id="content">
        @include('rt.layouts.topbar')

        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                {{-- Total Pengumuman (kiri) --}}
                <div class="d-flex align-items-center gap-1 mb-1 mb-sm-0">
                    <i class="fas fa-paper-plane me-2 text-primary"></i>
                    <span class="fw-semibold text-dark">{{ $total_pengaduan_rt ?? 0 }} Pengaduan</span>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="table table-hover table-sm text-nowrap">
                <thead>
                    @forelse ($pengaduan as $item)
                        <tr>
                            <th scope="col" class="text-center">No.</th>
                            <th scope="col" class="text-center">NIK Warga</th>
                            <th scope="col" class="text-center">Nama Warga</th>
                            <th scope="col" class="text-center">Judul</th>
                            <th scope="col" class="text-center">Isi</th>
                            <th scope="col" class="text-center">Tanggal</th>
                            <th scope="col" class="text-center">Detail</th>
                        </tr>
                </thead>
                <tbody>
                    @forelse ($rt_pengaduan as $item)
                        <tr>
                            <th scope="row" class="text-center">
                                {{ $loop->iteration }}</th>
                            <th scope="col" class="text-center">{{ $item->warga->nik }}</th>
                            <td class="text-center">{{ $item->warga->nama }}</td>
                            <td class="text-center">{{ $item->judul }}</td>
                            <td class="text-center">
                                {{ \Illuminate\Support\Str::limit($item->isi, 50, '...') }}
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i') }}
                            </td>
                            <td class="text-center align-item-center">
                                <button type="button" class="btn btn-success btn-sm"
                                    onclick="markAsRead({{ $item->id }})" data-bs-toggle="modal"
                                    data-bs-target="#modalDetailPengaduan{{ $item->id }}">
                                    <i class="fas fa-bookmark"></i>
                                </button>
                            </td>
                        </tr>
                        @include('rt.pengaduan.komponen.detail_pengaduan')
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada pengaduan</td>
                            {{-- colspan disesuaikan --}}
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>
            {{ $pengaduan->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
