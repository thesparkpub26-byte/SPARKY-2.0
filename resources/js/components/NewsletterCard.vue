<template>
  <section class="newsletter-section">
    <div class="newsletter-card">
      <div class="newsletter-left">
        <h2 class="newsletter-title">Subscribe To Our Newsletter</h2>
        <p class="newsletter-desc">
          Be the first to know about the latest stories, special issues, and publication highlights. Stay connected with insights, features, and updates that matter to community.
        </p>
      </div>
      <div class="newsletter-right">
        <h4 class="newsletter-stay-updated">Stay up to date</h4>
        <form class="newsletter-form" @submit.prevent="handleSubscribe">
          <input type="email" v-model="email" placeholder="Enter your email" class="newsletter-input" maxlength="255" required>
          <button type="submit" class="btn-subscribe" :disabled="loading">{{ loading ? 'Subscribing…' : 'Subscribe' }}</button>
        </form>
        <p v-if="feedback" :class="['newsletter-feedback', { error: isError }]" role="status">{{ feedback }}</p>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

const email = ref('');
const loading = ref(false);
const feedback = ref('');
const isError = ref(false);

const handleSubscribe = async () => {
  if (!email.value.trim() || loading.value) return;

  loading.value = true;
  feedback.value = '';
  try {
    const res = await fetch('/api/newsletter/subscribe', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({ email: email.value.trim() }),
    });
    const body = await res.json().catch(() => ({}));

    isError.value = !res.ok;
    feedback.value = res.ok
      ? body.message
      : (body.errors?.email?.[0] || (res.status === 429 ? 'Too many attempts. Please try again in a minute.' : body.message) || 'Could not subscribe. Please try again.');
    if (res.ok) email.value = '';
  } catch {
    isError.value = true;
    feedback.value = 'Could not connect to the server. Please try again.';
  } finally {
    loading.value = false;
  }
};
</script>
