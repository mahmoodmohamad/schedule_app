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
          {{ loading ? 'Refreshing…' : '↻ Refresh' }}
        </button>
        <router-link class="button button-primary" to="/editor">＋ Create post</router-link>
      </div>
    </header>

    <div class="stat-grid">
      <button
        v-for="stat in stats"
        :key="stat.label"
        type="button"
        class="surface stat-card stat-button"
        :class="{ 'is-selected': filterStatus === stat.status }"
        :aria-pressed="filterStatus === stat.status"
        @click="toggleFilter(stat.status)"
      >
        <div class="stat-topline"><span>{{ stat.label }}</span><span class="stat-icon">{{ stat.icon }}</span></div>
        <p class="stat-value">{{ stat.count }}</p>
        <p class="stat-note">{{ stat.note }}</p>
      </button>
    </div>

    <div v-if="loading" class="surface state"><strong>Loading your workspace</strong><p>Fetching the latest publishing queue.</p></div>

    <div v-else-if="error" class="surface state">
      <strong>Something went wrong</strong>
      <p>{{ error }}</p>
      <button class="button button-primary" type="button" @click="loadPosts">Try again</button>
    </div>

    <div v-else-if="posts.length === 0" class="surface state">
      <strong>Your queue is empty</strong>
      <p>Create your first post to start planning your publishing calendar.</p>
      <router-link class="button button-primary" to="/editor">Create your first post</router-link>
    </div>

    <template v-else>
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

      <div class="dashboard-grid">
        <section class="surface">
          <div class="surface-header">
            <div><h2 class="section-title">Recent posts</h2><p class="section-caption">A quick view of your latest content.</p></div>
            <router-link class="button button-ghost" to="/posts">View all →</router-link>
          </div>
          <PostList :posts="filteredPosts.slice(0, 6)" />
        </section>
        <section class="surface">
          <div class="surface-header">
            <div><h2 class="section-title">Upcoming</h2><p class="section-caption">Your next scheduled posts.</p></div>
          </div>
          <CalendarView :posts="filteredPosts" />
        </section>
      </div>
    </template>
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

const countByStatus = (status) => posts.value.filter((p) => p.status === status).length;

const stats = computed(() => [
  { label: 'Total posts', status: '', icon: '▤', count: posts.value.length, note: 'Across your workspace' },
  { label: 'Published', status: 'published', icon: '✓', count: countByStatus('published'), note: 'Ready and live' },
  { label: 'Scheduled', status: 'scheduled', icon: '◷', count: countByStatus('scheduled'), note: 'Queued for publishing' },
  { label: 'Drafts', status: 'draft', icon: '✎', count: countByStatus('draft'), note: 'Still in progress' },
]);

const toggleFilter = (status) => {
  filterStatus.value = filterStatus.value === status ? '' : status;
};

const filteredPosts = computed(() => {
  const query = filterSearch.value.trim().toLowerCase();
  return posts.value.filter((post) => {
    const title = String(post.title || '').toLowerCase();
    return (!filterStatus.value || post.status === filterStatus.value) && (!query || title.includes(query));
  });
});

const loadPosts = async () => {
  loading.value = true;
  error.value = '';
  try {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    userName.value = user.name?.split(' ')[0] || '';
    if (!user.id) throw new Error('User not found');
    const { data } = await axios.get(`/api/user/${user.id}/posts`);
    posts.value = data.posts || [];
  } catch (err) {
    console.error('Failed to load posts:', err);
    if (err.response?.status !== 401) {
      error.value = 'We could not load your posts. Check your connection and try again.';
    }
    posts.value = [];
  } finally {
    loading.value = false;
  }
};

onMounted(loadPosts);
</script>

<style scoped>
.stat-button { text-align: left; font: inherit; color: inherit; cursor: pointer; width: 100%; }
.stat-button.is-selected { outline: 2px solid #6366f1; outline-offset: -2px; }
</style>