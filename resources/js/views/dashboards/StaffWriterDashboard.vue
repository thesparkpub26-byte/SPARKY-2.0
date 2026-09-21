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
                <!-- My Tasks Nav Item -->
                <a href="#" class="nav-item" :class="{ active: activeTab === 'tasks' }" @click.prevent="activeTab = 'tasks'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                            <line x1="9" y1="12" x2="15" y2="12" />
                            <line x1="9" y1="16" x2="13" y2="16" />
                        </svg>
                        My Tasks
                    </div>
                </a>

                <!-- My Articles Nav Item -->
                <a href="#" class="nav-item" :class="{ active: activeTab === 'articles' }" @click.prevent="activeTab = 'articles'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <line x1="10" y1="9" x2="8" y2="9" />
                        </svg>
                        My Articles
                    </div>
                </a>

                <!-- Recent Submissions Nav Item -->
                <a href="#" class="nav-item" :class="{ active: activeTab === 'submissions' }" @click.prevent="activeTab = 'submissions'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                            <path d="m9 16 2 2 4-4" />
                        </svg>
                        Recent Submissions
                    </div>
                </a>

                <!-- Press Works Nav Item -->
                <a href="#" class="nav-item" :class="{ active: activeTab === 'pressWorks' }" @click.prevent="activeTab = 'pressWorks'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="14 2 14 8 20 8" />
                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2" />
                            <line x1="8" y1="13" x2="16" y2="13" />
                            <line x1="8" y1="17" x2="16" y2="17" />
                        </svg>
                        Press Works
                    </div>
                </a>
            </nav>

            <div class="user-profile">
                <img :src="user.profile_picture ? ('/storage/' + user.profile_picture) : (user.profile_picture_url || 'https://picsum.photos/200?random=15')" alt="Profile">
                <div class="user-info">
                    <span class="role-badge" style="background-color: #1a73e8; color: white;">{{ formatRole(user.role, user.secondary_role) }}</span>
                    <h4>{{ user.name || 'Staff Writer' }}</h4>
                    <p>{{ user.email || 'writer@thesparkpub.com' }}</p>
                </div>
                <button class="settings-btn" type="button" aria-label="Open profile" title="View Profile" @click="router.push('/profile')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"></path>
                        <path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"></path>
                        <path d="M12 2v2"></path>
                        <path d="M12 22v-2"></path>
                        <path d="m17 4-1.5 1.5"></path>
                        <path d="M22 12h-2"></path>
                        <path d="m17 20-1.5-1.5"></path>
                        <path d="M2 12h2"></path>
                        <path d="m7 4 1.5 1.5"></path>
                        <path d="m7 20 1.5-1.5"></path>
                    </svg>
                </button>
            </div>

            <button class="sign-out-btn" style="margin-top: 15px;"
                @click.prevent="performSignOut(router)">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                Sign Out
            </button>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="search" v-model="searchQuery" placeholder="Search tasks or articles..." aria-label="Search">
                </div>
                <div class="top-header-right">
                    <NotificationsPopover />
                </div>
            </header>

            <!-- Dynamic Content Container -->
            <div class="content-container fade-in">

                <!-- MY TASKS TAB -->
                <div v-show="activeTab === 'tasks'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header" style="margin-bottom: 4px;">
                        <h1 class="page-title">My Tasks</h1>
                    </div>
                    <div class="kanban-board">
                        <!-- Column 1: Pending -->
                        <div class="kanban-column">
                            <h3 class="column-header">Pending ({{ pendingTasks.length }})</h3>
                            <div class="cards-container">
                                <div 
                                    v-for="task in pendingTasks" 
                                    :key="task.id || task.title"
                                    class="task-card" 
                                    :class="getPriorityClass(task.priority)"
                                    @click="openTaskModal(task)"
                                    style="cursor: pointer;"
                                >
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                        </svg>
                                        {{ formatPriorityLabel(task.priority) }}
                                    </span>
                                    <h4 class="card-title">{{ task.title }}</h4>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            {{ task.deadline }}
                                        </div>
                                        <div class="avatar-group" v-if="task.assignees && task.assignees.length">
                                            <img 
                                                v-for="(assignee, idx) in task.assignees" 
                                                :key="idx" 
                                                :src="assignee.avatar || assignee.profile_picture_url || `https://api.dicebear.com/7.x/lorelei/svg?seed=writer_${idx}&backgroundColor=ffd5dc`"
                                                :alt="assignee.name || 'Assignee'" 
                                                class="assignee-avatar"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div v-if="pendingTasks.length === 0" class="empty-column-state">
                                    No pending tasks
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Ongoing -->
                        <div class="kanban-column">
                            <h3 class="column-header">Ongoing ({{ ongoingTasks.length }})</h3>
                            <div class="cards-container">
                                <div 
                                    v-for="task in ongoingTasks" 
                                    :key="task.id || task.title"
                                    class="task-card" 
                                    :class="getPriorityClass(task.priority)"
                                    @click="openTaskModal(task)"
                                    style="cursor: pointer;"
                                >
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                        </svg>
                                        {{ formatPriorityLabel(task.priority) }}
                                    </span>
                                    <h4 class="card-title">{{ task.title }}</h4>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            {{ task.deadline }}
                                        </div>
                                        <div class="avatar-group" v-if="task.assignees && task.assignees.length">
                                            <img 
                                                v-for="(assignee, idx) in task.assignees" 
                                                :key="idx" 
                                                :src="assignee.avatar || assignee.profile_picture_url || `https://api.dicebear.com/7.x/lorelei/svg?seed=writer_${idx}&backgroundColor=d1fae5`"
                                                :alt="assignee.name || 'Assignee'" 
                                                class="assignee-avatar"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div v-if="ongoingTasks.length === 0" class="empty-column-state">
                                    No ongoing tasks
                                </div>
                            </div>
                        </div>

                        <!-- Column 3: Submitted -->
                        <div class="kanban-column">
                            <h3 class="column-header">Submitted ({{ submittedTasks.length }})</h3>
                            <div class="cards-container">
                                <div 
                                    v-for="task in submittedTasks" 
                                    :key="task.id || task.title"
                                    class="task-card" 
                                    :class="getPriorityClass(task.priority)"
                                    @click="openTaskModal(task)"
                                    style="cursor: pointer;"
                                >
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                        </svg>
                                        {{ formatPriorityLabel(task.priority) }}
                                    </span>
                                    <h4 class="card-title">{{ task.title }}</h4>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            {{ task.deadline }}
                                        </div>
                                        <div class="avatar-group" v-if="task.assignees && task.assignees.length">
                                            <img 
                                                v-for="(assignee, idx) in task.assignees" 
                                                :key="idx" 
                                                :src="assignee.avatar || assignee.profile_picture_url || `https://api.dicebear.com/7.x/lorelei/svg?seed=writer_${idx}&backgroundColor=fecdd3`"
                                                :alt="assignee.name || 'Assignee'" 
                                                class="assignee-avatar"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div v-if="submittedTasks.length === 0" class="empty-column-state">
                                    No submitted tasks
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MY ARTICLES TAB -->
                <div v-show="activeTab === 'articles'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header"
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">My Articles</h1>
                        <button class="status-filter-btn">
                            Status
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                    </div>
                    <div class="articles-card">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px;">Title</th>
                                    <th>Coverage</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="padding-right: 28px;">Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr @click="openTaskModal({ title: 'Wellness Campaign Launch', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">Wellness Campaign Launch</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 1</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-draft">Draft</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 15 &bull; 3:10 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'Campus Wi-Fi Expansion Project', section: 'News', coverage: 'Tech & Innovation Series' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">Campus Wi-Fi Expansion Project</td>
                                    <td style="color: #64748b;">Tech &amp; Innovation Series</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-for-review">For Review</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 14 &bull; 5:05 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'The Rise of Campus Creatives', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 2' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">The Rise of Campus Creatives</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 2</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-under-revision">Under Revision</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 14 &bull; 10:45 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'New Campus Laboratory Building Opens', section: 'News', coverage: 'Foundation Day 2026' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">New Campus Laboratory Building Opens</td>
                                    <td style="color: #64748b;">Foundation Day 2026</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-published">Published</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 12 &bull; 4:00 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'College Fair Highlights', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">College Fair Highlights</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 1</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-published">Published</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 8 &bull; 6:00 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'College Fair Attracts Hundreds', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">College Fair Attracts Hundreds</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 1</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-published">Published</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 7 &bull; 5:30 PM</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container">
                        <div class="pagination-pill">
                            <button class="page-btn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                                Previous
                            </button>
                            <a href="#" class="page-number active">1</a>
                            <a href="#" class="page-number">2</a>
                            <a href="#" class="page-number">3</a>
                            <a href="#" class="page-number">4</a>
                            <a href="#" class="page-number">5</a>
                            <span class="page-dots">&bull;&bull;&bull;</span>
                            <button class="page-btn">
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>
                            <div class="page-results-count">
                                Showing <strong>6</strong> results
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RECENT SUBMISSIONS TAB -->
                <div v-show="activeTab === 'submissions'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header" style="margin-bottom: 4px;">
                        <h1 class="page-title">Recent Submissions</h1>
                    </div>
                    <div class="articles-card">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px;">Title</th>
                                    <th>Press Work</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="padding-right: 28px;">Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr @click="openTaskModal({ title: 'Wellness Campaign Launch', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">Wellness Campaign Launch</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 1</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-for-review">For Review</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 15 &bull; 3:10 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'Campus Wi-Fi Expansion Project', section: 'News', coverage: 'Tech & Innovation Series' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">Campus Wi-Fi Expansion Project</td>
                                    <td style="color: #64748b;">Tech &amp; Innovation Series</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-for-review">For Review</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 11 &bull; 5:05 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'The Rise of Campus Creatives', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 2' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">The Rise of Campus Creatives</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 2</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-endorsed">Endorsed</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 14 &bull; 10:45 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'New Campus Laboratory Building Opens', section: 'News', coverage: 'Foundation Day 2026' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">New Campus Laboratory Building Opens</td>
                                    <td style="color: #64748b;">Foundation Day 2026</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-published">Published</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 12 &bull; 4:00 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'College Fair Highlights', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">College Fair Highlights</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 1</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-published">Published</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 8 &bull; 6:00 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'College Fair Attracts Hundreds', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">College Fair Attracts Hundreds</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 1</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-published">Published</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 7 &bull; 5:30 PM</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container">
                        <div class="pagination-pill">
                            <button class="page-btn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                                Previous
                            </button>
                            <a href="#" class="page-number active">1</a>
                            <a href="#" class="page-number">2</a>
                            <a href="#" class="page-number">3</a>
                            <a href="#" class="page-number">4</a>
                            <a href="#" class="page-number">5</a>
                            <span class="page-dots">&bull;&bull;&bull;</span>
                            <button class="page-btn">
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>
                            <div class="page-results-count">
                                Showing <strong>6</strong> results
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PRESS WORKS TAB -->
                <div v-show="activeTab === 'pressWorks'" style="display: flex; flex-direction: column; gap: 12px; flex-shrink: 0;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h1 class="page-title">Press Works</h1>
                    </div>

                    <div class="card" style="padding: 16px 24px; background: #ffffff; border-radius: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);">
                        <div class="table-wrapper">
                            <table class="activities-table articles-table">
                                <thead>
                                    <tr>
                                        <th style="padding-left: 24px;">Press Works</th>
                                        <th>Publication Type</th>
                                        <th>Academic Year</th>
                                        <th>Members</th>
                                        <th>Date Created</th>
                                        <th style="padding-right: 24px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr @click="openMonitoringSheet" style="cursor: pointer;" class="clickable-row">
                                        <td style="padding-left: 24px;">Issue 1</td>
                                        <td><span class="pub-badge pub-newsletter">Newsletter</span></td>
                                        <td>2025-2026</td>
                                        <td>18</td>
                                        <td>May 15, 2025</td>
                                        <td style="padding-right: 24px;">
                                            <button class="action-menu-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr @click="openMonitoringSheet" style="cursor: pointer;" class="clickable-row">
                                        <td style="padding-left: 24px;">Issue 1</td>
                                        <td><span class="pub-badge pub-tabloid">Tabloid</span></td>
                                        <td>2025-2026</td>
                                        <td>24</td>
                                        <td>May 15, 2025</td>
                                        <td style="padding-right: 24px;">
                                            <button class="action-menu-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr @click="openMonitoringSheet" style="cursor: pointer;" class="clickable-row">
                                        <td style="padding-left: 24px;">Issue 1</td>
                                        <td><span class="pub-badge pub-magazine">Magazine</span></td>
                                        <td>2025-2026</td>
                                        <td>14</td>
                                        <td>May 15, 2025</td>
                                        <td style="padding-right: 24px;">
                                            <button class="action-menu-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr @click="openMonitoringSheet" style="cursor: pointer;" class="clickable-row">
                                        <td style="padding-left: 24px; border-bottom: none;">Issue 1</td>
                                        <td style="border-bottom: none;"><span class="pub-badge pub-litfolio">Litfolio</span></td>
                                        <td style="border-bottom: none;">2025-2026</td>
                                        <td style="border-bottom: none;">12</td>
                                        <td style="border-bottom: none;">May 15, 2025</td>
                                        <td style="padding-right: 24px; border-bottom: none;">
                                            <button class="action-menu-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="pagination-pill">
                        <button class="page-nav" style="border: none; background: none; display: flex; align-items: center; gap: 4px; color: #64748b; font-weight: 500; cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Previous
                        </button>
                        <div class="page-numbers" style="display: flex; gap: 8px;">
                            <button class="page-num active" style="background: #2563eb; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center;">1</button>
                            <button class="page-num" style="background: none; border: none; color: #64748b; font-weight: 500; cursor: pointer; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">2</button>
                            <button class="page-num" style="background: none; border: none; color: #64748b; font-weight: 500; cursor: pointer; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">3</button>
                            <button class="page-num" style="background: none; border: none; color: #64748b; font-weight: 500; cursor: pointer; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">4</button>
                            <button class="page-num" style="background: none; border: none; color: #64748b; font-weight: 500; cursor: pointer; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">5</button>
                            <span class="page-ellipsis" style="color: #64748b; font-weight: 500; display: flex; align-items: center; justify-content: center;">...</span>
                        </div>
                        <button class="page-nav" style="border: none; background: none; display: flex; align-items: center; gap: 4px; color: #64748b; font-weight: 500; cursor: pointer;">
                            Next <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </button>
                        <div class="showing-text" style="color: #64748b; font-size: 13px; margin-left: 16px;">
                            Showing <b>4</b> Press Works
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Assigned Task Quick Modal -->
    <AssignedTaskModal 
        :is-open="isAssignedTaskModalOpen" 
        :task-data="selectedTask"
        @close="isAssignedTaskModalOpen = false" 
        @open-workspace="handleOpenWorkspace" 
    />

    <!-- Full Assignment Workspace Modal -->
    <AssignmentWorkspaceModal 
        :is-open="isWorkspaceModalOpen" 
        :task-data="selectedTask"
        @close="isWorkspaceModalOpen = false"
        @task-submitted="handleTaskSubmitted"
        @task-saved-as-draft="handleTaskSavedAsDraft"
        @view-submissions="handleViewSubmissions"
    />
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import AssignedTaskModal from '../../components/AssignedTaskModal.vue';
import AssignmentWorkspaceModal from '../../components/AssignmentWorkspaceModal.vue';
import NotificationsPopover from '../../components/NotificationsPopover.vue';
import { signOut as performSignOut } from '../../utils/auth';

