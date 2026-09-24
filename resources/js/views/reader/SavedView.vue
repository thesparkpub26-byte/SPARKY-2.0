<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <section class="popular-section" style="margin-top: 32px; width: 100%;">
        <h1 class="section-headline">Saved Articles</h1>
        <p class="section-subtext">Stories you saved to read later</p>

        <SkeletonCards v-if="loading" :count="3" />
        <p v-else-if="error" class="section-empty">Could not load your saved articles. Please try again.</p>
        <p v-else-if="!articles.length" class="section-empty">
          You haven't saved anything yet. Open an article and tap <strong>Save</strong> to keep it here.
        </p>

        <div v-else class="articles-grid">
          <div v-for="item in articles" :key="item.id" class="saved-item">
            <ArticleCard :article="item" />
            <button type="button" class="saved-remove" @click="remove(item)">Remove from saved</button>
          </div>
        </div>

        <div v-if="page < lastPage" class="load-more-wrapper">
          <button type="button" class="btn-load-more btn-load-more-btn" :disabled="loadingMore" @click="loadMore">{{ loadingMore ? 'Loading…' : 'Load More' }}</button>
        </div>
      </section>

      <NewsletterCard />
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

const articles = ref([]);
const loading = ref(true);
const loadingMore = ref(false);
const error = ref(false);
const page = ref(0);
const lastPage = ref(1);

const headers = () => ({ Accept: 'application/json', Authorization: `Bearer ${localStorage.getItem('sparky_token')}` });

const loadPage = async (next) => {
  const res = await fetch(`/api/reader/bookmarks?page=${next}`, { headers: headers() });
  if (!res.ok) throw new Error('failed');
  const body = await res.json();
  articles.value = next === 1 ? body.data : [...articles.value, ...body.data];
  page.value = body.current_page;
  lastPage.value = body.last_page;
};

const loadMore = async () => {
  loadingMore.value = true;
  try {
    await loadPage(page.value + 1);
  } catch {
    error.value = true;
  } finally {
    loadingMore.value = false;
  }
};

const remove = async (item) => {
  try {
    const res = await fetch(`/api/reader/articles/${item.id}/bookmark`, { method: 'DELETE', headers: headers() });
    if (res.ok) articles.value = articles.value.filter((a) => a.id !== item.id);
  } catch {
    // The card stays; they can try again.
  }
};

onMounted(async () => {
  try {
    await loadPage(1);
  } catch {
    error.value = true;
  } finally {
    loading.value = false;
  }
});
</script>
