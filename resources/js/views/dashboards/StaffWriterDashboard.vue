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

                <!-- Artist's Submissions Nav Item -->
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
                        Artist's Submissions
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
                <MobileNavToggle />
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="search" v-model="searchInput" :placeholder="searchPlaceholder" aria-label="Search current view">
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
                                    class="task-card-enhanced" 
                                    :class="getPriorityClass(task.priority)"
                                    @click="openTaskModal(task)"
                                    style="cursor: pointer;"
                                >
                                    <div class="task-card-header">
                                        <span class="priority-badge">
                                            <svg viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                            </svg>
                                            {{ formatPriorityLabel(task.priority) }}
                                        </span>
                                        <span class="section-badge">{{ task.section }}</span>
                                    </div>
                                    <h4 class="card-title">{{ task.title }}</h4>
                                    <div class="task-card-details">
                                        <div class="task-detail-item">
                                            <span class="detail-label">Deadline:</span>
                                            <span class="detail-value">{{ task.deadline }}</span>
                                        </div>
                                        <div class="task-detail-item" v-if="task.mediaArtist">
                                            <span class="detail-label">Artist:</span>
                                            <span class="detail-value">{{ task.mediaArtist }}</span>
                                        </div>
                                    </div>
                                    <div class="task-card-footer">
                                        <span class="date-pill" v-if="task.deadline && task.deadline !== 'No deadline'">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                            {{ task.deadline }}
                                        </span>
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
                                    class="task-card-enhanced" 
                                    :class="getPriorityClass(task.priority)"
                                    @click="openTaskModal(task)"
                                    style="cursor: pointer;"
                                >
                                    <div class="task-card-header">
                                        <span class="priority-badge">
                                            <svg viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                            </svg>
                                            {{ formatPriorityLabel(task.priority) }}
                                        </span>
                                        <div style="display: flex; gap: 6px; align-items: center;">
                                            <span v-if="task.status === 'returned'" class="status-badge" style="background: #fee2e2; color: #dc2626; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 9999px;">Returned</span>
                                            <span class="section-badge">{{ task.section }}</span>
                                        </div>
                                    </div>
                                    <h4 class="card-title">{{ task.title }}</h4>
                                    <div class="task-card-details">
                                        <div class="task-detail-item">
                                            <span class="detail-label">Deadline:</span>
                                            <span class="detail-value">{{ task.deadline }}</span>
                                        </div>
                                        <div class="task-detail-item" v-if="task.mediaArtist">
                                            <span class="detail-label">Artist:</span>
                                            <span class="detail-value">{{ task.mediaArtist }}</span>
                                        </div>
                                        <div class="task-detail-item" v-if="task.status === 'returned' && task.revisionNotes" style="margin-top: 4px;">
                                            <span class="detail-label" style="color: #dc2626; font-weight: 600;">Revision Notes:</span>
                                            <span class="detail-value" style="color: #991b1b; font-size: 12px; line-height: 1.4;">{{ task.revisionNotes }}</span>
                                        </div>
                                    </div>
                                    <div class="task-card-footer">
                                        <span class="date-pill" v-if="task.deadline && task.deadline !== 'No deadline'">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                            {{ task.deadline }}
                                        </span>
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
                                    class="task-card-enhanced" 
                                    :class="getPriorityClass(task.priority)"
                                    @click="openTaskModal(task)"
                                    style="cursor: pointer;"
                                >
                                    <div class="task-card-header">
                                        <span class="priority-badge">
                                            <svg viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                            </svg>
                                            {{ formatPriorityLabel(task.priority) }}
                                        </span>
                                        <span class="section-badge">{{ task.section }}</span>
                                    </div>
                                    <h4 class="card-title">{{ task.title }}</h4>
                                    <div class="task-card-details">
                                        <div class="task-detail-item">
                                            <span class="detail-label">Deadline:</span>
                                            <span class="detail-value">{{ task.deadline }}</span>
                                        </div>
                                        <div class="task-detail-item" v-if="task.mediaArtist">
                                            <span class="detail-label">Artist:</span>
                                            <span class="detail-value">{{ task.mediaArtist }}</span>
                                        </div>
                                    </div>
                                    <div class="task-card-footer">
                                        <span class="date-pill" v-if="task.deadline && task.deadline !== 'No deadline'">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                            {{ task.deadline }}
                                        </span>
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
                        <div class="filter-pills-group">
                            <div class="custom-filter" @click.stop>
                                <button type="button" class="filter-trigger" @click="toggleStatusFilter">
                                    <span>{{ statusFilterLabel }}</span>
                                    <svg :class="{ rotated: activeStatusFilter }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>
                                <div v-if="activeStatusFilter" class="filter-menu">
                                    <button v-for="option in statusFilterOptions" :key="option.value" type="button" :class="{ selected: selectedStatusFilter === option.value }" @click="selectStatusFilter(option.value)">{{ option.label }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="articles-card">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px;">Title</th>
                                    <th style="text-align: center;">Status</th>
                                    <th>Last Updated</th>
                                    <th style="padding-right: 28px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="article in paginatedArticles" :key="article.id" @click="openArticleModal(article)" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">{{ article.title }}</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill" :class="getStatusClass(article.status)">{{ formatStatus(article.status) }}</span>
                                    </td>
                                    <td style="color: #64748b;">{{ formatArticleDate(article.lastUpdated) }}</td>
                                    <td style="padding-right: 28px; text-align: right;" @click.stop>
                                        <button
                                            v-if="article.status !== 'published'"
                                            class="article-delete-btn"
                                            @click.stop="confirmDeleteArticle(article)"
                                            title="Delete Article"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                        <span v-else style="font-size: 11px; color: #94a3b8;" title="Only the Editor-in-Chief can delete a published article.">—</span>
                                    </td>
                                </tr>
                                <tr v-if="filteredArticles.length === 0">
                                    <td colspan="4" style="text-align: center; padding: 40px; color: #64748b;">
                                        No articles found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container" v-if="filteredArticles.length > 0">
                        <div class="pagination-pill">
                            <button class="page-btn" @click="changeArticlesPage(articlesCurrentPage - 1)" :disabled="articlesCurrentPage === 1">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                                Previous
                            </button>
                            <a v-for="page in Math.min(totalArticlesPages, 5)" :key="page" href="#" class="page-number" :class="{ active: page === articlesCurrentPage }" @click.prevent="changeArticlesPage(page)">{{ page }}</a>
                            <span v-if="totalArticlesPages > 5" class="page-dots">&bull;&bull;&bull;</span>
                            <button class="page-btn" @click="changeArticlesPage(articlesCurrentPage + 1)" :disabled="articlesCurrentPage === totalArticlesPages">
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>
                            <div class="page-results-count">
                                Showing <strong>{{ paginatedArticles.length }}</strong> of {{ filteredArticles.length }} articles
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ARTIST'S SUBMISSIONS TAB -->
                <div v-show="activeTab === 'submissions'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header" style="margin-bottom: 4px;">
                        <h1 class="page-title">Artist's Submissions</h1>
                    </div>
                    <div class="articles-card">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px;">Article Title</th>
                                    <th>Artist/PJ</th>
                                    <th>Submission Type</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="padding-right: 28px;">Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="submission in shownArtistSubmissions" :key="submission.id" @click="openArtistSubmissionModal(submission)" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">{{ submission.articleTitle }}</td>
                                    <td style="color: #64748b;">{{ submission.artistName }}</td>
                                    <td>
                                        <span class="submission-type-badge">{{ submission.submissionType }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="status-pill" :class="getStatusClass(submission.status)">{{ formatStatus(submission.status) }}</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">{{ formatArticleDate(submission.submittedAt) }}</td>
                                </tr>
                                <tr v-if="shownArtistSubmissions.length === 0">
                                    <td colspan="5" style="text-align: center; padding: 40px; color: #64748b;">
                                        {{ searchQuery.trim() ? 'No submissions match your search.' : 'No artist submissions found' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PRESS WORKS TAB -->
                <div v-show="activeTab === 'pressWorks'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="margin-bottom: 4px;">
                        <h1 class="page-title">Press Works</h1>
                    </div>

                    <!-- Academic Years Folders -->
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div
                            v-for="yearGroup in shownAcademicYears"
                            :key="yearGroup.academic_year"
                            style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #ffffff;"
                        >
                            <!-- Folder Header (Expandable) -->
                            <div
                                class="folder-header-btn"
                                @click="toggleStaffYear(yearGroup.academic_year)"
                                style="padding: 16px; cursor: pointer; display: flex; align-items: center; gap: 12px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; user-select: none;"
                            >
                                <svg class="folder-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <span class="folder-title" style="font-weight: 700; flex: 1; color: #0f172a;">{{ yearGroup.academic_year }}</span>
                                <span style="color: #64748b; font-size: 13px; font-weight: 600;">{{ (yearGroup.monitoring_sheets || []).length }} Monitoring Sheet{{ (yearGroup.monitoring_sheets || []).length !== 1 ? 's' : '' }}</span>
                                <svg
                                    class="chevron-icon"
                                    :style="{ transform: staffExpandedYears[yearGroup.academic_year] ? 'rotate(180deg)' : 'rotate(0deg)', transition: 'transform 0.2s' }"
                                    xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                >
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </div>

                            <!-- Folder Content (Monitoring Sheets) -->
                            <div v-show="staffExpandedYears[yearGroup.academic_year]" style="padding: 16px 20px; display: flex; flex-direction: column; gap: 8px;">
                                <div
                                    v-for="sheet in yearGroup.monitoring_sheets"
                                    :key="sheet.id"
                                    class="monitoring-sheet-row"
                                    @click="openMonitoringSheet(sheet)"
                                    style="padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 12px; transition: all 0.2s;"
                                >
                                    <span :class="`pub-badge pub-${sheet.publication_type.toLowerCase()}`" style="padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; white-space: nowrap;">{{ sheet.publication_type }}</span>
                                    <span style="flex: 1; font-weight: 600; color: #0f172a;">{{ sheet.title }}</span>
                                    <span style="color: #94a3b8; font-size: 13px;">{{ formatDate(sheet.created_at) }}</span>
                                    <button
                                        class="action-menu-btn"
                                        type="button"
                                        @click.stop="openMonitoringSheet(sheet)"
                                        aria-label="Open monitoring sheet"
                                        style="padding: 4px 8px; cursor: pointer; background: none; border: none; color: #64748b;"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                    </button>
                                </div>
                                <div v-if="(yearGroup.monitoring_sheets || []).length === 0" style="padding: 20px; text-align: center; color: #94a3b8;">
                                    No monitoring sheets yet
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No Press Works Message -->
                    <div v-if="shownAcademicYears.length === 0" style="padding: 40px; text-align: center; color: #94a3b8; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        {{ noPressWorksText(searchQuery.trim() !== '') }}
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

    <!-- Artist Submission Details & Download Modal -->
    <ArtistSubmissionModal
        :is-open="isArtistSubmissionModalOpen"
        :submission-data="selectedArtistSubmission"
        @close="isArtistSubmissionModalOpen = false"
    />

    <!-- Read-only Article Preview (published articles) -->
    <ArticlePreviewModal
        :is-open="isArticlePreviewOpen"
        :article-data="selectedArticlePreview"
        read-only
        @close="isArticlePreviewOpen = false"
    />

    <!-- Delete Article Confirmation Modal -->
    <div v-if="articleToDelete" class="writer-confirm-overlay" @click.self="articleToDelete = null">
        <div class="writer-confirm-card">
            <h3 style="margin:0 0 8px;font-size:16px;font-weight:700;color:#0f172a;">Delete Article?</h3>
            <p style="margin:0 0 20px;font-size:13.5px;color:#64748b;line-height:1.5;">This will permanently delete <strong>"{{ articleToDelete.title }}"</strong>. This cannot be undone.</p>
            <p v-if="deleteArticleError" style="margin:0 0 16px;font-size:13px;color:#dc2626;">{{ deleteArticleError }}</p>
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button class="btn-cancel" @click="articleToDelete = null" :disabled="deletingArticle">Cancel</button>
                <button class="btn-confirm-delete" @click="doDeleteArticle" :disabled="deletingArticle">{{ deletingArticle ? 'Deleting…' : 'Delete' }}</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import MobileNavToggle from '../../components/MobileNavToggle.vue';
import { makeMatcher, searchAcademicYears, noPressWorksText, useDebouncedSearch } from '../../utils/dashboardSearch';
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { lazyModal } from '../../utils/lazyModal';
import { useRouter } from 'vue-router';
const AssignedTaskModal = lazyModal(() => import('../../components/AssignedTaskModal.vue'));
const AssignmentWorkspaceModal = lazyModal(() => import('../../components/AssignmentWorkspaceModal.vue'));
const ArtistSubmissionModal = lazyModal(() => import('../../components/ArtistSubmissionModal.vue'));
const ArticlePreviewModal = lazyModal(() => import('../../components/ArticlePreviewModal.vue'));
import NotificationsPopover from '../../components/NotificationsPopover.vue';
import { signOut as performSignOut } from '../../utils/auth';
import { fetchCreditedVideos } from '../../utils/video';

const router = useRouter();
const activeTab = ref('tasks');
const { input: searchInput, query: searchQuery } = useDebouncedSearch();
const matches = makeMatcher(searchQuery);
const searchPlaceholder = computed(() => ({
    tasks: 'Search my tasks',
    articles: 'Search my articles',
    submissions: 'Search artist submissions',
    pressWorks: 'Search press works',
}[activeTab.value] || 'Search'));
const isAssignedTaskModalOpen = ref(false);
const isWorkspaceModalOpen = ref(false);
const isArtistSubmissionModalOpen = ref(false);
const isArticlePreviewOpen = ref(false);
const selectedTask = ref({});
const selectedArtistSubmission = ref({});
const selectedArticlePreview = ref({});
const isLoadingTasks = ref(false);

// ── User Management ─────────────────────────────────────────────────────────────
const user = ref(JSON.parse(localStorage.getItem('sparky_user') || '{}'));
const token = localStorage.getItem('sparky_token');

const isCopyreader = computed(() => {
    const roles = [user.value?.secondary_role, user.value?.tertiary_role];
    return roles.includes('Copy Editor') || roles.includes('Copyreader');
});

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

// ── Articles State ──────────────────────────────────────────────────────────────
const articles = ref([]);
const articlesCurrentPage = ref(1);
const articlesPerPage = 8;
const activeStatusFilter = ref(false);
const selectedStatusFilter = ref('all');

// ── Artist Submissions State ─────────────────────────────────────────────────────
const artistSubmissions = ref([]);
const allTasks = ref([]); // Store all tasks to find artist submissions

// ── Press Works State ───────────────────────────────────────────────────────
const staffAcademicYears = ref([]);
const staffExpandedYears = ref({});

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
                // Store all tasks for artist submission matching
                allTasks.value = data;

                // Copyreaders/Copy Editors review other writers' articles (editing-type tasks);
                // everyone else only sees their own writing-type tasks. Once the linked
                // article is published, the task is done and drops off this working queue.
                const relevantType = isCopyreader.value ? 'editing' : 'writing';
                const userTasks = data.filter(t =>
                    t.type === relevantType
                    && (user.value.id ? t.assignee_id === user.value.id : true)
                    && t.article?.status !== 'published'
                );

                tasks.value = userTasks.map(t => {
                    const dueTime = parseNotesField(t.notes, 'Due Time');
                    let mediaArtist = parseNotesField(t.notes, 'Media Artist') || '';
                    let mediaArtistRole = '';

                    // Fallback: find the paired artist task by exact base title matching
                    // (e.g. "The Mob 3 (Visuals / Graphics)" matches "The Mob 3")
                    if (!mediaArtist) {
                        const writerClean = (t.title || '').replace(/\s*\([^)]*(visuals|video|graphics|photo|illustration|pj)[^)]*\)/i, '').trim().toLowerCase();
                        const pairedTask = data.find(other => {
                            if (other.assignee_id === user.value.id) return false;
                            if (!['illustration', 'photography', 'layout'].includes(other.type)) return false;
                            if (other.article_id && t.article_id && other.article_id === t.article_id) return true;
                            const otherClean = (other.title || '').replace(/\s*\([^)]*(visuals|video|graphics|photo|illustration|pj)[^)]*\)/i, '').trim().toLowerCase();
                            return otherClean && writerClean && otherClean === writerClean;
                        });
                        if (pairedTask && pairedTask.assignee) {
                            mediaArtist = pairedTask.assignee.name;
                            mediaArtistRole = pairedTask.assignee.secondary_role || 'Graphic Artist / PJ';
                        }
                    }

                    // For non-writing tasks (e.g. a copyreader's editing task), the
                    // real "writer" is whoever holds the writing-type task for the
                    // same article — not this task's own assignee.
                    let writer = null;
                    if (t.type === 'writing') {
                        writer = t.assignee || null;
                    } else if (t.article_id) {
                        const writingSibling = data.find(other => other.type === 'writing' && other.article_id === t.article_id);
                        writer = writingSibling?.assignee || null;
                    }

                    // Same idea for deadline/priority: fall back to the writer's task
                    // if this task doesn't carry its own (e.g. older editing tasks).
                    let effectiveDeadline = t.deadline;
                    let effectivePriority = t.priority;
                    if (t.type !== 'writing' && t.article_id && (!effectiveDeadline || !effectivePriority)) {
                        const writingSibling = data.find(other => other.type === 'writing' && other.article_id === t.article_id);
                        if (writingSibling) {
                            effectiveDeadline = effectiveDeadline || writingSibling.deadline;
                            effectivePriority = effectivePriority || writingSibling.priority;
                        }
                    }

                    return {
                        id: t.id,
                        title: t.title,
                        section: t.section?.name || parseNotesField(t.notes, 'Section') || 'News',
                        coverage: parseNotesField(t.notes, 'Coverage') || '',
                        dueTime: dueTime,
                        deadline: formatDeadline(effectiveDeadline, dueTime),
                        priority: effectivePriority || 'medium',
                        status: t.status || 'pending',
                        articleDesc: t.description || '',
                        thumbnailDesc: parseNotesField(t.notes, 'Thumbnail') || '',
                        mediaArtist: mediaArtist,
                        mediaArtistRole: mediaArtistRole || 'Graphic Artist / PJ',
                        notes: t.notes || '',
                        revisionNotes: parseNotesField(t.notes, 'Revision Notes') || '',
                        writer: writer ? {
                            name: writer.name,
                            role: writer.secondary_role || writer.role || 'Staff Writer',
                            avatar: writer.profile_picture ? `/storage/${writer.profile_picture}` : (writer.profile_picture_url || '')
                        } : null,
                        assignees: t.assignee ? [{ name: t.assignee.name, secondary_role: t.assignee.secondary_role || '', role: t.assignee.role || 'Staff Writer', avatar: t.assignee.profile_picture ? `/storage/${t.assignee.profile_picture}` : (t.assignee.profile_picture_url || '') }] : [],
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

// ── Fetch Articles from Backend ────────────────────────────────────────────────
const articleToDelete = ref(null);
const deletingArticle = ref(false);
const deleteArticleError = ref('');

const confirmDeleteArticle = (article) => {
    articleToDelete.value = article;
    deleteArticleError.value = '';
};

const doDeleteArticle = async () => {
    if (!articleToDelete.value) return;
    deletingArticle.value = true;
    deleteArticleError.value = '';
    try {
        const res = await fetch(`/api/articles/${articleToDelete.value.id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            articleToDelete.value = null;
            await fetchArticles();
            await fetchTasks();
        } else {
            const data = await res.json().catch(() => ({}));
            deleteArticleError.value = data.message || 'Failed to delete this article.';
        }
    } catch (err) {
        console.warn('Could not delete article:', err);
        deleteArticleError.value = 'Failed to delete this article.';
    } finally {
        deletingArticle.value = false;
    }
};

const fetchArticles = async () => {
    if (!token || !user.value?.id) return;
    try {
        if (!allTasks.value || !allTasks.value.length) {
            await fetchTasks();
        }
        const res = await fetch(`/api/articles?author_id=${user.value.id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            const data = await res.json();
            if (Array.isArray(data)) {
                // If a draft article was linked to a task that has been deleted,
                // verify that the task still exists in the database
                const validArticles = data.filter(a => {
                    if (a.status === 'draft') {
                        return allTasks.value.some(t =>
                            (t.article_id && t.article_id === a.id) ||
                            (t.raw?.article_id && t.raw?.article_id === a.id) ||
                            (t.title && t.title.toLowerCase().trim() === (a.title || '').toLowerCase().trim())
                        );
                    }
                    return true;
                });

                // Videos this writer was credited on (e.g. as scriptwriter) live here too
                const credited = await fetchCreditedVideos(user.value.id);
                credited.forEach(video => {
                    if (!validArticles.some(a => a.id === video.id)) validArticles.push(video);
                });

                articles.value = validArticles.map(a => {
                    return {
                        id: a.id,
                        title: a.title,
                        status: a.status,
                        section: a.section?.name || 'News',
                        type: a.type || 'article',
                        lastUpdated: a.updated_at || a.created_at,
                        raw: a
                    };
                });
            }
        }
    } catch (err) {
        console.warn('Could not fetch backend articles:', err);
    }
};

watch(activeTab, (tab) => {
    if (tab === 'articles') {
        fetchArticles();
    } else if (tab === 'tasks') {
        fetchTasks();
    }
});

// ── Process Artist Submissions ───────────────────────────────────────────────────
const processArtistSubmissions = () => {
    const submissions = [];

    // Get the writer's tasks to find related artist tasks
    const writerTasks = allTasks.value.filter(t =>
        t.assignee_id === user.value.id && t.type === 'writing'
    );

    writerTasks.forEach(writerTask => {
        const writerClean = (writerTask.title || '').replace(/\s*\([^)]*(visuals|video|graphics|photo|illustration|pj)[^)]*\)/i, '').trim().toLowerCase();

        // Find related artist tasks by exact base title matching
        const relatedArtistTasks = allTasks.value.filter(artistTask => {
            if (artistTask.assignee_id === user.value.id) return false; // Skip writer's own tasks
            if (artistTask.type === 'writing') return false; // Skip other writing tasks

            // 1. If both tasks share article_id
            if (artistTask.article_id && writerTask.article_id && artistTask.article_id === writerTask.article_id) {
                return true;
            }

            // 2. Strict exact match on base title
            const artistClean = (artistTask.title || '').replace(/\s*\([^)]*(visuals|video|graphics|photo|illustration|pj)[^)]*\)/i, '').trim().toLowerCase();

            return artistClean && writerClean && artistClean === writerClean;
        });

        relatedArtistTasks.forEach(artistTask => {
            const thumbnail = parseNotesField(artistTask.notes, 'Thumbnail') || '';
            const mediaUploads = parseNotesField(artistTask.notes, 'Media Uploads') || '';

            // Only add if there are actual submissions
            if (thumbnail || mediaUploads) {
                submissions.push({
                    id: artistTask.id,
                    articleTitle: writerTask.title,
                    artistName: artistTask.assignee?.name || 'Unknown Artist',
                    artistRole: artistTask.assignee?.secondary_role || 'Artist/PJ',
                    submissionType: thumbnail && mediaUploads ? 'Thumbnail + Media' : (thumbnail ? 'Thumbnail' : 'Media Uploads'),
                    thumbnail: thumbnail,
                    mediaUploads: mediaUploads,
                    status: artistTask.status || 'pending',
                    submittedAt: artistTask.updated_at || artistTask.created_at,
                    raw: artistTask
                });
            }
        });
    });

    // Sort by submission date (newest first)
    submissions.sort((a, b) => new Date(b.submittedAt) - new Date(a.submittedAt));

    artistSubmissions.value = submissions;
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

// ── Articles Pagination ─────────────────────────────────────────────────────────
const filteredArticles = computed(() => {
    const byStatus = selectedStatusFilter.value === 'all'
        ? articles.value
        : articles.value.filter(article => article.status === selectedStatusFilter.value);

    return byStatus.filter(article => matches(
        article.title,
        typeof article.section === 'object' ? article.section?.name : article.section,
        formatStatus(article.status),
    ));
});

const shownArtistSubmissions = computed(() => artistSubmissions.value.filter(item => matches(
    item.articleTitle,
    item.artistName,
    item.submissionType,
    formatStatus(item.status),
)));

const shownAcademicYears = computed(() => searchAcademicYears(staffAcademicYears.value, matches));

watch(searchQuery, () => { articlesCurrentPage.value = 1; });

const paginatedArticles = computed(() => {
    const start = (articlesCurrentPage.value - 1) * articlesPerPage;
    const end = start + articlesPerPage;
    return filteredArticles.value.slice(start, end);
});

const totalArticlesPages = computed(() => {
    return Math.ceil(filteredArticles.value.length / articlesPerPage);
});

const formatArticleDate = (dateStr) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) + ' • ' + d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
};

