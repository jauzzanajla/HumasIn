<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EmployeeActivationController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\PeminjamanController;
use App\Models\Peran;
use App\Http\Controllers\DukunganKegiatanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\AdminStatusController;
use App\Http\Controllers\KaryawanDashboardController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\OpiniController;
use App\Http\Controllers\KalenderController;





/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| AKTIVASI AKUN KARYAWAN
|--------------------------------------------------------------------------
*/

Route::get('/aktivasi-akun', [EmployeeActivationController::class, 'show'])
    ->name('activation.show');

Route::post('/aktivasi-akun/verifikasi', [EmployeeActivationController::class, 'verify'])
    ->name('activation.verify');

Route::get('/aktivasi-akun/password', [EmployeeActivationController::class, 'showPassword'])
    ->name('activation.password');

Route::post('/aktivasi-akun/password', [EmployeeActivationController::class, 'setPassword'])
    ->name('activation.password.store');


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profil', [ProfileController::class, 'index'])
        ->name('profile.index');

    Route::get('/profil/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profil', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/profil/foto', [ProfileController::class, 'updatePhoto'])
        ->name('profile.photo.update');

    Route::get('/profil/password', [ProfileController::class, 'editPassword'])
        ->name('profile.password');

    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

        Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');

});


/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');



/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| DASHBOARD SUPER ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/super-admin/dashboard', [SuperAdminDashboardController::class, 'index'])
    ->name('super-admin.dashboard');

/*
|--------------------------------------------------------------------------
| DASHBOARD TU ESELON II
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $user = request()->user()->loadMissing('peran');

  return match ($user->peran?->kode_peran) {
    'KARYAWAN', 'TU_ESELON_II' => redirect()->route('karyawan.dashboard'),
    'ADMIN'                    => redirect()->route('admin.dashboard'),
    'SUPER_ADMIN'              => redirect()->route('super-admin.dashboard'),
    default                    => app(\App\Http\Controllers\HomeController::class)->index(request()),
};
})->middleware('auth')->name('dashboard');
/*
|--------------------------------------------------------------------------
| DASHBOARD KARYAWAN
|--------------------------------------------------------------------------
*/
Route::get('/karyawan/dashboard', [KaryawanDashboardController::class, 'index'])
    ->middleware('auth')
    ->name('karyawan.dashboard');

