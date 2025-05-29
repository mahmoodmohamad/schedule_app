<template>
  <div class="max-w-4xl mx-auto mt-6 space-y-4">
    <h2 class="text-xl font-bold">Posts List</h2>

    <table v-if="posts.length > 0" class="w-full border border-gray-200">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 text-left">ID</th>
          <th class="p-2 text-left">Title</th>
          <th class="p-2 text-left">Status</th>
          <th class="p-2 text-left">Schedule Time</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(post, index) in posts" :key="post.id" class="border-t">
          <td class="p-2">{{ index + 1 }}</td> 
          <td class="p-2">{{ post.title }}</td>
          <td class="p-2">{{ post.status }}</td>
          <td class="p-2">{{ post.schedule_time }}</td>
        </tr>
      </tbody>
    </table>

    <div v-else class="text-gray-500">No posts found.</div>

    <div v-if="loading" class="text-gray-500">Loading...</div>
    <div v-if="error" class="text-red-500">Failed to load posts: {{ error }}</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const posts = ref([])
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
  try {
    const res = await axios.get('/api/posts')
    posts.value = res.data.posts
  } catch (err) {
    console.error(err)
    error.value = err.response?.data?.message || 'Error fetching posts'
  } finally {
    loading.value = false
  }
})
</script>
