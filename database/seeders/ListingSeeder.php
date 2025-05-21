<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\User;

class ListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

        Listing::create([
            'user_id' => 1,
            'title' => 'Крестовая падьььь',
            'description' => 'Гостиничный комплекс «Крестовая Падь» располагается на берегу живописного озера Байкал в поселке Листвянка, в 60 минутах пути от аэропорта Иркутска. Отель отлично подойдет как для спокойного отдыха наедине с природой, вдали от городских проблем, так и для организации деловых и корпоративных мероприятий различного уровня.',
            'price_per_night' => 4500,
            'address' => '​Адрес: рп. Листвянка, Горная, ст14',
            'city' => 'Иркутск',
            'count_rooms' => 2,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        

        
    }
}
