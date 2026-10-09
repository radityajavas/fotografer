<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\LandingController;
use App\Http\Controllers\PhotographyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ChatController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PhotographerController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Auth\ProfileController;

// 1. Auth
Auth::routes();

// 2. Public
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/fotografer/cari', [LandingController::class, 'cari'])->name('fotografer.cari');

Route::get('/home', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('landing');
})->middleware('auth')->name('home');

Route::get('/fotografi', [PhotographyController::class, 'index'])->name('fotografi.index');
Route::post('/fotografi/contact', [PhotographyController::class, 'storeContact'])->name('fotografi.contact');

Route::get('/photographer/detail', function () {
    return view('photographers.show');
});


// 3. Profil pengguna
Route::middleware(['auth'])->group(function () {

    // Halaman profil
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Ganti foto profil dari halaman Profile
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
        ->name('profile.photo');
});


// 4. Pelanggan
Route::middleware(['auth'])->group(function () {
    Route::resource('booking', BookingController::class);
    Route::resource('pelanggan', PelangganController::class);
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');

    Route::get('/booking/{id}/chat', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/booking/{id}/chat', [ChatController::class, 'store'])->name('chat.store');
});

// 5. Admin (auth + admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/chats', [AdminChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{id}', [AdminChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{id}', [AdminChatController::class, 'store'])->name('chats.store');

    Route::resource('customers', CustomerController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::delete('/bookings/{id}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::delete('/schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');

    Route::resource('photographers', PhotographerController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);
    Route::resource('packages', PackageController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);
});
