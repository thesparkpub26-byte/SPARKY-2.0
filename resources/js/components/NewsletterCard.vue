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
          <input type="email" v-model="email" placeholder="Enter your email" class="newsletter-input" required>
          <button type="submit" class="btn-subscribe">Subscribe</button>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

const email = ref('');
const handleSubscribe = () => {
  if (email.value) {
    alert(`Thank you for subscribing with ${email.value}!`);
    email.value = '';
  }
};
</script>
