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
    required: true
  }
});

const statusMap = {
  scheduled: { label: 'Scheduled', class: 'bg-blue-500' },
  published: { label: 'Published', class: 'bg-green-500' },
  draft: { label: 'Draft', class: 'bg-gray-500' },
  failed: { label: 'Failed', class: 'bg-red-500' }
};

const statusLabel = computed(() => 
  statusMap[props.status]?.label || 'Unknown'
);

const statusClass = computed(() => 
  statusMap[props.status]?.class || 'bg-gray-400'
);
</script>