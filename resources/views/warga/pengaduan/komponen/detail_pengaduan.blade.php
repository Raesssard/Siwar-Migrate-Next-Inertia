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

    .video-preview {
        width: 100%;
        height: 100%;
        object-fit: contain;
        /* jangan crop, tetap proporsional */
        border-radius: 5px;
    }
</style>

@php
    if ($item->status === 'belum') {
        $color = 'warning';
    } elseif ($item->status === 'sudah') {
        $color = 'primary';
    } else {
        $color = 'success';
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
                        @if ($item->status === 'sudah' || $item->status === 'selesai')
                            <span
                                class="ms-1">{{ \Carbon\Carbon::parse($item->updated_at)->isoFormat('dddd, D MMMM Y') }}</span>
                        @else
                            <span
                                class="ms-1">{{ \Carbon\Carbon::parse($item->created_at)->isoFormat('dddd, D MMMM Y') }}</span>
                        @endif
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
                    </div>
                @endif
                {{-- Akhir bagian dokumen --}}
                <div class="file-section">
                    <div class="file-display">
                        {{-- File utama --}}
                        @if ($item->file_path)
                            @include('warga.pengaduan.komponen.file_display', [
                                'filePath' => asset('storage/' . $item->file_path),
                                'judul' => $item->judul,
                            ])
                        @else
                            <p class="no-file-text">Tidak ada file utama</p>
                        @endif

                        {{-- File bukti kalau selesai --}}
                    </div>
                    <div class="d-flex gap-3">
                        @if ($item->status === 'selesai' && $item->foto_bukti)
                            <div class="file-display">
                                @include('warga.pengaduan.komponen.file_display', [
                                    'filePath' => asset('storage/' . $item->foto_bukti),
                                    'judul' => 'Bukti Selesai',
                                ])

                            </div>
                            <div class="d-block mb-1">
                                <div class="mt-3">
                                    <strong>Status:</strong>
                                    @if ($item->status === 'belum')
                                        <span class="badge bg-warning">Belum dibaca</span>
                                    @elseif ($item->status === 'sudah')
                                        <span class="badge bg-primary">Sudah dibaca</span>
                                    @else
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </div>
                                <div class="mt-3">
                                    <strong class="d-block mb-1">Foto/Video Bukti:</strong>
                                    <p class="mb-2">
                                        <a href="{{ Storage::url($item->foto_bukti) }}" target="_blank"
                                            class="btn btn-sm btn-info text-white">
                                            <i class="fas fa-file-download me-1"></i> Lihat/Unduh File
                                        </a>
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="mt-3">
                                <strong>Status:</strong>
                                @if ($item->status === 'belum')
                                    <span class="badge bg-warning">Belum dibaca</span>
                                @elseif ($item->status === 'sudah')
                                    <span class="badge bg-primary">Sudah dibaca</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </div>
                        @endif
                    </div>
                    <hr class="mt-3 mb-3">
                    <h6>Komentar:</h6>
                    <div class="mt-2">
                        @forelse($item->komentar as $komen)
                            <div class="border rounded p-2 mb-2 bg-light">
                                <small class="text-muted">
                                    {{ $komen->user->nama }} • {{ $komen->created_at->diffForHumans() }}
                                </small>
                                <p class="mb-0">{{ $komen->isi_komentar }}</p>
                            </div>
                        @empty
                            <p class="text-muted">Belum ada komentar.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
