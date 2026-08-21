<template>
  <div class="auth-page">
    <section class="auth-visual">
      <div class="auth-logo"><span class="brand-mark">S</span><span>Schedule</span></div>
      <div>
        <p class="eyebrow" style="color: #bfc0ff">Your publishing workspace</p>
        <h1>Make every post feel intentional.</h1>
        <p>Bring your content calendar, platform connections, and publishing workflow into one focused place.</p>
      </div>
      <p class="auth-quote">A clearer way to move from idea to published.</p>
    </section>

    <section class="auth-panel">
      <div class="auth-form">
        <p class="eyebrow">Welcome back</p>
        <h1 class="page-title">Sign in to Schedule</h1>
        <p class="page-subtitle">Access your content workspace and keep your publishing queue moving.</p>

        <div v-if="error" class="alert alert-danger" role="alert" style="margin-bottom: 18px">{{ error }}</div>

        <form @submit.prevent="login" novalidate>
          <div class="field">
            <label class="field-label" for="email">Email address</label>
            <input id="email" v-model.trim="email" class="form-control" type="email" autocomplete="email" placeholder="you@example.com" required />
          </div>
          <div class="field">
            <label class="field-label" for="password">Password</label>
            <input id="password" v-model="password" class="form-control" type="password" autocomplete="current-password" placeholder="Enter your password" required />
          </div>
          <button class="button button-primary" type="submit" :disabled="loading || !email || !password">
            {{ loading ? 'Signing you in…' : 'Sign in' }}
          </button>
        </form>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const email = ref('');
const password = ref('');
const loading = ref(false);
const error = ref('');

const login = async () => {
  if (!email.value || !password.value) return;
  loading.value = true;
  error.value = '';
  try {
    const response = await axios.post('/api/login', { email: email.value, password: password.value });
    localStorage.setItem('token', response.data.access_token);
    localStorage.setItem('user', JSON.stringify(response.data.user));
    router.push('/dashboard');
  } catch (err) {
    console.error('Login error:', err);
    error.value = err.response?.data?.message || 'We could not sign you in. Check your details and try again.';
  } finally {
    loading.value = false;
  }
};
</script>
