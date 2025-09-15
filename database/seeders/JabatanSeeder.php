<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatan = [
            // Jabatan RW
            ['level' => 'rw', 'nama_jabatan' => 'ketua'],
            ['level' => 'rw', 'nama_jabatan' => 'sekretaris'],
            ['level' => 'rw', 'nama_jabatan' => 'bendahara'],

            // Jabatan RT
            ['level' => 'rt', 'nama_jabatan' => 'ketua'],
            ['level' => 'rt', 'nama_jabatan' => 'sekretaris'],
            ['level' => 'rt', 'nama_jabatan' => 'bendahara'],
        ];

        foreach ($jabatan as $item) {
            Jabatan::firstOrCreate($item);
        }
    }
}
