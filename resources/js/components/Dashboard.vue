<template>
  <div class="p-6 space-y-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
      <div class="space-x-4">
        <router-link
          to="/posts"
          class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors"
        >
           Posts
        </router-link>
        <router-link
          to="/editor"
          class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors"
        >
          Create Post
        </router-link>
        <router-link
          to="/settings"
          class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors"
        >
          Platform Settings
        </router-link>
        <button
          @click="logout"
          class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors"
        >
          Logout
        </button>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg border space-y-4">
      <h2 class="text-lg font-medium">Filters</h2>
      <div class="flex gap-4 items-center">
        <select 
          v-model="filterStatus" 
          class="border rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option value="">All Statuses</option>
          <option value="draft">Draft</option>
          <option value="scheduled">Scheduled</option>
          <option value="published">Published</option>
        </select>

        <input
          v-model="filterSearch"
          type="text"
          placeholder="Search posts..."
          class="flex-1 border rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
        />
        
        <button
          @click="loadPosts"
          class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors"
        >
          Refresh
        </button>
      </div>
    </div>

    <div v-if="loading" class="text-center py-8">
      <div class="text-gray-600">Loading posts...</div>
    </div>

    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
      {{ error }}
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Calendar View -->
      <div>
        <h2 class="text-xl font-semibold mb-4">Calendar View</h2>
        <CalendarView :posts="filteredPosts" />
      </div>

      <!-- List View -->
      <div>
        <h2 class="text-xl font-semibold mb-4">List View</h2>
        <PostList :posts="filteredPosts" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import CalendarView from './CalendarView.vue';
import PostList from './PostList.vue';

const router = useRouter();

const posts = ref([]);
const loading = ref(true);
const error = ref('');
const filterStatus = ref('');
const filterSearch = ref('');

const loadPosts = async () => {
  loading.value = true;
  error.value = '';
  
  try {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    if (!user.id) {
      throw new Error('User not found');
    }
    
    const response = await axios.get(`/api/user/${user.id}/posts`);
    posts.value = response.data.posts || [];
  } catch (err) {
    console.error('Failed to load posts:', err);
    error.value = 'Failed to load posts';
    posts.value = [];
  } finally {
    loading.value = false;
  }
};

const filteredPosts = computed(() => {
  return posts.value.filter(post => {
    const matchesStatus = !filterStatus.value || post.status === filterStatus.value;
    const matchesSearch = !filterSearch.value || 
      post.title.toLowerCase().includes(filterSearch.value.toLowerCase());
    return matchesStatus && matchesSearch;
  });
});

const logout = () => {
  localStorage.removeItem('token');
  localStorage.removeItem('user');
  router.push('/login');
};

onMounted(loadPosts);
</script>