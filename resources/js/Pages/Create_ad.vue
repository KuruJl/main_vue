<template>
  <div class="container">
    <h1>Создание объявления</h1>
    
    <form @submit.prevent="submitForm">
      <div class="form-group">
        <label for="title">Название*</label>
        <input 
          type="text" 
          id="title" 
          v-model="form.title"
          placeholder="Введите название объявления"
          required
        >
        <div v-if="form.errors.title" class="error-message">{{ form.errors.title }}</div>
      </div>
      
      <div class="form-group">
        <label for="description">Описание*</label>
        <textarea
          id="description"
          v-model="form.description"
          placeholder="Опишите ваше помещение"
          required
          rows="4"
        ></textarea>
        <div v-if="form.errors.description" class="error-message">{{ form.errors.description }}</div>
      </div>
      
      <div class="form-group">
        <label for="address">Адрес (Город, улица, номер здания)*</label>
        <input 
          type="text" 
          id="address" 
          v-model="form.address"
          placeholder="Введите адрес"
          required
        >
        <div v-if="form.errors.address" class="error-message">{{ form.errors.address }}</div>
      </div>
      
      <div class="form-group">
        <label for="city">Город*</label>
        <input 
          type="text" 
          id="city" 
          v-model="form.city"
          placeholder="Введите город"
          required
        >
        <div v-if="form.errors.city" class="error-message">{{ form.errors.city }}</div>
      </div>
      
      <div class="form-group">
        <label for="count_rooms">Количество комнат*</label>
        <input 
          type="number" 
          id="count_rooms" 
          v-model.number="form.count_rooms"
          placeholder="Введите количество комнат" 
          min="1"
          required
        >
        <div v-if="form.errors.count_rooms" class="error-message">{{ form.errors.count_rooms }}</div>
      </div>
      
      <div class="form-group">
        <label for="price_per_night">Цена за ночь (₽)*</label>
        <input 
          type="number" 
          id="price_per_night" 
          v-model.number="form.price_per_night"
          placeholder="Введите цену"
          min="1"
          required
        >
        <div v-if="form.errors.price_per_night" class="error-message">{{ form.errors.price_per_night }}</div>
      </div>
      
      <div class="form-group">
        <label>Изображения (максимум 5)</label>
        <div 
          class="image-upload"
          @click="triggerFileInput"
          @dragover.prevent="dragOver = true"
          @dragleave="dragOver = false"
          @drop.prevent="handleDrop"
          :class="{ 'drag-over': dragOver }"
        >
          <input 
            type="file" 
            ref="fileInput"
            accept="image/*"
            multiple
            style="display: none;"
            @change="handleFileChange"
          >
          <p v-if="files.length === 0">Перетащите изображения сюда или нажмите для загрузки</p>
          <div v-else class="preview-container">
            <div v-for="(file, index) in files" :key="index" class="preview-item">
              <img :src="file.preview" class="preview-image">
              <button type="button" @click.stop="removeImage(index)" class="remove-btn">
                &times;
              </button>
            </div>
          </div>
        </div>
        <div v-if="form.errors.images" class="error-message">{{ form.errors.images }}</div>
      </div>
      
      <button type="submit" class="submit-btn" :disabled="form.processing">
        {{ form.processing ? 'Отправка...' : 'Разместить объявление' }}
      </button>

      <div v-if="page.props.flash && page.props.flash.success" class="success-message">
          {{ page.props.flash.success }}
      </div>
    </form>
  </div>
</template>
<script>
export default {
  name: 'CreateAd'
}
</script>
<script setup>


import { ref, watch, defineProps } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

// Определяем пропсы, которые этот компонент будет принимать
const props = defineProps({
  userId: {
    type: [Number, null], // Может быть числом или null
    required: false, // Не обязателен, т.к. пользователь может быть не авторизован
    default: null
  }
});

const page = usePage(); // Для доступа к flash сообщениям

const form = useForm({
  title: '',
  description: '',
  address: '',
  city: '',
  count_rooms: 1,
  price_per_night: '',
  user_id: props.userId, // Используем пропс userId
  images: [], // Для хранения файлов изображений
});

const files = ref([]);
const dragOver = ref(false);
const fileInput = ref(null);

const triggerFileInput = () => {
  fileInput.value.click();
};

const handleFileChange = (event) => {
  processFiles(event.target.files);
};

const handleDrop = (event) => {
  dragOver.value = false;
  processFiles(event.dataTransfer.files);
};

const processFiles = (newFiles) => {
  const allowedFiles = Array.from(newFiles).filter(file => file.type.startsWith('image/'));

  if (files.value.length + allowedFiles.length > 5) {
    alert('Можно загрузить не более 5 изображений.');
    return;
  }

  allowedFiles.forEach(file => {
    const reader = new FileReader();
    reader.onload = (e) => {
      files.value.push({ file: file, preview: e.target.result });
    };
    reader.readAsDataURL(file);
  });
};

const removeImage = (index) => {
  files.value.splice(index, 1);
};

// Важно: наблюдаем за изменением files.value и обновляем form.images
// Это нужно, чтобы useForm знал о файлах, которые нужно отправить
watch(files, (newFiles) => {
  form.images = newFiles.map(f => f.file);
}, { deep: true });

const submitForm = () => {
  if (!form.user_id) {
    alert('Для размещения объявления необходимо войти в систему.');
    return;
  }

  // ИЗМЕНИТЕ ЭТУ СТРОКУ, ЗАМЕНИВ route() НА ПРЯМОЙ URL:
  // Предположим, ваш URL - '/listings' (проверьте в routes/web.php)
  form.post('/listings', { // <-- Здесь укажите реальный URL для POST-запроса
    onSuccess: (response) => {
      alert('Объявление успешно создано!');
      form.reset();
      files.value = [];
    },
    onError: (errors) => {
      console.error('Ошибка создания объявления:', errors);
      alert('Пожалуйста, исправьте ошибки в форме.');
    },
    onFinish: () => {
      // ...
    }
  });
};
</script>

<style scoped>
/* Стили остаются такими же, как и были */
.container {
  font-family: 'Rubik';
  max-width: 600px;
  margin: 0 auto;
  background-color: white;
  padding: 25px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
  font-size: 24px;
  margin-top: 0;
  margin-bottom: 25px;
  color: black;
  text-align: center;
  font-weight: 600;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
  color: black;
}

input[type="text"],
input[type="number"],
textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 16px;
  box-sizing: border-box;
}

textarea {
  resize: vertical;
  min-height: 100px;
}

.image-upload {
  border: 2px dashed #ccc;
  padding: 20px;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
}

.image-upload:hover,
.image-upload.drag-over {
  border-color: #9bcb00;
  background-color: rgba(155, 203, 0, 0.05);
}

.preview-container {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 10px;
}

.preview-item {
  position: relative;
  width: 80px;
  height: 80px;
}

.preview-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 4px;
}

.remove-btn {
  position: absolute;
  top: -8px;
  right: -8px;
  width: 24px;
  height: 24px;
  background: #ff4444;
  color: white;
  border: none;
  border-radius: 50%;
  font-size: 14px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.submit-btn {
  background-color: rgba(172, 224, 0, 1);
  color: white;
  border: none;
  padding: 14px 20px;
  width: 100%;
  font-size: 20px;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.3s;
}

.submit-btn:hover {
  background-color: #8ab800;
}

.submit-btn:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}

.error-message {
  color: #e3342f;
  font-size: 0.875em;
  margin-top: 0.25em;
}

.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    margin-top: 20px;
    text-align: center;
}
</style>