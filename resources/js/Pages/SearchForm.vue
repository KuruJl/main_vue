<template>
    <div class="search-form">
  <div class="search-field">
    <select class="search-input location-select">
      <option value="" disabled selected>Локация</option>
      <option value="moscow">Москва</option>
      <option value="petersburg">Санкт-Петербург</option>
      <option value="sochi">Сочи</option>
    </select>
  </div>
  
  <div class="search-field">
    <input type="number" class="search-input" placeholder="Цена">
  </div>
  
  <div class="search-field">
    <input type="number" class="search-input" placeholder="Кол-во комнат" min="1">
  </div>
  
  <div class="search-field">
    <input type="text" class="search-input date-input" placeholder="Дата" readonly>
  </div>
  
  <button class="search-button">Поиск</button>
</div>
</template>

<script>
  export default {
    name: 'AppSearchForm'
  }
  </script>

<style>
.search-form {
    margin: 20px auto;  /* Центрирование */
  width: 920px;
  height: 50px;
  display: flex;
  background-color: #fff;
  border: 1px solid #000;
  border-radius: 8px;
  overflow: hidden;
}

.search-field {
  flex: 1;
  position: relative;
  border-right: 1px solid #000000;
}

.search-field:last-child {
  border-right: none;
}

.search-input {
  width: 100%;
  height: 100%;
  padding: 0 15px;
  border: none;
  outline: none;
  font-size: 14px;
}

.location-select {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 10px center;
  background-size: 15px;
}

.date-input {
  cursor: pointer;
}

.search-button {
  width: 120px;
  background-color: #9bcb00;
  color: white;
  border: none;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s;
}

.search-button:hover {
  background-color: #9bcb00;
}
</style>