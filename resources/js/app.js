import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { enablePullToRefresh } from './utils/pullToRefresh';

// Sign-in tokens expire (and are revoked on password change or deactivation). When the server says the
// saved one no longer works, clear it and go to sign-in instead of leaving pages half-broken.
const nativeFetch = window.fetch.bind(window);
const PUBLIC_API = ['/api/login', '/api/register', '/api/password', '/api/newsletter'];

window.fetch = async (input, init) => {
  const response = await nativeFetch(input, init);
  const url = typeof input === 'string' ? input : (input && input.url) || '';

  const sessionEnded = response.status === 401
    && url.startsWith('/api/')
    && !PUBLIC_API.some((prefix) => url.startsWith(prefix))
    && localStorage.getItem('sparky_token');

  if (sessionEnded) {
    localStorage.removeItem('sparky_token');
    localStorage.removeItem('sparky_user');
    router.push('/login');
  }

  return response;
};

const app = createApp(App);
app.use(router);
app.mount('#app');
enablePullToRefresh();
