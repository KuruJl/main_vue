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
                  <span class="info-label">Пароль:</span>
                  <span class="info-value password-mask">• • • • • • • • • •</span>
                  </div>
          </div>

          <div class="profile-actions">
              <button v-if="!editMode" @click="enterEditMode" class="edit-button">Редактировать данные</button>
              <template v-else>
                  <button @click="saveChanges" :disabled="form.processing" class="save-button">Сохранить</button>
                  <button @click="cancelEdit" class="cancel-button">Отменить</button>
              </template>
          </div>
      </div>
  </div>
</template>

<script setup>
import { usePage, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue'; // Добавляем ref и watch

const page = usePage();
const user = computed(() => page.props.auth.user);

// Состояние для переключения между режимом просмотра и редактирования
const editMode = ref(false); // Изначально false, т.е. режим просмотра

// Форма для редактирования данных
const form = useForm({
  name: user.value ? user.value.name : '',
  email: user.value ? user.value.email : '',
  phone: user.value ? user.value.phone || '' : '', // Убедитесь, что 'phone' поле существует
});

// Watcher для обновления формы, если данные пользователя изменились (например, после обновления страницы)
watch(user, (newUser) => {
  if (newUser) {
      form.name = newUser.name;
      form.email = newUser.email;
      form.phone = newUser.phone || '';
  }
}, { immediate: true }); // immediate: true - чтобы выполнить watcher сразу при создании компонента

const enterEditMode = () => {
  editMode.value = true;
  // При входе в режим редактирования, заполняем форму текущими данными пользователя
  form.name = user.value.name;
  form.email = user.value.email;
  form.phone = user.value.phone || '';
};

const saveChanges = () => {
  // Отправляем PATCH запрос на сервер для обновления данных пользователя
  // Предполагаем, что маршрут для обновления профиля будет '/profile' с методом PATCH
  form.patch('/profile', { // <--- ОБРАТИТЕ ВНИМАНИЕ: '/profile' - это ПРЯМОЙ URL
      onSuccess: () => {
          editMode.value = false; // Выходим из режима редактирования после успешного сохранения
          // Inertia автоматически обновит props, и user.value будет содержать новые данные
      },
      onError: () => {
          // Если есть ошибки валидации, они будут отображены благодаря Inertia и form.errors
          console.error('Ошибка при сохранении профиля:', form.errors);
      }
  });
};

const cancelEdit = () => {
  editMode.value = false; // Выходим из режима редактирования
  form.reset(); // Сбрасываем изменения в форме
  form.clearErrors(); // Очищаем возможные ошибки валидации
};
</script>

<style scoped>
/* Ваши существующие стили */
/* Добавьте стили для полей ввода в режиме редактирования */
.edit-input {
  flex-grow: 1; /* Позволяет полю ввода занимать оставшееся пространство */
  padding: 8px 12px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 20px; /* Соответствует font-size info-value */
  margin-left: 10px; /* Небольшой отступ от label */
  box-sizing: border-box;
}

/* Стили для кнопок сохранения и отмены */
.profile-actions {
  display: flex;
  gap: 15px; /* Отступ между кнопками */
  margin-top: 20px; /* Отступ сверху */
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

/* Стили для сообщения об ошибке */
.error-message {
  color: red;
  font-size: 14px;
  margin-top: 5px;
  margin-left: 10px; /* Или выровняйте по-другому */
}

/* Остальные стили из вашего кода */
.profile-container {
  font-family: 'Rubik';
  margin: 20px auto;
  width: 1120px;
  height: 560px; /* Возможно, этот height придется убрать или сделать min-height, если контент будет динамически расти */
  background-color: #ffffff;
  border-radius: 15px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  padding: 40px;
  box-sizing: border-box;
}

.profile-title {
  margin: 20px auto;
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
  align-items: center; /* Центрируем по вертикали элементы в строке */
}

.info-label {
  font-size: 22px;
  color: #000000;
  width: 250px;
  font-weight: 500;
  flex-shrink: 0; /* Чтобы метка не сжималась */
}

.info-value {
  font-size: 22px;
  color: #000000;
  flex-grow: 1; /* Значение будет занимать оставшееся место */
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
</style>