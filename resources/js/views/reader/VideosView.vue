<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <section class="popular-section" style="margin-top: 32px; width: 100%;">
        <h1 class="section-headline">Videos</h1>
        <p class="section-subtext">Watch what our broadcasting team made</p>

        <SkeletonCards v-if="loading" :count="6" />
        <p v-else-if="!videos.length" class="section-empty">No videos published yet.</p>
        <div v-else class="articles-grid">
          <ArticleCard v-for="video in videos" :key="video.id" :article="video" />
        </div>
        <div v-if="page < lastPage" class="load-more-wrapper">
          <button type="button" class="btn-load-more btn-load-more-btn" :disabled="loadingMore" @click="loadMore">{{ loadingMore ? 'Loading…' : 'Load More' }}</button>
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
import SkeletonCards from '../../components/SkeletonCards.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import ArticleCard from '../../components/ArticleCard.vue';

const videos = ref([]);
const loading = ref(true);
const loadingMore = ref(false);
const page = ref(0);
const lastPage = ref(1);

// Videos come nine at a time; "Load More" appends the next nine
const loadPage = async (next) => {
  const res = await fetch(`/api/reader/videos?page=${next}`, { headers: { Accept: 'application/json' } });
  if (!res.ok) return;
  const body = await res.json();
  videos.value = next === 1 ? body.data : [...videos.value, ...body.data];
  page.value = body.current_page;
  lastPage.value = body.last_page;
};

const loadMore = async () => {
  loadingMore.value = true;
  try {
    await loadPage(page.value + 1);
  } catch {
    // Keep what is already showing; the button stays so they can try again.
  } finally {
    loadingMore.value = false;
  }
};

onMounted(async () => {
  try {
    await loadPage(1);
  } catch {
    // Falls through to the empty state.
  } finally {
    loading.value = false;
  }
});
</script>
