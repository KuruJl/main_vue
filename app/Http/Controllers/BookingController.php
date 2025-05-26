<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing; // Важно: убедитесь, что модель Listing импортирована
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Класс для работы с датами и временем
use Inertia\Inertia; // Класс для Inertia.js ответов (хотя здесь он используется косвенно через redirect()->back())

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     * (Обычно для API-эндпоинта, не для Inertia-страницы)
     */
    public function index()
    {
        // Вы можете вернуть список бронирований текущего пользователя
        // return Inertia::render('Bookings/Index', [
        //     'bookings' => Auth::user()->bookings()->with('listing')->get(),
        // ]);
        return response()->json(Booking::all()); // Это для API, если он нужен
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Валидация входных данных
        // Валидация 'status' может быть опущена, если вы всегда устанавливаете 'pending'
        $validated = $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'start_date' => 'required|date|after_or_equal:today', // Дата заезда не может быть раньше сегодня
            'end_date' => 'required|date|after:start_date',       // Дата выезда должна быть после даты заезда
            'total_price' => 'required|numeric|min:0',             // Цена должна быть числом и не меньше 0
            // 'status' => 'in:pending,confirmed,cancelled', // Это можно удалить, если вы всегда ставите 'pending'
        ]);

        // Получаем объект объявления, чтобы убедиться, что оно существует
        $listing = Listing::find($validated['listing_id']);

        // Эта проверка, скорее всего, не нужна, т.к. 'exists:listings,id' уже это делает
        // Но как дополнительная мера безопасности можно оставить
        if (!$listing) {
            return redirect()->back()->withErrors(['message' => 'Выбранное объявление не найдено.']);
        }

        // 2. Проверка доступности дат (логика пересечений)
        // Carbon::parse() преобразует строки дат в объекты Carbon для удобной работы
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        
        $conflictingBookings = Booking::where('listing_id', $validated['listing_id'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($startDate, $endDate) {
                // Случай 1: Существующее бронирование начинается в пределах нового периода
                $query->whereBetween('start_date', [$startDate, $endDate->subDay()])
                      // Случай 2: Существующее бронирование заканчивается в пределах нового периода
                      ->orWhereBetween('end_date', [$startDate->addDay(), $endDate])
                      // Случай 3: Новое бронирование полностью охватывает существующее
                      ->orWhere(function ($query) use ($startDate, $endDate) {
                          $query->where('start_date', '>=', $startDate)
                                ->where('end_date', '<=', $endDate);
                      })
                      // Случай 4: Существующее бронирование полностью охватывает новое
                      ->orWhere(function ($query) use ($startDate, $endDate) {
                          $query->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                      });
            })
            ->count();

        if ($conflictingBookings > 0) {
            // Если даты заняты, перенаправляем обратно с ошибкой
            // 'message' будет доступен во Vue-компоненте через props.errors.message
            return redirect()->back()->withErrors(['message' => 'Выбранные даты уже заняты или частично пересекаются. Пожалуйста, выберите другие даты.']);
        }

        // 3. Создание бронирования
        $booking = Booking::create([
            'listing_id' => $validated['listing_id'],
            'user_id' => Auth::id(), // ID текущего авторизованного пользователя
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_price' => $validated['total_price'],
            'status' => 'pending', // Начальный статус бронирования
        ]);

        // 4. Возвращаем успешное перенаправление с flash-сообщением
        // 'success' будет доступен во Vue-компоненте через props.success
        return redirect()->route('listings.show', ['listing' => $validated['listing_id']])
        ->with('success', 'Бронирование успешно создано! Ожидайте подтверждения.'); 
        // Альтернативно, если вы хотите перенаправить на конкретную страницу (например, страницу бронирований пользователя):
        // return redirect()->route('user.bookings.index')->with('success', 'Бронирование успешно создано!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $booking = Booking::find($id);
        return $booking ? response()->json($booking) : response()->json(['error' => 'Not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) return response()->json(['error' => 'Not found'], 404);

        // Добавьте валидацию для update, аналогично store
        $booking->update($request->all());
        return response()->json($booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);
        if (!$booking) return response()->json(['error' => 'Not found'], 404);

        $booking->delete();
        return response()->json(['message' => 'Deleted']);
    }
}