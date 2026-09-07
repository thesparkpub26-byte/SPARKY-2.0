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

          <!-- Pagination Capsule -->
          <div class="category-pagination-wrapper">
            <button class="page-arrow prev" :disabled="currentPage === 1" @click="currentPage--">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="m15 18-6-6 6-6"/>
              </svg>
            </button>
            <div class="page-numbers">
              <button 
                v-for="p in [1, 2, 3, 4]" 
                :key="p" 
                :class="['page-num', { active: currentPage === p }]"
                @click="currentPage = p"
              >{{ p }}</button>
            </div>
            <button class="page-arrow next" :disabled="currentPage === 4" @click="currentPage++">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="m9 18 6-6-6-6"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Right Sidebar: Top Stories -->
        <aside class="article-sidebar">
          <div class="sidebar-card-container">
            <div class="sidebar-header">
              <h3 class="sidebar-title">Top Stories</h3>
              <p class="sidebar-subtitle">You might like to read these posts.</p>
            </div>

            <!-- Story 1 -->
            <div class="top-story-item">
              <div class="top-story-featured-img-wrapper">
                <span class="top-story-badge-overlay badge-category">Feature</span>
                <img src="/images/graduation.jpg" alt="Beyond the Classroom" class="top-story-featured-img">
              </div>
              <router-link to="/article" class="top-story-title">Beyond the Classroom: Stories of...</router-link>
              <p class="top-story-desc">Discover the inspiring journeys of CSPC students who continue to excel in academics...</p>
              <div class="top-story-footer">
                <span>👁 11.4k &nbsp;|&nbsp; January 18, 2026</span>
                <router-link to="/article" class="sidebar-read-more">Read More &rarr;</router-link>
              </div>
            </div>

            <!-- Story 2 -->
            <div class="top-story-item">
              <span class="badge-category text-badge">Sports</span>
              <router-link to="/article" class="top-story-title">CSPC Athletes Triumph in Regionals</router-link>
              <p class="top-story-desc">The Blue Dragons secured multiple gold medals during the annual SCUAA games...</p>
              <div class="top-story-footer">
                <span>👁 8.2k &nbsp;|&nbsp; January 15, 2026</span>
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

const articles = [
  {
    title: 'CSPC Launches New Student Portal',
    excerpt: 'NABUA, CAMARINES SUR — In a major move toward campus digitalization, Camarines Sur Polytechnic Colleges (CSPC) has officially rolled out its newly revamped Student Portal...',
    image: '/images/student_portal.jpg',
    views: '11.4k',
    date: 'January 18, 2026'
  },
  {
    title: 'Prescribed Dress Code for Upcoming Academic Year',
    excerpt: 'NABUA, CAMARINES SUR — As the new academic year opens, CSPC officially announced advisory regarding prescribed campus attires and guidelines...',
    image: '/images/dress_code.jpg',
    views: '9.8k',
    date: 'January 19, 2026'
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
