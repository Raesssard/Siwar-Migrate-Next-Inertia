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

            {{-- Filter --}}
            <form action="{{ route('rt_iuran.index') }}" method="GET" class="row g-2 align-items-center px-3 pb-2">
                <div class="col-md-5 col-sm-12">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari Data Iuran...">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 d-flex gap-2">
                    <select name="rt" class="form-select form-select-sm">
                        <option value="">Semua RT</option>
                        @foreach ($rt as $item)
                            <option value="{{ $item->nomor_rt }}"
                                {{ request('rt') == $item->nomor_rt ? 'selected' : '' }}>
                                RT {{ $item->nomor_rt }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('rt_iuran.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                </div>
            </form>

            <!-- Tabel Iuran Manual -->
            <div class="col-xl-12 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Tabel Daftar Iuran Manual</h6>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahIuran">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-container">
                            <table class="table table-hover table-sm scroll-table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nominal</th>
                                        <th>Nama Iuran</th>
                                        <th>Tgl Tagih</th>
                                        <th>Tgl Tempo</th>
                                        <th>Jenis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($iuran->where('jenis', 'manual') as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tgl_tagih)->translatedFormat('d F Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tgl_tempo)->translatedFormat('d F Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $item->jenis == 'otomatis' ? 'primary' : 'secondary' }}">
                                                    {{ ucfirst($item->jenis) }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('rt_iuran.destroy', $item->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditIuran{{ $item->id }}">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data iuran manual.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <div class="text-muted mb-2">
                                Menampilkan {{ $iuran->firstItem() ?? '0' }}-{{ $iuran->lastItem() }}
                                dari total {{ $iuran->total() }} data
                            </div>
                            <div>
                                {{ $iuran->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Tabel -->

        </div>
    </div>
</div>

{{-- Modal Tambah Iuran --}}
<div class="modal fade" id="modalTambahIuran" tabindex="-1" aria-labelledby="modalTambahIuranLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTambahIuranLabel">Tambah Iuran Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('rt_iuran.store') }}" method="POST" class="p-3">
          @csrf
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Iuran</label>
            <input type="text" name="nama" class="form-control" id="nama" required>
          </div>
          <div class="mb-3">
            <label for="tgl_tagih" class="form-label">Tanggal Tagih</label>
            <input type="date" name="tgl_tagih" class="form-control" id="tgl_tagih" required>
          </div>
          <div class="mb-3">
            <label for="tgl_tempo" class="form-label">Tanggal Tempo</label>
            <input type="date" name="tgl_tempo" class="form-control" id="tgl_tempo" required>
          </div>
          <div class="mb-3">
            <label for="jenis" class="form-label">Jenis Iuran</label>
            <select name="jenis" id="jenis" class="form-control" required>
              <option value="manual">Manual</option>
              <option value="otomatis">Otomatis</option>
            </select>
          </div>
          {{-- Manual --}}
          <div class="mb-3" id="manualFields">
            <label for="nominal" class="form-label">Nominal Manual</label>
            <input type="number" name="nominal" class="form-control" id="nominal">
          </div>
          {{-- Otomatis --}}
          <div id="otomatisFields" style="display:none;">
            <label class="form-label">Nominal per Golongan</label>
            @foreach ($golongan_list as $golongan)
              <div class="mb-2">
                <label for="nominal_{{ $golongan }}">Golongan {{ ucfirst($golongan) }}</label>
                <input type="number" name="nominal_{{ $golongan }}" class="form-control"
                  id="nominal_{{ $golongan }}">
              </div>
            @endforeach
          </div>
          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Modal Edit Iuran --}}
@foreach ($iuran as $item)
<div class="modal fade" id="modalEditIuran{{ $item->id }}" tabindex="-1"
    aria-labelledby="modalEditIuranLabel{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow-lg">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="modalEditIuranLabel{{ $item->id }}">Edit Data Iuran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('rt_iuran.update', $item->id) }}" method="POST" class="p-3">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label for="namaEdit{{ $item->id }}" class="form-label">Nama Iuran</label>
            <input type="text" name="nama" class="form-control" id="namaEdit{{ $item->id }}" 
                value="{{ $item->nama }}" required>
          </div>
          <div class="mb-3">
            <label for="tgl_tagihEdit{{ $item->id }}" class="form-label">Tanggal Tagih</label>
            <input type="date" name="tgl_tagih" class="form-control" id="tgl_tagihEdit{{ $item->id }}" 
                value="{{ $item->tgl_tagih }}" required>
          </div>
          <div class="mb-3">
            <label for="tgl_tempoEdit{{ $item->id }}" class="form-label">Tanggal Tempo</label>
            <input type="date" name="tgl_tempo" class="form-control" id="tgl_tempoEdit{{ $item->id }}" 
                value="{{ $item->tgl_tempo }}" required>
          </div>
          <div class="mb-3">
            <label for="jenisEdit{{ $item->id }}" class="form-label">Jenis Iuran</label>
            <select name="jenis" id="jenisEdit{{ $item->id }}" class="form-control" required>
              <option value="manual" {{ $item->jenis == 'manual' ? 'selected' : '' }}>Manual</option>
              <option value="otomatis" {{ $item->jenis == 'otomatis' ? 'selected' : '' }}>Otomatis</option>
            </select>
          </div>
          {{-- Manual --}}
          <div class="mb-3" id="manualFieldEdit{{ $item->id }}" style="{{ $item->jenis == 'otomatis' ? 'display:none;' : '' }}">
            <label for="nominalEdit{{ $item->id }}" class="form-label">Nominal Manual</label>
            <input type="number" name="nominal" class="form-control" id="nominalEdit{{ $item->id }}" 
                value="{{ $item->nominal }}">
          </div>
          {{-- Otomatis --}}
          <div id="otomatisFieldsEdit{{ $item->id }}" style="{{ $item->jenis == 'manual' ? 'display:none;' : '' }}">
            <label class="form-label">Nominal per Golongan</label>
            @foreach ($golongan_list as $golongan)
              <div class="mb-2">
                <label for="nominal_{{ $golongan }}_{{ $item->id }}">Golongan {{ ucfirst($golongan) }}</label>
                <input type="number" name="nominal_{{ $golongan }}" class="form-control"
                    id="nominal_{{ $golongan }}_{{ $item->id }}"
                    value="{{ $item['nominal_'.$golongan] ?? '' }}">
              </div>
            @endforeach
          </div>
          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-warning">Update Data</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection

