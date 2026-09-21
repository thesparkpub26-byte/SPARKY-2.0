<template>
<div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="brand">
                <img src="/assets/Spark_Logo.png" alt="The Spark Logo">
            </div>

            <nav class="nav-menu">
                <a href="#" class="nav-item" :class="{ active: activeTab === 'overview' }" @click.prevent="activeTab = 'overview'"><div class="nav-item-left">Overview</div></a>
                <a href="#" class="nav-item" :class="{ active: activeTab === 'endorsements' }" @click.prevent="activeTab = 'endorsements'"><div class="nav-item-left">Endorsements</div></a>
                <a href="#" class="nav-item" :class="{ active: activeTab === 'pressWorks' }" @click.prevent="activeTab = 'pressWorks'"><div class="nav-item-left">Press Works</div></a>
            </nav>

            <nav class="nav-menu">

                <!-- Published Articles Nav Item -->
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

                <!-- Contributors Nav Item -->
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

                <!-- Archive Nav Item -->
                <a href="#" class="nav-item" :class="{ active: activeTab === 'archive' }" @click.prevent="activeTab = 'archive'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="21 8 21 21 3 21 3 8"/>
                            <rect x="1" y="3" width="22" height="5"/>
                            <line x1="10" y1="12" x2="14" y2="12"/>
                        </svg>
                        Archive
                    </div>
                </a>

                <!-- Analytics Nav Item -->
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
                    <input type="text" placeholder="Search">
                </div>
                <div class="top-header-right" style="display: flex; align-items: center; gap: 16px;">
                    <button class="assign-task-btn" @click.prevent="isAssignTaskModalOpen = true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Assign Task</span>
                    </button>
                    <NotificationsPopover />
                </div>
            </header>

            <!-- Dynamic Content Container -->
            <div class="content-container fade-in">

                <!-- OVERVIEW TAB -->
                <div v-show="activeTab === 'overview'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
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
                                <tr v-for="activity in eicOverview.activities" :key="activity.id">
                                    <td style="padding-left: 28px; font-weight: 600;">{{ activity.action }}<span v-if="activity.subject">: {{ activity.subject }}</span></td>
                                    <td>{{ activity.user }}</td>
                                    <td><span class="role-pill role-pill-writer">{{ formatRole(activity.role) }}</span></td>
                                    <td style="padding-right: 28px; text-align: right; color: #64748b;">{{ formatDate(activity.created_at) }}</td>
                                </tr>
                                <tr v-if="!eicOverview.activities.length"><td colspan="4" class="empty-activity">No recent activities found.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ENDORSEMENTS TAB -->
                <div v-show="activeTab === 'endorsements'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
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
                <div v-show="activeTab === 'pressWorks'" style="display: flex; flex-direction: column; gap: 12px; flex-shrink: 0;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h1 class="page-title">Press Works</h1>
                        <button class="assign-task-btn" @click.prevent="isAssignTaskModalOpen = true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            <span>Assign Task</span>
                        </button>
                    </div>
                    <div class="card" style="padding: 16px 24px; background: #ffffff; border-radius: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
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
                                    <tr @click="openMonitoringSheet" style="cursor: pointer;" class="clickable-row"><td style="padding-left: 24px;">Issue 1</td><td><span class="pub-badge pub-newsletter">Newsletter</span></td><td>2025-2026</td><td>18</td><td>May 15, 2025</td><td style="padding-right: 24px;"><button class="action-menu-btn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg></button></td></tr>
                                    <tr @click="openMonitoringSheet" style="cursor: pointer;" class="clickable-row"><td style="padding-left: 24px;">Issue 1</td><td><span class="pub-badge pub-tabloid">Tabloid</span></td><td>2025-2026</td><td>24</td><td>May 15, 2025</td><td style="padding-right: 24px;"><button class="action-menu-btn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg></button></td></tr>
                                    <tr @click="openMonitoringSheet" style="cursor: pointer;" class="clickable-row"><td style="padding-left: 24px;">Issue 1</td><td><span class="pub-badge pub-magazine">Magazine</span></td><td>2025-2026</td><td>14</td><td>May 15, 2025</td><td style="padding-right: 24px;"><button class="action-menu-btn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg></button></td></tr>
                                    <tr @click="openMonitoringSheet" style="cursor: pointer;" class="clickable-row"><td style="padding-left: 24px; border-bottom: none;">Issue 1</td><td style="border-bottom: none;"><span class="pub-badge pub-litfolio">Litfolio</span></td><td style="border-bottom: none;">2025-2026</td><td style="border-bottom: none;">12</td><td style="border-bottom: none;">May 15, 2025</td><td style="padding-right: 24px; border-bottom: none;"><button class="action-menu-btn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg></button></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="pagination-pill">
                        <button class="page-nav" style="border:none;background:none;display:flex;align-items:center;gap:4px;color:#64748b;font-weight:500;cursor:pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Previous</button>
                        <div class="page-numbers" style="display:flex;gap:8px;">
                            <button class="page-num active" style="background:#2563eb;color:white;border:none;border-radius:50%;width:28px;height:28px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;">1</button>
                            <button class="page-num" style="background:none;border:none;color:#64748b;font-weight:500;cursor:pointer;width:28px;height:28px;display:flex;align-items:center;justify-content:center;">2</button>
                            <button class="page-num" style="background:none;border:none;color:#64748b;font-weight:500;cursor:pointer;width:28px;height:28px;display:flex;align-items:center;justify-content:center;">3</button>
                        </div>
                        <button class="page-nav" style="border:none;background:none;display:flex;align-items:center;gap:4px;color:#64748b;font-weight:500;cursor:pointer;">Next <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></button>
                        <div class="showing-text" style="color:#64748b;font-size:13px;margin-left:16px;">Showing <b>4</b> Press Works</div>
                    </div>
                </div>

                <!-- PUBLISHED ARTICLES TAB -->
                <div v-show="activeTab === 'articles'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Published Articles</h1>
                        <div class="filter-pills-group">
                            <button class="filter-dropdown-btn">Section <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button>
                            <button class="filter-dropdown-btn">Coverage <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button>
                            <button class="filter-dropdown-btn">Published Date <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button>
                        </div>
                    </div>
                    <div class="articles-card">
                        <table class="articles-table eic-published-articles-table">
                            <thead><tr><th style="padding-left: 28px;">Title</th><th>Section</th><th style="padding-right: 28px; text-align: right;">Published Date</th></tr></thead>
                            <tbody>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Foundation Day 2026 Highlights</td><td><span class="section-badge">News</span></td><td style="color: #64748b;">Foundation Day 2026</td><td style="padding-right: 28px; text-align: right; color: #64748b;">Apr 14, 2026</td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">College Fair Attracts Hundreds</td><td><span class="section-badge">Feature</span></td><td style="color: #64748b;">AY 2025 - 2026 Issue 1</td><td style="padding-right: 28px; text-align: right; color: #64748b;">Apr 12, 2026</td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">New Laboratory Campus Building Opens</td><td><span class="section-badge">News</span></td><td style="color: #64748b;">Foundation Day 2026</td><td style="padding-right: 28px; text-align: right; color: #64748b;">Apr 12, 2026</td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">The Rise of Campus Creatives</td><td><span class="section-badge">Feature</span></td><td style="color: #64748b;">AY 2025 - 2026 Issue 2</td><td style="padding-right: 28px; text-align: right; color: #64748b;">Apr 10, 2026</td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">CSPC Joins Regional Quiz Bee</td><td><span class="section-badge">News</span></td><td style="color: #64748b;">Student Achievements</td><td style="padding-right: 28px; text-align: right; color: #64748b;">Apr 9, 2026</td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Student Leaders Hold Town Hall Meeting</td><td><span class="section-badge">Opinion</span></td><td style="color: #64748b;">Student Governance</td><td style="padding-right: 28px; text-align: right; color: #64748b;">Apr 9, 2026</td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Wellness Campaign Launch</td><td><span class="section-badge">Feature</span></td><td style="color: #64748b;">Student Wellness Program</td><td style="padding-right: 28px; text-align: right; color: #64748b;">Apr 9, 2026</td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">College Fair Highlights</td><td><span class="section-badge">Feature</span></td><td style="color: #64748b;">AY 2025 - 2026 Issue 1</td><td style="padding-right: 28px; text-align: right; color: #64748b;">Apr 9, 2026</td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">New Scholarship Guidelines Released</td><td><span class="section-badge">News</span></td><td style="color: #64748b;">Enrollment Period 2026</td><td style="padding-right: 28px; text-align: right; color: #64748b;">Apr 8, 2026</td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Campus Wi-Fi Expansion Project</td><td><span class="section-badge">Sci &amp; Tech</span></td><td style="color: #64748b;">Tech &amp; Innovation Series</td><td style="padding-right: 28px; text-align: right; color: #64748b;">Apr 8, 2026</td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Student Government Election Begins</td><td><span class="section-badge">News</span></td><td style="color: #64748b;">Election Coverage 2026</td><td style="padding-right: 28px; text-align: right; color: #64748b;">Apr 6, 2026</td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Library Extends Week Hours</td><td><span class="section-badge">News</span></td><td style="color: #64748b;">Campus Services Update</td><td style="padding-right: 28px; text-align: right; color: #64748b;">Apr 5, 2026</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container">
                        <div class="pagination-pill">
                            <button class="page-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Previous</button>
                            <a href="#" class="page-number active">1</a><a href="#" class="page-number">2</a><a href="#" class="page-number">3</a><a href="#" class="page-number">4</a><a href="#" class="page-number">5</a>
                            <span class="page-dots">&bull;&bull;&bull;</span>
                            <button class="page-btn">Next <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></button>
                            <div class="page-results-count">Showing <strong>12</strong> results</div>
                        </div>
                    </div>
                </div>

                <!-- CONTRIBUTORS TAB -->
                <div v-show="activeTab === 'contributors'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Contributors</h1>
                        <div class="filter-pills-group"><button class="filter-dropdown-btn">Status <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button></div>
                    </div>
                    <div class="articles-card">
                        <table class="articles-table">
                            <thead><tr><th style="padding-left: 28px;">Name</th><th>Role</th><th>Email</th><th style="text-align: center;">Tasks</th><th style="padding-right: 28px; text-align: center;">Status</th></tr></thead>
                            <tbody>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Gabrielle Loquias</td><td><span class="role-pill">News Writer</span></td><td style="color: #64748b;">gabloquias@my.cspc.edu.ph</td><td style="text-align: center;"><span class="tasks-count-pill">3</span></td><td style="padding-right: 28px; text-align: center;"><span class="status-pill status-active">Active</span></td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Vien Lacoste</td><td><span class="role-pill">News Writer</span></td><td style="color: #64748b;">vienlacoste@my.cspc.edu.ph</td><td style="text-align: center;"><span class="tasks-count-pill">2</span></td><td style="padding-right: 28px; text-align: center;"><span class="status-pill status-active">Active</span></td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Karl Lumactod</td><td><span class="role-pill">Sports Writer</span></td><td style="color: #64748b;">kalumactod@my.cspc.edu.ph</td><td style="text-align: center;"><span class="tasks-count-pill">2</span></td><td style="padding-right: 28px; text-align: center;"><span class="status-pill status-active">Active</span></td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Samantha Ciscon</td><td><span class="role-pill">News Writer</span></td><td style="color: #64748b;">samciscon@my.cspc.edu.ph</td><td style="text-align: center;"><span class="tasks-count-pill">2</span></td><td style="padding-right: 28px; text-align: center;"><span class="status-pill status-active">Active</span></td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Johan Abinal</td><td><span class="role-pill">News Editor</span></td><td style="color: #64748b;">johabinal@my.cspc.edu.ph</td><td style="text-align: center;"><span class="tasks-count-pill">1</span></td><td style="padding-right: 28px; text-align: center;"><span class="status-pill status-available">Available</span></td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Jell Reginaldo</td><td><span class="role-pill">Copy Editor</span></td><td style="color: #64748b;">jellreginaldo@my.cspc.edu.ph</td><td style="text-align: center;"><span class="tasks-count-pill">2</span></td><td style="padding-right: 28px; text-align: center;"><span class="status-pill status-active">Active</span></td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Johnrey Frongoso</td><td><span class="role-pill">Photojournalist</span></td><td style="color: #64748b;">jofrongoso@my.cspc.edu.ph</td><td style="text-align: center;"><span class="tasks-count-pill">2</span></td><td style="padding-right: 28px; text-align: center;"><span class="status-pill status-active">Active</span></td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Nicole Orcine</td><td><span class="role-pill">Layout Artist</span></td><td style="color: #64748b;">nicorcine@my.cspc.edu.ph</td><td style="text-align: center;"><span class="tasks-count-pill">1</span></td><td style="padding-right: 28px; text-align: center;"><span class="status-pill status-available">Available</span></td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Angelica Belano</td><td><span class="role-pill">Graphic Artist</span></td><td style="color: #64748b;">angelbelano@my.cspc.edu.ph</td><td style="text-align: center;"><span class="tasks-count-pill">1</span></td><td style="padding-right: 28px; text-align: center;"><span class="status-pill status-available">Available</span></td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Rica Subang</td><td><span class="role-pill">Feature Writer</span></td><td style="color: #64748b;">rsubang@my.cspc.edu.ph</td><td style="text-align: center;"><span class="tasks-count-pill">2</span></td><td style="padding-right: 28px; text-align: center;"><span class="status-pill status-available">Available</span></td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Denise L.</td><td><span class="role-pill">Sports Writer</span></td><td style="color: #64748b;">denise@my.cspc.edu.ph</td><td style="text-align: center;"><span class="tasks-count-pill">4</span></td><td style="padding-right: 28px; text-align: center;"><span class="status-pill status-active">Active</span></td></tr>
                                <tr><td style="padding-left: 28px; font-weight: 600;">Gerald C.</td><td><span class="role-pill">Literary Writer</span></td><td style="color: #64748b;">geraldc@my.cspc.edu.ph</td><td style="text-align: center;"><span class="tasks-count-pill">1</span></td><td style="padding-right: 28px; text-align: center;"><span class="status-pill status-available">Available</span></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container">
                        <div class="pagination-pill">
                            <button class="page-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Previous</button>
                            <a href="#" class="page-number active">1</a><a href="#" class="page-number">2</a><a href="#" class="page-number">3</a><a href="#" class="page-number">4</a><a href="#" class="page-number">5</a>
                            <span class="page-dots">&bull;&bull;&bull;</span>
                            <button class="page-btn">Next <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></button>
                            <div class="page-results-count">Showing <strong>12</strong> of <strong>61</strong> results</div>
                        </div>
                    </div>
                </div>

                <!-- ARCHIVE TAB -->
                <div v-show="activeTab === 'archive'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Archive</h1>
                        <div class="filter-pills-group"><button class="filter-dropdown-btn">Sort By <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button></div>
                    </div>
                    <div class="archive-card-container">
                        <svg width="0" height="0" style="position:absolute">
                            <defs>
                                <linearGradient id="eicFolderGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" stop-color="#90bbf8" />
                                    <stop offset="100%" stop-color="#719bf0" />
                                </linearGradient>
                                <g id="eicFolderIcon">
                                    <path d="M 20 20 C 20 5, 30 0, 40 0 L 160 0 C 170 0, 180 5, 180 20 L 180 100 L 20 100 Z" fill="#ffffff" />
                                    <path d="M 0 50 C 0 30, 10 20, 30 20 L 80 20 C 90 20, 95 35, 100 40 C 105 45, 110 50, 120 50 L 170 50 C 190 50, 200 60, 200 80 L 200 200 L 0 200 Z" fill="url(#eicFolderGrad)" />
                                </g>
                            </defs>
                        </svg>
                        <div class="archive-grid">
                            <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#eicFolderIcon" /></svg><div class="archive-card-content"><h4>AY 2025 - 2026</h4><p>218 articles</p></div></div>
                            <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#eicFolderIcon" /></svg><div class="archive-card-content"><h4>AY 2024 - 2025</h4><p>206 articles</p></div></div>
                            <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#eicFolderIcon" /></svg><div class="archive-card-content"><h4>AY 2023 - 2024</h4><p>194 articles</p></div></div>
                            <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#eicFolderIcon" /></svg><div class="archive-card-content"><h4>AY 2022 - 2023</h4><p>181 articles</p></div></div>
                            <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#eicFolderIcon" /></svg><div class="archive-card-content"><h4>AY 2021 - 2022</h4><p>169 articles</p></div></div>
                            <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#eicFolderIcon" /></svg><div class="archive-card-content"><h4>AY 2020 - 2021</h4><p>158 articles</p></div></div>
                            <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#eicFolderIcon" /></svg><div class="archive-card-content"><h4>AY 2019 - 2020</h4><p>147 articles</p></div></div>
                            <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#eicFolderIcon" /></svg><div class="archive-card-content"><h4>AY 2018 - 2019</h4><p>136 articles</p></div></div>
                            <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#eicFolderIcon" /></svg><div class="archive-card-content"><h4>AY 2017 - 2018</h4><p>125 articles</p></div></div>
                            <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#eicFolderIcon" /></svg><div class="archive-card-content"><h4>AY 2016 - 2017</h4><p>114 articles</p></div></div>
                            <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#eicFolderIcon" /></svg><div class="archive-card-content"><h4>AY 2015 - 2016</h4><p>103 articles</p></div></div>
                            <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#eicFolderIcon" /></svg><div class="archive-card-content"><h4>AY 2014 - 2015</h4><p>92 articles</p></div></div>
                        </div>
                    </div>
                </div>

                <!-- ANALYTICS TAB -->
                <div v-show="activeTab === 'analytics'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">Analytics</h1>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <button class="new-press-work-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg> New Press Work</button>
                            <div class="filter-pills-group">
                                <button class="filter-dropdown-btn">AY 2025 - 2026 <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button>
                                <button class="filter-dropdown-btn">Last 30 days <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button>
                            </div>
                        </div>
                    </div>
                    <div class="analytics-grid-top">
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <div class="analytics-card-card">
                                <div>
                                    <div class="analytics-card-title">Section Performance</div>
                                    <table class="section-perf-table">
                                        <thead><tr><th style="text-align: left;">Sections</th><th style="text-align: right;">Published Articles</th></tr></thead>
                                        <tbody>
                                            <tr><td><span class="section-name-blue">News</span></td><td style="text-align: right; font-weight: 700; color: #0f172a;">42</td></tr>
                                            <tr><td><span class="section-name-blue">Feature</span></td><td style="text-align: right; font-weight: 700; color: #0f172a;">31</td></tr>
                                            <tr><td><span class="section-name-blue">Sports</span></td><td style="text-align: right; font-weight: 700; color: #0f172a;">24</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="#" class="view-all-link">View All <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></a>
                            </div>
                            <div class="analytics-card-card">
                                <div class="analytics-card-title">Workflow Efficiency</div>
                                <div class="workflow-boxes-row">
                                    <div class="workflow-stat-box"><div class="workflow-stat-lbl">Avg. Time to Publish</div><div class="workflow-stat-val">2.4d</div></div>
                                    <div class="workflow-stat-box"><div class="workflow-stat-lbl">Pending Approval</div><div class="workflow-stat-val">12</div></div>
                                    <div class="workflow-stat-box"><div class="workflow-stat-lbl">Revision Rate</div><div class="workflow-stat-val">28%</div></div>
                                </div>
                            </div>
                        </div>
                        <div class="analytics-card-card">
                            <div>
                                <div class="analytics-card-title">Most Viewed Articles</div>
                                <div class="most-viewed-item"><div><div class="most-viewed-title">CSPC Launches New Digital Learning Hub</div><div class="most-viewed-meta"><span class="section-link">News</span><span>Jhea Nicole N. Comandante</span></div></div><span class="metric-pill-blue">2,031 <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m7 17 9.2-9.2M17 17V7H7"/></svg></span></div>
                                <div class="most-viewed-item"><div><div class="most-viewed-title">Blue Stallions Dominate Regional Meet...</div><div class="most-viewed-meta"><span class="section-link">Sports</span><span>Hanna Grace A. Clevillas</span></div></div><span class="metric-pill-blue">1,245 <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></span></div>
                                <div class="most-viewed-item"><div><div class="most-viewed-title">The Rise of Campus Creatives</div><div class="most-viewed-meta"><span class="section-link">Feature</span><span>Gabrielle M. Loquias</span></div></div><span class="metric-pill-blue">1,102 <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></span></div>
                            </div>
                            <a href="#" class="view-all-link">View All <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></a>
                        </div>
                    </div>
                    <div class="analytics-grid-bottom">
                        <div class="analytics-card-card">
                            <div>
                                <div class="analytics-card-title">Recent Publications</div>
                                <table class="articles-table">
                                    <thead><tr><th style="padding-left: 20px;">Title</th><th>Category</th><th>Published Date</th><th style="padding-right: 20px; text-align: right;">Views</th></tr></thead>
                                    <tbody>
                                        <tr><td style="padding-left: 20px; font-weight: 600;">Blue Stallions Dominate Regional Me...</td><td><span class="section-badge">Sports</span></td><td style="color: #64748b;">Apr 4, 2026</td><td style="padding-right: 20px; text-align: right; font-weight: 700; color: #1e293b;">1,245</td></tr>
                                        <tr><td style="padding-left: 20px; font-weight: 600;">CSPC Launches New Digital Learning...</td><td><span class="section-badge">News</span></td><td style="color: #64748b;">Apr 4, 2026</td><td style="padding-right: 20px; text-align: right; font-weight: 700; color: #16a34a;">2,031</td></tr>
                                        <tr><td style="padding-left: 20px; font-weight: 600;">Sa Likod ng Tinta</td><td><span class="section-badge">Literary</span></td><td style="color: #64748b;">Apr 4, 2026</td><td style="padding-right: 20px; text-align: right; font-weight: 700; color: #1e293b;">876</td></tr>
                                        <tr><td style="padding-left: 20px; font-weight: 600;">The Rise of Campus Creatives</td><td><span class="section-badge">Feature</span></td><td style="color: #64748b;">Apr 4, 2026</td><td style="padding-right: 20px; text-align: right; font-weight: 700; color: #1e293b;">1,102</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <a href="#" class="view-all-link">View All <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></a>
                        </div>
                        <div class="analytics-card-card">
                            <div>
                                <div class="analytics-card-title">Approval Efficiency</div>
                                <div class="approval-eff-container">
                                    <div class="approval-eff-item"><span>Pending Approval</span><span class="approval-count-circle">12</span></div>
                                    <div class="approval-eff-item"><span>Avg. Review Time</span><span class="approval-count-circle">2.4d</span></div>
                                    <div class="approval-eff-item"><span>Final Reviews Completed</span><span class="approval-count-circle">34</span></div>
                                    <div class="approval-eff-item"><span>Returned for Revisions</span><span class="approval-count-circle">9</span></div>
                                    <div class="approval-eff-item"><span>Approved Directly</span><span class="approval-count-circle">21</span></div>
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
        @view-assignments="activeTab = 'pressWorks'" 
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
        @close="isWorkspaceModalOpen = false" 
    />

    <!-- Article Details Modal (EIC Endorsements Review) -->
    <ArticleDetailsModal
        :is-open="isArticleDetailsOpen"
        :article-data="selectedArticle"
        @close="isArticleDetailsOpen = false"
        @view-full-article="handleOpenWorkspace"
    />
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import AssignTaskModal from '../../components/AssignTaskModal.vue';
import AssignedTaskModal from '../../components/AssignedTaskModal.vue';
import AssignmentWorkspaceModal from '../../components/AssignmentWorkspaceModal.vue';
import ArticleDetailsModal from '../../components/ArticleDetailsModal.vue';
import NotificationsPopover from '../../components/NotificationsPopover.vue';
import { signOut as performSignOut } from '../../utils/auth';

