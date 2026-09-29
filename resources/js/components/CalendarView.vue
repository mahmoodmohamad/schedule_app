<template>
  <div class="cal">
    <div v-if="groups.length === 0" class="state">
      <strong>Nothing coming up</strong>
      <p>Scheduled posts will appear here.</p>
    </div>
    <div v-else v-for="group in groups" :key="group.key" class="cal-day">
      <p class="cal-date">{{ group.label }}</p>
      <div v-for="post in group.posts" :key="post.id" class="cal-item">
        <div class="cal-info">
          <p class="table-title">{{ post.title || 'Untitled post' }}</p>
          <p class="section-caption">{{ formatTime(post.date) }}</p>
        </div>
        <StatusIndicator :status="post.status" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import StatusIndicator from './StatusIndicator.vue';

const props = defineProps({ posts: { type: Array, default: () => [] } });

const parse = (value) => {
  if (!value) return null;
  const s = String(value);
  const d = new Date(s.includes(' ') && !s.includes('T') ? s.replace(' ', 'T') : s);
  return Number.isNaN(d.getTime()) ? null : d;
};

const label = (date) => {
  const today = new Date();
  const tomorrow = new Date(today);
  tomorrow.setDate(today.getDate() + 1);
  if (date.toDateString() === today.toDateString()) return 'Today';
  if (date.toDateString() === tomorrow.toDateString()) return 'Tomorrow';
  return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
};

const formatTime = (date) =>
  date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

const groups = computed(() => {
  const startOfToday = new Date();
  startOfToday.setHours(0, 0, 0, 0);

  const upcoming = props.posts
    .map((post) => ({ ...post, date: parse(post.schedule_time) }))
    .filter((post) => post.date && post.date >= startOfToday)
    .sort((a, b) => a.date - b.date);

  const map = new Map();
  for (const post of upcoming) {
    const key = post.date.toDateString();
    if (!map.has(key)) map.set(key, { key, label: label(post.date), posts: [] });
    map.get(key).posts.push(post);
  }
  return [...map.values()].slice(0, 7);
});
</script>

<style scoped>
.cal { display: grid; gap: 18px; padding: 4px 22px 22px; }
.cal-date { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin: 0 0 8px; opacity: .6; }
.cal-item { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 10px 0; border-top: 1px solid rgba(128, 128, 128, .18); }
.cal-info { min-width: 0; }
.cal-info p { margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
</style>