<div class="modal fade" id="modalTambahPengaduan" tabindex="-1" aria-labelledby="modalTambahPengaduanLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahPengaduanLabel">Tambah Pengaduan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('warga.pengaduan.store') }}" method="POST" class="p-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="level" class="form-label">Tujuan</label>
                        <select name="level" id="level" required value="{{ old('level') }}"
                            class="form-control @error('level')
                            is-invalid
                        @enderror">
                            <option value="" selected disabled>Pilih Tujuan Pengaduan</option>
                            <option value="rt">RT</option>
                            <option value="rw">RW</option>
                        </select>
                        @error('level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" name="judul" id="judul" required value="{{ old('judul') }}"
                            class="form-control @error('judul') is-invalid @enderror"
                            placeholder="Masukkan Judul Pengaduan">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi</label>
                        <textarea name="isi" id="isi" rows="5" required class="form-control @error('isi') is-invalid @enderror"
                            placeholder="Masukkan Isi Pengaduan">{{ old('isi') }}</textarea>
                        @error('isi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label">File (Max 2MB: .jpg, .jpeg, .png, .mp4, .doc,
                            .docx, .pdf)</label>
                        <input type="file" name="file" id="file"
                            class="form-control @error('file') is-invalid @enderror">
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