const router = useRouter();
const activeTab = ref('tasks');
const searchQuery = ref('');
const isAssignedTaskModalOpen = ref(false);
const isWorkspaceModalOpen = ref(false);
const selectedTask = ref({});
const isLoadingTasks = ref(false);

// ── User Management ─────────────────────────────────────────────────────────────
const user = ref(JSON.parse(localStorage.getItem('sparky_user') || '{}'));
const token = localStorage.getItem('sparky_token');

const formatRole = (role, secondaryRole) => {
    if (secondaryRole) return secondaryRole;
    if (!role) return 'Staff Writer';
    const map = {
        admin: 'Administrator',
        eic: 'Editor-in-Chief',
        section_editor: 'Section Editor',
        staff_writer: 'Staff Writer',
        staff_artist: 'Staff Artist',
        staff_broadcaster: 'Staff Broadcaster',
        reader: 'Reader'
    };
    return map[role] || role.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
};

const formatPriorityLabel = (priority) => {
    const p = (priority || '').toLowerCase();
    if (p === 'medium') return 'Moderate';
    if (p === 'urgent') return 'Urgent';
    if (p === 'high') return 'High';
    if (p === 'low') return 'Low';
    return priority ? priority.charAt(0).toUpperCase() + priority.slice(1) : 'Moderate';
};

