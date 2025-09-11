@extends('rt.layouts.app')

@section('title', $title)

@section('content')

    <div id="content">

        {{-- top bar --}}
        @include('rt.layouts.topbar')

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

                {{-- Form Filter dan Pencarian --}}
                <form action="{{ route('rt.tagihan.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
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
                        {{-- DROPDOWN FILTER KARTU KELUARGA --}}
                        <select name="no_kk_filter" class="form-select form-select-sm">
                            <option value="">Semua Kartu Keluarga</option>
                            @foreach ($kartuKeluargaForFilter as $item)
                                <option value="{{ $item->no_kk }}"
                                    {{ request('no_kk_filter') == $item->no_kk ? 'selected' : '' }}>
                                    KK {{ $item->no_kk }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('rt.tagihan.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </form>

                <div class="mb-3">
                    <a href="{{ route('rt.tagihan.export') }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Iuran ke Excel
                    </a>
                </div>

                <!-- Tabel Tagihan Manual -->
                <div class="col-xl-12 col-lg-7 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-2">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Tagihan Manual</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NAMA IURAN</th>
                                            <th>NO KK</th>
                                            <th>NOMINAL</th>
                                            <th>TGL TAGIH</th>
                                            <th>TGL TEMPO</th>
                                            <th>STATUS</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tagihanManual as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->no_kk ?? '-' }}</td>
                                                <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>
                                                    @if ($item->status_bayar === 'sudah_bayar')
                                                        <span class="badge bg-success">Sudah Bayar</span>
                                                    @else
                                                        <span class="badge bg-warning">Belum Bayar</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <!-- Tombol Detail -->
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#modalDetailkk{{ $item->no_kk }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <!-- Tombol Edit -->
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditTagihan{{ $item->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <!-- Tombol Hapus -->
                                                    <form action="{{ route('tagihan.destroy', $item->id) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus tagihan ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $tagihanManual->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>

                <!-- Tabel Tagihan Otomatis -->
                <div class="col-xl-12 col-lg-7 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-2">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Tagihan Otomatis</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-sm text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NAMA IURAN</th>
                                            <th>NO KK</th>
                                            <th>NOMINAL</th>
                                            <th>TGL TAGIH</th>
                                            <th>TGL TEMPO</th>
                                            <th>STATUS</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tagihanOtomatis as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->no_kk ?? '-' }}</td>
                                                <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>
                                                    @if ($item->status_bayar === 'sudah_bayar')
                                                        <span class="badge bg-success">Sudah Bayar</span>
                                                    @else
                                                        <span class="badge bg-warning">Belum Bayar</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <!-- Tombol Detail -->
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#modalDetailkkOtomatis{{ $item->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <!-- Tombol Edit -->
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditTagihanOtomatis{{ $item->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <!-- Tombol Hapus -->
                                                    <form action="{{ route('rt.tagihan.destroy', $item->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus tagihan otomatis ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $tagihanOtomatis->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>

                {{-- Modals for Edit --}}
                @foreach ($tagihanManual as $item)
                    <div class="modal fade" id="modalEditTagihan{{ $item->id }}" tabindex="-1"
                        aria-labelledby="modalEditTagihanLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content shadow-lg">
                                <div class="modal-header bg-warning text-white">
                                    <h5 class="modal-title" id="modalEditTagihanLabel{{ $item->id }}">Edit Data
                                        Tagihan
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Form Edit Tagihan --}}
                                    <form action="{{ route('rt.tagihan.update', $item->id) }}" method="POST"
                                        class="p-4">
                                        @csrf
                                        @method('PUT')



                                        {{-- Tambahkan field status_bayar di modal edit --}}
                                        <div class="mb-3">
                                            <label for="status_bayar" class="form-label">Status Pembayaran</label>
                                            <select name="status_bayar"
                                                class="form-select @error('status_bayar') is-invalid @enderror">
                                                <option value="belum_bayar"
                                                    {{ old('status_bayar', $item->status_bayar) == 'belum_bayar' ? 'selected' : '' }}>
                                                    Belum Bayar</option>
                                                <option value="sudah_bayar"
                                                    {{ old('status_bayar', $item->status_bayar) == 'sudah_bayar' ? 'selected' : '' }}>
                                                    Sudah Bayar</option>
                                            </select>
                                            @error('status_bayar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Tambahkan field tgl_bayar di modal edit --}}
                                        <div class="mb-3">
                                            <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
                                            <input type="datetime-local" name="tgl_bayar"
                                                class="form-control @error('tgl_bayar') is-invalid @enderror"
                                                value="{{ old('tgl_bayar', $item->tgl_bayar ? \Carbon\Carbon::parse($item->tgl_bayar)->format('Y-m-d\TH:i') : '') }}">
                                            @error('tgl_bayar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <input type="hidden" name="id_iuran" value="5">
                                        <input type="hidden" name="id_iuran" value="{{ $item->id_iuran }}">

                                        {{-- Tambahkan field kategori_pembayaran di modal edit --}}
                                        <div class="mb-3">
                                            <label for="kategori_pembayaran" class="form-label">Kategori
                                                Pembayaran</label>
                                            <select name="kategori_pembayaran"
                                                class="form-select @error('kategori_pembayaran') is-invalid @enderror">
                                                <option value="">Pilih Kategori</option>
                                                <option value="transfer"
                                                    {{ old('kategori_pembayaran', $item->kategori_pembayaran) == 'transfer' ? 'selected' : '' }}>
                                                    Transfer</option>
                                                <option value="tunai"
                                                    {{ old('kategori_pembayaran', $item->kategori_pembayaran) == 'tunai' ? 'selected' : '' }}>
                                                    Tunai</option>
                                            </select>
                                            @error('kategori_pembayaran')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Tambahkan field bukti_transfer di modal edit --}}
                                        <div class="mb-3">
                                            <label for="bukti_transfer" class="form-label">Bukti Transfer
                                                (URL/Path)</label>
                                            <input type="text" name="bukti_transfer"
                                                class="form-control @error('bukti_transfer') is-invalid @enderror"
                                                value="{{ old('bukti_transfer', $item->bukti_transfer) }}"
                                                placeholder="URL atau Path Bukti Transfer">
                                            @error('bukti_transfer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        {{-- Hanya tampilkan field nominal manual --}}
                                        <div id="manualFieldEdit{{ $item->id }}">
                                            <label class="form-label">Nominal</label>
                                            <input type="number" name="nominal_manual" class="form-control"
                                                placeholder="Masukkan nominal manual"
                                                value="{{ old('nominal_manual', $item->nominal) }}" required>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button type="submit" class="btn btn-warning">Update Data</button>
                                        </div>

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalDetailkk{{ $item->no_kk }}" tabindex="-1"
                        aria-labelledby="modalDetailkkLabel{{ $item->no_kk }}" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content shadow border-0">
                                <!-- Header -->
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="modalDetailkkLabel{{ $item->no_kk }}">
                                        Detail Tagihan
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>

                                <!-- Body -->
                                <div class="modal-body p-4">
                                    <!-- Informasi KK -->
                                    <div class="mb-4">
                                        <h6 class="text-success mb-3 fw-bold">Informasi KK</h6>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>No. KK:</strong> {{ $item->no_kk }}</p>
                                                <p class="mb-1">
                                                    <strong>Kepala Keluarga:</strong>
                                                    @php
                                                        $kepala = $item->kartuKeluarga->warga->firstWhere(
                                                            'status_hubungan_dalam_keluarga',
                                                            'kepala keluarga',
                                                        );
                                                    @endphp
                                                    {{ $kepala->nama ?? '-' }}
                                                </p>
                                                <p class="mb-1"><strong>Alamat:</strong>
                                                    {{ $item->kartuKeluarga->alamat }}</p>
                                                <p class="mb-1"><strong>RT/RW:</strong>
                                                    {{ $item->kartuKeluarga->rukunTetangga->nomor_rt }}/{{ $item->kartuKeluarga->rw->nomor_rw }}
                                                </p>
                                                <p class="mb-1"><strong>Golongan:</strong>
                                                    {{ $item->kartuKeluarga->kategoriGolongan->jenis }}
                                                </p>
                                                <p class="mb-1"><strong>Kode Pos:</strong>
                                                    {{ $item->kartuKeluarga->kode_pos }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Kelurahan:</strong>
                                                    {{ $item->kartuKeluarga->kelurahan }}
                                                </p>
                                                <p class="mb-1"><strong>Kecamatan:</strong>
                                                    {{ $item->kartuKeluarga->kecamatan }}
                                                </p>
                                                <p class="mb-1"><strong>Kabupaten:</strong>
                                                    {{ $item->kartuKeluarga->kabupaten }}
                                                </p>
                                                <p class="mb-1"><strong>Provinsi:</strong>
                                                    {{ $item->kartuKeluarga->provinsi }}
                                                </p>
                                                <p class="mb-1"><strong>Tanggal Terbit:</strong>
                                                    {{ \Carbon\Carbon::parse($item->kartuKeluarga->tgl_terbit)->isoFormat('D MMM Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Anggota Keluarga -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="text-success fw-bold mb-0">Informasi Tagihan</h6>

                                        </div>
                                        <div class="table-responsive table-container">
                                            <table class="table table-bordered table-sm align-middle text-nowrap">
                                                <thead class="table-success text-center small">
                                                    <tr>
                                                        <th scope="col">No</th>
                                                        <th scope="col">NAMA IURAN</th>
                                                        <th scope="col">NO KK</th>
                                                        <th scope="col">NOMINAL</th>
                                                        <th scope="col">TGL TAGIH</th>
                                                        <th scope="col">TGL TEMPO</th>
                                                        <th scope="col">JENIS</th>
                                                        <th scope="col">STATUS</th>
                                                        <th scope="col">TGL BAYAR</th>
                                                        <th scope="col">KATEGORI PEMBAYARAN</th>
                                                        <th scope="col">BUKTI TRANSFER</th>

                                                    </tr>
                                                </thead>
                                                <tbody class="small">

                                                    @php
                                                        $tagihanKK = $tagihanManual->where('no_kk', $item->no_kk);
                                                    @endphp

                                                    @forelse ($tagihanKK as $item)
                                                        <tr>
                                                            <th scope="row">{{ $loop->iteration }}</th>
                                                            <td>{{ $item->nama }}</td>
                                                            <td>{{ $item->no_kk ?? '-' }}</td>
                                                            <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}
                                                            </td>
                                                            <td><span
                                                                    class="badge bg-secondary">{{ ucfirst($item->jenis) }}</span>
                                                            </td>
                                                            <td>
                                                                @if ($item->status_bayar === 'sudah_bayar')
                                                                    <span class="badge bg-success">Sudah Bayar</span>
                                                                @else
                                                                    <span class="badge bg-warning">Belum Bayar</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $item->tgl_bayar ? \Carbon\Carbon::parse($item->tgl_bayar)->translatedFormat('d F Y H:i') : '-' }}
                                                            </td>
                                                            <td>{{ $item->kategori_pembayaran ?? '-' }}</td>
                                                            <td>
                                                                @if ($item->bukti_transfer)
                                                                    <a href="{{ asset('storage/' . $item->bukti_transfer) }}"
                                                                        target="_blank" class="btn btn-info btn-sm">Lihat
                                                                        Bukti</a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="12" class="text-center">Tidak ada data tagihan.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                                        <i class="bi bi-check2-circle"></i> Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Modals untuk Tagihan Otomatis --}}
                @foreach ($tagihanOtomatis as $item)
                    <div class="modal fade" id="modalEditTagihanOtomatis{{ $item->id }}" tabindex="-1"
                        aria-labelledby="modalEditTagihanLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content shadow-lg">
                                <div class="modal-header bg-warning text-white">
                                    <h5 class="modal-title" id="modalEditTagihanLabel{{ $item->id }}">Edit Data
                                        Tagihan</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Form Edit Tagihan --}}
                                    <form action="{{ route('rt.tagihan.update', $item->id) }}" method="POST"
                                        class="p-4">
                                        @csrf
                                        @method('PUT')



                                        {{-- Tambahkan field status_bayar di modal edit --}}
                                        <div class="mb-3">
                                            <label for="status_bayar" class="form-label">Status Pembayaran</label>
                                            <select name="status_bayar"
                                                class="form-select @error('status_bayar') is-invalid @enderror">
                                                <option value="belum_bayar"
                                                    {{ old('status_bayar', $item->status_bayar) == 'belum_bayar' ? 'selected' : '' }}>
                                                    Belum Bayar</option>
                                                <option value="sudah_bayar"
                                                    {{ old('status_bayar', $item->status_bayar) == 'sudah_bayar' ? 'selected' : '' }}>
                                                    Sudah Bayar</option>
                                            </select>
                                            @error('status_bayar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Tambahkan field tgl_bayar di modal edit --}}
                                        <div class="mb-3">
                                            <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
                                            <input type="datetime-local" name="tgl_bayar"
                                                class="form-control @error('tgl_bayar') is-invalid @enderror"
                                                value="{{ old('tgl_bayar', $item->tgl_bayar ? \Carbon\Carbon::parse($item->tgl_bayar)->format('Y-m-d\TH:i') : '') }}">
                                            @error('tgl_bayar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <input type="hidden" name="id_iuran" value="5">
                                        <input type="hidden" name="id_iuran" value="{{ $item->id_iuran }}">

                                        {{-- Tambahkan field kategori_pembayaran di modal edit --}}
                                        <div class="mb-3">
                                            <label for="kategori_pembayaran" class="form-label">Kategori
                                                Pembayaran</label>
                                            <select name="kategori_pembayaran"
                                                class="form-select @error('kategori_pembayaran') is-invalid @enderror">
                                                <option value="">Pilih Kategori</option>
                                                <option value="transfer"
                                                    {{ old('kategori_pembayaran', $item->kategori_pembayaran) == 'transfer' ? 'selected' : '' }}>
                                                    Transfer</option>
                                                <option value="tunai"
                                                    {{ old('kategori_pembayaran', $item->kategori_pembayaran) == 'tunai' ? 'selected' : '' }}>
                                                    Tunai</option>
                                            </select>
                                            @error('kategori_pembayaran')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Tambahkan field bukti_transfer di modal edit --}}
                                        <div class="mb-3">
                                            <label for="bukti_transfer" class="form-label">Bukti Transfer
                                                (URL/Path)</label>
                                            <input type="text" name="bukti_transfer"
                                                class="form-control @error('bukti_transfer') is-invalid @enderror"
                                                value="{{ old('bukti_transfer', $item->bukti_transfer) }}"
                                                placeholder="URL atau Path Bukti Transfer">
                                            @error('bukti_transfer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        {{-- Hanya tampilkan field nominal manual --}}
                                        <div id="manualFieldEdit{{ $item->id }}">
                                            <label class="form-label">Nominal</label>
                                            <input type="number" name="nominal_manual" class="form-control"
                                                placeholder="Masukkan nominal manual"
                                                value="{{ old('nominal_manual', $item->nominal) }}" required>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button type="submit" class="btn btn-warning">Update Data</button>
                                        </div>

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalDetailkkOtomatis{{ $item->id }}" tabindex="-1"
                        aria-labelledby="modalDetailkkLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content shadow border-0">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="modalDetailkkLabel{{ $item->id }}">
                                        Detail Tagihan
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>

                                <!-- Body -->
                                <div class="modal-body p-4">
                                    <!-- Informasi KK -->
                                    <div class="mb-4">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>No. KK:</strong> {{ $item->no_kk }}</p>
                                                <p class="mb-1">
                                                    <strong>Kepala Keluarga:</strong>
                                                    @php
                                                        $kepala = $item->kartuKeluarga->warga->firstWhere(
                                                            'status_hubungan_dalam_keluarga',
                                                            'kepala keluarga',
                                                        );
                                                    @endphp
                                                    {{ $kepala->nama ?? '-' }}
                                                </p>
                                                <p class="mb-1"><strong>Alamat:</strong>
                                                    {{ $item->kartuKeluarga->alamat }}</p>
                                                <p class="mb-1"><strong>RT/RW:</strong>
                                                    {{ $item->kartuKeluarga->rukunTetangga->nomor_rt }}/{{ $item->kartuKeluarga->rw->nomor_rw }}
                                                </p>
                                                <p class="mb-1"><strong>Golongan:</strong>
                                                    {{ $item->kartuKeluarga->golongan }}
                                                </p>
                                                <p class="mb-1"><strong>Kode Pos:</strong>
                                                    {{ $item->kartuKeluarga->kode_pos }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Kelurahan:</strong>
                                                    {{ $item->kartuKeluarga->kelurahan }}
                                                </p>
                                                <p class="mb-1"><strong>Kecamatan:</strong>
                                                    {{ $item->kartuKeluarga->kecamatan }}
                                                </p>
                                                <p class="mb-1"><strong>Kabupaten:</strong>
                                                    {{ $item->kartuKeluarga->kabupaten }}
                                                </p>
                                                <p class="mb-1"><strong>Provinsi:</strong>
                                                    {{ $item->kartuKeluarga->provinsi }}
                                                </p>
                                                <p class="mb-1"><strong>Tanggal Terbit:</strong>
                                                    {{ \Carbon\Carbon::parse($item->kartuKeluarga->tgl_terbit)->isoFormat('D MMM Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Anggota Keluarga -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="text-success fw-bold mb-0">Informasi Tagihan</h6>

                                        </div>
                                        <div class="table-responsive table-container">
                                            <table class="table table-bordered table-sm align-middle text-nowrap">
                                                <thead class="table-success text-center small">
                                                    <tr>
                                                        <th scope="col">No</th>
                                                        <th scope="col">NAMA IURAN</th>
                                                        <th scope="col">NO KK</th>
                                                        <th scope="col">NOMINAL</th>
                                                        <th scope="col">TGL TAGIH</th>
                                                        <th scope="col">TGL TEMPO</th>
                                                        <th scope="col">JENIS</th>
                                                        <th scope="col">STATUS</th>
                                                        <th scope="col">TGL BAYAR</th>
                                                        <th scope="col">KATEGORI PEMBAYARAN</th>
                                                        <th scope="col">BUKTI TRANSFER</th>

                                                    </tr>
                                                </thead>
                                                <tbody class="small">

                                                    @php
                                                        $tagihanKK = $tagihanOtomatis->where('id', $item->id);
                                                    @endphp


                                                    @forelse ($tagihanKK as $item)
                                                        <tr>
                                                            <th scope="row">{{ $loop->iteration }}</th>
                                                            <td>{{ $item->nama }}</td>
                                                            <td>{{ $item->no_kk ?? '-' }}</td>
                                                            <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}
                                                            </td>
                                                            <td><span
                                                                    class="badge bg-secondary">{{ ucfirst($item->jenis) }}</span>
                                                            </td>
                                                            <td>
                                                                @if ($item->status_bayar === 'sudah_bayar')
                                                                    <span class="badge bg-success">Sudah Bayar</span>
                                                                @else
                                                                    <span class="badge bg-warning">Belum Bayar</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $item->tgl_bayar ? \Carbon\Carbon::parse($item->tgl_bayar)->translatedFormat('d F Y H:i') : '-' }}
                                                            </td>
                                                            <td>{{ $item->kategori_pembayaran ?? '-' }}</td>
                                                            <td>
                                                                @if ($item->bukti_transfer)
                                                                    <a href="{{ asset('storage/' . $item->bukti_transfer) }}"
                                                                        target="_blank" class="btn btn-info btn-sm">Lihat
                                                                        Bukti</a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="12" class="text-center">Tidak ada data tagihan.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                                        <i class="bi bi-check2-circle"></i> Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Modal Tambah Tagihan --}}
                <div class="modal fade" id="modalTambahTagihan" tabindex="-1" aria-labelledby="modalTambahTagihanLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content shadow-lg">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="modalTambahTagihanLabel">Tambah Data Tagihan</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                {{-- Form Tambah Tagihan --}}
                                <form action="{{ route('rt.iuran.store') }}" method="POST" class="p-4">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Iuran</label>
                                        <input type="text" name="nama" placeholder="Nama Iuran"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            value="{{ old('nama') }}">
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="tgl_tagih" class="form-label">Tanggal Tagih</label>
                                        <input type="date" name="tgl_tagih"
                                            class="form-control @error('tgl_tagih') is-invalid @enderror"
                                            value="{{ old('tgl_tagih') }}" required>
                                        @error('tgl_tagih')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="tgl_tempo" class="form-label">Tanggal Tempo</label>
                                        <input type="date" name="tgl_tempo" id="tgl_tempo"
                                            class="form-control @error('tgl_tempo') is-invalid @enderror"
                                            value="{{ old('tgl_tempo') }}" required>
                                        @error('tgl_tempo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="jenis" class="form-label">Jenis Tagihan</label>
                                        <input type="text" class="form-control" value="Manual" disabled>
                                        <input type="hidden" name="jenis" value="manual">
                                    </div>

                                    {{-- Tambahkan field no_kk di modal tambah --}}
                                    <div class="mb-3">
                                        <label for="no_kk" class="form-label">Nomor Kartu Keluarga</label>
                                        <input type="text" name="no_kk"
                                            class="form-control @error('no_kk') is-invalid @enderror"
                                            value="{{ old('no_kk') }}" placeholder="Nomor Kartu Keluarga" required>
                                        @error('no_kk')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Tambahkan field status_bayar di modal tambah --}}
                                    <div class="mb-3">
                                        <label for="status_bayar" class="form-label">Status Pembayaran</label>
                                        <select name="status_bayar"
                                            class="form-select @error('status_bayar') is-invalid @enderror">
                                            <option value="belum_bayar"
                                                {{ old('status_bayar') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar
                                            </option>
                                            <option value="sudah_bayar"
                                                {{ old('status_bayar') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar
                                            </option>
                                        </select>
                                        @error('status_bayar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Tambahkan field tgl_bayar di modal tambah --}}
                                    <div class="mb-3">
                                        <label for="tgl_bayar" class="form-label">Tanggal Bayar (Opsional)</label>
                                        <input type="datetime-local" name="tgl_bayar"
                                            class="form-control @error('tgl_bayar') is-invalid @enderror"
                                            value="{{ old('tgl_bayar') }}">
                                        @error('tgl_bayar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    {{-- Tambahkan field kategori_pembayaran di modal tambah --}}
                                    <div class="mb-3">
                                        <label for="kategori_pembayaran" class="form-label">Kategori Pembayaran
                                            (Opsional)</label>
                                        <select name="kategori_pembayaran"
                                            class="form-select @error('kategori_pembayaran') is-invalid @enderror">
                                            <option value="">Pilih Kategori</option>
                                            <option value="transfer"
                                                {{ old('kategori_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer
                                            </option>
                                            <option value="tunai"
                                                {{ old('kategori_pembayaran') == 'tunai' ? 'selected' : '' }}>
                                                Tunai</option>
                                        </select>
                                        @error('kategori_pembayaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Tambahkan field bukti_transfer di modal tambah --}}
                                    <div class="mb-3">
                                        <label for="bukti_transfer" class="form-label">Bukti Transfer (URL/Path,
                                            Opsional)</label>
                                        <input type="text" name="bukti_transfer"
                                            class="form-control @error('bukti_transfer') is-invalid @enderror"
                                            value="{{ old('bukti_transfer') }}"
                                            placeholder="URL atau Path Bukti Transfer">
                                        @error('bukti_transfer')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Hanya tampilkan field nominal manual --}}
                                    <div class="mb-3" id="manual-field">
                                        <label class="form-label">Nominal</label>
                                        <input type="number" name="nominal_manual" class="form-control"
                                            placeholder="Masukkan nominal manual" value="{{ old('nominal_manual') }}"
                                            required>
                                    </div>

                                    <hr>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                                    </div>
                                </form>
                                {{-- Kondisi untuk menampilkan error validasi form tambah --}}
                                @if ($errors->any() && !request()->has('search') && !request()->has('no_kk_filter'))
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
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- Hapus semua skrip JavaScript terkait toggleNominalFields dan toggleEditFields --}}
    <script>
        // Tidak ada script JavaScript yang diperlukan untuk toggling field karena hanya ada jenis manual.
        // Pastikan DOM sudah dimuat sebelum menjalankan script
        document.addEventListener('DOMContentLoaded', function() {
            // Jika ada logika lain yang perlu dijalankan saat DOM dimuat, tambahkan di sini.
            // Misalnya, inisialisasi Bootstrap modals secara manual jika diperlukan.
        });
    </script>
@endpush
