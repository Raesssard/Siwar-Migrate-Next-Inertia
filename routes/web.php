<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Admin_dashboardController;
use App\Http\Controllers\Admin\Admin_rtController;
use App\Http\Controllers\Admin\Admin_rwController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Rt\{
    Rt_kartu_keluargaController,
    Rt_wargaController,
    Rt_dashboardController,
    Rt_pengumumanController,
    Rt_tagihanController,
    Rt_transaksiController,
    RtiuranController
};
use App\Http\Controllers\Rw\{
    DashboardController,
    IuranController,
    Kartu_keluargaController,
    Kategori_golonganController,
    LaporanController,
    PengeluaranController,
    TransaksiController,
    PengumumanController,
    PengumumanRtController,
    Rukun_tetanggaController,
    TagihanController,
    WargaController
};
use App\Http\Controllers\Warga\{
    DashboardWargaController,
    LihatKKController,
    PengumumanWargaController,
    WargatagihanController,
    WargatransaksiController
};
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [Admin_dashboardController::class, 'index'])->name('dashboard-admin');
    Route::resource('admin/data_rt', Admin_rtController::class);
    Route::resource('admin/data_rw', Admin_rwController::class);
});

/*
|--------------------------------------------------------------------------
| RW Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:rw'])->group(function () {
    Route::get('/rw', [DashboardController::class, 'index'])->name('dashboard-rw');
    Route::resource('rw/warga', WargaController::class);
    Route::resource('rw/kartu_keluarga', Kartu_keluargaController::class);
    Route::resource('rw/rukun_tetangga', Rukun_tetanggaController::class);
    Route::resource('rw/pengumuman', PengumumanController::class);
    Route::resource('rw/pengumuman-rt', PengumumanRtController::class);
    Route::resource('rw/tagihan', TagihanController::class);
    Route::resource('rw/iuran', IuranController::class);
    Route::resource('rw/kategori_golongan', Kategori_golonganController::class);
    Route::resource('rw/pengeluaran', PengeluaranController::class);
    Route::resource('rw/transaksi', TransaksiController::class);

    Route::get('rw/laporan_pengeluaran_bulanan/{bulan}/{tahun}', [LaporanController::class, 'pengeluaran_bulanan'])
        ->name('pengeluaran_bulanan');
    Route::get('rw/pengumuman/{id}/export', [PengumumanController::class, 'export'])
    ->name('rw.pengumuman.export');
    Route::get('rw/pengumuman-rt/{id}/export', [PengumumanRtController::class, 'export'])
    ->name('rw.pengumuman-rt.export');

    Route::get('/iuran/export/{jenis?}', [IuranController::class, 'export'])->name('iuran.export');



    // Upload / delete foto KK
    Route::put('rw/kartu_keluarga/{kartu_keluarga}/upload-foto', [Kartu_keluargaController::class, 'uploadFoto'])
        ->name('kartu_keluarga.upload_foto');
    Route::delete('rw/kartu_keluarga/{kartu_keluarga}/delete-foto', [Kartu_keluargaController::class, 'deleteFoto'])
        ->name('kartu_keluarga.delete_foto');
    Route::get('rw/kartu_keluarga/{kartu_keluarga}/upload-form', [Kartu_keluargaController::class, 'uploadForm'])
        ->name('kartu_keluarga.upload_form');
});

/*
|--------------------------------------------------------------------------
| RT Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:rt'])->group(function () {
    Route::get('/rt', [Rt_dashboardController::class, 'index'])->name('dashboard-rt');
    Route::resource('rt/rt_kartu_keluarga', Rt_kartu_keluargaController::class);
    Route::resource('rt/rt_warga', Rt_wargaController::class);
    Route::resource('rt/rt_pengumuman', Rt_pengumumanController::class);
    Route::resource('rt/rt_iuran', RtiuranController::class);
    Route::resource('rt/rt_tagihan', Rt_tagihanController::class);
    Route::resource('rt/rt_transaksi', Rt_transaksiController::class);
    Route::get('rt/pengumuman/{id}/export', [PengumumanRtController::class, 'export'])
    ->name('rt.pengumuman.export');
    // Upload / delete foto KK RT
    Route::put('rt/rt_kartu_keluarga/{rt_kartu_keluarga}/upload-foto', [Rt_kartu_keluargaController::class, 'uploadFoto'])
        ->name('rt_kartu_keluarga.upload_foto');
    Route::delete('rt/rt_kartu_keluarga/{rt_kartu_keluarga}/delete-foto', [Rt_kartu_keluargaController::class, 'deleteFoto'])
        ->name('rt_kartu_keluarga.delete_foto');
    Route::get('rt/rt_kartu_keluarga/{rt_kartu_keluarga}/upload-form', [Rt_kartu_keluargaController::class, 'uploadForm'])
        ->name('rt_kartu_keluarga.upload_form');
});

/*
|--------------------------------------------------------------------------
| Warga Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:warga'])->group(function () {
    Route::get('/', [DashboardWargaController::class, 'index'])->name('dashboard-main');
    Route::get('/warga/warga_pengumuman', [PengumumanWargaController::class, 'index'])->name('pengumuman-main');
    Route::get('/warga/lihat_kk', [LihatKKController::class, 'index'])->name('lihat_kk');
    Route::get('/warga/tagihan', [WargatagihanController::class, 'index'])->name('tagihan');
    Route::get('/warga/transaksi', [WargatransaksiController::class, 'index'])->name('transaksi');
});

/*
|--------------------------------------------------------------------------
| Shared Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:rw,rt,warga,admin'])->group(function () {
    Route::post('/update-password', [UserController::class, 'updatePassword'])->name('update.password');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/api/warga/{nik}', [Admin_rtController::class, 'getWargaByNik'])->name('warga.by_nik');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/choose-role', [LoginController::class, 'chooseRole'])->name('choose-role'); // tampilan pilih role
    Route::post('/choose-role', [LoginController::class, 'setRole'])->name('choose.role');   // submit role terpilih
});

