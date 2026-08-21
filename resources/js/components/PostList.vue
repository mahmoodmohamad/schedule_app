<template>
  <div class="table-wrap">
    <div v-if="posts.length === 0" class="state">
      <strong>No posts match this view</strong>
      <p>Try changing your filters or create a new post to build your queue.</p>
      <router-link class="button button-primary" to="/editor">Create post</router-link>
    </div>
    <table v-else class="data-table">
      <thead>
        <tr><th>Post</th><th>Status</th><th>Schedule</th><th>Created</th></tr>
      </thead>
      <tbody>
        <tr v-for="post in posts" :key="post.id">
          <td>
            <p class="table-title">{{ post.title || 'Untitled post' }}</p>
            <p v-if="post.content" class="table-description">{{ post.content }}</p>
          </td>
          <td><StatusIndicator :status="post.status" /></td>
          <td class="section-caption">{{ formatDateTime(post.schedule_time) || 'Not scheduled' }}</td>
          <td class="section-caption">{{ formatDateTime(post.created_at) || '—' }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import StatusIndicator from './StatusIndicator.vue';

defineProps({ posts: { type: Array, default: () => [] } });

const formatDateTime = (dateString) => {
  if (!dateString) return null;
  const normalized = String(dateString).includes(' ') && !String(dateString).includes('T')
    ? String(dateString).replace(' ', 'T')
    : dateString;
  const date = new Date(normalized);
  if (Number.isNaN(date.getTime())) return null;
  return new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' }).format(date);
};
</script>
