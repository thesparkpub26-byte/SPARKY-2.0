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
          <div class="carousel-track" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
            <div v-for="(slide, idx) in slides" :key="idx" class="carousel-slide">
              <img :src="slide.image" :alt="slide.title">
            </div>
          </div>

          <!-- Navigation Arrow Overlay Buttons -->
          <button class="carousel-arrow carousel-arrow-prev" @click="prevSlide" title="Previous Slide">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="m15 18-6-6 6-6" />
            </svg>
          </button>
          <button class="carousel-arrow carousel-arrow-next" @click="nextSlide" title="Next Slide">
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

        <div class="articles-grid">
          <ArticleCard v-for="(art, idx) in popularArticles" :key="idx" :article="art" />
        </div>
      </section>

      <!-- Published Issues Section -->
      <section class="published-issues-section">
        <h2 class="section-headline">Published Issues</h2>
        <p class="section-subtext">The most read from TheSPARK</p>

        <div class="issues-grid">
          <IssueCard v-for="(item, idx) in homeIssues" :key="idx" :issue="item" />
        </div>

        <!-- Load More Link -->
        <div class="load-more-wrapper">
          <router-link to="/issues" class="btn-load-more">Load More</router-link>
        </div>
      </section>

      <!-- Artists Gallery Section -->
      <section class="artists-gallery-section">
        <h2 class="section-headline">Artists Gallery</h2>
        <p class="section-subtext">Check out what our artists made!</p>

        <div class="gallery-carousel-wrapper">
          <div class="gallery-cards-row">
            <div class="gallery-card" @click="openModal('/images/dress_code.jpg')">
              <img src="/images/dress_code.jpg" alt="Artist Work 1">
            </div>
            <div class="gallery-card" @click="openModal('/images/student_portal.jpg')">
              <img src="/images/student_portal.jpg" alt="Artist Work 2">
            </div>
            <div class="gallery-card" @click="openModal('/images/fountain.jpg')">
              <img src="/images/fountain.jpg" alt="Artist Work 3">
            </div>
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
import { ref, onMounted, onUnmounted } from 'vue';
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import ArticleCard from '../../components/ArticleCard.vue';
import IssueCard from '../../components/IssueCard.vue';
import LightboxModal from '../../components/LightboxModal.vue';

const currentSlide = ref(0);
let timer = null;

const slides = [
  { image: '/images/hero_banner.jpg', title: 'TheSpark 39 Truth Knows No Limits' },
  { image: '/images/student_portal.jpg', title: 'CSPC Launches New Student Portal' },
  { image: '/images/graduation.jpg', title: 'Beyond the Classroom: Celebrating Graduates' },
  { image: '/images/basketball.jpg', title: 'CSPC Athletes Bring Home Championships' }
];

const popularArticles = [
  {
    badge: 'News',
    title: 'Prescribed Dress Code',
    excerpt: 'As the new school year begins, CSPC Officially announced advisory regarding the prescribed...',
    image: '/images/dress_code.jpg',
    date: 'Aug 24, 2026',
    readTime: '3 mins read',
    views: '1.4k',
    likes: '512'
  },
  {
    badge: 'DevCom',
    title: 'CSPC Advances Smart Campus Solutions',
    excerpt: 'Initiatives for digitalization and tech integrations across departments take full throttle...',
    image: '/images/student_portal.jpg',
    date: 'Aug 22, 2026',
    readTime: '4 mins read',
    views: '980',
    likes: '340'
  },
  {
    badge: 'Feature',
    title: 'Voice of the Students: Campus Stories',
    excerpt: 'An inspiring feature highlighting student leaders driving change in community development...',
    image: '/images/fountain.jpg',
    date: 'Aug 20, 2026',
    readTime: '5 mins read',
    views: '2.1k',
    likes: '890'
  },
  {
    badge: 'Sports',
    title: 'CSPC Athletes Bring Home Regional...',
    excerpt: 'The college athletes showcased determination and teamwork after achieving outstanding...',
    image: '/images/basketball.jpg',
    date: 'January 17, 2026',
    readTime: '4 mins read',
    views: '1.8k',
    likes: '620'
  },
  {
    badge: 'Literary',
    title: 'Whispers Between the Pages',
    excerpt: 'A collection of poems and short literary pieces reflecting the emotions, experiences, and...',
    image: '/images/fountain.jpg',
    date: 'January 14, 2026',
    readTime: '3 mins read',
    views: '1.1k',
    likes: '410'
  },
  {
    badge: 'DevComm',
    title: 'Student Lead Community Outreach...',
    excerpt: 'Student volunteers conducted an outreach program promoting education, environmental...',
    image: '/images/dress_code.jpg',
    date: 'February 28, 2026',
    readTime: '4 mins read',
    views: '1.5k',
    likes: '530'
  }
];

const homeIssues = [
  {
    image: '/images/blind_idolatry.jpg',
    title: 'NEWSLETTER | Volume XLI | No. 1',
    desc: 'The Official Student Community Publication of CSPC | August - December 2021',
    date: 'January 9, 2022'
  },
  {
    image: '/images/blind_idolatry.jpg',
    title: 'NEWSLETTER | Volume XLI | No. 1',
    desc: 'The Official Student Community Publication of CSPC | August - December 2021',
    date: 'January 9, 2022'
  },
  {
    image: '/images/blind_idolatry.jpg',
    title: 'NEWSLETTER | Volume XLI | No. 1',
    desc: 'The Official Student Community Publication of CSPC | August - December 2021',
    date: 'January 9, 2022'
  }
];

const isModalOpen = ref(false);
const selectedImage = ref('');

const openModal = (src) => {
  selectedImage.value = src;
  isModalOpen.value = true;
};

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % slides.length;
};

const prevSlide = () => {
  currentSlide.value = (currentSlide.value - 1 + slides.length) % slides.length;
};

const goToSlide = (idx) => {
  currentSlide.value = idx;
};

const startAutoPlay = () => {
  stopAutoPlay();
  timer = setInterval(nextSlide, 4500);
};

const stopAutoPlay = () => {
  if (timer) clearInterval(timer);
};

onMounted(() => {
  startAutoPlay();
});

onUnmounted(() => {
  stopAutoPlay();
});
</script>