const getPriorityClass = (priority) => {
    const p = (priority || '').toLowerCase();
    if (p === 'low') return 'card-low';
    if (p === 'high') return 'card-high';
    if (p === 'urgent') return 'card-urgent';
    return 'card-moderate';
};

const formatDeadline = (dateStr, dueTimeStr) => {
    if (!dateStr) return 'No deadline';
    if (typeof dateStr === 'string' && dateStr.includes('•')) return dateStr;

    // Parse the date part cleanly without timezone shifting
    let datePart = '';
    const cleanDate = String(dateStr).split('T')[0].split(' ')[0];
    const parts = cleanDate.split('-');
    if (parts.length === 3) {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const m = months[parseInt(parts[1], 10) - 1] || '';
        const day = parseInt(parts[2], 10);
        datePart = `${m} ${day}`;
    } else {
        const d = new Date(dateStr);
        datePart = !isNaN(d.getTime()) ? d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : dateStr;
    }

    // Parse the due time part cleanly
    let timePart = '';
    if (dueTimeStr) {
        if (dueTimeStr.includes('AM') || dueTimeStr.includes('PM')) {
            timePart = dueTimeStr;
        } else if (dueTimeStr.includes(':')) {
            const timeSegments = dueTimeStr.split(':').map(Number);
            const h = timeSegments[0];
            const m = timeSegments[1];
            const period = h >= 12 ? 'PM' : 'AM';
            const displayH = h % 12 || 12;
            const displayM = m < 10 ? `0${m}` : m;
            timePart = `${displayH}:${displayM} ${period}`;
        }
    } else if (typeof dateStr === 'string' && dateStr.includes(':') && (dateStr.includes('T') || dateStr.includes(' '))) {
        const timeObj = new Date(dateStr);
        if (!isNaN(timeObj.getTime())) {
            // Only add time if it's not 00:00:00 UTC artifact
            const h = timeObj.getHours();
            const m = timeObj.getMinutes();
            if (h !== 0 || m !== 0) {
                timePart = timeObj.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
            }
        }
    }

    return timePart ? `${datePart} • ${timePart}` : datePart;
};

