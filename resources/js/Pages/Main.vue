<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

// Определяем пропсы
const props = defineProps({
  backgroundImage: {
    type: String,
    default: '/images/main-bg.jpg' // Путь к изображению по умолчанию
  },
    // ДОБАВЬТЕ ЭТОТ ПРОПС
    filterOptions: {
        type: Object,
        default: () => ({
            cities: [],
            minPriceOverall: 0,
            maxPriceOverall: 0,
            minRoomsOverall: 0,
            maxRoomsOverall: 0,
        })
    }
});

// Вычисляемое свойство для стиля фона
const backgroundStyle = computed(() => {
  return {
    backgroundImage: `url(${props.backgroundImage})`,
    backgroundSize: 'cover',
    backgroundPosition: 'center',
    backgroundRepeat: 'no-repeat'
  };
});

// Данные формы поиска
const form = useForm({
  city: '',
  min_price: null,
  max_price: null,
  count_rooms: null,
  date_from: null,
  date_to: null,
});

// Добавьте этот лог для отладки
console.log('Main.vue received filterOptions:', props.filterOptions);


// Функция для применения фильтров
const applyFilters = () => {
  form.get('/search', {
    preserveState: true,
    preserveScroll: true,
    replace: true,
    onSuccess: () => {
      console.log('Фильтры успешно применены! Переход на страницу поиска.');
    },
    onError: (errors) => {
      console.error('Ошибка при применении фильтров:', errors);
      alert('Произошла ошибка при поиске: ' + (errors.message || JSON.stringify(errors)));
    }
  });
};
</script>

<template>
    <section class="main-section" :style="backgroundStyle">
        <div class="content-container">
            <div class="text-content">
                <h1 class="main-title">Дом, милый дом</h1>
                <p class="main-subtitle">
                    Найдите идеальную квартиру, дом или апартаменты на сутки или длительный срок.
                    Большой выбор, безопасные сделки, поддержка 24/7. Бронируйте легко и комфортно!
                </p>
            </div>

            <div class="search-container">
                <div class="search-group">
                    <div class="search-field">
                        <label class="search-label">локация</label>
                        <select v-model="form.city" class="search-input location-select">
              <option value="">Все города</option>
                            <option v-for="city in props.filterOptions.cities" :key="city" :value="city">
                {{ city }}
                            </option>
                     </select>
                    </div>
                    <div class="divider"></div>

                    <div class="search-field">
                        <label class="search-label">цена от</label>
                        <input type="number" v-model.number="form.min_price" class="search-input" placeholder="от">
                    </div>
                    <div class="search-field">
                        <label class="search-label">цена до</label>
                        <input type="number" v-model.number="form.max_price" class="search-input" placeholder="до">
                    </div>
                    <div class="divider"></div>

                    <div class="search-field">
                        <label class="search-label">кол-во комнат</label>
                        <input type="number" v-model.number="form.count_rooms" class="search-input" placeholder="Любое" min="1">
                    </div>
                    <div class="divider"></div>
                </div>

                <div class="search-actions">
                    <div class="search-field date-field-container">
                        <label class="search-label">дата от</label>
                        <input type="date" v-model="form.date_from" class="search-input date-input-field" placeholder="Дата от">
                    </div>
                     <div class="search-field date-field-container">
                        <label class="search-label">дата до</label>
                        <input type="date" v-model="form.date_to" class="search-input date-input-field" placeholder="Дата до">
                    </div>
                    <button @click="applyFilters" class="search-button">Поиск</button>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Ваши существующие стили */
.main-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    z-index: 1;
}

.main-section {
    font-family: 'Rubik';
    width: 1120px;
    height: 593px;
    margin: 0 auto;
    border-radius: 15px;
    position: relative;
    overflow: hidden;
}

.content-container {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 0 60px;
    color: white;
}

.background-image {
    position: absolute;
    width: 100%;
    height: 100%;
    background-image: url('https://via.placeholder.com/1120x593'); /* Это заглушка, используйте свой путь */
    background-size: cover;
    background-position: center;
    z-index: 1;
}

