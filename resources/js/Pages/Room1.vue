<template>
  <div v-if="listing" class="hotel-block">
      <div class="hotel-images">
          <div class="main-image">
              <img :src="listing.images && listing.images.length > 0 ? listing.images[0].url : '/images/placeholder.png'" :alt="listing.title">
          </div>
          <div class="thumbnails">
              <div v-for="(image, index) in listing.images" :key="index" class="thumbnail">
                  <img :src="image.url" :alt="listing.title">
              </div>
          </div>
      </div>

      <div class="hotel-info">
          <h2 class="hotel-name">{{ listing.title }}</h2>

          <div class="hotel-description">
              <p>{{ listing.description }}</p>
          </div>

          <div class="availability-booking-block">
              <h3>Проверить доступность и забронировать</h3>

              <div class="datepicker-wrapper">
                  <VueDatePicker
                      v-model="dateRange"
                      range
                      :enable-time-picker="false"
                      :min-date="new Date()"
                      :disabled-dates="disabledDates"
                      placeholder="Выберите даты заезда и выезда"
                      @update:model-value="handleDateChange"
                      menu-z-index="1000"
                  ></VueDatePicker>
              </div>

              <div v-if="selectedDates && selectedDates.start && selectedDates.end" class="booking-summary">
                  <p>Выбрано: {{ formatDate(selectedDates.start) }} - {{ formatDate(selectedDates.end) }}</p>
                  <p>Ночей: {{ numberOfNights }}</p>
                  <h3>Итого к оплате: {{ totalPrice }} ₽</h3>
                  <button @click="proceedToBooking" class="book-now-button" :disabled="bookingForm.processing">
                      Забронировать
                  </button>
              </div>
              <div v-else class="booking-summary-placeholder">
                  <p>Пожалуйста, выберите даты заезда и выезда на календаре.</p>
              </div>

              <p v-if="successMessage" class="success-message">{{ successMessage }}</p>
              <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>

              <p v-if="bookingForm.errors.start_date" class="error-message">{{ bookingForm.errors.start_date }}</p>
              <p v-if="bookingForm.errors.end_date" class="error-message">{{ bookingForm.errors.end_date }}</p>
              <p v-if="bookingForm.errors.total_price" class="error-message">{{ bookingForm.errors.total_price }}</p>
              <p v-if="bookingForm.errors.listing_id" class="error-message">{{ bookingForm.errors.listing_id }}</p>

          </div>
      </div>
  </div>
  <div v-else>
      <p>Загрузка объявления или объявление не найдено.</p>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'; // <--- Добавлен onMounted для отладки
import { useForm, usePage,  } from '@inertiajs/vue3'; // <--- ВОТ ТАК ДОЛЖНА ВЫГЛЯДЕТЬ СТРОКА ИМПОРТА
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

// Пропсы, которые приходят от Room.vue
const props = defineProps({
  listing: { type: Object, required: true },
  unavailableDates: Array, // Занятые даты
  success: String,         // Flash-сообщение об успехе от Laravel
  errors: Object,          // Объект ошибок от Laravel (включая errors.message)
});

// === ЛОГИ ДЛЯ ОТЛАДКИ (КОНСОЛЬ) ===
// Логируем начальные пропсы при монтировании компонента
onMounted(() => {
  console.log('AppRoom1 mounted. Initial props:', props);
  console.log('Initial success prop:', props.success);
  console.log('Initial errors prop (message):', props.errors?.message);
});
// ==================================

// Состояние для Vue Datepicker
const dateRange = ref(null); // Модель для datepicker (массив [start, end])
const selectedDates = ref(null); // Выбранные даты в виде объектов Date
const numberOfNights = ref(0);
const totalPrice = ref(0);

// Flash сообщения, которые будут отображаться
const successMessage = ref(props.success || null);
const errorMessage = ref(props.errors?.message || null);

// Смотрим за изменениями flash-сообщений из пропсов и обновляем локальные refs
watch(() => props.success, (newValue) => {
  console.log('Watcher: props.success changed to:', newValue); // <--- Лог при изменении
  successMessage.value = newValue;
  if (newValue) { // Очищаем сообщение через несколько секунд
      setTimeout(() => successMessage.value = null, 5000);
  }
});

watch(() => props.errors?.message, (newValue) => {
  console.log('Watcher: props.errors?.message changed to:', newValue); // <--- Лог при изменении
  errorMessage.value = newValue;
  if (newValue) { // Очищаем сообщение через несколько секунд
      setTimeout(() => errorMessage.value = null, 5000);
  }
});

// Форма для отправки бронирования через Inertia.js
const bookingForm = useForm({
  listing_id: props.listing.id,
  start_date: null,
  end_date: null,
  total_price: null,
});

// Вспомогательная функция для форматирования дат
const formatDate = (date) => {
  if (!date) return '';
  const d = new Date(date);
  const year = d.getFullYear();
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
};

// Вычисляемые недоступные даты для datepicker
const disabledDates = computed(() => {
  const dates = [];
  if (props.unavailableDates) { // Проверяем, что unavailableDates существует
      props.unavailableDates.forEach(range => {
          let start = new Date(range.start);
          let end = new Date(range.end);
          // Добавляем все даты в диапазоне, включая конец, чтобы они были недоступны
          for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
              dates.push(new Date(d));
          }
      });
  }
  return dates;
});

