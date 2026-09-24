<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <section class="popular-section" style="margin-top: 32px; width: 100%;">
        <h1 class="section-headline">Videos</h1>
        <p class="section-subtext">Watch what our broadcasting team made</p>

        <p v-if="loading" class="section-empty">Loading videos…</p>
        <p v-else-if="!videos.length" class="section-empty">No videos published yet.</p>
        <div v-else class="articles-grid">
          <ArticleCard v-for="video in videos" :key="video.id" :article="video" />
        </div>
      </section>

      <!-- Subscribe To Our Newsletter Section -->
      <NewsletterCard />

      <!-- Footer Section -->
      <Footer />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import ArticleCard from '../../components/ArticleCard.vue';

const videos = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const res = await fetch('/api/reader/videos', { headers: { Accept: 'application/json' } });
    if (res.ok) videos.value = await res.json();
  } catch {
    // Falls through to the empty state.
  } finally {
    loading.value = false;
  }
});
</script>
