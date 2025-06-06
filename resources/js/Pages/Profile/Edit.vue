<script setup>
import { usePage, useForm, router } from '@inertiajs/vue3';
import { computed, ref, watch, onMounted } from 'vue';
// Убедитесь, что пути к Header/Footer верны, как мы обсуждали
import AppHeader from '../../Pages/Header.vue';
import AppFooter from '../../Pages/Footer.vue';

const props = defineProps({
    bookings: {
        type: Array,
        default: () => []
    },
    success: String,
    error: String,
    mustVerifyEmail: Boolean,
    status: String,
    // Если вы добавили 'userPhone' в контроллере
    // userPhone: {
    //     type: String,
    //     default: null
    // }
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const editMode = ref(false);

// Единая форма для всех полей профиля и пароля
const form = useForm({
    name: user.value ? user.value.name : '',
    email: user.value ? user.value.email : '',
    phone: user.value ? user.value.phone || '' : '', // Убедитесь, что phone обрабатывается как пустая строка, если null
    current_password: '', // Добавлено поле для текущего пароля
    password: '',         // Добавлено поле для нового пароля
    password_confirmation: '', // Добавлено поле для подтверждения нового пароля
});

watch(user, (newUser) => {
    if (newUser) {
        form.name = newUser.name;
        form.email = newUser.email;
        form.phone = newUser.phone || ''; // Устанавливаем в пустую строку, если null
    }
}, { immediate: true });

onMounted(() => {
    console.log('Profile component mounted.');
    console.log('Initial user phone:', user.value.phone); // Проверяем, что приходит
    // Автоматическое отображение flash-сообщений
    if (props.success) {
        alert(props.success);
    }
    if (props.error) {
        alert(props.error);
    }
});

const enterEditMode = () => {
    editMode.value = true;
    // Сбрасываем поля пароля при входе в режим редактирования
    form.current_password = '';
    form.password = '';
    form.password_confirmation = '';
    form.clearErrors(); // Очищаем ошибки старой формы
};

// Изменяем saveChanges для отправки всех данных
const saveChanges = () => {
    // Если поля пароля не заполнены, сбрасываем их перед отправкой,
    // чтобы они не отправлялись на сервер для валидации, если не нужны
    if (!form.current_password && !form.password && !form.password_confirmation) {
        form.current_password = '';
        form.password = '';
        form.password_confirmation = '';
    }

    form.patch('/profile', {
        onSuccess: () => {
            editMode.value = false;
            // Обновляем данные пользователя напрямую из page.props.auth.user
            // После успешной patch-запроса Inertia автоматически обновит page.props.auth.user
            // Однако, иногда watch на user может не сработать для глубоких изменений,
            // или вы хотите быть уверены, что form.phone тоже синхронизируется.

            // Проверяем, что page.props.auth.user обновлен после успешной перезагрузки Inertia.
            // Если вы использовали router.reload({ only: ['auth'] }), то page.props.auth.user
            // должен быть обновлен. Тогда достаточно просто использовать user.value.phone
            // для отображения.

            // Если проблема именно в том, что `user.value.phone` не реактивно обновляется
            // в `span`, можно попробовать принудительно обновить `form.phone` после reload:
            router.reload({
                only: ['auth', 'bookings', 'success', 'error'],
                onSuccess: () => {
                    // Убедитесь, что user.value.phone теперь имеет правильное значение
                    // после перезагрузки данных от сервера.
                    // Если user.value.phone не null, то он должен отображаться.
                    // Для уверенности, можно синхронизировать форму с новыми данными
                    form.name = user.value.name;
                    form.email = user.value.email;
                    form.phone = user.value.phone || ''; // <--- Важно! Обновляем form.phone
                    form.current_password = '';
                    form.password = '';
                    form.password_confirmation = '';
                }
            });
        },
        onError: (errors) => {
            console.error('Ошибка при сохранении профиля:', errors);
            // Ошибки будут автоматически привязаны к полям формы 'form.errors'
        },
        onFinish: () => {
            // Сбрасываем поля пароля в любом случае после попытки сохранения
            form.current_password = '';
            form.password = '';
            form.password_confirmation = '';
        }
    });
};

const cancelEdit = () => {
    editMode.value = false;
    form.reset(); // Сбросит форму до начальных значений (user.value.name, email, phone)
    form.clearErrors();
    // Очищаем поля пароля при отмене
    form.current_password = '';
    form.password = '';
    form.password_confirmation = '';
};

// Функция updatePassword больше не нужна, так как все в saveChanges

const formatDate = (dateString) => {
    if (!dateString) return 'Не указано';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('ru-RU', options);
};

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

<template>
    <AppHeader />
    <div class="profile-container">

        <h1 class="profile-title">ПРОФИЛЬ</h1>

        <div class="profile-content">
            <h2 class="profile-subtitle">Данные о пользователе</h2>

            <div class="profile-info">
                <div class="info-row">
                    <span class="info-label">ФИО:</span>
                    <span v-if="!editMode" class="info-value">{{ user ? user.name : 'Недоступно' }}</span>
                    <input v-else type="text" v-model="form.name" class="edit-input" />
                    <span v-if="form.errors.name" class="error-message">{{ form.errors.name }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Электронная почта:</span>
                    <span v-if="!editMode" class="info-value">{{ user ? user.email : 'Недоступно' }}</span>
                    <input v-else type="email" v-model="form.email" class="edit-input" />
                    <span v-if="form.errors.email" class="error-message">{{ form.errors.email }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Номер телефона:</span>
                    <span v-if="!editMode" class="info-value">{{ user ? (user.phone || 'Не указан') : 'Недоступно' }}</span>
                    <input v-else type="text" v-model="form.phone" class="edit-input" />
                    <span v-if="form.errors.phone" class="error-message">{{ form.errors.phone }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Пароль:</span>
                    <span class="info-value password-mask">• • • • • • • • • •</span>
                </div>

                <template v-if="editMode">
                    <h3 class="profile-minor-subtitle mt-6">Изменить пароль (необязательно)</h3>
                    <div class="info-row">
                        <span class="info-label">Текущий пароль:</span>
                        <input
                            type="password"
                            id="current_password"
                            v-model="form.current_password"
                            class="edit-input"
                            autocomplete="current-password"
                        />
                        <span v-if="form.errors.current_password" class="error-message">{{ form.errors.current_password }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Новый пароль:</span>
                        <input
                            type="password"
                            id="new_password"
                            v-model="form.password"
                            class="edit-input"
                            autocomplete="new-password"
                        />
                        <span v-if="form.errors.password" class="error-message">{{ form.errors.password }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Повторите пароль:</span>
                        <input
                            type="password"
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            class="edit-input"
                            autocomplete="new-password"
                        />
                        <span v-if="form.errors.password_confirmation" class="error-message">{{ form.errors.password_confirmation }}</span>
                    </div>
                </template>
            </div>

            <div class="profile-actions">
                <button v-if="!editMode" @click="enterEditMode" class="edit-button">Редактировать данные</button>
                <template v-else>
                    <button @click="saveChanges" :disabled="form.processing" class="save-button">
                        Сохранить изменения
                    </button>
                    <button @click="cancelEdit" class="cancel-button">Отменить</button>
                </template>
            </div>

            <h2 class="profile-subtitle mt-8">Мои Бронирования</h2>
            <div class="bookings-list">
                <p v-if="props.bookings.length === 0" class="no-bookings-message">У вас пока нет активных бронирований.</p>
                <div v-for="booking in props.bookings" :key="booking.id" class="booking-item">
                    <div class="booking-header">
                        <h3>{{ booking.listing ? booking.listing.title : 'Объявление удалено' }}</h3>
                        <span :class="['booking-status', `status-${booking.status}`]">
                            {{ getStatusText(booking.status) }}
                        </span>
                    </div>
                    <p><strong>Даты:</strong> {{ formatDate(booking.start_date) }} - {{ formatDate(booking.date_end) }}</p>
                    <p><strong>Ночей:</strong> {{ booking.number_of_nights || 'Не указано' }}</p>
                    <p><strong>Итого к оплате:</strong> {{ booking.total_price }} ₽</p>
                    <p><strong>Дата бронирования:</strong> {{ formatDate(booking.created_at) }}</p>
                </div>
            </div>
        </div>
    </div>
    <AppFooter />
</template>

<style scoped>
/* Ваши стили остаются без изменений */
.profile-minor-subtitle {
    font-size: 24px;
    font-weight: 600;
    margin: 0 0 15px 0;
    color: #000000;
}
.mt-6 {
    margin-top: 1.5rem;
}
/* Стили для password-save-button больше не нужны, так как кнопка одна */
/* .password-save-button {
    background-color: #007bff;
}
.password-save-button:hover {
    background-color: #0056b3;
} */
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