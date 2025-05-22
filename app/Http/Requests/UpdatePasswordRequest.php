<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password; // Для более продвинутых правил пароля

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Разрешаем авторизованным пользователям
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'current_password'],
            'password' => [
                'required',
                'string',
                Password::min(8) // Минимум 8 символов
                    ->mixedCase() // Должны быть буквы в верхнем и нижнем регистре
                    ->numbers() // Должны быть цифры
                    ->symbols(), // Должны быть символы
                'confirmed', // Должен совпадать с password_confirmation
            ],
            'password_confirmation' => ['required', 'string'], // Явно указываем
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Введите текущий пароль.',
            'current_password.current_password' => 'Указанный текущий пароль неверен.',
            'password.required' => 'Введите новый пароль.',
            'password.min' => 'Новый пароль должен содержать минимум 8 символов.',
            'password.mixedCase' => 'Новый пароль должен содержать буквы в верхнем и нижнем регистре.',
            'password.numbers' => 'Новый пароль должен содержать хотя бы одну цифру.',
            'password.symbols' => 'Новый пароль должен содержать хотя бы один специальный символ.',
            'password.confirmed' => 'Подтверждение нового пароля не совпадает.',
            'password_confirmation.required' => 'Пожалуйста, подтвердите новый пароль.',
        ];
    }
}