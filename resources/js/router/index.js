import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/LoginForm.vue';
import Dashboard from '../components/Dashboard.vue';
import PlatformSettings from '../components/PlatformSettings.vue';
import PostEditor from '../components/PostEditor.vue';
import AllPosts from '../components/AllPosts.vue';
import Landing from '../components/Landing.vue';
const routes = [
  { path: '/', component: Landing },
  { path: '/login', component: Login },
  { path: '/dashboard', component: Dashboard },
  { path: '/editor', component: PostEditor },
  { path: '/settings', component: PlatformSettings },
  { path: '/platform-settings', redirect: '/settings' },
  { path: '/posts', component: AllPosts },
  { path: '/:pathMatch(.*)*', redirect: '/dashboard' },
];

const router = createRouter({ history: createWebHistory(), routes });

const publicPaths = ['/', '/login'];

router.beforeEach((to) => {
  const authenticated = Boolean(localStorage.getItem('token'));
  if (!publicPaths.includes(to.path) && !authenticated) return '/login';
  if (to.path === '/login' && authenticated) return '/dashboard';
  return true;
});

export default router;