const parseNotesField = (notes, key) => {
    if (!notes || typeof notes !== 'string') return '';
    const match = notes.match(new RegExp(`${key}:\\s*([^|]+)`, 'i'));
    return match ? match[1].trim() : '';
};

// ── Tasks State ────────────────────────────────────────────────────────────────
const tasks = ref([]);

// ── Fetch Tasks from Backend ───────────────────────────────────────────────────
const fetchTasks = async () => {
    if (!token) return;
    isLoadingTasks.value = true;
    try {
        const res = await fetch('/api/tasks', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            const data = await res.json();
            if (Array.isArray(data)) {
                // Only show tasks assigned specifically to this logged-in writer
                const userTasks = user.value.id 
                    ? data.filter(t => t.assignee_id === user.value.id)
                    : data;
                
                tasks.value = userTasks.map(t => {
                    const dueTime = parseNotesField(t.notes, 'Due Time');
                    return {
                        id: t.id,
                        title: t.title,
                        section: t.section?.name || parseNotesField(t.notes, 'Section') || 'News',
                        coverage: parseNotesField(t.notes, 'Coverage') || '',
                        dueTime: dueTime,
                        deadline: formatDeadline(t.deadline, dueTime),
                        priority: t.priority || 'medium',
                        status: t.status || 'pending',
                        articleDesc: t.description || 'Write a clear article according to editorial board guidelines.',
                        thumbnailDesc: parseNotesField(t.notes, 'Thumbnail') || 'Create a clean thumbnail using campus-related visuals with readable title placement...',
                        mediaArtist: parseNotesField(t.notes, 'Media Artist') || '',
                        assignees: t.assignee ? [{ name: t.assignee.name, avatar: t.assignee.profile_picture ? `/storage/${t.assignee.profile_picture}` : (t.assignee.profile_picture_url || 'https://picsum.photos/100?random=15') }] : [],
                        raw: t
                    };
                });
            }
        }
    } catch (err) {
        console.warn('Could not fetch backend tasks:', err);
    } finally {
        isLoadingTasks.value = false;
    }
};