const changeArticlesPage = (page) => {
    if (page >= 1 && page <= totalArticlesPages.value) {
        articlesCurrentPage.value = page;
    }
};

const formatStatus = (status) => {
    const statusMap = {
        'draft': 'Draft',
        'submitted': 'For Review',
        'under_review': 'Under Review',
        'endorsed': 'Endorsed',
        'approved': 'Approved',
        'rejected': 'Rejected',
        'published': 'Published'
    };
    return statusMap[status] || status.charAt(0).toUpperCase() + status.slice(1);
};

const getStatusClass = (status) => {
    const classMap = {
        'draft': 'status-draft',
        'submitted': 'status-for-review',
        'under_review': 'status-under-revision',
        'endorsed': 'status-endorsed',
        'approved': 'status-approved',
        'rejected': 'status-rejected',
        'published': 'status-published'
    };
    return classMap[status] || 'status-draft';
};

// ── Status Filter ───────────────────────────────────────────────────────────────
const statusFilterOptions = [
    { value: 'all', label: 'All Status' },
    { value: 'draft', label: 'Draft' },
    { value: 'submitted', label: 'For Review' },
    { value: 'under_review', label: 'Under Review' },
    { value: 'endorsed', label: 'Endorsed' },
    { value: 'approved', label: 'Approved' },
    { value: 'rejected', label: 'Rejected' },
    { value: 'published', label: 'Published' }
];

