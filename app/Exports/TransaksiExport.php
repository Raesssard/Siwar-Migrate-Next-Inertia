<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    protected $jenis;

    public function __construct($jenis = 'all')
    {
        $this->jenis = $jenis;
    }

    public function collection()
    {
        $query = Transaksi::query();

        if ($this->jenis !== 'all') {
            $query->where('jenis', $this->jenis);
        }

        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->id,
            $transaksi->rt,
            $transaksi->tanggal->format('Y-m-d'),
            ucfirst($transaksi->jenis),
            (float) $transaksi->nominal, // biar Excel baca sebagai angka
            $transaksi->nama_transaksi,
            $transaksi->keterangan,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'RT',
            'Tanggal',
            'Jenis',
            'Nominal',
            'Nama Transaksi',
            'Keterangan',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // format ribuan otomatis
        ];
    }
}