// ── Computed Filtered Tasks for Kanban ─────────────────────────────────────────
const filteredTasks = computed(() => {
    const q = searchQuery.value.trim().toLowerCase();
    if (!q) return tasks.value;
    return tasks.value.filter(t => {
        const title = (t.title || '').toLowerCase();
        const section = (typeof t.section === 'object' ? t.section?.name : t.section || '').toLowerCase();
        const priority = (t.priority || '').toLowerCase();
        return title.includes(q) || section.includes(q) || priority.includes(q);
    });
});

const pendingTasks = computed(() => {
    return filteredTasks.value.filter(t => t.status === 'pending');
});

const ongoingTasks = computed(() => {
    return filteredTasks.value.filter(t => t.status === 'in_progress' || t.status === 'ongoing' || t.status === 'returned');
});

const submittedTasks = computed(() => {
    return filteredTasks.value.filter(t => t.status === 'submitted' || t.status === 'completed');
});

// ── Modal & Action Handlers ─────────────────────────────────────────────────────
const openTaskModal = (task = {}) => {
    selectedTask.value = {
        ...task,
        title: task.title || 'Untitled Task',
        section: task.section || 'News',
        deadline: task.deadline || 'No deadline',
        priority: task.priority || 'Moderate',
        articleDesc: task.articleDesc || task.description || '',
        thumbnailDesc: task.thumbnailDesc || '',
        mediaArtist: task.mediaArtist || ''
    };
    isAssignedTaskModalOpen.value = true;
};