const statusFilterLabel = computed(() => {
    const option = statusFilterOptions.find(opt => opt.value === selectedStatusFilter.value);
    return option ? option.label : 'Status';
});

const toggleStatusFilter = () => {
    activeStatusFilter.value = !activeStatusFilter.value;
};

const selectStatusFilter = (value) => {
    selectedStatusFilter.value = value;
    activeStatusFilter.value = false;
    articlesCurrentPage.value = 1; // Reset to first page when filter changes
};

// Close dropdown when clicking outside
const handleStatusFilterClickOutside = (event) => {
    if (activeStatusFilter.value && !event.target.closest('.custom-filter')) {
        activeStatusFilter.value = false;
    }
};

// ── Modal & Action Handlers ─────────────────────────────────────────────────────
const openTaskModal = (task = {}) => {
    selectedTask.value = {
        ...task,
        title: task.title || 'Untitled Task',
        section: task.section || 'News',
        deadline: task.deadline || 'No deadline',
        priority: task.priority || 'Moderate',
        articleDesc: task.articleDesc || task.description || '',
        thumbnailDesc: task.thumbnailDesc || task.thumbnail || '',
        mediaUploads: Array.isArray(task.mediaUploads) ? task.mediaUploads : (task.mediaUploads || []),
        mediaArtist: task.mediaArtist || task.artistName || '',
        notes: task.notes || ''
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
        // Update the existing task with the draft data
        tasks.value[index] = {
            ...tasks.value[index],
            ...draftTask,
            status: 'in_progress',
            // Preserve the formatted deadline
            deadline: tasks.value[index].deadline
        };
    } else {
        tasks.value.unshift({
            ...draftTask,
            status: 'in_progress'
        });
    }

    // Update the corresponding article's taskData
    const articleIndex = articles.value.findIndex(a => a.title === draftTask.title);
    if (articleIndex !== -1) {
        articles.value[articleIndex].taskData = {
            ...articles.value[articleIndex].taskData,
            ...draftTask,
            status: 'in_progress'
        };
    }

    // Refresh articles to update task matching
    fetchArticles();

    // Don't refresh from backend - it would overwrite the local content changes
    // The backend only stores status, not article content/media
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

    // Update the corresponding article's taskData
    const articleIndex = articles.value.findIndex(a => a.title === submittedTask.title);
    if (articleIndex !== -1) {
        articles.value[articleIndex].taskData = {
            ...articles.value[articleIndex].taskData,
            ...submittedTask,
            status: 'submitted'
        };
    }

    // Refresh articles to update task matching
    fetchArticles();
};

