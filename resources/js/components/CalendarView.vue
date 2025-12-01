<template>
  <div class="border rounded-lg p-4 max-h-96 overflow-auto bg-white">
    <div v-if="posts.length === 0" class="text-center text-gray-500 py-8">
      No posts to display.
    </div>

    <div v-else class="space-y-4">
      <div
        v-for="(group, date) in groupedByDate"
        :key="date"
        class="border rounded-lg p-3 hover:bg-gray-50 transition-colors"
      >
        <div class="font-semibold text-gray-900 mb-2">{{ formatDate(date) }}</div>
        <div class="space-y-2">
          <div
            v-for="post in group"
            :key="post.id"
            class="flex justify-between items-center p-2 bg-gray-50 rounded"
          >
            <div class="flex-1">
              <div class="text-sm font-medium">{{ post.title }}</div>
              <div v-if="post.content" class="text-xs text-gray-600 truncate">
                {{ post.content.substring(0, 50) }}{{ post.content.length > 50 ? '...' : '' }}
              </div>
              <div class="text-xs text-gray-500 mt-1">
                {{ getPostTime(post) }}
              </div>
            </div>
            <StatusIndicator :status="post.status" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import StatusIndicator from './StatusIndicator.vue';

const props = defineProps({
  posts: {
    type: Array,
    default: () => []
  }
});

const parseDate = (dateString) => {
  if (!dateString) return null;
  
  if (dateString.includes('T')) {
    return new Date(dateString);
  } else if (dateString.includes(' ')) {
    return new Date(dateString.replace(' ', 'T'));
  } else {
    return new Date(dateString);
  }
};

const getPostTime = (post) => {
  const scheduleTime = parseDate(post.schedule_time);
  const createdTime = parseDate(post.created_at);
  
  if (scheduleTime) {
    return `Scheduled: ${scheduleTime.toLocaleString()}`;
  } else if (createdTime) {
    return `Created: ${createdTime.toLocaleString()}`;
  }
  return 'No date';
};

const groupedByDate = computed(() => {
  return props.posts.reduce((groups, post) => {
    let dateToUse;
    
    if (post.schedule_time) {
      dateToUse = parseDate(post.schedule_time);
    } else if (post.created_at) {
      dateToUse = parseDate(post.created_at);
    } else {
      dateToUse = new Date();
    }
    
    const dateKey = dateToUse.toDateString();
    
    if (!groups[dateKey]) {
      groups[dateKey] = [];
    }
    groups[dateKey].push(post);
    
    return groups;
  }, {});
});

const formatDate = (dateString) => {
  const date = new Date(dateString);
  const today = new Date();
  const tomorrow = new Date(today);
  tomorrow.setDate(tomorrow.getDate() + 1);
  
  if (date.toDateString() === today.toDateString()) {
    return 'Today';
  } else if (date.toDateString() === tomorrow.toDateString()) {
    return 'Tomorrow';
  } else {
    return date.toLocaleDateString('en-US', { 
      weekday: 'short', 
      month: 'short', 
      day: 'numeric',
      year: 'numeric'
    });
  }
};
</script>