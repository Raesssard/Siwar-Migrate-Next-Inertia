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
    RtiuranController,
    Rt_PengaduanController
};
use App\Http\Controllers\Rw\{
    DashboardController,
    IuranController,
    Kartu_keluargaController,
    Kategori_golonganController,
    LaporanController,
    PengaduanRwController,
    PengeluaranController,
    TransaksiController,
    PengumumanController,
    PengumumanRtController,
    Rukun_tetanggaController,
    TagihanController,
    WargaController,
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
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('rw.can:dashboard.rw');

    // Data Warga
    Route::resource('warga', WargaController::class)
        ->middleware('rw.can:warga.view');

    // Kartu Keluarga
    Route::resource('kartu_keluarga', Kartu_keluargaController::class)
        ->middleware('rw.can:kk.view');
    Route::put('kartu_keluarga/{kartu_keluarga}/upload-foto', [Kartu_keluargaController::class, 'uploadFoto'])
        ->name('kartu_keluarga.upload_foto')
        ->middleware('rw.can:kk.manage');
    Route::delete('kartu_keluarga/{kartu_keluarga}/delete-foto', [Kartu_keluargaController::class, 'deleteFoto'])
        ->name('kartu_keluarga.delete_foto')
        ->middleware('rw.can:kk.manage');
    Route::get('kartu_keluarga/{kartu_keluarga}/upload-form', [Kartu_keluargaController::class, 'uploadForm'])
        ->name('kartu_keluarga.upload_form')
        ->middleware('rw.can:kk.manage');

    // Rukun Tetangga
    Route::resource('rukun_tetangga', Rukun_tetanggaController::class)
        ->middleware('rw.can:rt.view');

    // Pengumuman
    Route::resource('pengumuman', PengumumanController::class)
        ->middleware('rw.can:pengumuman.rwrt.view');
    Route::resource('pengumuman-rt', PengumumanRtController::class)
        ->middleware('rw.can:pengumuman.rwrt.view');
    Route::get('pengumuman/{id}/export', [PengumumanController::class, 'export'])
        ->name('pengumuman.export')
        ->middleware('rw.can:pengumuman.rwrt.view');
    Route::get('pengumuman-rt/{id}/export', [PengumumanRtController::class, 'export'])
        ->name('pengumuman_rt.export')
        ->middleware('rw.can:pengumuman.rwrt.view');

    // Tagihan
    Route::resource('tagihan', TagihanController::class)
        ->middleware('rw.can:tagihan.rwrt.view');
    Route::get('tagihan/export/manual', [TagihanController::class, 'exportManual'])
        ->name('tagihan.export.manual')
        ->middleware('rw.can:tagihan.rwrt.view');
    Route::get('tagihan/export/otomatis', [TagihanController::class, 'exportOtomatis'])
        ->name('tagihan.export.otomatis')
        ->middleware('rw.can:tagihan.rwrt.view');
    Route::get('tagihan/export/semua', [TagihanController::class, 'exportSemua'])
        ->name('tagihan.export.semua')
        ->middleware('rw.can:tagihan.rwrt.view');

    // Iuran
    Route::resource('iuran', IuranController::class)
        ->middleware('rw.can:iuran.rwrt.view');
    Route::get('iuran/export/{jenis?}', [IuranController::class, 'export'])
        ->name('iuran.export')
        ->middleware('rw.can:iuran.rwrt.view');
    Route::put('iuran/otomatis/{id}', [IuranController::class, 'updateOtomatis'])
        ->name('iuran.updateOtomatis')
        ->middleware('rw.can:iuran.rwrt.manage');

    // Transaksi
    Route::resource('transaksi', TransaksiController::class)
        ->middleware('rw.can:transaksi.rwrt.view');
    Route::get('transaksi/export/{jenis}', [TransaksiController::class, 'export'])
        ->name('transaksi.export')
        ->middleware('rw.can:transaksi.rwrt.view');

    // Pengaduan
    Route::resource('pengaduan', PengaduanRwController::class)
        ->middleware('rw.can:pengaduan.rwrt.view');
    Route::patch('pengaduan/{id}/confirm', [PengaduanRwController::class, 'confirm'])
        ->name('pengaduan.confirm')
        ->middleware('rw.can:pengaduan.rwrt.view');

    // Kategori golongan (tanpa middleware, sesuai permintaan)
    Route::resource('kategori_golongan', Kategori_golonganController::class);

    // Laporan (sementara dibiarkan tanpa middleware)
    Route::get('laporan_pengeluaran_bulanan/{bulan}/{tahun}', [LaporanController::class, 'pengeluaran_bulanan'])
        ->name('laporan.pengeluaran_bulanan');
});


