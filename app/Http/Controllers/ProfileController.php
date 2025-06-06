<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Booking;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash; // Добавьте этот импорт для Hash

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();

        $bookings = $user->bookings()->with('listing')->latest()->get();
        return Inertia::render('Profile/Edit', [ // ИЛИ 'ProfileBlock' если вы переименовали Edit.vue в ProfileBlock.vue
            'mustVerifyEmail' => $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail(),
            'status' => session('status'),
            'bookings' => $bookings->toArray(),
            'success' => session('success'),
            'error' => session('error'),
            // Передаем текущий телефон, чтобы Vue мог его отобразить
            'userPhone' => $user->phone, // <-- Добавлено
        ]);
    }

    /**
     * Update the user's profile information and optionally password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Валидация для имени, email и телефона
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        // 2. Условная валидация для пароля
        // Если любое из полей пароля заполнено, тогда требуем все поля пароля и валидируем их
        if ($request->filled('current_password') || $request->filled('password') || $request->filled('password_confirmation')) {
            $rules['current_password'] = ['required', 'string', 'current_password'];
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed', Password::defaults()];
        }

        $validatedData = $request->validate($rules);

        // Обновление основной информации пользователя
        $user->fill([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'], // 'phone' всегда будет в $validatedData из-за nullable
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Обновление пароля, если поля были заполнены
        if (isset($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Профиль и/или пароль успешно обновлены.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
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