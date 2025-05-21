<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password',
        'is_admin',
        'phone',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

}
