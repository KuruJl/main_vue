<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\Host\BookingManagementController;
use App\Http\Controllers\BookingController; // Используется для store
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home'); // Или просто '/' без имени


Route::middleware(['auth'])->group(function () {
    // ЕДИНСТВЕННЫЙ И ПРАВИЛЬНЫЙ МАРШРУТ ПРОФИЛЯ
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // Добавлен delete маршрут

    // Маршрут для создания бронирования (остался как был, но теперь в группе auth)
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store'); // <-- ДОБАВЬТЕ ЭТУ СТРОКУ
    // Маршруты для управления бронированиями хостом
    Route::get('/my-bookings', [BookingManagementController::class, 'index'])->name('host.bookings.index');
    Route::put('/my-bookings/{booking}/confirm', [BookingManagementController::class, 'confirm'])->name('host.bookings.confirm');
    Route::put('/my-bookings/{booking}/decline', [BookingManagementController::class, 'decline'])->name('host.bookings.decline');

    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::patch('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');

    // Остальные маршруты
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
});

// УДАЛЯЕМ этот блок с /profilee, так как он больше не нужен
// Route::middleware(['auth'])->group(function () {
//     Route::get('/profilee', function () {
//         return Inertia::render('Profilee');
//     })->name('profile.show');
// });

Route::get('/search', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

Route::get('/room', function () {
    return Inertia::render('Room');
});
Route::get('/ad', function () {
    return Inertia::render('Ad');
});

require __DIR__.'/auth.php';