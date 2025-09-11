@extends('rw.layouts.app')

@section('title', $title)

@section('content')

<div id="content">
    {{-- Top Bar --}}
    @include('rw.layouts.topbar')
    {{-- End Top Bar --}}

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

            {{-- Form Filter dan Pencarian --}}
            <form action="{{ route('rw.transaksi.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari Transaksi...">
                </div>
                <div class="col-md-2">
                    <select name="bulan" class="form-select form-select-sm">
                        <option value="">Semua Bulan</option>
                        @foreach (range(1,12) as $bln)
                            <option value="{{ $bln }}" {{ request('bulan') == $bln ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $bln)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="tahun" class="form-select form-select-sm">
                        <option value="">Semua Tahun</option>
                        @foreach ($daftar_tahun as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="rt" class="form-control form-control-sm" placeholder="RT..." value="{{ request('rt') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('rw.transaksi.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                </div>
                <div class="dropdown mb-2 ms-auto">
    <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="dropdownExportTransaksi"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-file-excel"></i> Export Transaksi
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownExportTransaksi">
        <li>
            <a class="dropdown-item" href="{{ route('rw.transaksi.export', 'pemasukan') }}">
                <i class="fas fa-file-excel text-success"></i> Export Pemasukan
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('rw.transaksi.export', 'pengeluaran') }}">
                <i class="fas fa-file-excel text-danger"></i> Export Pengeluaran
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('rw.transaksi.export', 'all') }}">
                <i class="fas fa-file-excel text-primary"></i> Export Semua
            </a>
        </li>
    </ul>
</div>

            </form>

            {{-- Tabel Transaksi --}}
            <div class="col-xl-12 col-lg-12 mt-3">
                <div class="card shadow mb-4">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Tabel Data Transaksi</h6>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTransaksi">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                        
                    </div>
                    

                    <div class="card-body">
                        <div class="table-responsive table-container">
                            <table class="table table-hover table-sm scroll-table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>RT</th>
                                        <th>Tanggal</th>
                                        <th>Nama Transaksi</th>
                                        <th>Jenis</th>
                                        <th>Nominal</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($paginatedTransaksi as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->rt }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                                        <td>{{ $item->nama_transaksi }}</td>
                                        <td>
                                            <span class="badge {{ $item->jenis == 'pemasukan' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($item->jenis) }}
                                            </span>
                                        </td>
                                        <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditTransaksi{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('rw.transaksi.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data transaksi.</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
                            <div class="text-muted mb-2">
                                Menampilkan {{ $paginatedTransaksi->firstItem() ?? 0 }} - {{ $paginatedTransaksi->lastItem() }} dari total {{ $paginatedTransaksi->total() }} data
                            </div>
                            <div>
                                {{ $paginatedTransaksi->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit (semua transaksi) --}}
@foreach ($paginatedTransaksi as $item)
<div class="modal fade" id="modalEditTransaksi{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('rw.transaksi.update', $item->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <input type="hidden" name="modal_type" value="edit">
            <input type="hidden" name="edit_item_id" value="{{ $item->id }}">

            <div class="modal-header">
                <h5 class="modal-title">Edit Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label>RT</label>
                    <select name="rt" class="form-select form-select-sm" required>
                        <option value="">-- Pilih RT --</option>
                        @foreach ($rukun_tetangga as $rt)
                            <option value="{{ $rt }}" {{ $item->rt == $rt ? 'selected' : '' }}>
                                RT {{ $rt }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control form-control-sm"
                        value="{{ $item->tanggal->format('Y-m-d') }}" required>
                </div>
                <div class="mb-2">
                    <label>Nama Transaksi</label>
                    <input type="text" name="nama_transaksi" class="form-control form-control-sm"
                        value="{{ $item->nama_transaksi }}" required>
                </div>
                <div class="mb-2">
                    <label>Jenis</label>
                    <select name="jenis" class="form-select form-select-sm" required>
                        <option value="pemasukan" {{ $item->jenis == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ $item->jenis == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Nominal</label>
                    <input type="number" name="nominal" class="form-control form-control-sm"
                        value="{{ $item->nominal }}" required>
                </div>
                <div class="mb-2">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control form-control-sm">{{ $item->keterangan }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
@endforeach
{{-- End Modal Edit --}}

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambahTransaksi" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('rw.transaksi.store') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="modal_type" value="add">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
<div class="mb-2">
    <label>RT</label>
    <select name="rt" class="form-select form-select-sm" required>
        <option value="">-- Pilih RT --</option>
        @foreach ($rukun_tetangga as $rt)
            <option value="{{ $rt }}" {{ old('rt') == $rt ? 'selected' : '' }}>
                RT {{ $rt }}
            </option>
        @endforeach
    </select>
</div>
                <div class="mb-2">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ old('tanggal') }}" required>
                </div>
                <div class="mb-2">
                    <label>Nama Transaksi</label>
                    <input type="text" name="nama_transaksi" class="form-control form-control-sm" value="{{ old('nama_transaksi') }}" required>
                </div>
                <div class="mb-2">
                    <label>Jenis</label>
                    <select name="jenis" class="form-select form-select-sm" required>
                        <option value="pemasukan" {{ old('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ old('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Nominal</label>
                    <input type="number" name="nominal" class="form-control form-control-sm" value="{{ old('nominal') }}" required>
                </div>
                <div class="mb-2">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control form-control-sm">{{ old('keterangan') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
{{-- End Modal Tambah --}}

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Buka modal otomatis kalau ada error validasi
        @if ($errors->any())
            @if (old('modal_type') == 'add')
                var addModal = new bootstrap.Modal(document.getElementById('modalTambahTransaksi'));
                addModal.show();
            @elseif (old('modal_type') == 'edit' && old('edit_item_id'))
                var editModal = new bootstrap.Modal(document.getElementById('modalEditTransaksi' + '{{ old('edit_item_id') }}'));
                editModal.show();
            @endif
        @endif
    });
</script>
@endpush
