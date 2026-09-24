<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <!-- Artists Gallery Section -->
      <section class="artists-gallery-section" style="margin-top: 32px; width: 100%;">
        <h1 class="section-headline" style="margin-bottom: 28px;">Artists Gallery</h1>

        <SkeletonCards v-if="loading" variant="gallery" :count="6" />
        <p v-else-if="!photos.length" class="section-empty">No photos uploaded yet.</p>

        <!-- Gallery Grid -->
        <div v-else class="gallery-grid">
          <div
            v-for="photo in photos"
            :key="photo.id"
            class="gallery-grid-item"
            @click="openLightbox(photo)"
          >
            <img :src="photo.image" :alt="photo.title" loading="lazy" decoding="async">
            <div class="photo-hover-title"><span>{{ photo.title }}</span></div>
          </div>
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

    <LightboxModal
      :isOpen="isModalOpen"
      :imageSrc="selectedPhoto.image"
      :title="selectedPhoto.title"
      :artist="selectedPhoto.artist"
      :date="selectedPhoto.date"
      @close="closeLightbox"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Navbar from '../../components/Navbar.vue';
import SkeletonCards from '../../components/SkeletonCards.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import LightboxModal from '../../components/LightboxModal.vue';

const route = useRoute();
const router = useRouter();
const isModalOpen = ref(false);
const selectedPhoto = ref({});

const photos = ref([]);
const loading = ref(true);
const loadingMore = ref(false);
const page = ref(0);
const lastPage = ref(1);

// Photos come twelve at a time; "Load More" appends the next twelve
const loadPage = async (next) => {
  const res = await fetch(`/api/reader/gallery?page=${next}`, { headers: { Accept: 'application/json' } });
  if (!res.ok) return;
  const body = await res.json();
  photos.value = next === 1 ? body.data : [...photos.value, ...body.data];
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

// /gallery?photo=12 (from a search result) opens that photo straight away
const openFromLink = async () => {
  const id = route.query.photo;
  if (!id) return;

  try {
    const res = await fetch(`/api/reader/gallery/${encodeURIComponent(id)}`, { headers: { Accept: 'application/json' } });
    if (res.ok) {
      selectedPhoto.value = await res.json();
      isModalOpen.value = true;
    }
  } catch {
    // The gallery still shows; the photo just doesn't pop open.
  }
};

const closeLightbox = () => {
  isModalOpen.value = false;
  if (route.query.photo) router.replace({ path: '/gallery' });
};

onMounted(async () => {
  try {
    await loadPage(1);
  } catch {
    // Falls through to the empty state.
  } finally {
    loading.value = false;
  }
  openFromLink();
});

const openLightbox = (photo) => {
  selectedPhoto.value = photo;
  isModalOpen.value = true;
};
</script>
