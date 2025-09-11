<div class="modal fade" id="modalEditwarga{{ $item->nik }}" tabindex="-1"
    aria-labelledby="modalEditwargaLabel{{ $item->nik }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalEditwargaLabel{{ $item->nik }}">
                    Edit Data Warga
                </h5>
                <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <form action="{{ route('rw.warga.update', $item->nik) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_to" value="{{ route('rw.warga.index') }}">

                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik"
                                class="form-control @error('nik') is-invalid @enderror"
                                value="{{ old('nik', $item->nik) }}" required>
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor KK</label>
                            <input type="text" name="no_kk" class="form-control"
                                value="{{ $item->no_kk }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $item->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin"
                                class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                required>
                                <option value="laki-laki"
                                    {{ (old('jenis_kelamin') ?? $item->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>
                                <option value="perempuan"
                                    {{ (old('jenis_kelamin') ?? $item->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir"
                                class="form-control @error('tempat_lahir') is-invalid @enderror"
                                value="{{ old('tempat_lahir', $item->tempat_lahir) }}" required>
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                value="{{ old('tanggal_lahir', $item->tanggal_lahir) }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Agama</label>
                            <input type="text" name="agama"
                                class="form-control @error('agama') is-invalid @enderror"
                                value="{{ old('agama', $item->agama) }}" required>
                            @error('agama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pendidikan</label>
                            <input type="text" name="pendidikan"
                                class="form-control @error('pendidikan') is-invalid @enderror"
                                value="{{ old('pendidikan', $item->pendidikan) }}" required>
                            @error('pendidikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" name="pekerjaan"
                                class="form-control @error('pekerjaan') is-invalid @enderror"
                                value="{{ old('pekerjaan', $item->pekerjaan) }}" required>
                            @error('pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status Perkawinan</label>
                            <select name="status_perkawinan"
                                class="form-select @error('status_perkawinan') is-invalid @enderror"
                                required>
                                <option value="menikah"
                                    {{ old('status_perkawinan', $item->status_perkawinan) == 'menikah' ? 'selected' : '' }}>
                                    Menikah</option>
                                <option value="belum menikah"
                                    {{ old('status_perkawinan', $item->status_perkawinan) == 'belum menikah' ? 'selected' : '' }}>
                                    Belum Menikah</option>
                                <option value="cerai hidup"
                                    {{ old('status_perkawinan', $item->status_perkawinan) == 'cerai hidup' ? 'selected' : '' }}>
                                    Cerai Hidup</option>
                                <option value="cerai mati"
                                    {{ old('status_perkawinan', $item->status_perkawinan) == 'cerai mati' ? 'selected' : '' }}>
                                    Cerai Mati</option>
                            </select>
                            @error('status_perkawinan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hubungan dengan KK</label>
                            <select name="status_hubungan_dalam_keluarga"
                                class="form-select @error('status_hubungan_dalam_keluarga') is-invalid @enderror"
                                required>
                                <option value="kepala keluarga"
                                    {{ old('status_hubungan_dalam_keluarga', $item->status_hubungan_dalam_keluarga) == 'kepala keluarga' ? 'selected' : '' }}>
                                    Kepala Keluarga</option>
                                <option value="istri"
                                    {{ old('status_hubungan_dalam_keluarga', $item->status_hubungan_dalam_keluarga) == 'istri' ? 'selected' : '' }}>
                                    Istri</option>
                                <option value="anak"
                                    {{ old('status_hubungan_dalam_keluarga', $item->status_hubungan_dalam_keluarga) == 'anak' ? 'selected' : '' }}>
                                    Anak</option>
                                <option value="famili lain"
                                    {{ old('status_hubungan_dalam_keluarga', $item->status_hubungan_dalam_keluarga) == 'famili lain' ? 'selected' : '' }}>
                                    Famili Lain</option>
                            </select>
                            @error('status_hubungan_dalam_keluarga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Golongan Darah</label>
                            <select name="golongan_darah"
                                class="form-select @error('golongan_darah') is-invalid @enderror"
                                required>
                                @foreach (['A', 'B', 'AB', 'O'] as $gd)
                                    <option value="{{ $gd }}"
                                        {{ old('golongan_darah', $item->golongan_darah) == $gd ? 'selected' : '' }}>
                                        {{ $gd }}</option>
                                @endforeach
                            </select>
                            @error('golongan_darah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kewarganegaraan</label>
                            <select name="kewarganegaraan"
                                class="form-select @error('kewarganegaraan') is-invalid @enderror"
                                required>
                                <option value="WNI"
                                    {{ old('kewarganegaraan', $item->kewarganegaraan) == 'WNI' ? 'selected' : '' }}>
                                    WNI</option>
                                <option value="WNA"
                                    {{ old('kewarganegaraan', $item->kewarganegaraan) == 'WNA' ? 'selected' : '' }}>
                                    WNA</option>
                            </select>
                            @error('kewarganegaraan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah"
                                class="form-control @error('nama_ayah') is-invalid @enderror"
                                value="{{ old('nama_ayah', $item->nama_ayah) }}" required>
                            @error('nama_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu"
                                class="form-control @error('nama_ibu') is-invalid @enderror"
                                value="{{ old('nama_ibu', $item->nama_ibu) }}" required>
                            @error('nama_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status Warga</label>
                            <select name="status_warga"
                                class="form-select @error('status_warga') is-invalid @enderror"
                                required>
                                <option value="penduduk"
                                    {{ old('status_warga', $item->status_warga) == 'penduduk' ? 'selected' : '' }}>
                                    Penduduk</option>
                                <option value="pendatang"
                                    {{ old('status_warga', $item->status_warga) == 'pendatang' ? 'selected' : '' }}>
                                    Pendatang</option>
                            </select>
                            @error('status_warga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Paspor, KITAS, KITAP selalu tampil --}}
                        <div class="col-md-6">
                            <label class="form-label">Nomor Paspor</label>
                            <input type="text" name="no_paspor" class="form-control"
                                value="{{ old('no_paspor', $item->no_paspor) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Terbit Paspor</label>
                            <input type="date" name="tgl_terbit_paspor" class="form-control"
                                value="{{ old('tgl_terbit_paspor', $item->tgl_terbit_paspor) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Berakhir Paspor</label>
                            <input type="date" name="tgl_berakhir_paspor" class="form-control"
                                value="{{ old('tgl_berakhir_paspor', $item->tgl_berakhir_paspor) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor KITAS</label>
                            <input type="text" name="no_kitas" class="form-control"
                                value="{{ old('no_kitas', $item->no_kitas) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor KITAP</label>
                            <input type="text" name="no_kitap" class="form-control"
                                value="{{ old('no_kitap', $item->no_kitap) }}">
                        </div>

                        {{-- Tambahan untuk Pendatang --}}
                        <div class="pendatang-fields row g-3" style="display: none;">
                            <div class="col-md-6">
                                <label class="form-label">Alamat Asal</label>
                                <input type="text" name="alamat_asal" class="form-control"
                                    value="{{ old('alamat_asal', $item->alamat_asal) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alamat Domisili</label>
                                <input type="text" name="alamat_domisili" class="form-control"
                                    value="{{ old('alamat_domisili', $item->alamat_domisili) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Mulai Tinggal</label>
                                <input type="date" name="tanggal_mulai_tinggal" class="form-control"
                                    value="{{ old('tanggal_mulai_tinggal', $item->tanggal_mulai_tinggal) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tujuan Pindah</label>
                                <input type="text" name="tujuan_pindah" class="form-control"
                                    value="{{ old('tujuan_pindah', $item->tujuan_pindah) }}">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0">
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script toggle Pendatang --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("modalEditwarga{{ $item->nik }}");
    if (!modal) return;

    const statusWarga = modal.querySelector('select[name="status_warga"]');
    const pendatangFields = modal.querySelector('.pendatang-fields');

    function togglePendatangFields() {
        if (statusWarga.value === "pendatang") {
            pendatangFields.style.display = "";
        } else {
            pendatangFields.style.display = "none";
        }
    }

    togglePendatangFields();
    statusWarga.addEventListener("change", togglePendatangFields);
    modal.addEventListener("shown.bs.modal", togglePendatangFields);
});
</script>
