<template>
  <div class="profile-container">
      <h1 class="profile-title">ПРОФИЛЬ</h1>

      <div class="profile-content">
          <h2 class="profile-subtitle">Данные о пользователе</h2>

          <div class="profile-info">
              <div class="info-row">
                  <span class="info-label">ФИО:</span>
                  <span v-if="!editMode" class="info-value">{{ user ? user.name : 'Недоступно' }}</span>
                  <input v-else type="text" v-model="form.name" class="edit-input" />
              </div>

              <div class="info-row">
                  <span class="info-label">Электронная почта:</span>
                  <span v-if="!editMode" class="info-value">{{ user ? user.email : 'Недоступно' }}</span>
                  <input v-else type="email" v-model="form.email" class="edit-input" />
                  <span v-if="form.errors.email" class="error-message">{{ form.errors.email }}</span>
              </div>

              <div class="info-row">
                  <span class="info-label">Номер телефона:</span>
                  <span v-if="!editMode" class="info-value">{{ user ? user.phone || 'Не указан' : 'Недоступно' }}</span>
                  <input v-else type="text" v-model="form.phone" class="edit-input" />
                  <span v-if="form.errors.phone" class="error-message">{{ form.errors.phone }}</span>
              </div>

              <div class="info-row">
                  <span class="info-label">Логин:</span>
                  <span class="info-value password-mask">• • • • • • • • • •</span>
              </div>

              <template v-if="editMode">
                  <h3 class="profile-minor-subtitle mt-6">Изменить пароль</h3>
                  <div class="info-row">
                      <span class="info-label">Текущий пароль:</span>
                      <input
                          type="password"
                          id="current_password"
                          v-model="passwordForm.current_password"
                          class="edit-input"
                          autocomplete="current-password"
                      />
                      <span v-if="passwordForm.errors.current_password" class="error-message">{{ passwordForm.errors.current_password }}</span>
                  </div>

                  <div class="info-row">
                      <span class="info-label">Новый пароль:</span>
                      <input
                          type="password"
                          id="new_password"
                          v-model="passwordForm.password"
                          class="edit-input"
                          autocomplete="new-password"
                      />
                      <span v-if="passwordForm.errors.password" class="error-message">{{ passwordForm.errors.password }}</span>
                  </div>

                  <div class="info-row">
                      <span class="info-label">Повторите пароль:</span>
                      <input
                          type="password"
                          id="password_confirmation"
                          v-model="passwordForm.password_confirmation"
                          class="edit-input"
                          autocomplete="new-password"
                      />
                      <span v-if="passwordForm.errors.password_confirmation" class="error-message">{{ passwordForm.errors.password_confirmation }}</span>
                  </div>
              </template>
          </div>

          <div class="profile-actions">
              <button v-if="!editMode" @click="enterEditMode" class="edit-button">Редактировать данные</button>
              <template v-else>
                  <button @click="saveChanges" :disabled="form.processing" class="save-button">Сохранить данные</button>
                  <button @click="updatePassword" :disabled="passwordForm.processing" class="save-button password-save-button">
                      Изменить пароль
                  </button>
                  <button @click="cancelEdit" class="cancel-button">Отменить</button>
              </template>
          </div>

          <h2 class="profile-subtitle mt-8">Мои Бронирования</h2>
          <div class="bookings-list">
              <p v-if="bookings.length === 0" class="no-bookings-message">У вас пока нет активных бронирований.</p>
              <div v-for="booking in bookings" :key="booking.id" class="booking-item">
                  <div class="booking-header">
                      <h3>{{ booking.listing ? booking.listing.title : 'Объявление удалено' }}</h3>
                      <span :class="['booking-status', `status-${booking.status}`]">
                          {{ getStatusText(booking.status) }}
                      </span>
                  </div>
                  <p><strong>Даты:</strong> {{ formatDate(booking.start_date) }} - {{ formatDate(booking.end_date) }}</p>
                  <p><strong>Ночей:</strong> {{ booking.number_of_nights || 'Не указано' }}</p>
                  <p><strong>Итого к оплате:</strong> {{ booking.total_price }} ₽</p>
                  <p><strong>Дата бронирования:</strong> {{ formatDate(booking.created_at) }}</p>
              </div>
          </div>
          </div>
  </div>
</template>

<script setup>
import { usePage, useForm } from '@inertiajs/vue3';
import { computed, ref, watch, onMounted } from 'vue';

// Определяем пропсы, которые компонент будет принимать
const props = defineProps({
  // user: Object, // Эту строку закомментируем/удалим, если user берется из page.props.auth.user
  bookings: {
      type: Array,
      default: () => []
  },
  success: String,
  error: String,
  mustVerifyEmail: Boolean,
  status: String,
});

const page = usePage();
// *** ВОТ ЭТА СТРОКА ОПРЕДЕЛЯЕТ, КАК ПОЛУЧАЮТСЯ ДАННЫЕ ПОЛЬЗОВАТЕЛЯ ***
// Если ваш контроллер передает пользователя через page.props.auth.user (стандарт Breeze/Jetstream),
// то используем ее.
const user = computed(() => page.props.auth.user); 
// Если ваш контроллер передает пользователя как отдельный пропс 'user', 
// раскомментируйте 'user: Object' в defineProps и используйте:
// const user = computed(() => props.user); 
// Но для стандартной Inertia/Laravel Breeze настройки, первый вариант (page.props.auth.user) предпочтительнее.

const editMode = ref(false);

// Инициализируем форму с данными пользователя
const form = useForm({
  name: user.value ? user.value.name : '',
  email: user.value ? user.value.email : '',
  phone: user.value ? user.value.phone || '' : '',
});

