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
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                            <line x1="9" y1="12" x2="15" y2="12"/>
                            <line x1="9" y1="16" x2="13" y2="16"/>
                        </svg>
                        My Tasks
                    </div>
                </a>

                <!-- My Works Nav Item -->
                <a href="#" class="nav-item" :class="{ active: activeTab === 'works' }" @click.prevent="activeTab = 'works'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        My Works
                    </div>
                </a>

                <!-- Recent Submissions Nav Item -->
                <a href="#" class="nav-item" :class="{ active: activeTab === 'submissions' }" @click.prevent="activeTab = 'submissions'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                            <path d="m9 16 2 2 4-4"/>
                        </svg>
                        Recent Submissions
                    </div>
                </a>

                <!-- Press Works Nav Item -->
                <a href="#" class="nav-item" :class="{ active: activeTab === 'pressWorks' }" @click.prevent="activeTab = 'pressWorks'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                <img :src="user.profile_picture ? ('/storage/' + user.profile_picture) : (user.profile_picture_url || 'https://api.dicebear.com/7.x/lorelei/svg?seed=' + (user.name || 'artist') + '&backgroundColor=ffd5dc')" alt="Profile">
                <div class="user-info">
                    <span class="role-badge" style="background-color: #1a73e8; color: white;">{{ formatRole(user.role, user.secondary_role) }}</span>
                    <h4>{{ user.name || 'Staff Artist' }}</h4>
                    <p>{{ user.email || 'artist@thesparkpub.com' }}</p>
                </div>
                <button class="settings-btn" type="button" aria-label="Open profile" title="View Profile" @click="router.push('/profile')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"></path><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"></path><path d="M12 2v2"></path><path d="M12 22v-2"></path><path d="m17 4-1.5 1.5"></path><path d="M22 12h-2"></path><path d="m17 20-1.5-1.5"></path><path d="M2 12h2"></path><path d="m7 4 1.5 1.5"></path><path d="m7 20 1.5-1.5"></path></svg>
                </button>
            </div>
            
            <button class="sign-out-btn" style="margin-top: 15px;" @click.prevent="performSignOut(router)">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Sign Out
            </button>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <MobileNavToggle />
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
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
                                <div v-for="task in pendingTasks" :key="task.id" :class="['task-card', cardThemeClass(task.priority)]" @click="openTaskModal(task)" style="cursor: pointer;">
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                        {{ formatPriorityLabel(task.priority) }}
                                    </span>
                                    <h4 class="card-title">{{ task.title }}</h4>
                                    <span class="card-subtitle">{{ task.article?.title || task.description || task.category }}</span>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                                <line x1="16" y1="2" x2="16" y2="6"/>
                                                <line x1="8" y1="2" x2="8" y2="6"/>
                                                <line x1="3" y1="10" x2="21" y2="10"/>
                                            </svg>
                                            {{ formatDeadline(task.deadline) }}
                                        </div>
                                        <div class="avatar-group">
                                            <img :src="task.assignee?.profile_picture ? ('/storage/' + task.assignee.profile_picture) : ('https://api.dicebear.com/7.x/lorelei/svg?seed=' + (task.assignee?.name || 'artist') + '&backgroundColor=ffd5dc')" alt="Assignee" class="assignee-avatar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Ongoing -->
                        <div class="kanban-column">
                            <h3 class="column-header">Ongoing ({{ ongoingTasks.length }})</h3>
                            <div class="cards-container">
                                <div v-for="task in ongoingTasks" :key="task.id" :class="['task-card', cardThemeClass(task.priority)]" @click="openTaskModal(task)" style="cursor: pointer;">
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                        {{ formatPriorityLabel(task.priority) }}
                                    </span>
                                    <h4 class="card-title">{{ task.title }}</h4>
                                    <span class="card-subtitle">{{ task.article?.title || task.description || task.category }}</span>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                                <line x1="16" y1="2" x2="16" y2="6"/>
                                                <line x1="8" y1="2" x2="8" y2="6"/>
                                                <line x1="3" y1="10" x2="21" y2="10"/>
                                            </svg>
                                            {{ formatDeadline(task.deadline) }}
                                        </div>
                                        <div class="avatar-group">
                                            <img :src="task.assignee?.profile_picture ? ('/storage/' + task.assignee.profile_picture) : ('https://api.dicebear.com/7.x/lorelei/svg?seed=' + (task.assignee?.name || 'artist') + '&backgroundColor=fed7aa')" alt="Assignee" class="assignee-avatar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column 3: Submitted -->
                        <div class="kanban-column">
                            <h3 class="column-header">Submitted ({{ submittedTasks.length }})</h3>
                            <div class="cards-container">
                                <div v-for="task in submittedTasks" :key="task.id" :class="['task-card', cardThemeClass(task.priority)]" @click="openTaskModal(task)" style="cursor: pointer;">
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                        {{ formatPriorityLabel(task.priority) }}
                                    </span>
                                    <h4 class="card-title">{{ task.title }}</h4>
                                    <span class="card-subtitle">{{ task.article?.title || task.description || task.category }}</span>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                                <line x1="16" y1="2" x2="16" y2="6"/>
                                                <line x1="8" y1="2" x2="8" y2="6"/>
                                                <line x1="3" y1="10" x2="21" y2="10"/>
                                            </svg>
                                            {{ formatDeadline(task.deadline) }}
                                        </div>
                                        <div class="avatar-group">
                                            <img :src="task.assignee?.profile_picture ? ('/storage/' + task.assignee.profile_picture) : ('https://api.dicebear.com/7.x/lorelei/svg?seed=' + (task.assignee?.name || 'artist') + '&backgroundColor=fecdd3')" alt="Assignee" class="assignee-avatar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MY WORKS TAB -->
                <div v-show="activeTab === 'works'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">My Works</h1>
                        <div class="filter-pills-group">
                            <div class="custom-filter" @click.stop>
                                <button type="button" class="filter-trigger" @click="activeWorksFilter = !activeWorksFilter">
                                    <span>{{ worksFilterLabel }}</span>
                                    <svg :class="{ rotated: activeWorksFilter }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>
                                <div v-if="activeWorksFilter" class="filter-menu">
                                    <button v-for="option in worksFilterOptions" :key="option.value" type="button" :class="{ selected: selectedWorksFilter === option.value }" @click="selectWorksFilter(option.value)">{{ option.label }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="articles-card">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px;">Title</th>
                                    <th>Writer</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="padding-right: 28px;">Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="work in paginatedWorks" :key="work.id" @click="openWork(work)" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">
                                        <div>{{ work.title }}</div>
                                        <div style="font-size: 11px; color: #94a3b8; margin-top: 2px; font-weight: 500;">{{ work.section }}</div>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <img :src="work.writerAvatar" :alt="work.writerName" style="width: 26px; height: 26px; border-radius: 50%; object-fit: cover;">
                                            <span style="font-weight: 600; font-size: 13px; color: #334155;">{{ work.writerName }}</span>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="status-pill" :class="getWorkStatusClass(work.status)">{{ formatWorkStatus(work.status) }}</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">{{ formatSubmittedDate(work.lastUpdated) }}</td>
                                </tr>
                                <tr v-if="filteredWorks.length === 0">
                                    <td colspan="4" style="text-align: center; padding: 40px; color: #64748b;">
                                        No works found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-container" v-if="filteredWorks.length > 0">
                        <div class="pagination-pill">
                            <button class="page-btn" @click="worksCurrentPage--" :disabled="worksCurrentPage === 1">Previous</button>
                            <a v-for="page in Math.min(totalWorksPages, 5)" :key="page" href="#" class="page-number" :class="{ active: page === worksCurrentPage }" @click.prevent="worksCurrentPage = page">{{ page }}</a>
                            <span v-if="totalWorksPages > 5" class="page-dots">&bull;&bull;&bull;</span>
                            <button class="page-btn" @click="worksCurrentPage++" :disabled="worksCurrentPage === totalWorksPages">Next</button>
                            <div class="page-results-count">
                                Showing <strong>{{ paginatedWorks.length }}</strong> of {{ filteredWorks.length }} works
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RECENT SUBMISSIONS TAB -->
                <div v-show="activeTab === 'submissions'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header" style="margin-bottom: 4px;">
                        <h1 class="page-title">Recent Submissions</h1>
                        <p style="font-size: 13px; color: #94a3b8; margin: 0;">Visual assets you have submitted to writers</p>
                    </div>

                    <!-- Filled state -->
                    <div class="articles-card" v-if="recentSubmissions.length > 0">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px;">Task Title</th>
                                    <th>Sent To</th>
                                    <th>Assets</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="padding-right: 28px;">Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="sub in paginatedSubmissions"
                                    :key="sub.id"
                                    @click="openTaskModal(sub.raw)"
                                    style="cursor: pointer;"
                                    class="clickable-row"
                                >
                                    <td style="padding-left: 28px;">
                                        <div style="font-weight: 700; color: #1e293b;">{{ sub.title }}</div>
                                        <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">{{ sub.section }}</div>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <img
                                                :src="sub.writerAvatar"
                                                :alt="sub.writerName"
                                                style="width: 26px; height: 26px; border-radius: 50%; object-fit: cover;"
                                            >
                                            <div>
                                                <div style="font-weight: 600; font-size: 13px; color: #334155;">{{ sub.writerName }}</div>
                                                <div style="font-size: 11px; color: #94a3b8;">Staff Writer</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                            <span v-if="sub.hasThumbnail" style="background: #eff6ff; color: #1d4ed8; border-radius: 999px; padding: 2px 10px; font-size: 11px; font-weight: 600;">🖼 Thumbnail</span>
                                            <span v-if="sub.mediaCount > 0" style="background: #f0fdf4; color: #166534; border-radius: 999px; padding: 2px 10px; font-size: 11px; font-weight: 600;">📁 {{ sub.mediaCount }} File{{ sub.mediaCount > 1 ? 's' : '' }}</span>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <span :class="getSubmissionStatusClass(sub.status)">{{ getSubmissionStatusLabel(sub.status) }}</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b; font-size: 13px;">{{ sub.submittedAt }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty state -->
                    <div v-else style="padding: 60px 40px; text-align: center; background: #f8fafc; border-radius: 20px; border: 1px dashed #cbd5e1;">
                        <div style="font-size: 40px; margin-bottom: 12px;">📭</div>
                        <p style="font-weight: 700; color: #334155; margin: 0 0 4px;">No submissions yet</p>
                        <p style="color: #94a3b8; font-size: 13px; margin: 0;">Complete a task and send your visuals to a writer to see them here.</p>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-container" v-if="totalSubmissionsPages > 1">
                        <div class="pagination-pill">
                            <button class="page-btn" :disabled="submissionsCurrentPage === 1" @click="submissionsCurrentPage--">Previous</button>
                            <a
                                v-for="p in totalSubmissionsPages"
                                :key="p"
                                href="#"
                                class="page-number"
                                :class="{ active: p === submissionsCurrentPage }"
                                @click.prevent="submissionsCurrentPage = p"
                            >{{ p }}</a>
                            <div class="page-results-count">
                                Showing <strong>{{ paginatedSubmissions.length }}</strong> of {{ recentSubmissions.length }} results
                            </div>
                        </div>
                    </div>
                    <div v-if="recentSubmissions.length > 0 && totalSubmissionsPages <= 1" style="font-size: 13px; color: #94a3b8; text-align: right;">
                        Showing {{ recentSubmissions.length }} result{{ recentSubmissions.length !== 1 ? 's' : '' }}
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
                                    <span :class="`pub-badge pub-${(sheet.publication_type || '').toLowerCase()}`" style="padding: 4px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; white-space: nowrap;">{{ sheet.publication_type }}</span>
                                    <span style="flex: 1;"></span>
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

    <!-- Assigned Task Modal -->
    <AssignedTaskModal
        :is-open="isAssignedTaskModalOpen"
        :task-data="selectedTask"
        @close="isAssignedTaskModalOpen = false"
        @open-workspace="handleOpenWorkspace"
    />

    <!-- Read-only Article Preview (published works) -->
    <ArticlePreviewModal
        :is-open="isArticlePreviewOpen"
        :article-data="selectedArticlePreview"
        read-only
        @close="isArticlePreviewOpen = false"
    />

    <!-- Artist Visuals & Media Workspace Modal -->
    <ArtistWorkspaceModal
        :is-open="isWorkspaceModalOpen"
        :task-data="selectedTask"
        @close="isWorkspaceModalOpen = false"
        @task-submitted="handleTaskSubmitted"
        @task-saved-as-draft="handleTaskSavedAsDraft"
    />
</template>

<script setup>
import MobileNavToggle from '../../components/MobileNavToggle.vue';
import { makeMatcher, searchAcademicYears, noPressWorksText, useDebouncedSearch } from '../../utils/dashboardSearch';
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { lazyModal } from '../../utils/lazyModal';
import { useRouter } from 'vue-router';
const AssignedTaskModal = lazyModal(() => import('../../components/AssignedTaskModal.vue'));
const ArtistWorkspaceModal = lazyModal(() => import('../../components/ArtistWorkspaceModal.vue'));
const ArticlePreviewModal = lazyModal(() => import('../../components/ArticlePreviewModal.vue'));
import NotificationsPopover from '../../components/NotificationsPopover.vue';
import { signOut as performSignOut } from '../../utils/auth';

// Utilities
const formatRole = (role, secondaryRole) => {
    if (secondaryRole) return secondaryRole;
    const roles = { admin: 'Administrator', eic: 'EIC', section_editor: 'Section Editor', staff_writer: 'Staff Writer', staff_artist: 'Staff Artist', staff_broadcaster: 'Staff Broadcaster' };
    return roles[role] || role;
};

// Reads one "Key: value" field out of a task's notes text.
const parseNotesField = (notes, key) => {
    if (!notes || typeof notes !== 'string') return '';
    const match = notes.match(new RegExp(`${key}:\\s*([^|]+)`, 'i'));
    return match ? match[1].trim() : '';
};

// Formats a task's deadline date and due time for its card ("No deadline" when empty).
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
            const h = timeObj.getHours();
            const m = timeObj.getMinutes();
            if (h !== 0 || m !== 0) {
                timePart = timeObj.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
            }
        }
    }

    return timePart ? `${datePart} • ${timePart}` : datePart;
};