/*
|--------------------------------------------------------------------------
| RT Routes
|--------------------------------------------------------------------------
*/
Route::prefix('rt')->as('rt.')->middleware(['auth', 'role:rt'])->group(function () {
    // Dashboard
    Route::get('/', [Rt_dashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('rt.can:dashboard.rt');

    // Warga
    Route::resource('warga', Rt_wargaController::class)
        ->middleware('rt.can:warga.view');

    // Kartu Keluarga
    Route::resource('kartu_keluarga', Rt_kartu_keluargaController::class)
        ->middleware('rt.can:kk.view');

    // Pengumuman
    Route::resource('pengumuman', Rt_pengumumanController::class)
        ->middleware('rt.can:pengumuman.rt.manage');
    Route::get('/pengumuman/{id}/export-pdf', [Rt_pengumumanController::class, 'exportPDF'])
        ->name('pengumuman.export.pdf')
        ->middleware('rt.can:pengumuman.rt.manage');

    // Iuran
    Route::resource('iuran', RtiuranController::class)
        ->middleware('rt.can:iuran.rt.manage');
    Route::get('/export/iuran', [ExportController::class, 'exportIuran'])
        ->name('iuran.export')
        ->middleware('rt.can:iuran.rt.manage');

    // Tagihan
    Route::resource('tagihan', Rt_tagihanController::class)
        ->middleware('rt.can:tagihan.rt.manage');
    Route::get('/export/tagihan', [ExportController::class, 'exportTagihan'])
        ->name('tagihan.export')
        ->middleware('rt.can:tagihan.rt.manage');

    // Transaksi
    Route::resource('transaksi', Rt_transaksiController::class)
        ->middleware('rt.can:transaksi.rt.manage');
    Route::get('/export/transaksi', [ExportController::class, 'exportTransaksi'])
        ->name('transaksi.export')
        ->middleware('rt.can:transaksi.rt.manage');

    // Pengaduan (hanya view)
    Route::resource('pengaduan', Rt_PengaduanController::class)
        ->only('index')
        ->middleware('rt.can:pengaduan.rt.view');
    Route::patch('pengaduan/{id}/baca', [Rt_PengaduanController::class, 'show'])
        ->name('pengaduan.baca')
        ->middleware('rt.can:pengaduan.rt.view');

    // Upload / delete foto KK RT
    Route::put('kartu_keluarga/{rt_kartu_keluarga}/upload-foto', [Rt_kartu_keluargaController::class, 'uploadFoto'])
        ->name('kartu_keluarga.upload_foto')
        ->middleware('rt.can:kk.view');
    Route::delete('kartu_keluarga/{rt_kartu_keluarga}/delete-foto', [Rt_kartu_keluargaController::class, 'deleteFoto'])
        ->name('kartu_keluarga.delete_foto')
        ->middleware('rt.can:kk.view');
    Route::get('kartu_keluarga/{rt_kartu_keluarga}/upload-form', [Rt_kartu_keluargaController::class, 'uploadForm'])
        ->name('kartu_keluarga.upload_form')
        ->middleware('rt.can:kk.view');
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
    Route::resource('pengaduan', PengaduanController::class);
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