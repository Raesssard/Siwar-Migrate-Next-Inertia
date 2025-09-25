<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rw extends Model
{
    protected $table = 'rw';
    protected $hidden = ['nik'];
    protected $fillable = [
        'nik',
        'nomor_rw',
        'nama_ketua_rw',
        'jabatan_id',
        'mulai_menjabat',
        'akhir_jabatan',
    ];

    public function kartuKeluarga(): HasMany
    {
        return $this->hasMany(Kartu_keluarga::class, 'id_rw');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_rw', 'id');
    }

    public function pengumuman(): HasMany
    {
        return $this->hasMany(Pengumuman::class, 'id_rw', 'id');
    }

    public function rukunTetangga(): HasMany
    {
        return $this->hasMany(Rukun_tetangga::class, 'id_rw', 'id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
}
