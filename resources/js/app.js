import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; 

const app = createApp(App);
app.use(router); 
app.mount('#app');
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});