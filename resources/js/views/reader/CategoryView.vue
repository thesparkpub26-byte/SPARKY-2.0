<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <!-- 2 Column Category & Sidebar Grid -->
      <div class="category-main-grid" style="margin-top: 32px;">
        <!-- Left Category Articles Column -->
        <div class="category-content-wrapper">
          <h1 class="category-title-header">{{ category || 'Latest Articles' }}</h1>

          <SkeletonCards v-if="loading" variant="list" :count="5" />
          <p v-else-if="!articles.length" class="section-empty">No published articles in this category yet.</p>

          <!-- Horizontal Cards List -->
          <div v-else class="category-articles-list">
            <router-link
              v-for="item in articles"
              :key="item.id"
              :to="`/article/${item.id}`"
              class="category-article-card"
            >
              <div class="category-card-img-wrapper">
                <img :src="item.image || fallbackImage" :alt="item.title" loading="lazy" decoding="async">
              </div>
              <div class="category-card-body">
                <h3 class="category-card-title">{{ item.title }}</h3>
                <p class="category-card-excerpt">{{ item.excerpt }}</p>
                <div class="category-card-footer">
                  <span class="category-card-stats">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                      <line x1="16" y1="2" x2="16" y2="6" />
                      <line x1="8" y1="2" x2="8" y2="6" />
                      <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <template v-if="!category && item.badge">{{ item.badge }} &nbsp;|&nbsp; </template>{{ item.date }}
                  </span>
                  <span class="sidebar-read-more">Read More</span>
                </div>
              </div>
            </router-link>
          </div>

          <!-- Pagination Capsule Bar -->
          <div v-if="lastPage > 1" class="pagination-wrapper">
            <nav class="pagination-capsule">
              <a href="#" :class="['pagination-btn', { disabled: page === 1 }]" @click.prevent="goToPage(page - 1)">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="m15 18-6-6 6-6"/>
                </svg>
                Previous
              </a>
              <a
                v-for="p in pageNumbers"
                :key="p"
                href="#"
                :class="['pagination-btn', { active: page === p }]"
                @click.prevent="goToPage(p)"
              >{{ p }}</a>
              <a href="#" :class="['pagination-btn', { disabled: page === lastPage }]" @click.prevent="goToPage(page + 1)">
                Next
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="m9 18 6-6-6-6"/>
                </svg>
              </a>
            </nav>
          </div>
        </div>

        <!-- Right Sidebar: Popular Now (5 latest articles) -->
        <PopularSidebar />
      </div>

      <!-- Subscribe To Our Newsletter Section -->
      <NewsletterCard />

      <!-- Footer Section -->
      <Footer />
    </main>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Navbar from '../../components/Navbar.vue';
import SkeletonCards from '../../components/SkeletonCards.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import PopularSidebar from '../../components/PopularSidebar.vue';

const route = useRoute();
const router = useRouter();

const fallbackImage = '/images/hero_banner.jpg';

const articles = ref([]);
const loading = ref(true);
const lastPage = ref(1);

// /categories?category=News&page=2 — no category means the latest articles across all of them
const category = computed(() => String(route.query.category || ''));
const page = computed(() => Math.max(1, parseInt(route.query.page, 10) || 1));

// Up to 5 page buttons, centred on the current page
const pageNumbers = computed(() => {
  const start = Math.max(1, Math.min(page.value - 2, lastPage.value - 4));
  const end = Math.min(lastPage.value, start + 4);
  return Array.from({ length: end - start + 1 }, (_, i) => start + i);
});

const scrollToTop = () => document.querySelector('.reader-page')?.scrollTo({ top: 0 });

const loadArticles = async () => {
  loading.value = true;
  try {
    const params = new URLSearchParams({ page: page.value });
    if (category.value) params.set('category', category.value);
    const res = await fetch(`/api/reader/category-articles?${params}`, { headers: { Accept: 'application/json' } });
    if (res.ok) {
      const body = await res.json();
      articles.value = body.data;
      lastPage.value = body.last_page;
    } else {
      articles.value = [];
      lastPage.value = 1;
    }
  } catch {
    articles.value = [];
    lastPage.value = 1;
  } finally {
    loading.value = false;
  }
};

const goToPage = (p) => {
  if (p < 1 || p > lastPage.value || p === page.value) return;
  router.push({ path: '/categories', query: { ...route.query, page: p } });
};

watch([category, page], () => {
  loadArticles();
  scrollToTop();
});

onMounted(() => {
  loadArticles();
});
</script>
