<template>
    <div class="home-page">
      <AppHeader />
    <AppProfile />
      <AppFooter />
    </div>
  </template>
  
  <script setup>
  import AppHeader from './Header.vue';
  import AppFooter from './Footer.vue';
  import AppProfile from './ProfileBlock.vue';
  </script>
  
  <script >
  export default {
    name: 'Profilee'
  }
  </script>
  
  <style scoped>
  
  </style>