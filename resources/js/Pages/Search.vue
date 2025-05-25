<template>
  <div class="home-page">
      <AppHeader />
      <AppSearchForm :filterOptions="filterOptions" :initialFilters="filters" />
      <AppSearchField :listings="listings" />
      <AppFooter />
  </div>
</template>

<script setup>
import AppHeader from './Header.vue';
import AppSearchForm from './SearchForm.vue';
import AppSearchField from './SearchField.vue';
import AppFooter from './Footer.vue';

// Используем defineProps для получения пропсов в setup-скрипте
const props = defineProps({
  listings: Array,
  filters: Object, // Текущие примененные фильтры
  filterOptions: Object // Опции для выпадающих списков (города, мин/макс цены и т.д.)
});
</script>
  
  
  <style scoped>
  
  </style>