<div class="modal fade" id="modalTambahRt" tabindex="-1"
    aria-labelledby="modalTambahRtLabel" aria-hidden="true">
    
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahRtLabel">Tambah Data RT</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('rw.rukun_tetangga.store') }}" method="POST" class="p-4">
                    @csrf

                    {{-- Input tersembunyi untuk mengidentifikasi formulir ini secara spesifik --}}
                    <input type="hidden" name="form_type" value="rt_tambah"> 

                    <div class="mb-3">
                        <label for="no_kk" class="form-label">NO KK</label>
                        <input type="text" name="no_kk" id="no_kk" maxlength="16" required
                            value="{{ old('form_type') === 'rt_tambah' ? old('no_kk') : '' }}"
                            class="form-control {{ $errors->has('no_kk') && old('form_type') === 'rt_tambah' ? 'is-invalid' : '' }}"
                            placeholder="Contoh: 5233797890987659">
                        <small class="form-text text-muted">Masukkan No KK dari kepala keluarga yang akan menjadi pengurus RT.</small>
                        @if ($errors->has('no_kk') && old('form_type') === 'rt_tambah')
                            <div class="invalid-feedback">{{ $errors->first('no_kk') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" name="nik" id="nik" maxlength="16" required
                            value="{{ old('form_type') === 'rt_tambah' ? old('nik') : '' }}"
                            class="form-control {{ $errors->has('nik') && old('form_type') === 'rt_tambah' ? 'is-invalid' : '' }}"
                            placeholder="Contoh: 1234567890987654">
                        <small class="form-text text-muted">Masukkan NIK pengurus RT (16 digit).</small>
                        @if ($errors->has('nik') && old('form_type') === 'rt_tambah')
                            <div class="invalid-feedback">{{ $errors->first('nik') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="rt" class="form-label">RT</label>
                        <input type="text" name="rt" id="rt" maxlength="10" required
                            value="{{ old('form_type') === 'rt_tambah' ? old('rt') : '' }}"
                            class="form-control {{ $errors->has('rt') && old('form_type') === 'rt_tambah' ? 'is-invalid' : '' }}"
                            placeholder="Contoh: 01">
                        <small class="form-text text-muted">Masukkan Nomor RT yang akan dibentuk.</small>
                        @if ($errors->has('rt') && old('form_type') === 'rt_tambah')
                            <div class="invalid-feedback">{{ $errors->first('rt') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" required
                            value="{{ old('form_type') === 'rt_tambah' ? old('nama') : '' }}"
                            class="form-control {{ $errors->has('nama') && old('form_type') === 'rt_tambah' ? 'is-invalid' : '' }}"
                            placeholder="Contoh: Budi Santoso">
                        <small class="form-text text-muted">Masukkan nama lengkap pengurus RT.</small>
                        @if ($errors->has('nama') && old('form_type') === 'rt_tambah')
                            <div class="invalid-feedback">{{ $errors->first('nama') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="mulai_menjabat" class="form-label">Mulai Masa Jabatan</label>
                        <input type="date" name="mulai_menjabat" id="mulai_menjabat" required
                            value="{{ old('form_type') === 'rt_tambah' ? old('mulai_menjabat') : '' }}"
                            class="form-control {{ $errors->has('mulai_menjabat') && old('form_type') === 'rt_tambah' ? 'is-invalid' : '' }}">
                        <small class="form-text text-muted">Pilih tanggal mulai masa jabatan.</small>
                        @if ($errors->has('mulai_menjabat') && old('form_type') === 'rt_tambah')
                            <div class="invalid-feedback">{{ $errors->first('mulai_menjabat') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="akhir_jabatan" class="form-label">Akhir Masa Jabatan</label>
                        <input type="date" name="akhir_jabatan" id="akhir_jabatan" required
                            value="{{ old('form_type') === 'rt_tambah' ? old('akhir_jabatan') : '' }}"
                            class="form-control {{ $errors->has('akhir_jabatan') && old('form_type') === 'rt_tambah' ? 'is-invalid' : '' }}">
                        <small class="form-text text-muted">Pilih tanggal akhir masa jabatan.</small>
                        @if ($errors->has('akhir_jabatan') && old('form_type') === 'rt_tambah')
                            <div class="invalid-feedback">{{ $errors->first('akhir_jabatan') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="jabatan_id" class="form-label">Jabatan</label>
                        <select name="jabatan_id" id="jabatan_id"
                            class="form-select {{ $errors->has('jabatan_id') && old('form_type') === 'rt_tambah' ? 'is-invalid' : '' }}" required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatan_filter as $id => $nama)
                                <option value="{{ $id }}"
                                    {{ old('form_type') === 'rt_tambah' && old('jabatan_id') == $id ? 'selected' : '' }}>
                                    {{ ucfirst($nama) }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('jabatan_id') && old('form_type') === 'rt_tambah')
                            <div class="invalid-feedback">{{ $errors->first('jabatan_id') }}</div>
                        @endif
                    </div>

                    <div class="modal-footer sticky-bottom bg-light shadow-sm">
                        <button type="submit" class="btn btn-primary w-100">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
