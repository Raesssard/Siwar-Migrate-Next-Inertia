<style>
    .file-section {
        margin-top: 2rem;
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
        margin-top: 20px;
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

<div class="modal fade" id="modalDetailPengaduan{{ $item->id }}" tabindex="-1"
    aria-labelledby="modalDetailPengaduanLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title mb-0" id="modalDetailPengaduanLabel{{ $item->id }}">
                    Detail Pengaduan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body px-4 pt-4 pb-3">
                <h4 class="fw-bold text-success mb-3">
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
                </div>
            </div>
        </div>
    </div>
</div>
