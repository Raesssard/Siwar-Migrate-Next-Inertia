@php
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
    $isPdf = in_array(strtolower($fileExtension), ['pdf']);
    $extension = strtolower($fileExtension);
@endphp

@if (in_array($extension, ['pdf']))
    <div class="pdf-thumbnail-container" onclick="openDocumentModal('{{ $filePath }}', true)">
        <i class="far fa-file-pdf pdf-icon"></i>
        <p class="pdf-filename">Lihat PDF</p>
    </div>
@elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
    <img src="{{ $filePath }}" alt="File {{ $judul ?? '' }}"
        onclick="openDocumentModal('{{ $filePath }}', false)"
        style="max-width:150px;cursor:pointer">
@elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv', 'webm']))
    <video controls style="max-width:200px;cursor:pointer">
        <source src="{{ $filePath }}" type="video/{{ $extension }}">
        Browser tidak mendukung video ini.
    </video>
@elseif (in_array($extension, ['doc', 'docx']))
    <div class="doc-thumbnail-container" onclick="window.open('{{ $filePath }}', '_blank')">
        <i class="far fa-file-word text-primary fa-3x"></i>
        <p class="doc-filename">Lihat Dokumen Word</p>
    </div>
@else
    <p><i class="fas fa-file"></i> File tidak didukung</p>
@endif
