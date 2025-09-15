<?php

return [

    // ==========================
    // Jabatan di level RW
    // ==========================
    'rw' => [
        'Ketua RW' => [
            '*', // full akses RW
        ],
        'Sekretaris RW' => [
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
        'Bendahara RW' => [
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
        'Ketua RT' => [
            '*', // full akses RT
        ],
        'Sekretaris RT' => [
            'dashboard.rt',
            'warga.view',
            'kk.view',
            'pengumuman.rt.manage',
            'pengaduan.rt.view', // HANYA VIEW
        ],
        'Bendahara RT' => [
            'dashboard.rt',
            'iuran.rt.manage',
            'tagihan.rt.manage',
            'transaksi.rt.manage',
        ],
    ],

];
