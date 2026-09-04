<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\PhotographerController; 
use App\Http\Controllers\PhotographyController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PelangganController;

Route::get('/', function () {
    return view('welcome');
});

// Route /fotografi
Route::get('/fotografi', [PhotographyController::class, 'index'])->name('fotografi.index');
Route::post('/fotografi/contact', [PhotographyController::class, 'storeContact'])->name('fotografi.contact');

// Route Admin (Fotografer & Paket Foto)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('photographers', PhotographerController::class)->except(['create', 'edit', 'show']);
    Route::resource('packages', PackageController::class)->except(['create', 'edit', 'show']);
});

// Route Pelanggan & Booking
Route::resource('pelanggan', PelangganController::class);
Route::resource('booking', BookingController::class);

Route::resource('admin/packages', PackageController::class)->names('admin.packages');
Route::resource('admin/photographers', PhotographerController::class)->names('admin.photographers');

// Route Auth & Home
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route Panel Admin (Kelola Status Booking)
Route::get('/admin/bookings', [BookingController::class, 'adminIndex'])->name('admin.bookings.index');
Route::patch('/admin/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('admin.bookings.updateStatus');