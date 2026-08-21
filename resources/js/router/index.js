import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/LoginForm.vue';
import Dashboard from '../components/Dashboard.vue';
import PlatformSettings from '../components/PlatformSettings.vue';
import PostEditor from '../components/PostEditor.vue';
import PostList from '../components/PostList.vue';

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: Login },
  { path: '/dashboard', component: Dashboard },
  { path: '/platform-settings', redirect: '/settings' },
  { path: '/editor', component: PostEditor },
  { path: '/settings', component: PlatformSettings },
  { path: '/posts', component: PostList },
];

const router = createRouter({ history: createWebHistory(), routes });

router.beforeEach((to) => {
  const authenticated = Boolean(localStorage.getItem('token'));
  if (to.path !== '/login' && !authenticated) return '/login';
  if (to.path === '/login' && authenticated) return '/dashboard';
  return true;
});

export default router;
