import '../css/app.css';
import './bootstrap';
import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, h } from 'vue';
import { Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';



createInertiaApp({
  resolve: (name) => {
    // Используем встроенный помощник Laravel Vite для автоматического разрешения путей
    return resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue')
    );
  },
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .component('Link', Link)
      .mount(el);
  },
  progress: {
    color: '#4B5563',
    showSpinner: true,
  },
});