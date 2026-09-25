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

                <!-- Content & Editorial Dropdown -->
                <div>
                    <a href="#" class="nav-item has-dropdown" :class="{ active: ['my-articles', 'articles', 'videos', 'assignments', 'pressWorks', 'press-works', 'gallery', 'published-issues'].includes(activeTab), open: openDropdown === 'content' }" @click.prevent="openDropdown = openDropdown === 'content' ? null : 'content'">
                        <div class="nav-item-left">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="16" y1="13" x2="8" y2="13" />
                                <line x1="16" y1="17" x2="8" y2="17" />
                            </svg>
                            Content &amp; Editorial
                        </div>
                        <svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </a>
                    <div class="sub-menu" v-show="openDropdown === 'content'" :class="{ open: openDropdown === 'content' }">
                        <a href="#" class="sub-item" :class="{ active: activeTab === 'my-articles' }" @click.prevent="activeTab = 'my-articles'; openDropdown = 'content'">
                            My Articles
                            <svg v-if="activeTab === 'my-articles'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                        <a href="#" class="sub-item" :class="{ active: activeTab === 'articles' }" @click.prevent="activeTab = 'articles'; openDropdown = 'content'">
                            Published Articles
                            <svg v-if="activeTab === 'articles'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                        <a href="#" class="sub-item" :class="{ active: activeTab === 'videos' }" @click.prevent="activeTab = 'videos'; openDropdown = 'content'">
                            Videos
                            <svg v-if="activeTab === 'videos'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                        <a href="#" class="sub-item" :class="{ active: activeTab === 'assignments' }" @click.prevent="activeTab = 'assignments'; openDropdown = 'content'">
                            Assignments
                            <svg v-if="activeTab === 'assignments'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                        <a href="#" class="sub-item" :class="{ active: activeTab === 'pressWorks' || activeTab === 'press-works' }" @click.prevent="activeTab = 'pressWorks'; openDropdown = 'content'">
                            Press Works
                            <svg v-if="activeTab === 'pressWorks' || activeTab === 'press-works'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                        <a href="#" class="sub-item" :class="{ active: activeTab === 'gallery' }" @click.prevent="activeTab = 'gallery'; openDropdown = 'content'">
                            Gallery
                            <svg v-if="activeTab === 'gallery'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                        <a href="#" class="sub-item" :class="{ active: activeTab === 'published-issues' }" @click.prevent="activeTab = 'published-issues'; openDropdown = 'content'">
                            Published Issues
                            <svg v-if="activeTab === 'published-issues'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                    </div>
                </div>

                <!-- Management & Workflow Dropdown -->
                <div>
                    <a href="#" class="nav-item has-dropdown" :class="{ active: ['contributors', 'endorsements'].includes(activeTab), open: openDropdown === 'management' }" @click.prevent="openDropdown = openDropdown === 'management' ? null : 'management'">
                        <div class="nav-item-left">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            Management &amp; Workflow
                        </div>
                        <svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </a>
                    <div class="sub-menu" v-show="openDropdown === 'management'" :class="{ open: openDropdown === 'management' }">
                        <a href="#" class="sub-item" :class="{ active: activeTab === 'contributors' }" @click.prevent="activeTab = 'contributors'; openDropdown = 'management'">
                            Contributors
                            <svg v-if="activeTab === 'contributors'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                        <a href="#" class="sub-item" :class="{ active: activeTab === 'endorsements' }" @click.prevent="activeTab = 'endorsements'; openDropdown = 'management'">
                            Endorsements
                            <svg v-if="activeTab === 'endorsements'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                    </div>
                </div>

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
                <MobileNavToggle />
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input v-model="searchInput" type="search" :placeholder="searchPlaceholder" aria-label="Search current view">
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
                    <!-- Opens the live reader site in a new tab; the EIC stays signed in there -->
                    <a href="/" target="_blank" rel="noopener" class="view-site-btn" title="View the live reader site (you stay signed in)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                        <span>View Site</span>
                    </a>
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
                        <div>
                            <h1 class="page-title" style="margin-bottom: 0;">Endorsements</h1>
                            <p v-if="isEicSectionEditor" style="margin: 6px 0 0; font-size: 12.5px; color: #64748b; max-width: 560px;">
                                As a {{ eicEditorTitles.join(' / ') }}, submitted articles{{ eicEditorSections.length ? ' in ' + eicEditorSections.join(', ') : '' }} appear here as <strong>For Section Review</strong>. Send them to a copyreader; they return here as Endorsed for your final approval.
                            </p>
                        </div>
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
                                <tr v-for="article in paginatedEicEndorsements" :key="article.id" @click="openEndorsementRow(article)" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 600;">{{ article.title }}</td>
                                    <td><span class="section-badge">{{ resolveArticleSection(article) || 'Unassigned' }}</span></td>
                                    <td><span class="status-pill" :class="eicStatusClass(article.status)">{{ eicRowStatusLabel(article) }}</span></td>
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

                <!-- VIDEOS TAB -->
                <div v-show="activeTab === 'videos'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Videos</h1>
                        <div class="filter-pills-group eic-endorsement-filters">
                            <div class="eic-custom-filter" @click.stop>
                                <button type="button" class="eic-filter-trigger" @click="toggleVideoCategoryFilter">
                                    <span>{{ videoCategoryFilter === 'all' ? 'Section' : videoCategoryFilter }}</span>
                                    <svg :class="{ rotated: activeVideoCategoryFilter }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <div v-if="activeVideoCategoryFilter" class="eic-filter-menu">
                                    <button v-for="option in videoCategoryOptions" :key="option.value" type="button" :class="{ selected: videoCategoryFilter === option.value }" @click="selectVideoCategory(option.value)">{{ option.label }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="padding: 24px; background: #ffffff; border-radius: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);">
                        <div v-if="!filteredEicVideos.length" class="empty-activity" style="text-align: center; padding: 40px;">
                            {{ eicPublishedVideos.length ? 'No videos match this section.' : 'No published videos yet. Videos appear here once you publish them from Endorsements.' }}
                        </div>
                        <div v-else class="gallery-grid">
                            <div v-for="video in filteredEicVideos" :key="video.id" class="gallery-card" @click="openVideoManage(video)" style="cursor: pointer;">
                                <div class="gallery-card-img-wrap video-card-thumb">
                                    <img v-if="videoThumbnailFor(video)" :src="videoThumbnailFor(video)" :alt="video.title" class="gallery-card-img" />
                                    <span class="video-card-play">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#ffffff"><polygon points="6 4 20 12 6 20 6 4"></polygon></svg>
                                    </span>
                                    <span class="video-card-badge" :class="{ scheduled: video.status === 'scheduled' }">{{ video.status === 'scheduled' ? 'Scheduled' : (video.video_category || 'Uncategorized') }}</span>
                                </div>
                                <div class="gallery-card-body">
                                    <span class="gallery-card-title">{{ video.title }}</span>
                                    <span class="gallery-card-meta">{{ video.author?.name || 'Unknown' }} &bull; {{ formatDate(video.published_at || video.scheduled_at || video.created_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GALLERY TAB -->
                <div v-show="activeTab === 'gallery'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Gallery</h1>
                        <button class="new-user-btn" type="button" @click="openGalleryUploadModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                            Upload Photo
                        </button>
                    </div>

                    <div class="card" style="padding: 24px; background: #ffffff; border-radius: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);">
                        <div v-if="galleryLoading" class="empty-activity" style="text-align: center; padding: 40px;">Loading photos…</div>
                        <div v-else-if="shownGalleryPhotos.length === 0 && galleryPhotos.length > 0" class="empty-activity" style="text-align: center; padding: 40px;">No photos match your search.</div>
                        <div v-else-if="galleryPhotos.length === 0" class="empty-activity" style="text-align: center; padding: 40px;">No photos yet. Click "Upload Photo" to add one.</div>
                        <div v-else class="gallery-grid">
                            <div v-for="photo in shownGalleryPhotos" :key="photo.id" class="gallery-card" @click="openGalleryViewModal(photo)" style="cursor: pointer;">
                                <div class="gallery-card-img-wrap">
                                    <img :src="photo.image_url" :alt="photo.title" class="gallery-card-img" />
                                </div>
                                <div class="gallery-card-body">
                                    <span class="gallery-card-title">{{ photo.title }}</span>
                                    <span class="gallery-card-meta">{{ photo.artist?.name || photo.uploader?.name || 'Unknown' }} &bull; {{ formatDate(photo.created_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PUBLISHED ISSUES TAB -->
                <div v-show="activeTab === 'published-issues'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Published Issues</h1>
                        <button class="new-user-btn" type="button" @click="openIssueUploadModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                            Upload Published Issue
                        </button>
                    </div>

                    <div class="card" style="padding: 24px; background: #ffffff; border-radius: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);">
                        <div v-if="issuesLoading" class="empty-activity" style="text-align: center; padding: 40px;">Loading issues…</div>
                        <div v-else-if="shownPublishedIssues.length === 0 && publishedIssues.length > 0" class="empty-activity" style="text-align: center; padding: 40px;">No issues match your search.</div>
                        <div v-else-if="publishedIssues.length === 0" class="empty-activity" style="text-align: center; padding: 40px;">No published issues yet. Click "Upload Published Issue" to add one.</div>
                        <div v-else class="gallery-grid">
                            <div v-for="issue in shownPublishedIssues" :key="issue.id" class="gallery-card" @click="openIssueDetailModal(issue)" style="cursor: pointer;">
                                <div class="gallery-card-img-wrap">
                                    <img v-if="issueThumbnails[issue.id]" :src="issueThumbnails[issue.id]" :alt="issue.title" class="gallery-card-img" />
                                    <div v-else class="issue-row-icon" style="margin: auto;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                    </div>
                                </div>
                                <div class="gallery-card-body">
                                    <span class="gallery-card-title">{{ issue.title }}</span>
                                    <span class="gallery-card-meta">{{ issue.uploader?.name || 'Unknown' }} &bull; {{ formatDate(issue.created_at) }}</span>
                                </div>
                            </div>
                        </div>
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
                                <SkeletonRows v-if="eicMyArticlesLoading" :columns="4" />
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
                                <SkeletonRows v-if="eicAssignedLoading" :columns="6" />
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
                            <button class="new-user-btn" type="button" @click="isDirectPublishOpen = true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path></svg>
                                Publish Automatic/Past Article
                            </button>
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
                                <tr v-for="article in paginatedEicPublishedArticles" :key="article.id" @click="openArticlePreview(article)" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 600;">{{ article.title }}</td>
                                    <td><span class="section-badge">{{ resolveArticleSection(article) || 'General' }}</span></td>
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
                <div v-show="activeTab === 'contributors'" class="pinned-pagination-tab" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
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
                                    <td style="white-space: normal; overflow: visible; text-overflow: clip;">
                                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                            <span v-if="user.secondary_role" class="section-badge">{{ user.secondary_role }}</span>
                                            <span v-if="user.tertiary_role" class="section-badge">{{ user.tertiary_role }}</span>
                                            <span v-if="!user.secondary_role && !user.tertiary_role" style="color: #94a3b8;">—</span>
                                        </div>
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
                                            <button v-if="!isDeleteHidden(user)" class="action-btn delete" type="button" aria-label="Delete contributor" @click="openDeleteUser(user)">
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
                                        <td><span class="section-badge">{{ resolveArticleSection(article) || 'Unassigned' }}</span></td>
                                        <td style="color: #64748b;">{{ article.author?.name || 'Unknown' }}</td>
                                        <td><span class="status-pill" :class="articleStatusClass(article.status)">{{ articleStatusLabel(article.status) }}</span></td>
                                        <td style="color: #64748b;">{{ formatDate(article.created_at) }}</td>
                                        <td style="text-align: center; padding-right: 20px;">
                                            <div class="action-icons" style="display: flex; gap: 8px; justify-content: center;">
                                                <button class="action-btn edit" type="button" aria-label="View" @click="openArchiveItem(article)">
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
                                <div class="table-scroll">
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
</div>
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

                            <!-- Peak Viewing Time: what time of day readers open articles -->
                            <PeakTimeCard :hourly="analytics.hourly" />

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
        :allow-section-edit="isEicEditingArticle"
        @close="isWorkspaceModalOpen = false; isEicEditingArticle = false; loadEicMyArticles(); loadEicArticles();"
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
        @request-publish-preview="handleRequestPublishPreview"
    />

    <!-- Section-editor review of a submitted article (for an Editor-in-Chief who is also a section editor) -->
    <SEReviewModal
        :is-open="isEicSectionReviewOpen"
        :submission="eicSectionReviewTarget"
        @close="isEicSectionReviewOpen = false; eicSectionReviewTarget = null;"
        @reviewed="loadEicOverview(); loadEicArticles(); loadEicMyArticles();"
    />

    <!-- Article Preview Modal (Publish / Schedule / Published state) -->
    <ArticlePreviewModal
        :is-open="isArticlePreviewOpen"
        :article-data="selectedArticle"
        @close="isArticlePreviewOpen = false"
        @article-updated="handleArticleUpdated"
        @action-complete="handleArticleDeleted"
        @edit-article="handleEditArticleFromPreview"
    />

    <!-- Read-only preview for videos the EIC was credited on -->
    <ArticlePreviewModal
        :is-open="isCreditedVideoPreviewOpen"
        :article-data="creditedVideoPreview"
        read-only
        @close="isCreditedVideoPreviewOpen = false"
    />

    <!-- Publish an urgent or past article without the review workflow -->
    <DirectPublishModal
        :is-open="isDirectPublishOpen"
        @close="isDirectPublishOpen = false"
        @published="loadEicArticles(); loadEicOverview();"
    />

    <!-- Video view / edit / delete (Videos tab) -->
    <VideoManageModal
        :is-open="isVideoManageOpen"
        :video="manageVideo"
        :start-in-edit="manageVideoStartInEdit"
        @close="closeVideoManage"
        @updated="handleVideoUpdated"
        @deleted="handleVideoDeleted"
    />

    <!-- Archive Article Preview (read-only) -->
    <ArticlePreviewModal
        :is-open="isArchivePreviewOpen"
        :article-data="selectedArticle"
        archived
        @close="isArchivePreviewOpen = false"
        @action-complete="handleArticleDeleted"
        @edit-article="handleEditArticleFromPreview"
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

    <!-- Upload Photo (Gallery) Modal -->
    <div v-if="isGalleryUploadModalOpen" class="new-user-modal-overlay" @click.self="closeGalleryUploadModal">
        <form class="new-user-modal" role="dialog" aria-modal="true" @submit.prevent="submitGalleryUpload">
            <div class="new-user-modal-header">
                <h2>Upload Photo</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="closeGalleryUploadModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="new-user-step">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input v-model="galleryUploadForm.title" class="form-control" placeholder="Photo title" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Author</label>
                    <AuthorSelect v-model="galleryUploadForm.artist_id" :options="galleryArtists" />
                </div>
                <div class="form-group">
                    <label class="form-label">Photo</label>
                    <div class="upload-box">
                        <div class="upload-circle" :class="{ 'has-preview': galleryUploadPreview }">
                            <img v-if="galleryUploadPreview" :src="galleryUploadPreview" alt="Photo preview">
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        </div>
                        <div class="upload-info">
                            <p>Choose a file or drag & drop it here.<br>jpeg, png, gif, webp - Up to 10MB</p>
                            <input ref="galleryFileInput" type="file" accept="image/jpeg,image/png,image/gif,image/webp" hidden @change="handleGalleryPhotoChange">
                            <button class="btn-upload" type="button" @click="galleryFileInput?.click()">Upload photo <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 2px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="7" y2="8"></line></svg></button>
                        </div>
                    </div>
                </div>
                <p v-if="galleryUploadError" class="new-user-error">{{ galleryUploadError }}</p>
                <div class="modal-footer">
                    <button class="btn-back" type="button" @click="closeGalleryUploadModal">Cancel</button>
                    <button class="btn-next" type="submit" :disabled="galleryUploadSaving">{{ galleryUploadSaving ? 'Uploading...' : 'Upload Photo' }}</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Gallery Photo View / Edit Modal -->
    <div v-if="isGalleryViewModalOpen" class="new-user-modal-overlay" @click.self="closeGalleryViewModal">
        <div class="new-user-modal gallery-view-modal" role="dialog" aria-modal="true">
            <div class="new-user-modal-header">
                <h2>{{ isEditingGalleryPhoto ? 'Edit Photo' : viewingPhoto?.title }}</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="closeGalleryViewModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <!-- View mode -->
            <div v-if="!isEditingGalleryPhoto" class="new-user-step">
                <img :src="viewingPhoto?.image_url" :alt="viewingPhoto?.title" class="gallery-view-img">
                <p class="gallery-card-meta" style="margin-top: 10px;">
                    <template v-if="viewingPhoto?.artist">Artist: {{ viewingPhoto.artist.name }} &bull; </template>Uploaded by {{ viewingPhoto?.uploader?.name || 'Unknown' }} &bull; {{ formatDate(viewingPhoto?.created_at) }}
                </p>
                <div class="modal-footer">
                    <button class="btn-back" type="button" style="color: #dc2626;" @click="deletePhotoFromViewModal">Delete</button>
                    <button class="btn-next" type="button" @click="startEditGalleryPhoto">Edit</button>
                </div>
            </div>

            <!-- Edit mode -->
            <form v-else class="new-user-step" @submit.prevent="submitEditGalleryPhoto">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input v-model="editGalleryForm.title" class="form-control" placeholder="Photo title" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Author</label>
                    <AuthorSelect v-model="editGalleryForm.artist_id" :options="galleryArtists" />
                </div>
                <div class="form-group">
                    <label class="form-label">Photo</label>
                    <div class="upload-box">
                        <div class="upload-circle has-preview">
                            <img :src="editGalleryPreview" alt="Photo preview">
                        </div>
                        <div class="upload-info">
                            <p>Choose a file to replace the current photo.<br>jpeg, png, gif, webp - Up to 10MB</p>
                            <input ref="editGalleryFileInput" type="file" accept="image/jpeg,image/png,image/gif,image/webp" hidden @change="handleEditGalleryPhotoChange">
                            <button class="btn-upload" type="button" @click="editGalleryFileInput?.click()">Replace photo <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 2px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="7" y2="8"></line></svg></button>
                        </div>
                    </div>
                </div>
                <p v-if="editGalleryError" class="new-user-error">{{ editGalleryError }}</p>
                <div class="modal-footer">
                    <button class="btn-back" type="button" @click="cancelEditGalleryPhoto">Cancel</button>
                    <button class="btn-next" type="submit" :disabled="editGallerySaving">{{ editGallerySaving ? 'Saving...' : 'Save Changes' }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Gallery Photo Confirmation Modal -->
    <div v-if="photoToDelete" class="new-user-modal-overlay" @click.self="photoToDelete = null">
        <div class="new-user-modal new-user-confirm-modal" role="dialog" aria-modal="true">
            <div class="new-user-modal-header">
                <h2>Delete Photo?</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="photoToDelete = null">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <p>Delete <strong>"{{ photoToDelete.title }}"</strong>? This action cannot be undone.</p>
            <p v-if="deletePhotoError" class="new-user-error">{{ deletePhotoError }}</p>
            <div class="modal-footer">
                <button class="btn-back" type="button" @click="photoToDelete = null">Cancel</button>
                <button class="btn-next btn-danger" type="button" :disabled="deletePhotoSaving" @click="deleteGalleryPhoto">{{ deletePhotoSaving ? 'Deleting...' : 'Delete Photo' }}</button>
            </div>
        </div>
    </div>

    <!-- Upload Published Issue Modal -->
    <div v-if="isIssueUploadModalOpen" class="new-user-modal-overlay" @click.self="closeIssueUploadModal">
        <form class="new-user-modal" role="dialog" aria-modal="true" @submit.prevent="submitIssueUpload">
            <div class="new-user-modal-header">
                <h2>Upload Published Issue</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="closeIssueUploadModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="new-user-step">
                <p class="presswork-modal-note">The uploaded PDF will be converted into a flipbook for readers.</p>
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input v-model="issueUploadForm.title" class="form-control" placeholder="e.g. AY 2025-2026 Issue 1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">PDF File</label>
                    <div class="upload-box">
                        <div class="upload-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                        </div>
                        <div class="upload-info">
                            <p v-if="issueUploadFileName">{{ issueUploadFileName }}</p>
                            <p v-else>Choose a PDF file to upload.<br>PDF only - Up to 35MB</p>
                            <input ref="issueFileInput" type="file" accept="application/pdf" hidden @change="handleIssuePdfChange">
                            <button class="btn-upload" type="button" @click="issueFileInput?.click()">Upload PDF <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 2px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="7" y2="8"></line></svg></button>
                        </div>
                    </div>
                </div>
                <p v-if="issueUploadError" class="new-user-error">{{ issueUploadError }}</p>
                <div class="modal-footer">
                    <button class="btn-back" type="button" @click="closeIssueUploadModal">Cancel</button>
                    <button class="btn-next" type="submit" :disabled="issueUploadSaving">{{ issueUploadSaving ? 'Uploading...' : 'Upload Issue' }}</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Published Issue Detail / Edit Modal -->
    <div v-if="isIssueDetailModalOpen" class="new-user-modal-overlay" @click.self="closeIssueDetailModal">
        <div class="new-user-modal" role="dialog" aria-modal="true">
            <div class="new-user-modal-header">
                <h2>{{ isEditingIssue ? 'Edit Published Issue' : viewingIssue?.title }}</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="closeIssueDetailModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <!-- Detail mode -->
            <div v-if="!isEditingIssue" class="new-user-step">
                <div class="issue-thumb-preview">
                    <img v-if="issueThumbnails[viewingIssue?.id]" :src="issueThumbnails[viewingIssue?.id]" alt="" class="issue-thumb-img" />
                    <div v-else-if="issueThumbnailLoading" class="issue-thumb-placeholder">Loading preview…</div>
                    <div v-else class="issue-thumb-placeholder">No preview available</div>
                </div>
                <p class="gallery-card-meta" style="margin-top: 10px;">{{ viewingIssue?.uploader?.name || 'Unknown' }} &bull; {{ formatDate(viewingIssue?.created_at) }}</p>
                <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                    <button class="btn-back" type="button" style="color: #dc2626;" @click="deleteIssueFromDetailModal">Delete</button>
                    <div style="display: flex; gap: 14px;">
                        <button class="btn-back" type="button" @click="startEditIssue">Edit</button>
                        <button class="btn-next" type="button" @click="viewIssueBooklet">View Booklet</button>
                    </div>
                </div>
            </div>

            <!-- Edit mode -->
            <form v-else class="new-user-step" @submit.prevent="submitEditIssue">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input v-model="editIssueForm.title" class="form-control" placeholder="e.g. AY 2025-2026 Issue 1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">PDF File</label>
                    <div class="upload-box">
                        <div class="upload-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                        </div>
                        <div class="upload-info">
                            <p v-if="editIssueFileName">{{ editIssueFileName }}</p>
                            <p v-else>Choose a PDF file to replace the current one.<br>PDF only - Up to 35MB</p>
                            <input ref="editIssueFileInput" type="file" accept="application/pdf" hidden @change="handleEditIssuePdfChange">
                            <button class="btn-upload" type="button" @click="editIssueFileInput?.click()">Replace PDF <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 2px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="7" y2="8"></line></svg></button>
                        </div>
                    </div>
                </div>
                <p v-if="editIssueError" class="new-user-error">{{ editIssueError }}</p>
                <div class="modal-footer">
                    <button class="btn-back" type="button" @click="cancelEditIssue">Cancel</button>
                    <button class="btn-next" type="submit" :disabled="editIssueSaving">{{ editIssueSaving ? 'Saving...' : 'Save Changes' }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Published Issue Confirmation Modal -->
    <div v-if="issueToDelete" class="new-user-modal-overlay" @click.self="issueToDelete = null">
        <div class="new-user-modal new-user-confirm-modal" role="dialog" aria-modal="true">
            <div class="new-user-modal-header">
                <h2>Delete Issue?</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="issueToDelete = null">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <p>Delete <strong>"{{ issueToDelete.title }}"</strong>? This action cannot be undone.</p>
            <p v-if="deleteIssueError" class="new-user-error">{{ deleteIssueError }}</p>
            <div class="modal-footer">
                <button class="btn-back" type="button" @click="issueToDelete = null">Cancel</button>
                <button class="btn-next btn-danger" type="button" :disabled="deleteIssueSaving" @click="deleteIssue">{{ deleteIssueSaving ? 'Deleting...' : 'Delete Issue' }}</button>
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
                <div class="form-group">
                    <label class="form-label">Profile Picture</label>
                    <div class="upload-box">
                        <div class="upload-circle" :class="{ 'has-preview': editUserImagePreview }">
                            <img v-if="editUserImagePreview" :src="editUserImagePreview" alt="Profile preview">
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        </div>
                        <div class="upload-info">
                            <p>Choose a new photo for this contributor.<br>jpeg, png, webp - Up to 4MB</p>
                            <input ref="editUserFileInput" type="file" accept="image/jpeg,image/png,image/webp" hidden @change="handleEditUserImage">
                            <button class="btn-upload" type="button" @click="editUserFileInput?.click()">{{ editUserImagePreview ? 'Change image' : 'Upload image' }} <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 2px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="7" y2="8"></line></svg></button>
                        </div>
                    </div>
                </div>
                <div class="form-group"><label class="form-label">Full Name</label><input v-model="editUserForm.name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Email</label><input v-model="editUserForm.email" type="email" class="form-control" required></div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select v-model="editUserForm.role" class="form-control select-control" required :disabled="isEditingSelf" @change="editUserForm.secondary_role = ''; editUserForm.tertiary_role = ''">
                            <option value="eic">Editor-in-Chief</option>
                            <option value="section_editor">Section Editor</option>
                            <option value="staff_writer">Staff Writer</option>
                            <option value="staff_artist">Staff Artist</option>
                            <option value="staff_broadcaster">Staff Broadcaster</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select v-model="editUserForm.is_active" class="form-control select-control" :disabled="isEditingSelf">
                            <option :value="true">Active</option>
                            <option :value="false">Inactive</option>
                        </select>
                    </div>
                </div>
                <p v-if="isEditingSelf" class="form-hint" style="margin: 6px 0 0; font-size: 12px; color: #64748b;">
                    This is your own account. To step down as Editor-in-Chief, promote the next Editor-in-Chief: they can then change your position or set you inactive.
                </p>
                <p v-else-if="editUserForm.role === 'eic' && selectedUser?.role !== 'eic'" class="form-hint" style="margin: 6px 0 0; font-size: 12px; color: #64748b;">
                    This person will become an Editor-in-Chief and get the same access as you. They can then change your position or set you inactive.
                </p>
                <div v-if="SECONDARY_ROLES[editUserForm.role]" class="form-group" style="margin-top: 8px;">
                    <label class="form-label">{{ editUserForm.role === 'eic' ? 'Also a Section Editor (optional)' : 'Section / Secondary Role' }}</label>
                    <select v-model="editUserForm.secondary_role" class="form-control select-control" @change="editUserForm.tertiary_role = ''">
                        <option value="">None / Default</option>
                        <option v-for="secRole in SECONDARY_ROLES[editUserForm.role]" :key="secRole" :value="secRole">{{ secRole }}</option>
                    </select>
                </div>
                <div v-if="['section_editor', 'eic'].includes(editUserForm.role) && editUserForm.secondary_role" class="form-group" style="margin-top: 8px;">
                    <label class="form-label">Additional Section Editor Role</label>
                    <select v-model="editUserForm.tertiary_role" class="form-control select-control">
                        <option value="">None</option>
                        <option v-for="secRole in SECONDARY_ROLES.section_editor.filter(r => r !== editUserForm.secondary_role)" :key="secRole" :value="secRole">{{ secRole }}</option>
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
                                <select v-model="newContributorForm.role" class="form-control select-control" @change="newContributorForm.secondary_role = ''; newContributorForm.tertiary_role = ''">
                                    <option value="" disabled>Role</option>
                                    <option value="eic">Editor-in-Chief</option>
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
                        <label class="form-label">{{ newContributorForm.role === 'eic' ? 'Also a Section Editor (optional)' : 'Section / Secondary Role' }}</label>
                        <div class="input-icon-wrap custom-select-wrap">
                            <select v-model="newContributorForm.secondary_role" class="form-control select-control" @change="newContributorForm.tertiary_role = ''">
                                <option value="">None / Default</option>
                                <option v-for="secRole in SECONDARY_ROLES[newContributorForm.role]" :key="secRole" :value="secRole">{{ secRole }}</option>
                            </select>
                        </div>
                    </div>
                    <div v-if="['section_editor', 'eic'].includes(newContributorForm.role) && newContributorForm.secondary_role" class="form-group" style="margin-top: 12px;">
                        <label class="form-label">Additional Section Editor Role</label>
                        <div class="input-icon-wrap custom-select-wrap">
                            <select v-model="newContributorForm.tertiary_role" class="form-control select-control">
                                <option value="">None</option>
                                <option v-for="secRole in SECONDARY_ROLES.section_editor.filter(r => r !== newContributorForm.secondary_role)" :key="secRole" :value="secRole">{{ secRole }}</option>
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
import MobileNavToggle from '../../components/MobileNavToggle.vue';
import { computed, onMounted, onUnmounted, reactive, ref, watch } from 'vue';
import { lazyModal } from '../../utils/lazyModal';
import { useDebouncedSearch } from '../../utils/dashboardSearch';
import { useRouter } from 'vue-router';
const AssignTaskModal = lazyModal(() => import('../../components/AssignTaskModal.vue'));
const AssignedTaskModal = lazyModal(() => import('../../components/AssignedTaskModal.vue'));
const AssignmentWorkspaceModal = lazyModal(() => import('../../components/AssignmentWorkspaceModal.vue'));
const ArticleDetailsModal = lazyModal(() => import('../../components/ArticleDetailsModal.vue'));
const SEReviewModal = lazyModal(() => import('../../components/SEReviewModal.vue'));
const ArticlePreviewModal = lazyModal(() => import('../../components/ArticlePreviewModal.vue'));
const VideoManageModal = lazyModal(() => import('../../components/VideoManageModal.vue'));
const DirectPublishModal = lazyModal(() => import('../../components/DirectPublishModal.vue'));
import { VIDEO_CATEGORIES, youtubeThumbnail, fetchCreditedVideos, buildVideoPreviewData } from '../../utils/video';
const EditTaskModal = lazyModal(() => import('../../components/EditTaskModal.vue'));
import NotificationsPopover from '../../components/NotificationsPopover.vue';
import AuthorSelect from '../../components/AuthorSelect.vue';
import PeakTimeCard from '../../components/PeakTimeCard.vue';
import { signOut as performSignOut } from '../../utils/auth';

const router = useRouter();
const activeTab = ref('overview');
const openDropdown = ref(null);
const { input: searchInput, query: searchQuery } = useDebouncedSearch();
const eicUser = ref(JSON.parse(localStorage.getItem('sparky_user') || '{}'));
const eicOverview = ref({
    summary: { articles: 0, endorsements: 0, ready_to_publish: 0, published: 0 },
    activities: [],
    updated_at: null,
});
const eicArticles = ref([]);
const eicVideos = ref([]);
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
const isArticlePreviewOpen = ref(false);
const isArchivePreviewOpen = ref(false);
const isEicEditingArticle = ref(false);

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

// ── Gallery ──────────────────────────────────────────────────────────────────
const galleryPhotos = ref([]);
const galleryLoading = ref(false);
const isGalleryUploadModalOpen = ref(false);
const galleryUploadForm = ref({ title: '', artist_id: '' });
const galleryArtists = ref([]);
const galleryUploadFile = ref(null);
const galleryUploadPreview = ref('');
const galleryFileInput = ref(null);
const galleryUploadError = ref('');
const galleryUploadSaving = ref(false);
const photoToDelete = ref(null);
const deletePhotoError = ref('');
const deletePhotoSaving = ref(false);
const isGalleryViewModalOpen = ref(false);
const viewingPhoto = ref(null);
const isEditingGalleryPhoto = ref(false);
const editGalleryForm = ref({ title: '', artist_id: '' });
const editGalleryFile = ref(null);
const editGalleryPreview = ref('');
const editGalleryFileInput = ref(null);
const editGalleryError = ref('');
const editGallerySaving = ref(false);

// ── Published Issues ────────────────────────────────────────────────────────
const publishedIssues = ref([]);
const issuesLoading = ref(false);
const isIssueUploadModalOpen = ref(false);
const issueUploadForm = ref({ title: '' });
const issueUploadFile = ref(null);
const issueUploadFileName = ref('');
const issueFileInput = ref(null);
const issueUploadError = ref('');
const issueUploadSaving = ref(false);
const issueToDelete = ref(null);
const deleteIssueError = ref('');
const deleteIssueSaving = ref(false);
const isIssueDetailModalOpen = ref(false);
const viewingIssue = ref(null);
const isEditingIssue = ref(false);
const editIssueForm = ref({ title: '' });
const editIssueFile = ref(null);
const editIssueFileName = ref('');
const editIssueFileInput = ref(null);
const editIssueError = ref('');
const editIssueSaving = ref(false);
const issueThumbnails = ref({});
const issueThumbnailLoading = ref(false);

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
        case 'videos': return 'Search videos';
        case 'contributors': return 'Search contributors';
        case 'archive': return 'Search archive folders';
        case 'archive-year': return 'Search archived articles';
        case 'analytics': return 'Search analytics';
        case 'assignments': return 'Search assignments';
        case 'gallery': return 'Search gallery photos';
        case 'published-issues': return 'Search published issues';
        default: return 'Search';
    }
});

const matchesSearch = (...fields) => {
    if (!searchQuery.value || !searchQuery.value.trim()) return true;
    const query = searchQuery.value.toLowerCase().trim();
    return fields.some(field => String(field || '').toLowerCase().includes(query));
};

const shownGalleryPhotos = computed(() => galleryPhotos.value.filter(photo => matchesSearch(
    photo.title,
    photo.artist?.name,
    photo.uploader?.name,
)));

const shownPublishedIssues = computed(() => publishedIssues.value.filter(issue => matchesSearch(
    issue.title,
    issue.uploader?.name,
)));

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

// ── Gallery ──────────────────────────────────────────────────────────────────
const loadGalleryPhotos = async () => {
    galleryLoading.value = true;
    try {
        const response = await fetch('/api/gallery', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        if (response.ok) galleryPhotos.value = await response.json();
    } catch {
        galleryPhotos.value = [];
    } finally {
        galleryLoading.value = false;
    }
};

// Authors that can be credited on a photo: the staff artists and the Art Editor
const loadGalleryArtists = async () => {
    try {
        const response = await fetch('/api/gallery/artists', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        if (response.ok) galleryArtists.value = await response.json();
    } catch {
        galleryArtists.value = [];
    }
};

const openGalleryUploadModal = () => {
    loadGalleryArtists();
    galleryUploadForm.value = { title: '', artist_id: '' };
    galleryUploadFile.value = null;
    galleryUploadPreview.value = '';
    galleryUploadError.value = '';
    isGalleryUploadModalOpen.value = true;
};

const closeGalleryUploadModal = () => {
    isGalleryUploadModalOpen.value = false;
};

const handleGalleryPhotoChange = (event) => {
    const file = event.target.files?.[0] || null;
    galleryUploadFile.value = file;
    galleryUploadPreview.value = file ? URL.createObjectURL(file) : '';
};

const submitGalleryUpload = async () => {
    galleryUploadError.value = '';
    if (!galleryUploadForm.value.title.trim()) {
        galleryUploadError.value = 'Please provide a title.';
        return;
    }
    if (!galleryUploadForm.value.artist_id) {
        galleryUploadError.value = 'Please select the author of the photo.';
        return;
    }
    if (!galleryUploadFile.value) {
        galleryUploadError.value = 'Please choose a photo to upload.';
        return;
    }

    galleryUploadSaving.value = true;
    const payload = new FormData();
    payload.append('title', galleryUploadForm.value.title);
    payload.append('artist_id', galleryUploadForm.value.artist_id);
    payload.append('photo', galleryUploadFile.value);

    try {
        const response = await fetch('/api/gallery', {
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
            throw new Error(validationError || 'Could not upload photo.');
        }
        galleryPhotos.value.unshift(data);
        closeGalleryUploadModal();
    } catch (error) {
        galleryUploadError.value = error.message;
    } finally {
        galleryUploadSaving.value = false;
    }
};

const confirmDeleteGalleryPhoto = (photo) => {
    photoToDelete.value = photo;
    deletePhotoError.value = '';
};

const deleteGalleryPhoto = async () => {
    if (!photoToDelete.value) return;
    deletePhotoSaving.value = true;
    deletePhotoError.value = '';
    try {
        const response = await fetch(`/api/gallery/${photoToDelete.value.id}`, {
            method: 'DELETE',
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw new Error(data.message || 'Could not delete photo.');
        }
        galleryPhotos.value = galleryPhotos.value.filter(p => p.id !== photoToDelete.value.id);
        photoToDelete.value = null;
    } catch (error) {
        deletePhotoError.value = error.message;
    } finally {
        deletePhotoSaving.value = false;
    }
};

const openGalleryViewModal = (photo) => {
    viewingPhoto.value = photo;
    isEditingGalleryPhoto.value = false;
    isGalleryViewModalOpen.value = true;
};

const closeGalleryViewModal = () => {
    isGalleryViewModalOpen.value = false;
    isEditingGalleryPhoto.value = false;
};

const startEditGalleryPhoto = () => {
    loadGalleryArtists();
    editGalleryForm.value = { title: viewingPhoto.value?.title || '', artist_id: viewingPhoto.value?.artist_id || '' };
    editGalleryFile.value = null;
    editGalleryPreview.value = viewingPhoto.value?.image_url || '';
    editGalleryError.value = '';
    isEditingGalleryPhoto.value = true;
};

const cancelEditGalleryPhoto = () => {
    isEditingGalleryPhoto.value = false;
};

const handleEditGalleryPhotoChange = (event) => {
    const file = event.target.files?.[0] || null;
    if (!file) return;
    editGalleryFile.value = file;
    editGalleryPreview.value = URL.createObjectURL(file);
};

const submitEditGalleryPhoto = async () => {
    editGalleryError.value = '';
    if (!editGalleryForm.value.title.trim()) {
        editGalleryError.value = 'Please provide a title.';
        return;
    }
    if (!editGalleryForm.value.artist_id) {
        editGalleryError.value = 'Please select the author of the photo.';
        return;
    }

    editGallerySaving.value = true;
    const payload = new FormData();
    payload.append('title', editGalleryForm.value.title);
    payload.append('artist_id', editGalleryForm.value.artist_id);
    if (editGalleryFile.value) {
        payload.append('photo', editGalleryFile.value);
    }

    try {
        const response = await fetch(`/api/gallery/${viewingPhoto.value.id}`, {
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
            throw new Error(validationError || 'Could not save changes.');
        }
        viewingPhoto.value = data;
        const idx = galleryPhotos.value.findIndex(p => p.id === data.id);
        if (idx !== -1) galleryPhotos.value[idx] = data;
        isEditingGalleryPhoto.value = false;
    } catch (error) {
        editGalleryError.value = error.message;
    } finally {
        editGallerySaving.value = false;
    }
};

const deletePhotoFromViewModal = () => {
    photoToDelete.value = viewingPhoto.value;
    closeGalleryViewModal();
};

// ── Published Issues ────────────────────────────────────────────────────────
// The archive lists gallery photos and issues too, so it needs the raw lists (no thumbnails rendered)
const loadArchiveExtras = async () => {
    const headers = {
        Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
        Accept: 'application/json',
    };
    try {
        const [galleryRes, issuesRes] = await Promise.all([
            fetch('/api/gallery', { headers }),
            fetch('/api/published-issues', { headers }),
        ]);
        if (galleryRes.ok) galleryPhotos.value = await galleryRes.json();
        if (issuesRes.ok) publishedIssues.value = await issuesRes.json();
    } catch {
        // Keep whatever is already loaded.
    }
};

const loadPublishedIssues = async () => {
    issuesLoading.value = true;
    try {
        const response = await fetch('/api/published-issues', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        if (response.ok) {
            publishedIssues.value = await response.json();
            publishedIssues.value.forEach(renderIssueThumbnail);
        }
    } catch {
        publishedIssues.value = [];
    } finally {
        issuesLoading.value = false;
    }
};

const openIssueUploadModal = () => {
    issueUploadForm.value = { title: '' };
    issueUploadFile.value = null;
    issueUploadFileName.value = '';
    issueUploadError.value = '';
    isIssueUploadModalOpen.value = true;
};

const closeIssueUploadModal = () => {
    isIssueUploadModalOpen.value = false;
};

const handleIssuePdfChange = (event) => {
    const file = event.target.files?.[0] || null;
    issueUploadFile.value = file;
    issueUploadFileName.value = file ? file.name : '';
};

const submitIssueUpload = async () => {
    issueUploadError.value = '';
    if (!issueUploadForm.value.title.trim()) {
        issueUploadError.value = 'Please provide a title.';
        return;
    }
    if (!issueUploadFile.value) {
        issueUploadError.value = 'Please choose a PDF to upload.';
        return;
    }

    issueUploadSaving.value = true;
    const payload = new FormData();
    payload.append('title', issueUploadForm.value.title);
    payload.append('pdf', issueUploadFile.value);

    try {
        const response = await fetch('/api/published-issues', {
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
            throw new Error(validationError || 'Could not upload issue.');
        }
        publishedIssues.value.unshift(data);
        renderIssueThumbnail(data);
        closeIssueUploadModal();
    } catch (error) {
        issueUploadError.value = error.message;
    } finally {
        issueUploadSaving.value = false;
    }
};

const confirmDeleteIssue = (issue) => {
    issueToDelete.value = issue;
    deleteIssueError.value = '';
};

const deleteIssue = async () => {
    if (!issueToDelete.value) return;
    deleteIssueSaving.value = true;
    deleteIssueError.value = '';
    try {
        const response = await fetch(`/api/published-issues/${issueToDelete.value.id}`, {
            method: 'DELETE',
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw new Error(data.message || 'Could not delete issue.');
        }
        publishedIssues.value = publishedIssues.value.filter(i => i.id !== issueToDelete.value.id);
        issueToDelete.value = null;
    } catch (error) {
        deleteIssueError.value = error.message;
    } finally {
        deleteIssueSaving.value = false;
    }
};

// PDF.js (CDN, free) — used to render a first-page thumbnail for the issue detail modal.
const PDFJS_VERSION_EIC = '3.11.174';
const loadPdfJsForThumbnail = () => {
    return new Promise((resolve, reject) => {
        if (window.pdfjsLib) {
            resolve(window.pdfjsLib);
            return;
        }
        const script = document.createElement('script');
        script.src = `https://cdnjs.cloudflare.com/ajax/libs/pdf.js/${PDFJS_VERSION_EIC}/pdf.min.js`;
        script.onload = () => {
            window.pdfjsLib.GlobalWorkerOptions.workerSrc = `https://cdnjs.cloudflare.com/ajax/libs/pdf.js/${PDFJS_VERSION_EIC}/pdf.worker.min.js`;
            resolve(window.pdfjsLib);
        };
        script.onerror = () => reject(new Error('Could not load PDF renderer.'));
        document.head.appendChild(script);
    });
};

const renderIssueThumbnail = async (issue) => {
    if (!issue?.id || !issue?.pdf_url || issueThumbnails.value[issue.id]) return;
    issueThumbnailLoading.value = true;
    try {
        const pdfjsLib = await loadPdfJsForThumbnail();
        const pdf = await pdfjsLib.getDocument(issue.pdf_url).promise;
        const page = await pdf.getPage(1);
        const viewport = page.getViewport({ scale: 1.3 });
        const canvas = document.createElement('canvas');
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        const ctx = canvas.getContext('2d');
        await page.render({ canvasContext: ctx, viewport }).promise;
        issueThumbnails.value = { ...issueThumbnails.value, [issue.id]: canvas.toDataURL('image/jpeg', 0.85) };
    } catch (e) {
        console.warn('Could not render issue thumbnail:', e);
    } finally {
        issueThumbnailLoading.value = false;
    }
};

const openIssueDetailModal = (issue) => {
    viewingIssue.value = issue;
    isEditingIssue.value = false;
    isIssueDetailModalOpen.value = true;
    renderIssueThumbnail(issue);
};

const closeIssueDetailModal = () => {
    isIssueDetailModalOpen.value = false;
    isEditingIssue.value = false;
};

const startEditIssue = () => {
    editIssueForm.value = { title: viewingIssue.value?.title || '' };
    editIssueFile.value = null;
    editIssueFileName.value = '';
    editIssueError.value = '';
    isEditingIssue.value = true;
};

const cancelEditIssue = () => {
    isEditingIssue.value = false;
};

const handleEditIssuePdfChange = (event) => {
    const file = event.target.files?.[0] || null;
    if (!file) return;
    editIssueFile.value = file;
    editIssueFileName.value = file.name;
};

const submitEditIssue = async () => {
    editIssueError.value = '';
    if (!editIssueForm.value.title.trim()) {
        editIssueError.value = 'Please provide a title.';
        return;
    }

    editIssueSaving.value = true;
    const payload = new FormData();
    payload.append('title', editIssueForm.value.title);
    if (editIssueFile.value) {
        payload.append('pdf', editIssueFile.value);
    }

    try {
        const response = await fetch(`/api/published-issues/${viewingIssue.value.id}`, {
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
            throw new Error(validationError || 'Could not save changes.');
        }
        viewingIssue.value = data;
        const idx = publishedIssues.value.findIndex(i => i.id === data.id);
        if (idx !== -1) publishedIssues.value[idx] = data;
        if (editIssueFile.value) {
            const thumbs = { ...issueThumbnails.value };
            delete thumbs[data.id];
            issueThumbnails.value = thumbs;
            renderIssueThumbnail(data);
        }
        isEditingIssue.value = false;
    } catch (error) {
        editIssueError.value = error.message;
    } finally {
        editIssueSaving.value = false;
    }
};

const deleteIssueFromDetailModal = () => {
    issueToDelete.value = viewingIssue.value;
    closeIssueDetailModal();
};

const viewIssueBooklet = () => {
    if (viewingIssue.value?.id) {
        window.open(`/booklet/${viewingIssue.value.id}`, '_blank');
    }
};

// An Editor-in-Chief can also be a section editor (a "News Editor"...). Then the articles submitted in their
// section reach them for the section editor's review first: they send it to a copyreader, and it comes back
// as Endorsed for the final approval. Titles that are only for videos (Head Broadcaster) don't apply to articles.
const eicEditorTitles = computed(() => [eicUser.value.secondary_role, eicUser.value.tertiary_role]
    .filter(title => SECONDARY_ROLES.section_editor.includes(title) && title !== 'Copy Editor' && !/Broadcaster/i.test(title)));
const isEicSectionEditor = computed(() => eicEditorTitles.value.length > 0);
// "News Editor" -> News. Editors without a section of their own (the Managing Editor...) review every section.
const eicEditorSections = computed(() => eicEditorTitles.value.map(deriveSectionFromRole).filter(Boolean));
const needsSectionReview = (article) => isEicSectionEditor.value
    && article.status === 'submitted'
    && article.type !== 'video'
    && (!eicEditorSections.value.length || eicEditorSections.value.includes(resolveArticleSection(article)));
const eicRowStatusLabel = (article) => needsSectionReview(article) ? 'For Section Review' : eicStatusLabel(article.status);

const isEicSectionReviewOpen = ref(false);
const eicSectionReviewTarget = ref(null);
const openEndorsementRow = (article) => {
    if (!needsSectionReview(article)) return openArticleDetails(article);

    eicSectionReviewTarget.value = {
        ...article,
        article_id: article.id,
        taskId: (article.tasks || []).find(task => task.type === 'writing')?.id || null,
        authorName: article.author?.name || 'Unknown Writer',
        section: resolveArticleSection(article) || 'News',
        raw: article,
    };
    isEicSectionReviewOpen.value = true;
};

const eicStatusLabel = (status) => (status || 'unknown').replace('_', ' ').replace(/\b\w/g, letter => letter.toUpperCase());
const eicStatusClass = (status) => ({
    submitted: 'status-for-approval',
    under_review: 'status-for-approval',
    endorsed: 'status-approved',
    approved: 'status-approved',
    rejected: 'status-returned',
}[status] || 'status-for-approval');
const eicEndorsementCandidates = computed(() => [
    ...eicArticles.value.filter(article => [
        'submitted', 'under_review', 'endorsed', 'approved',
    ].includes(article.status)),
    // A video only reaches the EIC once the Head / Assistant Head Broadcaster has sent it on
    ...eicVideos.value.filter(video => ['endorsed', 'approved'].includes(video.status)),
]);
const eicEndorsementFilterDefinitions = computed(() => [
    {
        key: 'status',
        options: [{ value: 'all', label: 'Status' }, ...[...new Set(eicEndorsementCandidates.value.map(article => article.status))].sort().map(status => ({ value: status, label: eicStatusLabel(status) }))],
    },
    {
        key: 'section',
        options: [
            { value: 'all', label: 'Section' },
            ...['News', 'Opinion', 'Editorial', 'Feature', 'Sci-Tech', 'DevCom', 'Sports', 'Literary', 'Video'].map(section => ({ value: section, label: section })),
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
        && (eicFilters.section === 'all' || resolveArticleSection(article) === eicFilters.section)
        && (eicFilters.priority === 'all' || (article.priority || 'moderate').toLowerCase() === eicFilters.priority)
        && matchesSearch(
            article.title,
            resolveArticleSection(article),
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
    ['News', 'Opinion', 'Editorial', 'Feature', 'Sci-Tech', 'DevCom', 'Sports', 'Literary', 'Video'].forEach(s => sections.add(s));
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

const isDirectPublishOpen = ref(false);
const isCreditedVideoPreviewOpen = ref(false);
const creditedVideoPreview = ref({});

// ── Videos tab ───────────────────────────────────────────────────────────────
const isVideoManageOpen = ref(false);
const manageVideo = ref(null);
const manageVideoStartInEdit = ref(false);
const videoCategoryFilter = ref('all');
const activeVideoCategoryFilter = ref(false);

const eicPublishedVideos = computed(() => eicVideos.value.filter(video => ['published', 'scheduled'].includes(video.status)));
const videoCategoryOptions = computed(() => [
    { value: 'all', label: 'All Sections' },
    ...VIDEO_CATEGORIES.map(category => ({ value: category, label: category })),
]);
const filteredEicVideos = computed(() => eicPublishedVideos.value
    .filter(video => (videoCategoryFilter.value === 'all' || video.video_category === videoCategoryFilter.value)
        && matchesSearch(video.title, video.video_category, video.author?.name))
    .sort((first, second) => {
        const firstDate = new Date(first.published_at || first.scheduled_at || first.created_at || 0).getTime();
        const secondDate = new Date(second.published_at || second.scheduled_at || second.created_at || 0).getTime();
        return secondDate - firstDate;
    }));

const videoThumbnailFor = (video) => youtubeThumbnail(video.video_url) || video.cover_image || '';
const toggleVideoCategoryFilter = () => { activeVideoCategoryFilter.value = !activeVideoCategoryFilter.value; };
const selectVideoCategory = (value) => {
    videoCategoryFilter.value = value;
    activeVideoCategoryFilter.value = false;
};

const openVideoManage = (video) => {
    manageVideo.value = video;
    manageVideoStartInEdit.value = false;
    isVideoManageOpen.value = true;
};
const closeVideoManage = () => {
    isVideoManageOpen.value = false;
    manageVideo.value = null;
    manageVideoStartInEdit.value = false;
};
const handleVideoUpdated = (updated) => {
    if (updated?.id) {
        const merged = { ...(eicVideos.value.find(v => v.id === updated.id) || {}), ...updated };
        eicVideos.value = eicVideos.value.map(v => v.id === updated.id ? merged : v);
        manageVideo.value = merged;
    }
    loadEicArticles();
};
const handleVideoDeleted = () => {
    loadEicArticles();
    loadEicOverview();
};

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
        'News Editor',
        'Opinion Editor',
        'Editorial Editor',
        'Feature Editor',
        'Sci-Tech Editor',
        'DevCom Editor',
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

// An Editor-in-Chief can also be a section editor, so they pick from the same editor titles
SECONDARY_ROLES.eic = SECONDARY_ROLES.section_editor;

const selectedUser = ref(null);
// You can't step down yourself: the Editor-in-Chief you promote changes your position or sets you inactive
const isEditingSelf = computed(() => !!selectedUser.value && selectedUser.value.id === eicUser.value.id);
const isDeleteHidden = (user) => user.id === eicUser.value.id;
const isEditUserModalOpen = ref(false);
const isDeleteUserModalOpen = ref(false);
const editUserSaving = ref(false);
const editUserFileInput = ref(null);
const editUserImage = ref(null);
const editUserImagePreview = ref('');
const editUserError = ref('');
const deleteUserSaving = ref(false);
const deleteUserError = ref('');
const editUserForm = ref({
    name: '',
    email: '',
    role: 'staff_writer',
    secondary_role: '',
    tertiary_role: '',
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
        tertiary_role: user.tertiary_role || '',
        is_active: user.is_active !== false,
        program: user.program || '',
        year_section: user.year_section || '',
        password: '',
    };
    editUserImage.value = null;
    editUserImagePreview.value = user.profile_picture_url || (user.profile_picture ? `/storage/${user.profile_picture}` : '');
    editUserError.value = '';
    isEditUserModalOpen.value = true;
};

const handleEditUserImage = (event) => {
    const image = event.target.files?.[0] || null;
    if (!image) return;
    editUserImage.value = image;
    editUserImagePreview.value = URL.createObjectURL(image);
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
    if (!payload.tertiary_role) payload.tertiary_role = null;

    try {
        const headers = { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json' };
        let request;
        if (editUserImage.value) {
            // Files need multipart, and PHP only parses multipart on POST, so spoof PUT.
            const body = new FormData();
            Object.entries(payload).forEach(([key, value]) => body.append(key, typeof value === 'boolean' ? (value ? 1 : 0) : (value ?? '')));
            body.append('profile_picture', editUserImage.value);
            body.append('_method', 'PUT');
            request = { method: 'POST', headers, body };
        } else {
            request = { method: 'PUT', headers: { ...headers, 'Content-Type': 'application/json' }, body: JSON.stringify(payload) };
        }
        const response = await fetch(`/api/users/${selectedUser.value.id}`, request);
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
    tertiary_role: '',
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
        tertiary_role: '',
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
    if (newContributorForm.value.tertiary_role) {
        payload.append('tertiary_role', newContributorForm.value.tertiary_role);
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
        ['eic', 'section_editor', 'staff_writer', 'staff_artist', 'staff_broadcaster'].includes(user.role)
    );
});

const eicContributorRoleOptions = [
    { value: 'all', label: 'All Roles', triggerLabel: 'Role' },
    { value: 'eic', label: 'Editor-in-Chief', triggerLabel: 'Editor-in-Chief' },
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
            || user.secondary_role === eicContributorSection.value
            || user.tertiary_role === eicContributorSection.value;

        const matchesStatus = eicContributorStatus.value === 'inactive'
            ? user.is_active === false
            : user.is_active !== false;

        const matchesSearchQuery = matchesSearch(
            user.name,
            user.secondary_role,
            user.tertiary_role,
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

    // Articles, plus everything else the newsroom posted that year (gallery photos,
    // published issues and videos), all listed in the same format
    const archiveItems = [
        ...eicArticles.value,
        ...galleryPhotos.value.map(photo => ({
            id: `gallery-${photo.id}`,
            archive_kind: 'gallery',
            title: photo.title,
            section: { name: 'Gallery' },
            author: photo.uploader,
            status: 'published',
            created_at: photo.created_at,
            source: photo,
        })),
        ...publishedIssues.value.map(issue => ({
            id: `issue-${issue.id}`,
            archive_kind: 'issue',
            title: issue.title,
            section: { name: 'Published Issue' },
            author: issue.uploader,
            status: 'published',
            created_at: issue.created_at,
            source: issue,
        })),
        ...eicPublishedVideos.value.map(video => ({ ...video, id: `video-${video.id}`, archive_kind: 'video', source: video })),
    ];

    archiveItems.forEach((article) => {
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
    hourly: { views: [], visitors: [] },
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
    if (activeTab.value === 'gallery' && !galleryPhotos.value.length) {
        loadGalleryPhotos();
    }
    // Thumbnails are cached per issue, so re-loading the list is cheap
    if (activeTab.value === 'published-issues') {
        loadPublishedIssues();
    }
    if (activeTab.value === 'archive' || activeTab.value === 'archive-year') {
        loadArchiveExtras();
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
    activeVideoCategoryFilter.value = false;
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
    const headers = {
        Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
        Accept: 'application/json',
    };
    try {
        const response = await fetch('/api/articles', { headers });
        if (response.ok) eicArticles.value = await response.json();
    } catch {
        eicArticles.value = [];
    }

    // Videos are a separate content type, so they load (and refresh) alongside the articles
    try {
        const response = await fetch('/api/articles?type=video', { headers });
        if (response.ok) eicVideos.value = await response.json();
    } catch {
        eicVideos.value = [];
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

const KNOWN_SECTIONS = ['News', 'Opinion', 'Editorial', 'Feature', 'Sci-Tech', 'DevCom', 'Sports', 'Literary', 'Video'];
const SECTION_ROLE_ALIASES = { 'Sci&Tech': 'Sci-Tech' };

// Writer/section-editor secondary roles are named after their section (e.g. "News Writer"),
// so when an article/task was never given a real section_id, fall back to that.
const deriveSectionFromRole = (secondaryRole) => {
    if (!secondaryRole || typeof secondaryRole !== 'string') return '';
    const stripped = secondaryRole.replace(/\s*(Writer|Editor|Artist|Presenter)$/i, '').trim();
    const mapped = SECTION_ROLE_ALIASES[stripped] || stripped;
    return KNOWN_SECTIONS.includes(mapped) ? mapped : '';
};

const resolveArticleSection = (article = {}) => {
    if (article.type === 'video') return 'Video';
    if (article.section?.name) return article.section.name;
    if (typeof article.section === 'string' && article.section) return article.section;
    const tasks = Array.isArray(article.tasks) ? article.tasks : [];
    const primaryTask = tasks.find(t => t.type === 'writing') || tasks[0] || null;
    if (primaryTask?.section?.name) return primaryTask.section.name;
    const fromNotes = parseNotesField(primaryTask?.notes, 'Section');
    if (fromNotes) return fromNotes;
    const fromRole = deriveSectionFromRole(article.author?.secondary_role);
    if (fromRole) return fromRole;
    return '';
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

        // Videos the EIC was credited on (e.g. as scriptwriter) also show up under My Articles
        const credited = await fetchCreditedVideos(eicUser.value.id);
        credited.forEach(video => {
            if (!articlesList.some(a => a.id === video.id)) articlesList.push(video);
        });

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

    // A credited video opens as a read-only preview
    if (item.raw?.type === 'video') {
        creditedVideoPreview.value = buildVideoPreviewData(item.raw);
        isCreditedVideoPreviewOpen.value = true;
        return;
    }

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

const extractFileName = (url) => {
    if (!url || typeof url !== 'string') return 'file';
    const parts = url.split('/');
    return parts[parts.length - 1] || 'file';
};

const buildAttachedFiles = (item) => {
    const files = [];
    if (item.cover_image) {
        files.push({ name: extractFileName(item.cover_image), type: 'image', url: item.cover_image });
    }
    if (Array.isArray(item.media_files)) {
        item.media_files.forEach((url) => {
            files.push({ name: extractFileName(url), type: 'image', url });
        });
    }
    return files;
};

const staffRoleLabel = (role) => (role === 'staff_broadcaster' ? 'Staff Broadcaster' : 'Staff Artist');

const buildSelectedArticle = (item = {}) => {
    const rawSection = resolveArticleSection(item) || 'Unassigned';
    const tasks = Array.isArray(item.tasks) ? item.tasks : [];
    const primaryTask = tasks.find(t => t.type === 'writing') || tasks[0] || null;
    const isVideo = item.type === 'video';
    const artistTask = isVideo ? null : (tasks.find(t => ['illustration', 'photography', 'layout'].includes(t.type)) || null);
    const artist = artistTask?.assignee || null;

    // The video crew (videographer / video editor) shown in the review modal's Assigned Team
    const crewRoles = { videography: 'Videographer', video_editing: 'Video Editor' };
    const creditRoles = { reporter: 'Reporter', scriptwriter: 'Scriptwriter', videographer: 'Videographer', video_editor: 'Video Editor' };
    const avatarFor = (person) => person.profile_picture_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(person.name)}&background=ffd5dc&color=9f1239&size=100`;
    const credits = Array.isArray(item.credits) ? item.credits.filter(c => c.user) : [];
    // The Head's final credits win; before they exist, fall back to the crew assigned on the tasks
    const videoCrew = !isVideo ? [] : (credits.length
        ? credits.map(c => ({ role: creditRoles[c.role] || c.role, name: c.user.name, email: c.user.email || '', avatar: avatarFor(c.user) }))
        : tasks.filter(t => crewRoles[t.type] && t.assignee).map(t => ({
            role: crewRoles[t.type],
            name: t.assignee.name,
            email: t.assignee.email || '',
            avatar: avatarFor(t.assignee),
        })));

    return {
        ...item,
        video_crew: videoCrew,
        title: item.title || (isVideo ? 'Untitled Video' : 'Untitled Article'),
        section: rawSection,
        writer: item.author?.name || item.writer || 'Staff Writer',
        dateEndorsed: formatDate(item.endorsed_at || item.submitted_at || item.created_at),
        deadline: formatDate(primaryTask?.deadline || item.endorsed_at || item.created_at),
        status: eicStatusLabel(item.status),
        raw_status: item.status,
        wordCount: item.word_count || (item.content ? item.content.trim().split(/\s+/).filter(Boolean).length : '0'),
        artist_name: artist?.name || '',
        artist_email: artist?.email || '',
        artist_role: artist?.secondary_role || (artist ? staffRoleLabel(artist.role) : ''),
        artist_avatar: artist?.profile_picture_url || '',
        attached_files: buildAttachedFiles(item),
    };
};

const openArticleDetails = (item = {}) => {
    selectedArticle.value = buildSelectedArticle(item);
    isArticleDetailsOpen.value = true;
};

const openArticlePreview = (item = {}) => {
    selectedArticle.value = buildSelectedArticle(item);
    isArticlePreviewOpen.value = true;
};

// An archive row can be an article, a gallery photo, a published issue or a video
const openArchiveItem = (item = {}) => {
    if (item.archive_kind === 'gallery') return openGalleryViewModal(item.source);
    if (item.archive_kind === 'issue') return openIssueDetailModal(item.source);
    if (item.archive_kind === 'video') return openVideoManage(item.source);
    return openArchivePreview(item);
};

const openArchivePreview = (item = {}) => {
    selectedArticle.value = buildSelectedArticle(item);
    isArchivePreviewOpen.value = true;
};

const handleRequestPublishPreview =(articleData) => {
    isArticleDetailsOpen.value = false;
    selectedArticle.value = articleData || selectedArticle.value;
    isArticlePreviewOpen.value = true;
};

const handleArticleUpdated = (updated) => {
    if (updated && selectedArticle.value) {
        selectedArticle.value = {
            ...selectedArticle.value,
            status: updated.status,
            raw_status: updated.status,
            scheduled_at: updated.scheduled_at,
            published_at: updated.published_at,
        };
    }
    loadEicOverview();
    loadEicArticles();
};

const handleArticleDeleted = () => {
    isArticlePreviewOpen.value = false;
    isArchivePreviewOpen.value = false;
    loadEicOverview();
    loadEicArticles();
};

const handleEditArticleFromPreview = (articleData = {}) => {
    // Videos have their own editor (headline, details, YouTube link, section)
    if (articleData.type === 'video') {
        isArticlePreviewOpen.value = false;
        isArchivePreviewOpen.value = false;
        manageVideo.value = eicVideos.value.find(v => v.id === articleData.id) || articleData;
        manageVideoStartInEdit.value = true;
        isVideoManageOpen.value = true;
        return;
    }

    const tasks = Array.isArray(articleData.tasks) ? articleData.tasks : [];
    const writingTask = tasks.find(t => t.type === 'writing') || null;

    isArticlePreviewOpen.value = false;
    isArchivePreviewOpen.value = false;
    isEicEditingArticle.value = true;
    selectedTask.value = {
        id: writingTask?.id || null,
        title: articleData.title,
        section: articleData.section,
        article_id: articleData.id,
        deadline: writingTask?.deadline || null,
        status: writingTask?.status || 'completed',
        notes: writingTask?.notes || '',
        mediaArtist: articleData.artist_name || '',
        writer: {
            name: articleData.author?.name || articleData.writer || 'Staff Writer',
            avatar: articleData.author?.profile_picture_url || '',
        },
        raw: writingTask || { article_id: articleData.id },
    };
    isWorkspaceModalOpen.value = true;
};

const handleOpenWorkspace = (taskData) => {
    isAssignedTaskModalOpen.value = false;
    isArticleDetailsOpen.value = false;
    isEicEditingArticle.value = false;
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

/* Gallery Tab */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 18px;
}

.gallery-card {
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    background: #ffffff;
}

.gallery-card-img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 4 / 3;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.gallery-delete-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: none;
    background: rgba(15, 23, 42, 0.6);
    color: #ffffff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.gallery-delete-btn:hover {
    background: #dc2626;
}

.gallery-card-body {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 12px 14px;
}

.gallery-card-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
}

.gallery-card-meta {
    font-size: 11.5px;
    color: #94a3b8;
}

/* Videos Tab (extends the gallery card) */
.video-card-thumb {
    aspect-ratio: 16 / 9;
    background: #0f172a;
}

.video-card-play {
    position: absolute;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(15, 23, 42, 0.65);
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.video-card-badge {
    position: absolute;
    left: 10px;
    bottom: 10px;
    padding: 3px 10px;
    border-radius: 12px;
    background: #dbeafe;
    color: #1e40af;
    font-size: 11px;
    font-weight: 700;
}

.video-card-badge.scheduled {
    background: #fef3c7;
    color: #92400e;
}

.gallery-view-modal {
    max-width: 560px;
}

.issue-thumb-preview {
    width: 100%;
    max-height: 360px;
    min-height: 180px;
    background: #f1f5f9;
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.issue-thumb-img {
    width: 100%;
    max-height: 360px;
    object-fit: contain;
    display: block;
}

.issue-thumb-placeholder {
    font-size: 13px;
    color: #94a3b8;
    font-weight: 600;
    padding: 40px 0;
}

.gallery-view-img {
    width: 100%;
    max-height: 420px;
    object-fit: contain;
    background: #f1f5f9;
    border-radius: 14px;
    display: block;
}

/* Published Issues Tab */
.issue-row-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #eff6ff;
    color: #1d6bf3;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
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
