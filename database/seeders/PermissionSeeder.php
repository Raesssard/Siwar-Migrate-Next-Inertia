<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ======================
        // List Permissions
        // ======================
        $permissions = [
            // Admin
            'dashboard.admin',
            'rw.view', 'rw.manage',
            'rt.view', 'rt.manage',

            // RW
            'dashboard.rw',
            'warga.view', 'warga.manage',
            'kk.view', 'kk.manage',
            'pengumuman.rw.view', 'pengumuman.rw.manage',
            'pengumuman.rt.view',
            'iuran.rwrt.view', 'iuran.rw.manage',
            'tagihan.rwrt.view', 'tagihan.rw.manage',
            'transaksi.rwrt.view', 'transaksi.rw.manage',

            // RT
            'dashboard.rt',
            'warga.view',
            'kk.view',
            'pengumuman.rwrt.view',
            'pengumuman.rt.manage',
            'iuran.rt.manage',
            'tagihan.rt.manage',
            'transaksi.rt.manage',

            // Warga
            'dashboard.warga',
            'kk.view',
            'pengumuman.rwrt.view',
            'tagihan.rwrt.view',
            'transaksi.rwrt.view',
        ];

        // ======================
        // Buat Permission
        // ======================
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ======================
        // Buat Roles
        // ======================
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $rw    = Role::firstOrCreate(['name' => 'rw']);
        $rt    = Role::firstOrCreate(['name' => 'rt']);
        $warga = Role::firstOrCreate(['name' => 'warga']);

        // ======================
        // Assign Permissions
        // ======================
        $admin->givePermissionTo([
            'dashboard.admin',
            'rw.view', 'rw.manage',
            'rt.view', 'rt.manage',
        ]);

        $rw->givePermissionTo([
            'dashboard.rw',
            'warga.view', 'warga.manage',
            'kk.view', 'kk.manage',
            'rt.view', 'rt.manage',
            'pengumuman.rw.view', 'pengumuman.rw.manage',
            'pengumuman.rt.view',
            'iuran.rwrt.view', 'iuran.rw.manage',
            'tagihan.rwrt.view', 'tagihan.rw.manage',
            'transaksi.rwrt.view', 'transaksi.rw.manage',
        ]);

        $rt->givePermissionTo([
            'dashboard.rt',
            'warga.view',
            'kk.view',
            'pengumuman.rwrt.view',
            'pengumuman.rt.manage',
            'iuran.rwrt.view', 'iuran.rt.manage',
            'tagihan.rwrt.view', 'tagihan.rt.manage',
            'transaksi.rwrt.view', 'transaksi.rt.manage',
        ]);

        $warga->givePermissionTo([
            'dashboard.warga',
            'kk.view',
            'pengumuman.rwrt.view',
            'tagihan.rwrt.view',
            'transaksi.rwrt.view',
        ]);
    }
}