const handleOpenWorkspace = (taskData) => {
    isAssignedTaskModalOpen.value = false;
    selectedTask.value = taskData || selectedTask.value;
    isWorkspaceModalOpen.value = true;
};

const handleTaskSavedAsDraft = (draftTask) => {
    const targetId = draftTask.id || selectedTask.value.id;
    const index = tasks.value.findIndex(t => t.id === targetId || t.title === draftTask.title);
    if (index !== -1) {
        tasks.value[index] = {
            ...tasks.value[index],
            ...draftTask,
            status: 'in_progress'
        };
    } else {
        tasks.value.unshift({
            ...draftTask,
            status: 'in_progress'
        });
    }
};

const handleTaskSubmitted = (submittedTask) => {
    const targetId = submittedTask.id || selectedTask.value.id;
    const index = tasks.value.findIndex(t => t.id === targetId || t.title === submittedTask.title);
    if (index !== -1) {
        tasks.value[index] = {
            ...tasks.value[index],
            ...submittedTask,
            status: 'submitted'
        };
    } else {
        tasks.value.unshift({
            ...submittedTask,
            status: 'submitted'
        });
    }
};

const handleViewSubmissions = () => {
    activeTab.value = 'tasks';
};

const openMonitoringSheet = () => {
    window.open('/monitoring-sheet', '_blank');
};

const onProfileUpdated = (e) => {
    if (e.detail) {
        user.value = e.detail;
    }
};

// ── Lifecycle ───────────────────────────────────────────────────────────────────
onMounted(async () => {
    if (token) {
        try {
            const res = await fetch('/api/me', {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            if (res.ok) {
                const fresh = await res.json();
                user.value = fresh;
                localStorage.setItem('sparky_user', JSON.stringify(fresh));
            }
        } catch (e) {
            console.warn('Could not refresh profile:', e);
        }
    }
    await fetchTasks();
    window.addEventListener('sparky:profile-updated', onProfileUpdated);
});

onUnmounted(() => {
    window.removeEventListener('sparky:profile-updated', onProfileUpdated);
});
</script>
