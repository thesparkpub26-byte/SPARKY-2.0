<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <!-- Hero Section: What's New Carousel -->
      <section class="hero-section">
        <h1 class="section-headline">What’s New</h1>
        <br>

        <!-- Full-Width Responsive Hero Carousel -->
        <div class="hero-carousel-container" @mouseenter="stopAutoPlay" @mouseleave="startAutoPlay">
          <div v-if="!slidesLoaded" class="carousel-skeleton skeleton skeleton-dark" role="status" aria-label="Loading"></div>
          <div v-else-if="!slides.length" class="carousel-empty">No published articles yet.</div>
          <div v-else class="carousel-track" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
            <router-link v-for="(slide, idx) in slides" :key="slide.id" :to="`/article/${slide.id}`" class="carousel-slide">
              <img :src="slide.image || fallbackImage" :alt="slide.title" draggable="false" :loading="idx === 0 ? 'eager' : 'lazy'" :fetchpriority="idx === 0 ? 'high' : 'auto'" decoding="async">
              <div class="carousel-caption">
                <h3 class="carousel-caption-title">{{ slide.title }}</h3>
                <div class="carousel-caption-meta">
                  <span v-if="slide.author">By {{ slide.author }}</span>
                  <span>{{ formatDate(slide.published_at) }}</span>
                </div>
              </div>
            </router-link>
          </div>

          <!-- Navigation Arrow Overlay Buttons -->
          <button v-if="slides.length > 1" class="carousel-arrow carousel-arrow-prev" @click="prevSlide" title="Previous Slide">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="m15 18-6-6 6-6" />
            </svg>
          </button>
          <button v-if="slides.length > 1" class="carousel-arrow carousel-arrow-next" @click="nextSlide" title="Next Slide">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="m9 18 6-6-6-6" />
            </svg>
          </button>
        </div>

        <!-- Carousel Pagination Dots -->
        <div class="carousel-dots">
          <div 
            v-for="(slide, idx) in slides" 
            :key="idx" 
            :class="['dot', { active: currentSlide === idx }]"
            @click="goToSlide(idx)"
          ></div>
        </div>
      </section>

      <!-- Popular Now Section -->
      <section class="popular-section">
        <h2 class="section-headline">Popular now</h2>
        <p class="section-subtext">The most read from TheSPARK</p>

        <SkeletonCards v-if="!articlesLoaded" :count="3" />
        <p v-else-if="!popularArticles.length" class="section-empty">No published articles yet.</p>
        <div v-else class="articles-grid">
          <ArticleCard v-for="art in popularArticles" :key="art.id" :article="art" />
        </div>

        <!-- Load More Link (opens the Categories page showing the latest articles) -->
        <div v-if="popularArticles.length" class="load-more-wrapper">
          <router-link to="/categories" class="btn-load-more">Load More</router-link>
        </div>
      </section>

      <!-- Videos Section -->
      <section class="videos-section">
        <h2 class="section-headline">Check out our Videos</h2>
        <p class="section-subtext">Watch what our broadcasting team made</p>

        <SkeletonCards v-if="!videosLoaded" :count="3" />
        <p v-else-if="!homeVideos.length" class="section-empty">No videos published yet.</p>
        <div v-else class="articles-grid">
          <ArticleCard v-for="video in homeVideos" :key="video.id" :article="video" />
        </div>

        <!-- Load More Link -->
        <div v-if="homeVideos.length" class="load-more-wrapper">
          <router-link to="/videos" class="btn-load-more">Load More</router-link>
        </div>
      </section>

      <!-- Published Issues Section -->
      <section class="published-issues-section">
        <h2 class="section-headline">Published Issues</h2>
        <p class="section-subtext">The latest issues from TheSPARK</p>

        <SkeletonCards v-if="!issuesLoaded" variant="issue" :count="3" />
        <p v-else-if="!homeIssues.length" class="section-empty">No published issues yet.</p>
        <div v-else class="issues-grid">
          <IssueCard v-for="item in homeIssues" :key="item.id" :issue="item" @explore="openIssue" />
        </div>

        <!-- Load More Link -->
        <div v-if="homeIssues.length" class="load-more-wrapper">
          <router-link to="/issues" class="btn-load-more">Load More</router-link>
        </div>
      </section>

      <!-- Artists Gallery Section -->
      <section class="artists-gallery-section">
        <h2 class="section-headline">Artists Gallery</h2>
        <p class="section-subtext">Check out what our artists made!</p>

        <SkeletonCards v-if="!galleryLoaded" variant="strip" :count="3" />
        <p v-else-if="!galleryPhotos.length" class="section-empty">No photos uploaded yet.</p>
        <template v-else>
          <div class="gallery-carousel-wrapper">
            <div class="gallery-cards-row">
              <div v-for="photo in galleryPhotos" :key="photo.id" class="gallery-card" @click="openModal(photo)">
                <img :src="photo.image" :alt="photo.title" loading="lazy" decoding="async">
                <div class="photo-hover-title"><span>{{ photo.title }}</span></div>
              </div>
            </div>
          </div>

          <!-- Load More Link -->
          <div class="load-more-wrapper">
            <router-link to="/gallery" class="btn-load-more">Load More</router-link>
          </div>
        </template>
      </section>

      <!-- Subscribe To Our Newsletter Section -->
      <NewsletterCard />

      <!-- Footer Section -->
      <Footer />
    </main>

    <LightboxModal
      :isOpen="isModalOpen"
      :imageSrc="selectedPhoto.image"
      :title="selectedPhoto.title"
      :artist="selectedPhoto.artist"
      :date="selectedPhoto.date"
      @close="isModalOpen = false"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import SkeletonCards from '../../components/SkeletonCards.vue';
