import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot, hydrateRoot } from 'react-dom/client';
import { Provider } from 'react-redux';
import { store } from './store/store';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
  setup({ el, App, props }) {
    const app = (
      <Provider store={store}>
        <App {...props} />
      </Provider>
    );

    if (import.meta.env.SSR) {
      hydrateRoot(el, app);
      return;
    }

    createRoot(el).render(app);
  },
  progress: {
    color: '#4B5563',
  },
});
