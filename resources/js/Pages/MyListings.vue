<script setup>
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppHeader from '../Pages/Header.vue'; // Убедитесь, что путь правильный
import AppFooter from '../Pages/Footer.vue'; // Убедитесь, что путь правильный

const props = defineProps({
    listings: Array, // Массив объявлений текущего пользователя
});

const getStatusText = (isActive) => {
    return isActive ? 'Активно' : 'Неактивно (скрыто)';
};

const getStatusClass = (isActive) => {
    return isActive ? 'status-active' : 'status-inactive';
};
</script>

<template>
    <AppHeader />
    <div class="my-listings-container">
        <h1>Мои объявления</h1>

        <div v-if="listings.length === 0" class="no-listings-message">
            <p>У вас пока нет активных или неактивных объявлений.</p>
            <Link href="/listings/create" class="create-listing-link">
                Создать новое объявление
            </Link>
        </div>

        <div v-else class="listings-grid">
            <div v-for="listing in listings" :key="listing.id" class="listing-card">
                <div class="listing-image">
                    <img :src="listing.images[0] ? listing.images[0].url : '/images/placeholder.webp'" alt="Listing Image" />
                    <span :class="['status-badge', getStatusClass(listing.is_active)]">
                        {{ getStatusText(listing.is_active) }}
                    </span>
                </div>
                <div class="listing-details">
                    <h2 class="listing-title">{{ listing.title }}</h2>
                    <p class="listing-address">{{ listing.address }}, {{ listing.city }}</p>
                    <p class="listing-price">{{ listing.price_per_night }} ₽/ночь</p>
                    <p class="listing-rooms">{{ listing.count_rooms }} комнат</p>
                    <Link :href="`/listings/${listing.id}/edit`" class="edit-button">
                        Редактировать
                    </Link>
                </div>
            </div>
        </div>
    </div>
    <AppFooter />
</template>

<style scoped>
/* Ваши стили остаются без изменений */
.my-listings-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    font-family: 'Rubik', sans-serif;
}

h1 {
    font-size: 2.8em;
    color: #333;
    margin-bottom: 30px;
    text-align: center;
    font-weight: 700;
}

.no-listings-message {
    text-align: center;
    margin-top: 50px;
    font-size: 1.2em;
    color: #666;
}

.create-listing-link {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.create-listing-link:hover {
    background-color: #0056b3;
}

.listings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
}

.listing-card {
    background-color: #f8f8f8;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    display: flex;
    flex-direction: column;
}

.listing-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.listing-image {
    width: 100%;
    height: 180px;
    overflow: hidden;
    position: relative;
}

.listing-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.status-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 0.9em;
    font-weight: 600;
    color: white;
}

.status-active {
    background-color: #28a745; /* Зеленый */
}

.status-inactive {
    background-color: #ffc107; /* Желтый */
    color: #333;
}

.listing-details {
    padding: 15px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.listing-title {
    font-size: 1.5em;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

.listing-address,
.listing-price,
.listing-rooms {
    font-size: 1em;
    color: #555;
    margin-bottom: 5px;
}

.listing-price {
    font-weight: 700;
    color: #007bff;
    margin-top: 10px;
}

.edit-button {
    display: block;
    width: 100%;
    padding: 10px 15px;
    background-color: #6c757d; /* Серый */
    color: white;
    text-align: center;
    border-radius: 5px;
    text-decoration: none;
    margin-top: auto; /* Прижимает кнопку к низу карточки */
    transition: background-color 0.3s ease;
}

.edit-button:hover {
    background-color: #5a6268;
}
</style>