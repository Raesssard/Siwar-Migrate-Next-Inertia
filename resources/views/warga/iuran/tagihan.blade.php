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
                <form action="{{ route('warga.tagihan') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari Data Tagihan...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 d-flex gap-2">
                        <a href="{{ route('warga.tagihan') }}" class="btn btn-secondary btn-sm">Reset Pencarian</a>
                    </div>
                </form>

                <div class="row">

    {{-- ===================== --}}
    {{-- Tabel Tagihan Manual --}}
    {{-- ===================== --}}
    <div class="col-xl-12 col-lg-7 mb-4">
        <div class="card shadow">
            <div class="card-header py-2">
                <h6 class="m-0 font-weight-bold text-primary">Tagihan Manual</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm text-nowrap">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Iuran</th>
                                <th>Nominal</th>
                                <th>Tgl Tagih</th>
                                <th>Tgl Tempo</th>
                                <th>Status</th>
                                <th>Tgl Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tagihanManual as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}</td>
                                    <td>
                                        @if ($item->status_bayar === 'sudah_bayar')
                                            <span class="badge bg-success">Sudah Bayar</span>
                                        @else
                                            <span class="badge bg-warning">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->tgl_bayar ? \Carbon\Carbon::parse($item->tgl_bayar)->translatedFormat('d F Y H:i') : '-' }}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDetailManual{{ $item->id }}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">Tidak ada tagihan manual.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $tagihanManual->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- ======================= --}}
    {{-- Tabel Tagihan Otomatis --}}
    {{-- ======================= --}}
    <div class="col-xl-12 col-lg-7 mb-4">
        <div class="card shadow">
            <div class="card-header py-2">
                <h6 class="m-0 font-weight-bold text-primary">Tagihan Otomatis</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm text-nowrap">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Iuran</th>
                                <th>Nominal</th>
                                <th>Tgl Tagih</th>
                                <th>Tgl Tempo</th>
                                <th>Status</th>
                                <th>Tgl Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tagihanOtomatis as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}</td>
                                    <td>
                                        @if ($item->status_bayar === 'sudah_bayar')
                                            <span class="badge bg-success">Sudah Bayar</span>
                                        @else
                                            <span class="badge bg-warning">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->tgl_bayar ? \Carbon\Carbon::parse($item->tgl_bayar)->translatedFormat('d F Y H:i') : '-' }}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDetailOtomatis{{ $item->id }}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">Tidak ada tagihan otomatis.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $tagihanOtomatis->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>

{{-- Modal Detail Tagihan Manual --}}
@foreach ($tagihanManual as $item)
<div class="modal fade" id="modalDetailManual{{ $item->id }}" tabindex="-1"
    aria-labelledby="modalDetailManualLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalDetailManualLabel{{ $item->id }}">
                    Detail Tagihan Manual: {{ $item->nama }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p><strong>Nama Iuran:</strong> {{ $item->nama }}</p>
                        <p><strong>Nominal:</strong> Rp{{ number_format($item->nominal, 0, ',', '.') }}</p>
                        <p><strong>Tanggal Tagih:</strong> {{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}</p>
                        <p><strong>Tanggal Tempo:</strong> {{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}</p>
                        <p><strong>Jenis:</strong> <span class="badge bg-secondary">Manual</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status Pembayaran:</strong>
                            @if ($item->status_bayar === 'sudah_bayar')
                                <span class="badge bg-success">Sudah Bayar</span>
                            @else
                                <span class="badge bg-warning">Belum Bayar</span>
                            @endif
                        </p>
                        <p><strong>Tanggal Bayar:</strong> {{ $item->tgl_bayar ? \Carbon\Carbon::parse($item->tgl_bayar)->translatedFormat('d F Y H:i') : '-' }}</p>
                        <p><strong>Kategori Pembayaran:</strong> {{ $item->kategori_pembayaran ?? '-' }}</p>
                        <p><strong>Bukti Transfer:</strong>
                            @if ($item->bukti_transfer)
                                <a href="{{ asset('storage/' . $item->bukti_transfer) }}" target="_blank" class="btn btn-sm btn-info">Lihat Bukti</a>
                            @else
                                -
                            @endif
                        </p>
                        <p><strong>Nomor KK:</strong> {{ $item->no_kk ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach


{{-- Modal Detail Tagihan Otomatis --}}
@foreach ($tagihanOtomatis as $item)
<div class="modal fade" id="modalDetailOtomatis{{ $item->id }}" tabindex="-1"
    aria-labelledby="modalDetailOtomatisLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetailOtomatisLabel{{ $item->id }}">
                    Detail Tagihan Otomatis: {{ $item->nama }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p><strong>Nama Iuran:</strong> {{ $item->nama }}</p>
                        <p><strong>Nominal:</strong> Rp{{ number_format($item->nominal, 0, ',', '.') }}</p>
                        <p><strong>Tanggal Tagih:</strong> {{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}</p>
                        <p><strong>Tanggal Tempo:</strong> {{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}</p>
                        <p><strong>Jenis:</strong> <span class="badge bg-secondary">Otomatis</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status Pembayaran:</strong>
                            @if ($item->status_bayar === 'sudah_bayar')
                                <span class="badge bg-success">Sudah Bayar</span>
                            @else
                                <span class="badge bg-warning">Belum Bayar</span>
                            @endif
                        </p>
                        <p><strong>Tanggal Bayar:</strong> {{ $item->tgl_bayar ? \Carbon\Carbon::parse($item->tgl_bayar)->translatedFormat('d F Y H:i') : '-' }}</p>
                        <p><strong>Kategori Pembayaran:</strong> {{ $item->kategori_pembayaran ?? '-' }}</p>
                        <p><strong>Bukti Transfer:</strong>
                            @if ($item->bukti_transfer)
                                <a href="{{ asset('storage/' . $item->bukti_transfer) }}" target="_blank" class="btn btn-sm btn-info">Lihat Bukti</a>
                            @else
                                -
                            @endif
                        </p>
                        <p><strong>Nomor KK:</strong> {{ $item->no_kk ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach


@endsection

@push('scripts')
    {{-- Tidak ada script khusus untuk modal tambah/edit/hapus karena warga hanya melihat --}}
@endpush
