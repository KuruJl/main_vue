<template>
  <div v-for="listing in listings" :key="listing.id" class="booking-block">
    <img :src="listing.images && listing.images.length > 0 ? listing.images[0].url : '/images/image2.png'" :alt="listing.title" class="booking-image">
    <div class="booking-content">
      <a :href="`/listings/${listing.id}`" class="booking-title">
    {{ listing.title }}
</a>
<p class="booking-description">{{ listing.count_rooms }} {{ roomDeclension(listing.count_rooms) }}</p>
      <p class="booking-description">{{ listing.address }}, {{ listing.city }}</p>
    </div>
    <div class="divider" ></div>
    <div class="booking-price">
      <h3 class="price">{{ listing.price_per_night }} ₽</h3>
      <p class="booking-description">за ночь</p>
      <a :href="`/listings/${listing.id}`">
      <button   class="booking-button">Забронировать</button>
    </a>
    </div>
  </div>
  <div v-if="listings.length === 0" class="no-listings-message-container"> <p class="no-listings-message">Помещений не найдено.</p> </div>
</template>

<script>
export default {
  name: 'AppSearchField',
  props: {
    listings: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    roomDeclension(count) {
      const cases = [2, 0, 1, 1, 1, 2];
      const words = ['комната', 'комнаты', 'комнат'];
      return words[(count % 100 > 4 && count % 100 < 20) ? 2 : cases[Math.min(count % 10, 5)]];
    },
  },
  // Удаляем fetchListings и mounted, так как данные приходят через пропсы
}
</script>
<style scoped>
.no-listings-message-container {
    /* Чтобы центрировать сам блок */
    width: 920px; /* Задаем ту же ширину, что и у booking-block */
    margin: 20px auto; /* Центрируем контейнер */
    text-align: center; /* Центрируем текст внутри контейнера */
    padding: 40px 20px; /* Добавим немного отступа сверху/снизу и по бокам */
    background-color: #f0f0f0; /* Легкий фон, чтобы выделить */
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.no-listings-message {
    font-family: 'Rubik'; /* Применяем ваш шрифт */
    font-size: 24px; /* Увеличим размер для наглядности */
    color: #555; /* Более мягкий цвет */
    margin: 0; /* Убираем стандартные отступы параграфа */
    font-weight: 500;
}

/* Ваши существующие стили остаются без изменений */
.booking-price {
  display: flex;
  flex-direction: column;
  align-items: flex-start; /* <--- ИЗМЕНИТЕ ЭТО ЗНАЧЕНИЕ (было flex-end, стало flex-start) */
  justify-content: center;
  flex-shrink: 0;
  width: 170px;
}
.divider {
  margin-left: 177px;
  border: 1px solid #AEAEAE;
  width: 1px;
  height: 120px;
}
.divider2 {
  margin-left: 208px;
  border: 1px solid #AEAEAE;
  width: 1px;
  height: 120px;
}
.price{
  font-weight: 700;
  font-size: 20px;
}
.booking-block {
  font-family: 'Rubik';
  margin: 20px auto;
  width: 920px;
  height: 150px;
  display: flex;
  align-items: center;
  background-color: #ffffff;
  border: 1px solid rgba(0, 0, 0, 1);
  border-radius: 10px;
  padding-left: 10px;
  box-sizing: border-box;

  justify-content: space-between;
  padding-right: 20px;
}

.booking-image {
  width: 223px;
  height: 135px;
  object-fit: cover;
  margin-right: 20px; /* Это отступ от картинки до следующего блока */
  border-radius: 10px;
  flex-shrink: 0; /* Важно: запрещаем изображению сжиматься */
}

.booking-content {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100%;
  padding: 15px 0;
  flex-grow: 1; /* Этому блоку разрешаем занимать все доступное пространство */
  flex-basis: 0; /* В сочетании с flex-grow: 1, позволяет ему правильно расширяться */
  min-width: 0; /* Иногда нужно для предотвращения переполнения очень длинным текстом в flex-grow элементах */
  /* Устанавливаем максимальную ширину, чтобы он не "задавил" правый блок */
  max-width: 400px; /* Подберите значение, чтобы заголовок не был слишком длинным и не перекрывал разделитель */
}

.divider {
  border: 1px solid #AEAEAE;
  width: 1px;
  height: 120px;
  flex-shrink: 0;
  margin-left: 20px; 
  margin-right: 10px; /* <--- ИЗМЕНИТЕ ЭТО ЗНАЧЕНИЕ (было 20px, стало 10px) */
  /* Можете попробовать 5px или даже 0px, чтобы найти идеальный отступ */
}
.booking-price {
  /* Убираем старый margin-left: 20px; */ /* УДАЛИТЕ */

  display: flex; /* Делаем этот блок flex-контейнером для его содержимого */
  flex-direction: column;
  align-items: flex-end; /* Выравнивает цену и кнопку по правому краю внутри блока */
  justify-content: center; /* Центрирует содержимое по вертикали */
  flex-shrink: 0; /* Важно: запрещаем этому блоку сжиматься */
  width: 170px; /* Установите фиксированную ширину, соответствующую кнопке или немного больше */
}

.price {
  font-weight: 700;
  font-size: 20px;
  /* margin-right: 10px; */ /* Уберите, если align-items: flex-end все выровняет */
  margin-bottom: 5px; /* Небольшой отступ от цены до "за ночь" */
}

.booking-description {
  font-size: 16px;
  font-weight: normal;
  margin: 0;
  margin-bottom: 10px;
}

.booking-button {
  /* margin-right: 10px; */ /* Уберите, если align-items: flex-end все выровняет */
  width: 170px;
  height: 40px;
  background-color: #9bcb00;
  color: white;
  border: none;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s;
}

.booking-button:hover {
  background-color: #8bb900;
}
</style>