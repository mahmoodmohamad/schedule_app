<template>
  <div class="page">
    <header class="page-header">
      <div>
        <p class="eyebrow">Workspace settings</p>
        <h1 class="page-title">Connected platforms</h1>
        <p class="page-subtitle">Manage the destinations available when you create and schedule content.</p>
      </div>
      <router-link class="button button-secondary" to="/dashboard">← Back to dashboard</router-link>
    </header>

    <div class="form-layout">
      <section class="surface form-card">
        <div class="surface-header" style="margin: -22px -22px 24px">
          <div><h2 class="section-title">Add a platform</h2><p class="section-caption">Keep your publishing destinations organized.</p></div>
          <span class="stat-icon">＋</span>
        </div>
        <div v-if="error" class="alert alert-danger" role="alert" style="margin-bottom: 18px">{{ error }}</div>
        <div v-if="success" class="alert alert-success" role="status" style="margin-bottom: 18px">{{ success }}</div>
        <form @submit.prevent="createPlatform">
          <div class="field">
            <label class="field-label" for="platform-name">Platform name</label>
            <input id="platform-name" v-model.trim="newPlatform.name" class="form-control" type="text" placeholder="e.g. LinkedIn or Company blog" required />
          </div>
          <div class="field">
            <label class="field-label" for="platform-type">Platform type</label>
            <select id="platform-type" v-model="newPlatform.type" class="form-control" required>
              <option value="">Choose a type</option>
              <option value="social">Social media</option>
              <option value="blog">Blog</option>
              <option value="news">News</option>
              <option value="other">Other</option>
            </select>
          </div>
          <button class="button button-primary" type="submit" :disabled="creating || !newPlatform.name || !newPlatform.type">
            {{ creating ? 'Adding platform…' : 'Add platform' }}
          </button>
        </form>
      </section>

      <section class="surface">
        <div class="surface-header">
          <div><h2 class="section-title">Your platforms</h2><p class="section-caption">{{ platforms.length }} connected destination{{ platforms.length === 1 ? '' : 's' }}.</p></div>
        </div>
        <div v-if="loading" class="state"><strong>Loading platforms</strong><p>Checking your connected destinations.</p></div>
        <div v-else-if="platforms.length === 0" class="state"><strong>No platforms yet</strong><p>Add your first destination to make posts publishable.</p></div>
        <div v-else class="table-wrap">
          <table class="data-table">
            <thead><tr><th>Platform</th><th>Type</th><th>Added</th><th><span class="sr-only">Actions</span></th></tr></thead>
            <tbody>
              <tr v-for="platform in platforms" :key="platform.id">
                <td><p class="table-title">{{ platform.name }}</p></td>
                <td><span class="status-pill status-draft">{{ platform.type }}</span></td>
                <td class="section-caption">{{ formatDate(platform.created_at) }}</td>
                <td style="text-align: right"><button class="button button-danger" type="button" :disabled="deletingId === platform.id" @click="deletePlatform(platform.id)">{{ deletingId === platform.id ? 'Deleting…' : 'Delete' }}</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';

const platforms = ref([]);
const newPlatform = ref({ name: '', type: '' });
const loading = ref(true);
const creating = ref(false);
const deletingId = ref(null);
const error = ref('');
const success = ref('');

const fetchPlatforms = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/platforms');
    platforms.value = response.data.platforms || [];
  } catch (err) {
    console.error('Failed to load platforms:', err);
    error.value = 'We could not load your platforms. Please try again.';
  } finally {
    loading.value = false;
  }
};

const createPlatform = async () => {
  creating.value = true;
  error.value = '';
  success.value = '';
  try {
    const response = await axios.post('/api/platforms', newPlatform.value);
    platforms.value.push(response.data.platform);
    newPlatform.value = { name: '', type: '' };
    success.value = 'Platform added successfully.';
  } catch (err) {
    console.error('Failed to create platform:', err);
    error.value = err.response?.data?.message || 'The platform could not be added.';
  } finally {
    creating.value = false;
  }
};

const deletePlatform = async (id) => {
  if (!window.confirm('Delete this platform? Existing posts will keep their records, but it will no longer be available for new posts.')) return;
  deletingId.value = id;
  error.value = '';
  try {
    await axios.delete(`/api/platforms/${id}`);
    platforms.value = platforms.value.filter((platform) => platform.id !== id);
    success.value = 'Platform deleted.';
  } catch (err) {
    console.error('Failed to delete platform:', err);
    error.value = 'The platform could not be deleted. Please try again.';
  } finally {
    deletingId.value = null;
  }
};

const formatDate = (value) => value ? new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric', year: 'numeric' }).format(new Date(value)) : '—';
onMounted(fetchPlatforms);
</script>

<style scoped>
.sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }
</style>
