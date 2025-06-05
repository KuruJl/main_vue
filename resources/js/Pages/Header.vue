<template>
  <nav class="navigation-bar">
    <div class="nav-menu">
      <h1 class="logo">Sweet<br />Home</h1>
      <a href="/" class="nav-link">Главная</a>
      <a href="/search" class="nav-link">Поиск</a>
      <a href="/profile" class="nav-link">Профиль</a>

    </div>
    <div class="auth-buttons">
      <template v-if="!user">
        <a href="/login" class="auth-button">Вход</a>
        <a href="/register" class="auth-button">Регистрация</a>
      </template>
      <template v-else>
        <Link href="/logout" method="post" as="button" class="auth-button">Выйти</Link>
      </template>
    </div>
  </nav>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>
<script>
  export default {
    name: 'AppHeader'
  }
</script>
<style scoped>
/* Ваши существующие стили остаются без изменений */
.navigation-bar {
  font-family: 'Rubik';
  margin: 20px auto;
  max-width: 1120px;
  width: 80%;
  border-radius: 15px;
  background-color: rgba(255, 255, 255, 1);
  border: 1px solid rgba(0, 0, 0, 1);
  display: flex;
  padding: 10px 22px;
  align-items: stretch;
  gap: 20px;
  flex-wrap: wrap;
  justify-content: space-between;
}

.nav-menu {
  display: flex;
  margin: auto 0;
  align-items: stretch;
  gap: 30px;
}

.logo {
  color: rgba(172, 224, 0, 1);
  font-weight: 800;
  line-height: 13px;
  margin: 0;
}

.nav-link {
  text-decoration: none;
  color: inherit;
}

.auth-buttons {
  display: flex;
  align-items: stretch;
  gap: 25px;
}

.auth-button {
  border-radius: 15px;
  background-color: rgba(172, 224, 0, 1);
  padding: 16px 32px;
  border: none;
  font-family: inherit;
  font-size: inherit;
  font-weight: inherit;
  color: inherit;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
}

.auth-button:last-child {
  padding: 16px 27px;
}

@media (max-width: 991px) {
  .navigation-bar {
    padding: 10px 20px;
    white-space: initial;
  }

  .nav-menu {
    white-space: initial;
  }

  .auth-buttons {
    white-space: initial;
  }

  .auth-button {
    padding: 16px 20px;
  }
}
</style>