<template>
  <div>
    <AppHeader /> 

    <CreateAd :user-id="currentUserId" />

    <AppFooter />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

// Импортируем компонент формы создания объявления
import CreateAd from '../Pages/Create_ad.vue'; 

// Если используешь Header/Footer, импортируй их здесь
 import AppHeader from '../Pages/Header.vue'; 
 import AppFooter from '../Pages/Footer.vue';

// Получаем доступ к глобальным пропсам Inertia, включая данные аутентификации
const page = usePage();

// Вычисляемое свойство для user_id
const currentUserId = computed(() => page.props.auth.user ? page.props.auth.user.id : null);
</script>

<style scoped>
/* Ваши стили для страницы Ad.vue, если они есть */
</style>