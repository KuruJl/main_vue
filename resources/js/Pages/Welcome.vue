<template>
  <div class="flex min-h-screen font-sans">
    <!-- Sidebar -->
    <aside class="w-60 bg-[#999C7B] text-white p-4 flex flex-col items-center">
      <img src="/logo.svg" alt="Логотип" class="w-16 mb-8" />
      <nav class="space-y-4 text-lg">
        <a href="#" class="block hover:underline">Главная</a>
        <a href="#" class="block hover:underline">Календарь</a>
        <a href="#" class="block hover:underline">Профиль</a>
        <a href="#" class="block hover:underline">Лента</a>
        <a href="#" class="block hover:underline">Форум</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 bg-cover bg-center relative" style="background-image: url('/img/sprout.jpg')">
      <div class="absolute top-4 left-1/2 transform -translate-x-1/2 w-[300px]">
        <input
          type="text"
          placeholder="поиск..."
          class="w-full p-2 rounded bg-[#d9d9d9]/70 placeholder-gray-700"
        />
      </div>

      <div class="h-full flex flex-col justify-center items-end p-12 text-right text-white">
        <h1 class="text-6xl font-serif mb-6">РостОК</h1>
        <p class="max-w-lg text-md bg-black/40 p-4 rounded">
          Здесь каждое семечко становится историей, а каждый росток — поводом для гордости. Делитесь своими зелёными
          побегами, находите вдохновение в успехах других и получайте персональные советы по уходу за своими растениями.
        </p>
      </div>
    </main>
  </div>
</template>