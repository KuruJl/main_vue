<template>
    <footer class="footer">
      <div class="footer-content">
        <div class="footer-logo-column">
          <h2 class="footer-logo">Sweet<br />Home</h2>
        </div>
  
        <div class="footer-nav-column">
          <div class="footer-nav-content">
            <h3 class="footer-heading">Съемщику</h3>
            <nav class="footer-links">
              <a href="/" class="footer-link">Главная</a>
              <a href="/search" class="footer-link">Поиск</a>
              <a href="#" class="footer-link">Контакты</a>
              <a href="/profilee" class="footer-link">Профиль</a>
            </nav>
          </div>
        </div>
  
        <div class="footer-landlord-column">
          <div class="footer-landlord-content">
            <h3 class="footer-heading">Арендодателю</h3>
            <nav class="footer-links">
                <a href="/profilee" class="btn">Профиль</a>
                <a href="/ad" class="footer-link">Разместить объявление</a>
            </nav>
          </div>
        </div>
      </div>
    </footer>
  </template>
  


  <script>
  export default {
    name: 'AppFooter'
  }
  </script>
  
  <style scoped>
  .footer {
    font-family: 'Rubik';
    margin: 20px auto;  /* Центрирование */
    max-width: 1120px;  /* Максимальная ширина */
    width: 80%;         /* Гибкая ширина (80% от родителя) */
    border-radius: 15px;
    background-color: #fff;
    border: 1px solid #000;
    padding: 25px 80px 25px 25px;
    height: 190px;
    box-sizing: border-box;
}
  
  .footer-content {
    gap: 20px;
    display: flex;
  }
  
  .footer-logo-column {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    width: 16%;
  }
  
  .footer-logo {
    color: rgba(172, 224, 0, 1);
    font-size: 16px;
    font-family: Rubik, -apple-system, Roboto, Helvetica, sans-serif;
    font-weight: 800;
    line-height: 13px;
    margin: 0;
  }
  
  .footer-nav-column {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    width: 26%;
    margin-left: 20px;
  }
  
  .footer-nav-content {
    display: flex;
    flex-grow: 1;
    flex-direction: column;
    align-items: start;
    font-family: Rubik, -apple-system, Roboto, Helvetica, sans-serif;
    font-size: 16px;
    color: #000;
    font-weight: 300;
  }
  
  .footer-heading {
    font-weight: 600;
    margin: 0;
    font-size: 16px;
  }
  
  .footer-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
  }
  
  .footer-link {
    color: inherit;
    text-decoration: none;
  }
  
  .footer-landlord-column {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    width: 57%;
    margin-left: 20px;
  }
  
  .footer-landlord-content {
    display: flex;
    flex-direction: column;
    align-items: start;
    font-family: Rubik, -apple-system, Roboto, Helvetica, sans-serif;
    font-size: 16px;
    color: #000;
    font-weight: 300;
  }
  
  @media (max-width: 991px) {
    .footer {
      width: 100%;
      height: auto;
      min-height: 190px;
      padding: 28px 20px;
      margin-top: 40px;
    }
  
    .footer-content {
      flex-direction: column;
      align-items: stretch;
      gap: 0;
    }
  
    .footer-logo-column,
    .footer-nav-column,
    .footer-landlord-column {
      width: 100%;
      margin-left: 0;
    }
  
    .footer-logo,
    .footer-nav-content,
    .footer-landlord-content {
      margin-top: 40px;
    }
  }
  </style>
  