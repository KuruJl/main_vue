<?php

namespace Database\Factories;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Listing::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(), // Автоматически создаст нового пользователя
            'title' => $this->faker->sentence(rand(3, 7)), // Случайное предложение
            'description' => $this->faker->paragraphs(rand(3, 6), true), // Несколько параграфов
            'price_per_night' => $this->faker->numberBetween(1500, 15000), // Цена от 1500 до 15000
            'address' => $this->faker->address, // Случайный адрес
            'city' => $this->faker->randomElement(['Москва', 'Санкт-Петербург', 'Казань', 'Екатеринбург', 'Новосибирск', 'Сочи', 'Иркутск', 'Владивосток']), // Разные города
            'count_rooms' => $this->faker->numberBetween(1, 5), // От 1 до 5 комнат
            'is_active' => $this->faker->boolean(80), // 80% шанс быть активным
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'), // Случайная дата создания за последний год
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'), // Случайная дата обновления
        ];
    }
}