// Обработка изменения дат в Datepicker
const handleDateChange = (modelData) => {
  // Очищаем сообщения при каждом изменении даты
  successMessage.value = null;
  errorMessage.value = null;
  bookingForm.clearErrors(); // Очищаем ошибки валидации формы

  if (modelData && modelData.length === 2 && modelData[0] instanceof Date && modelData[1] instanceof Date) {
      const startDate = modelData[0];
      const endDate = modelData[1];

      if (startDate && endDate && endDate > startDate) {
          selectedDates.value = { start: startDate, end: endDate };

          const diffTime = Math.abs(endDate.getTime() - startDate.getTime());
          const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
          numberOfNights.value = diffDays;

          totalPrice.value = (parseFloat(props.listing.price_per_night) * numberOfNights.value).toFixed(2);

          bookingForm.start_date = formatDate(startDate);
          bookingForm.end_date = formatDate(endDate);
          bookingForm.total_price = parseFloat(totalPrice.value); // Убедитесь, что это число
      } else {
          selectedDates.value = null;
          numberOfNights.value = 0;
          totalPrice.value = 0;
          bookingForm.reset(); // Сбрасываем форму, если даты некорректны
      }
  } else {
      selectedDates.value = null;
      numberOfNights.value = 0;
      totalPrice.value = 0;
      bookingForm.reset();
  }
};

// Метод для перехода к бронированию
const proceedToBooking = () => {
  // Очищаем сообщения перед новой отправкой
  successMessage.value = null;
  errorMessage.value = null;
  bookingForm.clearErrors();

  if (!selectedDates.value || !selectedDates.value.start || !selectedDates.value.end) {
      errorMessage.value = 'Пожалуйста, выберите даты заезда и выезда.';
      return;
  }

  if (!props.listing || !props.listing.id) {
      errorMessage.value = 'Ошибка: ID объявления не найден.';
      return;
  }

  bookingForm.listing_id = props.listing.id; // Убедимся, что listing_id установлен

  console.log('Attempting to book with form data:', bookingForm.data()); // <--- Лог отправляемых данных

  bookingForm.post('/bookings', { // Отправляем POST-запрос на /bookings
      onSuccess: () => {
          console.log('Booking successful! Inertia onSuccess callback triggered.'); // <--- Лог при успехе
          // Flash-сообщение об успехе будет отображено через props.success
          // Здесь можно сбросить форму и обновить даты на календаре
          dateRange.value = null;
          selectedDates.value = null;
          numberOfNights.value = 0;
          totalPrice.value = 0;
          bookingForm.reset(); // Сброс формы после успешной отправки

          Inertia.reload({ only: ['unavailableDates'] }); 
      },
      onError: (errors) => {
          console.error('Booking failed! Inertia onError callback triggered. Errors:', errors); // <--- Лог при ошибке
          // Ошибки из `withErrors` попадают в `props.errors.message`,
          // а ошибки валидации полей попадают в `bookingForm.errors`
          errorMessage.value = props.errors?.message || 'Произошла ошибка при бронировании.';
          // Ошибки валидации полей отобразятся автоматически через `bookingForm.errors.field_name` в шаблоне
      },
  });
};
</script>

<style scoped>
/* Ваши существующие стили */
.hotel-block {
  font-family: 'Rubik';
  margin: 20px auto;
  width: 1120px;
  height: auto;
  display: flex;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.hotel-images {
  width: 533px;
  height: auto;
  display: flex;
  flex-direction: column;
}

.main-image {
  border-radius: 5px;
  width: 100%;
  height: 400px;
  overflow: hidden;
}

.main-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.thumbnails {
  display: flex;
  gap: 10px;
  padding: 10px;
  background-color: #f5f5f5;
  flex-wrap: wrap;
}

.thumbnail {
  width: 124px;
  height: 58px;
  overflow: hidden;
  cursor: pointer;
  border-radius: 3px;
}

.thumbnail img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.hotel-info {
  flex: 1;
  padding: 25px;
  display: flex;
  flex-direction: column;
}

.hotel-name {
  font-size: 24px;
  font-weight: 800;
  margin: 0 0 15px 0;
  color: #000000;
}

.hotel-description {
  font-size: 15px;
  line-height: 1.5;
  text-align: justify;
  color: #000000;
  margin-bottom: 20px;
  flex-grow: 1;
}

/* ОБНОВЛЕННЫЕ СТИЛИ ДЛЯ БЛОКА КАЛЕНДАРЯ И БРОНИРОВАНИЯ */
.availability-booking-block {
  margin-top: 30px;
  padding: 25px;
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  background-color: #f9f9f9;
}

.availability-booking-block h3 {
  font-size: 20px;
  margin-bottom: 15px;
  color: #000;
  font-weight: 600;
}

.datepicker-wrapper {
  margin-bottom: 20px;
  width: 100%;
}

.booking-summary {
  margin-top: 15px;
  padding-top: 15px;
  border-top: 1px solid #eee;
}

.booking-summary p {
  font-size: 16px;
  margin-bottom: 8px;
  color: #333;
}

.booking-summary h3 {
  font-size: 24px;
  color: #9bcb00;
  margin-top: 10px;
  margin-bottom: 15px;
  font-weight: bold;
}

.booking-summary-placeholder {
  font-style: italic;
  color: #777;
  margin-top: 15px;
  font-size: 15px;
}

.book-now-button {
  background-color: #9bcb00;
  color: white;
  border: none;
  border-radius: 8px;
  padding: 12px 25px;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
  width: 100%;
  display: block;
  box-sizing: border-box;
}

.book-now-button:hover {
  background-color: #8bb900;
}

.book-now-button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}

.error-message {
  color: #d9534f;
  margin-top: 10px;
  font-size: 14px;
  font-weight: 500;
}

.success-message {
  color: #5cb85c;
  margin-top: 10px;
  font-size: 14px;
  font-weight: 500;
}
</style>