<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi'; // Ubah dari 'pengeluaran'

    protected $fillable = [
        'rt',
        'tanggal',
        'jenis',
        'nominal',
        'nama_transaksi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
        // pemasukan dan pengeluaran tetap sebagai string
    ];
}
