<?php

namespace App\Exports;

use App\Models\IuranGolongan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IuranGolonganSheetExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return IuranGolongan::with('iuran', 'golongan')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Iuran',
            'Golongan',
            'Nominal',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->iuran->nama ?? '-',
            $row->golongan->jenis ?? '-',
            $row->nominal,
        ];
    }
}
