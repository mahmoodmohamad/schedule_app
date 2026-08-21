<template>
  <div class="page">
    <header class="page-header">
      <div>
        <p class="eyebrow">Content studio</p>
        <h1 class="page-title">Create a post</h1>
        <p class="page-subtitle">Shape your message, choose where it should go, and decide when it should be published.</p>
      </div>
      <router-link class="button button-secondary" to="/dashboard">← Back to dashboard</router-link>
    </header>

    <div class="form-layout">
      <section class="surface form-card">
        <div v-if="error" class="alert alert-danger" role="alert" style="margin-bottom: 20px">{{ error }}</div>
        <div v-if="success" class="alert alert-success" role="status" style="margin-bottom: 20px">{{ success }}</div>
        <form @submit.prevent="submitPost">
          <div class="field">
            <label class="field-label" for="title"><span>Post title</span><span class="field-hint">{{ form.title.length }}/255</span></label>
            <input id="title" v-model.trim="form.title" class="form-control" maxlength="255" type="text" placeholder="Give your post a clear working title" required />
          </div>
          <div class="field">
            <label class="field-label" for="content"><span>Content</span><span class="field-hint">{{ form.content.length }}/1000</span></label>
            <textarea id="content" v-model.trim="form.content" class="form-control textarea" maxlength="1000" placeholder="Write the message your audience should see…" required></textarea>
          </div>
          <div class="field">
            <label class="field-label" for="platforms">Publishing platforms</label>
            <select id="platforms" v-model="form.platform_ids" class="form-control select-multiple" multiple :disabled="platformsLoading">
              <option v-for="platform in platforms" :key="platform.id" :value="platform.id">{{ platform.name }} · {{ platform.type }}</option>
            </select>
            <p class="section-caption">{{ platformsLoading ? 'Loading your connected platforms…' : platforms.length ? 'Use Ctrl/Cmd to select more than one.' : 'No platforms yet. Add one in Platform settings.' }}</p>
          </div>
          <div class="field">
            <label class="field-label" for="image"><span>Media</span><span class="field-hint">Optional</span></label>
            <div class="upload-zone">
              <input id="image" type="file" accept="image/*" @change="handleImageUpload" />
              <p v-if="uploading">Uploading image…</p>
              <p v-else>Choose an image to add a visual layer to your post.</p>
              <img v-if="form.image_url" class="image-preview" :src="form.image_url" alt="Uploaded post preview" />
            </div>
          </div>
          <div class="form-actions">
            <router-link class="button button-ghost" to="/dashboard">Cancel</router-link>
            <button class="button button-primary" type="submit" :disabled="submitting || uploading || !form.title || !form.content">
              {{ submitting ? 'Creating post…' : 'Create post' }}
            </button>
          </div>
        </form>
      </section>

      <aside class="surface side-summary">
        <p class="eyebrow">Publishing details</p>
        <h2 class="section-title">A quick final check</h2>
        <div class="summary-list">
          <div class="summary-row"><span>Status</span><strong>{{ form.status }}</strong></div>
          <div class="summary-row"><span>Platforms</span><strong>{{ form.platform_ids.length || 'None selected' }}</strong></div>
          <div class="summary-row"><span>Schedule</span><strong>{{ form.schedule_time ? 'Scheduled' : 'Manual' }}</strong></div>
        </div>
        <div class="field" style="margin-top: 24px">
          <label class="field-label" for="schedule">Schedule time</label>
          <input id="schedule" v-model="form.schedule_time" class="form-control" type="datetime-local" />
        </div>
        <div class="field">
          <label class="field-label" for="status">Post status</label>
          <select id="status" v-model="form.status" class="form-control">
            <option value="draft">Draft</option>
            <option value="scheduled">Scheduled</option>
            <option value="published">Published</option>
          </select>
        </div>
      </aside>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const form = ref({ title: '', content: '', image_url: '', platform_ids: [], schedule_time: '', status: 'draft' });
const platforms = ref([]);
const platformsLoading = ref(true);
const uploading = ref(false);
const submitting = ref(false);
const error = ref('');
const success = ref('');

const loadPlatforms = async () => {
  platformsLoading.value = true;
  try {
    const response = await axios.get('/api/platforms');
    platforms.value = response.data.platforms || [];
  } catch (err) {
    console.error('Failed to load platforms:', err);
    error.value = 'We could not load your platforms. You can still save a draft and configure them later.';
  } finally {
    platformsLoading.value = false;
  }
};

const handleImageUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;
  uploading.value = true;
  error.value = '';
  try {
    const body = new FormData();
    body.append('image', file);
    const response = await axios.post('/api/upload-image', body, { headers: { 'Content-Type': 'multipart/form-data' } });
    form.value.image_url = response.data.image_url;
  } catch (err) {
    console.error('Image upload failed:', err);
    error.value = 'The image could not be uploaded. Please try another file.';
  } finally {
    uploading.value = false;
  }
};

const submitPost = async () => {
  submitting.value = true;
  error.value = '';
  success.value = '';
  try {
    await axios.post('/api/posts', form.value);
    success.value = 'Post created. Taking you back to your workspace…';
    window.setTimeout(() => router.push('/dashboard'), 650);
  } catch (err) {
    console.error('Failed to create post:', err);
    error.value = err.response?.data?.message || 'The post could not be created. Please check the form and try again.';
  } finally {
    submitting.value = false;
  }
};

onMounted(loadPlatforms);
</script>