const handleViewSubmissions = () => {
    activeTab.value = 'tasks';
};

const extractFileNameSW = (url) => {
    if (!url || typeof url !== 'string') return 'file';
    const parts = url.split('/');
    return parts[parts.length - 1] || 'file';
};

const staffRoleLabelSW = (role) => (role === 'staff_broadcaster' ? 'Staff Broadcaster' : 'Staff Artist');

// Builds the same article-preview shape EditorInChiefDashboard uses, so a writer's
// own published article opens the identical read-only preview.
const buildArticlePreviewData = (item = {}) => {
    const tasks = Array.isArray(item.tasks) ? item.tasks : [];
    const primaryTask = tasks.find(t => t.type === 'writing') || tasks[0] || null;
    const artistTask = tasks.find(t => ['illustration', 'photography', 'layout'].includes(t.type)) || null;
    const artist = artistTask?.assignee || null;

    const attachedFiles = [];
    if (item.cover_image) {
        attachedFiles.push({ name: extractFileNameSW(item.cover_image), type: 'image', url: item.cover_image });
    }
    if (Array.isArray(item.media_files)) {
        item.media_files.forEach((url) => {
            attachedFiles.push({ name: extractFileNameSW(url), type: 'image', url });
        });
    }

    return {
        ...item,
        raw_status: item.status,
        coverage: parseNotesField(primaryTask?.notes, 'Coverage') || '',
        artist_name: artist?.name || '',
        artist_email: artist?.email || '',
        artist_role: artist?.secondary_role || (artist ? staffRoleLabelSW(artist.role) : ''),
        artist_avatar: artist?.profile_picture_url || '',
        attached_files: attachedFiles,
    };
};

