<template>
  <header class="reader-navbar">
    <!-- Logo Left -->
    <router-link to="/" class="reader-logo">
      <img src="/assets/Spark_Logo.png" alt="TheSpark Logo" class="reader-logo-img">
      <div class="reader-logo-text">
        <h2>TheSpark</h2>
        <p>Publication</p>
      </div>
    </router-link>

    <!-- Center Floating Capsule Nav -->
    <nav class="nav-capsule">
      <router-link to="/" class="nav-capsule-link" :class="{ active: currentRoute === '/' }">Home</router-link>
      <div class="nav-dropdown-wrapper">
        <router-link to="/categories" class="nav-capsule-link dropdown-toggle" :class="{ active: currentRoute === '/categories' }">
          Categories
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="m6 9 6 6 6-6" />
          </svg>
        </router-link>
        <div class="nav-dropdown-menu">
          <router-link to="/categories" class="dropdown-item">News</router-link>
          <router-link to="/categories" class="dropdown-item">Opinion/Editorial</router-link>
          <router-link to="/categories" class="dropdown-item">Feature</router-link>
          <router-link to="/categories" class="dropdown-item">DevCom</router-link>
          <router-link to="/categories" class="dropdown-item">Sports</router-link>
          <router-link to="/categories" class="dropdown-item">Literary</router-link>
        </div>
      </div>
      <router-link to="/gallery" class="nav-capsule-link" :class="{ active: currentRoute === '/gallery' }">Gallery</router-link>
      <router-link to="/issues" class="nav-capsule-link" :class="{ active: currentRoute === '/issues' }">Published Issues</router-link>
    </nav>

    <!-- Right Action Buttons -->
    <div class="reader-actions">
      <button class="btn-icon-search" title="Search" @click="toggleSearch">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8" />
          <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
      </button>
      <button class="btn-icon-menu" title="Menu" @click="toggleMenu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="4" y1="6" x2="20" y2="6" />
          <line x1="4" y1="12" x2="20" y2="12" />
          <line x1="4" y1="18" x2="20" y2="18" />
        </svg>
      </button>
    </div>
  </header>

  <!-- Right Action Menu Modal Popup -->
  <div :class="['reader-menu-overlay', { active: isMenuOpen }]" @click="closeMenu"></div>
  <div :class="['reader-menu-card', { active: isMenuOpen }]">
    <div class="menu-card-top">
      <button class="menu-close-btn" @click="closeMenu" title="Close Menu">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>

    <!-- State 1: Logged In User -->
    <div v-if="isSignedIn" class="menu-state-content">
      <div class="user-capsule-card">
        <svg width="44" height="44" viewBox="0 0 24 24" fill="#cbd5e1" class="user-capsule-avatar">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/>
        </svg>
        <div class="user-capsule-info">
          <div class="user-capsule-name">Kenji Turiano</div>
          <div class="user-capsule-email">sec.editor@thesparkpub.com</div>
        </div>
        <router-link to="/editor" class="user-capsule-icon-btn" title="Control Settings" @click="closeMenu">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="4" y1="21" x2="4" y2="14"/>
            <line x1="4" y1="10" x2="4" y2="3"/>
            <line x1="12" y1="21" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12" y2="3"/>
            <line x1="20" y1="21" x2="20" y2="16"/>
            <line x1="20" y1="12" x2="20" y2="3"/>
            <line x1="1" y1="14" x2="7" y2="14"/>
            <line x1="9" y1="8" x2="15" y2="8"/>
            <line x1="17" y1="16" x2="23" y2="16"/>
          </svg>
        </router-link>
      </div>
      <button class="btn-menu-signout" @click="isSignedIn = false">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Sign Out
      </button>
    </div>

    <!-- State 2: Logged Out / Sign In -->
    <div v-else class="menu-state-content">
      <router-link to="/login" class="signin-capsule-card" @click="closeMenu">
        <div class="signin-icon-circle">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </div>
        <div class="signin-capsule-info">
          <span class="signin-title">Sign In</span>
          <span class="signin-subtext">Access your account & features</span>
        </div>
      </router-link>
    </div>

    <!-- State Switcher Toggle -->
    <div class="menu-state-toggle-row">
      <button class="menu-state-toggle-btn" @click="isSignedIn = !isSignedIn">
        Switch View (Signed In &harr; Signed Out)
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const currentRoute = computed(() => route.path);

const isMenuOpen = ref(false);
const isSignedIn = ref(true);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const closeMenu = () => {
  isMenuOpen.value = false;
};

const toggleSearch = () => {
  // Can expand search overlay or navigate
};
</script>