/*
|--------------------------------------------------------------------------
| STATUS ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN'])->group(function () {
    Route::get('/admin/status-ketersediaan', 'App\\Http\\Controllers\\AdminStatusController@index')->name('admin.status.index');
    Route::patch('/admin/status-ketersediaan/update', 'App\\Http\\Controllers\\AdminStatusController@update')->name('admin.status.update');
});

/*
|--------------------------------------------------------------------------
| PUBLIKASI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/publikasi', [PublikasiController::class, 'index'])->name('publikasi.index');
    Route::get('/publikasi/tambah', [PublikasiController::class, 'create'])->name('publikasi.create');
    Route::post('/publikasi', [PublikasiController::class, 'store'])->name('publikasi.store');
    Route::patch('/publikasi/{id}/status', [PublikasiController::class, 'updateStatus'])->name('publikasi.updateStatus');
});


/*
|--------------------------------------------------------------------------
| OPINI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/opini', [OpiniController::class, 'index'])->name('opini.index');
    Route::get('/opini/tambah', [OpiniController::class, 'create'])->name('opini.create');
    Route::post('/opini', [OpiniController::class, 'store'])->name('opini.store');
    Route::patch('/opini/{id}/status', [OpiniController::class, 'updateStatus'])->name('opini.updateStatus');
});


/*
|--------------------------------------------------------------------------
| DOKUMENTASI KEGIATAN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/dokumentasi-kegiatan', [DokumentasiController::class, 'index'])
        ->name('dokumentasi.index');
 
    Route::middleware('role:SUPER_ADMIN,ADMIN')->group(function () {
        Route::post('/dokumentasi-kegiatan/folder', [DokumentasiController::class, 'storeCategory'])
            ->name('dokumentasi.category.store');
 
        Route::patch('/dokumentasi-kegiatan/folder/{id}', [DokumentasiController::class, 'updateCategory'])
            ->name('dokumentasi.category.update');
 
        Route::delete('/dokumentasi-kegiatan/folder/{id}', [DokumentasiController::class, 'destroyCategory'])
            ->name('dokumentasi.category.destroy');
 
        Route::post('/dokumentasi-kegiatan/item', [DokumentasiController::class, 'storeItem'])
            ->name('dokumentasi.item.store');
 
        Route::delete('/dokumentasi-kegiatan/item/{id}', [DokumentasiController::class, 'destroyItem'])
            ->name('dokumentasi.item.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| PEMINJAMAN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->name('peminjaman.index');

    Route::get('/kelola-pinjaman', [PeminjamanController::class, 'kelola'])
        ->name('peminjaman.kelola');

    Route::get('/peminjaman/aset', [PeminjamanController::class, 'aset'])
        ->name('peminjaman.aset');

    Route::get('/peminjaman/ruangan', [PeminjamanController::class, 'ruangan'])
        ->name('peminjaman.ruangan');

    Route::get('/peminjaman/aset/{id}/ajukan', [PeminjamanController::class, 'createAset'])
        ->name('peminjaman.aset.create');

    Route::post('/peminjaman/aset/{id}/ajukan', [PeminjamanController::class, 'storeAset'])
        ->name('peminjaman.aset.store');

    Route::match(['post', 'patch'], '/peminjaman/{id}/status', [PeminjamanController::class, 'updateStatus'])
        ->name('peminjaman.approve');

    Route::match(['post', 'patch'], '/peminjaman/{id}/reject', [PeminjamanController::class, 'updateStatus'])
        ->name('peminjaman.reject');

    Route::match(['post', 'patch'], '/peminjaman/{id}/return', [PeminjamanController::class, 'markReturned'])
        ->name('peminjaman.return');

    Route::get('/peminjaman/ruangan/{id}/ajukan', [PeminjamanController::class, 'createRuangan'])
        ->name('peminjaman.ruangan.create');

    Route::post('/peminjaman/ruangan/{id}/ajukan', [PeminjamanController::class, 'storeRuangan'])
        ->name('peminjaman.ruangan.store');
    
    Route::get('/peminjaman/jadwal-ruangan', [PeminjamanController::class, 'jadwalRuanganJson'])
        ->name('peminjaman.jadwal');

    Route::get('/peminjaman/jadwal-kalender', [PeminjamanController::class, 'jadwalKalenderJson'])
        ->name('peminjaman.jadwal.kalender');
});


/*
|--------------------------------------------------------------------------
| KELOLA RUANGAN
|--------------------------------------------------------------------------
*/
Route::resource('ruangan', RuanganController::class)
    ->middleware('role:SUPER_ADMIN,ADMIN');


/*
|--------------------------------------------------------------------------
| NOTIFIKASI
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/', [NotifikasiController::class, 'index'])->name('index');
    Route::post('/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('bacaSemua');
    Route::delete('/hapus-dibaca', [NotifikasiController::class, 'hapusDibaca'])->name('hapusDibaca');
    // Ubah ke match ['delete', 'patch', 'post'] agar aman dari error method not allowed
    Route::match(['delete', 'patch', 'post'], '/hapus-terpilih', [NotifikasiController::class, 'hapusTerpilih'])->name('hapusTerpilih');
    Route::delete('/hapus-semua', [NotifikasiController::class, 'hapusSemua'])->name('hapusSemua');
    Route::get('/{id}/baca', [NotifikasiController::class, 'baca'])->name('baca');
    Route::patch('/{id}/tandai', [NotifikasiController::class, 'tandai'])->name('tandai');
    Route::delete('/{id}', [NotifikasiController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| DUKUNGAN KEGIATAN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dukungan-kegiatan', [DukunganKegiatanController::class, 'index'])->name('dukungan.index');
    Route::get('/dukungan-kegiatan/ajukan', [DukunganKegiatanController::class, 'create'])->name('dukungan.create');
    Route::post('/dukungan-kegiatan/ajukan', [DukunganKegiatanController::class, 'store'])->name('dukungan.store');
    Route::patch('/dukungan-kegiatan/{id}/status', [DukunganKegiatanController::class, 'updateStatus'])->name('dukungan.updateStatus');
});


/*
|--------------------------------------------------------------------------
| KELOLA ASET
|--------------------------------------------------------------------------
*/
Route::resource('aset', AsetController::class)
    ->middleware('role:SUPER_ADMIN,ADMIN');


