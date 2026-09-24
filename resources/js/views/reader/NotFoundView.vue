<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <section class="not-found">
        <div class="not-found-code">404</div>
        <h1 class="not-found-title">We couldn't find that page</h1>
        <p class="not-found-text">The link may be broken, or the page may have been moved or removed.</p>
        <div class="not-found-actions">
          <router-link to="/" class="not-found-btn primary">Back to Home</router-link>
          <router-link to="/categories" class="not-found-btn">Browse Articles</router-link>
        </div>
      </section>

      <Footer />
    </main>
  </div>
</template>

<script setup>
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';
</script>
