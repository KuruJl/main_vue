<script setup>
import AppHeader from './Header.vue'; // Предполагаем, что AppHeader - это Header.vue
import AppMain from './Main.vue';     // Предполагаем, что AppMain - это Main.vue
import AppFooter from './Footer.vue'; // Предполагаем, что AppFooter - это Footer.vue

// Этот пропс Home.vue получает от Laravel через Inertia
const props = defineProps({
 filterOptions: {
  type: Object,
  default: () => ({
   cities: [],
   minPriceOverall: 0,
   maxPriceOverall: 0,
   minRoomsOverall: 0,
   maxRoomsOverall: 0,
  })
 }
});

// Мы просто делаем пропс доступным для передачи вниз
const filterOptions = props.filterOptions;

console.log('Home.vue received filterOptions:', filterOptions); // Проверьте этот лог еще раз
</script>


  <script>

  export default {
    name: 'Home' // Можно оставить, если хотите, но для Inertia/Vue3 это обычно не обязательно.
  }
  </script>

  <template>
      <div class="home-page">
          <AppHeader />
          <AppMain backgroundImage="/images/image1.png" :filterOptions="props.filterOptions" />
          <AppFooter />
      </div>
  </template>

  <style scoped>

  </style>