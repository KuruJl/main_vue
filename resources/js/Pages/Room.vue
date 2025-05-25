<template>
  <div class="room-page">
      <AppHeader />
      <AppRoom1 :listing="listing" :unavailableDates="unavailableDates" />
      <AppFooter />
  </div>
</template>

<script setup>
import AppHeader from './Header.vue';
import AppFooter from './Footer.vue';
import AppRoom1 from './Room1.vue';

const props = defineProps({
    listing: Object,
    unavailableDates: Array,
    // Добавьте пропсы для flash сообщений
    success: String, // 'success' от redirect()->with('success', ...)
    errors: Object,  // 'errors' от redirect()->withErrors(...)
});

// Передаем flash сообщения в AppRoom1
const successMessage = props.success || null;
const errorMessage = props.errors.message || null; // Если у вас есть errors.message

</script>
