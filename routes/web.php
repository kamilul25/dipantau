<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerumahanController;
use App\Http\Controllers\PjuController;
use App\Http\Controllers\AduanController;
use App\Http\Controllers\PublikController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROUTES UNTUK SUPERADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:superadmin'])->prefix('management')->group(function () {

    Route::get('/users', [UserManagementController::class, 'index'])
        ->name('management.users');

    Route::post('/users/store', [UserManagementController::class, 'store'])
        ->name('management.users.store');

    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])
        ->name('management.users.destroy');
});

/*
|--------------------------------------------------------------------------
| ROUTES UNTUK SUPERADMIN & ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:superadmin,admin'])->group(function () {

    /*
    | PERUMAHAN
    |----------------------------------------------------------------------
    */
    Route::prefix('perumahan')->group(function () {
        Route::get('/', [PerumahanController::class, 'index'])->name('perumahan.index');
        Route::post('/store', [PerumahanController::class, 'store'])->name('perumahan.store');
        Route::get('/edit/{id}', [PerumahanController::class, 'edit'])->name('perumahan.edit');
        Route::put('/update/{id}', [PerumahanController::class, 'update'])->name('perumahan.update');
        Route::delete('/destroy/{id}', [PerumahanController::class, 'destroy'])->name('perumahan.destroy');
        Route::get('/cetak', [PerumahanController::class, 'cetak'])->name('perumahan.cetak');
        Route::get('/pdf', [PerumahanController::class, 'pdf'])->name('perumahan.pdf');
        Route::post('/getDesa', [PerumahanController::class, 'getDesa'])->name('get.desa');
    });

    /*
    | PJU
    |----------------------------------------------------------------------
    */
    Route::prefix('pju')->group(function () {
        Route::get('/', [PjuController::class, 'index'])->name('pju.index');
        Route::post('/store', [PjuController::class, 'store'])->name('pju.store');
        Route::get('/edit/{id}', [PjuController::class, 'edit'])->name('pju.edit');
        Route::put('/update/{id}', [PjuController::class, 'update'])->name('pju.update');
        Route::delete('/destroy/{id}', [PjuController::class, 'destroy'])->name('pju.destroy');
        Route::post('/getDesa', [PjuController::class, 'getDesa'])->name('get.desa');
        Route::get('/viewGpx/{id}', [PjuController::class, 'viewGpx'])->name('pju.viewGpx');
        Route::get('/cetak', [PjuController::class, 'cetak'])->name('pju.cetak');
        Route::get('/pdf', [PjuController::class, 'pdf'])->name('pju.pdf');
    });

    /*
    | ADUAN
    |----------------------------------------------------------------------
    */
    Route::prefix('aduan')->group(function () {
        Route::get('/', [AduanController::class, 'index'])->name('aduan.index');
        Route::post('/store', [AduanController::class, 'store'])->name('aduan.store');
        Route::get('/edit/{id}', [AduanController::class, 'edit'])->name('aduan.edit');
        Route::put('/update/{id}', [AduanController::class, 'update'])->name('aduan.update');
        Route::delete('/destroy/{id}', [AduanController::class, 'destroy'])->name('aduan.destroy');
        Route::get('/cetak', [AduanController::class, 'cetak'])->name('aduan.cetak');
        Route::post('/getdesa', [AduanController::class, 'getDesa'])->name('getdesa');
        Route::post('/update-status/{id}', [AduanController::class, 'updateStatus']);
    });

    /*
    | SLIDES
    |----------------------------------------------------------------------
    */
    Route::resource('slides', SlideController::class);
    Route::post('/slides/update-order', [SlideController::class, 'updateOrderAjax'])->name('slides.updateOrderAjax');

    /*
    | WA
    |----------------------------------------------------------------------
    */    
        Route::get('/management/settings', [SettingController::class, 'index']
        )->name('management.settings');

        Route::post('/management/settings/update', [SettingController::class, 'update']
        )->name('management.settings.update');

});

/*
|--------------------------------------------------------------------------
| ROUTES UNTUK PUBLIK (TANPA LOGIN)
|--------------------------------------------------------------------------
*/

// Beranda utama
Route::get('/', [PublikController::class, 'beranda'])->name('publik.beranda');

// Data Perumahan (Pasum)
Route::get('/pasum', [PublikController::class, 'index'])->name('publik.pasum');
Route::post('/pasum/getDesa', [PublikController::class, 'getDesas'])->name('pasum.getDesa');

// Data PJU Publik
Route::get('/pju-publik', [PublikController::class, 'pju'])->name('publik.pju');
Route::post('/pju-publik/getDesa', [PublikController::class, 'getDesas'])->name('pju.getDesa');
Route::get('/pju-publik/viewGpx/{id}', [PublikController::class, 'viewGpx'])->name('pjuviewGpx');

Route::get('/map-all', [PublikController::class, 'mapAll'])->name('publik.mapAll');
Route::get('/gpx-all', [PublikController::class, 'getAllGpx'])->name('publik.getAllGpx');

// Data Aduan Publik
Route::get('/aduan-publik', [PublikController::class, 'aduan'])->name('publik.aduan');
Route::post('/aduan-publik/getdesa', [PublikController::class, 'getDesas'])->name('aduan.getDesa');

// Data RTH Publik
Route::view('/rth', 'publik.rth')->name('publik.rth');

// Auto Logout
Route::post('/auto-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return response()->json(['status' => 'logged_out']);
})->name('auto.logout');