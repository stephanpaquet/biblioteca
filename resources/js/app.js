import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';
import '../css/app.css';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

const pinia = createPinia();

const toastOptions = {
  position: 'top-right',
  timeout: 4000,
  closeOnClick: true,
  pauseOnFocusLoss: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.6,
  showCloseButtonOnHover: false,
  hideProgressBar: false,
  closeButton: 'button',
  icon: true,
  rtl: false,
};

createInertiaApp({
  title: (title) => `${title} - Biblioteca`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(pinia)
      .use(Toast, toastOptions);

    // Make route function available globally
    app.config.globalProperties.route = route;

    return app.mount(el);
  },
  progress: {
    color: '#4F46E5',
  },
});
