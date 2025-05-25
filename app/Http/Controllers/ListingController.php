<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        // 1. Получаем все уникальные города для фильтра (из всех объявлений)
        $cities = Listing::select('city')
                         ->distinct()
                         ->orderBy('city')
                         ->pluck('city')
                         ->toArray();

        // 2. Получаем минимальную и максимальную цену / количество комнат для фильтра
        $minPrice = Listing::min('price_per_night');
        $maxPrice = Listing::max('price_per_night');
        $minRooms = Listing::min('count_rooms');
        $maxRooms = Listing::max('count_rooms');

        // Начинаем запрос к объявлениям, включая изображения
        $query = Listing::with('images');

        // Применяем фильтры из запроса
        // Фильтр по городу
        if ($request->has('city') && $request->input('city') !== null && $request->input('city') !== '') {
            $query->where('city', $request->input('city'));
        }

        // Фильтр по количеству комнат
        if ($request->has('count_rooms') && $request->input('count_rooms') !== null) {
            $query->where('count_rooms', (int) $request->input('count_rooms'));
        }

        // Фильтр по диапазону цен
        if ($request->has('min_price') && $request->input('min_price') !== null) {
            $query->where('price_per_night', '>=', (float) $request->input('min_price'));
        }
        if ($request->has('max_price') && $request->input('max_price') !== null) {
            $query->where('price_per_night', '<=', (float) $request->input('max_price'));
        }

        // Фильтр по дате (например, по дате создания объявления)
        // Для более сложной фильтрации по доступности дат потребуется отдельная логика с таблицей бронирований
        if ($request->has('date_from') && $request->input('date_from') !== null) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->has('date_to') && $request->input('date_to') !== null) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Получаем отфильтрованные объявления
        $listings = $query->get();

        return Inertia::render('Search', [
            'listings' => $listings,
            'filters' => $request->all(), // Передаем текущие примененные фильтры обратно во фронтенд
            'filterOptions' => [ // Опции для выпадающих списков/диапазонов
                'cities' => $cities,
                'minPriceOverall' => $minPrice,
                'maxPriceOverall' => $maxPrice,
                'minRoomsOverall' => $minRooms,
                'maxRoomsOverall' => $maxRooms,
            ]
        ]);
    }

    public function show($id)
    {
        $listing = Listing::with('images')->find($id);
        if (!$listing) {
            abort(404);
        }

        $bookedDates = Booking::where('listing_id', $listing->id)
                               ->whereIn('status', ['pending', 'confirmed'])
                               // ИСПОЛЬЗУЕМ ВАШИ НАЗВАНИЯ ПОЛЕЙ: start_date, end_date
                               ->get(['start_date', 'end_date']); // <--- ИЗМЕНЕНО

        $unavailableDates = [];
        foreach ($bookedDates as $booking) {
            $unavailableDates[] = [
                'start' => $booking->start_date, // <--- ИЗМЕНЕНО
                'end' => $booking->end_date,     // <--- ИЗМЕНЕНО
            ];
        }

        return Inertia::render('Room', [
            'listing' => $listing,
            'unavailableDates' => $unavailableDates,
        ]);
    }


    public function store(Request $request)
    {
        if (!auth()->check()) {
            return back()->withErrors(['auth' => 'Для создания объявления необходимо войти в систему.']);
        }

        $request->merge(['user_id' => auth()->id()]);

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:1',
            'address' => 'required|string',
            'city' => 'required|string',
            'count_rooms' => 'required|integer|min:1',
            'images' => 'array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $listing = Listing::create($validatedData);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = Storage::disk('public')->put('listings', $image);
                $listing->images()->create([
                    'url' => Storage::url($path),
                    'is_cover' => false
                ]);
            }
        }

        session()->flash('success', 'Объявление успешно создано!');
        return redirect()->route('listings.show', $listing->id);
    }

    public function update(Request $request, $id)
    {
        $listing = Listing::find($id);
        if (!$listing) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $listing->update($request->all());
        return response()->json($listing);
    }

    public function destroy($id)
    {
        $listing = Listing::find($id);
        if (!$listing) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $listing->delete();
        return response()->json(['message' => 'Deleted']);
    }
}