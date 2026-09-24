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
          <router-link
            v-for="name in CATEGORIES"
            :key="name"
            :to="{ path: '/categories', query: { category: name } }"
            class="dropdown-item"
          >{{ name }}</router-link>
        </div>
      </div>
      <router-link to="/videos" class="nav-capsule-link" :class="{ active: currentRoute === '/videos' }">Videos</router-link>
      <router-link to="/gallery" class="nav-capsule-link" :class="{ active: currentRoute === '/gallery' }">Gallery</router-link>
      <router-link to="/issues" class="nav-capsule-link" :class="{ active: currentRoute === '/issues' }">Published Issues</router-link>
    </nav>

    <!-- Right Action Buttons -->
    <div class="reader-actions">
      <button class="btn-icon-search" title="Search" @click="openSearch">
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

    <!-- Site links: the top capsule is hidden on phones, so they live here instead -->
    <nav class="menu-mobile-nav" aria-label="Site sections">
      <router-link to="/" :class="{ active: currentRoute === '/' }" @click="closeMenu">Home</router-link>
      <router-link to="/categories" :class="{ active: currentRoute === '/categories' }" @click="closeMenu">Categories</router-link>
      <router-link to="/videos" :class="{ active: currentRoute === '/videos' }" @click="closeMenu">Videos</router-link>
      <router-link to="/gallery" :class="{ active: currentRoute === '/gallery' }" @click="closeMenu">Gallery</router-link>
      <router-link to="/issues" :class="{ active: currentRoute === '/issues' }" @click="closeMenu">Published Issues</router-link>
    </nav>

    <!-- State 1: Logged In -->
    <div v-if="isLoggedIn" class="menu-state-content">
      <div class="user-capsule-card">
      <router-link to="/profile" @click="closeMenu" class="avatar-link" title="View Profile">
          <img
            v-if="currentUser.profile_picture_url"
            :src="currentUser.profile_picture_url"
            class="user-capsule-avatar"
            alt="Profile"
          />
          <div v-else class="user-capsule-avatar-initials">
            {{ userInitials }}
          </div>
        </router-link>
        <div class="user-capsule-info">
          <div class="user-capsule-name">{{ currentUser.name }}</div>
          <div class="user-capsule-email">{{ currentUser.email }}</div>
        </div>
        <!-- Dashboard shortcut — only for staff roles -->
        <router-link v-if="isStaff" :to="staffDashboardPath" class="user-capsule-icon-btn" title="Go to Dashboard" @click="closeMenu">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/>
            <line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/>
            <line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/>
            <line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/>
            <line x1="17" y1="16" x2="23" y2="16"/>
          </svg>
        </router-link>
      </div>
      <router-link to="/saved" class="btn-menu-saved" @click="closeMenu">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
        </svg>
        Saved Articles
      </router-link>
      <button class="btn-menu-signout" @click="signOut">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Sign Out
      </button>
    </div>

    <!-- State 2: Not Logged In -->
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

  </div>

  <SearchModal :open="isSearchOpen" @close="closeSearch" />
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { signOut as performSignOut } from '../utils/auth';
import { lazyModal } from '../utils/lazyModal';

const SearchModal = lazyModal(() => import('./SearchModal.vue'), 'open');

const route  = useRoute();
const router = useRouter();
const currentRoute = computed(() => route.path);

const CATEGORIES = ['News', 'Opinion', 'Editorial', 'Feature', 'Sci-Tech', 'DevCom', 'Sports', 'Literary'];

const isMenuOpen = ref(false);
const isSearchOpen = ref(false);

// ── Real auth state from localStorage ────────────────────────────────────────
const rawUser    = localStorage.getItem('sparky_user');
const currentUser = ref(rawUser ? JSON.parse(rawUser) : null);
const isLoggedIn  = computed(() => !!currentUser.value);

// Ctrl/Cmd+K, or "/" outside a text field, opens the search; Esc closes it
const onKeydown = (e) => {
  const typing = /^(INPUT|TEXTAREA|SELECT)$/.test(e.target?.tagName) || e.target?.isContentEditable;
  if ((e.key === 'k' && (e.ctrlKey || e.metaKey)) || (e.key === '/' && !typing)) {
    e.preventDefault();
    openSearch();
  } else if (e.key === 'Escape' && isSearchOpen.value) {
    closeSearch();
  }
};

// Re-read user from localStorage on mount (picks up profile_picture_url set after upload)
onMounted(() => {
  const stored = localStorage.getItem('sparky_user');
  if (stored) currentUser.value = JSON.parse(stored);
  window.addEventListener('keydown', onKeydown);
});
onBeforeUnmount(() => window.removeEventListener('keydown', onKeydown));

const userInitials = computed(() => {
  const name = currentUser.value?.name || '';
  return name.split(' ').map(p => p[0]).join('').toUpperCase().slice(0, 2) || '?';
});

const STAFF_ROLES = ['admin', 'eic', 'section_editor', 'staff_writer', 'staff_artist', 'staff_broadcaster'];
const isStaff     = computed(() => STAFF_ROLES.includes(currentUser.value?.role));

const dashMap = {
  admin:             '/admin',
  eic:               '/eic',
  section_editor:    '/editor',
  staff_writer:      '/writer',
  staff_artist:      '/artist',
  staff_broadcaster: '/broadcaster',
};
const staffDashboardPath = computed(() => dashMap[currentUser.value?.role] || '/');

// ── Actions ───────────────────────────────────────────────────────────────────
const toggleMenu   = () => { isMenuOpen.value = !isMenuOpen.value; };
const closeMenu    = () => { isMenuOpen.value = false; };
const openSearch   = () => { isMenuOpen.value = false; isSearchOpen.value = true; };
const closeSearch  = () => { isSearchOpen.value = false; };

const signOut = async () => {
  currentUser.value = null;
  closeMenu();
  await performSignOut(router);
};
</script>
