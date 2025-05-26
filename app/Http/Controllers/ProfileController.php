<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User; // Убедитесь, что User импортирован
use App\Models\Booking; // Убедитесь, что Booking импортирован

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        // Получаем текущего аутентифицированного пользователя
        $user = $request->user();

        // Загружаем бронирования текущего пользователя с информацией об объявлении (listing)
        // Если у пользователя есть отношение 'bookings' в модели User:
        $bookings = $user->bookings()->with('listing')->latest()->get(); // 'listing' - это отношение в модели Booking
        // Если у вас нет отношения bookings в User, то можно так:
        // $bookings = Booking::where('user_id', $user->id)->with('listing')->latest()->get();


        return Inertia::render('Profile/Edit', [
            'user' => $user->toArray(), // Передаем данные пользователя
            'bookings' => $bookings->toArray(), // Передаем бронирования пользователя
            'mustVerifyEmail' => $user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail(),
            'status' => session('status'),
            'success' => session('success'), // Для flash-сообщений, если они используются
            'error' => session('error'),     // Для flash-сообщений
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Данные профиля успешно обновлены.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password),
        ]);

        return Redirect::route('profile.edit')->with('success', 'Пароль успешно обновлен.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}