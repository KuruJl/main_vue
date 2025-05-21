<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price_per_night',
        'address',
        'city',
        'count_rooms',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