// Форма для изменения пароля
const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

// Отслеживаем изменения в объекте user и обновляем форму
watch(user, (newUser) => {
  if (newUser) {
      form.name = newUser.name;
      form.email = newUser.email;
      form.phone = newUser.phone || '';
  }
}, { immediate: true });

// Хук жизненного цикла, выполняется после монтирования компонента
onMounted(() => {
  console.log('Profile component mounted. Bookings:', props.bookings);
  // Отображение flash-сообщений, если они есть
  if (props.success) {
      alert(props.success);
  }
  if (props.error) {
      alert(props.error);
  }
});


const enterEditMode = () => {
  editMode.value = true;
  // Обновляем форму данными пользователя при входе в режим редактирования
  form.name = user.value.name;
  form.email = user.value.email;
  form.phone = user.value.phone || '';
  passwordForm.reset();
  passwordForm.clearErrors();
};

const saveChanges = () => {
  form.patch('/profile', {
      onSuccess: () => {
          editMode.value = false;
      },
      onError: () => {
          console.error('Ошибка при сохранении профиля:', form.errors);
      }
  });
};

const cancelEdit = () => {
  editMode.value = false;
  form.reset();
  form.clearErrors();
  passwordForm.reset();
  passwordForm.clearErrors();
};

const updatePassword = () => {
  passwordForm.patch('/profile/password', {
      onSuccess: () => {
          passwordForm.reset();
      },
      onError: (errors) => {
          console.error('Ошибка при изменении пароля:', errors);
      },
      onFinish: () => {
          passwordForm.reset('current_password');
      }
  });
};

// Вспомогательная функция для форматирования даты
const formatDate = (dateString) => {
  if (!dateString) return 'Не указано';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('ru-RU', options);
};

// Вспомогательная функция для отображения статуса на русском
const getStatusText = (status) => {
  switch (status) {
      case 'pending':
          return 'В ожидании';
      case 'confirmed':
          return 'Подтверждено';
      case 'declined':
          return 'Отклонено';
      case 'cancelled':
          return 'Отменено';
      default:
          return status;
  }
};
</script>

<style scoped>
/* Ваши существующие стили */
.profile-minor-subtitle {
  font-size: 24px;
  font-weight: 600;
  margin: 0 0 15px 0;
  color: #000000;
}
.mt-6 {
  margin-top: 1.5rem;
}
.password-save-button {
  background-color: #007bff;
}
.password-save-button:hover {
  background-color: #0056b3;
}
.edit-input {
  flex-grow: 1;
  padding: 8px 12px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 20px;
  margin-left: 10px;
  box-sizing: border-box;
}
.profile-actions {
  display: flex;
  gap: 15px;
  margin-top: 20px;
}
.save-button,
.cancel-button {
  border-radius: 8px;
  padding: 12px 30px;
  font-size: 20px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s;
  border: none;
}
.save-button {
  background-color: #9bcb00;
  color: white;
}
.save-button:hover {
  background-color: #8bb900;
}
.save-button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}
.cancel-button {
  background-color: #e0e0e0;
  color: #333;
}
.cancel-button:hover {
  background-color: #d0d0d0;
}
.error-message {
  color: red;
  font-size: 14px;
  margin-top: 5px;
  margin-left: 10px;
}
.profile-container {
  font-family: 'Rubik';
  margin: 20px auto;
  width: 1120px;
  background-color: #ffffff;
  border-radius: 15px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  padding: 40px;
  box-sizing: border-box;
}
.profile-title {
  font-size: 64px;
  font-weight: bold;
  margin: 0 0 30px 0;
  color: #000000;
}
.profile-subtitle {
  font-size: 30px;
  font-weight: 600;
  margin: 0 0 25px 0;
  color: #000000;
}
.profile-info {
  margin-bottom: 40px;
}
.info-row {
  display: flex;
  margin-bottom: 20px;
  align-items: center;
}
.info-label {
  font-size: 22px;
  color: #000000;
  width: 250px;
  font-weight: 500;
  flex-shrink: 0;
}
.info-value {
  font-size: 22px;
  color: #000000;
  flex-grow: 1;
}
.password-mask {
  letter-spacing: 3px;
}
.edit-button {
  background-color: #9bcb00;
  color: white;
  border: none;
  border-radius: 8px;
  padding: 12px 30px;
  font-size: 20px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s;
}
.edit-button:hover {
  background-color: #ACE000;
}

/* НОВЫЕ СТИЛИ ДЛЯ БЛОКА БРОНИРОВАНИЙ */
.mt-8 {
  margin-top: 2rem;
}
.bookings-list {
  display: grid;
  gap: 20px;
  margin-top: 20px;
}
.booking-item {
  background-color: #f9f9f9;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s ease;
}
.booking-item:hover {
  transform: translateY(-3px);
}
.booking-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}
.booking-header h3 {
  font-size: 1.5em;
  color: #333;
  margin: 0;
  font-weight: 600;
}
.booking-item p {
  margin: 5px 0;
  color: #555;
  font-size: 0.95em;
}
.booking-item strong {
  color: #000;
}
.booking-status {
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 0.85em;
  font-weight: bold;
  color: white;
}
.status-pending {
  background-color: #ffc107;
}
.status-confirmed {
  background-color: #28a745;
}
.status-declined {
  background-color: #dc3545;
}
.status-cancelled {
  background-color: #6c757d;
}
.no-bookings-message {
  text-align: center;
  color: #777;
  font-style: italic;
  padding: 20px;
  border: 1px dashed #e0e0e0;
  border-radius: 8px;
  background-color: #fafafa;
}
</style>