// Shows a task priority as a readable label.
const formatPriorityLabel = (priority) => {
    const labels = { low: 'Low', medium: 'Medium', high: 'High', urgent: 'Urgent', critical: 'Critical' };
    return labels[(priority || '').toLowerCase()] || priority || 'Medium';
};

// A task's priority picks the card colour. "medium" is styled as .card-moderate; a priority with no theme falls back to it
const CARD_THEMES = { low: 'card-low', medium: 'card-moderate', high: 'card-high', urgent: 'card-urgent', critical: 'card-urgent' };
// The CSS class that colours a task card by its priority.
const cardThemeClass = (priority) => CARD_THEMES[String(priority || '').toLowerCase()] || 'card-moderate';

// The CSS class of a priority badge.
const getPriorityClass = (priority) => {
    const classes = { low: 'priority-low', medium: 'priority-medium', high: 'priority-high', urgent: 'priority-critical', critical: 'priority-critical' };
    return classes[(priority || '').toLowerCase()] || 'priority-medium';
};

// Formats a date as "Oct 1, 2026".
const formatDate = (date) => {
    if (!date) return '';
    const d = new Date(date);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

// Router
const router = useRouter();

// State
const user = ref({});
const token = ref('');
const tasks = ref([]);
const allTasks = ref([]);
const activeTab = ref('tasks');
const isAssignedTaskModalOpen = ref(false);
const isWorkspaceModalOpen = ref(false);
const selectedTask = ref({});

// ── Press Works State ─────────────────────────────────────────────────────────
const staffAcademicYears = ref([]);
const shownAcademicYears = computed(() => searchAcademicYears(staffAcademicYears.value, matches));
const staffExpandedYears = ref({});

const articles = ref([]);
const isArticlePreviewOpen = ref(false);
const selectedArticlePreview = ref({});

const articleById = computed(() => Object.fromEntries(articles.value.map(a => [a.id, a])));

// Once the linked article is published, the artist's job is over: the task leaves
// My Tasks and lives on as a work under My Works.
const isTaskDone = (task) => {
    const article = task.article || articleById.value[task.linked_article_id];
    return article?.status === 'published';
};

// ── Search (the box at the top searches whichever tab is open) ────────────────
const { input: searchInput, query: searchQuery } = useDebouncedSearch();
const matches = makeMatcher(searchQuery);
const searchPlaceholder = computed(() => ({
    tasks: 'Search my tasks',
    works: 'Search my works',
    submissions: 'Search my submissions',
    pressWorks: 'Search press works',
}[activeTab.value] || 'Search'));

// The name of a task's section, whether it is stored as an object or as text.
const sectionNameOf = (t) => (typeof t.section === 'object' ? t.section?.name : t.section) || '';

// Computed properties
const activeTasks = computed(() => tasks.value.filter(t => !isTaskDone(t)));
const searchedTasks = computed(() => activeTasks.value.filter(t => matches(t.title, sectionNameOf(t), t.priority, t.type)));
const pendingTasks = computed(() => searchedTasks.value.filter(t => t.status === 'pending'));
const ongoingTasks = computed(() => searchedTasks.value.filter(t => t.status === 'ongoing' || t.status === 'in_progress' || t.status === 'returned'));
const submittedTasks = computed(() => searchedTasks.value.filter(t => t.status === 'submitted' || t.status === 'completed'));

// ── My Works (articles the artist collaborated on) ───────────────────────────
const worksPerPage = 8;
const worksCurrentPage = ref(1);
const activeWorksFilter = ref(false);
const selectedWorksFilter = ref('all');

const worksFilterOptions = [
    { value: 'all', label: 'All Status' },
    { value: 'draft', label: 'Draft' },
    { value: 'submitted', label: 'For Review' },
    { value: 'under_review', label: 'Under Review' },
    { value: 'endorsed', label: 'Endorsed' },
    { value: 'approved', label: 'Approved' },
    { value: 'rejected', label: 'Rejected' },
    { value: 'scheduled', label: 'Scheduled' },
    { value: 'published', label: 'Published' }
];
const worksFilterLabel = computed(() => worksFilterOptions.find(o => o.value === selectedWorksFilter.value)?.label || 'Status');

// Applies a filter to the works list and goes back to the first page.
const selectWorksFilter = (value) => {
    selectedWorksFilter.value = value;
    activeWorksFilter.value = false;
    worksCurrentPage.value = 1;
};

// The picture address for a person: the uploaded photo, otherwise a generated avatar.
const avatarFor = (person, background) => person?.profile_picture
    ? `/storage/${person.profile_picture}`
    : `https://api.dicebear.com/7.x/lorelei/svg?seed=${encodeURIComponent(person?.name || 'writer')}&backgroundColor=${background}`;

const works = computed(() => {
    const myTaskByArticle = {};
    tasks.value.forEach(t => { if (t.linked_article_id) myTaskByArticle[t.linked_article_id] = t; });

    return articles.value
        .filter(a => myTaskByArticle[a.id] || (a.tasks || []).some(t => t.assignee_id === user.value.id && t.type !== 'writing'))
        .map(a => ({
            id: a.id,
            title: a.title || 'Untitled Article',
            section: a.section?.name || 'Unassigned',
            status: a.status,
            writerName: a.author?.name || 'Staff Writer',
            writerAvatar: avatarFor(a.author, 'd1fae5'),
            lastUpdated: a.updated_at || a.created_at,
            task: myTaskByArticle[a.id] || null,
            raw: a
        }))
        .sort((a, b) => new Date(b.lastUpdated) - new Date(a.lastUpdated));
});

const filteredWorks = computed(() => (selectedWorksFilter.value === 'all'
    ? works.value
    : works.value.filter(w => w.status === selectedWorksFilter.value))
    .filter(w => matches(w.title, w.section, w.writerName, formatWorkStatus(w.status))));
const totalWorksPages = computed(() => Math.max(1, Math.ceil(filteredWorks.value.length / worksPerPage)));
const paginatedWorks = computed(() => {
    const start = (worksCurrentPage.value - 1) * worksPerPage;
    return filteredWorks.value.slice(start, start + worksPerPage);
});

// Shows a work's status as a readable label.
const formatWorkStatus = (status) => {
    const map = { draft: 'Draft', submitted: 'For Review', under_review: 'Under Review', endorsed: 'Endorsed', approved: 'Approved', rejected: 'Rejected', scheduled: 'Scheduled', published: 'Published' };
    return map[status] || (status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Draft');
};

// The CSS class that colours a work's status label.
const getWorkStatusClass = (status) => {
    const map = {
        draft: 'status-draft',
        submitted: 'status-for-review',
        under_review: 'status-under-revision',
        endorsed: 'status-endorsed',
        approved: 'status-approved',
        rejected: 'status-rejected',
        scheduled: 'status-for-review',
        published: 'status-published'
    };
    return map[status] || 'status-draft';
};

// The file name at the end of a file address.
const fileNameOf = (url) => String(url || '').split('/').pop() || 'file';

// Same read-only preview shape the writer and EIC dashboards use
const buildArticlePreviewData = (item = {}) => {
    const itemTasks = Array.isArray(item.tasks) ? item.tasks : [];
    const primaryTask = itemTasks.find(t => t.type === 'writing') || itemTasks[0] || null;
    const artistTask = itemTasks.find(t => ['illustration', 'photography', 'layout'].includes(t.type)) || null;
    const artist = artistTask?.assignee || null;

    const attachedFiles = [];
    if (item.cover_image) attachedFiles.push({ name: fileNameOf(item.cover_image), type: 'image', url: item.cover_image });
    (Array.isArray(item.media_files) ? item.media_files : []).forEach(url => attachedFiles.push({ name: fileNameOf(url), type: 'image', url }));

    return {
        ...item,
        raw_status: item.status,
        coverage: parseNotesField(primaryTask?.notes, 'Coverage') || '',
        artist_name: artist?.name || '',
        artist_email: artist?.email || '',
        artist_avatar: artist?.profile_picture_url || '',
        attached_files: attachedFiles,
    };
};

// Opens a work: a published one as a read-only preview, an unfinished one through its task's details.
const openWork = (work) => {
    if (work.status === 'published') {
        selectedArticlePreview.value = buildArticlePreviewData(work.raw);
        isArticlePreviewOpen.value = true;
    } else if (work.task) {
        openTaskModal(work.task);
    }
};

// ── Recent Submissions (for submissions tab) ─────────────────────────────────
const submissionsPerPage = 8;
const submissionsCurrentPage = ref(1);

const recentSubmissions = computed(() => {
    return tasks.value
        .filter(t => t.status === 'submitted' || t.status === 'completed')
        .map(t => {
            const thumbnail = parseNotesField(t.notes, 'Thumbnail') || '';
            const mediaRaw = parseNotesField(t.notes, 'Media Uploads') || '';
            const mediaCount = mediaRaw ? mediaRaw.split(',').filter(Boolean).length : 0;
            const writer = t.writer || null;
            const writerName = writer?.name || 'Staff Writer';
            const writerAvatar = writer?.profile_picture
                ? `/storage/${writer.profile_picture}`
                : `https://api.dicebear.com/7.x/lorelei/svg?seed=${encodeURIComponent(writerName)}&backgroundColor=d1fae5`;
            return {
                id: t.id,
                title: t.title || 'Untitled Task',
                section: t.section?.name || (typeof t.section === 'string' ? t.section : '') || 'News',
                status: t.status,
                hasThumbnail: !!thumbnail,
                mediaCount,
                writerName,
                writerAvatar,
                submittedAt: formatSubmittedDate(t.updated_at || t.created_at),
                raw: t
            };
        })
        .filter(item => matches(item.title, item.section, item.writerName))
        .sort((a, b) => new Date(b.raw.updated_at || b.raw.created_at) - new Date(a.raw.updated_at || a.raw.created_at));
});

const paginatedSubmissions = computed(() => {
    const start = (submissionsCurrentPage.value - 1) * submissionsPerPage;
    return recentSubmissions.value.slice(start, start + submissionsPerPage);
});

const totalSubmissionsPages = computed(() =>
    Math.ceil(recentSubmissions.value.length / submissionsPerPage)
);

// Formats a submission date for the list ("—" when empty).
const formatSubmittedDate = (dateStr) => {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
        + ' • '
        + d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
};

// Shows a submission status as a readable label.
const getSubmissionStatusLabel = (status) => {
    const map = { submitted: 'Submitted', completed: 'Reviewed', in_progress: 'In Progress', pending: 'Pending' };
    return map[status] || status.charAt(0).toUpperCase() + status.slice(1);
};

// The CSS classes that colour a submission status label.
const getSubmissionStatusClass = (status) => {
    const map = {
        submitted: 'status-pill status-for-review',
        completed: 'status-pill status-approved',
        in_progress: 'status-pill status-under-revision',
        pending: 'status-pill status-draft'
    };
    return map[status] || 'status-pill status-draft';
};

// Fetch user data
const fetchUser = async () => {
    try {
        const response = await fetch('/api/me', {
            headers: { 'Authorization': `Bearer ${token.value}` }
        });
        if (response.ok) {
            user.value = await response.json();
        }
    } catch (e) {
        console.error('Failed to fetch user:', e);
    }
};

// Fetch tasks
const fetchTasks = async () => {
    try {
        const response = await fetch('/api/tasks', {
            headers: { 'Authorization': `Bearer ${token.value}` }
        });
        if (response.ok) {
            const data = await response.json();
            const allTasksList = Array.isArray(data) ? data : (data.tasks || []);
            allTasks.value = allTasksList;

            tasks.value = allTasksList
                .filter(t => t.assignee_id === user.value.id)
                .map(t => {
                    // Find paired writer task by matching title prefix or article_id
                    let pairedWriter = null;
                    let linkedArticleId = t.article_id || null;
                    if (t.article_id) {
                        const wt = allTasksList.find(other => other.article_id === t.article_id && other.type === 'writing');
                        if (wt && wt.assignee) pairedWriter = wt.assignee;
                    }
                    if (!pairedWriter) {
                        const cleanTitle = (t.title || '').replace(/\s*\([^)]*(visuals|video|graphics|photo|illustration|pj)[^)]*\)/i, '').trim().toLowerCase();
                        const wt = allTasksList.find(other => {
                            if (other.assignee_id === user.value.id) return false;
                            if (other.type !== 'writing') return false;
                            const otherClean = (other.title || '').replace(/\s*\([^)]*(visuals|video|graphics|photo|illustration|pj)[^)]*\)/i, '').trim().toLowerCase();
                            return otherClean && cleanTitle && otherClean === cleanTitle;
                        });
                        if (wt && wt.assignee) pairedWriter = wt.assignee;
                        // Artist tasks are created before the article exists, so they may not carry an
                        // article_id yet — fall back to the paired writing task's article.
                        if (wt && !linkedArticleId) linkedArticleId = wt.article_id || null;
                    }

                    return {
                        ...t,
                        linked_article_id: linkedArticleId,
                        writer: pairedWriter || { name: 'Staff Writer', role: 'Staff Writer' }
                    };
                })
                .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        }
    } catch (e) {
        console.error('Failed to fetch tasks:', e);
    }
};

