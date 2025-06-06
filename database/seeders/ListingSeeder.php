<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\User;
use App\Models\ListingImage; // Убедитесь, что это правильная модель для изображений

class ListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        // ===============================================
        // Конкретные объявления, заполненные вручную
        // ===============================================

        // Объявление 1 (Иркутск - как ваш первый пример)
        $listing1 = Listing::create([
            'user_id' => 1,
            'title' => 'Гостиничный комплекс «Крестовая Падь»',
            'description' => 'Располагается на берегу живописного озера Байкал в поселке Листвянка, в 60 минутах пути от аэропорта Иркутска. Отель отлично подойдет как для спокойного отдыха наедине с природой, вдали от городских проблем, так и для организации деловых и корпоративных мероприятий различного уровня.',
            'price_per_night' => 4500,
            'address' => 'рп. Листвянка, Горная, ст14',
            'city' => 'Иркутск',
            'count_rooms' => 2,
            'is_active' => true,
            'created_at' => now()->subDays(30),
            'updated_at' => now()->subDays(15),
        ]);
        $listing1->images()->createMany([
            ['url' => '/images/alan1.jpg', 'is_cover' => true],
            ['url' => '/images/alan2.jpg', 'is_cover' => false],
            ['url' => '/images/alan3.jpg', 'is_cover' => false],
            ['url' => '/images/alan4.jpg', 'is_cover' => false],
        ]);

        // Объявление 2 (Москва - Апартаменты в центре)
        $listing2 = Listing::create([
            'user_id' => 1,
            'title' => 'Современные апартаменты в центре Москвы',
            'description' => 'Просторные апартаменты в элитном ЖК с панорамными окнами и видом на город. Полностью меблированы и оснащены всем необходимым для комфортного проживания. Рядом метро, парки, рестораны.',
            'price_per_night' => 8500,
            'address' => 'ул. Тверская, д. 15, кв. 7',
            'city' => 'Москва',
            'count_rooms' => 3,
            'is_active' => true,
            'created_at' => now()->subDays(25),
            'updated_at' => now()->subDays(0),
        ]);
        $listing2->images()->createMany([
            ['url' => '/images/kum1.jpg', 'is_cover' => true],
            ['url' => '/images/kum2.jpg', 'is_cover' => false],
            ['url' => '/images/kum3.jpg', 'is_cover' => false],
            ['url' => '/images/kum4.jpg', 'is_cover' => false],
        ]);

        // Объявление 3 (Санкт-Петербург - Уютная студия)
        $listing3 = Listing::create([
            'user_id' => 1,
            'title' => 'Уютная студия у Невского проспекта',
            'description' => 'Идеальное место для туристов. Компактная, но функциональная студия в историческом центре Петербурга. В шаговой доступности основные достопримечательности: Эрмитаж, Казанский собор.',
            'price_per_night' => 3200,
            'address' => 'Невский пр., д. 50, к. 2',
            'city' => 'Санкт-Петербург',
            'count_rooms' => 1,
            'is_active' => true,
            'created_at' => now()->subDays(20),
            'updated_at' => now()->subDays(8),
        ]);
        $listing3->images()->createMany([
            ['url' => '/images/laz1.jpg', 'is_cover' => true],
            ['url' => '/images/laz2.jpg', 'is_cover' => false],
            ['url' => '/images/laz3.jpg', 'is_cover' => false],
            ['url' => '/images/laz4.jpg', 'is_cover' => false],
        ]);

        // Объявление 4 (Казань - Дом с видом на Казанку)
        $listing4 = Listing::create([
            'user_id' => 1,
            'title' => 'Просторный дом с видом на реку Казанку',
            'description' => 'Уютный загородный дом всего в 20 минутах от центра Казани. Отлично подходит для семейного отдыха. Имеется сауна, беседка и большая территория для барбекю.',
            'price_per_night' => 12000,
            'address' => 'ул. Береговая, д. 10, пос. Залесный',
            'city' => 'Казань',
            'count_rooms' => 5,
            'is_active' => true,
            'created_at' => now()->subDays(18),
            'updated_at' => now()->subDays(7),
        ]);
        $listing4->images()->createMany([
            ['url' => '/images/legenda1.jpg', 'is_cover' => true],
            ['url' => '/images/legenda2.jpg', 'is_cover' => false],
            ['url' => '/images/legenda3.jpg', 'is_cover' => false],
            ['url' => '/images/legenda4.jpg', 'is_cover' => false],
        ]);

        // Объявление 5 (Сочи - Квартира у моря)
        $listing5 = Listing::create([
            'user_id' => 1,
            'title' => 'Квартира у моря в Олимпийском парке',
            'description' => 'Комфортабельная квартира в Адлере, в пешей доступности от Олимпийского парка и пляжа. Идеально для отдыха на Черноморском побережье.',
            'price_per_night' => 6800,
            'address' => 'Адлер, ул. Морской Бульвар, д. 25, кв. 12',
            'city' => 'Сочи',
            'count_rooms' => 2,
            'is_active' => true,
            'created_at' => now()->subDays(12),
            'updated_at' => now()->subDays(5),
        ]);
        $listing5->images()->createMany([
            ['url' => '/images/lig1.jpg', 'is_cover' => true],
            ['url' => '/images/lig2.jpg', 'is_cover' => false],
            ['url' => '/images/lig3.jpg', 'is_cover' => false],
            ['url' => '/images/lig4.jpg', 'is_cover' => false],
        ]);

        // Объявление 6 (Екатеринбург - Апартаменты с лофтовым дизайном)
        $listing6 = Listing::create([
            'user_id' => 1,
            'title' => 'Стильные лофт-апартаменты в центре Екатеринбурга',
            'description' => 'Уникальный дизайн, высокие потолки, панорамные окна. Расположены в историческом здании после реконструкции. Вся инфраструктура в шаговой доступности.',
            'price_per_night' => 5500,
            'address' => 'ул. Ленина, д. 30, к. 4',
            'city' => 'Екатеринбург',
            'count_rooms' => 1,
            'is_active' => true,
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(4),
        ]);
        $listing6->images()->createMany([
            ['url' => '/images/nik1.jpg', 'is_cover' => true],
            ['url' => '/images/nik2.jpg', 'is_cover' => false],
            ['url' => '/images/nik3.jpg', 'is_cover' => false],
            ['url' => '/images/nik4.jpg', 'is_cover' => false],
        ]);

        // Объявление 7 (Новосибирск - Уютная квартира у метро)
        $listing7 = Listing::create([
            'user_id' => 1,
            'title' => 'Уютная двухкомнатная квартира у метро',
            'description' => 'Расположена в тихом районе, но с отличной транспортной доступностью. В квартире сделан свежий ремонт, есть вся бытовая техника. Идеально для командировок.',
            'price_per_night' => 3800,
            'address' => 'Красный проспект, д. 70, кв. 45',
            'city' => 'Новосибирск',
            'count_rooms' => 2,
            'is_active' => true,
            'created_at' => now()->subDays(9),
            'updated_at' => now()->subDays(3),
        ]);
        $listing7->images()->createMany([
            ['url' => '/images/olhon1.jpg', 'is_cover' => true],
            ['url' => '/images/olhon2.jpg', 'is_cover' => false],
            ['url' => '/images/olhon3.jpg', 'is_cover' => false],
            ['url' => '/images/olhon4.jpg', 'is_cover' => false],
        ]);

        // Объявление 8 (Краснодар - Дом с бассейном)
        $listing8 = Listing::create([
            'user_id' => 1,
            'title' => 'Просторный дом с бассейном в Краснодаре',
            'description' => 'Отличный вариант для летнего отдыха большой компанией. Большая зеленая территория, открытый бассейн, зона барбекю. Расположен в коттеджном поселке.',
            'price_per_night' => 15000,
            'address' => 'ул. Вишневая, д. 3, пос. Северный',
            'city' => 'Краснодар',
            'count_rooms' => 4,
            'is_active' => true,
            'created_at' => now()->subDays(7),
            'updated_at' => now()->subDays(2),
        ]);
        $listing8->images()->createMany([
            ['url' => '/images/priboy1.jpg', 'is_cover' => true],
            ['url' => '/images/priboy2.jpg', 'is_cover' => false],
            ['url' => '/images/priboy3.jpg', 'is_cover' => false],
            ['url' => '/images/priboy4.jpg', 'is_cover' => false],
        ]);

        // Объявление 9 (Владивосток - Квартира с видом на бухту)
        $listing9 = Listing::create([
            'user_id' => 1,
            'title' => 'Квартира с панорамным видом на Золотой Рог',
            'description' => 'Уникальное предложение во Владивостоке. Отличный вид на бухту Золотой Рог и мосты. Современный ремонт, вся необходимая бытовая техника. Рядом центр города.',
            'price_per_night' => 7500,
            'address' => 'ул. Светланская, д. 100, кв. 21',
            'city' => 'Владивосток',
            'count_rooms' => 3,
            'is_active' => true,
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(1),
        ]);
        $listing9->images()->createMany([
            ['url' => '/images/taiga1.jpg', 'is_cover' => true],
            ['url' => '/images/taiga2.jpg', 'is_cover' => false],
            ['url' => '/images/taiga3.jpg', 'is_cover' => false],
            ['url' => '/images/taiga4.jpg', 'is_cover' => false],
        ]);

        // Объявление 10 (Нижний Новгород - Коттедж на берегу Волги)
        $listing10 = Listing::create([
            'user_id' => 1,
            'title' => 'Уютный коттедж на берегу Волги',
            'description' => 'Прекрасное место для уединенного отдыха или рыбалки. Расположен в живописном месте в пригороде Нижнего Новгорода. Имеется собственный причал.',
            'price_per_night' => 10000,
            'address' => 'Нижегородская область, пос. Прибрежный, ул. Речная, д. 5',
            'city' => 'Нижний Новгород',
            'count_rooms' => 4,
            'is_active' => true,
            'created_at' => now()->subDays(3),
            'updated_at' => now(),
        ]);
        $listing10->images()->createMany([
            ['url' => '/images/bigimage.png', 'is_cover' => true],
            ['url' => '/images/image1.png', 'is_cover' => false],
            ['url' => '/images/image2.png', 'is_cover' => false],
            ['url' => '/images/image3.png', 'is_cover' => false],
        ]);
    }
}