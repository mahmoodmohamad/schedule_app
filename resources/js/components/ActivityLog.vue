<template>
  <div class="p-4 bg-white rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">User Activity Log</h2>
    <ul v-if="activities.length" class="list-disc pl-5 space-y-2">
      <li v-for="activity in activities" :key="activity.id" class="text-gray-700">
        <span class="text-sm text-gray-500">{{ formatDate(activity.created_at) }}</span> - 
        {{ activity.description }}
      </li>
    </ul>
    <p v-else>No activity found.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const activities = ref([])

const fetchActivities = async () => {
  try {
    const response = await fetch('/api/activity-logs', {
      headers: {
        'Accept': 'application/json',
      },
      credentials: 'include', // send cookies for auth
    })
    const data = await response.json()
    activities.value = data.activities
  } catch (error) {
    console.error('Failed to fetch activities:', error)
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleString()
}

onMounted(fetchActivities)
</script>
