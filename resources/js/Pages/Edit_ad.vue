<template>
    <div class="container">
      <h1>Редактирование объявления</h1>
  
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
            <p v-if="files.length === 0 && existingImages.length === 0">Перетащите изображения сюда или нажмите для загрузки</p>
            <div v-else class="preview-container">
              <div v-for="image in existingImages" :key="image.id" class="preview-item">
                <img :src="image.url" class="preview-image">
                <button type="button" @click.stop="removeExistingImage(image.id)" class="remove-btn">
                  &times;
                </button>
              </div>
              <div v-for="(file, index) in files" :key="`new-${index}`" class="preview-item">
                <img :src="file.preview" class="preview-image">
                <button type="button" @click.stop="removeNewImage(index)" class="remove-btn">
                  &times;
                </button>
              </div>
            </div>
          </div>
          <div v-if="form.errors.images" class="error-message">{{ form.errors.images }}</div>
          <div v-if="form.errors.new_images" class="error-message">{{ form.errors.new_images }}</div>
        </div>
  
        <button type="submit" class="submit-btn" :disabled="form.processing">
          {{ form.processing ? 'Сохранение...' : 'Обновить объявление' }}
        </button>
  
        <div v-if="page.props.flash && page.props.flash.success" class="success-message">
          {{ page.props.flash.success }}
        </div>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    name: 'EditAd'
  }
  </script>
  
  <script setup>
  import { ref, watch, defineProps, onMounted } from 'vue';
  import { useForm, usePage } from '@inertiajs/vue3';

  const props = defineProps({
    listing: {
      type: Object,
      required: true,
    },
  });
  
  const page = usePage();
  
  const form = useForm({
    _method: 'patch', // Важно: указываем метод PATCH для Inertia
    title: props.listing.title,
    description: props.listing.description,
    address: props.listing.address,
    city: props.listing.city,
    count_rooms: props.listing.count_rooms,
    price_per_night: props.listing.price_per_night,
    user_id: props.listing.user_id, // Или page.props.auth.user.id если уверены, что он совпадает
    new_images: [], // Для новых файлов
    existing_image_ids_to_keep: [], // Для ID существующих изображений, которые нужно оставить
  });
  
  const files = ref([]); // Для новых загруженных файлов с превью
  const existingImages = ref([]); // Для существующих изображений из пропсов
  const dragOver = ref(false);
  const fileInput = ref(null);
  
  // Инициализируем existingImages на основе пропса listing.images
  onMounted(() => {
    if (props.listing.images) {
      existingImages.value = props.listing.images.map(img => ({
        id: img.id,
        url: img.url,
      }));
      // Инициализируем existing_image_ids_to_keep всеми существующими ID
      form.existing_image_ids_to_keep = existingImages.value.map(img => img.id);
    }
  });
  
  
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
  
    if (existingImages.value.length + files.value.length + allowedFiles.length > 5) {
      alert(`Можно загрузить не более 5 изображений (у вас уже ${existingImages.value.length + files.value.length} загружено).`);
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
  
  const removeExistingImage = (imageId) => {
    existingImages.value = existingImages.value.filter(img => img.id !== imageId);
    // Обновляем список ID, которые нужно сохранить
    form.existing_image_ids_to_keep = existingImages.value.map(img => img.id);
  };
  
  const removeNewImage = (index) => {
    files.value.splice(index, 1);
  };
  
  // Важно: наблюдаем за изменением files.value и обновляем form.new_images
  watch(files, (newFiles) => {
    form.new_images = newFiles.map(f => f.file);
  }, { deep: true });
  
  const submitForm = () => {
  // ИЗМЕНИТЕ ЭТУ СТРОКУ:
  // form.patch(route('listings.update', props.listing.id), {
  // НА ЭТУ: (используя прямой URL)
  form.patch(`/listings/${props.listing.id}`, { // <-- ИЗМЕНЕНО ЗДЕСЬ!
    onSuccess: () => {
      alert('Объявление успешно обновлено!');
      // После успешного обновления, Inertia сделает полный редирект,
      // так что нет необходимости вручную сбрасывать форму или изображения
    },
    onError: (errors) => {
      console.error('Ошибка обновления объявления:', errors);
      alert('Пожалуйста, исправьте ошибки в форме.');
    },
    onFinish: () => {
      // Можно что-то сделать по завершении запроса
    }
  });
};
  </script>
  
  <style scoped>
  /* Стили из Create_ad.vue должны подойти */
  /* Ваши стили остаются такими же, как и были */
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