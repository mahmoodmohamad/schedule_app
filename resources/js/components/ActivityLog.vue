<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const activities = ref([]);

const fetchActivities = async () => {
  try {
    const { data } = await axios.get('/api/activity-logs');
    activities.value = data.activities || [];
  } catch (error) {
    console.error('Failed to fetch activities:', error);
  }
};

const formatDate = (dateString) => new Date(dateString).toLocaleString();

onMounted(fetchActivities);
</script>