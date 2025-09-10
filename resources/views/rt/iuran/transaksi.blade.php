@extends('rt.layouts.app')

@section('title', $title)

@section('content')

    <div id="content">
        {{-- Top Bar --}}
        @include('rt.layouts.topbar')
        {{-- End Top Bar --}}

        <div class="container-fluid">
            <div class="row">


                {{-- Form Filter dan Pencarian --}}
                <form action="{{ route('rt.transaksi.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                    <div class="col-md-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm" placeholder="Cari Transaksi...">
                    </div>
                    <div class="col-md-2">
                        <select name="bulan" class="form-select form-select-sm">
                            <option value="">Semua Bulan</option>
                            @foreach (range(1, 12) as $bln)
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
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('rt.transaksi.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </form>

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

                <div class="mt-3">
                    <a href="{{ route('rt.transaksi.export') }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Iuran ke Excel
                    </a>
                </div>

                {{-- Tabel Transaksi --}}
                <div class="col-xl-12 col-lg-12 mt-3">
                    <div class="card shadow mb-4">
                        <div class="card-header py-2 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Data Transaksi</h6>
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                data-bs-target="#modalTambahTransaksi">
                                <i class="fas fa-plus"></i> Tambah Transaksi
                            </button>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm scroll-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">RT</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Nama Transaksi</th>
                                            <th class="text-center">Jenis</th>
                                            <th class="text-center">Nominal</th>
                                            <th class="text-center">Keterangan</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($paginatedTransaksi as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $item->rt }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                                </td>
                                                <td class="text-center">{{ $item->nama_transaksi }}</td>
                                                <td class="text-center">
                                                    @if ($item->jenis === 'pemasukan')
                                                        <span class="badge bg-success">Pemasukkan</span>
                                                    @else
                                                        <span class="badge bg-danger">Pengeluaran</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">Rp{{ number_format($item->nominal, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">{{ $item->keterangan }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditTransaksi{{ $item->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <form action="{{ route('rt.transaksi.destroy', $item->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                                                {{-- colspan disesuaikan --}}
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
                                <div class="text-muted mb-2">
                                    Menampilkan {{ $paginatedTransaksi->firstItem() ?? 0 }} -
                                    {{ $paginatedTransaksi->lastItem() }} dari total {{ $paginatedTransaksi->total() }}
                                    data
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

    {{-- Modal Tambah Transaksi --}}
    <div class="modal fade" id="modalTambahTransaksi" tabindex="-1" aria-labelledby="modalTambahTransaksiLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTambahTransaksiLabel">Tambah Data Transaksi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('rt.transaksi.store') }}" method="POST" class="p-3">
                        @csrf

                        <div class="mb-3">
                            <label for="rt" class="form-label">Nomor RT</label>
                            <select name="rt" id="rt_add" class="form-select @error('rt') is-invalid @enderror"
                                required>
                                <option value="" selected disabled>Pilih RT</option>
                                @foreach ($rukun_tetangga as $rt_value => $rt_text)
                                    <option value="{{ $rt_text }}"
                                        {{ $user->rukunTetangga->rt === $rt_text ? 'selected' : '' }}>
                                        RT {{ $rt_text }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Transaksi</label>
                            <input type="date" name="tanggal"
                                class="form-control @error('tanggal') is-invalid @enderror"
                                value="{{ old('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama_transaksi" class="form-label">Nama Transaksi</label>
                            <input type="text" name="nama_transaksi"
                                class="form-control @error('nama_transaksi') is-invalid @enderror"
                                placeholder="Contoh: Penerimaan Iuran Warga" value="{{ old('nama_transaksi') }}"
                                required>
                            @error('nama_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis Transaksi</label>
                            <select name="jenis" id="jenis_add"
                                class="form-select @error('jenis') is-invalid @enderror" required>
                                <option value="" selected disabled>Pilih Jenis Transaksi</option>
                                <option value="pemasukan">Pemasukkan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal (Rp)</label>
                            <input type="number" name="nominal"
                                class="form-control @error('nominal') is-invalid @enderror"
                                placeholder="Masukkan nominal (contoh: 25000)" value="{{ old('nominal', 0) }}"
                                min="0">
                            @error('nominal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan transaksi">{{ old('keterangan') }}</textarea>
                        </div>

                        <hr>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                        </div>
                    </form>

                    @if ($errors->any() && old('modal_type') == 'add')
                        <div class="alert alert-danger mt-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Transaksi (Pemasukan sudah disembunyikan sebelumnya) --}}
    @foreach ($transaksi as $item)
        <div class="modal fade" id="modalEditTransaksi{{ $item->id }}" tabindex="-1"
            aria-labelledby="modalEditTransaksiLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="modalEditTransaksiLabel{{ $item->id }}">Edit Data Transaksi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('rt.transaksi.update', $item->id) }}" method="POST" class="p-3">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="rt" class="form-label">Nomor RT</label>
                                <select name="rt" id="rt_edit_{{ $item->id }}"
                                    class="form-select @error('rt') is-invalid @enderror" required>
                                    <option value="" disabled>Pilih RT</option>
                                    @foreach ($rukun_tetangga as $rt_value => $rt_text)
                                        <option value="{{ $rt_text }}"
                                            {{ $user->rukunTetangga->rt === $rt_text ? 'selected' : '' }}>
                                            RT {{ $rt_text }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal Transaksi</label>
                                <input type="date" name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal', \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d')) }}"
                                    required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nama_transaksi" class="form-label">Nama Transaksi</label>
                                <input type="text" name="nama_transaksi"
                                    class="form-control @error('nama_transaksi') is-invalid @enderror"
                                    placeholder="Contoh: Pembayaran Iuran Kebersihan"
                                    value="{{ old('nama_transaksi', $item->nama_transaksi) }}" required>
                                @error('nama_transaksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jenis" class="form-label">Jenis Transaksi</label>
                                <select name="jenis" id="jenis_edit_{{ $item->id }}"
                                    class="form-select @error('jenis') is-invalid @enderror" required>
                                    <option value="" disabled>Pilih Jenis Transaksi</option>
                                    <option value="pemasukan"
                                        {{ old('jenis', $item->jenis) === 'pemasukan' ? 'selected' : '' }}>Pemasukkan
                                    </option>
                                    <option value="pengeluaran"
                                        {{ old('jenis', $item->jenis) === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran
                                    </option>
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nominal" class="form-label">Nominal (Rp)</label>
                                <input type="number" name="nominal"
                                    class="form-control @error('nominal') is-invalid @enderror"
                                    placeholder="Masukkan nominal (contoh: 50000)"
                                    value="{{ old('nominal', $item->nominal) }}" min="0">
                                @error('nominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan transaksi">{{ old('keterangan', $item->keterangan) }}</textarea>
                            </div>

                            <hr>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning">Update Transaksi</button>
                            </div>
                        </form>

                        @if ($errors->any() && old('modal_type') == 'edit' && old('edit_item_id') == $item->id)
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tangani pesan error validasi agar modal tetap terbuka
            @if ($errors->any())
                @if (old('modal_type') == 'add')
                    var addModal = new bootstrap.Modal(document.getElementById('modalTambahTransaksi'));
                    addModal.show();
                @elseif (old('modal_type') == 'edit' && old('edit_item_id'))
                    var editModal = new bootstrap.Modal(document.getElementById('modalEditTransaksi' +
                        '{{ old('edit_item_id') }}'));
                    editModal.show();
                @endif
            @endif
        });
    </script>
@endpush
