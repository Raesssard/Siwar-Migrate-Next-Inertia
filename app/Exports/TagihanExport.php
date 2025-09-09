<?php

namespace App\Exports;

use App\Models\Tagihan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TagihanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $jenis; // manual / otomatis / all

    public function __construct($jenis = 'all')
    {
        $this->jenis = $jenis;
    }

    public function collection()
    {
        $query = Tagihan::with(['iuran', 'kartuKeluarga.kepalaKeluarga']);

        if ($this->jenis === 'manual') {
            $query->where('jenis', 'manual');
        } elseif ($this->jenis === 'otomatis') {
            $query->where('jenis', 'otomatis');
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Tagihan',
            'Jenis',
            'Nominal',
            'Tanggal Tagih',
            'Tanggal Tempo',
            'Status Bayar',
            'Tanggal Bayar',
            'Kategori Pembayaran',
            'Nomor KK',
            'Nama Kepala Keluarga',
            'Iuran Terkait',
        ];
    }

    public function map($tagihan): array
    {
        return [
            $tagihan->id,
            $tagihan->nama,
            ucfirst($tagihan->jenis),
            'Rp ' . number_format($tagihan->nominal, 0, ',', '.'),
            $tagihan->tgl_tagih,
            $tagihan->tgl_tempo,
            ucfirst(str_replace('_', ' ', $tagihan->status_bayar)),
            $tagihan->tgl_bayar ?? '-',
            $tagihan->kategori_pembayaran ?? '-',
            $tagihan->no_kk,
            $tagihan->kartuKeluarga->kepalaKeluarga->nama ?? '-',
            $tagihan->iuran->nama ?? '-',
        ];
    }
}
