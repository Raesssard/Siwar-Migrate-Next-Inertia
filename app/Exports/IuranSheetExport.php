<?php

namespace App\Exports;

use App\Models\Iuran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IuranSheetExport implements FromCollection, WithHeadings, WithMapping
{
    protected $jenis;

    public function __construct($jenis = 'all')
    {
        $this->jenis = $jenis;
    }

    public function collection()
    {
        $query = Iuran::query();

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
            'Nama Iuran',
            'Jenis',
            'Nominal',
            'Tanggal Tagih',
            'Tanggal Tempo',
            'Level',
        ];
    }

    public function map($iuran): array
    {
        return [
            $iuran->id,
            $iuran->nama,
            ucfirst($iuran->jenis),
            $iuran->nominal ?? '-',
            $iuran->tgl_tagih,
            $iuran->tgl_tempo,
            strtoupper($iuran->level),
        ];
    }
}
