@extends('admin.layouts.app')

@section('title', $title)

@section('content')

<style>
    .modal-body {
        max-height: 80vh;
        overflow-y: auto;
    }

    .modal-body-scroll {
        max-height: 65vh;
        overflow-y: auto;
    }
</style>

<!-- Main Content -->
<div id="content">

    {{-- top bar --}}
    @include('admin.layouts.topbar')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-12 col-lg-7">
                <div class="card shadow mb-4">

                    <div class="card-header d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Tabel Rukun Tetangga</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambahRt">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive table-container">
                            <table class="table table-hover table-sm scroll-table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nomor RT</th>
                                        <th>Nama Ketua RT</th>
                                        <th>Mulai Menjabat</th>
                                        <th>Akhir Jabatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rukun_tetangga as $rt)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $rt->nik }}</td>
                                            <td>{{ $rt->rt }}</td>
                                            <td>{{ $rt->nama }}</td>
                                            <td>{{ $rt->mulai_menjabat }}</td>
                                            <td>{{ $rt->akhir_jabatan }}</td>
                                            <td>
                                                <form action="{{ route('admin.rt.destroy', $rt->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus RT ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>

                                                <button type="button" class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditRT{{ $rt->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit RT -->
<div class="modal fade" id="modalEditRT{{ $rt->id }}" tabindex="-1"
    aria-labelledby="modalEditRTLabel{{ $rt->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalEditRTLabel{{ $rt->id }}">
                    Edit Data RT
                </h5>
                <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="{{ route('admin.rt.update', $rt->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $rt->id }}">

                <div class="modal-body">
                    {{-- NIK --}}
                    <div class="mb-3">
                        <label for="nik{{ $rt->id }}" class="form-label">NIK</label>
                        <input type="text"
                            name="nik" id="nik{{ $rt->id }}"
                            value="{{ old('id') == $rt->id ? old('nik') : $rt->nik }}"
                            class="form-control @error('nik') is-invalid @enderror"
                            placeholder="Masukkan NIK (16 digit)" required readonly>
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- RT --}}
                    <div class="mb-3">
                        <label for="rt{{ $rt->id }}" class="form-label">Nomor RT</label>
                        <input type="text"
                            name="rt" id="rt{{ $rt->id }}"
                            value="{{ old('id') == $rt->id ? old('rt') : $rt->rt }}"
                            class="form-control @error('rt') is-invalid @enderror"
                            placeholder="Masukkan Nomor RT (Contoh : 01, 02, 03)" required>
                        @error('rt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label for="nama{{ $rt->id }}" class="form-label">Nama Ketua RT</label>
                        <input type="text"
                            name="nama" id="nama{{ $rt->id }}"
                            value="{{ old('id') == $rt->id ? old('nama') : $rt->nama }}"
                            class="form-control @error('nama') is-invalid @enderror"
                            placeholder="Masukkan Nama Ketua RT (Sesuai dengan NIK)" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mulai Menjabat --}}
                    <div class="mb-3">
                        <label for="mulai_menjabat{{ $rt->id }}" class="form-label">Mulai Masa Jabatan</label>
                        <input type="date"
                            name="mulai_menjabat" id="mulai_menjabat{{ $rt->id }}"
                            value="{{ old('id') == $rt->id ? old('mulai_menjabat') : $rt->mulai_menjabat }}"
                            class="form-control @error('mulai_menjabat') is-invalid @enderror" required>
                        @error('mulai_menjabat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Akhir Jabatan --}}
                    <div class="mb-3">
                        <label for="akhir_jabatan{{ $rt->id }}" class="form-label">Akhir Masa Jabatan</label>
                        <input type="date"
                            name="akhir_jabatan" id="akhir_jabatan{{ $rt->id }}"
                            value="{{ old('id') == $rt->id ? old('akhir_jabatan') : $rt->akhir_jabatan }}"
                            class="form-control @error('akhir_jabatan') is-invalid @enderror" required>
                        @error('akhir_jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

                    <!-- Info dan Pagination -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 px-3">
                        <div class="text-muted mb-2">
                            Menampilkan {{ $rukun_tetangga->firstItem() }}-{{ $rukun_tetangga->lastItem() }} dari total
                            {{ $rukun_tetangga->total() }} data
                        </div>
                        <div>
                            {{ $rukun_tetangga->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                    <!-- Modal Tambah RT -->
                    <div class="modal fade" id="modalTambahRt" tabindex="-1" aria-labelledby="modalTambahRtLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content shadow-lg">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="modalTambahRtLabel">Tambah Data RT</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.rt.store') }}" method="POST" class="p-4">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="nik" class="form-label">NIK</label>
                                            <input type="text" name="nik" id="nik" maxlength="16"
                                                value="{{ old('nik') }}"
                                                placeholder="Masukkan NIK (16 digit)"
                                                class="form-control @error('nik') is-invalid @enderror" required>
                                            @error('nik')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="rt" class="form-label">Nomor RT</label>
                                            <input type="text" name="rt" id="rt"
                                                value="{{ old('rt') }}"
                                                placeholder="Masukkan Nomor RT (Contoh : 01, 02, 03)"
                                                class="form-control @error('rt') is-invalid @enderror" required>
                                            @error('rt')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="nama" class="form-label">Nama Ketua RT</label>
                                            <input type="text" name="nama" id="nama"
                                                value="{{ old('nama') }}"
                                                placeholder="Masukkan Nama Ketua RT (Sesuai dengan NIK)"
                                                class="form-control @error('nama') is-invalid @enderror" required>
                                            @error('nama')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="mulai_menjabat" class="form-label">Mulai Masa Jabatan</label>
                                            <input type="date" name="mulai_menjabat" id="mulai_menjabat"
                                                value="{{ old('mulai_menjabat') }}"
                                                class="form-control @error('mulai_menjabat') is-invalid @enderror" required>
                                            @error('mulai_menjabat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="akhir_jabatan" class="form-label">Akhir Masa Jabatan</label>
                                            <input type="date" name="akhir_jabatan" id="akhir_jabatan"
                                                value="{{ old('akhir_jabatan') }}"
                                                class="form-control @error('akhir_jabatan') is-invalid @enderror" required>
                                            @error('akhir_jabatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

{{-- Script untuk auto buka modal jika ada error --}}
{{-- Script untuk auto buka modal Tambah RT jika ada error dari store --}}
@if ($errors->any() && !old('_method'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var tambahModal = new bootstrap.Modal(document.getElementById('modalTambahRt'));
            tambahModal.show();
        });
    </script>
@endif

{{-- Script untuk auto buka modal Edit RT jika ada error dari update --}}
@if ($errors->any() && old('_method') === 'PUT' && old('id'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var editModal = new bootstrap.Modal(document.getElementById('modalEditRT{{ old('id') }}'));
            editModal.show();
        });
    </script>
@endif
@endsection
