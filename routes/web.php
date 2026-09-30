<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Photographer;
use App\Models\User;

use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\PhotographyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ChatController;

use App\Http\Controllers\Admin\PhotographerController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

// 1. Auth
Auth::routes();

// 2. Public
Route::get('/', function () {
    $photographers = Photographer::all();
    return view('landing', compact('photographers'));
})->name('landing');

Route::get('/fotografer/cari', function (Request $request) {
    $photographers = Photographer::query()
        ->when($request->kategori, fn($q, $v) => $q->where('specialization', 'like', "%{$v}%"))
        ->get();

    return view('landing', compact('photographers'));
})->name('fotografer.cari');

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

// 3. Pelanggan
Route::middleware(['auth'])->group(function () {
    Route::resource('booking', BookingController::class);
    Route::resource('pelanggan', PelangganController::class);
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');

    Route::get('/booking/{id}/chat', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/booking/{id}/chat', [ChatController::class, 'store'])->name('chat.store');
});

// 4. Admin (auth + admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/chats', [AdminChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{id}', [AdminChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{id}', [AdminChatController::class, 'store'])->name('chats.store');

    Route::get('/customers', function () {
        $pelanggan = User::where('role', 'customer')->latest()->get();
        return view('admin.customers', compact('pelanggan'));
    })->name('customers.index');
    Route::get('/customers', function () {
        $pelanggan = User::where('role', 'customer')->latest()->get();
        return view('admin.customers', compact('pelanggan'));
    })->name('customers.index');

    Route::delete('/customers/{id}', function ($id) {
        User::where('role', 'customer')->findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Pelanggan berhasil dihapus!');
    })->name('customers.destroy');

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