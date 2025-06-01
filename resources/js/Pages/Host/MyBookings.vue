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
        router.put(route('host.bookings.confirm', { booking: bookingId }), {}, { // ИСПОЛЬЗУЕМ route()
            onSuccess: () => {
                console.log('Booking confirmed successfully!');
                // Перезагружаем страницу хоста, чтобы обновить списки
                router.reload({ only: ['pendingBookings', 'confirmedBookings', 'success', 'error'] });

                // ОПЦИОНАЛЬНО: Если вы хотите, чтобы профиль пользователя реактивно обновился,
                // без того, чтобы он перезагружал страницу, это сложнее и требует WebSockets (Laravel Echo).
                // Но для большинства случаев Inertia "smart reload" при следующем посещении страницы пользователя будет достаточно.
                // Если пользователь находится на странице своего профиля, когда хост подтвердил бронь,
                // и он затем обновляет страницу или переходит на нее, он увидит обновленный статус.
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
        router.put(route('host.bookings.decline', { booking: bookingId }), {}, { // ИСПОЛЬЗУЕМ route()
            onSuccess: () => {
                console.log('Booking declined successfully!');
                // Перезагружаем страницу хоста, чтобы обновить списки
                router.reload({ only: ['pendingBookings', 'confirmedBookings', 'success', 'error'] });
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
/* Ваши стили для MyBookings.vue */
.my-bookings-container {
    font-family: 'Rubik', sans-serif;
    max-width: 1000px;
    margin: 40px auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 2.8em;
    color: #333;
    margin-bottom: 30px;
    text-align: center;
    font-weight: 700;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-size: 1.1em;
    text-align: center;
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
    font-size: 2em;
    color: #000;
    margin-bottom: 25px;
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
    font-weight: 600;
}

.no-bookings {
    text-align: center;
    font-style: italic;
    color: #777;
    padding: 20px;
    background-color: #f0f0f0;
    border-radius: 8px;
    border: 1px dashed #ccc;
}

.booking-list {
    display: grid;
    gap: 25px;
}

.booking-item {
    background-color: #fefefe;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.booking-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.booking-item.pending {
    border-left: 5px solid #ffc107; /* Желтая полоса для ожидающих */
}

.booking-item.confirmed {
    border-left: 5px solid #28a745; /* Зеленая полоса для подтвержденных */
}

.booking-item p {
    margin: 8px 0;
    color: #555;
    font-size: 1.05em;
    line-height: 1.5;
}

.booking-item strong {
    color: #333;
    font-weight: 600;
}

.booking-item h3 {
    font-size: 1.8em;
    color: #000;
    margin-bottom: 15px;
    font-weight: 700;
    text-align: center;
}

.booking-actions {
    margin-top: 20px;
    display: flex;
    gap: 15px;
    justify-content: center;
}

.btn {
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    font-size: 1.1em;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
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