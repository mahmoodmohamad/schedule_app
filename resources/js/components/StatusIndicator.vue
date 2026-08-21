<template>
  <span class="status-pill" :class="`status-${normalizedStatus}`" :aria-label="`Status: ${label}`">{{ label }}</span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({ status: { type: String, default: 'unknown' } });
const normalizedStatus = computed(() => {
  const value = String(props.status || 'unknown').toLowerCase();
  return ['scheduled', 'published', 'draft', 'failed'].includes(value) ? value : 'unknown';
});
const label = computed(() => normalizedStatus.value === 'unknown' ? 'Unknown' : normalizedStatus.value);
</script>