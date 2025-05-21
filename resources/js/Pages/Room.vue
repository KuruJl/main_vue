<template>
  <div>
    <AppHeader />
    <AppRoom1 :listing="listing" />
    <AppFooter />
  </div>
</template>

<script>
import AppRoom1 from '../Pages/Room1.vue'; // Убедись, что путь правильный
 import AppHeader from '../Pages/Header.vue'; // Раскомментируй, если используешь
 import AppFooter from '../Pages/Footer.vue'; // Раскомментируй, если используешь

export default {
  components: {
    AppHeader,
    AppRoom1,
    AppFooter,
    // Header,
    // Footer,
  },
  props: {
    listing: {
      type: Object,
      required: true // Оставляем true, чтобы Vue предупредил, если пропс не придет
    }
  },
  name: 'Room',
  // Добавляем хук mounted для отладки
  
}
</script>