// Fetch articles (drives My Works and hides tasks whose article is published)
const fetchArticles = async () => {
    try {
        const response = await fetch('/api/articles', {
            headers: { 'Authorization': `Bearer ${token.value}`, 'Accept': 'application/json' }
        });
        if (response.ok) {
            const data = await response.json();
            articles.value = Array.isArray(data) ? data : [];
        }
    } catch (e) {
        console.error('Failed to fetch articles:', e);
    }
};

watch(activeTab, (tab) => {
    if (tab === 'tasks') {
        fetchTasks();
        fetchArticles();
    } else if (tab === 'works') {
        fetchArticles();
    }
});

watch(selectedWorksFilter, () => { worksCurrentPage.value = 1; });
watch(searchQuery, () => { worksCurrentPage.value = 1; submissionsCurrentPage.value = 1; });

// Closes the works filter menu when the user clicks outside it.
const closeWorksFilter = (event) => {
    if (activeWorksFilter.value && !event.target.closest('.custom-filter')) {
        activeWorksFilter.value = false;
    }
};

// Open task modal
const openTaskModal = (task = {}) => {
    console.log('Opening task modal with task:', task);
    const dueTime = parseNotesField(task.notes, 'Due Time');
    const sectionName = task.section?.name || (typeof task.section === 'string' ? task.section : parseNotesField(task.notes, 'Section')) || 'News';
    const formattedDeadline = formatDeadline(task.deadline, dueTime);

    selectedTask.value = {
        ...task,
        title: task.title || 'Untitled Task',
        section: sectionName,
        coverage: parseNotesField(task.notes, 'Coverage') || '',
        dueTime: dueTime,
        deadline: formattedDeadline,
        priority: task.priority || 'medium',
        status: task.status || 'pending',
        articleDesc: task.description || task.articleDesc || '',
        thumbnailDesc: parseNotesField(task.notes, 'Thumbnail') || task.thumbnailDesc || '',
        mediaUploads: Array.isArray(task.mediaUploads) ? task.mediaUploads : (task.mediaUploads || []),
        notes: task.notes || '',
        assignee: task.assignee || user.value,
        writer: task.writer || null,
        raw: task
    };
    isAssignedTaskModalOpen.value = true;
};

