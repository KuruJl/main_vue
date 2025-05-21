<template>
  <div class="home-page">
    <AppHeader />
    <AppMain backgroundImage="/images/image1.png" />
    <AppFooter />
  </div>
</template>

<script setup>
import AppHeader from './Header.vue';
import AppMain from './Main.vue';
import AppFooter from './Footer.vue';
</script>

<script >
export default {
  name: 'Home'
}
</script>

<style scoped>

</style>\