const openArticleModal = (article) => {
    // Videos have no writing workspace here, so they always open as the read-only preview
    if (article.raw?.type === 'video' || (article.status === 'published' && article.raw)) {
        selectedArticlePreview.value = buildArticlePreviewData(article.raw);
        isArticlePreviewOpen.value = true;
        return;
    }

    // Find the task by article_id, not by title matching
    const relatedTask = tasks.value.find(t => t.raw?.article_id === article.id);

    if (relatedTask) {
        // Use the real task data with article status
        openTaskModal({
            ...relatedTask,
            id: article.id,
            article_id: article.id,
            status: article.status,
            mediaUploads: Array.isArray(article.raw?.media_files) ? article.raw.media_files : (relatedTask.mediaUploads || []),
            raw: article.raw
        });
    } else {
        // Fallback: create task-like object from article data
        openTaskModal({
            id: article.id,
            article_id: article.id,
            title: article.title,
            section: article.section,
            status: article.status,
            type: article.type,
            articleDesc: 'Article saved. Open the workspace to view and edit full content.',
            thumbnailDesc: article.raw?.cover_image || '',
            mediaUploads: Array.isArray(article.raw?.media_files) ? article.raw?.media_files : [],
            deadline: 'No deadline',
            priority: 'Moderate',
            mediaArtist: '',
            notes: article.raw?.editor_notes || '',
            assignees: article.raw?.author ? [{
                name: article.raw.author.name,
                secondary_role: article.raw.author.secondary_role || '',
                role: article.raw.author.role || 'Staff Writer',
                avatar: article.raw.author.profile_picture ? `/storage/${article.raw.author.profile_picture}` : (article.raw.author.profile_picture_url || '')
            }] : [],
            raw: article.raw
        });
    }
};

