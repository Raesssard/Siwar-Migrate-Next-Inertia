<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    protected $table = 'jabatan';

    protected $fillable = [
        'level',
        'nama_jabatan',
    ];

    public function rw(): HasMany
    {
        return $this->hasMany(Rw::class, 'jabatan_id', 'id');
    }

    public function rukunTetangga(): HasMany
    {
        return $this->hasMany(Rukun_tetangga::class, 'jabatan_id', 'id');
    }
}
