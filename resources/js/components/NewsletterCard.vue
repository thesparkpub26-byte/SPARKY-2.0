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
        <template v-if="isLoggedIn">
          <p class="newsletter-feedback">You're already getting our latest stories as a member — no need to subscribe separately.</p>
        </template>
        <template v-else>
          <form class="newsletter-form" @submit.prevent="handleSubscribe">
            <input type="email" v-model="email" placeholder="Enter your email" class="newsletter-input" maxlength="255" required>
            <button type="submit" class="btn-subscribe">Subscribe</button>
          </form>
          <p class="newsletter-feedback">We'll take you to sign up so your stories land straight in your inbox.</p>
        </template>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const email = ref('');
const isLoggedIn = !!localStorage.getItem('sparky_token');

const handleSubscribe = () => {
  const trimmed = email.value.trim();
  if (!trimmed) return;

  router.push({ path: '/signup', query: { email: trimmed } });
};
</script>
