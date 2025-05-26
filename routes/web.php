<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\Host\BookingManagementController;
use App\Http\Controllers\BookingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::middleware(['auth'])->group(function () { // Группируем защищенные маршруты
    Route::get('/profilee', function () {
        return Inertia::render('Profilee');
    })->name('profile.show');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    // *** ПЕРЕМЕЩЕННЫЙ МАРШРУТ ДЛЯ БРОНИРОВАНИЯ В ГРУППУ 'auth' ***
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
});
Route::middleware('auth')->group(function () {
    // Страница "Мои бронирования" для владельца
    Route::get('/my-bookings', [BookingManagementController::class, 'index']); // Удалено ->name('host.bookings.index')
    
    // Маршруты для изменения статуса бронирования
    Route::put('/my-bookings/{booking}/confirm', [BookingManagementController::class, 'confirm']); // Удалено ->name('host.bookings.confirm')
    Route::put('/my-bookings/{booking}/decline', [BookingManagementController::class, 'decline']); // Удалено ->name('host.bookings.decline')
});

Route::get('/search', [ListingController::class, 'index'])->name('listings.index');
// Route::resource('listings', ListingController::class); // Этот ресурс уже включает listings/{listing}
// Если вы используете Resource-контроллер, то Route::get('/listings/{listing}', ...) не нужен
// Если вы не используете resource, то оставьте эту строку:
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');


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