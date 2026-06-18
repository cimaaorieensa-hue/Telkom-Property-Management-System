<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\DuitkuCallbackController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Publik (tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/properti', [PublicController::class, 'properties'])->name('properties.index');
Route::get('/properti/{property}', [PublicController::class, 'propertyDetail'])->name('properties.show');

Route::post('/payments/duitku/callback', [DuitkuCallbackController::class, 'handle'])
    ->name('payments.duitku.callback');

Route::get('/payments/duitku/return', [DuitkuCallbackController::class, 'redirect'])
    ->name('payments.duitku.return');

Route::post('/payments/midtrans/notification', [\App\Http\Controllers\MidtransNotificationController::class, 'handle'])
    ->name('payments.midtrans.notification');

/*
|--------------------------------------------------------------------------
| Rute Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;

/*
|--------------------------------------------------------------------------
| Rute Admin (hanya role admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Manajemen Properti
        Route::resource('properties', AdminPropertyController::class);
        Route::patch('properties/{property}/status', [AdminPropertyController::class, 'updateStatus'])->name('properties.status');

        // Manajemen Galeri
        Route::get('galleries', [\App\Http\Controllers\Admin\GalleryController::class, 'index'])->name('galleries.index');
        Route::get('galleries/{property}', [\App\Http\Controllers\Admin\GalleryController::class, 'show'])->name('galleries.show');
        Route::post('galleries/{property}', [\App\Http\Controllers\Admin\GalleryController::class, 'store'])->name('galleries.store');
        Route::delete('galleries/photo/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'destroy'])->name('galleries.destroy');

        // Manajemen Penyewaan
        Route::resource('rentals', \App\Http\Controllers\Admin\RentalController::class);
        Route::patch('rentals/{rental}/status', [\App\Http\Controllers\Admin\RentalController::class, 'updateStatus'])->name('rentals.status');
        Route::post('rentals/{rental}/payment', [\App\Http\Controllers\Admin\PaymentController::class, 'store'])->name('rentals.payment.store');

        // Laporan
        Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

        // Pengaturan
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    });

/*
|--------------------------------------------------------------------------
| Rute Manager (hanya role manager)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
        
        // Laporan
        Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        
        // Data Properti (Read Only)
        Route::get('properties', [\App\Http\Controllers\Admin\PropertyController::class, 'index'])->name('properties.index');
    });

/*
|--------------------------------------------------------------------------
| Rute User (area pelanggan)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/properties/{property}/order', [\App\Http\Controllers\User\OrderController::class, 'create'])->name('orders.create');
        Route::post('/properties/{property}/order', [\App\Http\Controllers\User\OrderController::class, 'store'])->name('orders.store');

        // Riwayat Penyewaan Saya
        Route::get('/rentals', [\App\Http\Controllers\User\RentalController::class, 'index'])->name('rentals.index');
        Route::get('/rentals/{rental}', [\App\Http\Controllers\User\RentalController::class, 'show'])->name('rentals.show');
    });
