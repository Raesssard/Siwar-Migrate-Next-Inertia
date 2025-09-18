<?php

return [

    // ==========================
    // Jabatan di level RW
    // ==========================
    'rw' => [
        'ketua' => [
            '*', // full akses RW
        ],
        'sekretaris' => [
            'dashboard.rw',
            'rt.view',
            'rt.manage',
            'warga.view',
            'warga.manage',
            'kk.view',
            'kk.manage',
            'pengumuman.rwrt.view',
            'pengumuman.rw.manage',
            'pengaduan.rwrt.view',
            'pengaduan.rw.manage',
        ],
        'bendahara' => [
            'dashboard.rw',
            'iuran.rwrt.view',
            'iuran.rw.manage',
            'tagihan.rwrt.view',
            'tagihan.rw.manage',
            'transaksi.rwrt.view',
            'transaksi.rw.manage',
        ],
    ],

    // ==========================
    // Jabatan di level RT
    // ==========================
    'rt' => [
        'ketua' => [
            '*', // full akses RT
        ],
        'sekretaris' => [
            'dashboard.rt',
            'warga.view',
            'kk.view',
            'pengumuman.rt.manage',
            'pengaduan.rt.view', // HANYA VIEW
        ],
        'bendahara' => [
            'dashboard.rt',
            'iuran.rt.manage',
            'tagihan.rt.manage',
            'transaksi.rt.manage',
        ],
    ],

];
