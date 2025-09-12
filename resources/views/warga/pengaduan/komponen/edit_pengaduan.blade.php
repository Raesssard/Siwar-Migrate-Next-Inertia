<div class="modal fade" id="modalEditPengaduan{{ $item->id }}" tabindex="-1"
    aria-labelledby="modalEditPengaduanLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalEditPengaduanLabel{{ $item->id }}">Edit
                    Pengaduan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            {{-- Tambahkan enctype="multipart/form-data" pada form --}}
            <form action="{{ route('pengaduan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body modal-body-scroll px-4">
                    <div class="mb-3">
                        <label for="judul{{ $item->id }}" class="form-label">Judul</label>
                        <input type="text" name="judul" id="judul{{ $item->id }}" required
                            value="{{ old('judul', $item->judul) }}" {{-- Gunakan old() untuk mempertahankan input jika ada error --}}
                            class="form-control @error('judul') is-invalid @enderror"
                            placeholder="Masukkan Judul Pengaduan">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="isi{{ $item->id }}" class="form-label">Isi</label>
                        <textarea name="isi" id="isi{{ $item->id }}" rows="5" required
                            class="form-control @error('isi') is-invalid @enderror" placeholder="Masukkan Isi Pengaduan">{{ old('isi', $item->isi) }}</textarea> {{-- Gunakan old() --}}
                        @error('isi')
                            <div class="invalid-feedback">{{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Bagian untuk edit dokumen --}}
                    <div class="mb-3">
                        <label for="dokumen{{ $item->id }}" class="form-label">Dokumen (Opsional, Max 2MB: .doc,
                            .docx, .pdf)</label>
                        @if ($item->file_path)
                            <p class="mb-1">
                                Dokumen saat ini:
                                <a href="{{ Storage::url($item->file_path) }}" target="_blank"
                                    class="text-decoration-none">
                                    @if ($item->file_name && Str::endsWith(strtolower($item->file_name), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <i class="fas fa-image me-1"></i>
                                        {{ $item->original_file_name ?? 'Foto' }}
                                    @elseif($item->file_name && Str::endsWith(strtolower($item->file_name), ['mp4', 'mov', 'avi', 'mkv', 'webm']))
                                        <i class="fas fa-video me-1"></i>
                                        {{ $item->original_file_name ?? 'Video' }}
                                    @elseif($item->file_name && Str::endsWith(strtolower($item->file_name), ['doc', 'docx', 'pdf', 'pptx', 'xlsx']))
                                        <i class="fas fa-file-alt me-1"></i>
                                        {{ $item->original_file_name ?? 'Dokumen' }}
                                    @else
                                        {{ $item->file_name ?? 'File tidak ada/diketahui' }}
                                    @endif
                                </a>
                            </p>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="hapus_dokumen_lama"
                                    id="hapusDokumenLama{{ $item->id }}" value="1">
                                <label class="form-check-label" for="hapusDokumenLama{{ $item->id }}">
                                    Hapus dokumen ini
                                </label>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada dokumen terlampir saat ini.</p>
                        @endif

                        <input type="file" name="file" id="file{{ $item->id }}"
                            class="form-control @error('file') is-invalid @enderror">
                        <small class="form-text text-muted">Unggah dokumen baru untuk mengganti yang lama, atau biarkan
                            kosong jika tidak ingin mengubah.</small>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Akhir bagian edit dokumen --}}

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
