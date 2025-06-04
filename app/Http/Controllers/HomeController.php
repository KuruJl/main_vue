<?php

namespace App\Http\Controllers;

use App\Models\Listing; // Убедитесь, что импортируете модель Listing
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // Логика для получения уникальных городов и диапазонов цен,
        // аналогичная той, что используется в ListingController
        $cities = Listing::distinct()->pluck('city')->sort()->values()->toArray();
        $minPriceOverall = Listing::min('price_per_night');
        $maxPriceOverall = Listing::max('price_per_night');
        $minRoomsOverall = Listing::min('count_rooms');
        $maxRoomsOverall = Listing::max('count_rooms');

        $filterOptions = [
            'cities' => $cities,
            'minPriceOverall' => $minPriceOverall,
            'maxPriceOverall' => $maxPriceOverall,
            'minRoomsOverall' => $minRoomsOverall,
            'maxRoomsOverall' => $maxRoomsOverall,
        ];

        return Inertia::render('Home', [ // Home.vue, который в свою очередь включает Main.vue
            'filterOptions' => $filterOptions,
            // ... другие данные, если Home.vue их ожидает
        ]);
    }
}