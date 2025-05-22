<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\UpdatePasswordRequest; // <--- ДОБАВЬТЕ ЭТО
use Illuminate\Support\Facades\Hash; // <--- ДОБАВЬТЕ ЭТО для Hash::check()

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
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

        // Если вы добавили поле 'phone' в базу данных, добавьте его здесь
        // Пример: $request->user()->phone = $request->input('phone');
        // Пожалуйста, добавьте это, если phone есть в форме
        if ($request->has('phone')) {
            $request->user()->phone = $request->input('phone');
        }


        $request->user()->save();

        // ИЗМЕНИТЕ ЭТУ СТРОКУ:
        return Redirect::back()->with('success', 'Профиль успешно обновлен!'); // <--- Редирект назад с сообщением об успехе
    }
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        // $request->validated() уже содержит current_password, password, password_confirmation
        // и current_password уже проверен правилом 'current_password'

        $request->user()->forceFill([
            'password' => Hash::make($request->password),
        ])->save(); // Используем forceFill, чтобы обойти $fillable для пароля, если не добавлен

        return Redirect::back()->with('success', 'Пароль успешно изменен!');
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