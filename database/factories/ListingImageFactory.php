<?php

namespace Database\Factories;

use App\Models\ListingImage; // ОБЯЗАТЕЛЬНО ИМПОРТИРУЙТЕ ПРАВИЛЬНУЮ МОДЕЛЬ!
use App\Models\Listing; // Также импортируйте модель Listing, если она нужна для связи
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ListingImage::class; // УКАЖИТЕ ПРАВИЛЬНУЮ МОДЕЛЬ

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Предполагаем, что у вас есть набор фейковых картинок в public/images/fake/
        // Или используем заглушки Placeholder.com
        $imageUrls = [
            'https://via.placeholder.com/640x480?text=House+1',
            'https://via.placeholder.com/640x480?text=Apartment+2',
            'https://via.placeholder.com/640x480?text=Cottage+3',
            'https://via.placeholder.com/640x480?text=Flat+4',
            'https://via.placeholder.com/640x480?text=Room+5',
            // Если у вас есть свои фейковые картинки в public/images/fake/, добавьте их сюда:
            // '/images/fake/house_1.jpg',
            // '/images/fake/flat_2.jpg',
        ];

        return [
            'listing_id' => Listing::factory(), // Автоматически свяжет с новым объявлением (если не указано явно)
            'url' => $this->faker->randomElement($imageUrls), // Случайная картинка из списка
            'is_cover' => false, // По умолчанию не обложка
        ];
    }

    /**
     * Indicate that the image is a cover image.
     */
    public function cover(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_cover' => true,
            ];
        });
    }
}