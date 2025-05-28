import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/LoginForm.vue'; // Verify this path is correct
import Dashboard from '../components/Dashboard.vue';
import PlatformSettings from '../components/PlatformSettings.vue';
import PostEditor from '../components/PostEditor.vue';

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: Login },
  { path: '/dashboard', component: Dashboard },
  { path: '/platform-settings', component: PlatformSettings },
  // Add these missing routes
  { path: '/editor', component: PostEditor },
  { path: '/settings', component: PlatformSettings }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Navigation guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  console.log('Router Guard - Token:', token, 'Path:', to.path);

  if (to.path !== '/login' && !token) {
    next('/login');
  } else if (to.path === '/login' && token) {
    next('/dashboard');
  } else {
    next();
  }
});

export default router;