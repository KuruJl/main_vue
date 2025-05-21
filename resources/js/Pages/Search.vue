<template>
    <div class="home-page">
      <AppHeader />
      <AppSearchForm /> 
      <AppSearchField :listings="listings" />
      <AppFooter />
    </div>
  </template>

  <script setup>
  import AppHeader from './Header.vue';
  import AppSearchForm from './SearchForm.vue';
  import AppSearchField from './SearchField.vue';
  import AppFooter from './Footer.vue';
  </script>
  <script>
  
  export default {
    components: {
      AppSearchField
    },
    props: {
      listings: Array // Объявления будут приходить сюда из контроллера
    },
    // Мы больше не делаем fetch внутри этого компонента,
    // так как данные приходят через пропсы от Inertia
  };
  </script>
  
  
  <style scoped>
  
  </style>