<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/profilee', function () {
    return Inertia::render('Profilee');
})->middleware(['auth'])->name('profile.show'); // Добавляем middleware 'auth' и имя маршрута

// Маршрут для обновления профиля
Route::patch('/profile', [ProfileController::class, 'update'])
    ->middleware(['auth'])
    ->name('profile.update');

Route::get('/search', [ListingController::class, 'index'])->name('listings.index');
Route::resource('listings', ListingController::class);

Route::get('/room', function () {
    return Inertia::render('Room');
});
Route::get('/ad', function () {
    return Inertia::render('Ad');
});


Route::get('/dashboard', function () {
return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';