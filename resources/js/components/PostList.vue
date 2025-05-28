<template>
  <div class="border rounded p-4 max-h-[400px] overflow-auto">
    <table class="w-full table-auto">
      <thead>
        <tr class="bg-gray-100">
          <th class="text-left px-3 py-2">Title</th>
          <th class="text-left px-3 py-2">Date</th>
          <th class="text-left px-3 py-2">Status</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="post in posts"
          :key="post.id"
          class="hover:bg-gray-50 cursor-pointer"
        >
          <td class="px-3 py-2">{{ post.title }}</td>
          <td class="px-3 py-2">{{ post.date }}</td>
          <td class="px-3 py-2">
            <StatusIndicator :status="post.status" />
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="posts.length === 0" class="text-center text-gray-500 mt-4">
      No posts to show.
    </div>
  </div>
</template>

<script setup>
import StatusIndicator from './StatusIndicator.vue'

const props = defineProps({
  posts: {
    type: Array,
    default: () => [],
  },
})
</script>