.text-content {
    max-width: 700px;
    margin-bottom: 50px;
}

.main-title {
    font-size: 64px;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

.main-subtitle {
    font-size: 20px;
    line-height: 1.5;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

/* **СТИЛИ ДЛЯ ПОИСКА** */
.search-container {
    width: 860px; /* Увеличим ширину, чтобы вместить все поля */
    position: relative;
    border-radius: 10px;
    background-color: #fff;
    display: flex;
    margin-top: 83px;
    margin-left: 12px;
    padding: 8px 15px;
    align-items: center; /* Центрируем элементы по вертикали */
    gap: 14px;
    color: rgba(48, 48, 48, 1);
    flex-wrap: wrap; /* Разрешаем перенос на новую строку */
}

.search-group {
    display: flex;
    align-items: center; /* Выравнивание по центру */
    gap: 6px;
    font-size: 12px;
    font-weight: 400;
    flex-grow: 1; /* Позволяем группе занимать доступное пространство */
}

.search-field {
    display: flex;
    flex-direction: column;
    flex-shrink: 0; /* Чтобы поля не сжимались слишком сильно */
}

.search-label {
    align-self: flex-start; /* Выравнивание лейбла по левому краю */
    font-size: 10px; /* Уменьшим размер лейбла */
    color: #888; /* Более светлый цвет для лейбла */
    margin-bottom: 2px; /* Небольшой отступ от инпута */
}

/* Стиль применен к input */
.search-input {
    border-radius: 5px;
    background-color: rgba(217, 217, 217, 1);
    width: 100px; /* Сделаем инпуты немного шире */
    height: 35px;
    border: none;
    padding: 0 10px;
    font-size: 14px;
    box-sizing: border-box;
    color: #333;
}

.location-select {
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 15px;
    padding-right: 30px;
    min-width: 120px; /* Минимальная ширина для селекта */
}

.divider {
    border: 1px solid rgba(51, 51, 51, 1);
    width: 1px;
    height: 45px; /* Уменьшим высоту разделителя */
    align-self: center; /* Центрируем разделитель */
}

.search-actions {
    display: flex;
    align-items: center;
    gap: 15px; /* Уменьшим зазор между элементами */
    color: #000;
    flex-grow: 1; /* Позволяем действиям занимать доступное пространство */
    justify-content: flex-end; /* Выравниваем элементы справа */
}

.date-field-container { /* Новый класс для контейнера даты */
    display: flex;
    flex-direction: column;
}

.date-input-field { /* Новый класс для инпута даты */
    width: 120px; /* Ширина для полей даты */
    cursor: pointer;
}

.search-button {
    border-radius: 10px;
    background-color: rgba(172, 224, 0, 1);
    padding: 12px 20px; /* Уменьшим паддинг кнопки */
    font-size: 14px; /* Уменьшим шрифт кнопки */
    font-weight: 600; /* Сделаем шрифт жирнее */
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
    flex-shrink: 0;
}

.search-button:hover {
    background-color: #8bb900;
}

@media (max-width: 991px) {
    .search-container {
        width: 100%; /* Занимаем всю ширину на маленьких экранах */
        padding: 10px;
        flex-direction: column; /* Элементы в столбец */
        align-items: stretch; /* Растягиваем элементы */
        margin-left: 0;
    }

    .search-group, .search-actions {
        flex-direction: column; /* Элементы в столбец */
        align-items: stretch;
        width: 100%;
        gap: 10px;
    }

    .search-field {
        width: 100%; /* Занимаем всю ширину */
    }

    .search-input, .location-select, .date-input-field {
        width: 100%; /* Растягиваем на всю ширину контейнера */
    }

    .divider {
        display: none; /* Скрываем разделители на мобильных */
    }

    .search-button {
        width: 100%; /* Кнопка на всю ширину */
        padding: 15px;
    }

    .main-title {
        font-size: 48px;
    }

    .main-subtitle {
        font-size: 18px;
    }
}
</style>