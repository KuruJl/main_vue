<script>
export default {
  name: 'AppMain',
  props: {
    backgroundImage: {
      type: String,
      default: '/images/main-bg.jpg' // Путь к изображению по умолчанию
    }
  },
  computed: {
    backgroundStyle() {
      return {
        backgroundImage: `url(${this.backgroundImage})`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        backgroundRepeat: 'no-repeat'
      }
    }
  }
}
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
            <input type="text" class="search-input" placeholder="Введите локацию">
          </div>
          <div class="divider"></div>
          <div class="search-field">
            <label class="search-label">цена</label>
            <input type="text" class="search-input" placeholder="от и до">
          </div>
          <div class="divider"></div>
          <div class="search-field">
            <label class="search-label">кол-во комнат</label>
            <input type="text" class="search-input" placeholder="Любое">
          </div>
          <div class="divider"></div>
        </div>
        
          
        
        <div class="search-actions">
          <div class="date-input">дата</div>
          <button class="search-button">Поиск</button>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
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
  background-image: url('https://via.placeholder.com/1120x593');
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

.search-container {
  width: 570px;
  position: relative;
  border-radius: 10px;
  background-color: #fff;
  display: flex;
  margin-top: 83px;
  margin-left: 12px;
  padding: 8px 15px;
  align-items: stretch;
  gap: 14px;
  color: rgba(48, 48, 48, 1);
  flex-wrap: wrap;
}

.search-group {
  
  display: flex;
  align-items: stretch;
  gap: 6px;
  font-size: 12px;
  font-weight: 400;
}

.search-field {
  display: flex;
  flex-direction: column;
}

.search-label {
  align-self: start;
}

/* Стиль применен к input */
.search-input {
  border-radius: 5px;
  background-color: rgba(217, 217, 217, 1);
  margin-top: 6px;
  width: 90px;
  height: 35px;
  /* Добавлены стили для input */
  border: none;
  /* Убираем стандартную рамку input */
  padding: 0 10px;
  /* Добавляем внутренние отступы для текста */
  font-size: 14px;
  /* Подбираем размер шрифта */
  box-sizing: border-box;
  /* Включаем padding и border в размер элемента */
}

.divider {
  border: 1px solid rgba(51, 51, 51, 1);
  width: 1px;
  height: 55px;
}

.search-actions {
  align-self: start;
  display: flex;
  margin-top: 5px;
  align-items: stretch;
  gap: 25px;
  color: #000;
}

.date-input {
  border-radius: 5px;
  background-color: rgba(217, 217, 217, 1);
  align-self: start;
  margin-top: 15px;
  padding: 11px 32px;
  font-size: 12px;
  font-weight: 400;
}

.search-button {
  border-radius: 10px;
  background-color: rgba(172, 224, 0, 1);
  padding: 16px 27px;
  font-size: 16px;
  font-weight: 300;
  border: none;
  cursor: pointer;
}

@media (max-width: 991px) {
  .search-container {
    margin-top: 40px;
  }

  .date-input {
    padding: 11px 20px;
  }

  .search-button {
    padding: 16px 20px;
  }
}
</style>