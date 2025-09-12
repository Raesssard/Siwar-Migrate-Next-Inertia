<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{Admin_dashboardController, Admin_rtController, Admin_rwController};
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Rt\{
    Rt_kartu_keluargaController,
    Rt_wargaController,
    Rt_dashboardController,
    Rt_pengumumanController,
    Rt_tagihanController,
    Rt_transaksiController,
    ExportController,
    Rt_pengaduanController,
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
    PengaduanController,
    PengumumanWargaController,
    WargatagihanController,
    WargatransaksiController
};
use App\Http\Controllers\UserController;
use App\Models\Pengaduan;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->as('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin_dashboardController::class, 'index'])->name('dashboard');
    Route::resource('rt', Admin_rtController::class);
    Route::resource('rw', Admin_rwController::class);
});

/*
|--------------------------------------------------------------------------
| RW Routes
|--------------------------------------------------------------------------
*/
Route::prefix('rw')->as('rw.')->middleware(['auth', 'role:rw'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('warga', WargaController::class);
    Route::resource('kartu_keluarga', Kartu_keluargaController::class);
    Route::resource('rukun_tetangga', Rukun_tetanggaController::class);
    Route::resource('pengumuman', PengumumanController::class);
    Route::resource('pengumuman-rt', PengumumanRtController::class);
    Route::resource('tagihan', TagihanController::class);
    Route::resource('iuran', IuranController::class);
    Route::resource('kategori_golongan', Kategori_golonganController::class);
    Route::resource('pengeluaran', PengeluaranController::class);
    Route::resource('transaksi', TransaksiController::class);

    // Export & laporan
    Route::get('laporan_pengeluaran_bulanan/{bulan}/{tahun}', [LaporanController::class, 'pengeluaran_bulanan'])->name('laporan.pengeluaran_bulanan');
    Route::get('pengumuman/{id}/export', [PengumumanController::class, 'export'])->name('pengumuman.export');
    Route::get('pengumuman-rt/{id}/export', [PengumumanRtController::class, 'export'])->name('pengumuman_rt.export');

    Route::get('iuran/export/{jenis?}', [IuranController::class, 'export'])->name('iuran.export');
    Route::get('tagihan/export/manual', [TagihanController::class, 'exportManual'])->name('tagihan.export.manual');
    Route::get('tagihan/export/otomatis', [TagihanController::class, 'exportOtomatis'])->name('tagihan.export.otomatis');
    Route::get('tagihan/export/semua', [TagihanController::class, 'exportSemua'])->name('tagihan.export.semua');
    Route::get('transaksi/export/{jenis}', [TransaksiController::class, 'export'])->name('transaksi.export');

    // Upload / delete foto KK
    Route::put('kartu_keluarga/{kartu_keluarga}/upload-foto', [Kartu_keluargaController::class, 'uploadFoto'])->name('kartu_keluarga.upload_foto');
    Route::delete('kartu_keluarga/{kartu_keluarga}/delete-foto', [Kartu_keluargaController::class, 'deleteFoto'])->name('kartu_keluarga.delete_foto');
    Route::get('kartu_keluarga/{kartu_keluarga}/upload-form', [Kartu_keluargaController::class, 'uploadForm'])->name('kartu_keluarga.upload_form');
});

/*
|--------------------------------------------------------------------------
| RT Routes
|--------------------------------------------------------------------------
*/
Route::prefix('rt')->as('rt.')->middleware(['auth', 'role:rt'])->group(function () {
    Route::get('/', [Rt_dashboardController::class, 'index'])->name('dashboard');
    Route::resource('kartu_keluarga', Rt_kartu_keluargaController::class);
    Route::resource('warga', Rt_wargaController::class);
    Route::resource('pengumuman', Rt_pengumumanController::class);
    Route::resource('iuran', RtiuranController::class);
    Route::resource('tagihan', Rt_tagihanController::class);
    Route::resource('transaksi', Rt_transaksiController::class);
    Route::resource('pengaduan', Rt_pengaduanController::class);

    Route::patch('pengaduan/{id}/baca', [Rt_pengaduanController::class, 'baca'])
        ->name('pengaduan.baca');

    // Upload / delete foto KK RT
    Route::put('kartu_keluarga/{rt_kartu_keluarga}/upload-foto', [Rt_kartu_keluargaController::class, 'uploadFoto'])->name('kartu_keluarga.upload_foto');
    Route::delete('kartu_keluarga/{rt_kartu_keluarga}/delete-foto', [Rt_kartu_keluargaController::class, 'deleteFoto'])->name('kartu_keluarga.delete_foto');
    Route::get('kartu_keluarga/{rt_kartu_keluarga}/upload-form', [Rt_kartu_keluargaController::class, 'uploadForm'])->name('kartu_keluarga.upload_form');

    // Export khusus RT
    Route::get('/rt/export/iuran', [ExportController::class, 'exportIuran'])->name('rt.iuran.export');
    Route::get('/rt/export/tagihan', [ExportController::class, 'exportTagihan'])->name('rt.tagihan.export');
    Route::get('/rt/export/transaksi', [ExportController::class, 'exportTransaksi'])->name('rt.transaksi.export');
    Route::get('/pengumuman/{id}/export-pdf', [Rt_pengumumanController::class, 'exportPDF'])
        ->name('pengumuman.export.pdf');
});

/*
|--------------------------------------------------------------------------
| Warga Routes
|--------------------------------------------------------------------------
*/
Route::prefix('warga')->as('warga.')->middleware(['auth', 'role:warga'])->group(function () {
    Route::get('/', [DashboardWargaController::class, 'index'])->name('dashboard');
    Route::get('pengumuman', [PengumumanWargaController::class, 'index'])->name('pengumuman');
    Route::get('kk', [LihatKKController::class, 'index'])->name('kk');
    Route::get('tagihan', [WargatagihanController::class, 'index'])->name('tagihan');
    Route::get('transaksi', [WargatransaksiController::class, 'index'])->name('transaksi');
    Route::resource('/warga/pengaduan', PengaduanController::class);
});

/*
|--------------------------------------------------------------------------
| Shared Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:rw|rt|warga|admin'])->group(function () {
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
    Route::get('/choose-role', [LoginController::class, 'chooseRole'])->name('choose-role');
    Route::post('/choose-role', [LoginController::class, 'setRole'])->name('choose.role');
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
    Route::get('/choose-role', [LoginController::class, 'chooseRole'])->name('choose-role');
    Route::post('/choose-role', [LoginController::class, 'setRole'])->name('choose.role');
});
