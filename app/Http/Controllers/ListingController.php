<?php

namespace App\Http\Controllers;

use App\Models\Listing;
 use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage; // <-- ДОБАВЬТЕ ЭТУ СТРОКУ!

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::with('images')->get();
        return Inertia::render('Search', [
            'listings' => $listings,
        ]);
    }

    public function show($id)
    {
        $listing = Listing::with('images')->find($id);
        if (!$listing) {
            abort(404); // Или перенаправление, или другое действие
        }
        return Inertia::render('Room', [
            'listing' => $listing,
        ]);
    }

    public function store(Request $request)
    {
        // ПРОВЕРКА АУТЕНТИФИКАЦИИ И БЕЗОПАСНОСТИ:
        // Убедимся, что пользователь авторизован, и берем его ID.
        // Это предотвращает подмену user_id через клиентский запрос.
        if (!auth()->check()) {
            // Если пользователь не авторизован, можно вернуть ошибку
            // или перенаправить на страницу входа.
            return back()->withErrors(['auth' => 'Для создания объявления необходимо войти в систему.']);
            // Или: return redirect()->route('login');
        }

        // Проверка user_id, чтобы убедиться, что он совпадает с аутентифицированным пользователем
        // Это хорошая практика безопасности
        $request->merge(['user_id' => auth()->id()]);


        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id', // Убеждаемся, что user_id валиден
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:1',
            'address' => 'required|string',
            'city' => 'required|string',
            'count_rooms' => 'required|integer|min:1',
            'images' => 'array|max:5', // Максимум 5 файлов
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Валидация для каждого файла
        ]);

        // Создаем объявление
        $listing = Listing::create($validatedData);

        // Обработка изображений
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Сохраняем изображение в публичное хранилище (storage/app/public/listings)
                $path = Storage::disk('public')->put('listings', $image);

                // Создаем запись в таблице listing_images
                $listing->images()->create([
                    'url' => Storage::url($path), // Генерируем публичный URL для изображения
                    'is_cover' => false // По умолчанию не обложка, можешь добавить логику для выбора обложки
                ]);
            }
        }

        // После успешного создания и обработки, перенаправляем пользователя
        // на страницу созданного объявления. Inertia.js автоматически обрабатывает редиректы.
        // Используем session()->flash для передачи сообщения об успехе.
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