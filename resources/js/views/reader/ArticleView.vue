<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <!-- 2 Column Article & Sidebar Grid -->
      <div class="article-main-grid" style="margin-top: 24px;">
        <!-- Left Article Content -->
        <article class="article-content-wrapper">
          <!-- Title -->
          <h1 class="article-title">CSPC Launches New Student Portal</h1>

          <!-- Category & Date Header -->
          <div class="article-meta-header">
            <span class="badge-category">News</span>
            <span class="article-date-text">January 20, 2026</span>
          </div>

          <!-- Featured Image -->
          <img src="/images/student_portal.jpg" alt="CSPC Launches New Student Portal" class="article-main-cover">

          <!-- Author Info Row -->
          <div class="article-author-card">
            <div class="author-left-info">
              <svg width="44" height="44" viewBox="0 0 24 24" fill="#cbd5e1" class="author-avatar-img">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/>
              </svg>
              <div class="author-details-text">
                <h4>Gabrielle M. Loquias</h4>
                <p>Staff Writer</p>
              </div>
            </div>
            <button class="btn-view-contributors">
              View Contributors
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
              </svg>
            </button>
          </div>

          <!-- Article Body Paragraphs -->
          <div class="article-body-content">
            <p><strong>NABUA, CAMARINES SUR —</strong> In a major move toward campus digitalization, Camarines Sur Polytechnic Colleges (CSPC) has officially rolled out its newly revamped Student Portal.</p>
            
            <p>Designed to eliminate the dreaded long lines of enrollment seasons past, the upgraded platform replaces outdated legacy servers with a high-speed, mobile-friendly interface. The new system is specifically engineered to handle high-volume traffic without crashing, allowing the CSPCian community to access services smoothly even during peak hours.</p>
            
            <p>The upgraded portal now serves as a centralized dashboard where CSPCians can manage their entire academic lifecycle with just a few clicks. It features real-time grade viewing, a streamlined online enrollment process, integrated accounting tracking for tuition balances, and a digital request system for official academic documents.</p>
            
            <p>Initial feedback from the student body has been overwhelmingly positive, with many praising the convenience of securing class schedules directly from their smartphone.</p>
            
            <p>Moving forward, the administration advises all CSPCians to log in using their official institutional credentials and to update their security settings.</p>
            
            <p>For anyone experiencing log-in issues or account glitches, the Management Information System (MIS) office has opened a virtual helpdesk to provide immediate technical support and ensure a seamless transition for everyone.</p>
          </div>

          <!-- Engagement Pill Bar -->
          <div class="article-engagement-bar">
            <div class="engagement-stats">
              <span class="stat-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
                1.4k Reads
              </span>
              <span class="stat-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                55 Comments
              </span>
              <span class="stat-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                  <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                </svg>
                121 Shares
              </span>
            </div>
            <form class="comment-input-form" @submit.prevent="submitComment">
              <input type="text" v-model="commentText" placeholder="Write a comment..." class="comment-input-field" required>
              <button type="submit" class="btn-send-comment" title="Send Comment">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <line x1="22" y1="2" x2="11" y2="13"/>
                  <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                </svg>
              </button>
            </form>
          </div>
        </article>

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

      <!-- Related Posts Section -->
      <section class="popular-section" style="margin-top: 56px;">
        <h2 class="section-headline">Related posts</h2>
        <p class="section-subtext">More stories from the CSPC community</p>

        <div class="articles-grid">
          <ArticleCard v-for="(art, idx) in relatedArticles" :key="idx" :article="art" />
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
import { ref } from 'vue';
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import ArticleCard from '../../components/ArticleCard.vue';

const commentText = ref('');

const submitComment = () => {
  if (commentText.value) {
    alert(`Comment posted: "${commentText.value}"`);
    commentText.value = '';
  }
};

const relatedArticles = [
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
    badge: 'Sports',
    title: 'CSPC Athletes Bring Home Regional Championships',
    excerpt: 'Dominating courts and tracks across events during SCUAA meets...',
    image: '/images/basketball.jpg',
    date: 'Aug 22, 2026',
    readTime: '4 mins read',
    views: '980',
    likes: '340'
  },
  {
    badge: 'DevCom',
    title: 'Voice of the Students: Campus Stories',
    excerpt: 'An inspiring feature highlighting student leaders driving change...',
    image: '/images/fountain.jpg',
    date: 'Aug 20, 2026',
    readTime: '5 mins read',
    views: '2.1k',
    likes: '890'
  }
];
</script>