const router = useRouter();
const activeTab = ref('overview');
const eicUser = ref(JSON.parse(localStorage.getItem('sparky_user') || '{}'));
const eicOverview = ref({
    summary: { articles: 0, endorsements: 0, ready_to_publish: 0, published: 0 },
    activities: [],
    updated_at: null,
});
const eicArticles = ref([]);
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

const selectedTask = ref({});
const selectedArticle = ref({});

const formatCount = (value) => Number(value || 0).toLocaleString();
const formatRole = (role) => {
    if (role === 'eic') return 'Editor in Chief';
    if (role === 'system') return 'System';
    return (role || '').split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
};
const formatDate = (date) => date
    ? new Date(date).toLocaleString([], { dateStyle: 'medium', timeStyle: 'short' })
    : '—';
const updatedLabel = computed(() => eicOverview.value.updated_at
    ? `Updated ${new Date(eicOverview.value.updated_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })}`
    : 'Loading...');

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
            ...['News', 'Opinion', 'Editorial', 'Feature', 'DevCom', 'Sports', 'Literary'].map(section => ({ value: section, label: section })),
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

const loadEicPressWorks = async () => {
    try {
        const response = await fetch('/api/press-works', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        if (response.ok) {
            const data = await response.json();
            eicAcademicYears.value = data.academic_years || [];
            if (eicAcademicYears.value.length) eicExpandedYears.value[eicAcademicYears.value[0].academic_year] = true;
        }
    } catch {
        eicAcademicYears.value = [];
    }
};

const toggleEicYear = (academicYear) => {
    eicExpandedYears.value[academicYear] = !eicExpandedYears.value[academicYear];
};

onMounted(() => {
    loadEicOverview();
    loadEicArticles();
    loadEicPressWorks();
});

watch(eicFilters, () => {
    eicEndorsementPage.value = 1;
}, { deep: true });
watch(eicEndorsementPageCount, (pageCount) => {
    if (eicEndorsementPage.value > pageCount) eicEndorsementPage.value = pageCount;
});

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
    selectedArticle.value = {
        title: item.title || 'Campus Wi-Fi Expansion Project',
        section: item.section || 'News',
        coverage: item.coverage || 'Tech & Innovation Series',
        writer: item.writer || 'Gabrielle M. Loquias',
        dateEndorsed: item.dateEndorsed || 'Apr 14 • 10:31 AM',
        deadline: item.deadline || 'Apr 16 • 5:00 PM',
        status: item.status || 'For Approval',
        wordCount: item.wordCount || '95',
        remarks: item.remarks || 'This article has been reviewed and revised on the initial feedback. It is now endorsed for your final review and approval.'
    };
    isArticleDetailsOpen.value = true;
};

const handleOpenWorkspace = (taskData) => {
    isAssignedTaskModalOpen.value = false;
    isArticleDetailsOpen.value = false;
    selectedTask.value = taskData || selectedTask.value;
    isWorkspaceModalOpen.value = true;
};

const openMonitoringSheet = () => {
    window.open('/monitoring-sheet', '_blank');
};
</script>
