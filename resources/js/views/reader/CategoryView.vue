<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <!-- 2 Column Category & Sidebar Grid -->
      <div class="category-main-grid" style="margin-top: 32px;">
        <!-- Left Category Articles Column -->
        <div class="category-content-wrapper">
          <h1 class="category-title-header">{{ activeCategory }}</h1>

          <!-- Horizontal Cards List -->
          <div class="category-articles-list">
            <router-link 
              v-for="(item, idx) in articles" 
              :key="idx" 
              to="/article" 
              class="category-article-card"
            >
              <div class="category-card-img-wrapper">
                <img :src="item.image" :alt="item.title">
              </div>
              <div class="category-card-body">
                <h3 class="category-card-title">{{ item.title }}</h3>
                <p class="category-card-excerpt">{{ item.excerpt }}</p>
                <div class="category-card-footer">
                  <span class="category-card-stats">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                      <circle cx="12" cy="12" r="3" />
                    </svg>
                    {{ item.views }} &nbsp;|&nbsp; {{ item.date }}
                  </span>
                  <span class="sidebar-read-more">Read More</span>
                </div>
              </div>
            </router-link>
          </div>

          <!-- Pagination Capsule Bar -->
          <div class="pagination-wrapper">
            <nav class="pagination-capsule">
              <a href="#" :class="['pagination-btn', { disabled: currentPage === 1 }]" @click.prevent="prevPage">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="m15 18-6-6 6-6"/>
                </svg>
                Previous
              </a>
              <a v-for="p in [1, 2, 3, 4, 5]" :key="p" href="#" :class="['pagination-btn', { active: currentPage === p }]" @click.prevent="currentPage = p">{{ p }}</a>
              <span style="padding: 0 4px; color: #94a3b8; font-size: 13px; font-weight: 600;">...</span>
              <a href="#" class="pagination-btn" @click.prevent="nextPage">
                Next
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="m9 18 6-6-6-6"/>
                </svg>
              </a>
            </nav>
          </div>
        </div>

        <!-- Right Sidebar: Popular Now -->
        <aside class="article-sidebar">
          <div class="sidebar-card-container">
            <div class="sidebar-header">
              <h3 class="sidebar-title">Popular Now</h3>
              <p class="sidebar-subtitle">You might like to read these posts.</p>
            </div>

            <!-- Story 1 (Featured Card with Image) -->
            <div class="top-story-item">
              <div class="top-story-featured-img-wrapper">
                <span class="top-story-badge-overlay badge-category">Sports</span>
                <img src="/images/basketball.jpg" alt="CSPC Athletes Bring Home Regional" class="top-story-featured-img">
              </div>
              <router-link to="/article" class="top-story-title">CSPC Athletes Bring Home Regional...</router-link>
              <p class="top-story-desc">The college athletes showcased determination and teamwork after achieving outstanding...</p>
              <div class="top-story-footer">
                <span>👁 402 &nbsp;|&nbsp; December 12, 2025</span>
                <router-link to="/article" class="sidebar-read-more">Read More &rarr;</router-link>
              </div>
            </div>

            <!-- Story 2 -->
            <div class="top-story-item">
              <span class="top-story-badge">News</span>
              <router-link to="/article" class="top-story-title">Prescribed Dress Code</router-link>
              <p class="top-story-desc">As the new school year begins, CSPC Officially announced advisory regarding the prescribed...</p>
              <div class="top-story-footer">
                <span>👁 9.6k &nbsp;|&nbsp; January 27, 2026</span>
                <router-link to="/article" class="sidebar-read-more">Read More &rarr;</router-link>
              </div>
            </div>

            <!-- Story 3 -->
            <div class="top-story-item">
              <span class="top-story-badge">Feature</span>
              <router-link to="/article" class="top-story-title">Student Lead Community Outreach...</router-link>
              <p class="top-story-desc">Student volunteers conducted an outreach program promoting education, environmental...</p>
              <div class="top-story-footer">
                <span>👁 1.2k &nbsp;|&nbsp; February 28, 2026</span>
                <router-link to="/article" class="sidebar-read-more">Read More &rarr;</router-link>
              </div>
            </div>

            <!-- Story 4 -->
            <div class="top-story-item">
              <span class="top-story-badge">Feature</span>
              <router-link to="/article" class="top-story-title">Beyond the Classroom: Stories of...</router-link>
              <p class="top-story-desc">Discover the inspiring journeys of CSPC students who continue to excel in academics...</p>
              <div class="top-story-footer">
                <span>👁 907 &nbsp;|&nbsp; January 18, 2026</span>
                <router-link to="/article" class="sidebar-read-more">Read More &rarr;</router-link>
              </div>
            </div>
          </div>
        </aside>
      </div>

      <!-- Subscribe To Our Newsletter Section -->
      <NewsletterCard />

      <!-- Footer Section -->
      <Footer />
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';

const activeCategory = ref('News');
const currentPage = ref(1);

const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};

const nextPage = () => {
  if (currentPage.value < 5) currentPage.value++;
};

const articles = [
  {
    title: 'CSPC Launches New Student Portal',
    excerpt: 'NABUA, CAMARINES SUR — In a major move toward campus digitalization, Camarines Sur Polytechnic Colleges (CSPC) has officially rolled out its newly revamped Student Portal...',
    image: '/images/graduation.jpg',
    views: '11.4k',
    date: 'January 18, 2026'
  },
  {
    title: 'Prescribed Dress Code for Upcoming Academic Year',
    excerpt: 'NABUA, CAMARINES SUR — In a major move toward campus digitalization, Camarines Sur Polytechnic Colleges (CSPC) has officially rolled out its newly revamped Student Portal...',
    image: '/images/dress_code.jpg',
    views: '11.4k',
    date: 'January 19, 2026'
  },
  {
    title: 'CSPC Launches New Student Portal',
    excerpt: 'NABUA, CAMARINES SUR — In a major move toward campus digitalization, Camarines Sur Polytechnic Colleges (CSPC) has officially rolled out its newly revamped Student Portal...',
    image: '/images/graduation.jpg',
    views: '11.4k',
    date: 'January 18, 2026'
  },
  {
    title: 'CSPC Launches New Student Portal',
    excerpt: 'NABUA, CAMARINES SUR — In a major move toward campus digitalization, Camarines Sur Polytechnic Colleges (CSPC) has officially rolled out its newly revamped Student Portal...',
    image: '/images/graduation.jpg',
    views: '11.4k',
    date: 'January 18, 2026'
  },
  {
    title: 'CSPC Athletes Bring Home Regional Championships',
    excerpt: 'NABUA, CAMARINES SUR — CSPC Blue Dragons dominating courts and tracks across events during regional sports meets...',
    image: '/images/basketball.jpg',
    views: '7.5k',
    date: 'January 12, 2026'
  }
];
</script>
