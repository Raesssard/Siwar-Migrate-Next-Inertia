<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\IuranSheetExport;
use App\Exports\IuranGolonganSheetExport;

class IuranExport implements WithMultipleSheets
{
    protected $jenis;

    public function __construct($jenis = 'all')
    {
        $this->jenis = $jenis;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Sheet 1: Data Iuran
        $sheets[] = new IuranSheetExport($this->jenis);

        // Sheet 2: Data Iuran Golongan (jika otomatis atau all)
        if ($this->jenis === 'otomatis' || $this->jenis === 'all') {
            $sheets[] = new IuranGolonganSheetExport();
        }

        return $sheets;
    }
}
