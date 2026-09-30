<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Photographer;

// Import Controller
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhotographyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ChatController;

// Import Admin Controllers (Gunakan Alias AdminBookingController)
use App\Http\Controllers\Admin\PhotographerController; 
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

/*
|--------------------------------------------------------------------------
| Web Routes - Cocofonder
|--------------------------------------------------------------------------
*/

// 1. Auth Routes
Auth::routes();

// 2. Public Routes
Route::get('/', function () {
    $photographers = Photographer::all();
    return view('landing', compact('photographers'));
})->name('landing');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/fotografi', [PhotographyController::class, 'index'])->name('fotografi.index');
Route::post('/fotografi/contact', [PhotographyController::class, 'storeContact'])->name('fotografi.contact');
Route::get('/fotografer/cari', [PhotographerController::class, 'cari'])->name('fotografer.cari');

Route::get('/photographer/detail', function () {
    return view('photographers.show');
});

// 3. Customer Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('booking', BookingController::class);
    Route::resource('pelanggan', PelangganController::class);
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');

    // Chat Routes
    Route::get('/booking/{id}/chat', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/booking/{id}/chat', [ChatController::class, 'store'])->name('chat.store');
});

// 4. Admin Panel Routes (Disatukan dalam namespace/prefix Admin)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Pelanggan
    Route::get('/customers', function () {
        $pelanggan = \App\Models\User::where('role', 'customer')->get();
        return view('admin.customers', compact('pelanggan'));
    })->name('customers.index');

    // Data Booking Khusus Admin
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');

    // Master Data & Agenda
    Route::resource('photographers', PhotographerController::class);
    Route::resource('packages', PackageController::class);
    Route::get('/schedule', [BookingController::class, 'schedule'])->name('schedule.index');
});