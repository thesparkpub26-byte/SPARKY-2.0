<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <!-- Artists Gallery Section -->
      <section class="artists-gallery-section" style="margin-top: 32px; width: 100%;">
        <h1 class="section-headline" style="margin-bottom: 28px;">Artists Gallery</h1>

        <!-- Gallery Grid -->
        <div class="gallery-grid">
          <div 
            v-for="(img, idx) in galleryImages" 
            :key="idx" 
            class="gallery-grid-item"
            @click="openLightbox(img)"
          >
            <img :src="img" :alt="`Artist Work ${idx + 1}`">
          </div>
        </div>
      </section>

      <!-- Subscribe To Our Newsletter Section -->
      <NewsletterCard />

      <!-- Footer Section -->
      <Footer />
    </main>

    <LightboxModal :isOpen="isModalOpen" :imageSrc="selectedImage" @close="isModalOpen = false" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import LightboxModal from '../../components/LightboxModal.vue';

const isModalOpen = ref(false);
const selectedImage = ref('');

const galleryImages = [
  '/images/hero_banner.jpg',
  '/images/dress_code.jpg',
  '/images/fountain.jpg',
  '/images/graduation.jpg',
  '/images/basketball.jpg',
  '/images/student_portal.jpg'
];

const openLightbox = (src) => {
  selectedImage.value = src;
  isModalOpen.value = true;
};
</script>
