<template>
  <div class="bg-white border rounded-lg">
    <div v-if="posts.length === 0" class="text-center text-gray-500 py-8">
      No posts found.
    </div>

    <div v-else class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule Time</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="post in posts" :key="post.id" class="hover:bg-gray-50">
            <td class="px-4 py-4">
              <div class="text-sm font-medium text-gray-900">{{ post.title }}</div>
              <div v-if="post.content" class="text-sm text-gray-600 truncate max-w-xs">
                {{ post.content.substring(0, 60) }}{{ post.content.length > 60 ? '...' : '' }}
              </div>
            </td>
            <td class="px-4 py-4">
              <StatusIndicator :status="post.status" />
            </td>
            <td class="px-4 py-4 text-sm text-gray-900">
              {{ formatDateTime(post.schedule_time) || 'Not scheduled' }}
            </td>
            <td class="px-4 py-4 text-sm text-gray-900">
              {{ formatDateTime(post.created_at) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import StatusIndicator from './StatusIndicator.vue';

const props = defineProps({
  posts: {
    type: Array,
    default: () => []
  }
});

const formatDateTime = (dateString) => {
  if (!dateString) return null;
  
  let date;
  if (dateString.includes('T')) {
    date = new Date(dateString);
  } else if (dateString.includes(' ')) {
    date = new Date(dateString.replace(' ', 'T'));
  } else {
    date = new Date(dateString);
  }
  
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};
</script>