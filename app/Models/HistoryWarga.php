<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryWarga extends Model
{
    protected $table = 'history_warga';

    protected $fillable = [
        'warga_nik',
        'jenis',
        'keterangan',
        'tanggal',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }
}
