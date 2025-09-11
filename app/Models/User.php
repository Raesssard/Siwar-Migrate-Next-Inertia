<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'users';

    protected $fillable = [
        'nik',
        'password',
        'nama',
        'nomor_rw',
        'id_rw',
        'id_rt',
    ];

    // ⛔ Hapus cast JSON roles (sudah pakai Spatie, jadi tidak dipakai)
    // protected $casts = ['roles' => 'array'];

    // Default id_rw (opsional)
    protected $attributes = [
        'id_rw' => 1,
    ];

    // Relasi
    public function warga()
    {
        return $this->hasOne(Warga::class, 'nik', 'nik');
    }

    public function rukunTetangga()
    {
        return $this->belongsTo(Rukun_tetangga::class, 'id_rt', 'id');
    }

    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class, 'id_rw', 'id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
