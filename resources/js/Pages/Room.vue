<template>
  <div class="room-page">
    <AppHeader />
    <AppRoom1 :listing="listing" :unavailableDates="unavailableDates" />

    <div v-if="$page.props.auth.user && listing.user_id === $page.props.auth.user.id" class="edit-listing-container">
      <Link :href="`/listings/${listing.id}/edit`" class="edit-listing-button">
        Редактировать объявление
      </Link>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import AppHeader from './Header.vue';
import AppFooter from './Footer.vue';
import AppRoom1 from './Room1.vue';
import { Link, usePage } from '@inertiajs/vue3'; // Импортируем Link и usePage

const props = defineProps({
  listing: Object,
  unavailableDates: Array,
  // Добавьте пропсы для flash сообщений
  success: String, // 'success' от redirect()->with('success', ...)
  errors: Object,  // 'errors' от redirect()->withErrors(...)
});

// Получаем доступ к глобальным пропсам Inertia, включая данные об аутентификации
const page = usePage();

// Передаем flash сообщения в AppRoom1
const successMessage = props.success || null;
const errorMessage = props.errors.message || null; // Если у вас есть errors.message

// Вы можете использовать этот console.log для отладки, чтобы убедиться, что данные пользователя доступны
console.log('Current user ID:', page.props.auth.user ? page.props.auth.user.id : 'Guest');
console.log('Listing user ID:', props.listing.user_id);
</script>

<style scoped>
/* Добавьте эти стили, чтобы кнопка выглядела хорошо */
.edit-listing-container {
    display: flex;
    justify-content: center; /* Центрируем кнопку */
    margin-top: 30px; /* Отступ сверху */
    margin-bottom: 50px; /* Отступ снизу */
}

.edit-listing-button {
    display: inline-block;
    padding: 12px 25px;
    background-color: #007bff; /* Красивый синий цвет */
    color: white;
    text-decoration: none;
    border-radius: 8px; /* Немного скругленные углы */
    font-size: 16px;
    font-weight: 600;
    transition: background-color 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.edit-listing-button:hover {
    background-color: #0056b3; /* Темнее при наведении */
    transform: translateY(-2px); /* Небольшой эффект поднятия */
}

/* Добавьте или убедитесь, что у вас есть базовые стили для Room.vue и его подкомпонентов */
.room-page {
    /* Например, margin-top для отступа от навигации */
    margin-top: 20px;
}
</style>