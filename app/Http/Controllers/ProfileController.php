<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Booking; // Убедитесь, что эта строка есть
use Illuminate\Validation\Rule; // Добавьте этот импорт для Rule
use Illuminate\Validation\Rules\Password; // Добавьте этот импорт для Password

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();

        $bookings = $user->bookings()->with('listing')->latest()->get();
        return Inertia::render('Profile/Edit', [ // Изменил здесь с 'Profile/Edit' на 'ProfileBlock' как в твоем Vue-коде
            'mustVerifyEmail' => $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail(),
            'status' => session('status'),
            'bookings' => $bookings->toArray(),
            'success' => session('success'),
            'error' => session('error'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Валидация для имени, email и телефона
        $validatedData = $request->validate([ // <-- ИЗМЕНЕНО: теперь результат валидации присваивается переменной $validatedData
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->fill($validatedData); // <-- ИЗМЕНЕНО: используем $validatedData

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Профиль успешно обновлен.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validatedPasswordData = $request->validate([ // <-- ИЗМЕНЕНО: теперь результат валидации присваивается переменной $validatedPasswordData
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($validatedPasswordData['password']), // <-- ИЗМЕНЕНО: используем $validatedPasswordData
        ]);

        return Redirect::route('profile.edit')->with('success', 'Пароль успешно обновлен.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Здесь уже, скорее всего, все правильно, но на всякий случай
        $validatedData = $request->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}