const openArtistSubmissionModal = (submission) => {
    selectedArtistSubmission.value = submission;
    isArtistSubmissionModalOpen.value = true;
};

const onProfileUpdated = (e) => {
    if (e.detail) {
        user.value = e.detail;
    }
};

// ── Fetch Press Works ────────────────────────────────────────────────────────
const fetchPressWorks = async () => {
    if (!token) return;
    try {
        const res = await fetch('/api/press-works', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            const data = await res.json();
            if (data.academic_years && Array.isArray(data.academic_years)) {
                staffAcademicYears.value = data.academic_years;
                // Initialize all years as collapsed
                staffExpandedYears.value = {};
                data.academic_years.forEach(year => {
                    staffExpandedYears.value[year.academic_year] = false;
                });
            }
        }
    } catch (err) {
        console.warn('Could not fetch press works:', err);
    }
};

// ── Toggle Press Work Year ───────────────────────────────────────────────────
const toggleStaffYear = (year) => {
    staffExpandedYears.value[year] = !staffExpandedYears.value[year];
};

// ── Format Date ──────────────────────────────────────────────────────────────
const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

// ── Open Monitoring Sheet ────────────────────────────────────────────────────
const openMonitoringSheet = (sheet) => {
    if (sheet && sheet.id) {
        window.open(`/monitoring-sheet/${sheet.id}`, '_blank');
    } else {
        window.open('/monitoring-sheet', '_blank');
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

    // Fetch tasks first, THEN fetch articles so they can be matched properly
    await fetchTasks();
    await fetchArticles(); // Now articles can match with tasks that were just loaded
    await fetchPressWorks(); // Fetch press works for the current user
    processArtistSubmissions(); // Process artist submissions after tasks are loaded

    window.addEventListener('sparky:profile-updated', onProfileUpdated);
    document.addEventListener('click', handleStatusFilterClickOutside);
});

onUnmounted(() => {
    window.removeEventListener('sparky:profile-updated', onProfileUpdated);
    document.removeEventListener('click', handleStatusFilterClickOutside);
});
</script>
