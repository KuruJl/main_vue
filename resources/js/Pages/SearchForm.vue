<template>
  <div class="search-form">
      <div class="search-field">
          <select v-model="form.city" class="search-input location-select">
              <option value="">Все города</option>
              <option v-for="city in filterOptions.cities" :key="city" :value="city">
                  {{ city }}
              </option>
          </select>
      </div>

      <div class="search-field">
          <input type="number" v-model.number="form.min_price" class="search-input" :placeholder="`Цена от (${filterOptions.minPriceOverall} ₽)`">
      </div>
       <div class="search-field">
          <input type="number" v-model.number="form.max_price" class="search-input" :placeholder="`Цена до (${filterOptions.maxPriceOverall} ₽)`">
      </div>

      <div class="search-field">
          <input type="number" v-model.number="form.count_rooms" class="search-input" :placeholder="`Комнат (${filterOptions.minRoomsOverall}-${filterOptions.maxRoomsOverall})`" min="1">
      </div>

      <div class="search-field">
          <input type="date" v-model="form.date_from" class="search-input date-input" placeholder="Дата от">
      </div>
      <div class="search-field">
          <input type="date" v-model="form.date_to" class="search-input date-input" placeholder="Дата до">
      </div>

      <button @click="applyFilters" class="search-button">Поиск</button>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { onMounted, watch } from 'vue';

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
  },
  initialFilters: {
      type: Object,
      default: () => ({
          city: '',
          min_price: null,
          max_price: null,
          count_rooms: null,
          date_from: null,
          date_to: null,
      })
  }
});

const form = useForm({
  city: props.initialFilters.city || '',
  min_price: props.initialFilters.min_price || null,
  max_price: props.initialFilters.max_price || null,
  count_rooms: props.initialFilters.count_rooms || null,
  date_from: props.initialFilters.date_from || null,
  date_to: props.initialFilters.date_to || null,
});

const applyFilters = () => {
  console.log('1. Функция applyFilters вызвана.'); // Добавляем лог
  console.log('2. Данные формы для отправки:', form.data()); // Добавляем лог

  let targetUrl = '';
  try {
      // Пытаемся использовать route()
      targetUrl = route('listings.index');
      console.log('3. Используется именованный маршрут:', targetUrl); // Добавляем лог
  } catch (e) {
      // Если route() не определен, значит Ziggy не работает, используем прямой путь
      console.error('Ошибка: функция route() не определена. Возможно, Ziggy не установлен или не настроен. Детали ошибки:', e); // Лог ошибки
      targetUrl = '/search'; // Используем прямой URL
      console.log('4. Используется прямой URL:', targetUrl); // Добавляем лог
  }

  form.get(targetUrl, {
      preserveState: true,
      preserveScroll: true,
      replace: true,
      onSuccess: () => {
          console.log('5. Фильтры успешно применены! Inertia обновила страницу.');
      },
      onError: (errors) => {
          console.error('6. Ошибка при применении фильтров (Inertia onError):', errors);
      },
      onFinish: () => {
          console.log('7. Запрос фильтрации завершен.');
      }
  });
};
</script>

<style scoped>
/* Ваши стили остаются без изменений */
.search-form {
  margin: 20px auto;
  width: 920px;
  height: 50px;
  display: flex;
  background-color: #fff;
  border: 1px solid #000;
  border-radius: 8px;
  overflow: hidden;
}

.search-field {
  flex: 1;
  position: relative;
  border-right: 1px solid #000000;
}

.search-field:last-child {
  border-right: none;
}

.search-input {
  width: 100%;
  height: 100%;
  padding: 0 15px;
  border: none;
  outline: none;
  font-size: 14px;
  box-sizing: border-box;
}

.location-select {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 10px center;
  background-size: 15px;
  padding-right: 30px;
}

.date-input {
  cursor: pointer;
}

.search-button {
  width: 120px;
  background-color: #9bcb00;
  color: white;
  border: none;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s;
  flex-shrink: 0;
}

.search-button:hover {
  background-color: #8bb900;
}
</style>