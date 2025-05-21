<template>
  <div v-if="listing" class="hotel-block">
    <div class="hotel-images">
      <div class="main-image">
        <img :src="listing.images && listing.images.length > 0 ? listing.images[0].url : '/images/image2.png'" :alt="listing.title">
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

      <div class="booking-section">
        <button class="date-button">Выбрать дату</button>
      </div>
      <button class="book-button">Забронировать</button>

      <div class="price-section">
        <div class="price-box">{{ listing.price_per_night }} ₽</div>
        <span class="price-note">1 ночь, 1 номер</span>
      </div>
    </div>
  </div>
  <div v-else>
    <p>Загрузка объявления или объявление не найдено.</p>
  </div>
</template>

<script>
export default {
  name: 'AppRoom1',
  props: {
    listing: {
      type: Object,
      // Снова ставим required: true, если уверены, что listing всегда должен приходить
      // если он все еще undefined, то будет ошибка "missing required prop"
      required: true
    }
  }
}
</script>

<style scoped>
/* Ваши существующие стили остаются без изменений */
.hotel-block {
    font-family: 'Rubik';
    margin: 20px auto;
    width: 1120px;
    height: 468px;
    display: flex;
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    }

  .hotel-images {
    width: 533px;
    height: 100%;
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
  }

  .thumbnail {
    width: 124px;
    height: 58px;
    overflow: hidden;
    cursor: pointer;
  }

  .thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .hotel-info {
    flex: 1;
    padding: 25px;
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

  .booking-section {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .date-button {
    border: 1px solid #000;
    width: 360px;
    height: 45px;
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
  }

  .price-section {
    margin-top: 20px;
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .price-box {
    color: white;
    width: 111px;
    height: 50px;
    background-color: #9bcb00;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
  }

  .price-note {
    font-size: 15px;
    color: #000000;
  }

  .book-button {
    margin-top: 20px;
    flex-grow: 1;
    width: 360px;
    height: 45px;
    background-color: #9bcb00;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .book-button:hover {
    background-color: #9bcb00;
  }
</style>