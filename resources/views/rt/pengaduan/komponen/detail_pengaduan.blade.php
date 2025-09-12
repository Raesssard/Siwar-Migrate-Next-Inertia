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
                        <span class="ms-1">
                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('dddd, D MMMM Y H:i') }}
                        </span>
                    </li>
                    <li>
                        <strong>Warga:</strong> {{ $item->warga->nama ?? '-' }}
                    </li>
                    <li>
                        <strong>NIK:</strong> {{ $item->warga->nik ?? '-' }}
                    </li>
                    <li>
                        <strong>Status:</strong>
                        <span class="badge {{ $item->status === 'diproses' ? 'bg-warning' : 'bg-success' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </li>
                </ul>

                <hr class="my-2">

                <div class="mb-2">
                    <strong class="d-block mb-1">Isi Pengaduan:</strong>
                    <div class="border rounded bg-light p-3" style="line-height: 1.6;">
                        {{ $item->isi }}
                    </div>
                </div>

                {{-- Lampiran --}}
                @if ($item->file_path)
                    <div class="file-section mt-3">
                        <strong class="d-block mb-2">Lampiran:</strong>
                        @php
                            $filePath = asset('storage/'.$item->file_path);
                            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                        @endphp

                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            <img src="{{ $filePath }}" alt="Lampiran" class="img-fluid rounded shadow-sm">
                        @elseif ($extension === 'pdf')
                            <a href="{{ $filePath }}" target="_blank" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf"></i> Lihat PDF
                            </a>
                        @elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv', 'webm']))
                            <video controls class="w-100 rounded shadow-sm">
                                <source src="{{ $filePath }}" type="video/{{ $extension }}">
                                Browser tidak mendukung video ini.
                            </video>
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