// Handle workspace opening
const handleOpenWorkspace = (taskData) => {
    isAssignedTaskModalOpen.value = false;
    selectedTask.value = taskData || selectedTask.value;
    isWorkspaceModalOpen.value = true;
};

// Updates the task in the list after a draft was saved in the workspace.
const handleTaskSavedAsDraft = (draftTask) => {
    const targetId = draftTask.id || selectedTask.value.id;
    const index = tasks.value.findIndex(t => t.id === targetId || t.title === draftTask.title);
    if (index !== -1) {
        tasks.value[index] = {
            ...tasks.value[index],
            ...draftTask,
            status: 'in_progress',
            deadline: tasks.value[index].deadline
        };
    }
    fetchTasks();
};

// Updates the task in the list after it was submitted.
const handleTaskSubmitted = (submittedTask) => {
    const targetId = submittedTask.id || selectedTask.value.id;
    const index = tasks.value.findIndex(t => t.id === targetId || t.title === submittedTask.title);
    if (index !== -1) {
        tasks.value[index] = {
            ...tasks.value[index],
            ...submittedTask,
            status: 'submitted'
        };
    }
    fetchTasks();
};

// Open monitoring sheet
const openMonitoringSheet = (sheet) => {
    if (sheet && sheet.id) {
        window.open(`/monitoring-sheet/${sheet.id}`, '_blank');
    }
};

