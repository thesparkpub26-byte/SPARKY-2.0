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
                <a href="#" class="nav-item" :class="{ active: activeTab === 'overview' }" @click.prevent="activeTab = 'overview'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        Overview
                    </div>
                </a>
                <a href="#" class="nav-item" :class="{ active: activeTab === 'endorsements' }" @click.prevent="activeTab = 'endorsements'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        Endorsements
                    </div>
                </a>
                <a href="#" class="nav-item" :class="{ active: activeTab === 'pressWorks' || activeTab === 'press-works' }" @click.prevent="activeTab = 'pressWorks'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        Press Works
                    </div>
                </a>
                <a href="#" class="nav-item" :class="{ active: activeTab === 'my-articles' }" @click.prevent="activeTab = 'my-articles'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10 9 9 9 8 9" />
                        </svg>
                        My Articles
                    </div>
                </a>
                <a href="#" class="nav-item" :class="{ active: activeTab === 'assignments' }" @click.prevent="activeTab = 'assignments'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        Assignments
                    </div>
                </a>
                <a href="#" class="nav-item" :class="{ active: activeTab === 'articles' }" @click.prevent="activeTab = 'articles'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <line x1="10" y1="9" x2="8" y2="9"/>
                        </svg>
                        Published Articles
                    </div>
                </a>
                <a href="#" class="nav-item" :class="{ active: activeTab === 'contributors' }" @click.prevent="activeTab = 'contributors'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        Contributors
                    </div>
                </a>
                <a href="#" class="nav-item" :class="{ active: activeTab === 'archive' || activeTab === 'archive-year' }" @click.prevent="activeTab = 'archive'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="21 8 21 21 3 21 3 8"/>
                            <rect x="1" y="3" width="22" height="5"/>
                            <line x1="10" y1="12" x2="14" y2="12"/>
                        </svg>
                        Archive
                    </div>
                </a>
                <a href="#" class="nav-item" :class="{ active: activeTab === 'analytics' }" @click.prevent="activeTab = 'analytics'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"/>
                            <line x1="12" y1="20" x2="12" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                        Analytics
                    </div>
                </a>
            </nav>

            <div class="user-profile">
                <img :src="eicUser.profile_picture_url || 'https://picsum.photos/200?random=22'" alt="Profile">
                <div class="user-info">
                    <span class="role-badge" style="background-color: #1a73e8; color: white;">{{ formatRole(eicUser.role) }}</span>
                    <h4>{{ eicUser.name || 'Editor-in-Chief' }}</h4>
                    <p>{{ eicUser.email || 'eic@thesparkpub.com' }}</p>
                </div>
                <button class="settings-btn" type="button" aria-label="Open profile" @click="router.push('/profile')">
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
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input v-model="searchQuery" type="search" :placeholder="searchPlaceholder" aria-label="Search current view">
                </div>
                <div class="top-header-right" style="display: flex; align-items: center; gap: 16px;">
                    <button v-if="activeTab === 'contributors'" class="assign-task-btn" type="button" @click.prevent="openNewContributorModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Add Contributor</span>
                    </button>
                    <button v-else class="assign-task-btn" type="button" @click.prevent="isAssignTaskModalOpen = true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Assign Task</span>
                    </button>
                    <NotificationsPopover />
                </div>
            </header>

            <!-- Dynamic Content Container -->
            <div class="content-container fade-in">

                <!-- OVERVIEW TAB -->
                <div v-show="activeTab === 'overview'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="margin-bottom: 4px;">
                        <h1 class="page-title">Overview</h1>
                    </div>

                    <div class="system-summary-card">
                        <h3 class="system-summary-header">System Summary</h3>
                        <div class="metrics-grid">
                            <div class="metric-card metric-total-articles">
                                <div class="metric-icon-circle">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="metric-val">{{ formatCount(eicOverview.summary.articles) }}</div>
                                    <div class="metric-lbl">Total Articles</div>
                                </div>
                            </div>
                            <div class="metric-card metric-endorsements">
                                <div class="metric-icon-circle">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="metric-val">{{ formatCount(eicOverview.summary.endorsements) }}</div>
                                    <div class="metric-lbl">Endorsements</div>
                                </div>
                            </div>
                            <div class="metric-card metric-ready-publish">
                                <div class="metric-icon-circle">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="metric-val">{{ formatCount(eicOverview.summary.ready_to_publish) }}</div>
                                    <div class="metric-lbl">Ready to Publish</div>
                                </div>
                            </div>
                            <div class="metric-card metric-published">
                                <div class="metric-icon-circle">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="metric-val">{{ formatCount(eicOverview.summary.published) }}</div>
                                    <div class="metric-lbl">Published</div>
                                </div>
                            </div>
                        </div>
                        <div class="updated-time">{{ updatedLabel }}</div>
                    </div>

                    <div class="activities-card">
                        <h3 class="activities-title">Recent Activities</h3>
                        <table class="activities-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px;">Activity</th>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th style="padding-right: 28px; text-align: right;">Date &amp; Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="activity in filteredOverviewActivities" :key="activity.id">
                                    <td style="padding-left: 28px; font-weight: 600;">{{ activity.action }}<span v-if="activity.subject">: {{ activity.subject }}</span></td>
                                    <td>{{ activity.user }}</td>
                                    <td><span class="role-pill role-pill-writer">{{ formatRole(activity.role) }}</span></td>
                                    <td style="padding-right: 28px; text-align: right; color: #64748b;">{{ formatDate(activity.created_at) }}</td>
                                </tr>
                                <tr v-if="!filteredOverviewActivities.length"><td colspan="4" class="empty-activity">No recent activities found.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ENDORSEMENTS TAB -->
                <div v-show="activeTab === 'endorsements'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Endorsements</h1>
                        <div class="filter-pills-group eic-endorsement-filters">
                            <div v-for="filter in eicEndorsementFilterDefinitions" :key="filter.key" class="eic-custom-filter" @click.stop>
                                <button type="button" class="eic-filter-trigger" @click="toggleEicFilter(filter.key)">
                                    <span>{{ eicFilterLabel(filter.key) }}</span>
                                    <svg :class="{ rotated: activeEicFilter === filter.key }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <div v-if="activeEicFilter === filter.key" class="eic-filter-menu">
                                    <button v-for="option in filter.options" :key="option.value" type="button" :class="{ selected: eicFilters[filter.key] === option.value }" @click="selectEicFilter(filter.key, option.value)">{{ option.label }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="articles-card">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px;">Title</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th style="padding-right: 28px; text-align: right;">Deadline</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="article in paginatedEicEndorsements" :key="article.id" @click="openArticleDetails(article)" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 600;">{{ article.title }}</td>
                                    <td><span class="section-badge">{{ article.section?.name || 'Unassigned' }}</span></td>
                                    <td><span class="status-pill" :class="eicStatusClass(article.status)">{{ eicStatusLabel(article.status) }}</span></td>
                                    <td><span class="priority-pill priority-moderate">Moderate</span></td>
                                    <td style="padding-right: 28px; text-align: right; color: #64748b;">{{ formatDate(article.created_at) }}</td>
                                </tr>
                                <tr v-if="!filteredEicEndorsements.length"><td colspan="5" class="empty-activity">No endorsement articles found.</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container eic-endorsement-pagination">
                        <div class="pagination-pill">
                            <button class="page-btn" :disabled="eicEndorsementPage === 1" @click="eicEndorsementPage--">Previous</button>
                            <button v-for="page in eicEndorsementPageCount" :key="page" class="page-number" :class="{ active: eicEndorsementPage === page }" @click="eicEndorsementPage = page">{{ page }}</button>
                            <button class="page-btn" :disabled="eicEndorsementPage === eicEndorsementPageCount" @click="eicEndorsementPage++">Next</button>
                            <div class="page-results-count">Showing <strong>{{ filteredEicEndorsements.length }}</strong> results</div>
                        </div>
                    </div>
                </div>

                <!-- PRESS WORKS TAB -->
                <div v-show="activeTab === 'pressWorks' || activeTab === 'press-works'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Press Works</h1>
                        <button class="new-user-btn" type="button" @click="openPressworkModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            New Academic Year
                        </button>
                    </div>

                    <div class="card" style="padding: 24px; background: #ffffff; border-radius: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);">
                        <div v-if="eicAcademicYears.length === 0" class="empty-activity" style="text-align: center; padding: 40px;">
                            No academic years found. Click "New Academic Year" to create one.
                        </div>
                        
                        <div v-else-if="filteredEicAcademicYears.length" class="academic-years-container">
                            <div v-for="yearGroup in filteredEicAcademicYears" :key="yearGroup.academic_year" class="academic-year-folder">
                                <div class="folder-header" @click="toggleEicYear(yearGroup.academic_year)">
                                    <svg class="folder-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    <span class="folder-title">{{ yearGroup.academic_year }}</span>
                                    <span class="folder-count">{{ (yearGroup.monitoring_sheets || []).length }} Monitoring Sheet{{ (yearGroup.monitoring_sheets || []).length !== 1 ? 's' : '' }}</span>
                                    <button class="delete-year-btn" type="button" @click.stop="openDeleteYearModal(yearGroup.academic_year)" title="Delete Academic Year">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    </button>
                                    <svg class="chevron-icon" :style="{ transform: eicExpandedYears[yearGroup.academic_year] ? 'rotate(180deg)' : 'rotate(0deg)' }" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </div>
                                
                                <div v-show="eicExpandedYears[yearGroup.academic_year]" class="folder-content">
                                    <div class="monitoring-sheets-list">
                                        <div v-for="sheet in yearGroup.monitoring_sheets" :key="sheet.id" class="monitoring-sheet-item" @click="openMonitoringSheet(sheet)">
                                            <span class="pub-badge" :class="pressworkBadgeClass(sheet.publication_type)">{{ sheet.publication_type }}</span>
                                            <span class="sheet-title">{{ sheet.title }}</span>
                                            <span class="sheet-date">{{ formatDate(sheet.created_at) }}</span>
                                            <button class="action-menu-btn" type="button" @click.stop="openMonitoringSheet(sheet)" aria-label="Open monitoring sheet">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="empty-activity" style="text-align: center; padding: 40px;">No matching academic years found.</div>
                    </div>
                </div>

                <!-- MY ARTICLES TAB -->
                <div v-show="activeTab === 'my-articles'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">My Articles</h1>
                        <div class="filter-pills-group eic-endorsement-filters">
                            <div class="eic-custom-filter" @click.stop>
                                <button type="button" class="eic-filter-trigger" @click="eicMyArticlesStatusDropdownOpen = !eicMyArticlesStatusDropdownOpen">
                                    <span>{{ eicMyArticlesStatusFilter ? (myArticlesStatusLabelMap[eicMyArticlesStatusFilter] || 'Status') : 'Status' }}</span>
                                    <svg :class="{ rotated: eicMyArticlesStatusDropdownOpen }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </button>
                                <div v-if="eicMyArticlesStatusDropdownOpen" class="eic-filter-menu">
                                    <button
                                        v-for="opt in myArticlesStatusOptions"
                                        :key="opt.value"
                                        type="button"
                                        :class="{ selected: eicMyArticlesStatusFilter === opt.value }"
                                        @click="eicMyArticlesStatusFilter = opt.value; eicMyArticlesStatusDropdownOpen = false; eicMyArticlesPage = 1"
                                    >
                                        {{ opt.label }}
                                    </button>
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
                                    <th>Status</th>
                                    <th style="padding-right: 28px; text-align: right;">Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="eicMyArticlesLoading">
                                    <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8;">Loading articles…</td>
                                </tr>
                                <tr v-else-if="paginatedEicMyArticles.length === 0">
                                    <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8;">No articles found.</td>
                                </tr>
                                <template v-else>
                                    <tr
                                        v-for="(article, idx) in paginatedEicMyArticles"
                                        :key="article.id"
                                        @click="openArticleOrTaskModal(article)"
                                        style="cursor: pointer;"
                                    >
                                        <td :style="idx === paginatedEicMyArticles.length - 1 ? 'padding-left: 28px; border-bottom: none; font-weight: 600;' : 'padding-left: 28px; font-weight: 600;'">
                                            {{ article.title }}
                                        </td>
                                        <td :style="idx === paginatedEicMyArticles.length - 1 ? 'border-bottom: none;' : ''">
                                            {{ article.author ? (article.author.name ? article.author.name.split(' ').slice(0, 2).join(' ') : article.author) : (eicUser.name || '—') }}
                                        </td>
                                        <td :style="idx === paginatedEicMyArticles.length - 1 ? 'border-bottom: none;' : ''">
                                            <span :class="'status-badge ' + myArticlesStatusBadgeClass(article.status)">{{ myArticlesStatusLabel(article.status) }}</span>
                                        </td>
                                        <td :style="idx === paginatedEicMyArticles.length - 1 ? 'padding-right: 28px; text-align: right; border-bottom: none; color: #64748b; font-weight: 500;' : 'padding-right: 28px; text-align: right; color: #64748b; font-weight: 500;'">
                                            {{ formatDate(article.updated_at || article.created_at) }}
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container" v-if="filteredEicMyArticles.length > 0">
                        <div class="pagination-pill">
                            <button class="page-btn" :disabled="eicMyArticlesPage <= 1" @click="eicMyArticlesPage--">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                Previous
                            </button>
                            <button
                                v-for="page in eicMyArticlesPageCount"
                                :key="page"
                                class="page-number"
                                :class="{ active: eicMyArticlesPage === page }"
                                @click="eicMyArticlesPage = page"
                            >{{ page }}</button>
                            <button class="page-btn" :disabled="eicMyArticlesPage >= eicMyArticlesPageCount" @click="eicMyArticlesPage++">
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                            <div class="page-results-count">
                                Showing <strong>{{ paginatedEicMyArticles.length }}</strong> of {{ filteredEicMyArticles.length }} articles
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ASSIGNMENTS TAB -->
                <div v-show="activeTab === 'assignments'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Assignments</h1>
                    </div>

                    <div class="articles-card">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px; width: 35%;">Title</th>
                                    <th style="width: 20%;">Assigned To</th>
                                    <th style="width: 15%;">Deadline</th>
                                    <th style="width: 10%;">Priority</th>
                                    <th style="width: 10%; text-align: center;">Status</th>
                                    <th style="padding-right: 28px; width: 10%; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="eicAssignedLoading">
                                    <td colspan="6" class="empty-activity">Loading assignments…</td>
                                </tr>
                                <tr v-else-if="eicAssignedPaged.length === 0">
                                    <td colspan="6" class="empty-activity">No assignments found. Use "+ Assign Task" above to create one.</td>
                                </tr>
                                <template v-else>
                                    <tr v-for="task in eicAssignedPaged" :key="task.id">
                                        <td style="padding-left: 28px; font-weight: 700;">{{ task.title }}</td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <img
                                                    :src="task.assigneeAvatar || avatarFallback(task.assigneeName)"
                                                    :alt="task.assigneeName"
                                                    style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 1px solid #e2e8f0;"
                                                />
                                                <span style="font-weight: 600;">{{ task.assigneeName || '—' }}</span>
                                            </div>
                                        </td>
                                        <td style="color: #64748b;">{{ task.deadlineLabel }}</td>
                                        <td>
                                            <span class="priority-pill" :class="'priority-' + (task.priority === 'medium' ? 'moderate' : (task.priority || 'moderate'))">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                                </svg>
                                                {{ formatPriorityLabel(task.priority) }}
                                            </span>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="status-pill" :class="assignedStatusClass(task.status)">{{ assignedStatusLabel(task.status) }}</span>
                                        </td>
                                        <td style="padding-right: 28px; text-align: center;">
                                            <div class="action-icons" style="display: flex; gap: 8px; justify-content: center;">
                                                <button class="action-btn edit" type="button" aria-label="Edit assignment" @click.stop="openEditTask(task)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>
                                                </button>
                                                <button class="action-btn delete" type="button" aria-label="Delete assignment" @click.stop="confirmDeleteTask(task)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 1 2 1 2v2"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-container" v-if="eicAssignedFiltered.length > 0">
                        <div class="pagination-pill">
                            <button class="page-btn" :disabled="eicAssignedPage <= 1" @click="eicAssignedPage--">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                Previous
                            </button>
                            <button
                                v-for="page in eicAssignedTotalPages"
                                :key="page"
                                class="page-number"
                                :class="{ active: eicAssignedPage === page }"
                                @click="eicAssignedPage = page"
                            >{{ page }}</button>
                            <button class="page-btn" :disabled="eicAssignedPage >= eicAssignedTotalPages" @click="eicAssignedPage++">
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                            <div class="page-results-count">
                                Showing <strong>{{ eicAssignedPaged.length }}</strong> of {{ eicAssignedFiltered.length }} assignments
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PUBLISHED ARTICLES TAB -->
                <div v-show="activeTab === 'articles'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Published Articles</h1>
                        <div class="filter-pills-group eic-endorsement-filters">
                            <div v-for="filter in eicPublishedFilterDefinitions" :key="filter.key" class="eic-custom-filter" @click.stop>
                                <button type="button" class="eic-filter-trigger" @click="toggleEicPublishedFilter(filter.key)">
                                    <span>{{ eicPublishedFilterLabel(filter.key) }}</span>
                                    <svg :class="{ rotated: activeEicPublishedFilter === filter.key }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <div v-if="activeEicPublishedFilter === filter.key" class="eic-filter-menu">
                                    <button v-for="option in filter.options" :key="option.value" type="button" :class="{ selected: eicPublishedFilters[filter.key] === option.value }" @click="selectEicPublishedFilter(filter.key, option.value)">{{ option.label }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="articles-card">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px;">Title</th>
                                    <th>Section</th>
                                    <th style="padding-right: 28px; text-align: right;">Published Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="article in paginatedEicPublishedArticles" :key="article.id" @click="openArticleDetails(article)" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 600;">{{ article.title }}</td>
                                    <td><span class="section-badge">{{ article.section?.name || 'General' }}</span></td>
                                    <td style="padding-right: 28px; text-align: right; color: #64748b;">{{ formatDate(article.published_at || article.updated_at || article.created_at) }}</td>
                                </tr>
                                <tr v-if="!filteredEicPublishedArticles.length">
                                    <td colspan="3" class="empty-activity">No published articles found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container">
                        <div class="pagination-pill">
                            <button class="page-btn" :disabled="eicPublishedPage === 1" @click="eicPublishedPage--">Previous</button>
                            <button v-for="page in eicPublishedPageCount" :key="page" class="page-number" :class="{ active: eicPublishedPage === page }" @click="eicPublishedPage = page">{{ page }}</button>
                            <button class="page-btn" :disabled="eicPublishedPage === eicPublishedPageCount" @click="eicPublishedPage++">Next</button>
                            <div class="page-results-count">Showing <strong>{{ filteredEicPublishedArticles.length }}</strong> results</div>
                        </div>
                    </div>
                </div>

                <!-- CONTRIBUTORS TAB -->
                <div v-show="activeTab === 'contributors'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Contributors</h1>
                        <div class="filter-pills-group eic-endorsement-filters">
                            <!-- Role Filter -->
                            <div class="eic-custom-filter" @click.stop>
                                <button type="button" class="eic-filter-trigger" @click="toggleEicContributorFilter('role')">
                                    <span>{{ eicContributorFilterLabel('role') }}</span>
                                    <svg :class="{ rotated: activeEicContributorFilter === 'role' }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <div v-if="activeEicContributorFilter === 'role'" class="eic-filter-menu">
                                    <button v-for="option in eicContributorRoleOptions" :key="option.value" type="button" :class="{ selected: eicContributorRole === option.value }" @click="selectEicContributorFilter('role', option.value)">{{ option.label }}</button>
                                </div>
                            </div>

                            <!-- Section Filter (Dependent on Role) -->
                            <div class="eic-custom-filter" :class="{ disabled: eicContributorRole === 'all' }" @click.stop>
                                <button
                                    type="button"
                                    class="eic-filter-trigger"
                                    :disabled="eicContributorRole === 'all'"
                                    @click="eicContributorRole !== 'all' && toggleEicContributorFilter('section')"
                                    :title="eicContributorRole === 'all' ? 'Select a role first to filter by section' : ''"
                                >
                                    <span>{{ eicContributorFilterLabel('section') }}</span>
                                    <svg :class="{ rotated: activeEicContributorFilter === 'section' }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <div v-if="activeEicContributorFilter === 'section' && eicContributorRole !== 'all'" class="eic-filter-menu">
                                    <button v-for="option in eicContributorSectionOptions" :key="option.value" type="button" :class="{ selected: eicContributorSection === option.value }" @click="selectEicContributorFilter('section', option.value)">{{ option.label }}</button>
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="eic-custom-filter" @click.stop>
                                <button type="button" class="eic-filter-trigger" @click="toggleEicContributorFilter('status')">
                                    <span>{{ eicContributorFilterLabel('status') }}</span>
                                    <svg :class="{ rotated: activeEicContributorFilter === 'status' }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <div v-if="activeEicContributorFilter === 'status'" class="eic-filter-menu">
                                    <button v-for="option in eicContributorStatusOptions" :key="option.value" type="button" :class="{ selected: eicContributorStatus === option.value }" @click="selectEicContributorFilter('status', option.value)">{{ option.label }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="articles-card">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="width: 22%; padding-left: 20px;">Name</th>
                                    <th style="width: 14%;">Role</th>
                                    <th style="width: 14%;">Section</th>
                                    <th style="width: 24%;">Email</th>
                                    <th style="width: 8%; text-align: center;">Tasks</th>
                                    <th style="width: 10%; text-align: center;">Status</th>
                                    <th style="width: 8%; padding-right: 20px; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in paginatedEicContributors" :key="user.id">
                                    <td style="padding-left: 20px; font-weight: 600;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img :src="user.profile_picture ? ('/storage/' + user.profile_picture) : avatarFallback(user.name)" :alt="user.name" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 1px solid #e2e8f0;">
                                            <span>{{ user.name }}</span>
                                        </div>
                                    </td>
                                    <td><span class="role-pill">{{ formatRole(user.role) }}</span></td>
                                    <td>
                                        <span v-if="user.secondary_role" class="section-badge">{{ user.secondary_role }}</span>
                                        <span v-else style="color: #94a3b8;">—</span>
                                    </td>
                                    <td style="color: #64748b;">{{ user.email }}</td>
                                    <td style="text-align: center;">
                                        <span class="tasks-count-pill">{{ user.assigned_tasks_count ?? (user.assigned_tasks ? user.assigned_tasks.length : 0) }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="status-pill" :class="user.is_active !== false ? 'status-active' : 'status-available'">
                                            {{ user.is_active !== false ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td style="padding-right: 20px; text-align: center;">
                                        <div class="action-icons" style="display: flex; gap: 8px; justify-content: center;">
                                            <button class="action-btn edit" type="button" aria-label="Edit contributor" @click="openEditUser(user)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>
                                            </button>
                                            <button class="action-btn delete" type="button" aria-label="Delete contributor" @click="openDeleteUser(user)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 1 2 1 2v2"></path></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!filteredEicContributors.length">
                                    <td colspan="7" class="empty-activity">No contributors found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container">
                        <div class="pagination-pill">
                            <button class="page-btn" :disabled="eicContributorPage === 1" @click="eicContributorPage--">Previous</button>
                            <button v-for="page in eicContributorPageCount" :key="page" class="page-number" :class="{ active: eicContributorPage === page }" @click="eicContributorPage = page">{{ page }}</button>
                            <button class="page-btn" :disabled="eicContributorPage === eicContributorPageCount" @click="eicContributorPage++">Next</button>
                            <div class="page-results-count">Showing <strong>{{ filteredEicContributors.length }}</strong> results</div>
                        </div>
                    </div>
                </div>

                <!-- ARCHIVE TAB -->
                <div v-show="activeTab === 'archive'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Archive</h1>
                    </div>

                    <div class="archive-explorer">
                        <div class="archive-explorer-header">
                            <span class="archive-breadcrumb">Archive</span>
                            <span class="archive-breadcrumb-separator">›</span>
                            <span class="archive-breadcrumb-current">School Years</span>
                        </div>

                        <div class="archive-grid">
                            <svg width="0" height="0" style="position:absolute">
                                <defs>
                                    <linearGradient id="folderGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                        <stop offset="0%" stop-color="#90bbf8" />
                                        <stop offset="100%" stop-color="#719bf0" />
                                    </linearGradient>
                                    <g id="folderIcon">
                                        <path d="M 20 20 C 20 5, 30 0, 40 0 L 160 0 C 170 0, 180 5, 180 20 L 180 100 L 20 100 Z" fill="#ffffff" />
                                        <path d="M 0 50 C 0 30, 10 20, 30 20 L 80 20 C 90 20, 95 35, 100 40 C 105 45, 110 50, 120 50 L 170 50 C 190 50, 200 60, 200 80 L 200 200 L 0 200 Z" fill="url(#folderGrad)" />
                                    </g>
                                </defs>
                            </svg>

                            <div
                                v-for="folder in paginatedArchiveFolders"
                                :key="folder.key"
                                class="archive-card explorer-folder"
                                @click="openArchiveFolder(folder)"
                            >
                                <svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg>
                                <div class="archive-card-content">
                                    <h4>{{ folder.label }}</h4>
                                    <p>{{ folder.articles.length }} article{{ folder.articles.length === 1 ? '' : 's' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Pagination -->
                    <div class="floating-pagination">
                        <div class="pagination" style="margin-top: 0;">
                            <button class="page-nav" :disabled="archivePage === 1" @click="archivePage--">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="m15 18-6-6 6-6"/></svg>
                                Previous
                            </button>
                            <button v-for="page in archivePageNumbers" :key="page" class="page-btn" :class="{ active: archivePage === page }" @click="archivePage = page">{{ page }}</button>
                            <span v-if="archivePageCount > 5 && archivePage < archivePageCount - 1" style="margin: 0 4px; color: #555; font-weight: 700;">...</span>
                            <button class="page-nav" :disabled="archivePage === archivePageCount" @click="archivePage++">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                        </div>
                        <div class="page-info" style="margin-left: 0; padding-left: 32px; border-left: 1px solid #eef0f4;">
                            Showing <strong>{{ paginatedArchiveFolders.length }}</strong> of <strong>{{ filteredArchiveFolders.length }}</strong> folders
                        </div>
                    </div>
                </div>

                <!-- ARCHIVE YEAR (FOLDER DETAILS) TAB -->
                <div v-show="activeTab === 'archive-year'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="align-items: center; margin-bottom: 4px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <button class="btn-back" type="button" @click="closeArchiveFolder" style="padding: 0; color: #1a73e8; font-size: 14px; display: inline-flex; align-items: center; gap: 4px; background: none; border: none; cursor: pointer; font-weight: 600;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                Back
                            </button>
                            <h1 class="page-title" style="margin: 0;">{{ selectedArchiveFolder?.label || 'Archive' }}</h1>
                        </div>
                    </div>

                    <div class="card" style="flex: 1; padding: 0; overflow: hidden; display: flex; flex-direction: column; background: #ffffff; border-radius: 20px;">
                        <div class="table-container" style="flex: 1; padding: 24px;">
                            <table class="articles-table">
                                <thead>
                                    <tr>
                                        <th style="padding-left: 20px;">Title</th>
                                        <th>Section</th>
                                        <th>Writer</th>
                                        <th>Status</th>
                                        <th>Date & Time</th>
                                        <th style="text-align: center; padding-right: 20px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="article in paginatedArchiveArticles" :key="article.id">
                                        <td style="padding-left: 20px; font-weight: 600;">{{ article.title }}</td>
                                        <td><span class="section-badge">{{ article.section?.name || 'Unassigned' }}</span></td>
                                        <td style="color: #64748b;">{{ article.author?.name || 'Unknown' }}</td>
                                        <td><span class="status-pill" :class="articleStatusClass(article.status)">{{ articleStatusLabel(article.status) }}</span></td>
                                        <td style="color: #64748b;">{{ formatDate(article.created_at) }}</td>
                                        <td style="text-align: center; padding-right: 20px;">
                                            <div class="action-icons" style="display: flex; gap: 8px; justify-content: center;">
                                                <button class="action-btn edit" type="button" aria-label="View article" @click="openArticleDetails(article)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="!selectedArchiveArticles.length"><td colspan="6" class="empty-activity">No articles found for this academic year.</td></tr>
                                </tbody>
                            </table>
                        </div>

                        <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <div style="flex: 1; display: flex; justify-content: center;">
                                <div class="pagination">
                                    <button class="page-nav" :disabled="archiveYearPage === 1" @click="archiveYearPage--">Previous</button>
                                    <button v-for="page in archiveYearPageCount" :key="page" class="page-btn" :class="{ active: archiveYearPage === page }" @click="archiveYearPage = page">{{ page }}</button>
                                    <button class="page-nav" :disabled="archiveYearPage === archiveYearPageCount" @click="archiveYearPage++">Next</button>
                                </div>
                            </div>
                            <div class="page-info">Showing <strong>{{ paginatedArchiveArticles.length }}</strong> of <strong>{{ selectedArchiveArticles.length }}</strong> articles</div>
                        </div>
                    </div>
                </div>

                <!-- ANALYTICS TAB -->
                <div v-show="activeTab === 'analytics'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Analytics</h1>
                        <div class="filters admin-analytics-filters">
                            <div class="custom-filter-dropdown analytics-filter" @click.stop>
                                <button type="button" class="custom-filter-trigger" @click="toggleAnalyticsDropdown">
                                    <span>{{ analyticsPeriodLabels[analyticsPeriod] }}</span>
                                    <svg :class="{ rotated: activeAnalyticsDropdown }" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6" /></svg>
                                </button>
                                <div v-if="activeAnalyticsDropdown" class="custom-filter-menu">
                                    <button v-for="(label, value) in analyticsPeriodLabels" :key="value" type="button" :class="{ selected: analyticsPeriod === value }" @click="selectAnalyticsPeriod(value)">{{ label }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dashboard Grid -->
                    <div class="overview-grid" style="grid-template-columns: 1.5fr 1fr;">
                        
                        <!-- Left Column -->
                        <div class="left-col" style="display: flex; flex-direction: column; gap: 24px;">
                            
                            <!-- Content Performance -->
                            <div class="card">
                                <h3 class="card-header">Content Performance</h3>
                                <div v-if="analyticsError" class="empty-activity">{{ analyticsError }}</div>
                                <div v-else-if="!filteredAnalyticsPages.length" class="empty-activity">No site traffic data for this period.</div>
                                <div v-for="page in filteredAnalyticsPages" :key="page.title" class="analytics-list-item">
                                    <div class="analytics-item-left">
                                        <div class="analytics-item-title">{{ page.title }}</div>
                                        <div class="analytics-item-meta">
                                            <span class="section">Site analytics</span>
                                            <span>{{ formatCount(page.users) }} active users</span>
                                        </div>
                                    </div>
                                    <div class="analytics-item-right">
                                        <div class="metric-pill">
                                            {{ formatCount(page.views) }}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 17 9.2-9.2M17 17V7H7"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <p v-if="analytics.configured" class="updated-text">{{ analytics.start_date }} to {{ analytics.end_date }}</p>
                            </div>

                            <!-- Recent Publications -->
                            <div class="card">
                                <h3 class="card-header">Recent Publications</h3>
                                <table style="margin-top: 10px;">
                                    <thead>
                                        <tr class="table-header-rounded">
                                            <th style="background-color: #f1f5f9;">Title</th>
                                            <th style="background-color: #f1f5f9;">Category</th>
                                            <th style="background-color: #f1f5f9;">Published Date</th>
                                            <th style="background-color: #f1f5f9;">Views</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="page in filteredAnalyticsPages" :key="`publication-${page.title}`">
                                            <td style="border-bottom: none;">{{ page.title }}</td>
                                            <td style="border-bottom: none;"><span class="section-pill">Web</span></td>
                                            <td style="border-bottom: none;">{{ analytics.end_date || '—' }}</td>
                                            <td style="border-bottom: none; font-weight: 600;">{{ formatCount(page.views) }}</td>
                                        </tr>
                                        <tr v-if="!filteredAnalyticsPages.length"><td colspan="4" class="empty-activity">No publication traffic data.</td></tr>
                                    </tbody>
                                </table>
                                <a href="#" class="view-all" @click.prevent="activeTab = 'articles'">View All <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -2px;"><path d="m9 18 6-6-6-6"/></svg></a>
                            </div>
                            
                        </div>

                        <!-- Right Column -->
                        <div class="right-col" style="display: flex; flex-direction: column; gap: 24px;">
                            
                            <!-- Engagement Metrics -->
                            <div class="card">
                                <h3 class="card-header">Engagement Metrics</h3>
                                <div class="engagement-grid">
                                    <div style="display: flex; flex-direction: column; gap: 16px;">
                                        <div class="engagement-box" style="flex: 1;">
                                            <div class="engagement-label">Total Views</div>
                                            <div class="engagement-value">{{ formatCount(analytics.metrics.page_views) }}</div>
                                        </div>
                                        <div class="engagement-box" style="flex: 1;">
                                            <div class="engagement-label">Unique Visitors</div>
                                            <div class="engagement-value">{{ formatCount(analytics.metrics.active_users) }}</div>
                                        </div>
                                    </div>
                                    <div class="engagement-box tall">
                                        <div class="engagement-label" style="margin-bottom: 16px;">Tracked Visitors</div>
                                        <div class="engagement-value">{{ formatCount(analytics.metrics.sessions) }}</div>
                                        <div class="engagement-label" style="margin: 16px 0 4px;">Published Articles</div>
                                        <div class="engagement-value">{{ formatCount(eicArticles.filter(article => article.status === 'published').length) }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Workflow Efficiency -->
                            <div class="card">
                                <h3 class="card-header">Workflow Efficiency</h3>
                                <div class="workflow-grid">
                                    <div class="workflow-box">
                                        <div class="workflow-label">Avg. Time to Publish</div>
                                        <div class="workflow-value-large">{{ eicAveragePublishDays }}<span style="font-size: 16px;">d</span></div>
                                    </div>
                                    <div class="workflow-box">
                                        <div class="workflow-label">Articles to Review</div>
                                        <div class="workflow-value-large">{{ formatCount((eicArticleStatusCounts.submitted || 0) + (eicArticleStatusCounts.under_review || 0) + (eicArticleStatusCounts.endorsed || 0)) }}</div>
                                    </div>
                                    <div class="workflow-box">
                                        <div class="workflow-label">Revision Rate</div>
                                        <div class="workflow-value-large">{{ eicRevisionRate }}<span style="font-size: 16px;">%</span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Article Status Overview -->
                            <div class="card">
                                <h3 class="card-header">Article Status Overview</h3>
                                <div class="status-list">
                                    <div class="status-list-item">
                                        <span>Draft</span>
                                        <div class="status-count">{{ formatCount(eicArticleStatusCounts.draft || 0) }}</div>
                                    </div>
                                    <div class="status-list-item">
                                        <span>In Review</span>
                                        <div class="status-count">{{ formatCount((eicArticleStatusCounts.submitted || 0) + (eicArticleStatusCounts.under_review || 0) + (eicArticleStatusCounts.endorsed || 0)) }}</div>
                                    </div>
                                    <div class="status-list-item">
                                        <span>Under Revision</span>
                                        <div class="status-count">{{ formatCount(eicArticleStatusCounts.rejected || 0) }}</div>
                                    </div>
                                    <div class="status-list-item">
                                        <span>Approved</span>
                                        <div class="status-count">{{ formatCount(eicArticleStatusCounts.approved || 0) }}</div>
                                    </div>
                                    <div class="status-list-item">
                                        <span>Published</span>
                                        <div class="status-count">{{ formatCount(eicArticleStatusCounts.published || 0) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Assign Task Modal -->
    <AssignTaskModal 
        :is-open="isAssignTaskModalOpen" 
        @close="isAssignTaskModalOpen = false" 
        @task-added="loadEicMyArticles(); loadEicOverview(); loadEicArticles(); loadEicAssigned();"
        @view-assignments="activeTab = 'assignments'" 
    />

    <!-- Edit Task Modal -->
    <EditTaskModal
        :is-open="isEditTaskModalOpen"
        :task="editTaskTarget"
        @close="isEditTaskModalOpen = false; editTaskTarget = null;"
        @task-updated="onTaskUpdated"
    />

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
        @close="isWorkspaceModalOpen = false; loadEicMyArticles();" 
        @task-submitted="loadEicMyArticles(); loadEicOverview(); loadEicArticles();"
        @task-saved-as-draft="loadEicMyArticles(); loadEicOverview(); loadEicArticles();"
    />

    <!-- Article Details Modal (EIC Endorsements Review) -->
    <ArticleDetailsModal
        :is-open="isArticleDetailsOpen"
        :article-data="selectedArticle"
        @close="isArticleDetailsOpen = false"
        @action-complete="loadEicOverview(); loadEicArticles();"
        @view-full-article="handleOpenWorkspace"
    />

    <!-- Add Academic Year Modal -->
    <div v-if="isPressworkModalOpen" class="new-user-modal-overlay" @click.self="closePressworkModal">
        <form class="new-user-modal" role="dialog" aria-modal="true" @submit.prevent="createPresswork">
            <div class="new-user-modal-header">
                <h2>Add Academic Year</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="closePressworkModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="new-user-step">
                <p class="presswork-modal-note">This will create a new academic year folder with Newsletter, Tabloid, Magazine, and Litfolio monitoring sheets.</p>
                <div class="form-group">
                    <label class="form-label" for="presswork-year">Academic Year</label>
                    <input id="presswork-year" v-model="pressworkForm.academic_year" class="form-control" placeholder="2025-2026" required>
                </div>
                <p v-if="yearAlreadyExists" class="new-user-error" style="color: #dc2626;">⚠️ This academic year already exists!</p>
                <p v-if="pressworkError" class="new-user-error">{{ pressworkError }}</p>
                <div class="modal-footer">
                    <button class="btn-back" type="button" @click="closePressworkModal">Cancel</button>
                    <button class="btn-next" type="submit" :disabled="pressworkSaving || yearAlreadyExists">{{ pressworkSaving ? 'Creating...' : 'Create Academic Year' }}</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Delete Academic Year Confirmation Modal -->
    <div v-if="isDeleteYearModalOpen" class="new-user-modal-overlay" @click.self="closeDeleteYearModal">
        <div class="new-user-modal new-user-confirm-modal" role="dialog" aria-modal="true">
            <div class="new-user-modal-header">
                <h2>Delete Academic Year?</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="closeDeleteYearModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <p>Delete <strong>{{ yearToDelete }}</strong>? This will permanently delete all monitoring sheets and their entries for this academic year. This action cannot be undone.</p>
            <p v-if="deleteYearError" class="new-user-error">{{ deleteYearError }}</p>
            <div class="modal-footer">
                <button class="btn-back" type="button" @click="closeDeleteYearModal">Cancel</button>
                <button class="btn-next btn-danger" type="button" :disabled="deleteYearSaving" @click="deleteYear">{{ deleteYearSaving ? 'Deleting...' : 'Delete Academic Year' }}</button>
            </div>
        </div>
    </div>

    <!-- Edit Contributor Modal -->
    <div v-if="isEditUserModalOpen" class="new-user-modal-overlay" @click.self="closeEditUser">
        <form class="new-user-modal" role="dialog" aria-modal="true" @submit.prevent="saveEditedUser">
            <div class="new-user-modal-header">
                <h2>Edit Contributor</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="closeEditUser">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="new-user-step">
                <div class="form-group"><label class="form-label">Full Name</label><input v-model="editUserForm.name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Email</label><input v-model="editUserForm.email" type="email" class="form-control" required></div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select v-model="editUserForm.role" class="form-control select-control" required @change="editUserForm.secondary_role = ''">
                            <option value="section_editor">Section Editor</option>
                            <option value="staff_writer">Staff Writer</option>
                            <option value="staff_artist">Staff Artist</option>
                            <option value="staff_broadcaster">Staff Broadcaster</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select v-model="editUserForm.is_active" class="form-control select-control">
                            <option :value="true">Active</option>
                            <option :value="false">Inactive</option>
                        </select>
                    </div>
                </div>
                <div v-if="SECONDARY_ROLES[editUserForm.role]" class="form-group" style="margin-top: 8px;">
                    <label class="form-label">Section / Secondary Role</label>
                    <select v-model="editUserForm.secondary_role" class="form-control select-control">
                        <option value="">None / Default</option>
                        <option v-for="secRole in SECONDARY_ROLES[editUserForm.role]" :key="secRole" :value="secRole">{{ secRole }}</option>
                    </select>
                </div>
                <div class="form-row edit-user-academic-group">
                    <div class="form-group"><label class="form-label">Program</label><input v-model="editUserForm.program" class="form-control" placeholder="Program (e.g. BSIT)"></div>
                    <div class="form-group"><label class="form-label">Year/Section</label><input v-model="editUserForm.year_section" class="form-control" placeholder="Year/Section (e.g. 3A)"></div>
                </div>
                <div class="form-group edit-user-password-group">
                    <label class="form-label">New Password <span class="optional" style="font-size: 11px; color: #94a3b8; font-weight: normal;">(leave blank to keep current)</span></label>
                    <input v-model="editUserForm.password" type="password" class="form-control" minlength="8" placeholder="••••••••">
                </div>
                <p v-if="editUserError" class="new-user-error">{{ editUserError }}</p>
                <div class="modal-footer">
                    <button class="btn-back" type="button" @click="closeEditUser">Cancel</button>
                    <button class="btn-next" type="submit" :disabled="editUserSaving">{{ editUserSaving ? 'Saving...' : 'Save Changes' }}</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Delete Contributor Modal -->
    <div v-if="isDeleteUserModalOpen" class="new-user-modal-overlay" @click.self="closeDeleteUser">
        <div class="new-user-modal new-user-confirm-modal" role="dialog" aria-modal="true">
            <div class="new-user-modal-header">
                <h2>Delete Contributor?</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="closeDeleteUser">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <p>Delete <strong>{{ selectedUser?.name }}</strong>? This will permanently delete the user and all of their information. This action cannot be undone.</p>
            <p v-if="deleteUserError" class="new-user-error">{{ deleteUserError }}</p>
            <div class="modal-footer">
                <button class="btn-back" type="button" @click="closeDeleteUser">Cancel</button>
                <button class="btn-next btn-danger" type="button" :disabled="deleteUserSaving" @click="deleteSelectedUser">{{ deleteUserSaving ? 'Deleting...' : 'Delete Contributor' }}</button>
            </div>
        </div>
    </div>

    <!-- Delete Assignment Modal -->
    <div v-if="deleteTaskTarget" class="new-user-modal-overlay" @click.self="deleteTaskTarget = null">
        <div class="new-user-modal new-user-confirm-modal" role="dialog" aria-modal="true">
            <div class="new-user-modal-header">
                <h2>Delete Assignment?</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="deleteTaskTarget = null">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <p>Are you sure you want to delete "<strong>{{ deleteTaskTarget?.title }}</strong>"? This will permanently remove the assignment and any associated draft. This action cannot be undone.</p>
            <div class="modal-footer">
                <button class="btn-back" type="button" @click="deleteTaskTarget = null">Cancel</button>
                <button class="btn-next btn-danger" type="button" :disabled="deletingTask" @click="doDeleteTask">{{ deletingTask ? 'Deleting...' : 'Delete Assignment' }}</button>
            </div>
        </div>
    </div>

    <!-- Add Contributor Modal -->
    <div v-if="isNewContributorModalOpen" class="new-user-modal-overlay" @click.self="closeNewContributorModal">
        <div class="new-user-modal" role="dialog" aria-modal="true" aria-labelledby="new-contributor-modal-title">
            <div class="new-user-modal-header">
                <h2 id="new-contributor-modal-title">Add Contributor</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="closeNewContributorModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <!-- Step 1: Profile picture, Name, Email, Program, Year/Section -->
            <div v-if="newContributorStep === 1" class="new-user-step">
                <div class="form-group">
                    <label class="form-label">Profile Picture</label>
                    <div class="upload-box">
                        <div class="upload-circle" :class="{ 'has-preview': newContributorImagePreview }">
                            <img v-if="newContributorImagePreview" :src="newContributorImagePreview" alt="Profile preview">
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        </div>
                        <div class="upload-info">
                            <p>Choose a file or drag & drop it here.<br>jpeg, png - Up to 10MB</p>
                            <input ref="newContributorFileInput" type="file" accept="image/jpeg,image/png,image/webp" hidden @change="handleNewContributorImage">
                            <button class="btn-upload" type="button" @click="newContributorFileInput?.click()">Upload image <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 2px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="7" y2="8"></line></svg></button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input v-model="newContributorForm.name" type="text" class="form-control" placeholder="Full Name" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input v-model="newContributorForm.email" type="email" class="form-control" placeholder="handle@my.cspc.edu.ph" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Program</label>
                        <input v-model="newContributorForm.program" type="text" class="form-control" placeholder="Program (e.g. BSIT)">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Year/Section</label>
                        <input v-model="newContributorForm.year_section" type="text" class="form-control" placeholder="Year/Section (e.g. 3A)">
                    </div>
                </div>

                <p v-if="newContributorError" class="new-user-error">{{ newContributorError }}</p>

                <div class="modal-footer">
                    <button class="btn-back" type="button" @click="closeNewContributorModal"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Cancel</button>
                    <button class="btn-next" type="button" @click="goToNewContributorStep2">Next</button>
                </div>
            </div>

            <!-- Step 2: Role, Section (Filtered by Role), Status, Password -->
            <div v-else-if="newContributorStep === 2" class="new-user-step">
                <div class="form-group">
                    <label class="form-label">Role & Status</label>
                    <div class="form-row">
                        <div class="form-group">
                            <div class="input-icon-wrap custom-select-wrap">
                                <select v-model="newContributorForm.role" class="form-control select-control" @change="newContributorForm.secondary_role = ''">
                                    <option value="" disabled>Role</option>
                                    <option value="section_editor">Section Editor</option>
                                    <option value="staff_writer">Staff Writer</option>
                                    <option value="staff_artist">Staff Artist</option>
                                    <option value="staff_broadcaster">Staff Broadcaster</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon-wrap custom-select-wrap">
                                <select v-model="newContributorForm.status" class="form-control select-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Filtered Section / Secondary Role -->
                    <div v-if="SECONDARY_ROLES[newContributorForm.role]" class="form-group" style="margin-top: 12px;">
                        <label class="form-label">Section / Secondary Role</label>
                        <div class="input-icon-wrap custom-select-wrap">
                            <select v-model="newContributorForm.secondary_role" class="form-control select-control">
                                <option value="">None / Default</option>
                                <option v-for="secRole in SECONDARY_ROLES[newContributorForm.role]" :key="secRole" :value="secRole">{{ secRole }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Set Password</label>
                    <div class="input-icon-wrap" style="margin-bottom: 12px;">
                        <input v-model="newContributorForm.password" type="password" class="form-control" placeholder="Create Password (min. 8 characters)" minlength="8">
                    </div>
                    <div class="input-icon-wrap">
                        <input v-model="newContributorForm.passwordConfirmation" type="password" class="form-control" placeholder="Retype Password">
                    </div>
                </div>

                <p v-if="newContributorError" class="new-user-error">{{ newContributorError }}</p>

                <div class="modal-footer">
                    <button class="btn-back" type="button" @click="newContributorStep = 1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Back</button>
                    <button class="btn-next" type="button" :disabled="newContributorSaving" @click="saveNewContributor">{{ newContributorSaving ? 'Saving...' : 'Save Contributor' }}</button>
                </div>
            </div>

            <!-- Step 3: Success -->
            <div v-else class="new-user-step">
                <div class="success-step">
                    <div class="success-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <h3 class="success-title">Contributor Added Successfully</h3>
                    <p class="success-text">A new contributor has been successfully created</p>
                    <button class="btn-next btn-full" type="button" @click="closeNewContributorModal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, reactive, ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import AssignTaskModal from '../../components/AssignTaskModal.vue';
import AssignedTaskModal from '../../components/AssignedTaskModal.vue';
import AssignmentWorkspaceModal from '../../components/AssignmentWorkspaceModal.vue';
import ArticleDetailsModal from '../../components/ArticleDetailsModal.vue';
import EditTaskModal from '../../components/EditTaskModal.vue';
import NotificationsPopover from '../../components/NotificationsPopover.vue';
import { signOut as performSignOut } from '../../utils/auth';

const router = useRouter();
const activeTab = ref('overview');
const searchQuery = ref('');
const eicUser = ref(JSON.parse(localStorage.getItem('sparky_user') || '{}'));
const eicOverview = ref({
    summary: { articles: 0, endorsements: 0, ready_to_publish: 0, published: 0 },
    activities: [],
    updated_at: null,
});
const eicArticles = ref([]);
const eicUsers = ref([]);
const eicAcademicYears = ref([]);
const eicExpandedYears = ref({});
const activeEicFilter = ref(null);
const eicEndorsementPage = ref(1);
const eicEndorsementPageSize = 8;
const eicFilters = reactive({ status: 'all', section: 'all', priority: 'all', deadline: 'newest' });
const isAssignTaskModalOpen = ref(false);
const isAssignedTaskModalOpen = ref(false);
const isWorkspaceModalOpen = ref(false);
const isArticleDetailsOpen = ref(false);

// ── EIC My Articles State ───────────────────────────────────────────────────
const eicMyArticles = ref([]);
const eicMyArticlesLoading = ref(false);
const eicMyArticlesStatusFilter = ref('');
const eicMyArticlesStatusDropdownOpen = ref(false);
const eicMyArticlesPage = ref(1);
const eicMyArticlesPerPage = 8;

const myArticlesStatusOptions = [
    { value: '', label: 'All Status' },
    { value: 'pending', label: 'Pending' },
    { value: 'draft', label: 'Draft' },
    { value: 'submitted', label: 'For Review' },
    { value: 'under_review', label: 'Under Review' },
    { value: 'endorsed', label: 'Endorsed' },
    { value: 'approved', label: 'Approved' },
    { value: 'rejected', label: 'Rejected' },
    { value: 'published', label: 'Published' },
];

const myArticlesStatusLabelMap = {
    '': 'Status',
    pending: 'Pending',
    draft: 'Draft',
    submitted: 'For Review',
    under_review: 'Under Review',
    endorsed: 'Endorsed',
    approved: 'Approved',
    rejected: 'Rejected',
    published: 'Published',
};

const myArticlesStatusLabel = (status) => {
    const map = {
        pending: 'Pending',
        draft: 'Draft',
        submitted: 'Submitted',
        under_review: 'Under Review',
        endorsed: 'Endorsed',
        approved: 'Approved',
        published: 'Published',
        rejected: 'Rejected',
    };
    return map[status] || (status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Pending');
};

const myArticlesStatusBadgeClass = (status) => {
    const map = {
        pending: 'status-draft',
        draft: 'status-draft',
        submitted: 'status-for-review',
        under_review: 'status-under-revision',
        endorsed: 'status-endorsed',
        approved: 'status-published',
        published: 'status-published',
        rejected: 'status-under-revision',
    };
    return map[status] || 'status-draft';
};

const activeEicPublishedFilter = ref(null);
const eicPublishedPage = ref(1);
const eicPublishedPageSize = 8;
const eicPublishedFilters = reactive({ section: 'all', date: 'newest' });

const activeEicContributorFilter = ref(null);
const eicContributorPage = ref(1);
const eicContributorPageSize = 8;
const eicContributorRole = ref('all');
const eicContributorSection = ref('all');
const eicContributorStatus = ref('all');

const isPressworkModalOpen = ref(false);
const pressworkSaving = ref(false);
const pressworkError = ref('');
const pressworkForm = ref({ academic_year: '2025-2026' });

const isDeleteYearModalOpen = ref(false);
const yearToDelete = ref('');
const deleteYearSaving = ref(false);
const deleteYearError = ref('');

const selectedTask = ref({});
const selectedArticle = ref({});

const formatCount = (value) => Number(value || 0).toLocaleString();
const formatRole = (role) => {
    if (role === 'eic') return 'Editor in Chief';
    if (role === 'system') return 'System';
    if (role === 'staff_broadcaster') return 'Staff Broadcaster';
    return (role || '').split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
};
const formatDate = (date) => date
    ? new Date(date).toLocaleString([], { dateStyle: 'medium', timeStyle: 'short' })
    : '—';
const updatedLabel = computed(() => eicOverview.value.updated_at
    ? `Updated ${new Date(eicOverview.value.updated_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })}`
    : 'Loading...');
const searchPlaceholder = computed(() => {
    switch (activeTab.value) {
        case 'overview': return 'Search recent activities';
        case 'endorsements': return 'Search endorsements';
        case 'pressWorks':
        case 'press-works': return 'Search press works';
        case 'my-articles': return 'Search my articles';
        case 'articles': return 'Search published articles';
        case 'contributors': return 'Search contributors';
        case 'archive': return 'Search archive folders';
        case 'archive-year': return 'Search archived articles';
        case 'analytics': return 'Search analytics';
        default: return 'Search';
    }
});

const matchesSearch = (...fields) => {
    if (!searchQuery.value || !searchQuery.value.trim()) return true;
    const query = searchQuery.value.toLowerCase().trim();
    return fields.some(field => String(field || '').toLowerCase().includes(query));
};

const filteredOverviewActivities = computed(() => (eicOverview.value.activities || []).filter(activity => matchesSearch(
    activity.action,
    activity.subject,
    activity.user,
    activity.role,
    formatRole(activity.role),
)));

const filteredEicAcademicYears = computed(() => {
    return eicAcademicYears.value
        .map(yearGroup => ({
            ...yearGroup,
            monitoring_sheets: (yearGroup.monitoring_sheets || []).filter(sheet => matchesSearch(
                yearGroup.academic_year,
                sheet.title,
                sheet.publication_type,
            )),
        }))
        .filter(yearGroup => matchesSearch(yearGroup.academic_year) || (yearGroup.monitoring_sheets || []).length);
});

const yearAlreadyExists = computed(() => {
    return eicAcademicYears.value.some(year => year.academic_year === pressworkForm.value.academic_year);
});

watch(() => pressworkForm.value.academic_year, () => {
    pressworkError.value = '';
});

const pressworkBadgeClass = (type) => ({
    Newsletter: 'pub-newsletter',
    Tabloid: 'pub-tabloid',
    Magazine: 'pub-magazine',
    Litfolio: 'pub-litfolio',
}[type] || 'pub-newsletter');

const openPressworkModal = () => {
    pressworkForm.value = { academic_year: '2025-2026' };
    pressworkError.value = '';
    isPressworkModalOpen.value = true;
};

const closePressworkModal = () => {
    isPressworkModalOpen.value = false;
};

const createPresswork = async () => {
    if (yearAlreadyExists.value) {
        pressworkError.value = 'An academic year with this name already exists.';
        return;
    }

    pressworkSaving.value = true;
    pressworkError.value = '';
    try {
        const response = await fetch('/api/press-works', {
            method: 'POST',
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(pressworkForm.value),
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            if (response.status === 409) {
                throw new Error('An academic year with this name already exists.');
            }
            throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Could not create academic year.');
        }

        await loadEicPressWorks();
        eicExpandedYears.value[pressworkForm.value.academic_year] = true;
        closePressworkModal();
    } catch (error) {
        pressworkError.value = error.message;
    } finally {
        pressworkSaving.value = false;
    }
};

const openDeleteYearModal = (year) => {
    yearToDelete.value = year;
    deleteYearError.value = '';
    isDeleteYearModalOpen.value = true;
};

const closeDeleteYearModal = () => {
    isDeleteYearModalOpen.value = false;
    yearToDelete.value = '';
    deleteYearError.value = '';
};

const deleteYear = async () => {
    deleteYearSaving.value = true;
    deleteYearError.value = '';
    try {
        const response = await fetch(`/api/press-works/${yearToDelete.value}`, {
            method: 'DELETE',
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json'
            },
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw new Error(data.message || 'Could not delete academic year.');
        }

        await loadEicPressWorks();
        closeDeleteYearModal();
    } catch (error) {
        deleteYearError.value = error.message;
    } finally {
        deleteYearSaving.value = false;
    }
};

const eicStatusLabel = (status) => (status || 'unknown').replace('_', ' ').replace(/\b\w/g, letter => letter.toUpperCase());
const eicStatusClass = (status) => ({
    submitted: 'status-for-approval',
    under_review: 'status-for-approval',
    endorsed: 'status-approved',
    approved: 'status-approved',
    rejected: 'status-returned',
}[status] || 'status-for-approval');
const eicEndorsementCandidates = computed(() => eicArticles.value.filter(article => [
    'submitted', 'under_review', 'endorsed', 'approved', 'rejected',
].includes(article.status)));
const eicEndorsementFilterDefinitions = computed(() => [
    {
        key: 'status',
        options: [{ value: 'all', label: 'Status' }, ...[...new Set(eicEndorsementCandidates.value.map(article => article.status))].sort().map(status => ({ value: status, label: eicStatusLabel(status) }))],
    },
    {
        key: 'section',
        options: [
            { value: 'all', label: 'Section' },
            ...['News', 'Opinion', 'Editorial', 'Feature', 'Sci-Tech', 'DevCom', 'Sports', 'Literary', 'Videos'].map(section => ({ value: section, label: section })),
        ],
    },
    {
        key: 'priority',
        options: [
            { value: 'all', label: 'Priority' },
            ...['Low', 'Moderate', 'High', 'Urgent'].map(priority => ({ value: priority.toLowerCase(), label: priority })),
        ],
    },
    {
        key: 'deadline',
        options: [{ value: 'newest', label: 'Newest' }, { value: 'oldest', label: 'Oldest' }],
    },
]);
const eicFilterLabel = (filter) => eicEndorsementFilterDefinitions.value.find(definition => definition.key === filter)?.options.find(option => option.value === eicFilters[filter])?.label || filter;
const filteredEicEndorsements = computed(() => {
    const filtered = eicEndorsementCandidates.value.filter(article =>
        (eicFilters.status === 'all' || article.status === eicFilters.status)
        && (eicFilters.section === 'all' || article.section?.name === eicFilters.section)
        && (eicFilters.priority === 'all' || (article.priority || 'moderate').toLowerCase() === eicFilters.priority)
        && matchesSearch(
            article.title,
            article.section?.name,
            article.author?.name,
            article.priority,
            eicStatusLabel(article.status),
        )
    );

    return filtered.sort((first, second) => {
        const firstDate = new Date(first.created_at || 0).getTime();
        const secondDate = new Date(second.created_at || 0).getTime();
        return eicFilters.deadline === 'oldest' ? firstDate - secondDate : secondDate - firstDate;
    });
});
const eicEndorsementPageCount = computed(() => Math.max(1, Math.ceil(filteredEicEndorsements.value.length / eicEndorsementPageSize)));
const paginatedEicEndorsements = computed(() => {
    const start = (eicEndorsementPage.value - 1) * eicEndorsementPageSize;
    return filteredEicEndorsements.value.slice(start, start + eicEndorsementPageSize);
});

const toggleEicFilter = (filter) => {
    activeEicFilter.value = activeEicFilter.value === filter ? null : filter;
};
const selectEicFilter = (filter, value) => {
    eicFilters[filter] = value;
    activeEicFilter.value = null;
};

const eicPublishedCandidates = computed(() => {
    return eicArticles.value.filter(article => article.status === 'published');
});

const availableSections = computed(() => {
    const sections = new Set();
    eicArticles.value.forEach(a => {
        if (a.section?.name) sections.add(a.section.name);
    });
    ['News', 'Opinion', 'Editorial', 'Feature', 'Sci-Tech', 'DevCom', 'Sports', 'Literary', 'Videos'].forEach(s => sections.add(s));
    return Array.from(sections);
});

const eicPublishedFilterDefinitions = computed(() => [
    {
        key: 'section',
        options: [
            { value: 'all', label: 'Section' },
            ...availableSections.value.map(section => ({ value: section, label: section })),
        ],
    },
    {
        key: 'date',
        options: [
            { value: 'newest', label: 'Published Date' },
            { value: 'oldest', label: 'Oldest' },
        ],
    },
]);

const eicPublishedFilterLabel = (filterKey) => {
    const def = eicPublishedFilterDefinitions.value.find(d => d.key === filterKey);
    return def?.options.find(opt => opt.value === eicPublishedFilters[filterKey])?.label || filterKey;
};

const toggleEicPublishedFilter = (filterKey) => {
    activeEicPublishedFilter.value = activeEicPublishedFilter.value === filterKey ? null : filterKey;
};

const selectEicPublishedFilter = (filterKey, value) => {
    eicPublishedFilters[filterKey] = value;
    activeEicPublishedFilter.value = null;
};

const filteredEicPublishedArticles = computed(() => {
    const filtered = eicPublishedCandidates.value.filter(article =>
        (eicPublishedFilters.section === 'all' || article.section?.name === eicPublishedFilters.section)
        && matchesSearch(article.title, article.section?.name, article.author?.name)
    );

    return filtered.sort((first, second) => {
        const firstDate = new Date(first.published_at || first.updated_at || first.created_at || 0).getTime();
        const secondDate = new Date(second.published_at || second.updated_at || second.created_at || 0).getTime();
        return eicPublishedFilters.date === 'oldest' ? firstDate - secondDate : secondDate - firstDate;
    });
});

const eicPublishedPageCount = computed(() => Math.max(1, Math.ceil(filteredEicPublishedArticles.value.length / eicPublishedPageSize)));
const paginatedEicPublishedArticles = computed(() => {
    const start = (eicPublishedPage.value - 1) * eicPublishedPageSize;
    return filteredEicPublishedArticles.value.slice(start, start + eicPublishedPageSize);
});

const SECONDARY_ROLES = {
    section_editor: [
        'Associate Editor for Internal',
        'Associate Editor for External',
        'Managing Editor',
        'Assistant Managing Editor',
        'Copy Editor',
        'Circulation Manager',
        'Art Editor',
        'Layout Editor',
        'Publication Adviser',
        'Opinion Editor',
        'Editorial Editor',
        'Literary Editor',
        'Sports Editor',
        'Head Broadcaster',
        'Assistant Head Broadcaster',
    ],
    staff_writer: [
        'News Writer',
        'Editorial Writer',
        'Opinion Writer',
        'Sports Writer',
        'Sci&Tech Writer',
        'Feature Writer',
        'Literary Writer',
        'DevCom Writer',
        'Copyreader',
        'Editorial Assistant',
    ],
    staff_broadcaster: [
        'News Presenter',
        'Video Editor',
        'Technical Director',
        'Videographer',
    ],
    staff_artist: [
        'Layout Artist',
        'Graphic Artist',
        'Photojournalist',
        'Cartoonist',
        'Illustrator',
    ],
};

const selectedUser = ref(null);
const isEditUserModalOpen = ref(false);
const isDeleteUserModalOpen = ref(false);
const editUserSaving = ref(false);
const editUserError = ref('');
const deleteUserSaving = ref(false);
const deleteUserError = ref('');
const editUserForm = ref({
    name: '',
    email: '',
    role: 'staff_writer',
    secondary_role: '',
    is_active: true,
    program: '',
    year_section: '',
    password: '',
});

const openEditUser = (user) => {
    selectedUser.value = user;
    editUserForm.value = {
        name: user.name || '',
        email: user.email || '',
        role: user.role || 'staff_writer',
        secondary_role: user.secondary_role || '',
        is_active: user.is_active !== false,
        program: user.program || '',
        year_section: user.year_section || '',
        password: '',
    };
    editUserError.value = '';
    isEditUserModalOpen.value = true;
};

const closeEditUser = () => {
    isEditUserModalOpen.value = false;
    selectedUser.value = null;
    editUserError.value = '';
};

const saveEditedUser = async () => {
    if (!selectedUser.value) return;
    editUserSaving.value = true;
    editUserError.value = '';
    const payload = { ...editUserForm.value };
    if (!payload.password) delete payload.password;
    if (!payload.secondary_role) payload.secondary_role = null;

    try {
        const response = await fetch(`/api/users/${selectedUser.value.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
            body: JSON.stringify(payload),
        });
        const data = await response.json();
        if (!response.ok) {
            editUserError.value = data.message || 'Failed to update contributor.';
            return;
        }
        const index = eicUsers.value.findIndex(u => u.id === selectedUser.value.id);
        if (index !== -1) {
            eicUsers.value[index] = { ...eicUsers.value[index], ...data };
        }
        closeEditUser();
    } catch (e) {
        editUserError.value = e.message || 'Failed to update contributor.';
    } finally {
        editUserSaving.value = false;
    }
};

const openDeleteUser = (user) => {
    selectedUser.value = user;
    deleteUserError.value = '';
    isDeleteUserModalOpen.value = true;
};

const closeDeleteUser = () => {
    isDeleteUserModalOpen.value = false;
    selectedUser.value = null;
    deleteUserError.value = '';
};

const deleteSelectedUser = async () => {
    if (!selectedUser.value) return;
    deleteUserSaving.value = true;
    deleteUserError.value = '';

    try {
        const userIdToDelete = selectedUser.value.id;
        const response = await fetch(`/api/users/${userIdToDelete}`, {
            method: 'DELETE',
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        if (!response.ok) {
            const data = await response.json();
            deleteUserError.value = data.message || 'Failed to delete contributor.';
            return;
        }
        eicUsers.value = eicUsers.value.filter(u => u.id !== userIdToDelete);
        eicArticles.value = eicArticles.value.filter(a => a.author_id !== userIdToDelete && a.author?.id !== userIdToDelete);
        closeDeleteUser();
    } catch (e) {
        deleteUserError.value = e.message || 'Failed to delete contributor.';
    } finally {
        deleteUserSaving.value = false;
    }
};

const avatarFallback = (name) => `https://ui-avatars.com/api/?name=${encodeURIComponent(name || 'User')}&background=dbeafe&color=1d4ed8`;

const isNewContributorModalOpen = ref(false);
const newContributorStep = ref(1);
const newContributorFileInput = ref(null);
const newContributorImagePreview = ref('');
const newContributorSaving = ref(false);
const newContributorError = ref('');
const newContributorForm = ref({
    name: '',
    email: '',
    role: 'staff_writer',
    secondary_role: '',
    status: 'active',
    password: '',
    passwordConfirmation: '',
    program: '',
    year_section: '',
    image: null,
});

const openNewContributorModal = () => {
    newContributorStep.value = 1;
    newContributorError.value = '';
    newContributorImagePreview.value = '';
    newContributorForm.value = {
        name: '',
        email: '',
        role: 'staff_writer',
        secondary_role: '',
        status: 'active',
        password: '',
        passwordConfirmation: '',
        program: '',
        year_section: '',
        image: null,
    };
    isNewContributorModalOpen.value = true;
};

const closeNewContributorModal = () => {
    isNewContributorModalOpen.value = false;
    newContributorImagePreview.value = '';
    newContributorError.value = '';
};

const handleNewContributorImage = (event) => {
    const file = event.target.files?.[0] || null;
    newContributorForm.value.image = file;
    newContributorImagePreview.value = file ? URL.createObjectURL(file) : '';
};

const goToNewContributorStep2 = () => {
    newContributorError.value = '';
    if (!newContributorForm.value.name.trim() || !newContributorForm.value.email.trim()) {
        newContributorError.value = 'Please provide full name and email.';
        return;
    }
    newContributorStep.value = 2;
};

const saveNewContributor = async () => {
    newContributorError.value = '';
    if (!newContributorForm.value.name || !newContributorForm.value.email || !newContributorForm.value.role || !newContributorForm.value.password) {
        newContributorError.value = 'Please complete all required fields.';
        return;
    }
    if (newContributorForm.value.password.length < 8) {
        newContributorError.value = 'Password must be at least 8 characters long.';
        return;
    }
    if (newContributorForm.value.password !== newContributorForm.value.passwordConfirmation) {
        newContributorError.value = 'Passwords do not match.';
        return;
    }

    newContributorSaving.value = true;
    const payload = new FormData();
    payload.append('name', newContributorForm.value.name);
    payload.append('email', newContributorForm.value.email);
    payload.append('role', newContributorForm.value.role);
    if (newContributorForm.value.secondary_role) {
        payload.append('secondary_role', newContributorForm.value.secondary_role);
    }
    payload.append('program', newContributorForm.value.program || '');
    payload.append('year_section', newContributorForm.value.year_section || '');
    payload.append('password', newContributorForm.value.password);
    payload.append('is_active', newContributorForm.value.status !== 'inactive' ? '1' : '0');
    if (newContributorForm.value.image) {
        payload.append('profile_picture', newContributorForm.value.image);
    }

    try {
        const response = await fetch('/api/users', {
            method: 'POST',
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
            body: payload,
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            const validationError = data.errors ? Object.values(data.errors)[0]?.[0] : data.message;
            throw new Error(validationError || `Could not create contributor (${response.status}).`);
        }
        if (data.id) {
            eicUsers.value.unshift(data);
        }
        newContributorStep.value = 3;
    } catch (error) {
        newContributorError.value = error.message || 'Could not connect to the server.';
    } finally {
        newContributorSaving.value = false;
    }
};

const eicContributorCandidates = computed(() => {
    return (eicUsers.value || []).filter(user =>
        ['section_editor', 'staff_writer', 'staff_artist', 'staff_broadcaster'].includes(user.role)
    );
});

const eicContributorRoleOptions = [
    { value: 'all', label: 'All Roles', triggerLabel: 'Role' },
    { value: 'section_editor', label: 'Section Editor', triggerLabel: 'Section Editor' },
    { value: 'staff_writer', label: 'Staff Writer', triggerLabel: 'Staff Writer' },
    { value: 'staff_artist', label: 'Staff Artist', triggerLabel: 'Staff Artist' },
    { value: 'staff_broadcaster', label: 'Staff Broadcaster', triggerLabel: 'Staff Broadcaster' },
];

const eicContributorStatusOptions = [
    { value: 'all', label: 'All Status', triggerLabel: 'Status' },
    { value: 'active', label: 'Active', triggerLabel: 'Active' },
    { value: 'inactive', label: 'Inactive', triggerLabel: 'Inactive' },
];

const eicContributorSectionOptions = computed(() => {
    if (eicContributorRole.value === 'all' || !SECONDARY_ROLES[eicContributorRole.value]) {
        return [{ value: 'all', label: 'All Sections' }];
    }
    const roles = SECONDARY_ROLES[eicContributorRole.value] || [];
    return [
        { value: 'all', label: 'All Sections' },
        ...roles.map(r => ({ value: r, label: r })),
    ];
});

const eicContributorFilterLabel = (filterKey) => {
    if (filterKey === 'role') {
        const opt = eicContributorRoleOptions.find(o => o.value === eicContributorRole.value);
        return opt?.triggerLabel || 'Role';
    }
    if (filterKey === 'section') {
        if (eicContributorRole.value === 'all' || eicContributorSection.value === 'all') return 'Section';
        const opt = eicContributorSectionOptions.value.find(o => o.value === eicContributorSection.value);
        return opt?.label || 'Section';
    }
    if (filterKey === 'status') {
        const opt = eicContributorStatusOptions.find(o => o.value === eicContributorStatus.value);
        return opt?.triggerLabel || 'Status';
    }
    return '';
};

const toggleEicContributorFilter = (filterKey) => {
    activeEicContributorFilter.value = activeEicContributorFilter.value === filterKey ? null : filterKey;
};

const selectEicContributorFilter = (filterKey, value) => {
    if (filterKey === 'role') {
        eicContributorRole.value = value;
        eicContributorSection.value = 'all';
    } else if (filterKey === 'section') {
        eicContributorSection.value = value;
    } else if (filterKey === 'status') {
        eicContributorStatus.value = value;
    }
    activeEicContributorFilter.value = null;
};

const filteredEicContributors = computed(() => {
    const filtered = eicContributorCandidates.value.filter(user => {
        const matchesRole = eicContributorRole.value === 'all'
            || user.role === eicContributorRole.value;

        const matchesSection = eicContributorSection.value === 'all'
            || user.secondary_role === eicContributorSection.value;

        const matchesStatus = eicContributorStatus.value === 'inactive'
            ? user.is_active === false
            : user.is_active !== false;

        const matchesSearchQuery = matchesSearch(
            user.name,
            user.secondary_role,
            formatRole(user.role),
            user.email,
            user.program,
            user.year_section,
        );

        return matchesRole && matchesSection && matchesStatus && matchesSearchQuery;
    });

    // Inactive contributors are sorted to the bottom of the list
    return filtered.sort((a, b) => {
        const aActive = a.is_active !== false ? 1 : 0;
        const bActive = b.is_active !== false ? 1 : 0;
        if (aActive !== bActive) {
            return bActive - aActive; // Active first (1), Inactive at bottom (0)
        }
        return (a.name || '').localeCompare(b.name || '');
    });
});

const eicContributorPageCount = computed(() => Math.max(1, Math.ceil(filteredEicContributors.value.length / eicContributorPageSize)));
const paginatedEicContributors = computed(() => {
    const start = (eicContributorPage.value - 1) * eicContributorPageSize;
    return filteredEicContributors.value.slice(start, start + eicContributorPageSize);
});

const selectedArchiveFolder = ref(null);
const archivePage = ref(1);
const archivePageSize = 14;
const archiveYearPage = ref(1);
const archiveYearPageSize = 10;

const getAcademicYearMeta = (dateInput = new Date()) => {
    const date = new Date(dateInput);
    const startYear = date.getMonth() >= 6 ? date.getFullYear() : date.getFullYear() - 1;
    const endYear = startYear + 1;
    return {
        key: `A/Y ${startYear} - ${endYear}`,
        label: `A/Y July ${startYear} - June ${endYear}`,
        startYear,
        endYear,
    };
};

const archiveFolders = computed(() => {
    const folders = new Map();
    const currentFolder = getAcademicYearMeta();
    folders.set(currentFolder.key, { key: currentFolder.key, label: currentFolder.label, articles: [] });

    [2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025].forEach((startYear) => {
        const yearMeta = getAcademicYearMeta(new Date(startYear, 6, 1));
        folders.set(yearMeta.key, { key: yearMeta.key, label: yearMeta.label, articles: [] });
    });

    eicArticles.value.forEach((article) => {
        const articleDate = article.created_at ? new Date(article.created_at) : new Date();
        const yearMeta = getAcademicYearMeta(articleDate);
        const existing = folders.get(yearMeta.key) || {
            key: yearMeta.key,
            label: yearMeta.label,
            articles: [],
        };

        existing.articles.push(article);
        folders.set(yearMeta.key, existing);
    });

    return [...folders.values()].sort((first, second) => {
        const firstStart = Number((first.key.match(/(\d{4}) - (\d{4})/) || [])[1] || 0);
        const secondStart = Number((second.key.match(/(\d{4}) - (\d{4})/) || [])[1] || 0);
        return secondStart - firstStart;
    });
});

const filteredArchiveFolders = computed(() => archiveFolders.value.filter(folder =>
    matchesSearch(folder.label, folder.key, ...folder.articles.map(article => article.title))
));

const archivePageCount = computed(() => Math.max(1, Math.ceil(filteredArchiveFolders.value.length / archivePageSize)));
const paginatedArchiveFolders = computed(() => {
    const start = (archivePage.value - 1) * archivePageSize;
    return filteredArchiveFolders.value.slice(start, start + archivePageSize);
});
const archivePageNumbers = computed(() => {
    const totalPages = archivePageCount.value;
    if (totalPages <= 5) return Array.from({ length: totalPages }, (_, index) => index + 1);

    const pages = [1, 2, 3, 4, 5];
    if (archivePage.value > 3) pages[0] = archivePage.value - 2;
    if (archivePage.value > 2) pages[1] = archivePage.value - 1;
    if (archivePage.value > 1) pages[2] = archivePage.value;
    if (archivePage.value < totalPages - 1) pages[4] = archivePage.value + 2;

    return [...new Set(pages.filter(page => page >= 1 && page <= totalPages))].slice(0, 5);
});

const openArchiveFolder = (folder) => {
    selectedArchiveFolder.value = folder;
    archiveYearPage.value = 1;
    activeTab.value = 'archive-year';
};

const closeArchiveFolder = () => {
    activeTab.value = 'archive';
    selectedArchiveFolder.value = null;
};

const selectedArchiveArticles = computed(() => selectedArchiveFolder.value
    ? selectedArchiveFolder.value.articles.filter(article => matchesSearch(
        article.title,
        article.author?.name,
        article.section?.name,
        article.monitoring_sheet_url,
        articleStatusLabel(article.status),
    ))
    : []);
const archiveYearPageCount = computed(() => Math.max(1, Math.ceil(selectedArchiveArticles.value.length / archiveYearPageSize)));
const paginatedArchiveArticles = computed(() => {
    const start = (archiveYearPage.value - 1) * archiveYearPageSize;
    return selectedArchiveArticles.value.slice(start, start + archiveYearPageSize);
});

const articleStatusLabel = (status) => (status || 'unknown').replace('_', ' ').replace(/\b\w/g, letter => letter.toUpperCase());
const articleStatusClass = (status) => ({
    published: 'published',
    draft: 'draft',
    rejected: 'revision',
    under_review: 'review',
    submitted: 'review',
    endorsed: 'approved',
    approved: 'approved',
}[status] || 'draft');

const activeAnalyticsDropdown = ref(false);
const analyticsPeriod = ref('30');
const analyticsPeriodLabels = {
    '7': 'Last 7 days',
    '30': 'Last 30 days',
    '90': 'Last 90 days',
};
const analytics = ref({
    configured: false,
    metrics: { page_views: 0, active_users: 0, sessions: 0, average_session_duration: 0 },
    top_pages: [],
    start_date: null,
    end_date: null,
});
const analyticsError = ref('');

const toggleAnalyticsDropdown = () => {
    activeAnalyticsDropdown.value = !activeAnalyticsDropdown.value;
};

const selectAnalyticsPeriod = (period) => {
    analyticsPeriod.value = period;
    activeAnalyticsDropdown.value = false;
    loadEicAnalytics();
};

const filteredAnalyticsPages = computed(() => (analytics.value.top_pages || []).filter(page => matchesSearch(
    page.title,
    'Site analytics',
)));

const eicArticleStatusCounts = computed(() => eicArticles.value.reduce((counts, article) => {
    counts[article.status] = (counts[article.status] || 0) + 1;
    return counts;
}, {}));

const eicAveragePublishDays = computed(() => {
    const durations = eicArticles.value
        .filter(article => article.status === 'published' && article.created_at && article.approved_at)
        .map(article => (new Date(article.approved_at) - new Date(article.created_at)) / 86400000)
        .filter(duration => Number.isFinite(duration) && duration >= 0);

    return durations.length ? (durations.reduce((total, duration) => total + duration, 0) / durations.length).toFixed(1) : '0.0';
});

const eicRevisionRate = computed(() => {
    if (!eicArticles.value.length) return 0;
    return Math.round(((eicArticleStatusCounts.value.rejected || 0) / eicArticles.value.length) * 100);
});

watch(activeTab, () => {
    isNewContributorModalOpen.value = false;
    isEditUserModalOpen.value = false;
    isDeleteUserModalOpen.value = false;
    eicContributorRole.value = 'all';
    eicContributorSection.value = 'all';
    eicContributorStatus.value = 'all';
    eicContributorPage.value = 1;
    activeEicContributorFilter.value = null;
    activeEicFilter.value = null;
    activeEicPublishedFilter.value = null;
    activeAnalyticsDropdown.value = false;
    if (activeTab.value !== 'archive-year') {
        selectedArchiveFolder.value = null;
    }
    if (activeTab.value === 'assignments') {
        loadEicAssigned();
    }
});

watch([eicContributorRole, eicContributorSection, eicContributorStatus, searchQuery], () => {
    eicContributorPage.value = 1;
});

watch(archivePageCount, (pageCount) => {
    if (archivePage.value > pageCount) archivePage.value = pageCount;
});

const closeAllEicFilters = () => {
    activeEicFilter.value = null;
    activeEicPublishedFilter.value = null;
    activeEicContributorFilter.value = null;
    activeAnalyticsDropdown.value = false;
    eicMyArticlesStatusDropdownOpen.value = false;
};

const loadEicOverview = async () => {
    try {
        const response = await fetch('/api/eic/overview', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        if (response.ok) eicOverview.value = await response.json();
    } catch {
        // Keep the zero state if the overview endpoint is unavailable.
    }
};

const loadEicArticles = async () => {
    try {
        const response = await fetch('/api/articles', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        if (response.ok) eicArticles.value = await response.json();
    } catch {
        eicArticles.value = [];
    }
};

const loadEicUsers = async () => {
    try {
        const response = await fetch('/api/users', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        if (response.ok) {
            eicUsers.value = await response.json();
        }
    } catch {
        eicUsers.value = [];
    }
};

const loadEicPressWorks = async () => {
    try {
        const token = localStorage.getItem('sparky_token');
        const response = await fetch('/api/press-works', {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json',
            },
        });
        if (response.ok) {
            const data = await response.json();
            eicAcademicYears.value = data.academic_years || [];
            if (eicAcademicYears.value.length && Object.keys(eicExpandedYears.value).length === 0) {
                eicExpandedYears.value[eicAcademicYears.value[0].academic_year] = true;
            }
        } else {
            eicAcademicYears.value = [];
        }
    } catch {
        eicAcademicYears.value = [];
    }
};

const toggleEicYear = (academicYear) => {
    eicExpandedYears.value[academicYear] = !eicExpandedYears.value[academicYear];
};

const loadEicAnalytics = async () => {
    analyticsError.value = '';

    try {
        const response = await fetch(`/api/admin/analytics?period=${analyticsPeriod.value}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        const data = await response.json();

        if (!response.ok) {
            analyticsError.value = data.message || 'Analytics data is unavailable.';
            return;
        }

        analytics.value = data;
    } catch {
        analyticsError.value = 'Unable to load analytics data.';
    }
};

onMounted(() => {
    loadEicOverview();
    loadEicArticles();
    loadEicMyArticles();
    loadEicAssigned();
    loadEicPressWorks();
    loadEicUsers();
    loadEicAnalytics();
    window.addEventListener('click', closeAllEicFilters);
});

onUnmounted(() => {
    window.removeEventListener('click', closeAllEicFilters);
});

watch(eicFilters, () => {
    eicEndorsementPage.value = 1;
}, { deep: true });
watch(eicEndorsementPageCount, (pageCount) => {
    if (eicEndorsementPage.value > pageCount) eicEndorsementPage.value = pageCount;
});

watch(eicPublishedFilters, () => {
    eicPublishedPage.value = 1;
}, { deep: true });
watch(eicPublishedPageCount, (pageCount) => {
    if (eicPublishedPage.value > pageCount) eicPublishedPage.value = pageCount;
});

watch(eicContributorSection, () => {
    eicContributorPage.value = 1;
});
watch(eicContributorPageCount, (pageCount) => {
    if (eicContributorPage.value > pageCount) eicContributorPage.value = pageCount;
});

watch(searchQuery, () => {
    eicEndorsementPage.value = 1;
    eicPublishedPage.value = 1;
    eicMyArticlesPage.value = 1;
    eicContributorPage.value = 1;
    archivePage.value = 1;
    archiveYearPage.value = 1;
});

const parseNotesField = (notes, key) => {
    if (!notes || typeof notes !== 'string') return '';
    const match = notes.match(new RegExp(`${key}:\\s*([^|]+)`, 'i'));
    return match ? match[1].trim() : '';
};

const formatDeadline = (dateStr, dueTimeStr) => {
    if (!dateStr) return 'No deadline';
    if (typeof dateStr === 'string' && dateStr.includes('•')) return dateStr;

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

const filteredEicMyArticles = computed(() => {
    let list = eicMyArticles.value;
    if (eicMyArticlesStatusFilter.value) {
        list = list.filter(a => a.status === eicMyArticlesStatusFilter.value);
    }
    if (searchQuery.value && searchQuery.value.trim()) {
        list = list.filter(a => matchesSearch(a.title, a.section?.name || a.section));
    }
    return list;
});

const eicMyArticlesPageCount = computed(() =>
    Math.max(1, Math.ceil(filteredEicMyArticles.value.length / eicMyArticlesPerPage))
);

const paginatedEicMyArticles = computed(() => {
    const start = (eicMyArticlesPage.value - 1) * eicMyArticlesPerPage;
    return filteredEicMyArticles.value.slice(start, start + eicMyArticlesPerPage);
});

const loadEicMyArticles = async () => {
    const token = localStorage.getItem('sparky_token');
    if (!token || !eicUser.value?.id) return;
    eicMyArticlesLoading.value = true;
    try {
        const [articlesRes, tasksRes] = await Promise.allSettled([
            fetch(`/api/articles?author_id=${eicUser.value.id}`, {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            }),
            fetch(`/api/tasks?assignee_id=${eicUser.value.id}`, {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            })
        ]);

        let articlesList = [];
        let tasksList = [];

        if (articlesRes.status === 'fulfilled' && articlesRes.value.ok) {
            const data = await articlesRes.value.json();
            articlesList = Array.isArray(data) ? data : [];
        }

        if (tasksRes.status === 'fulfilled' && tasksRes.value.ok) {
            const data = await tasksRes.value.json();
            tasksList = Array.isArray(data) ? data.filter(t => t.type === 'writing') : [];
        }

        const combined = articlesList.map(a => {
            const matchedTask = tasksList.find(t => t.article_id === a.id);
            const dueTime = matchedTask ? parseNotesField(matchedTask.notes, 'Due Time') : '';
            return {
                id: a.id,
                article_id: a.id,
                taskId: matchedTask?.id || null,
                title: a.title,
                author: a.author || eicUser.value,
                section: a.section?.name || a.section || (matchedTask ? parseNotesField(matchedTask.notes, 'Section') : 'News'),
                status: a.status || 'draft',
                deadline: matchedTask ? formatDeadline(matchedTask.deadline, dueTime) : 'No deadline',
                priority: matchedTask?.priority || 'medium',
                updated_at: a.updated_at || a.created_at,
                created_at: a.created_at,
                isTask: false,
                media_files: a.media_files || [],
                cover_image: a.cover_image || '',
                content: a.content || '',
                // Always prefer the task description over article body (which contains HTML)
                description: matchedTask?.description || '',
                notes: matchedTask?.notes || a.editor_notes || '',
                raw: a,
                matchedTask: matchedTask || null
            };
        });

        tasksList.forEach(t => {
            const alreadyLinked = combined.some(item => item.article_id && item.article_id === t.article_id);
            if (!alreadyLinked) {
                const dueTime = parseNotesField(t.notes, 'Due Time');
                combined.push({
                    id: `task-${t.id}`,
                    taskId: t.id,
                    article_id: t.article_id || null,
                    title: t.title,
                    author: t.assignee || eicUser.value,
                    section: t.section?.name || parseNotesField(t.notes, 'Section') || 'News',
                    status: t.status === 'in_progress' ? 'draft' : (t.status || 'pending'),
                    deadline: formatDeadline(t.deadline, dueTime),
                    priority: t.priority || 'medium',
                    updated_at: t.updated_at || t.created_at,
                    created_at: t.created_at,
                    isTask: true,
                    media_files: [],
                    cover_image: '',
                    content: t.description || '',
                    notes: t.notes || '',
                    description: t.description || '',
                    raw: t,
                    matchedTask: t
                });
            }
        });

        combined.sort((a, b) => new Date(b.updated_at || b.created_at) - new Date(a.updated_at || a.created_at));
        eicMyArticles.value = combined;
    } catch (err) {
        console.warn('Could not load EIC My Articles:', err);
    } finally {
        eicMyArticlesLoading.value = false;
    }
};

// ── EIC Assigned Tasks State (Assignments Tab) ────────────────────────────────
const eicAssignedTasks = ref([]);
const eicAssignedLoading = ref(false);
const eicAssignedPage = ref(1);
const eicAssignedPerPage = 10;
const isEditTaskModalOpen = ref(false);
const editTaskTarget = ref(null);
const deleteTaskTarget = ref(null);
const deletingTask = ref(false);

const loadEicAssigned = async () => {
    const token = localStorage.getItem('sparky_token');
    if (!token || !eicUser.value?.id) return;
    eicAssignedLoading.value = true;
    try {
        const res = await fetch(`/api/tasks?assigned_by=${eicUser.value.id}&type=writing`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            const data = await res.json();
            eicAssignedTasks.value = (Array.isArray(data) ? data : []).map(t => {
                const dueTime = parseNotesField(t.notes, 'Due Time');
                return {
                    id: t.id,
                    title: t.title,
                    assigneeName: t.assignee?.name || '—',
                    assigneeAvatar: t.assignee?.profile_picture ? `/storage/${t.assignee.profile_picture}` : (t.assignee?.profile_picture_url || ''),
                    deadlineLabel: formatDeadline(t.deadline, dueTime),
                    deadline: t.deadline,
                    priority: t.priority || 'medium',
                    status: t.status || 'pending',
                    description: t.description || '',
                    raw: t
                };
            });
        }
    } catch (e) {
        console.warn('Could not load EIC assigned tasks:', e);
    } finally {
        eicAssignedLoading.value = false;
    }
};

const eicAssignedFiltered = computed(() => {
    if (!searchQuery.value || !searchQuery.value.trim()) return eicAssignedTasks.value;
    const q = searchQuery.value.toLowerCase().trim();
    return eicAssignedTasks.value.filter(t =>
        (t.title || '').toLowerCase().includes(q) ||
        (t.assigneeName || '').toLowerCase().includes(q) ||
        (t.priority || '').toLowerCase().includes(q) ||
        (t.status || '').toLowerCase().includes(q)
    );
});

const eicAssignedTotalPages = computed(() =>
    Math.max(1, Math.ceil(eicAssignedFiltered.value.length / eicAssignedPerPage))
);

const eicAssignedPaged = computed(() => {
    const start = (eicAssignedPage.value - 1) * eicAssignedPerPage;
    return eicAssignedFiltered.value.slice(start, start + eicAssignedPerPage);
});

const openEditTask = (task) => {
    editTaskTarget.value = task;
    isEditTaskModalOpen.value = true;
};

const onTaskUpdated = () => {
    isEditTaskModalOpen.value = false;
    editTaskTarget.value = null;
    loadEicAssigned();
    loadEicOverview();
};

const confirmDeleteTask = (task) => {
    deleteTaskTarget.value = task;
};

const doDeleteTask = async () => {
    if (!deleteTaskTarget.value) return;
    deletingTask.value = true;
    try {
        const token = localStorage.getItem('sparky_token');
        const res = await fetch(`/api/tasks/${deleteTaskTarget.value.id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            deleteTaskTarget.value = null;
            await loadEicAssigned();
            loadEicOverview();
        }
    } catch (e) {
        console.warn('Could not delete task:', e);
    } finally {
        deletingTask.value = false;
    }
};

const formatPriorityLabel = (priority) => {
    const map = {
        low: 'Low',
        medium: 'Moderate',
        high: 'High',
        urgent: 'Urgent'
    };
    return map[priority] || (priority ? priority.charAt(0).toUpperCase() + priority.slice(1) : 'Moderate');
};

const assignedStatusLabel = (status) => {
    const map = {
        pending: 'Pending',
        in_progress: 'In Progress',
        submitted: 'Submitted',
        under_review: 'Under Review',
        returned: 'Returned',
        rejected: 'Rejected',
        completed: 'Completed',
        approved: 'Approved',
        endorsed: 'Endorsed'
    };
    return map[status] || (status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Pending');
};
const formatStatus = assignedStatusLabel;

const assignedStatusClass = (status) => {
    const map = {
        pending: 'status-available',
        in_progress: 'status-for-approval',
        submitted: 'status-for-approval',
        under_review: 'status-for-approval',
        returned: 'status-returned',
        rejected: 'status-returned',
        completed: 'status-approved',
        approved: 'status-approved',
        endorsed: 'status-approved'
    };
    return map[status] || 'status-available';
};
const getStatusClass = assignedStatusClass;

const openArticleOrTaskModal = (item) => {
    const matchedTask = item.matchedTask || (item.isTask ? item.raw : null);
    const notesStr = item.notes || matchedTask?.notes || '';
    const dueTime = parseNotesField(notesStr, 'Due Time');
    const mediaArtist = parseNotesField(notesStr, 'Media Artist');

    selectedTask.value = {
        id: item.taskId || item.id,
        taskId: item.taskId || (item.isTask ? item.id : null),
        article_id: item.article_id || (item.isTask ? null : item.id),
        title: item.title || 'Untitled Task',
        section: (item.section && typeof item.section === 'object') ? (item.section.name || 'News') : (item.section || parseNotesField(notesStr, 'Section') || 'News'),
        coverage: parseNotesField(notesStr, 'Coverage') || '',
        dueTime: dueTime,
        deadline: item.deadline || formatDeadline(matchedTask?.deadline, dueTime),
        priority: item.priority || matchedTask?.priority || 'Moderate',
        status: item.status || 'pending',
        // Prefer task description (set during assignment), never fall back to article body HTML
        articleDesc: item.description || matchedTask?.description || item.raw?.description || item.raw?.tasks?.[0]?.description || 'Article saved. Open the workspace to view and edit full content.',
        thumbnailDesc: item.cover_image || item.raw?.cover_image || parseNotesField(notesStr, 'Thumbnail') || '',
        mediaUploads: Array.isArray(item.media_files) ? item.media_files : (item.raw?.media_files || []),
        mediaArtist: mediaArtist || '',
        notes: notesStr,
        assignees: item.author ? [{
            name: item.author.name || eicUser.value.name,
            secondary_role: item.author.secondary_role || 'Editor-in-Chief',
            role: item.author.role || 'eic',
            avatar: item.author.profile_picture ? `/storage/${item.author.profile_picture}` : (item.author.profile_picture_url || '')
        }] : [{
            name: eicUser.value.name,
            secondary_role: 'Editor-in-Chief',
            role: 'eic',
            avatar: eicUser.value.profile_picture ? `/storage/${eicUser.value.profile_picture}` : (eicUser.value.profile_picture_url || '')
        }],
        raw: item.raw || item
    };
    isAssignedTaskModalOpen.value = true;
};

const openTaskModal = (task = {}) => {
    selectedTask.value = {
        title: task.title || 'Enrollment Update for Second Semester',
        section: task.section || task.category || 'News',
        coverage: task.coverage || 'AY 2025 - 2026 Issue 1',
        deadline: task.deadline || 'Apr 16 • 5:00 PM',
        priority: task.priority || 'Moderate',
        articleDesc: task.articleDesc || 'Write a clear update about second semester enrollment, including dates, procedures, and registrar announcements.',
        thumbnailDesc: task.thumbnailDesc || 'Create a clean thumbnail using campus-related visuals with readable title placement...'
    };
    isAssignedTaskModalOpen.value = true;
};

const openArticleDetails = (item = {}) => {
    const rawSection = item.section?.name || (typeof item.section === 'string' ? item.section : 'News');
    selectedArticle.value = {
        ...item,
        title: item.title || 'Untitled Article',
        section: rawSection,
        coverage: item.coverage || `${rawSection} Coverage`,
        writer: item.author?.name || item.writer || 'Staff Writer',
        dateEndorsed: formatDate(item.endorsed_at || item.submitted_at || item.created_at),
        deadline: formatDate(item.deadline || item.endorsed_at || item.created_at),
        status: eicStatusLabel(item.status),
        raw_status: item.status,
        wordCount: item.word_count || (item.content ? item.content.trim().split(/\s+/).filter(Boolean).length : '0'),
        remarks: item.editor_notes || item.remarks || 'This article has been reviewed and revised on the initial feedback.',
    };
    isArticleDetailsOpen.value = true;
};

const handleOpenWorkspace = (taskData) => {
    isAssignedTaskModalOpen.value = false;
    isArticleDetailsOpen.value = false;
    selectedTask.value = taskData || selectedTask.value;
    isWorkspaceModalOpen.value = true;
};

const openMonitoringSheet = (sheet) => {
    if (sheet && sheet.id) {
        window.open(`/monitoring-sheet/${sheet.id}`, '_blank');
    } else {
        window.open('/monitoring-sheet', '_blank');
    }
};
</script>

<style scoped>
.academic-years-container {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.academic-year-folder {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    background: #ffffff;
}

.folder-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: #f8fafc;
    cursor: pointer;
    transition: background-color 0.2s;
    border-bottom: 1px solid #e2e8f0;
}

.folder-header:hover {
    background: #f1f5f9;
}

.folder-icon {
    color: #64748b;
    flex-shrink: 0;
}

.folder-title {
    font-weight: 700;
    font-size: 16px;
    color: #0f172a;
    flex: 1;
}

.folder-count {
    font-size: 13px;
    color: #64748b;
    font-weight: 600;
}

.chevron-icon {
    color: #64748b;
    transition: transform 0.2s;
    flex-shrink: 0;
}

.folder-content {
    padding: 0;
}

.monitoring-sheets-list {
    padding: 16px 20px;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.monitoring-sheet-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.monitoring-sheet-item:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
}

.sheet-title {
    font-size: 14px;
    color: #475569;
    flex: 1;
    font-weight: 600;
}

.sheet-date {
    font-size: 12px;
    color: #94a3b8;
}

.action-menu-btn {
    background: none;
    border: none;
    color: #64748b;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-menu-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
}

.delete-year-btn {
    background: none;
    border: none;
    color: #ef4444;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
}

.delete-year-btn:hover {
    background: #fee2e2;
    color: #dc2626;
}

.tbl-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}
.tbl-action-btn.edit {
    color: #2563eb;
}
.tbl-action-btn.edit:hover {
    background: #eff6ff;
    border-color: #93c5fd;
}
.tbl-action-btn.delete {
    color: #ef4444;
}
.tbl-action-btn.delete:hover {
    background: #fee2e2;
    border-color: #fca5a5;
}

.priority-tag {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 600;
}
.priority-low {
    background-color: #f1f5f9;
    color: #475569;
}
.priority-medium, .priority-moderate {
    background-color: #fef3c7;
    color: #d97706;
}
.priority-high, .priority-urgent {
    background-color: #fee2e2;
    color: #ef4444;
}

.status-rejected, .status-returned {
    background-color: #fee2e2 !important;
    color: #ef4444 !important;
}
</style>
