<template>
  <div class="max-w-4xl mx-auto p-6 space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-900">Platform Management</h1>
      <router-link 
        to="/dashboard" 
        class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors"
      >
        Back to Dashboard
      </router-link>
    </div>

    <!-- Add Platform Form -->
    <div class="bg-white p-6 rounded-lg border">
      <h2 class="text-lg font-medium mb-4">Add New Platform</h2>
      <form @submit.prevent="createPlatform" class="flex gap-4 items-end">
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">Platform Name</label>
          <input 
            v-model="newPlatform.name" 
            type="text" 
            placeholder="e.g., Facebook, Twitter, Instagram" 
            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            required 
          />
        </div>
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
          <select 
            v-model="newPlatform.type" 
            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            required
          >
            <option value="">Select Type</option>
            <option value="social">Social Media</option>
            <option value="blog">Blog</option>
            <option value="news">News</option>
            <option value="other">Other</option>
          </select>
        </div>
        <button 
          type="submit" 
          :disabled="creating"
          class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          {{ creating ? 'Adding...' : 'Add Platform' }}
        </button>
      </form>
    </div>

    <!-- Platforms List -->
    <div class="bg-white rounded-lg border overflow-hidden">
      <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-lg font-medium">Your Platforms</h2>
      </div>

      <div v-if="loading" class="text-center py-8">
        <div class="text-gray-600">Loading platforms...</div>
      </div>

      <div v-else-if="platforms.length === 0" class="text-center py-8">
        <div class="text-gray-500">No platforms added yet. Add your first platform above.</div>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="(platform, index) in platforms" :key="platform.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 text-sm text-gray-900">{{ index + 1 }}</td>
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ platform.name }}</div>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                  {{ platform.type }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">
                {{ formatDate(platform.created_at) }}
              </td>
              <td class="px-6 py-4">
                <button 
                  @click="deletePlatform(platform.id)" 
                  class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition-colors"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const platforms = ref([]);
const newPlatform = ref({ name: '', type: '' });
const loading = ref(true);
const creating = ref(false);

const fetchPlatforms = async () => {
  loading.value = true;
  try {
    const token = localStorage.getItem('token');
    const response = await axios.get('/api/platforms', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    platforms.value = response.data.platforms || [];
  } catch (error) {
    console.error('Failed to load platforms:', error);
    platforms.value = [];
  } finally {
    loading.value = false;
  }
};

const createPlatform = async () => {
  creating.value = true;
  try {
    const token = localStorage.getItem('token');
    const response = await axios.post('/api/platforms', newPlatform.value, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    platforms.value.push(response.data.platform);
    newPlatform.value = { name: '', type: '' };
  } catch (error) {
    console.error('Failed to create platform:', error);
    alert('Failed to create platform. Please try again.');
  } finally {
    creating.value = false;
  }
};

const deletePlatform = async (id) => {
  if (!confirm('Are you sure you want to delete this platform?')) {
    return;
  }

  try {
    const token = localStorage.getItem('token');
    await axios.delete(`/api/platforms/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    platforms.value = platforms.value.filter(p => p.id !== id);
  } catch (error) {
    console.error('Failed to delete platform:', error);
    alert('Failed to delete platform. Please try again.');
  }
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

onMounted(fetchPlatforms);
</script>