// ── Fetch Press Works ─────────────────────────────────────────────────────────
const fetchPressWorks = async () => {
    if (!token.value) return;
    try {
        const res = await fetch('/api/press-works', {
            headers: {
                'Authorization': `Bearer ${token.value}`,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            const data = await res.json();
            if (data.academic_years && Array.isArray(data.academic_years)) {
                staffAcademicYears.value = data.academic_years;
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

// ── Toggle Press Work Year ────────────────────────────────────────────────────
const toggleStaffYear = (year) => {
    staffExpandedYears.value[year] = !staffExpandedYears.value[year];
};

// Sign out
const handleSignOut = async () => {
    await performSignOut(router);
};

// Lifecycle hooks
onMounted(() => {
    const userData = JSON.parse(localStorage.getItem('sparky_user') || '{}');
    const userToken = localStorage.getItem('sparky_token');

    if (userData && userData.id) {
        user.value = userData;
    }
    if (userToken) {
        token.value = userToken;
    }

    if (user.value.id && token.value) {
        fetchUser();
        fetchTasks();
        fetchArticles();
        fetchPressWorks();
    }

    document.addEventListener('click', closeWorksFilter);

    // Refresh user on storage change
    const handleStorageChange = (e) => {
        if (e.key === 'sparky_user') {
            user.value = JSON.parse(e.newValue || '{}');
        }
        if (e.key === 'sparky_token') {
            token.value = e.newValue;
        }
    };

    window.addEventListener('storage', handleStorageChange);

    onUnmounted(() => {
        window.removeEventListener('storage', handleStorageChange);
        document.removeEventListener('click', closeWorksFilter);
    });
});
</script>
