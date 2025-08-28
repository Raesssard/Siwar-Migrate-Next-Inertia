<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Dihapus karena tidak digunakan
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Pastikan ini ada jika menggunakan type-hinting BelongsTo
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nik',
        'password',
        'nama',
        'nomor_rw',
        'id_rw',
        'roles', // ganti dari role ke roles
        'id_rt',
    ];

    protected $casts = [
        'roles' => 'array',
    ];

    protected $attributes = [
    'id_rw' => 1, // ✅ default kalau tidak diisi
    ];

    public function hasRole(string $role): bool
    {
        // kalau kolom roles disimpan array (JSON)
        if (is_array($this->roles)) {
            return in_array($role, $this->roles);
        }

        // fallback kalau roles cuma string
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        if (is_array($this->roles)) {
            return count(array_intersect($roles, $this->roles)) > 0;
        }

        return in_array($this->role, $roles);
    }


    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'nik', 'nik');
    }

    public function rukunTetangga(): BelongsTo
    {
        // Relasi ini sudah benar, id_rt di users merujuk ke id di rukun_tetangga
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