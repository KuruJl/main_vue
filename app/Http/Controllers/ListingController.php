<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Booking;
use App\Models\Image;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

// **ВАЖНО:** Импортируй трейт AuthorizesRequests
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ListingController extends Controller
{
    // **ВАЖНО:** Используй трейт AuthorizesRequests здесь!
    use AuthorizesRequests; // <-- ЭТА СТРОКА!

    /**
     * Отображает список объявлений с опциями фильтрации.
     * Используется для страницы поиска.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // ... (весь твой существующий код для index) ...
        $cities = Listing::select('city')
                         ->distinct()
                         ->orderBy('city')
                         ->pluck('city')
                         ->toArray();

        $minPrice = Listing::min('price_per_night') ?? 0;
        $maxPrice = Listing::max('price_per_night') ?? 0;
        $minRooms = Listing::min('count_rooms') ?? 0;
        $maxRooms = Listing::max('count_rooms') ?? 0;

        $query = Listing::with('images');

        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        if ($request->filled('count_rooms')) {
            $query->where('count_rooms', (int) $request->input('count_rooms'));
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', (float) $request->input('max_price'));
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');

            $query->whereDoesntHave('bookings', function ($q) use ($dateFrom, $dateTo) {
                $q->whereIn('status', ['pending', 'confirmed'])
                  ->where(function ($query) use ($dateFrom, $dateTo) {
                      $query->where('start_date', '<=', $dateTo)
                            ->where('end_date', '>=', $dateFrom);
                  });
            });
        }

        $listings = $query->get();

        return Inertia::render('Search', [
            'listings' => $listings,
            'filters' => $request->all(),
            'filterOptions' => [
                'cities' => $cities,
                'minPriceOverall' => $minPrice,
                'maxPriceOverall' => $maxPrice,
                'minRoomsOverall' => $minRooms,
                'maxRoomsOverall' => $maxRooms,
            ]
        ]);
    }

    /**
     * Отображает страницу с деталями конкретного объявления.
     *
     * @param  \App\Models\Listing  $listing
     * @return \Inertia\Response
     */
    public function show(Listing $listing)
    {
        // ... (весь твой существующий код для show) ...
        $listing->load('images');

        $bookedDates = Booking::where('listing_id', $listing->id)
                               ->whereIn('status', ['pending', 'confirmed'])
                               ->get(['start_date', 'end_date']);

        $unavailableDates = [];
        foreach ($bookedDates as $booking) {
            $unavailableDates[] = [
                'start' => $booking->start_date,
                'end' => $booking->end_date,
            ];
        }

        return Inertia::render('Room', [
            'listing' => $listing,
            'unavailableDates' => $unavailableDates,
        ]);
    }

    /**
     * Сохраняет новое объявление в хранилище.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // ... (весь твой существующий код для store) ...
        if (!Auth::check()) {
            return back()->withErrors(['auth' => 'Для создания объявления необходимо войти в систему.']);
        }

        $request->merge(['user_id' => Auth::id()]);

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:1',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'count_rooms' => 'required|integer|min:1',
            'images' => 'array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $listing = Listing::create($validatedData);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = Storage::disk('public')->put('listings', $imageFile);
                $listing->images()->create([
                    'url' => Storage::url($path),
                    'is_cover' => false
                ]);
            }
        }

        return redirect()->route('listings.show', $listing->id)
                         ->with('success', 'Объявление успешно создано!');
    }

    /**
     * Отображает форму для редактирования указанного объявления.
     *
     * @param  \App\Models\Listing  $listing
     * @return \Inertia\Response
     */
    public function edit(Listing $listing)
    {
        // 1. Авторизация: Проверяем, имеет ли текущий пользователь право редактировать это объявление.
        $this->authorize('update', $listing);

        // 2. Загружаем все изображения, связанные с объявлением, для отображения в форме.
        $listing->load('images');

        // 3. Рендерим Vue-компонент формы редактирования, передавая ему объект объявления.
        return Inertia::render('Edit_ad', [
            'listing' => $listing,
        ]);
    }

    /**
     * Обновляет указанное объявление в хранилище.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Listing  $listing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Listing $listing)
    {
        // 1. Авторизация: Проверяем, имеет ли текущий пользователь право обновлять это объявление.
        $this->authorize('update', $listing);

        // 2. Валидация входящих данных для обновления.
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:1',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'count_rooms' => 'required|integer|min:1',
            'new_images' => 'nullable|array|max:5',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'existing_image_ids_to_keep' => 'nullable|array',
            'existing_image_ids_to_keep.*' => 'exists:images,id',
        ]);

        // 3. Обновление основных полей объявления.
        $listing->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'price_per_night' => $validatedData['price_per_night'],
            'address' => $validatedData['address'],
            'city' => $validatedData['city'],
            'count_rooms' => $validatedData['count_rooms'],
        ]);

        // 4. Логика управления изображениями:
        $currentImageIds = $listing->images->pluck('id')->toArray();
        $imageIdsToKeep = $validatedData['existing_image_ids_to_keep'] ?? [];
        $imageIdsToDelete = array_diff($currentImageIds, $imageIdsToKeep);

        if (!empty($imageIdsToDelete)) {
            $imagesToDelete = $listing->images()->whereIn('id', $imageIdsToDelete)->get();
            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $image->url));
                $image->delete();
            }
        }

        if ($request->hasFile('new_images')) {
            if ($listing->images()->count() + count($request->file('new_images')) > 5) {
                return back()->withErrors(['new_images' => 'Общее количество изображений не может превышать 5.']);
            }

            foreach ($request->file('new_images') as $imageFile) {
                $path = Storage::disk('public')->put('listings', $imageFile);
                $listing->images()->create([
                    'url' => Storage::url($path),
                    'is_cover' => false
                ]);
            }
        }

        // 5. Перенаправление после успешного обновления.
        // Здесь используем прямой URL, так как мы договорились не использовать Ziggy.
        return redirect('/listings/' . $listing->id)
                         ->with('success', 'Объявление успешно обновлено!');
    }

    /**
     * Удаляет указанное объявление из хранилища.
     *
     * @param  \App\Models\Listing  $listing
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Listing $listing)
    {
        // 1. Авторизация: Проверяем, имеет ли текущий пользователь право удалять это объявление.
        $this->authorize('delete', $listing);

        // 2. Удаляем все связанные изображения с диска и из базы данных.
        foreach ($listing->images as $image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $image->url));
            $image->delete();
        }

        // 3. Удаляем само объявление.
        $listing->delete();

        // 4. Перенаправление после успешного удаления.
        // Здесь также используем прямой URL.
        return redirect('/')->with('success', 'Объявление успешно удалено.');
    }
}