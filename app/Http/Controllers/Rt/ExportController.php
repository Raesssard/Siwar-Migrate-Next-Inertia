<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\IuranGolongan;
use App\Models\Kategori_golongan;
use App\Models\Tagihan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportController extends Controller
{
    // Export Iuran
    public function exportIuran()
    {
        $id_rt = Auth::user()->id_rt;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Iuran');
        $sheet->getStyle("A1:N2")->getFont()->setBold(true);
        foreach (range('A', 'R') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->setCellValue('B1', 'Iuran Manual');
        $sheet->mergeCells('B1:F1');
        $sheet->setCellValue('B2', 'No.');
        $sheet->setCellValue('C2', 'Nama');
        $sheet->setCellValue('D2', 'Nominal');
        $sheet->setCellValue('E2', 'Tanggal Tagih');
        $sheet->setCellValue('F2', 'Tanggal Tempo');

        $row = 3;
        $iurans = Iuran::where('id_rt', $id_rt)->where('jenis', 'manual')->get();
        $no_urut = 1;

        foreach ($iurans as $iuran) {
            $sheet->setCellValue("B{$row}", $no_urut);
            $sheet->setCellValue("C{$row}", $iuran->nama);
            $sheet->setCellValue("D{$row}", $iuran->nominal);
            $sheet->setCellValue("E{$row}", $iuran->tgl_tagih);
            $sheet->setCellValue("F{$row}", $iuran->tgl_tempo);
            $row++;
            $no_urut++;
        }

        $sheet->setCellValue('I1', 'Iuran Otomatis');
        $sheet->mergeCells('I1:R1');
        $sheet->setCellValue('I2', 'No.');
        $sheet->setCellValue('J2', 'Nama');
        $sheet->setCellValue('K2', 'Kampung');
        $sheet->setCellValue('L2', 'Kavling');
        $sheet->setCellValue('M2', 'Kost');
        $sheet->setCellValue('N2', 'Kantor');
        $sheet->setCellValue('O2', 'Kontrakan');
        $sheet->setCellValue('P2', 'UMKM');
        $sheet->setCellValue('Q2', 'Tanggal Tagih');
        $sheet->setCellValue('R2', 'Tanggal Tempo');
        $row_otomatis = 3;
        $iuran_otomatis = Iuran::where('id_rt', $id_rt)->where('jenis', 'otomatis')->get();
        $starCol = Kategori_golongan::all();
        $no = 1;
        foreach ($iuran_otomatis as $iuran) {
            $sheet->setCellValue("I{$row_otomatis}", $no);
            $sheet->setCellValue("J{$row_otomatis}", $iuran->nama);

            $colIndex = 11; // kolom K = 11
            foreach ($starCol as $golongan) {
                $col = Coordinate::stringFromColumnIndex($colIndex);
                $nominal = $iuran->iuran_golongan->firstWhere('id_golongan', $golongan->id)->nominal ?? 0;
                $sheet->setCellValue("{$col}{$row_otomatis}", $nominal);
                $colIndex++;
            }

            $sheet->setCellValue("Q{$row_otomatis}", $iuran->tgl_tagih);
            $sheet->setCellValue("R{$row_otomatis}", $iuran->tgl_tempo);

            $row_otomatis++;
            $no++;
        }

        $rowEnd = $row - 1;
        $rowEnds = $row_otomatis - 1;

        $sheet->getStyle("B2:F{$rowEnd}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN, // bisa THICK, DASHED, dll.
                    'color' => ['argb' => 'FF000000'], // hitam
                ],
            ],
        ]);

        $sheet->getStyle("I2:R{$rowEnds}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN, // bisa THICK, DASHED, dll.
                    'color' => ['argb' => 'FF000000'], // hitam
                ],
            ],
        ]);

        $rowStart = 3;

        $sheet->getStyle("B2:F2")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '40bf40'], // kuning
            ],
        ]);

        for ($r = $rowStart; $r <= $rowEnd; $r++) {
            $isEven = $r % 2 == 0; // baris genap
            $color = $isEven ? '79d279' : 'b3e6b3';

            $sheet->getStyle("B{$r}:F{$r}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $color],
                ],
            ]);
        }

        $sheet->getStyle("I2:R2")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '3366ff'], // kuning
            ],
        ]);

        for ($r = $rowStart; $r <= $rowEnds; $r++) {
            $isEven = $r % 2 == 0; // baris genap
            $color = $isEven ? '809fff' : 'b3c6ff';

            $sheet->getStyle("I{$r}:R{$r}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $color],
                ],
            ]);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "iuran_rt_{$id_rt}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }

    // Export Tagihan
    public function exportTagihan()
    {
        $id_rt = Auth::user()->id_rt;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Tagihan');

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'No KK');
        $sheet->setCellValue('C1', 'Nominal');
        $sheet->setCellValue('D1', 'Status Bayar');
        $sheet->setCellValue('E1', 'Tanggal');

        $row = 2;
        $tagihans = Tagihan::where('id_rt', $id_rt)->get();

        foreach ($tagihans as $tagihan) {
            $sheet->setCellValue("A{$row}", $tagihan->id);
            $sheet->setCellValue("B{$row}", $tagihan->no_kk);
            $sheet->setCellValue("C{$row}", $tagihan->nominal);
            $sheet->setCellValue("D{$row}", $tagihan->status_bayar);
            $sheet->setCellValue("E{$row}", $tagihan->created_at);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "tagihan_rt_{$id_rt}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }

    // Export Transaksi
    public function exportTransaksi()
    {
        $id_rt = Auth::user()->id_rt;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Transaksi');

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Pemasukan');
        $sheet->setCellValue('C1', 'Pengeluaran');
        $sheet->setCellValue('D1', 'Jumlah');
        $sheet->setCellValue('E1', 'Tanggal');

        $row = 2;
        $transaksis = Transaksi::where('id_rt', $id_rt)->get();

        foreach ($transaksis as $trx) {
            $sheet->setCellValue("A{$row}", $trx->id);
            $sheet->setCellValue("B{$row}", $trx->pemasukan);
            $sheet->setCellValue("C{$row}", $trx->pengeluaran);
            $sheet->setCellValue("D{$row}", $trx->jumlah);
            $sheet->setCellValue("E{$row}", $trx->created_at);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "transaksi_rt_{$id_rt}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }
}
