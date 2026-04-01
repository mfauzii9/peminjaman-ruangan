<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\SuccessController;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\QuickBookingController;
use App\Http\Controllers\Admin\PbmManageController;
use App\Http\Controllers\Admin\PengajuanDetailController;
use App\Http\Controllers\Admin\AdminUserController;

use App\Http\Controllers\Kema\DashboardController as KemaDashboardController;
use App\Http\Controllers\Kema\PengajuanController as KemaPengajuanController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/ruangan', [RoomController::class, 'index'])->name('ruangan.index');

Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
Route::get('/history/{code}', [HistoryController::class, 'show'])->name('history.show');

Route::get('/pinjam/{room}', [BorrowController::class, 'create'])->name('pinjam.create');
Route::post('/pinjam/{room}', [BorrowController::class, 'store'])->name('pinjam.store');

Route::get('/ruangan/{room}/schedule-json', [BorrowController::class, 'scheduleJson'])
    ->name('ruangan.schedule.json');

Route::get('/success', [SuccessController::class, 'show'])->name('success.show');

/*
|--------------------------------------------------------------------------
| ADMIN AUTH (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| KEMA PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('kema')
    ->name('kema.')
    ->middleware(['kema.auth', 'share_pending'])
    ->group(function () {
        Route::get('/dashboard', [KemaDashboardController::class, 'index'])->name('dashboard');

        Route::get('/pengajuan', [KemaPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/list', [KemaPengajuanController::class, 'list'])->name('pengajuan.list');

        Route::get('/pengajuan/{id}', [KemaPengajuanController::class, 'show'])
            ->whereNumber('id')
            ->name('pengajuan.show');

        Route::post('/pengajuan/{id}/approve', [KemaPengajuanController::class, 'approve'])
            ->whereNumber('id')
            ->name('pengajuan.approve');

        Route::post('/pengajuan/{id}/reject', [KemaPengajuanController::class, 'reject'])
            ->whereNumber('id')
            ->name('pengajuan.reject');

        Route::get('/riwayat', [KemaPengajuanController::class, 'riwayat'])->name('riwayat');
        Route::get('/riwayat/list', [KemaPengajuanController::class, 'riwayatList'])->name('riwayat.list');
    });

/*
|--------------------------------------------------------------------------
| ADMIN PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['admin.auth', 'share_pending'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | PENGAJUAN
        |--------------------------------------------------------------------------
        */
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');
        Route::get('/pengajuan/stats', [PengajuanController::class, 'stats'])->name('pengajuan.stats');
        Route::get('/pengajuan/history', [PengajuanController::class, 'history'])->name('pengajuan.history');
        Route::get('/pengajuan/rooms', [PengajuanController::class, 'rooms'])->name('pengajuan.rooms');
        Route::get('/pengajuan/rooms-free', [PengajuanController::class, 'roomsFree'])->name('pengajuan.rooms_free');

        Route::get('/pengajuan/confirm/{id}', [PengajuanDetailController::class, 'confirm'])
            ->whereNumber('id')
            ->name('pengajuan.confirm');

        Route::get('/pengajuan/{id}', [PengajuanDetailController::class, 'show'])
            ->whereNumber('id')
            ->name('pengajuan.show');

        Route::post('/pengajuan/{id}/approve', [PengajuanDetailController::class, 'approve'])
            ->whereNumber('id')
            ->name('pengajuan.approve');

        Route::post('/pengajuan/{id}/reject', [PengajuanDetailController::class, 'reject'])
            ->whereNumber('id')
            ->name('pengajuan.reject');

        /*
        |--------------------------------------------------------------------------
        | ROOMS CRUD
        |--------------------------------------------------------------------------
        */
        Route::get('/rooms', [AdminRoomController::class, 'index'])->name('rooms.index');
        Route::post('/rooms', [AdminRoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/{room}/edit', [AdminRoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/rooms/{room}', [AdminRoomController::class, 'update'])->name('rooms.update');
        Route::delete('/rooms/{room}', [AdminRoomController::class, 'destroy'])->name('rooms.destroy');

        /*
        |--------------------------------------------------------------------------
        | ROOMS CSV
        |--------------------------------------------------------------------------
        */
        Route::post('/rooms/import-csv', [AdminRoomController::class, 'importCsv'])->name('rooms.importCsv');
        Route::get('/rooms/template-csv', [AdminRoomController::class, 'templateCsv'])->name('rooms.templateCsv');

        /*
        |--------------------------------------------------------------------------
        | QUICK BOOKING
        |--------------------------------------------------------------------------
        */
        Route::get('/quick-booking', [QuickBookingController::class, 'index'])->name('quick_booking');

        Route::get('/room-blocks', [QuickBookingController::class, 'list'])->name('room_blocks.list');
        Route::post('/room-blocks', [QuickBookingController::class, 'create'])->name('room_blocks.create');
        Route::put('/room-blocks/{id}', [QuickBookingController::class, 'update'])
            ->whereNumber('id')
            ->name('room_blocks.update');
        Route::delete('/room-blocks/{id}', [QuickBookingController::class, 'delete'])
            ->whereNumber('id')
            ->name('room_blocks.delete');

        /*
        |--------------------------------------------------------------------------
        | AKUN
        |--------------------------------------------------------------------------
        */
        Route::get('/akun', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/akun/profile', [AdminUserController::class, 'updateProfile'])->name('users.profile.update');
        Route::post('/akun/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/akun/users/{id}', [AdminUserController::class, 'show'])
            ->whereNumber('id')
            ->name('users.show');
        Route::put('/akun/users/{id}', [AdminUserController::class, 'update'])
            ->whereNumber('id')
            ->name('users.update');
        Route::delete('/akun/users/{id}', [AdminUserController::class, 'destroy'])
            ->whereNumber('id')
            ->name('users.destroy');

        /*
        |--------------------------------------------------------------------------
        | PBM
        |--------------------------------------------------------------------------
        */
        Route::prefix('pbm')->name('pbm.')->group(function () {
            Route::get('/', [PbmManageController::class, 'index'])->name('index');

            // Rooms
            Route::get('/rooms', [PbmManageController::class, 'rooms'])->name('rooms');

            // Templates
            Route::get('/templates', [PbmManageController::class, 'templates'])->name('templates');
            Route::get('/templates/{id}', [PbmManageController::class, 'templateGet'])
                ->whereNumber('id')
                ->name('template.get');
            Route::post('/templates/save', [PbmManageController::class, 'templateSave'])->name('template.save');
            Route::delete('/templates/{id}', [PbmManageController::class, 'templateDelete'])
                ->whereNumber('id')
                ->name('template.delete');
            Route::post('/templates/delete-all', [PbmManageController::class, 'templateDeleteAll'])
                ->name('template.delete_all');

            // Upload CSV
            Route::post('/templates/upload-csv', [PbmManageController::class, 'templatesImportCsv'])
                ->name('templates.upload_csv');
            Route::get('/templates/sample-csv', [PbmManageController::class, 'templatesSampleCsv'])
                ->name('templates.sample_csv');

            // Events / Occurrences
            Route::get('/events', [PbmManageController::class, 'events'])->name('events');
            Route::post('/events/{id}/reschedule', [PbmManageController::class, 'reschedule'])
                ->whereNumber('id')
                ->name('events.reschedule');
            Route::delete('/events/{id}', [PbmManageController::class, 'eventDelete'])
                ->whereNumber('id')
                ->name('events.delete');
            Route::post('/events/delete-all', [PbmManageController::class, 'deleteAllEvents'])
                ->name('events.delete_all');
        });
    });