{{-- Script Toggle --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tambah Iuran
    function toggleNominalFields() {
        const jenisSelect = document.getElementById('jenis');
        const otomatisFields = document.getElementById('otomatisFields');
        const manualField = document.getElementById('manualFields');
        if (!jenisSelect || !otomatisFields || !manualField) return;
        const isOtomatis = jenisSelect.value === 'otomatis';
        otomatisFields.style.display = isOtomatis ? 'block' : 'none';
        manualField.style.display = isOtomatis ? 'none' : 'block';
        otomatisFields.querySelectorAll('input[type="number"]').forEach(input => {
            input.required = isOtomatis;
            if (!isOtomatis) input.value = '';
        });
        const manualInput = manualField.querySelector('input[type="number"]');
        if (manualInput) {
            manualInput.required = !isOtomatis;
            if (isOtomatis) manualInput.value = '';
        }
    }
    const jenisAddSelect = document.getElementById('jenis');
    if (jenisAddSelect) {
        toggleNominalFields();
        jenisAddSelect.addEventListener('change', toggleNominalFields);
        const tambahIuranModal = document.getElementById('modalTambahIuran');
        if (tambahIuranModal) {
            tambahIuranModal.addEventListener('shown.bs.modal', toggleNominalFields);
        }
    }

    // Edit Iuran
    function toggleEditFields(itemId) {
        const jenisSelect = document.getElementById('jenisEdit' + itemId);
        const otomatisField = document.getElementById('otomatisFieldsEdit' + itemId);
        const manualField = document.getElementById('manualFieldEdit' + itemId);
        if (!jenisSelect || !otomatisField || !manualField) return;
        const isOtomatis = jenisSelect.value === 'otomatis';
        otomatisField.style.display = isOtomatis ? 'block' : 'none';
        manualField.style.display = isOtomatis ? 'none' : 'block';
        otomatisField.querySelectorAll('input[type="number"]').forEach(input => {
            input.required = isOtomatis;
            if (!isOtomatis) input.value = '';
        });
        const manualInput = manualField.querySelector('input[type="number"]');
        if (manualInput) {
            manualInput.required = !isOtomatis;
            if (isOtomatis) manualInput.value = '';
        }
    }
    @if ($iuran->isNotEmpty())
        @foreach ($iuran as $item)
            (function() {
                const itemId = '{{ $item->id }}';
                toggleEditFields(itemId);
                const jenisEditSelect = document.getElementById('jenisEdit' + itemId);
                if (jenisEditSelect) {
                    jenisEditSelect.addEventListener('change', function() {
                        toggleEditFields(itemId);
                    });
                }
                const editModal = document.getElementById('modalEditIuran' + itemId);
                if (editModal) {
                    editModal.addEventListener('shown.bs.modal', function() {
                        toggleEditFields(itemId);
                    });
                }
            })();
        @endforeach
    @endif
});
</script>
