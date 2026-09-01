<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PhotographerController; 
use App\Http\Controllers\PhotographyController;
use App\Http\Controllers\Admin\PackageController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/fotografi', function () {
    return view('fotografi');
});
Route::get('/fotografi', [PhotographyController::class, 'index'])->name('fotografi.index');
Route::post('/fotografi/contact', [PhotographyController::class, 'storeContact'])->name('fotografi.contact');

Route::prefix('admin')->name('admin.')->group(function () {
    // Route CRUD Fotografer
    Route::resource('photographers', PhotographerController::class)->except(['create', 'edit', 'show']);
    
    // Route CRUD Paket Foto
    Route::resource('packages', PackageController::class)->except(['create', 'edit', 'show']);
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
