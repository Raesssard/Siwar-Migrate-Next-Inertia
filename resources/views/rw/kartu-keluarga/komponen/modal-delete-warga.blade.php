@php
    $modalId = "deleteWargaModal{$warga->nik}";
@endphp

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('rw.warga.destroy', $warga->nik) }}" method="POST" onsubmit="return validateDeleteWarga{{ $warga->nik }}()">
                @csrf
                @method('DELETE')

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="{{ $modalId }}Label">
                        Hapus Warga - {{ strtoupper($warga->nama) }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>
                        Apakah Anda yakin ingin menghapus warga
                        <strong>{{ strtoupper($warga->nama) }}</strong> <br> dengan NIK
                        <strong>{{ $warga->nik }}</strong>?
                    </p>

                    <div class="mb-3">
                        <label for="keterangan_select_{{ $warga->nik }}" class="form-label">Pilih Alasan</label>
                        <select id="keterangan_select_{{ $warga->nik }}" class="form-select" required>
                            <option value="">-- Pilih Alasan --</option>
                            <option value="Keluar dari kampung/desa">Keluar dari kampung/desa</option>
                            <option value="Meninggal dunia">Meninggal dunia</option>
                            <option value="lainnya">Lainnya (tulis manual)</option>
                        </select>
                        <div class="invalid-feedback">Silakan pilih alasan penghapusan.</div>
                    </div>

                    <div class="mb-3 d-none" id="keterangan_manual_wrapper_{{ $warga->nik }}">
                        <label for="keterangan_manual_{{ $warga->nik }}" class="form-label">Keterangan</label>
                        <textarea id="keterangan_manual_{{ $warga->nik }}" class="form-control" rows="3" placeholder="Tuliskan keterangan..."></textarea>
                        <div class="invalid-feedback">Silakan tuliskan keterangan.</div>
                    </div>

                    {{-- Hidden field untuk dikirim --}}
                    <input type="hidden" name="keterangan" id="keterangan_hidden_{{ $warga->nik }}">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus & Simpan History</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sel = document.getElementById('keterangan_select_{{ $warga->nik }}');
    const manualWrap = document.getElementById('keterangan_manual_wrapper_{{ $warga->nik }}');
    const manualInput = document.getElementById('keterangan_manual_{{ $warga->nik }}');
    const hiddenInput = document.getElementById('keterangan_hidden_{{ $warga->nik }}');

    if (!sel) return;

    sel.addEventListener('change', function () {
        if (this.value === 'lainnya') {
            manualWrap.classList.remove('d-none');
            manualInput.focus();
        } else {
            manualWrap.classList.add('d-none');
            manualInput.value = '';
        }
    });

    const modalEl = document.getElementById('{{ $modalId }}');
    modalEl.addEventListener('hidden.bs.modal', function () {
        sel.value = '';
        hiddenInput.value = '';
        manualInput.value = '';
        manualWrap.classList.add('d-none');
        sel.classList.remove('is-invalid');
        manualInput.classList.remove('is-invalid');
    });
});

function validateDeleteWarga{{ $warga->nik }}() {
    const sel = document.getElementById('keterangan_select_{{ $warga->nik }}');
    const manualInput = document.getElementById('keterangan_manual_{{ $warga->nik }}');
    const hiddenInput = document.getElementById('keterangan_hidden_{{ $warga->nik }}');

    sel.classList.remove('is-invalid');
    manualInput.classList.remove('is-invalid');

    if (sel.value === '') {
        sel.classList.add('is-invalid');
        sel.focus();
        return false;
    }

    if (sel.value === 'lainnya') {
        if (manualInput.value.trim() === '') {
            manualInput.classList.add('is-invalid');
            manualInput.focus();
            return false;
        }
        hiddenInput.value = manualInput.value.trim();
    } else {
        hiddenInput.value = sel.value;
    }

    return true;
}
</script>

