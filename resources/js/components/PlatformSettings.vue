<template>
  <div class="max-w-3xl mx-auto p-6 space-y-6">
    <h1 class="text-2xl font-bold">Platform Management</h1>

    <!-- Form لإضافة منصة -->
    <form @submit.prevent="createPlatform" class="flex items-center gap-2">
      <input v-model="newPlatform.name" type="text" placeholder="Platform Name" class="border p-2 rounded w-1/2" required />
      <input v-model="newPlatform.type" type="text" placeholder="Type" class="border p-2 rounded w-1/2" required />
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
    </form>

    <!-- جدول عرض المنصات -->
    <table class="w-full border mt-6">
      <thead>
        <tr class="bg-gray-100">
          <th class="border p-2">#</th>
          <th class="border p-2">Name</th>
          <th class="border p-2">Type</th>
          <th class="border p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(platform, index) in platforms" :key="platform.id">
          <td class="border p-2">{{ index + 1 }}</td>
          <td class="border p-2">{{ platform.name }}</td>
          <td class="border p-2">{{ platform.type }}</td>
          <td class="border p-2 space-x-2">
            <button @click="deletePlatform(platform.id)" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const platforms = ref([])
const newPlatform = ref({ name: '', type: '' })

// استرجاع المنصات من الـ API
const fetchPlatforms = async () => {
  const token = localStorage.getItem('token')
  console.log('Token:', token)
  try {
    const response = await axios.get('/api/platforms', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    console.log('Platforms API response:', response.data)
    platforms.value = response.data.platforms
  } catch (error) {
    console.error('Failed to load platforms:', error)
    alert('Failed to load platforms')
  }
}


const createPlatform = async () => {
  const token = localStorage.getItem('token')
  try {
    const response = await axios.post('/api/platforms', newPlatform.value, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    platforms.value.push(response.data.platform)
    newPlatform.value = { name: '', type: '' }
  } catch (error) {
    alert('Failed to create platform')
  }
}

const deletePlatform = async (id) => {
  const token = localStorage.getItem('token')
  try {
    await axios.delete(`/api/platforms/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    platforms.value = platforms.value.filter(p => p.id !== id)
  } catch (error) {
    alert('Failed to delete platform')
  }
}

onMounted(fetchPlatforms)
</script>
