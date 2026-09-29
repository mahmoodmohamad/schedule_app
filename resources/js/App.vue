<template>
  <div class="app-shell">
    <aside v-if="showShell" class="app-sidebar" :class="{ 'is-open': mobileMenuOpen }">
      <div class="sidebar-brand">
        <div class="brand-mark">S</div>
        <div>
          <p class="brand-name">Schedule</p>
          <p class="brand-caption">Content workspace</p>
        </div>
      </div>

      <nav class="sidebar-nav" aria-label="Primary navigation">
        <p class="nav-label">Workspace</p>
        <router-link to="/dashboard" class="nav-link" active-class="is-active" @click="closeMenu">
          <span class="nav-icon">⌂</span>
          Dashboard
        </router-link>
        <router-link to="/posts" class="nav-link" active-class="is-active" @click="closeMenu">
          <span class="nav-icon">▤</span>
          All posts
        </router-link>
        <router-link to="/editor" class="nav-link" active-class="is-active" @click="closeMenu">
          <span class="nav-icon">＋</span>
          Create post
        </router-link>

        <p class="nav-label nav-label-spaced">Manage</p>
        <router-link to="/settings" class="nav-link" active-class="is-active" @click="closeMenu">
          <span class="nav-icon">⚙</span>
          Platforms
        </router-link>
      </nav>

      <div class="sidebar-footer">
        <div class="sidebar-tip">
          <span class="tip-dot"></span>
          Keep your publishing queue moving.
        </div>
        <button class="nav-link nav-link-button" type="button" @click="logout">
          <span class="nav-icon">↪</span>
          Sign out
        </button>
      </div>
    </aside>

    <div v-if="showShell && mobileMenuOpen" class="sidebar-backdrop" @click="closeMenu"></div>

    <main class="app-main" :class="{ 'is-authenticated': showShell }">
      <header v-if="showShell" class="mobile-header">
        <button class="icon-button" type="button" aria-label="Open navigation" @click="mobileMenuOpen = true">☰</button>
        <span class="mobile-brand">Schedule</span>
        <router-link to="/editor" class="mobile-create" aria-label="Create post">＋</router-link>
      </header>
      <router-view />
    </main>
  </div>
</template>
<script setup>
import { computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const router = useRouter();
const route = useRoute();
const mobileMenuOpen = ref(false);
const isAuthenticated = ref(Boolean(localStorage.getItem('token')));

const publicPaths = ['/', '/login'];
const showShell = computed(() => isAuthenticated.value && !publicPaths.includes(route.path));

router.afterEach(() => {
  isAuthenticated.value = Boolean(localStorage.getItem('token'));
});

const closeMenu = () => {
  mobileMenuOpen.value = false;
};

const logout = () => {
  localStorage.removeItem('token');
  localStorage.removeItem('user');
  isAuthenticated.value = false;
  closeMenu();
  router.push('/');
};
</script>
