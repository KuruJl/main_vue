<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
// use Tightenco\Ziggy\Ziggy; // Убедитесь, что эта строка закомментирована или удалена, если вы не используете Ziggy

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared to all Inertia requests.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    // Добавьте другие поля пользователя, если нужно
                ] : null,
            ],
            // *** ЭТОТ БЛОК ТЕПЕРЬ КОРРЕКТНО ПЕРЕДАЕТ FLASH-СООБЩЕНИЯ В ПРОПСЫ ***
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'message' => fn () => $request->session()->get('message'), // Общее сообщение
                'errors' => fn () => $request->session()->get('errors') ? $request->session()->get('errors')->getBag('default')->getMessages() : (object) [],
            ],
            // ***************************************************************

            // Удаленный или закомментированный блок Ziggy, если он не используется:
            // 'ziggy' => fn () => [
            //     ...(new Ziggy)->toArray(),
            //     'location' => $request->url(),
            // ],
        ]);
    }
}