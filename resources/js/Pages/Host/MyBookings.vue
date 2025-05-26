<script setup>
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    pendingBookings: Array,
    confirmedBookings: Array,
    success: String, // Для flash-сообщений Laravel
    error: String,   // Для flash-сообщений Laravel
});

const confirmBooking = (bookingId) => {
    if (confirm('Вы уверены, что хотите подтвердить это бронирование?')) {
        // --- ИЗМЕНЕНИЕ ЗДЕСЬ: ВРУЧНУЮ ФОРМИРУЕМ URL ---
        router.put(`/my-bookings/${bookingId}/confirm`, {}, {
            onSuccess: () => {
                console.log('Booking confirmed successfully!');
            },
            onError: (errors) => {
                console.error('Error confirming booking:', errors);
                alert('Ошибка при подтверждении бронирования: ' + (errors.message || JSON.stringify(errors) || 'Неизвестная ошибка'));
            }
        });
    }
};

const declineBooking = (bookingId) => {
    if (confirm('Вы уверены, что хотите отклонить это бронирование?')) {
        // --- ИЗМЕНЕНИЕ ЗДЕСЬ: ВРУЧНУЮ ФОРМИРУЕМ URL ---
        router.put(`/my-bookings/${bookingId}/decline`, {}, {
            onSuccess: () => {
                console.log('Booking declined successfully!');
            },
            onError: (errors) => {
                console.error('Error declining booking:', errors);
                alert('Ошибка при отклонении бронирования: ' + (errors.message || JSON.stringify(errors) || 'Неизвестная ошибка'));
            }
        });
    }
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('ru-RU', options);
};
</script>

<template>

    <div class="my-bookings-container">
        <h1>Мои Бронирования</h1>

        <div v-if="props.success" class="alert alert-success">{{ props.success }}</div>
        <div v-if="props.error" class="alert alert-danger">{{ props.error }}</div>

        <section class="booking-section">
            <h2>Ожидающие подтверждения ({{ pendingBookings.length }})</h2>
            <div v-if="pendingBookings.length > 0" class="booking-list">
                <div v-for="booking in pendingBookings" :key="booking.id" class="booking-item pending">
                    <p><strong>Объявление:</strong> {{ booking.listing.title }}</p>
                    <p><strong>Гость:</strong> {{ booking.user.name }} ({{ booking.user.email }})</p>
                    <p><strong>Даты:</strong> {{ formatDate(booking.start_date) }} - {{ formatDate(booking.end_date) }}</p>
                    <p><strong>Ночей:</strong> {{ booking.number_of_nights || 'N/A' }}</p>
                    <p><strong>Итого:</strong> {{ booking.total_price }} ₽</p>
                    <p><strong>Статус:</strong> {{ booking.status }}</p>
                    <div class="booking-actions">
                        <button @click="confirmBooking(booking.id)" class="btn confirm-btn">Подтвердить</button>
                        <button @click="declineBooking(booking.id)" class="btn decline-btn">Отклонить</button>
                    </div>
                </div>
            </div>
            <p v-else class="no-bookings">Нет бронирований, ожидающих подтверждения.</p>
        </section>

        <section class="booking-section">
            <h2>Подтвержденные бронирования ({{ confirmedBookings.length }})</h2>
            <div v-if="confirmedBookings.length > 0" class="booking-list">
                <div v-for="booking in confirmedBookings" :key="booking.id" class="booking-item confirmed">
                    <p><strong>Объявление:</strong> {{ booking.listing.title }}</p>
                    <p><strong>Гость:</strong> {{ booking.user.name }} ({{ booking.user.email }})</p>
                    <p><strong>Даты:</strong> {{ formatDate(booking.start_date) }} - {{ formatDate(booking.end_date) }}</p>
                    <p><strong>Ночей:</strong> {{ booking.number_of_nights || 'N/A' }}</p>
                    <p><strong>Итого:</strong> {{ booking.total_price }} ₽</p>
                    <p><strong>Статус:</strong> {{ booking.status }}</p>
                </div>
            </div>
            <p v-else class="no-bookings">Нет подтвержденных бронирований.</p>
        </section>
    </div>
</template>

<style scoped>
.my-bookings-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    font-family: 'Rubik', sans-serif;
}

h1 {
    font-size: 2.5em;
    color: #333;
    margin-bottom: 30px;
    text-align: center;
    font-weight: 700;
}

.alert {
    padding: 10px 20px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-weight: 500;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.booking-section {
    margin-bottom: 40px;
}

.booking-section h2 {
    font-size: 1.8em;
    color: #555;
    margin-bottom: 20px;
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
    font-weight: 600;
}

.booking-list {
    display: grid;
    gap: 20px;
}

.booking-item {
    background-color: #f9f9f9;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease;
}

.booking-item:hover {
    transform: translateY(-3px);
}

.booking-item p {
    margin: 5px 0;
    color: #444;
    font-size: 0.95em;
}

.booking-item strong {
    color: #000;
}

.booking-item.pending {
    border-left: 5px solid #ffc107; /* Оранжевый для pending */
}

.booking-item.confirmed {
    border-left: 5px solid #28a745; /* Зеленый для confirmed */
}

.no-bookings {
    text-align: center;
    color: #777;
    font-style: italic;
    padding: 20px;
    border: 1px dashed #e0e0e0;
    border-radius: 8px;
    background-color: #fafafa;
}

.booking-actions {
    margin-top: 15px;
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 18px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9em;
    font-weight: 600;
    transition: background-color 0.3s ease;
    border: none;
}

.confirm-btn {
    background-color: #28a745;
    color: white;
}

.confirm-btn:hover {
    background-color: #218838;
}

.decline-btn {
    background-color: #dc3545;
    color: white;
}

.decline-btn:hover {
    background-color: #c82333;
}
</style>