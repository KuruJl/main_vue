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
      </div>
  </div>
</template>

<script setup>
import { usePage, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const editMode = ref(false);

// Форма для редактирования общих данных
const form = useForm({
  name: user.value ? user.value.name : '',
  email: user.value ? user.value.email : '',
  phone: user.value ? user.value.phone || '' : '',
});

// Отдельная форма для изменения пароля
const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

watch(user, (newUser) => {
  if (newUser) {
      form.name = newUser.name;
      form.email = newUser.email;
      form.phone = newUser.phone || '';
  }
}, { immediate: true });

const enterEditMode = () => {
  editMode.value = true;
  form.name = user.value.name;
  form.email = user.value.email;
  form.phone = user.value.phone || '';
  passwordForm.reset(); // Сбрасываем поля пароля при входе в режим редактирования
  passwordForm.clearErrors(); // Очищаем ошибки формы пароля
};

const saveChanges = () => {
  form.patch('/profile', {
      onSuccess: () => {
          editMode.value = false; // Выходим из режима редактирования общих данных
          alert('Данные профиля успешно обновлены!'); // Временное оповещение
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
  passwordForm.reset(); // Сбрасываем поля пароля при отмене
  passwordForm.clearErrors(); // Очищаем ошибки формы пароля
};

// Метод для обновления пароля (отдельный)
const updatePassword = () => {
  passwordForm.patch('/profile/password', {
      onSuccess: () => {
          passwordForm.reset(); // Очищаем поля пароля после успешного изменения
          alert('Пароль успешно изменен!'); // Временное оповещение
      },
      onError: (errors) => {
          console.error('Ошибка при изменении пароля:', errors);
      },
      onFinish: () => {
          passwordForm.reset('current_password'); // Сбросить только текущий пароль на всякий случай
      }
  });
};
</script>

<style scoped>
/* Ваши существующие стили */

/* Дополнительные стили для отступов и заголовка */
.profile-minor-subtitle {
  font-size: 24px; /* Чуть меньше основного заголовка */
  font-weight: 600;
  margin: 0 0 15px 0;
  color: #000000;
}

.mt-6 {
  margin-top: 1.5rem; /* Отступ сверху для заголовка пароля */
}

/* Стили для кнопки "Изменить пароль", чтобы отличалась, если нужно */
.password-save-button {
  background-color: #007bff; /* Пример другого цвета */
}
.password-save-button:hover {
  background-color: #0056b3;
}
/* Остальные стили из вашего кода (убедитесь, что они применены) */
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
</style>