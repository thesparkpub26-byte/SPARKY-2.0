<template>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="brand">
        <img src="/assets/Spark_Logo.png" alt="The Spark Logo">
        <div class="brand-text">
          <h2>TheSpark</h2>
          <p>Publication System</p>
        </div>
      </div>

      <nav class="nav-menu">
        <a href="#" :class="['nav-item', { active: activeTab === 'overview' }]" @click.prevent="activeTab = 'overview'">
          <div class="nav-item-left">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Overview
          </div>
        </a>
        <a href="#" :class="['nav-item', { active: activeTab === 'endorsements' }]" @click.prevent="activeTab = 'endorsements'">
          <div class="nav-item-left">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
            Endorsements
          </div>
        </a>
        <a href="#" :class="['nav-item', { active: activeTab === 'articles' }]" @click.prevent="activeTab = 'articles'">
          <div class="nav-item-left">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            Published Articles
          </div>
        </a>
        <a href="#" :class="['nav-item', { active: activeTab === 'press-works' }]" @click.prevent="activeTab = 'press-works'">
          <div class="nav-item-left">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            Press Works
          </div>
        </a>
        <a href="#" :class="['nav-item', { active: activeTab === 'contributors' }]" @click.prevent="activeTab = 'contributors'">
          <div class="nav-item-left">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
            Contributors
          </div>
        </a>
        <a href="#" :class="['nav-item', { active: activeTab === 'archive' }]" @click.prevent="activeTab = 'archive'">
          <div class="nav-item-left">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect></svg>
            Archive
          </div>
        </a>
        <a href="#" :class="['nav-item', { active: activeTab === 'analytics' }]" @click.prevent="activeTab = 'analytics'">
          <div class="nav-item-left">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            Analytics
          </div>
        </a>
      </nav>

      <div class="user-profile">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="#cbd5e1">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/>
        </svg>
        <div class="user-info">
          <span class="role-badge">Editor-in-Chief</span>
          <h4>Samantha Reyes</h4>
          <p>eic@thesparkpub.com</p>
        </div>
      </div>

      <button class="sign-out-btn" style="margin-top: 15px;" @click="$router.push('/login')">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        Sign Out
      </button>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
      <header class="top-header">
        <h1 class="page-title" style="text-transform: capitalize;">{{ activeTab.replace('-', ' ') }}</h1>
      </header>

      <div class="content-view" style="margin-top: 20px;">
        <p style="color: #64748b;">Editor-in-Chief Panel — Viewing {{ activeTab }} section.</p>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const activeTab = ref('overview');
</script>