/*
|--------------------------------------------------------------------------
| KARYAWAN
|--------------------------------------------------------------------------
*/
Route::get('/karyawan/unit/{unit}', [KaryawanController::class, 'unitDetail'])
    ->name('karyawan.unit.detail')
    ->middleware('role:SUPER_ADMIN');

Route::resource('karyawan', KaryawanController::class)
    ->middleware('role:SUPER_ADMIN');


/*
|--------------------------------------------------------------------------
| UNIT
|--------------------------------------------------------------------------
*/
Route::get('/unit', function () {
    return redirect()->route('karyawan.index', ['tab' => 'unit']);
})
    ->name('unit.index')
    ->middleware('role:SUPER_ADMIN');


/*
|--------------------------------------------------------------------------
| AKUN USER
|--------------------------------------------------------------------------
*/
Route::get('/users/search-karyawan', [UserController::class, 'searchKaryawan'])
    ->name('users.search.karyawan')
    ->middleware('role:SUPER_ADMIN');

Route::post('/users/update-role', [UserController::class, 'updateRole'])
    ->name('users.update-role')
    ->middleware('role:SUPER_ADMIN');

Route::resource('users', UserController::class)
    ->middleware('role:SUPER_ADMIN');


/*
|--------------------------------------------------------------------------
| ROLE
|--------------------------------------------------------------------------
*/
Route::get('/peran', function () {
    $peran = Peran::orderBy('id')->get();
    return view('peran.index', compact('peran'));
})
    ->name('peran.index')
    ->middleware('role:SUPER_ADMIN');


/*
|--------------------------------------------------------------------------
| KALENDER - LIHAT (Semua user login: Admin, Eselon II, Karyawan)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/event', [DukunganKegiatanController::class, 'index'])->name('event.index');
    Route::get('/kalender/events-json', [KalenderController::class, 'getEvents'])->name('kalender.events.json');
});

/*
|--------------------------------------------------------------------------
| KALENDER - KELOLA (Hanya Super Admin & Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN'])
    ->prefix('kalender')->name('kalender.')->group(function () {
        Route::post('/pengingat', [KalenderController::class, 'storePengingat'])->name('pengingat.store');
        Route::patch('/pengingat/{pengingat}', [KalenderController::class, 'updatePengingat'])->name('pengingat.update');
        Route::delete('/pengingat/{pengingat}', [KalenderController::class, 'destroyPengingat'])->name('pengingat.destroy');
        Route::patch('/event/{id}', [KalenderController::class, 'updateEvent'])->name('event.update');
    });
/*
|--------------------------------------------------------------------------
| TEMPLATE DESAIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/template', [TemplateController::class, 'index'])
        ->name('template.index');

    Route::middleware('role:SUPER_ADMIN,ADMIN')->group(function () {
        Route::post('/template/folder', [TemplateController::class, 'storeCategory'])
            ->name('template.category.store');

        Route::post('/template/item', [TemplateController::class, 'storeItem'])
            ->name('template.item.store');

        Route::delete('/template/folder/{id}', [TemplateController::class, 'destroyCategory'])
            ->name('template.category.destroy');

        Route::delete('/template/item/{id}', [TemplateController::class, 'destroyItem'])
            ->name('template.item.destroy');
    });
    
    Route::patch('/template/folder/{id}', [TemplateController::class, 'updateCategory'])
        ->name('template.category.update');
});


/*
|--------------------------------------------------------------------------
| MEDIA DJPK
|--------------------------------------------------------------------------
*/
Route::get('/media-djpk', function () {
    return view('media.index');
})->name('media.index');


/*
|--------------------------------------------------------------------------
| MONITORING
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN'])->group(function () {
    Route::get('/monitoring/riwayat', fn () => redirect()->route('monitoring.rekap', request()->query()))
        ->name('monitoring.riwayat');
    Route::get('/monitoring/riwayat/export', [MonitoringController::class, 'exportRiwayat'])->name('monitoring.riwayat.export');

    Route::get('/monitoring/rekap', [MonitoringController::class, 'rekap'])->name('monitoring.rekap');
    Route::get('/monitoring/rekap/export', [MonitoringController::class, 'exportRekap'])->name('monitoring.rekap.export');
});