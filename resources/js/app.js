import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // Make sure this points to your router index.js

const app = createApp(App);
app.use(router); // Properly register the router
app.mount('#app');
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    // Make sure the format matches what your backend expects
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});