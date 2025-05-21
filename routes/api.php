<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProductController;
use App\Models\Listing;
use Illuminate\Support\Facades\Route;


Route::get('/search', function() {
    return Listing::with('images')
        ->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($listing) {
            return [
                'id' => $listing->id,
                'title' => $listing->title,
                'price_per_night' => $listing->price_per_night,
                'address' => $listing->address,
                'city' => $listing->city,
                'rooms' => $listing->count_rooms,
                'image_url' => $listing->images->first()?->url ?? '/images/default-listing.jpg'
            ];
        });
});
