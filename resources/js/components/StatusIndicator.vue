<template>
  <span
    :class="statusClass"
    class="px-2 py-1 rounded text-white text-xs font-semibold"
  >
    {{ statusLabel }}
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  status: {
    type: String,
    required: false,
    default: 'unknown'
  }
});

const statusMap = {
  scheduled: { label: 'Scheduled', class: 'bg-blue-500' },
  published: { label: 'Published', class: 'bg-green-500' },
  draft: { label: 'Draft', class: 'bg-gray-500' },
  failed: { label: 'Failed', class: 'bg-red-500' },
  unknown: { label: 'Unknown', class: 'bg-gray-400' }
};

const statusLabel = computed(() => {
  const status = props.status?.toLowerCase() || 'unknown';
  return statusMap[status]?.label || 'Unknown';
});

const statusClass = computed(() => {
  const status = props.status?.toLowerCase() || 'unknown';
  return statusMap[status]?.class || 'bg-gray-400';
});
</script>