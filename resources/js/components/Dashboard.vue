<template>
  <div class="page">
    <header class="page-header">
      <div>
        <p class="eyebrow">Workspace overview</p>
        <h1 class="page-title">Good to see you{{ userName ? `, ${userName}` : '' }}.</h1>
        <p class="page-subtitle">Plan, review, and publish content across your connected platforms from one calm workspace.</p>
      </div>
      <div class="header-actions">
        <button class="button button-secondary" type="button" :disabled="loading" @click="loadPosts">
          <span>{{ loading ? 'Refreshing…' : '↻ Refresh' }}</span>
        </button>
        <router-link class="button button-primary" to="/editor">＋ Create post</router-link>
      </div>
    </header>

    <div class="stat-grid">
      <article class="surface stat-card">
        <div class="stat-topline"><span>Total posts</span><span class="stat-icon">▤</span></div>
        <p class="stat-value">{{ posts.length }}</p>
        <p class="stat-note">Across your workspace</p>
      </article>
      <article class="surface stat-card">
        <div class="stat-topline"><span>Published</span><span class="stat-icon">✓</span></div>
        <p class="stat-value">{{ countByStatus('published') }}</p>
        <p class="stat-note">Ready and live</p>
      </article>
      <article class="surface stat-card">
        <div class="stat-topline"><span>Scheduled</span><span class="stat-icon">◷</span></div>
        <p class="stat-value">{{ countByStatus('scheduled') }}</p>
        <p class="stat-note">Queued for publishing</p>
      </article>
      <article class="surface stat-card">
        <div class="stat-topline"><span>Drafts</span><span class="stat-icon">✎</span></div>
        <p class="stat-value">{{ countByStatus('draft') }}</p>
        <p class="stat-note">Still in progress</p>
      </article>
    </div>

    <div class="surface" style="margin-bottom: 18px">
      <div class="surface-header">
        <div>
          <h2 class="section-title">Content queue</h2>
          <p class="section-caption">Filter your posts by status or search by title.</p>
        </div>
        <span class="section-caption">{{ filteredPosts.length }} shown</span>
      </div>
      <div class="filter-bar">
        <select v-model="filterStatus" class="form-control" aria-label="Filter by status" style="max-width: 180px">
          <option value="">All statuses</option>
          <option value="draft">Draft</option>
          <option value="scheduled">Scheduled</option>
          <option value="published">Published</option>
          <option value="failed">Failed</option>
        </select>
        <div class="search-wrap">
          <span class="search-symbol" aria-hidden="true">⌕</span>
          <input v-model="filterSearch" class="search-control" type="search" placeholder="Search posts…" aria-label="Search posts" />
        </div>
      </div>
    </div>

    <div v-if="loading" class="surface state"><strong>Loading your workspace</strong><p>Fetching the latest publishing queue.</p></div>
    <div v-else-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
    <div v-else-if="posts.length === 0" class="surface state">
      <strong>Your queue is empty</strong>
      <p>Create your first post to start planning your publishing calendar.</p>
      <router-link class="button button-primary" to="/editor">Create your first post</router-link>
    </div>
    <div v-else class="dashboard-grid">
      <section class="surface">
        <div class="surface-header">
          <div><h2 class="section-title">Recent posts</h2><p class="section-caption">A quick view of your latest content.</p></div>
          <router-link class="button button-ghost" to="/posts">View all →</router-link>
        </div>
        <PostList :posts="filteredPosts.slice(0, 6)" />
      </section>
      <section class="surface">
        <div class="surface-header">
          <div><h2 class="section-title">Publishing calendar</h2><p class="section-caption">What is coming up next.</p></div>
        </div>
        <CalendarView :posts="filteredPosts" />
      </section>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import CalendarView from './CalendarView.vue';
import PostList from './PostList.vue';

const posts = ref([]);
const loading = ref(true);
const error = ref('');
const filterStatus = ref('');
const filterSearch = ref('');
const userName = ref('');

const loadPosts = async () => {
  loading.value = true;
  error.value = '';
  try {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    userName.value = user.name?.split(' ')[0] || '';
    if (!user.id) throw new Error('User not found');
    const response = await axios.get(`/api/user/${user.id}/posts`);
    posts.value = response.data.posts || [];
  } catch (err) {
    console.error('Failed to load posts:', err);
    error.value = 'We could not load your posts. Check your connection and try again.';
    posts.value = [];
  } finally {
    loading.value = false;
  }
};

const filteredPosts = computed(() => posts.value.filter((post) => {
  const title = String(post.title || '').toLowerCase();
  const query = filterSearch.value.trim().toLowerCase();
  return (!filterStatus.value || post.status === filterStatus.value) && (!query || title.includes(query));
}));

const countByStatus = (status) => posts.value.filter((post) => post.status === status).length;
onMounted(loadPosts);
</script>
