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
        <a href="#" :class="['nav-item', { active: activeTab === 'tasks' }]" @click.prevent="activeTab = 'tasks'">
          <div class="nav-item-left">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>
            My Tasks
          </div>
        </a>
        <a href="#" :class="['nav-item', { active: activeTab === 'recent-submissions' }]" @click.prevent="activeTab = 'recent-submissions'">
          <div class="nav-item-left">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            Submissions
          </div>
        </a>
        <a href="#" :class="['nav-item', { active: activeTab === 'press-works' }]" @click.prevent="activeTab = 'press-works'">
          <div class="nav-item-left">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            Press Works
          </div>
        </a>
      </nav>

      <div class="user-profile">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="#cbd5e1">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/>
        </svg>
        <div class="user-info">
          <span class="role-badge">Staff Artist</span>
          <h4>Staff Artist</h4>
          <p>artist@thesparkpub.com</p>
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
        <p style="color: #64748b;">Staff Artist Studio — Viewing graphics & art submissions.</p>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const activeTab = ref('tasks');
</script>
