<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Photographer; // Tambahkan import model Photographer di sini
use App\Http\Controllers\Admin\PhotographerController; 
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PhotographyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Web Routes - Cocofonder
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // ... route kamu yang lain ...

    Route::get('/booking/{id}/chat', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/booking/{id}/chat', [ChatController::class, 'store'])->name('chat.store');
});

// 1. Auth Routes
Auth::routes();

// 2. Public Routes (Bisa diakses tanpa login)
Route::get('/', function () {
    $photographers = Photographer::all(); // Mengambil seluruh data fotografer dari database
    return view('landing', compact('photographers'));
})->name('landing');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/fotografi', [PhotographyController::class, 'index'])->name('fotografi.index');
Route::post('/fotografi/contact', [PhotographyController::class, 'storeContact'])->name('fotografi.contact');

Route::get('/photographer/detail', function () {
    return view('photographers.show');
});

// 3. Customer Routes (Wajib Login untuk Transaksi/Booking)
Route::middleware(['auth'])->group(function () {
    Route::resource('booking', BookingController::class);
    Route::resource('pelanggan', PelangganController::class);
});

// 4. Admin Routes (Khusus Admin)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('photographers', PhotographerController::class);
    Route::resource('packages', PackageController::class);

    Route::get('/bookings', [BookingController::class, 'adminIndex'])->name('bookings.index');
    Route::patch('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::get('/schedule', [BookingController::class, 'schedule'])->name('schedule.index');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
});