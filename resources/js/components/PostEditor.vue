<template>
  <div class="max-w-xl mx-auto space-y-4">
    <h2 class="text-xl font-bold">Create New Post</h2>

    <input v-model="form.title" type="text" placeholder="Title" class="w-full p-2 border rounded" />
    <div class="text-sm text-gray-500">Characters: {{ form.title.length }}/255</div>

    <textarea v-model="form.content" placeholder="Content" class="w-full p-2 border rounded"></textarea>
    <div class="text-sm text-gray-500">Characters: {{ form.content.length }}/1000</div>
<div class="mb-4">
  <label class="block text-gray-700 mb-2">Select Platforms</label>
  <div v-if="platforms.length === 0" class="text-red-500 mb-2">
    No platforms available. Please add platforms in settings first.
  </div>
  <select 
    v-else
    v-model="form.platform_ids" 
    multiple 
    class="w-full p-2 border rounded"
  >
    <option v-for="platform in platforms" :key="platform.id" :value="platform.id">
      {{ platform.name }}
    </option>
  </select>
  <p class="text-sm text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple platforms</p>
</div>
    <input type="file" @change="handleImageUpload" />

    
    <input type="datetime-local" v-model="form.schedule_time" class="w-full p-2 border rounded" />

    <select v-model="form.status" class="w-full p-2 border rounded">
      <option value="draft">Draft</option>
      <option value="scheduled">Scheduled</option>
      <option value="published">Published</option>
    </select>

    <button @click="submitPost" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const form = ref({
  title: '',
  content: '',
  image_url: '',
  platform_ids: [],
  schedule_time: '',
  status: 'draft',
})

const platforms = ref([])

onMounted(async () => {
  try {
    console.log('Fetching platforms...');
    const res = await axios.get('/api/platforms');
    platforms.value = res.data.platforms;
    console.log('Loaded platforms:', platforms.value);
    
    // If platforms array is empty, use fallback
    if (!platforms.value || platforms.value.length === 0) {
      console.warn('No platforms returned from API, using fallback');
      platforms.value = fallbackPlatforms;
    }
  } catch (error) {
    console.error('Failed to load platforms:', error);
    // Use fallback platforms
    platforms.value = fallbackPlatforms;
    console.log('Using fallback platforms');
  }
});
const handleImageUpload = async (e) => {
  const file = e.target.files[0]
  const formData = new FormData()
  formData.append('image', file)

  // تأكد إنك عامل route لرفع الصورة
  const res = await axios.post('/api/upload-image', formData)
  form.value.image_url = res.data.image_url
}

const submitPost = async () => {
  try {
    const res = await axios.post('/api/posts', form.value)
    alert('Post created!')
    console.log(res.data)
  } catch (e) {
    alert('Error occurred')
    console.error(e.response.data)
  }
}
</script>

<style scoped>
/* Add styles if needed */
</style>
