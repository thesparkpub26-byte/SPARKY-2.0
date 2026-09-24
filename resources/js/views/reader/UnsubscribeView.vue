<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <section class="not-found">
        <div class="not-found-code" aria-hidden="true">{{ state === 'done' ? '✓' : state === 'error' ? '!' : '…' }}</div>
        <h1 class="not-found-title">{{ state === 'done' ? 'You have been unsubscribed' : state === 'error' ? 'Unsubscribe link not valid' : 'Unsubscribing…' }}</h1>
        <p class="not-found-text">{{ message }}</p>
        <div class="not-found-actions">
          <router-link to="/" class="not-found-btn primary">Back to Home</router-link>
        </div>
      </section>

      <Footer />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';

const route = useRoute();
const state = ref('working'); // working | done | error
const message = ref('');

// Opening the link from the email is the request to unsubscribe
onMounted(async () => {
  try {
    const res = await fetch('/api/newsletter/unsubscribe', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({ token: route.params.token }),
    });
    const body = await res.json().catch(() => ({}));
    state.value = res.ok ? 'done' : 'error';
    message.value = body.message || (res.ok ? '' : 'Something went wrong. Please try again.');
  } catch {
    state.value = 'error';
    message.value = 'Could not connect to the server. Please try again.';
  }
});
</script>
