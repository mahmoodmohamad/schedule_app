<template>
  <div class="page">
    <header class="page-header">
      <div>
        <p class="eyebrow">Content library</p>
        <h1 class="page-title">All posts</h1>
        <p class="page-subtitle">Every post in your workspace.</p>
      </div>
      <router-link class="button button-primary" to="/editor">＋ Create post</router-link>
    </header>
    <div v-if="loading" class="surface state"><strong>Loading posts</strong></div>
    <div v-else-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
    <section v-else class="surface"><PostList :posts="posts" /></section>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import PostList from './PostList.vue';

const posts = ref([]);
const loading = ref(true);
const error = ref('');

onMounted(async () => {
  try {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    const { data } = await axios.get(`/api/user/${user.id}/posts`);
    posts.value = data.posts || [];
  } catch (e) {
    error.value = 'We could not load your posts.';
  } finally {
    loading.value = false;
  }
});
</script>