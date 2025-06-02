<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookingManagementController extends Controller
{
    // Метод для отображения бронирований, ожидающих подтверждения для текущего пользователя
    public function index()
    {
        // Получаем объявления, принадлежащие текущему аутентифицированному пользователю
        $userListingsIds = Auth::user()->listings()->pluck('id');

        // Получаем бронирования для этих объявлений, которые находятся в статусе 'pending'
        // С eager loading для информации об объявлении и пользователе, который бронировал
        $pendingBookings = Booking::whereIn('listing_id', $userListingsIds)
            ->where('status', 'pending')
            ->with(['listing', 'user']) // Загружаем связанные модели
            ->latest() // Сортируем по дате создания, чтобы новые были сверху
            ->get();
        
        // Получаем подтвержденные бронирования (для полноты картины на странице)
        $confirmedBookings = Booking::whereIn('listing_id', $userListingsIds)
            ->where('status', 'confirmed')
            ->with(['listing', 'user'])
            ->latest()
            ->get();

        return Inertia::render('Host/MyBookings', [
            'pendingBookings' => $pendingBookings,
            'confirmedBookings' => $confirmedBookings,
            // Добавьте 'declinedBookings' если нужно
        ]);
    }

    // Метод для подтверждения бронирования
    public function confirm(Booking $booking)
    {
        // ПРОВЕРКА АВТОРИЗАЦИИ: Убедимся, что текущий пользователь является владельцем объявления
        // к которому относится это бронирование.
        if (Auth::id() !== $booking->listing->user_id) {
            abort(403, 'У вас нет прав для подтверждения этого бронирования.');
        }

        // Проверим, что бронирование находится в статусе 'pending'
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Бронирование уже не находится в ожидании.');
        }

        $booking->update(['status' => 'confirmed']);

        // Здесь можно добавить логику отправки уведомления пользователю о подтверждении
        // Mail::to($booking->user->email)->send(new BookingConfirmed($booking));

        return redirect()->back()->with('success', 'Бронирование успешно подтверждено!');
    }

    // Метод для отклонения бронирования
    public function decline(Booking $booking)
    {
        // ПРОВЕРКА АВТОРИЗАЦИИ
        if (Auth::id() !== $booking->listing->user_id) {
            abort(403, 'У вас нет прав для отклонения этого бронирования.');
        }

        // Проверим, что бронирование находится в статусе 'pending'
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Бронирование уже не находится в ожидании.');
        }

        $booking->update(['status' => 'cancelled']); // <-- Исправлено: значение в кавычках!

        // Здесь можно добавить логику отправки уведомления пользователю об отклонении
        // Mail::to($booking->user->email)->send(new BookingDeclined($booking));

        return redirect()->back()->with('success', 'Бронирование успешно отклонено.');
    }
}