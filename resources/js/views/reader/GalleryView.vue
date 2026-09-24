<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <!-- Artists Gallery Section -->
      <section class="artists-gallery-section" style="margin-top: 32px; width: 100%;">
        <h1 class="section-headline" style="margin-bottom: 28px;">Artists Gallery</h1>

        <p v-if="loading" class="section-empty">Loading photos…</p>
        <p v-else-if="!photos.length" class="section-empty">No photos uploaded yet.</p>

        <!-- Gallery Grid -->
        <div v-else class="gallery-grid">
          <div
            v-for="photo in photos"
            :key="photo.id"
            class="gallery-grid-item"
            @click="openLightbox(photo)"
          >
            <img :src="photo.image" :alt="photo.title">
            <div class="photo-hover-title"><span>{{ photo.title }}</span></div>
          </div>
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
      @close="isModalOpen = false"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import LightboxModal from '../../components/LightboxModal.vue';

const isModalOpen = ref(false);
const selectedPhoto = ref({});

const photos = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const res = await fetch('/api/reader/gallery', { headers: { Accept: 'application/json' } });
    if (res.ok) photos.value = await res.json();
  } catch {
    // Falls through to the empty state.
  } finally {
    loading.value = false;
  }
});

const openLightbox = (photo) => {
  selectedPhoto.value = photo;
  isModalOpen.value = true;
};
</script>