import ArticleCard from '../../components/ArticleCard.vue';
import IssueCard from '../../components/IssueCard.vue';
import LightboxModal from '../../components/LightboxModal.vue';
import { renderPdfCover } from '../../utils/pdfThumbnail';

const currentSlide = ref(0);
let timer = null;

const fallbackImage = '/images/hero_banner.jpg';
const slides = ref([]);
const slidesLoaded = ref(false);

// Formats a date as "October 1, 2026".
const formatDate = (iso) => iso
  ? new Date(iso).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
  : '';

// Loads the stories shown in the home page carousel.
const loadSlides = async () => {
  try {
    const res = await fetch('/api/reader/carousel', { headers: { Accept: 'application/json' } });
    if (res.ok) slides.value = await res.json();
  } catch {
    // The carousel just stays empty if the request fails.
  } finally {
    slidesLoaded.value = true;
  }
};

const popularArticles = ref([]);
const homeVideos = ref([]);
const homeIssues = ref([]);
const galleryPhotos = ref([]);

// Each section shows placeholders until its request has finished (so "No … yet" never flashes while loading)
const articlesLoaded = ref(false);
const videosLoaded = ref(false);
const issuesLoaded = ref(false);
const galleryLoaded = ref(false);

// Fetches a list from the API, or an empty list when the request fails.
const getJson = async (url) => {
  try {
    const res = await fetch(url, { headers: { Accept: 'application/json' } });
    return res.ok ? await res.json() : [];
  } catch {
    return [];
  }
};

// Loads the popular articles.
const loadArticles = async () => {
  popularArticles.value = await getJson('/api/reader/popular');
  articlesLoaded.value = true;
};

// Loads the latest videos (3).
const loadVideos = async () => {
  homeVideos.value = await getJson('/api/reader/videos?limit=3');
  videosLoaded.value = true;
};

// Loads the latest gallery photos (3).
const loadGallery = async () => {
  galleryPhotos.value = await getJson('/api/reader/gallery?limit=3');
  galleryLoaded.value = true;
};

// Loads the latest published issues (3) and draws their covers.
const loadIssues = async () => {
  const issues = await getJson('/api/reader/issues?limit=3');
  homeIssues.value = issues.map((i) => ({ ...i, image: null, date: formatDate(i.created_at) }));
  issuesLoaded.value = true;
  // Issues are PDFs, so the cover is the rendered first page (fills in as each one finishes).
  homeIssues.value.forEach(async (issue) => {
    if (!issue.pdf_url) return;
    try {
      issue.image = await renderPdfCover(issue.pdf_url);
    } catch (e) {
      console.warn('Could not render issue cover:', e);
    }
  });
};

// Opens an issue in the booklet viewer in a new tab.
const openIssue = (issue) => {
  window.open(`/booklet/${issue.id}`, '_blank');
};

const isModalOpen = ref(false);
const selectedPhoto = ref({});

// Opens a gallery photo in the lightbox.
const openModal = (photo) => {
  selectedPhoto.value = photo;
  isModalOpen.value = true;
};

// Shows the next carousel slide (wrapping around).
const nextSlide = () => {
  if (!slides.value.length) return;
  currentSlide.value = (currentSlide.value + 1) % slides.value.length;
};

// Shows the previous carousel slide (wrapping around).
const prevSlide = () => {
  if (!slides.value.length) return;
  currentSlide.value = (currentSlide.value - 1 + slides.value.length) % slides.value.length;
};

// Jumps to a chosen carousel slide.
const goToSlide = (idx) => {
  currentSlide.value = idx;
};

// Starts the carousel moving every 4.5 seconds (paused while the tab is in the background).
const startAutoPlay = () => {
  stopAutoPlay();
  // No point re-rendering the page every few seconds while the tab is in the background
  timer = setInterval(() => { if (!document.hidden) nextSlide(); }, 4500);
};

// Stops the carousel.
const stopAutoPlay = () => {
  if (timer) clearInterval(timer);
};

onMounted(() => {
  loadSlides();
  loadArticles();
  loadVideos();
  loadIssues();
  loadGallery();
  startAutoPlay();
});

onUnmounted(() => {
  stopAutoPlay();
});
</script>
