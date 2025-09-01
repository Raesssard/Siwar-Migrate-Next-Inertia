@extends('rw.layouts.app')

@section('title', $title)

@section('content')
<div id="content">
    {{-- Topbar --}}
    @include('rw.layouts.topbar')

    <div class="container-fluid">
        <div class="row">
            {{-- Session Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Form Filter --}}
            <form action="{{ route('tagihan.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari Data Tagihan...">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <select name="no_kk_filter" class="form-select form-select-sm">
                        <option value="">Semua Kartu Keluarga</option>
                        @foreach ($kartuKeluargaForFilter as $item)
                            <option value="{{ $item->no_kk }}" {{ request('no_kk_filter') == $item->no_kk ? 'selected' : '' }}>
                                KK {{ $item->no_kk }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('tagihan.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                </div>
            </form>

            <!-- Tabel Tagihan Manual -->
            <div class="col-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-2 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">Tabel Tagihan Manual</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-container">
                            <table class="table table-hover table-sm text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Iuran</th>
                                        <th>No KK</th>
                                        <th>Nominal</th>
                                        <th>Tgl Tagih</th>
                                        <th>Tgl Tempo</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tagihanManual as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->no_kk }}</td>
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
                                            <td>
                                                {{-- Modal Edit --}}
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#modalEditManual{{ $item->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                {{-- Modal Detail --}}
                                                <button type="button" class="btn btn-success btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#modalDetailManual{{ $item->id }}">
                                                    <i class="fas fa-info"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- Modal Edit Manual --}}
                                        <div class="modal fade" id="modalEditManual{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning">
                                                        <h5 class="modal-title">Edit Tagihan Manual</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('tagihan.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id_iuran" value="{{ $item->id_iuran }}">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label>Status Bayar</label>
                                                                <select name="status_bayar" class="form-select">
                                                                    <option value="belum_bayar" {{ $item->status_bayar == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                                                    <option value="sudah_bayar" {{ $item->status_bayar == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Nominal</label>
                                                                <input type="number" name="nominal" class="form-control" value="{{ $item->nominal }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Kategori Pembayaran</label>
                                                                <select name="kategori_pembayaran" class="form-select">
                                                                    <option value="" {{ !$item->kategori_pembayaran ? 'selected' : '' }}>-</option>
                                                                    <option value="tunai" {{ $item->kategori_pembayaran == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                                                    <option value="transfer" {{ $item->kategori_pembayaran == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Bukti Transfer</label>
                                                                <input type="text" name="bukti_transfer" class="form-control" value="{{ $item->bukti_transfer }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Detail Manual --}}
                                        <div class="modal fade" id="modalDetailManual{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title">Detail Tagihan Manual</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Nama:</strong> {{ $item->nama }}</p>
                                                        <p><strong>No KK:</strong> {{ $item->no_kk }}</p>
                                                        <p><strong>Nominal:</strong> Rp{{ number_format($item->nominal, 0, ',', '.') }}</p>
                                                        <p><strong>Status Bayar:</strong> {{ ucfirst(str_replace('_',' ',$item->status_bayar)) }}</p>
                                                        <p><strong>Tgl Tagih:</strong> {{ $item->tgl_tagih }}</p>
                                                        <p><strong>Tgl Tempo:</strong> {{ $item->tgl_tempo }}</p>
                                                        @if ($item->tgl_bayar)
                                                            <p><strong>Tgl Bayar:</strong> {{ $item->tgl_bayar }}</p>
                                                        @endif
                                                        @if ($item->kategori_pembayaran)
                                                            <p><strong>Kategori Pembayaran:</strong> {{ ucfirst($item->kategori_pembayaran) }}</p>
                                                        @endif
                                                        @if ($item->bukti_transfer)
                                                            <p><strong>Bukti Transfer:</strong> {{ $item->bukti_transfer }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr><td colspan="8" class="text-center">Tidak ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $tagihanManual->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

            <!-- Tabel Tagihan Otomatis -->
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-2 bg-success text-white">
                        <h6 class="m-0 font-weight-bold">Tabel Tagihan Otomatis</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-container">
                            <table class="table table-hover table-sm text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Iuran</th>
                                        <th>No KK</th>
                                        <th>Nominal</th>
                                        <th>Tgl Tagih</th>
                                        <th>Tgl Tempo</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tagihanOtomatis as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->no_kk }}</td>
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
                                            <td>
                                                {{-- Modal Edit --}}
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#modalEditOtomatis{{ $item->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                {{-- Modal Detail --}}
                                                <button type="button" class="btn btn-success btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#modalDetailOtomatis{{ $item->id }}">
                                                    <i class="fas fa-info"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- Modal Edit Otomatis --}}
                                        <div class="modal fade" id="modalEditOtomatis{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning">
                                                        <h5 class="modal-title">Edit Tagihan Otomatis</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('tagihan.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id_iuran" value="{{ $item->id_iuran }}">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label>Status Bayar</label>
                                                                <select name="status_bayar" class="form-select">
                                                                    <option value="belum_bayar" {{ $item->status_bayar == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                                                    <option value="sudah_bayar" {{ $item->status_bayar == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Kategori Pembayaran</label>
                                                                <select name="kategori_pembayaran" class="form-select">
                                                                    <option value="" {{ !$item->kategori_pembayaran ? 'selected' : '' }}>-</option>
                                                                    <option value="tunai" {{ $item->kategori_pembayaran == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                                                    <option value="transfer" {{ $item->kategori_pembayaran == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Bukti Transfer</label>
                                                                <input type="text" name="bukti_transfer" class="form-control" value="{{ $item->bukti_transfer }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Detail Otomatis --}}
                                        <div class="modal fade" id="modalDetailOtomatis{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title">Detail Tagihan Otomatis</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Nama:</strong> {{ $item->nama }}</p>
                                                        <p><strong>No KK:</strong> {{ $item->no_kk }}</p>
                                                        <p><strong>Nominal:</strong> Rp{{ number_format($item->nominal, 0, ',', '.') }}</p>
                                                        <p><strong>Status Bayar:</strong> {{ ucfirst(str_replace('_',' ',$item->status_bayar)) }}</p>
                                                        <p><strong>Tgl Tagih:</strong> {{ $item->tgl_tagih }}</p>
                                                        <p><strong>Tgl Tempo:</strong> {{ $item->tgl_tempo }}</p>
                                                        @if ($item->tgl_bayar)
                                                            <p><strong>Tgl Bayar:</strong> {{ $item->tgl_bayar }}</p>
                                                        @endif
                                                        @if ($item->kategori_pembayaran)
                                                            <p><strong>Kategori Pembayaran:</strong> {{ ucfirst($item->kategori_pembayaran) }}</p>
                                                        @endif
                                                        @if ($item->bukti_transfer)
                                                            <p><strong>Bukti Transfer:</strong> {{ $item->bukti_transfer }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr><td colspan="8" class="text-center">Tidak ada data.</td></tr>
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
@endsection
