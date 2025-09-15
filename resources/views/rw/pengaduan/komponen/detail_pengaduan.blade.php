<style>
    .file-section {
        margin-top: 0.25rem;
        /* Added margin for separation */
    }

    .file-display {
        position: relative;
        width: 200px;
        height: 150px;
        border: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f8f9fa;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 5px;
    }

    .file-display img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Overlay dan teks tanpa dokumen */
    .file-display .view-file-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }

    .file-display:hover .view-file-overlay {
        opacity: 1;
    }

    .file-display .view-file-overlay i {
        color: white;
        font-size: 2rem;
    }

    .no-file-text {
        color: #6c757d;
        font-style: italic;
    }
</style>

@php
    if ($item->status === 'sudah') {
        $color = 'primary';
    } elseif ($item->status === 'selesai') {
        $color = 'success';
    } else {
        $color = 'warning';
    }
@endphp

<div class="modal fade" id="modalDetailPengaduan{{ $item->id }}" tabindex="-1"
    aria-labelledby="modalDetailPengaduanLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-{{ $color }} text-white">
                <h5 class="modal-title mb-0" id="modalDetailPengaduanLabel{{ $item->id }}">
                    Detail Pengaduan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body px-4 pt-4 pb-3">
                <h4 class="fw-bold text-{{ $color }} mb-3">
                    {{ $item->judul }}</h4>

                <ul class="list-unstyled mb-3 small">
                    <li>
                        <strong>Tanggal:</strong>
                        {{ \Carbon\Carbon::parse($item->created_at)->isoFormat('dddd, D MMMM Y') }}
                    </li>
                    <li>
                        <strong>RT {{ $item->warga->kartuKeluarga->rukunTetangga->rt ?? '-' }}/RW
                            {{ $item->warga->kartuKeluarga->rw->nomor_rw ?? '-' }}</strong>
                    </li>
                </ul>

                <hr class="my-2">

                <div class="mb-2">
                    <strong class="d-block mb-1">Isi Pengaduan:</strong>
                    <div class="border rounded bg-light p-3" style="line-height: 1.6;">
                        {{ $item->isi }}
                    </div>
                </div>

                {{-- Tambahkan bagian ini untuk menampilkan dokumen --}}
                @if ($item->file_path)
                    <div class="mb-3">
                        <strong class="d-block mb-1">Foto/Video:</strong>
                        <p class="mb-2">
                            <a href="{{ Storage::url($item->file_path) }}" target="_blank"
                                class="btn btn-sm btn-info text-white">
                                <i class="fas fa-file-download me-1"></i> Lihat/Unduh File
                                ({{ $item->original_file_name ?? 'Dokumen' }})
                            </a>
                        </p>
                        <small class="text-muted">
                            Dokumen akan dibuka di tab baru.
                        </small>
                    </div>
                @endif
                {{-- Akhir bagian dokumen --}}
                <div class="file-section">
                    <div class="file-display">
                        @if ($item->file_path && $item->file_name)
                            @php
                                $fileExtension = pathinfo($item->file_path, PATHINFO_EXTENSION);
                                $isPdf = in_array(strtolower($fileExtension), ['pdf']);
                                $filePath = asset('storage/' . $item->file_path);
                                $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                            @endphp

                            @if (in_array($extension, ['pdf']))
                                {{-- Tampilkan PDF --}}
                                <div class="pdf-thumbnail-container"
                                    onclick="openDocumentModal('{{ $filePath }}', true)">
                                    <i class="far fa-file-pdf pdf-icon"></i>
                                    <p class="pdf-filename">Lihat PDF</p>
                                </div>
                            @elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                {{-- Tampilkan Gambar --}}
                                <img src="{{ $filePath }}" alt="Bukti foto {{ $item->judul }}"
                                    onclick="openDocumentModal('{{ $filePath }}', false)"
                                    style="max-width:150px;cursor:pointer">
                            @elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv', 'webm']))
                                {{-- Tampilkan Video --}}
                                <video controls style="max-width:200px;cursor:pointer">
                                    <source src="{{ $filePath }}" type="video/{{ $extension }}">
                                    Browser tidak mendukung video ini.
                                </video>
                            @elseif (in_array($extension, ['doc', 'docx']))
                                {{-- Tampilkan Dokumen Word --}}
                                <div class="doc-thumbnail-container"
                                    onclick="window.open('{{ asset('storage/' . $filePath) }}', '_blank')">
                                    <i class="far fa-file-word text-primary fa-3x"></i>
                                    <p class="doc-filename">Lihat Dokumen Word</p>
                                </div>
                            @else
                                {{-- File tidak dikenal --}}
                                <p><i class="fas fa-file"></i> File tidak didukung</p>
                            @endif

                            <div class="view-file-overlay"
                                onclick="openDocumentModal('{{ $filePath }}', {{ $isPdf ? 'true' : 'false' }})">
                                <i class="fas fa-eye"></i>
                            </div>
                        @else
                            <p class="no-file-text">Tidak ada foto/video</p>
                        @endif
                    </div>
                    @if ($item->foto_bukti)
                        <div class="d-flex align-items-center gap-3 mt-3">
                            <div class="file-display mt-3">
                                @php
                                    $fileExtension = pathinfo($item->foto_bukti, PATHINFO_EXTENSION);
                                    $isPdf = in_array(strtolower($fileExtension), ['pdf']);
                                    $filePath = asset('storage/' . $item->foto_bukti);
                                    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                @endphp

                                @if (in_array($extension, ['pdf']))
                                    {{-- Tampilkan PDF --}}
                                    <div class="pdf-thumbnail-container"
                                        onclick="openDocumentModal('{{ $filePath }}', true)">
                                        <i class="far fa-file-pdf pdf-icon"></i>
                                        <p class="pdf-filename">Lihat PDF</p>
                                    </div>
                                @elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    {{-- Tampilkan Gambar --}}
                                    <img src="{{ $filePath }}" alt="Bukti foto {{ $item->judul }}"
                                        onclick="openDocumentModal('{{ $filePath }}', false)"
                                        style="max-width:150px;cursor:pointer">
                                @elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv', 'webm']))
                                    {{-- Tampilkan Video --}}
                                    <video controls style="max-width:200px;cursor:pointer">
                                        <source src="{{ $filePath }}" type="video/{{ $extension }}">
                                        Browser tidak mendukung video ini.
                                    </video>
                                @elseif (in_array($extension, ['doc', 'docx']))
                                    {{-- Tampilkan Dokumen Word --}}
                                    <div class="doc-thumbnail-container"
                                        onclick="window.open('{{ asset('storage/' . $filePath) }}', '_blank')">
                                        <i class="far fa-file-word text-primary fa-3x"></i>
                                        <p class="doc-filename">Lihat Dokumen Word</p>
                                    </div>
                                @else
                                    {{-- File tidak dikenal --}}
                                    <p><i class="fas fa-file"></i> File tidak didukung</p>
                                @endif

                                <div class="view-file-overlay"
                                    onclick="openDocumentModal('{{ $filePath }}', {{ $isPdf ? 'true' : 'false' }})">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                @if ($item->foto_bukti && Str::endsWith(strtolower($item->foto_bukti), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <i class="fas fa-image me-1"></i>
                                    Bukti foto
                                @elseif($item->foto_bukti && Str::endsWith(strtolower($item->foto_bukti), ['mp4', 'mov', 'avi', 'mkv', 'webm']))
                                    <i class="fas fa-video me-1"></i>
                                    Bukti video
                                @elseif($item->foto_bukti && Str::endsWith(strtolower($item->foto_bukti), ['doc', 'docx', 'pdf', 'pptx', 'xlsx']))
                                    <i class="fas fa-file-alt me-1"></i>
                                    Bukti dokumen
                                @else
                                    File tidak ada/diketahui
                                @endif
                            </div>
                        </div>
                    @else
                        <form action="{{ route('rw.pengaduan.baca', $item->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="selesai"
                                    id="selesai{{ $item->id }}" value="1">
                                <label class="form-check-label" for="selesai{{ $item->id }}">
                                    Selesai
                                </label>
                            </div>
                            <div id="buktiSelesai{{ $item->id }}" style="display:none;" class="mt-3">
                                <input type="file" name="file" id="file{{ $item->id }}"
                                    class="form-control @error('file') is-invalid @enderror">
                                <small class="form-text text-muted">Unggah Foto atau Video bukti selesai, atau
                                    biarkan
                                    kosong jika tidak ingin mengubah.</small>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="modal-footer sticky-footer mt-3">
                                <button type="submit" class="btn btn-{{$color}}">Simpan
                                    Perubahan</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleBukti(id) {
        const selesaiCheckbox = document.getElementById('selesai' + id);
        const buktiSelesai = document.getElementById('buktiSelesai' + id);

        if (!selesaiCheckbox || !buktiSelesai) return;

        buktiSelesai.style.display = selesaiCheckbox.checked ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const checkbox = document.getElementById('selesai{{ $item->id }}');
        if (checkbox) {
            checkbox.addEventListener('change', () => toggleBukti({{ $item->id }}));
        }
    });
</script>
