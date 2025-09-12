<div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-labelledby="modalDetailLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel{{ $item->id }}">
                    Detail Pengaduan - {{ $item->judul }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p><strong>Warga:</strong> {{ $item->warga->nama ?? '-' }} (NIK: {{ $item->warga->nik ?? '-' }})</p>
                <p><strong>Status:</strong> 
                    <span class="badge {{ $item->status === 'diproses' ? 'bg-warning' : 'bg-success' }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </p>
                <p><strong>Tanggal:</strong> {{ $item->created_at->format('d-m-Y H:i') }}</p>
                <hr>
                <p>{{ $item->isi }}</p>

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

                @if($item->bukti_selesai)
                    <hr>
                    <p><strong>Bukti Penyelesaian:</strong></p>
                    <img src="{{ asset('storage/'.$item->bukti_selesai) }}" alt="Bukti" class="img-fluid rounded shadow">
                @endif

                <hr>
                {{-- Form update status --}}
                <form action="{{ route('rw.pengaduan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Ubah Status</label>
                        <select name="status" class="form-select">
                            <option value="diproses" {{ $item->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $item->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="bukti_selesai" class="form-label">Upload Bukti Selesai (Opsional)</label>
                        <input type="file" name="bukti_selesai" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
