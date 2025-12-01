<template>
  <div class="max-w-2xl mx-auto p-6 space-y-6">
    <h2 class="text-2xl font-bold text-gray-900">Create New Post</h2>

    <form @submit.prevent="submitPost" class="space-y-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
        <input 
          v-model="form.title" 
          type="text" 
          placeholder="Enter post title" 
          class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          required
        />
        <div class="text-sm text-gray-500 mt-1">{{ form.title.length }}/255 characters</div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
        <textarea 
          v-model="form.content" 
          placeholder="Write your post content here..." 
          rows="6"
          class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          required
        ></textarea>
        <div class="text-sm text-gray-500 mt-1">{{ form.content.length }}/1000 characters</div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Select Platforms</label>
        <div v-if="platforms.length === 0" class="text-red-600 text-sm mb-2">
          No platforms available. Please add platforms in settings first.
        </div>
        <select 
          v-else
          v-model="form.platform_ids" 
          multiple 
          class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option v-for="platform in platforms" :key="platform.id" :value="platform.id">
            {{ platform.name }} ({{ platform.type }})
          </option>
        </select>
        <p class="text-sm text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple platforms</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Image Upload</label>
        <input 
          type="file" 
          @change="handleImageUpload" 
          accept="image/*"
          class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
        />
        <div v-if="form.image_url" class="mt-2">
          <img :src="form.image_url" alt="Preview" class="max-w-xs h-auto rounded-md" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Time (Optional)</label>
        <input 
          type="datetime-local" 
          v-model="form.schedule_time" 
          class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
        <select 
          v-model="form.status" 
          class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option value="draft">Draft</option>
          <option value="scheduled">Scheduled</option>
          <option value="published">Published</option>
        </select>
      </div>

      <button 
        type="submit" 
        :disabled="submitting"
        class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
      >
        {{ submitting ? 'Creating Post...' : 'Create Post' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const form = ref({
  title: '',
  content: '',
  image_url: '',
  platform_ids: [],
  schedule_time: '',
  status: 'draft',
});

const platforms = ref([]);
const submitting = ref(false);

const loadPlatforms = async () => {
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
  }
};

const handleImageUpload = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append('image', file);

  try {
    const token = localStorage.getItem('token');
    const response = await axios.post('/api/upload-image', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Authorization: `Bearer ${token}`
      }
    });
    form.value.image_url = response.data.image_url;
  } catch (error) {
    console.error('Image upload failed:', error);
    alert('Failed to upload image');
  }
};

const submitPost = async () => {
  submitting.value = true;
  
  try {
    const token = localStorage.getItem('token');
    const response = await axios.post('/api/posts', form.value, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    
    alert('Post created successfully!');
    router.push('/dashboard');
  } catch (error) {
    console.error('Failed to create post:', error);
    alert('Failed to create post. Please try again.');
  } finally {
    submitting.value = false;
  }
};

onMounted(loadPlatforms);
</script>