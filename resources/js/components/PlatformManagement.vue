<template>
  <div>
    <h2 class="text-2xl font-bold mb-4">Platform Management</h2>
    <div v-if="loading">Loading...</div>
    <table v-else class="min-w-full border">
      <thead>
        <tr>
          <th class="border p-2">ID</th>
          <th class="border p-2">Name</th>
          <th class="border p-2">Type</th>
          <th class="border p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="platform in platforms" :key="platform.id">
          <td class="border p-2">{{ platform.id }}</td>
          <td class="border p-2">{{ platform.name }}</td>
          <td class="border p-2">{{ platform.type }}</td>
          <td class="border p-2">
            <button @click="edit(platform)">Edit</button>
            <button @click="remove(platform.id)">Delete</button>
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
const loading = ref(true)

const fetchPlatforms = async () => {
  loading.value = true
  const token = localStorage.getItem('token')
  const res = await axios.get('/api/platforms', {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
  platforms.value = res.data
  loading.value = false
}

const edit = (platform) => {
  // فتح مودال التعديل
  alert(`Edit ${platform.name}`)
}

const remove = async (id) => {
  const confirmDelete = confirm('Are you sure?')
  if (!confirmDelete) return

  const token = localStorage.getItem('token')
  await axios.delete(`/api/platforms/${id}`, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
  await fetchPlatforms()
}

onMounted(fetchPlatforms)
</script>
