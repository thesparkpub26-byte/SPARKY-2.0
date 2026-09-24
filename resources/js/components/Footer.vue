<template>
  <footer class="reader-footer">
    <div class="footer-container">
      <!-- Col 1: Brand Info -->
      <div class="footer-col brand-col">
        <div class="footer-logo">
          <img src="/assets/Spark_Logo.png" alt="TheSPARK Logo" class="footer-logo-img">
          <div>
            <h3>TheSPARK</h3>
            <p class="footer-tagline">Truth knows no limits</p>
          </div>
        </div>
        <div class="footer-address">
          <p><strong>Camarines Sur Polytechnic Colleges</strong></p>
          <p>San Miguel, Nabua, Camarines Sur</p>
          <p>4434 Philippines</p>
        </div>
        <p class="footer-copyright">&copy; 2026 by TheSPARK (CSPC)</p>
      </div>

      <!-- Col 2: Socials -->
      <div class="footer-col">
        <h4 class="footer-heading">Socials</h4>
        <ul class="footer-links-list">
          <li>
            <a href="https://www.facebook.com/thesparkpub" target="_blank" rel="noopener noreferrer"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" /></svg> /thesparkpub</a>
          </li>
          <li>
            <a href="https://www.youtube.com/@TheSparkPub" target="_blank" rel="noopener noreferrer"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z" /><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" /></svg> @TheSparkPub</a>
          </li>
          <li>
            <a href="https://www.instagram.com/thesparkpub/" target="_blank" rel="noopener noreferrer"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" /><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" /><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" /></svg> @thesparkpub</a>
          </li>
          <li>
            <a href="https://www.tiktok.com/@thesparkpub" target="_blank" rel="noopener noreferrer"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5" /></svg> @thesparkpub</a>
          </li>
        </ul>
      </div>

      <!-- Col 3: Navigations -->
      <div class="footer-col">
        <h4 class="footer-heading">Navigations</h4>
        <ul class="footer-links-list">
          <li><router-link to="/" @click="scrollTopIfCurrent('/')">Home</router-link></li>
          <li><router-link to="/categories" @click="scrollTopIfCurrent('/categories')">Categories</router-link></li>
          <li><router-link to="/gallery" @click="scrollTopIfCurrent('/gallery')">Gallery</router-link></li>
          <li><router-link to="/issues" @click="scrollTopIfCurrent('/issues')">Published Issues</router-link></li>
        </ul>
      </div>

      <!-- Col 4: Explore -->
      <div class="footer-col">
        <h4 class="footer-heading">Explore</h4>
        <ul class="footer-links-list">
          <li><router-link to="/privacy-policy">Privacy Policy</router-link></li>
          <li><router-link to="/terms">Terms &amp; Conditions</router-link></li>
        </ul>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { useRoute } from 'vue-router';

const route = useRoute();

// Pages scroll inside .reader-page, so a link to the page we're already on wouldn't move it.
const scrollTopIfCurrent = (path) => {
  if (route.path === path) {
    document.querySelector('.reader-page')?.scrollTo({ top: 0, behavior: 'smooth' });
  }
};
</script>
