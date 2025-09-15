<div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1"
     aria-labelledby="modalDetailLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title mb-0" id="modalDetailLabel{{ $item->id }}">
                    Detail Pengaduan Warga
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body px-4 pt-4 pb-3">
                <h4 class="fw-bold text-success mb-3">{{ $item->judul }}</h4>

                <ul class="list-unstyled mb-3 small">
                    <li>
                        <strong>Tanggal:</strong>
                        <span
                            class="ms-1">{{ \Carbon\Carbon::parse($item->created_at)->isoFormat('dddd, D MMMM Y') }}</span>
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
                                ({{ $item->file_name ?? 'Dokumen' }})
                            </a>
                        </p>
                        <small class="text-muted">
                            Foto/Video akan dibuka di tab baru.
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
                            <a href="{{ $filePath }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fas fa-file-download"></i> Unduh Lampiran
                            </a>
                        @endif
                    </div>
                @else
                    <p class="text-muted"><em>Tidak ada lampiran</em></p>
                @endif
            </div>
        </div>
    </div>
</div>
