<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil config jabatan_permission
        $jabatanPermissions = config('jabatan_permission');

        // Kumpulin semua permission unik dari config
        $allPermissions = [];
        foreach ($jabatanPermissions as $level => $jabatans) {
            foreach ($jabatans as $jabatan => $perms) {
                if (in_array('*', $perms)) {
                    // * berarti full akses -> skip (nanti dikasih semua permission)
                    continue;
                }
                $allPermissions = array_merge($allPermissions, $perms);
            }
        }

        // Tambahin permission khusus Admin & Warga
        $allPermissions = array_merge($allPermissions, [
            // Admin
            'dashboard.admin',
            'rw.view', 'rw.manage',
            'rt.view', 'rt.manage',

            // Warga
            'dashboard.warga',
            'kk.view',
            'pengaduan.manage',
            'pengumuman.rwrt.view',
            'tagihan.rwrt.view',
            'transaksi.rwrt.view',
        ]);

        // Buat permission di DB
        $allPermissions = array_unique($allPermissions);
        foreach ($allPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Buat roles
        $roles = ['admin', 'rw', 'rt', 'warga'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Assign permission untuk Admin (full akses ke RW & RT)
        $admin = Role::findByName('admin');
        $admin->syncPermissions([
            'dashboard.admin',
            'rw.view', 'rw.manage',
            'rt.view', 'rt.manage',
        ]);

        // Assign permission untuk RW
        $rw = Role::findByName('rw');
        $rwPermissions = $this->getPermissionsFromConfig($jabatanPermissions['rw'] ?? []);
        $rw->syncPermissions($rwPermissions);

        // Assign permission untuk RT
        $rt = Role::findByName('rt');
        $rtPermissions = $this->getPermissionsFromConfig($jabatanPermissions['rt'] ?? []);
        $rt->syncPermissions($rtPermissions);

        // Assign permission untuk Warga
        $warga = Role::findByName('warga');
        $warga->syncPermissions([
            'dashboard.warga',
            'kk.view',
            'pengaduan.manage',        // warga bisa bikin/manage pengaduan
            'pengumuman.rwrt.view',
            'tagihan.rwrt.view',
            'transaksi.rwrt.view',
        ]);

    }

    private function getPermissionsFromConfig(array $jabatans): array
    {
        $perms = [];
        foreach ($jabatans as $jabatan => $list) {
            if (in_array('*', $list)) {
                // Ambil semua permission yg ada di DB
                $perms = array_merge($perms, Permission::pluck('name')->toArray());
            } else {
                $perms = array_merge($perms, $list);
            }
        }
        return array_unique($perms);
    }
}
