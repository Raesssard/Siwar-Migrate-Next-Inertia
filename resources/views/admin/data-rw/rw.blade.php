@extends('admin.layouts.app')
@section('title', $title)
@section('content')

<style>
    .modal-body { max-height: 80vh; overflow-y: auto; }
    .modal-body-scroll { max-height: 65vh; overflow-y: auto; }
</style>

<div id="content">
    {{-- top bar --}}
    @include('admin.layouts.topbar')
    {{-- end top bar --}}

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Tabel RW</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambahRW">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <div class="card-body">
                        {{-- Alert pesan --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive table-container">
                            <table class="table table-hover table-sm text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nomor RW</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Mulai Menjabat</th>
                                        <th>Akhir Jabatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rw as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->nik }}</td>
                                            <td>{{ $data->nomor_rw }}</td>
                                            <td>{{ $data->nama_ketua_rw }}</td>
                                            <td>{{ $data->jabatan?->nama_jabatan ?? '-' }}</td>
                                            <td>{{ $data->mulai_menjabat }}</td>
                                            <td>{{ $data->akhir_jabatan }}</td>
                                            <td>
                                                <form action="{{ route('admin.rw.destroy', $data->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus RW ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditRW{{ $data->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit RW -->
                                        <div class="modal fade" id="modalEditRW{{ $data->id }}" tabindex="-1"
                                            aria-labelledby="modalEditRWLabel{{ $data->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content shadow-lg">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title">Edit Data RW</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.rw.update', $data->id) }}"
                                                        method="POST">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">NIK</label>
                                                                <input type="text"
                                                                    name="nik"
                                                                    value="{{ old('nik', $data->nik) }}"
                                                                    class="form-control @error('nik') is-invalid @enderror"
                                                                    readonly>
                                                                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Nomor RW</label>
                                                                <input type="text"
                                                                    name="nomor_rw"
                                                                    value="{{ old('nomor_rw', $data->nomor_rw) }}"
                                                                    class="form-control @error('nomor_rw') is-invalid @enderror"
                                                                    required>
                                                                @error('nomor_rw') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Ketua RW</label>
                                                                <input type="text"
                                                                    name="nama_ketua_rw"
                                                                    value="{{ old('nama_ketua_rw', $data->nama_ketua_rw) }}"
                                                                    class="form-control @error('nama_ketua_rw') is-invalid @enderror"
                                                                    required>
                                                                @error('nama_ketua_rw') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Jabatan</label>
                                                                <select name="jabatan_id"
                                                                    class="form-control @error('jabatan_id') is-invalid @enderror"
                                                                    required>
                                                                    <option value="">-- Pilih Jabatan --</option>
                                                                    @foreach ($jabatan as $j)
                                                                        <option value="{{ $j->id }}"
                                                                            {{ old('jabatan_id', $data->jabatan_id) == $j->id ? 'selected' : '' }}>
                                                                            {{ $j->nama_jabatan }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('jabatan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Mulai Menjabat</label>
                                                                <input type="date"
                                                                    name="mulai_menjabat"
                                                                    value="{{ old('mulai_menjabat', $data->mulai_menjabat) }}"
                                                                    class="form-control @error('mulai_menjabat') is-invalid @enderror"
                                                                    required>
                                                                @error('mulai_menjabat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Akhir Jabatan</label>
                                                                <input type="date"
                                                                    name="akhir_jabatan"
                                                                    value="{{ old('akhir_jabatan', $data->akhir_jabatan) }}"
                                                                    class="form-control @error('akhir_jabatan') is-invalid @enderror"
                                                                    required>
                                                                @error('akhir_jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination info -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 px-3">
                        <div class="text-muted mb-2">
                            Menampilkan {{ $rw->firstItem() }} - {{ $rw->lastItem() }} dari {{ $rw->total() }} data
                        </div>
                        <div>
                            {{ $rw->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                    <!-- Modal Tambah RW -->
                    <div class="modal fade" id="modalTambahRW" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content shadow-lg">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Tambah Data RW</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.rw.store') }}" method="POST" class="p-4">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">NIK</label>
                                            <input type="text" name="nik" value="{{ old('nik') }}" required
                                                class="form-control @error('nik') is-invalid @enderror"
                                                placeholder="Contoh: 1234567890987654" maxlength="16">
                                            @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nomor RW</label>
                                            <input type="text" name="nomor_rw" value="{{ old('nomor_rw') }}" required
                                                class="form-control @error('nomor_rw') is-invalid @enderror"
                                                placeholder="Contoh: 01">
                                            @error('nomor_rw') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Ketua RW</label>
                                            <input type="text" name="nama_ketua_rw" value="{{ old('nama_ketua_rw') }}" required
                                                class="form-control @error('nama_ketua_rw') is-invalid @enderror"
                                                placeholder="Contoh: Budi">
                                            @error('nama_ketua_rw') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jabatan</label>
                                            <select name="jabatan_id" class="form-control @error('jabatan_id') is-invalid @enderror" required>
                                                <option value="">-- Pilih Jabatan --</option>
                                                @foreach ($jabatan as $j)
                                                    <option value="{{ $j->id }}" {{ old('jabatan_id') == $j->id ? 'selected' : '' }}>
                                                        {{ $j->nama_jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('jabatan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mulai Menjabat</label>
                                            <input type="date" name="mulai_menjabat" value="{{ old('mulai_menjabat') }}" required
                                                class="form-control @error('mulai_menjabat') is-invalid @enderror">
                                            @error('mulai_menjabat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Akhir Jabatan</label>
                                            <input type="date" name="akhir_jabatan" value="{{ old('akhir_jabatan') }}" required
                                                class="form-control @error('akhir_jabatan') is-invalid @enderror">
                                            @error('akhir_jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> {{-- end card --}}
            </div>
        </div>
    </div>
</div>

{{-- Script untuk auto buka modal jika ada error --}}
@if ($errors->any() && !old('_method'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var tambahModal = new bootstrap.Modal(document.getElementById('modalTambahRW'));
            tambahModal.show();
        });
    </script>
@endif

@if ($errors->any() && old('_method') === 'PUT' && old('id'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var editModal = new bootstrap.Modal(document.getElementById('modalEditRW{{ old('id') }}'));
            editModal.show();
        });
    </script>
@endif
@endsection
