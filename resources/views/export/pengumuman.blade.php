<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pengumuman {{ $jenis }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; margin-bottom: 10px; }
        .content { margin-top: 20px; }
        .footer { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Pengumuman {{ $jenis }}</div>
        <div>Tanggal: {{ \Carbon\Carbon::parse($pengumuman->tanggal)->translatedFormat('d F Y') }}</div>
    </div>

    <div class="content">
        <h3>{{ $pengumuman->judul }}</h3>
        <p>{!! nl2br(e($pengumuman->isi)) !!}</p>
    </div>

    @if($pengumuman->dokumen_name)
        <div class="content">
            <strong>Lampiran:</strong> {{ $pengumuman->dokumen_name }}
        </div>
    @endif

    <div class="footer">
        <em>Diterbitkan oleh {{ $jenis }}</em>
    </div>
</body>
</html>
