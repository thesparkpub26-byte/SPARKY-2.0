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
                <!-- Overview Nav Item -->
                <a href="#" class="nav-item" @click.prevent="activeTab = 'overview'" :class="{ active: activeTab === 'overview' }">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        Overview
                    </div>
                </a>
                
                <!-- Content Dropdown -->
                <div>
                    <a href="#" class="nav-item has-dropdown" @click.prevent="openDropdown = openDropdown === 'content' ? null : 'content'" :class="{ active: activeTab === 'articles' || activeTab === 'press-works' || activeTab === 'gallery' }">
                        <div class="nav-item-left">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            Content
                        </div>
                        <svg class="chevron" :style="{ transform: openDropdown === 'content' ? 'rotate(180deg)' : 'rotate(0deg)', transition: 'transform 0.2s' }" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </a>
                    <div class="sub-menu" v-show="openDropdown === 'content'" :class="{ open: openDropdown === 'content' }">
                        <a href="#" class="sub-item" @click.prevent="activeTab = 'articles'; openDropdown = 'content'" :class="{ active: activeTab === 'articles' }">
                            {{ isBroadcastHeadUser ? 'My Videos' : 'Articles' }}
                            <svg v-if="activeTab === 'articles'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                        <a href="#" class="sub-item" @click.prevent="activeTab = 'press-works'; openDropdown = 'content'" :class="{ active: activeTab === 'press-works' }">
                            Press Works
                            <svg v-if="activeTab === 'press-works'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                        <a v-if="isArtEditorUser" href="#" class="sub-item" @click.prevent="activeTab = 'gallery'; openDropdown = 'content'" :class="{ active: activeTab === 'gallery' }">
                            Gallery
                            <svg v-if="activeTab === 'gallery'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                    </div>
                </div>
                
                <!-- Workflow Dropdown -->
                <div>
                    <a href="#" class="nav-item has-dropdown" @click.prevent="openDropdown = openDropdown === 'workflow' ? null : 'workflow'" :class="{ active: activeTab === 'assignments' || activeTab === 'submissions' }">
                        <div class="nav-item-left">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            Workflow
                        </div>
                        <svg class="chevron" :style="{ transform: openDropdown === 'workflow' ? 'rotate(180deg)' : 'rotate(0deg)', transition: 'transform 0.2s' }" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </a>
                    <div class="sub-menu" v-show="openDropdown === 'workflow'" :class="{ open: openDropdown === 'workflow' }">
                        <a href="#" class="sub-item" @click.prevent="activeTab = 'assignments'; openDropdown = 'workflow'" :class="{ active: activeTab === 'assignments' }">
                            Assignments
                            <svg v-if="activeTab === 'assignments'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                        <a href="#" class="sub-item" @click.prevent="activeTab = 'submissions'; openDropdown = 'workflow'" :class="{ active: activeTab === 'submissions' }">
                            Submissions
                            <svg v-if="activeTab === 'submissions'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                    </div>
                </div>
                
                <!-- Contributors Nav Item -->
                <a href="#" class="nav-item" @click.prevent="activeTab = 'contributors'" :class="{ active: activeTab === 'contributors' }">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Contributors
                    </div>
                </a>
            </nav>

            <div class="user-profile">
                <img :src="seUser.profile_picture ? ('/storage/' + seUser.profile_picture) : (seUser.profile_picture_url || 'https://picsum.photos/200?random=15')" alt="Profile">
                <div class="user-info">
                    <span class="role-badge" style="background-color: #1a73e8; color: white;">{{ seUser.secondary_role || 'Section Editor' }}</span>
                    <span v-if="seUser.tertiary_role" class="role-badge" style="background-color: #5b21b6; color: white; margin-left: 4px;">{{ seUser.tertiary_role }}</span>
                    <h4>{{ seUser.name || 'Section Editor' }}</h4>
                    <p>{{ seUser.email || 'sec.editor@thesparkpub.com' }}</p>
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

        <!-- Main Content (SPA Container) -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <MobileNavToggle />
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="search" v-model="searchInput" :placeholder="searchPlaceholder" aria-label="Search current view">
                </div>
                <div class="top-header-right">
                    <button class="assign-task-btn" id="main-action-btn" @click.prevent="isAssignTaskModalOpen = true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span id="main-action-text">Assign Task</span>
                    </button>
                    <NotificationsPopover />
                </div>
            </header>

            
<div id="main-content-container" class="content-container fade-in">
    <div v-show="activeTab === 'overview'">
        <div>
    <h1 class="page-title">Overview</h1>
</div>

<!-- Dashboard Grid Layout -->
<div class="dashboard-grid">
    <div class="card">
        <div>
            <div class="card-title" style="margin-bottom: 16px;">Section Summary</div>
            <div class="summary-cards-row">
                <!-- Total Articles -->
                <div class="summary-card blue">
                    <div class="summary-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                    <div>
                        <div class="summary-value">{{ seOverview.totalArticles }}</div>
                        <div class="summary-label">Total Articles</div>
                    </div>
                </div>

                <!-- Pending Review -->
                <div class="summary-card grey">
                    <div class="summary-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 2h14"></path><path d="M5 22h14"></path><path d="M19 2v4c0 3.3-2.7 6-6 6s-6-2.7-6-6V2"></path><path d="M5 22v-4c0-3.3 2.7-6 6-6s6 2.7 6 6v4"></path></svg>
                    </div>
                    <div>
                        <div class="summary-value">{{ seOverview.pendingReview }}</div>
                        <div class="summary-label">Pending Review</div>
                    </div>
                </div>

                <!-- Ready for EIC -->
                <div class="summary-card navy">
                    <div class="summary-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    </div>
                    <div>
                        <div class="summary-value">{{ seOverview.readyForEIC }}</div>
                        <div class="summary-label">Ready for EIC</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="updated-timestamp">{{ seOverview.updatedLabel }}</div>
    </div>

    <!-- Right Column: Staff Writers -->
    <div class="card">
        <div>
            <div class="card-title" style="margin-bottom: 16px;">Staff Writers</div>
            <div v-if="seOverview.activeContributors.length" class="staff-writers-grid">
                <div
                    v-for="user in seOverview.activeContributors.slice(0, 12)"
                    :key="user.id"
                    class="staff-writer-item"
                    :title="user.name"
                >
                    <img
                        :src="user.profile_picture ? '/storage/' + user.profile_picture : (user.profile_picture_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name || 'U')}&background=dbeafe&color=1d4ed8&size=64`)"
                        :alt="user.name"
                        class="staff-writer-avatar"
                    >
                    <span class="staff-writer-name">{{ user.name.split(' ')[0] }}</span>
                </div>
                <!-- +N overflow badge -->
                <div
                    v-if="seOverview.activeContributors.length > 12"
                    class="staff-writer-item"
                    :title="`+${seOverview.activeContributors.length - 12} more staff writers`"
                >
                    <div class="staff-writer-overflow">+{{ seOverview.activeContributors.length - 12 }}</div>
                    <span class="staff-writer-name">more</span>
                </div>
            </div>
            <div v-else style="color: #94a3b8; font-size: 13px; padding: 12px 0;">
                No staff writers found.
            </div>
        </div>
        <div class="updated-timestamp">{{ seOverview.updatedLabel }}</div>
    </div>

    <!-- Full-Width Bottom: Recent Activities Table -->
    <div class="card activities-card">
        <div class="card-title">Recent Activities</div>
        <div class="table-wrapper">
            <table class="activities-table">
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Date &amp; Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="activity in shownOverviewActivities" :key="activity.id">
                        <td>{{ activity.action }}<span v-if="activity.subject">: {{ activity.subject }}</span></td>
                        <td>{{ activity.user }}</td>
                        <td><span class="role-pill">{{ formatRoleSE(activity.role) }}</span></td>
                        <td>{{ formatDateSE(activity.created_at) }}</td>
                    </tr>
                    <tr v-if="!seOverview.activities.length">
                        <td colspan="4" style="text-align: center; padding: 32px; color: #94a3b8;">No recent activities found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div>
    <div v-show="activeTab === 'articles'">
        <div style="display: flex; flex-direction: column; gap: 12px;">
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h1 class="page-title">{{ isBroadcastHeadUser ? 'My Videos' : 'My Articles' }}</h1>
        <div class="filter-pills-group eic-endorsement-filters">
            <!-- Status Dropdown (EIC style) -->
            <div class="eic-custom-filter" @click.stop>
                <button type="button" class="eic-filter-trigger" @click="articlesStatusDropdownOpen = !articlesStatusDropdownOpen">
                    <span>{{ articlesStatusFilter ? (statusLabelMap[articlesStatusFilter] || 'Status') : 'Status' }}</span>
                    <svg :class="{ rotated: articlesStatusDropdownOpen }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </button>
                <div v-if="articlesStatusDropdownOpen" class="eic-filter-menu">
                    <button
                        v-for="opt in statusOptions"
                        :key="opt.value"
                        type="button"
                        :class="{ selected: articlesStatusFilter === opt.value }"
                        @click="articlesStatusFilter = opt.value; articlesStatusDropdownOpen = false; articlesPage = 1"
                    >
                        {{ opt.label }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="activities-table articles-table">
                <thead>
                    <tr>
                        <th style="padding-left: 24px;">Title</th>
                        <th>Writer</th>
                        <th>Status</th>
                        <th style="padding-right: 24px;">Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <SkeletonRows v-if="seArticlesLoading" :columns="4" />
                    <tr v-else-if="seArticlesPaged.length === 0">
                        <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8;">No {{ isBroadcastHeadUser ? 'videos' : 'articles' }} found.</td>
                    </tr>
                    <template v-else>
                        <tr
                            v-for="(article, idx) in seArticlesPaged"
                            :key="article.id"
                            @click="openArticleOrTaskModal(article)"
                            style="cursor: pointer;"
                        >
                            <td :style="idx === seArticlesPaged.length - 1 ? 'padding-left: 24px; border-bottom: none; font-weight: 600;' : 'padding-left: 24px; font-weight: 600;'">
                                {{ article.title }}
                            </td>
                            <td :style="idx === seArticlesPaged.length - 1 ? 'border-bottom: none;' : ''">
                                {{ article.author ? (article.author.name ? article.author.name.split(' ').slice(0,2).join(' ') : article.author) : (seUser.name || '—') }}
                            </td>
                            <td :style="idx === seArticlesPaged.length - 1 ? 'border-bottom: none;' : ''">
                                <span :class="'status-badge ' + statusBadgeClass(article.status)">{{ statusLabel(article.status) }}</span>
                            </td>
                            <td :style="idx === seArticlesPaged.length - 1 ? 'padding-right: 24px; border-bottom: none; color: #64748b; font-weight: 500;' : 'padding-right: 24px; color: #64748b; font-weight: 500;'">
                                {{ formatDateSE(article.updated_at || article.created_at) }}
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <div class="pagination-container" style="padding-top: 20px; margin-top: 20px; border-top: 1px solid #f1f5f9;">
            <div class="pagination-pill">
                <button class="page-btn" :disabled="articlesPage <= 1" @click="articlesPage--">Previous</button>
                <button
                    v-for="p in articlesTotalPages"
                    :key="p"
                    class="page-number"
                    :class="{ active: p === articlesPage }"
                    @click="articlesPage = p"
                >{{ p }}</button>
                <button class="page-btn" :disabled="articlesPage >= articlesTotalPages" @click="articlesPage++">Next</button>
                <div class="page-results-count">
                    Showing <strong>{{ seArticlesPaged.length }}</strong> of <strong>{{ seArticlesFiltered.length }}</strong> {{ isBroadcastHeadUser ? 'videos' : 'articles' }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- </body> -->
    </div>
    <!-- PRESS WORKS TAB -->
    <div v-show="activeTab === 'press-works' || activeTab === 'pressWorks'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
            <h1 class="page-title" style="margin-bottom: 0;">Press Works</h1>
            <button class="new-user-btn" type="button" @click="openPressworkModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                New Academic Year
            </button>
        </div>

        <div class="card" style="padding: 24px; background: #ffffff; border-radius: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);">
            <div v-if="seAcademicYears.length === 0" class="empty-activity" style="text-align: center; padding: 40px;">
                No academic years found. Click "New Academic Year" to create one.
            </div>
            
            <div v-else-if="filteredSeAcademicYears.length" class="academic-years-container">
                <div v-for="yearGroup in filteredSeAcademicYears" :key="yearGroup.academic_year" class="academic-year-folder">
                    <div class="folder-header" @click="toggleSeYear(yearGroup.academic_year)">
                        <svg class="folder-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span class="folder-title">{{ yearGroup.academic_year }}</span>
                        <span class="folder-count">{{ (yearGroup.monitoring_sheets || []).length }} Monitoring Sheet{{ (yearGroup.monitoring_sheets || []).length !== 1 ? 's' : '' }}</span>
                        <button class="delete-year-btn" type="button" @click.stop="openDeleteYearModal(yearGroup.academic_year)" title="Delete Academic Year">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        </button>
                        <svg class="chevron-icon" :style="{ transform: seExpandedYears[yearGroup.academic_year] ? 'rotate(180deg)' : 'rotate(0deg)' }" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </div>
                    
                    <div v-show="seExpandedYears[yearGroup.academic_year]" class="folder-content">
                        <div class="monitoring-sheets-list">
                            <div v-for="sheet in yearGroup.monitoring_sheets" :key="sheet.id" class="monitoring-sheet-item" @click="openMonitoringSheet(sheet)">
                                <span class="pub-badge" :class="pressworkBadgeClass(sheet.publication_type)">{{ sheet.publication_type }}</span>
                                <span style="flex: 1;"></span>
                                <span class="sheet-date">{{ formatDateSE(sheet.created_at) }}</span>
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
    <div v-show="activeTab === 'assignments'">
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h1 class="page-title">Assignments</h1>
                <div style="display: flex; align-items: center; gap: 10px;">
                <button class="assign-task-btn" type="button" @click="isDirectPublishOpen = true" style="margin: 0; background: #ffffff; color: #1d6bf3; border: 1.5px solid #1d6bf3; box-shadow: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path></svg>
                    {{ isBroadcastHeadUser ? 'Publish Automatic/Past Video' : 'Publish Automatic/Past Article' }}
                </button>
                <button class="assign-task-btn" type="button" @click="isAssignTaskModalOpen = true" style="margin: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Assign Task
                </button>
                </div>
            </div>

            <div class="card">
                <div class="table-wrapper">
                    <table class="activities-table articles-table">
                        <thead>
                            <tr>
                                <th style="padding-left: 24px;">Title</th>
                                <th>Assigned To</th>
                                <th>Deadline</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th style="padding-right: 24px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <SkeletonRows v-if="seAssignedLoading" :columns="6" />
                            <tr v-else-if="seAssignedPaged.length === 0">
                                <td colspan="6" style="text-align:center;padding:40px;color:#94a3b8;">No assignments found. Use "Assign Task" to create one.</td>
                            </tr>
                            <template v-else>
                                <tr v-for="(task, idx) in seAssignedPaged" :key="task.id">
                                    <td :style="idx === seAssignedPaged.length-1 ? 'padding-left:24px;border-bottom:none;font-weight:600;' : 'padding-left:24px;font-weight:600;'">{{ task.title }}</td>
                                    <td :style="idx === seAssignedPaged.length-1 ? 'border-bottom:none;' : ''">
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            <img
                                                v-if="task.assigneeAvatar"
                                                :src="task.assigneeAvatar"
                                                :alt="task.assigneeName"
                                                style="width:24px;height:24px;border-radius:50%;object-fit:cover;"
                                            />
                                            <span>{{ task.assigneeName || '—' }}</span>
                                        </div>
                                    </td>
                                    <td :style="idx === seAssignedPaged.length-1 ? 'border-bottom:none;color:#64748b;' : 'color:#64748b;'">{{ task.deadlineLabel }}</td>
                                    <td :style="idx === seAssignedPaged.length-1 ? 'border-bottom:none;' : ''">
                                        <span :class="'priority-tag priority-' + (task.priority || 'medium')">{{ formatPriorityLabel(task.priority) }}</span>
                                    </td>
                                    <td :style="idx === seAssignedPaged.length-1 ? 'border-bottom:none;' : ''">
                                        <span :class="'status-badge ' + assignedStatusClass(task.status)">{{ assignedStatusLabel(task.status) }}</span>
                                    </td>
                                    <td :style="idx === seAssignedPaged.length-1 ? 'padding-right:24px;border-bottom:none;' : 'padding-right:24px;'">
                                        <div style="display:flex;gap:8px;">
                                            <button class="tbl-action-btn edit" @click.stop="openEditTask(task)" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </button>
                                            <button class="tbl-action-btn delete" @click.stop="confirmDeleteTask(task)" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container" style="padding-top:20px;margin-top:20px;border-top:1px solid #f1f5f9;">
                    <div class="pagination-pill">
                        <button class="page-btn" :disabled="seAssignedPage <= 1" @click="seAssignedPage--">Previous</button>
                        <button v-for="p in seAssignedTotalPages" :key="p" class="page-number" :class="{ active: p === seAssignedPage }" @click="seAssignedPage = p">{{ p }}</button>
                        <button class="page-btn" :disabled="seAssignedPage >= seAssignedTotalPages" @click="seAssignedPage++">Next</button>
                        <div class="page-results-count">Showing <strong>{{ seAssignedPaged.length }}</strong> of <strong>{{ seAssignedTasks.length }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-show="activeTab === 'submissions'">
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h1 class="page-title">Submissions</h1>
                <button class="page-nav" style="display:flex;align-items:center;gap:6px;border:1.5px solid #e2e8f0;border-radius:10px;padding:7px 14px;background:#f8fafc;cursor:pointer;font-size:13px;color:#64748b;font-weight:600;" @click="loadSESubmissions" title="Refresh">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                    Refresh
                </button>
            </div>

            <div class="card">
                <div class="table-wrapper">
                    <table class="activities-table articles-table">
                        <thead>
                            <tr>
                                <th style="padding-left: 24px;">Title</th>
                                <th>Submitted By</th>
                                <th>Submitted Date</th>
                                <th>Status</th>
                                <th style="padding-right: 24px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <SkeletonRows v-if="seSubmissionsLoading" :columns="5" />
                            <tr v-else-if="shownSubmissions.length === 0">
                                <td colspan="5" style="text-align:center;padding:40px;color:#94a3b8;">No submitted {{ isBroadcastHeadUser ? 'videos' : 'articles' }} awaiting review.</td>
                            </tr>
                            <template v-else>
                                <tr v-for="(sub, idx) in shownSubmissions" :key="sub.id">
                                    <td :style="idx === shownSubmissions.length-1 ? 'padding-left:24px;border-bottom:none;font-weight:600;' : 'padding-left:24px;font-weight:600;'">{{ sub.title }}</td>
                                    <td :style="idx === shownSubmissions.length-1 ? 'border-bottom:none;' : ''">
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            <img
                                                v-if="sub.author?.profile_picture || sub.author?.profile_picture_url"
                                                :src="sub.author.profile_picture ? '/storage/' + sub.author.profile_picture : sub.author.profile_picture_url"
                                                :alt="sub.author.name"
                                                style="width:24px;height:24px;border-radius:50%;object-fit:cover;"
                                            />
                                            <span>{{ sub.author?.name || '—' }}</span>
                                        </div>
                                    </td>
                                    <td :style="idx === shownSubmissions.length-1 ? 'border-bottom:none;color:#64748b;' : 'color:#64748b;'">{{ formatDateSE(sub.submitted_at || sub.created_at) }}</td>
                                    <td :style="idx === shownSubmissions.length-1 ? 'border-bottom:none;' : ''">
                                        <span class="status-badge status-for-review">For Review</span>
                                    </td>
                                    <td :style="idx === shownSubmissions.length-1 ? 'padding-right:24px;border-bottom:none;' : 'padding-right:24px;'">
                                        <button class="review-btn" @click="openSEReview(sub)">Review</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery (Art Editor only) -->
    <GalleryManager v-if="isArtEditorUser" v-show="activeTab === 'gallery'" :search="searchQuery" />

    <div v-show="activeTab === 'contributors'" class="pinned-pagination-tab">
        <div class="pinned-fill" style="display: flex; flex-direction: column; gap: 12px;">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h1 class="page-title">Contributors</h1>
                <div class="filter-pills-group eic-endorsement-filters">
                    <div class="eic-custom-filter" @click.stop>
                        <button type="button" class="eic-filter-trigger" @click="contributorsStatusDropdownOpen = !contributorsStatusDropdownOpen">
                            <span>{{ contributorsStatusFilter ? (contributorsStatusLabelMap[contributorsStatusFilter] || 'Status') : 'Status' }}</span>
                            <svg :class="{ rotated: contributorsStatusDropdownOpen }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                        <div v-if="contributorsStatusDropdownOpen" class="eic-filter-menu">
                            <button
                                v-for="opt in contributorsStatusOptions"
                                :key="opt.value"
                                type="button"
                                :class="{ selected: contributorsStatusFilter === opt.value }"
                                @click="contributorsStatusFilter = opt.value; contributorsStatusDropdownOpen = false; contributorsPage = 1"
                            >
                                {{ opt.label }}
                            </button>
                        </div>
                    </div>
                    <button type="button" class="page-nav" style="display:flex;align-items:center;gap:6px;border:1.5px solid #e2e8f0;border-radius:10px;padding:7px 14px;background:#f8fafc;cursor:pointer;font-size:13px;color:#64748b;font-weight:600;" @click="loadSEContributors" title="Refresh">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                        Refresh
                    </button>
                </div>
            </div>

            <div class="card pinned-fill">
                <div class="table-wrapper">
                    <table class="activities-table articles-table">
                        <thead>
                            <tr>
                                <th style="padding-left: 24px;">Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Tasks</th>
                                <th style="padding-right: 24px;">Status</th>
                            </tr>
                        </thead>
                        <tbody v-if="contributorsLoading">
                            <tr><td colspan="5" style="text-align:center;padding:40px;color:#94a3b8;">Loading contributors…</td></tr>
                        </tbody>
                        <tbody v-else-if="paginatedContributors.length === 0">
                            <tr><td colspan="5" style="text-align:center;padding:40px;color:#94a3b8;">No contributors found.</td></tr>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="(member, idx) in paginatedContributors" :key="member.id">
                                <td :style="idx === paginatedContributors.length-1 ? 'padding-left:24px;border-bottom:none;' : 'padding-left:24px;'">
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <img :src="member.profile_picture ? ('/storage/' + member.profile_picture) : (member.profile_picture_url || contributorAvatarFallback(member.name))" :alt="member.name" style="width:32px;height:32px;border-radius:50%;object-fit:cover;flex-shrink:0;border:1px solid #e2e8f0;">
                                        <span style="font-weight:600;">{{ member.name }}</span>
                                    </div>
                                </td>
                                <td :style="idx === paginatedContributors.length-1 ? 'border-bottom:none;' : ''"><span class="role-pill">{{ member.secondary_role || member.tertiary_role || formatRoleSE(member.role) }}</span></td>
                                <td :style="idx === paginatedContributors.length-1 ? 'border-bottom:none;color:#64748b;font-weight:500;' : 'color:#64748b;font-weight:500;'">{{ member.email }}</td>
                                <td :style="idx === paginatedContributors.length-1 ? 'border-bottom:none;' : ''"><span style="background:#93c5fd;color:#1e3a8a;width:24px;height:24px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">{{ member.assigned_tasks_count ?? 0 }}</span></td>
                                <td :style="idx === paginatedContributors.length-1 ? 'padding-right:24px;border-bottom:none;' : 'padding-right:24px;'">
                                    <span class="status-badge" :style="member.is_active !== false ? 'background:#dcfce7;color:#15803d;' : 'background:#f1f5f9;color:#64748b;'">{{ member.is_active !== false ? 'Active' : 'Inactive' }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container" style="padding: 20px 0;">
                    <div class="pagination-pill">
                        <button class="page-btn" :disabled="contributorsPage <= 1" @click="contributorsPage--">Previous</button>
                        <button
                            v-for="p in contributorsTotalPages"
                            :key="p"
                            class="page-number"
                            :class="{ active: p === contributorsPage }"
                            @click="contributorsPage = p"
                        >{{ p }}</button>
                        <button class="page-btn" :disabled="contributorsPage >= contributorsTotalPages" @click="contributorsPage++">Next</button>
                        <div class="page-results-count">Showing <strong>{{ filteredContributors.length }}</strong> results</div>
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
        :default-section="defaultSection"
        @close="isAssignTaskModalOpen = false" 
        @task-added="loadSEArticles(); loadSEOverview(); loadSEAssigned();"
        @view-assignments="activeTab = 'assignments'; openDropdown = 'workflow'" 
    />

    <!-- Edit Task Modal -->
    <EditTaskModal
        :is-open="isEditTaskModalOpen"
        :task="editTaskTarget"
        @close="isEditTaskModalOpen = false; editTaskTarget = null;"
        @task-updated="onTaskUpdated"
    />

    <!-- SE Review Modal -->
    <SEReviewModal
        :is-open="isSEReviewModalOpen"
        :submission="seReviewTarget"
        @close="isSEReviewModalOpen = false; seReviewTarget = null;"
        @reviewed="onSEReviewed"
    />

    <!-- Publish an urgent or past article without the review workflow -->
    <DirectVideoPublishModal
        :is-open="isDirectPublishOpen && isBroadcastHeadUser"
        @close="isDirectPublishOpen = false"
        @published="loadSEArticles(); loadSEOverview();"
    />
    <DirectPublishModal
        :is-open="isDirectPublishOpen && !isBroadcastHeadUser"
        @close="isDirectPublishOpen = false"
        @published="loadSEArticles(); loadSEOverview();"
    />

    <!-- Read-only video preview (videos the user was credited on) -->
    <ArticlePreviewModal
        :is-open="isVideoPreviewOpen"
        :article-data="videoPreviewData"
        read-only
        @close="isVideoPreviewOpen = false"
    />

    <!-- Read-only article view (published articles — no more editing workspace) -->
    <ArticlePreviewModal
        :is-open="isArticlePreviewOpen"
        :article-data="selectedArticlePreview"
        read-only
        @close="isArticlePreviewOpen = false"
    />

    <!-- Video Review Modal (Head / Assistant Head Broadcaster) -->
    <VideoReviewModal
        :is-open="isVideoReviewOpen"
        :submission="seReviewTarget"
        @close="isVideoReviewOpen = false; seReviewTarget = null;"
        @reviewed="onSEReviewed"
    />

    <!-- Video Workspace Modal (when the head presents a video themselves) -->
    <VideoWorkspaceModal
        :is-open="isVideoWorkspaceOpen"
        :task-data="selectedTask"
        @close="isVideoWorkspaceOpen = false; loadSEArticles();"
        @task-submitted="loadSEArticles(); loadSEOverview();"
        @task-saved-as-draft="loadSEArticles(); loadSEOverview();"
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
        @close="isWorkspaceModalOpen = false; loadSEArticles();" 
        @task-submitted="loadSEArticles(); loadSEOverview();"
        @task-saved-as-draft="loadSEArticles(); loadSEOverview();"
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
                <button class="btn-next btn-danger" type="button" :disabled="deleteYearSaving" @click="deleteYear">{{ deleteYearSaving ? 'Deleting...' : 'Delete Academic Year' }}</button>
                <button class="btn-back" type="button" @click="closeDeleteYearModal">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Delete Assignment Confirmation Modal -->
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
                <button class="btn-next btn-danger" type="button" :disabled="deletingTask" @click="doDeleteTask">{{ deletingTask ? 'Deleting...' : 'Delete Assignment' }}</button>
                <button class="btn-back" type="button" @click="deleteTaskTarget = null">Cancel</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import MobileNavToggle from '../../components/MobileNavToggle.vue';
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { lazyModal } from '../../utils/lazyModal';
import { useDebouncedSearch } from '../../utils/dashboardSearch';
import { useRouter } from 'vue-router';
const AssignTaskModal = lazyModal(() => import('../../components/AssignTaskModal.vue'));
const AssignedTaskModal = lazyModal(() => import('../../components/AssignedTaskModal.vue'));
const AssignmentWorkspaceModal = lazyModal(() => import('../../components/AssignmentWorkspaceModal.vue'));
const EditTaskModal = lazyModal(() => import('../../components/EditTaskModal.vue'));
const SEReviewModal = lazyModal(() => import('../../components/SEReviewModal.vue'));
const VideoReviewModal = lazyModal(() => import('../../components/VideoReviewModal.vue'));
const VideoWorkspaceModal = lazyModal(() => import('../../components/VideoWorkspaceModal.vue'));
const ArticlePreviewModal = lazyModal(() => import('../../components/ArticlePreviewModal.vue'));
const DirectPublishModal = lazyModal(() => import('../../components/DirectPublishModal.vue'));
const DirectVideoPublishModal = lazyModal(() => import('../../components/DirectVideoPublishModal.vue'));
import { isBroadcastHead, isVideoTask, fetchCreditedVideos, buildVideoPreviewData } from '../../utils/video';
import NotificationsPopover from '../../components/NotificationsPopover.vue';
import GalleryManager from '../../components/GalleryManager.vue';
import { signOut as performSignOut } from '../../utils/auth';

const router = useRouter();
const token = localStorage.getItem('sparky_token');
const activeTab = ref('overview');
const { input: searchInput, query: searchQuery } = useDebouncedSearch();

const matchesSearch = (...fields) => {
    if (!searchQuery.value || !searchQuery.value.trim()) return true;
    const query = searchQuery.value.toLowerCase().trim();
    return fields.some(field => String(field || '').toLowerCase().includes(query));
};

const searchPlaceholder = computed(() => ({
    overview: 'Search recent activities',
    articles: 'Search articles',
    assignments: 'Search assignments',
    submissions: 'Search submissions',
    contributors: 'Search contributors',
    gallery: 'Search gallery photos',
    'press-works': 'Search press works',
    pressWorks: 'Search press works',
}[activeTab.value] || 'Search'));

// ── Articles Tab State ────────────────────────────────────────────────────────
const seArticles = ref([]);
const seArticlesLoading = ref(false);
const articlesStatusFilter = ref('');
const articlesStatusDropdownOpen = ref(false);
const articlesPage = ref(1);
const articlesPerPage = 12;

const statusOptions = [
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

const statusLabelMap = {
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

const statusLabel = (status) => {
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

const statusBadgeClass = (status) => {
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

const shownOverviewActivities = computed(() => (seOverview.value.activities || []).filter(activity => matchesSearch(
    activity.action,
    activity.subject,
    activity.user,
    activity.role,
)));

const shownSubmissions = computed(() => seSubmissions.value.filter(sub => matchesSearch(
    sub.title,
    sub.authorName,
    typeof sub.section === 'object' ? sub.section?.name : sub.section,
)));

const seArticlesFiltered = computed(() => {
    let list = seArticles.value;
    if (articlesStatusFilter.value) {
        list = list.filter(a => a.status === articlesStatusFilter.value);
    }
    if (searchQuery.value && searchQuery.value.trim()) {
        list = list.filter(a => matchesSearch(a.title, a.section?.name || a.section));
    }
    return list;
});

const articlesTotalPages = computed(() =>
    Math.max(1, Math.ceil(seArticlesFiltered.value.length / articlesPerPage))
);

const seArticlesPaged = computed(() => {
    const start = (articlesPage.value - 1) * articlesPerPage;
    return seArticlesFiltered.value.slice(start, start + articlesPerPage);
});

const loadSEArticles = async () => {
    if (!token) return;
    seArticlesLoading.value = true;
    try {
        const [articlesRes, tasksRes] = await Promise.allSettled([
            fetch(`/api/articles?author_id=${seUser.value.id}${isBroadcastHeadUser.value ? '&type=video' : ''}`, {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            }),
            fetch(`/api/tasks?assignee_id=${seUser.value.id}`, {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            })
        ]);

        let articlesList = [];
        let tasksList = [];

        if (articlesRes.status === 'fulfilled' && articlesRes.value.ok) {
            const data = await articlesRes.value.json();
            articlesList = Array.isArray(data) ? data : [];
        }

        // Videos this user was credited on (reporter, scriptwriter, ...) also count as theirs
        const credited = await fetchCreditedVideos(seUser.value.id);
        credited.forEach(video => {
            if (!articlesList.some(a => a.id === video.id)) articlesList.push(video);
        });

        if (tasksRes.status === 'fulfilled' && tasksRes.value.ok) {
            const data = await tasksRes.value.json();
            tasksList = Array.isArray(data)
                ? data.filter(t => t.type === 'writing' && isVideoTask(t) === isBroadcastHeadUser.value)
                : [];
        }

        // Map articles into standardized view objects
        const combined = articlesList.map(a => {
            const matchedTask = tasksList.find(t => t.article_id === a.id);
            const dueTime = matchedTask ? parseNotesField(matchedTask.notes, 'Due Time') : '';
            return {
                id: a.id,
                article_id: a.id,
                taskId: matchedTask?.id || null,
                title: a.title,
                author: a.author || seUser.value,
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

        // For tasks assigned to seUser that haven't been linked to an article yet:
        tasksList.forEach(t => {
            const alreadyLinked = combined.some(item => item.article_id && item.article_id === t.article_id);
            if (!alreadyLinked) {
                const dueTime = parseNotesField(t.notes, 'Due Time');
                combined.push({
                    id: `task-${t.id}`,
                    taskId: t.id,
                    article_id: t.article_id || null,
                    title: t.title,
                    author: t.assignee || seUser.value,
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

        // Sort descending by updated_at / created_at
        combined.sort((a, b) => new Date(b.updated_at || b.created_at) - new Date(a.updated_at || a.created_at));
        seArticles.value = combined;
    } catch (err) {
        console.warn('Could not load SE articles:', err);
    } finally {
        seArticlesLoading.value = false;
    }
};
const openDropdown = ref(null);
const isAssignTaskModalOpen = ref(false);
const isAssignedTaskModalOpen = ref(false);
const isWorkspaceModalOpen = ref(false);
const selectedTask = ref({});
const seUser = ref(JSON.parse(localStorage.getItem('sparky_user') || '{}'));

// The Head / Assistant Head Broadcaster runs the video workflow instead of print articles
const isBroadcastHeadUser = computed(() => isBroadcastHead(seUser.value));
// The Art Editor manages the gallery (upload / edit photos)
const isArtEditorUser = computed(() =>
    `${seUser.value?.secondary_role || ''} ${seUser.value?.tertiary_role || ''}`.toLowerCase().includes('art editor'));
const isVideoWorkspaceOpen = ref(false);
const isVideoReviewOpen = ref(false);

// ── Overview State ─────────────────────────────────────────────────────────────
const seOverview = ref({
    totalArticles: 0,
    pendingReview: 0,
    readyForEIC: 0,
    activities: [],
    recentSubmitters: [],
    activeContributors: [],
    updatedAt: null,
    updatedLabel: 'Loading...',
});

// ── Helpers ────────────────────────────────────────────────────────────────────
const formatRoleSE = (role) => {
    if (!role) return 'Unknown';
    const map = {
        eic: 'Editor in Chief',
        section_editor: 'Section Editor',
        staff_writer: 'Staff Writer',
        staff_artist: 'Staff Artist',
        staff_broadcaster: 'Staff Broadcaster',
        admin: 'Administrator',
        system: 'System',
    };
    return map[role] || role.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
};

// ── Contributors Tab State ──────────────────────────────────────────────────
const seContributors = ref([]);
const contributorsLoading = ref(false);
const contributorsStatusFilter = ref('');
const contributorsStatusDropdownOpen = ref(false);
const contributorsPage = ref(1);
const contributorsPerPage = 8;

const contributorsStatusOptions = [
    { value: '', label: 'All Status' },
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' },
];
const contributorsStatusLabelMap = { active: 'Active', inactive: 'Inactive' };

const contributorAvatarFallback = (name) => `https://ui-avatars.com/api/?name=${encodeURIComponent(name || 'User')}&background=dbeafe&color=1d4ed8`;

const loadSEContributors = async () => {
    if (!token) return;
    contributorsLoading.value = true;
    try {
        const res = await fetch('/api/users', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        if (res.ok) {
            const data = await res.json();
            const list = Array.isArray(data) ? data : (data.users || []);
            seContributors.value = list.filter(u => ['staff_writer', 'staff_artist', 'staff_broadcaster'].includes(u.role));
        }
    } catch (e) {
        console.warn('Could not load SE contributors:', e);
    } finally {
        contributorsLoading.value = false;
    }
};

const filteredContributors = computed(() => {
    return seContributors.value
        .filter(m => {
            if (contributorsStatusFilter.value === 'active') return m.is_active !== false;
            if (contributorsStatusFilter.value === 'inactive') return m.is_active === false;
            return true;
        })
        .filter(m => matchesSearch(
            m.name,
            m.email,
            m.secondary_role,
            m.tertiary_role,
            formatRoleSE(m.role),
        ));
});

const contributorsTotalPages = computed(() =>
    Math.max(1, Math.ceil(filteredContributors.value.length / contributorsPerPage))
);

const paginatedContributors = computed(() => {
    const start = (contributorsPage.value - 1) * contributorsPerPage;
    return filteredContributors.value.slice(start, start + contributorsPerPage);
});

watch([searchQuery, contributorsStatusFilter], () => {
    contributorsPage.value = 1;
});

const formatDateSE = (date) => date
    ? new Date(date).toLocaleString([], { dateStyle: 'medium', timeStyle: 'short' })
    : '—';

// ── Fetch Overview Data ───────────────────────────────────────────────────────
const loadSEOverview = async () => {
    if (!token) return;
    try {
        const headers = { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' };

        // Fetch articles, users, and SE overview in parallel
        const [articlesRes, usersRes, overviewRes] = await Promise.allSettled([
            fetch(`/api/articles${isBroadcastHeadUser.value ? '?type=video' : ''}`, { headers }),
            fetch('/api/users', { headers }),
            fetch('/api/section-editor/overview', { headers }),
        ]);

        let articles = [];
        let users = [];
        let activities = [];
        let summaryFromAPI = null;

        if (articlesRes.status === 'fulfilled' && articlesRes.value.ok) {
            const data = await articlesRes.value.json();
            articles = Array.isArray(data) ? data : [];
        }

        if (usersRes.status === 'fulfilled' && usersRes.value.ok) {
            const data = await usersRes.value.json();
            users = Array.isArray(data) ? data : [];
        }

        // Use the dedicated SE overview endpoint for activities & API-computed summary
        if (overviewRes.status === 'fulfilled' && overviewRes.value.ok) {
            const data = await overviewRes.value.json();
            activities = data.activities || [];
            summaryFromAPI = data.summary || null;
        }

        // Prefer API-computed counts; fall back to client-side computation
        const totalArticles = summaryFromAPI?.articles ?? articles.length;
        const pendingReview = summaryFromAPI?.pending_review ?? articles.filter(a =>
            ['submitted', 'under_review'].includes(a.status)
        ).length;
        const readyForEIC = summaryFromAPI?.ready_for_eic ?? articles.filter(a => a.status === 'endorsed').length;

        // Recent submitters: unique authors of submitted/under_review articles
        const recentMap = new Map();
        articles
            .filter(a => ['submitted', 'under_review'].includes(a.status) && a.author)
            .forEach(a => { if (!recentMap.has(a.author.id)) recentMap.set(a.author.id, a.author); });
        const recentSubmitters = [...recentMap.values()].slice(0, 6);

        // Active contributors: ALL active staff writers/artists/broadcasters (no cap)
        const activeContributors = users
            .filter(u => ['staff_writer', 'staff_artist', 'staff_broadcaster'].includes(u.role) && u.is_active !== false)
            .sort((a, b) => (a.name || '').localeCompare(b.name || ''));

        const now = new Date();
        const updatedLabel = `Updated ${now.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })}`;

        seOverview.value = {
            totalArticles,
            pendingReview,
            readyForEIC,
            activities,
            recentSubmitters,
            activeContributors,
            updatedAt: now,
            updatedLabel,
        };
    } catch (err) {
        console.warn('Could not load SE overview:', err);
        seOverview.value.updatedLabel = 'Unable to load';
    }
};

const defaultSection = computed(() => {
    const secRole = `${seUser.value?.secondary_role || ''} ${seUser.value?.tertiary_role || ''}`;
    if (secRole.includes('News')) return 'News';
    if (secRole.includes('Opinion')) return 'Opinion';
    if (secRole.includes('Editorial')) return 'Editorial';
    if (secRole.includes('Feature')) return 'Feature';
    if (secRole.includes('Sci') || secRole.includes('Tech')) return 'Sci-Tech';
    if (secRole.includes('DevCom')) return 'DevCom';
    if (secRole.includes('Sports')) return 'Sports';
    if (secRole.includes('Literary')) return 'Literary';
    if (secRole.includes('Broadcaster') || secRole.includes('Radio')) return 'Radio Broadcasting';
    return 'News';
});

const isVideoPreviewOpen = ref(false);
const videoPreviewData = ref({});
const isDirectPublishOpen = ref(false);
const isArticlePreviewOpen = ref(false);
const selectedArticlePreview = ref({});


const openArticleOrTaskModal = (item) => {
    const matchedTask = item.matchedTask || (item.isTask ? item.raw : null);

    // A credited video (no task of their own) opens as a read-only preview
    if (item.raw?.type === 'video' && !matchedTask) {
        videoPreviewData.value = buildVideoPreviewData(item.raw);
        isVideoPreviewOpen.value = true;
        return;
    }

    // A published article is finished — show the read-only article view, not the editing workspace
    if (item.status === 'published' && item.raw) {
        selectedArticlePreview.value = item.raw;
        isArticlePreviewOpen.value = true;
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
        section: isBroadcastHeadUser.value
            ? 'Radio Broadcasting'
            : ((item.section && typeof item.section === 'object') ? (item.section.name || 'News') : (item.section || parseNotesField(notesStr, 'Section') || 'News')),
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
            name: item.author.name || seUser.value.name,
            secondary_role: item.author.secondary_role || seUser.value.secondary_role || 'Section Editor',
            role: item.author.role || seUser.value.role || 'section_editor',
            avatar: item.author.profile_picture ? `/storage/${item.author.profile_picture}` : (item.author.profile_picture_url || '')
        }] : [{
            name: seUser.value.name,
            secondary_role: seUser.value.secondary_role || 'Section Editor',
            role: seUser.value.role || 'section_editor',
            avatar: seUser.value.profile_picture ? `/storage/${seUser.value.profile_picture}` : (seUser.value.profile_picture_url || '')
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

const handleOpenWorkspace = (taskData) => {
    isAssignedTaskModalOpen.value = false;
    selectedTask.value = taskData || selectedTask.value;
    if (isBroadcastHeadUser.value) {
        isVideoWorkspaceOpen.value = true;
        return;
    }
    isWorkspaceModalOpen.value = true;
};

const openMonitoringSheet = (sheet) => {
    if (sheet && sheet.id) {
        window.open(`/monitoring-sheet/${sheet.id}`, '_blank');
    } else {
        window.open('/monitoring-sheet', '_blank');
    }
};

// ── Press Works State & Methods ──────────────────────────────────────────────
const seAcademicYears = ref([]);
const seExpandedYears = ref({});
const isPressworkModalOpen = ref(false);
const pressworkSaving = ref(false);
const pressworkError = ref('');
const pressworkForm = ref({ academic_year: '2025-2026' });

const isDeleteYearModalOpen = ref(false);
const yearToDelete = ref('');
const deleteYearSaving = ref(false);
const deleteYearError = ref('');

const filteredSeAcademicYears = computed(() => {
    return seAcademicYears.value
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
    return seAcademicYears.value.some(year => year.academic_year === pressworkForm.value.academic_year);
});

watch(() => pressworkForm.value.academic_year, () => {
    pressworkError.value = '';
});

watch(searchQuery, () => {
    articlesPage.value = 1;
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

        await loadSEPressWorks();
        seExpandedYears.value[pressworkForm.value.academic_year] = true;
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

        await loadSEPressWorks();
        closeDeleteYearModal();
    } catch (error) {
        deleteYearError.value = error.message;
    } finally {
        deleteYearSaving.value = false;
    }
};

const loadSEPressWorks = async () => {
    try {
        const currentToken = localStorage.getItem('sparky_token') || token;
        const response = await fetch('/api/press-works', {
            headers: {
                Authorization: `Bearer ${currentToken}`,
                Accept: 'application/json',
            },
        });
        if (response.ok) {
            const data = await response.json();
            seAcademicYears.value = data.academic_years || [];
            if (seAcademicYears.value.length && Object.keys(seExpandedYears.value).length === 0) {
                seExpandedYears.value[seAcademicYears.value[0].academic_year] = true;
            }
        } else {
            seAcademicYears.value = [];
        }
    } catch {
        seAcademicYears.value = [];
    }
};

const toggleSeYear = (academicYear) => {
    seExpandedYears.value[academicYear] = !seExpandedYears.value[academicYear];
};

// ── SE Assigned Tasks State (Assignments Tab) ─────────────────────────────────
const seAssignedTasks = ref([]);
const seAssignedLoading = ref(false);
const seAssignedPage = ref(1);
const seAssignedPerPage = 10;
const isEditTaskModalOpen = ref(false);
const editTaskTarget = ref(null);
const deleteTaskTarget = ref(null);
const deletingTask = ref(false);

// ── SE Submissions State (Submissions Tab) ────────────────────────────────────
const seSubmissions = ref([]);
const seSubmissionsLoading = ref(false);
const isSEReviewModalOpen = ref(false);
const seReviewTarget = ref(null);

const loadSEAssigned = async () => {
    if (!token || !seUser.value?.id) return;
    seAssignedLoading.value = true;
    try {
        const res = await fetch(`/api/tasks?assigned_by=${seUser.value.id}&type=writing`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            const data = await res.json();
            // Once the linked article is published, the assignment is done and drops off this list.
            seAssignedTasks.value = (Array.isArray(data) ? data : []).filter(t => t.article?.status !== 'published').map(t => {
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
        console.warn('Could not load SE assigned tasks:', e);
    } finally {
        seAssignedLoading.value = false;
    }
};

const seAssignedFiltered = computed(() => {
    if (!searchQuery.value || !searchQuery.value.trim()) return seAssignedTasks.value;
    const q = searchQuery.value.toLowerCase().trim();
    return seAssignedTasks.value.filter(t =>
        (t.title || '').toLowerCase().includes(q) ||
        (t.assigneeName || '').toLowerCase().includes(q) ||
        (t.priority || '').toLowerCase().includes(q) ||
        (t.status || '').toLowerCase().includes(q)
    );
});

const seAssignedTotalPages = computed(() =>
    Math.max(1, Math.ceil(seAssignedFiltered.value.length / seAssignedPerPage))
);

const seAssignedPaged = computed(() => {
    const start = (seAssignedPage.value - 1) * seAssignedPerPage;
    return seAssignedFiltered.value.slice(start, start + seAssignedPerPage);
});

const openEditTask = (task) => {
    editTaskTarget.value = task;
    isEditTaskModalOpen.value = true;
};

const onTaskUpdated = () => {
    isEditTaskModalOpen.value = false;
    editTaskTarget.value = null;
    loadSEAssigned();
    loadSEOverview();
};

const confirmDeleteTask = (task) => {
    deleteTaskTarget.value = task;
};

const doDeleteTask = async () => {
    if (!deleteTaskTarget.value) return;
    deletingTask.value = true;
    try {
        const res = await fetch(`/api/tasks/${deleteTaskTarget.value.id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            deleteTaskTarget.value = null;
            await loadSEAssigned();
            loadSEOverview();
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
        returned: 'Returned',
        completed: 'Completed'
    };
    return map[status] || (status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Pending');
};

const assignedStatusClass = (status) => {
    const map = {
        pending: 'status-draft',
        in_progress: 'status-under-revision',
        submitted: 'status-for-review',
        returned: 'status-rejected',
        completed: 'status-published'
    };
    return map[status] || 'status-draft';
};

const loadSESubmissions = async () => {
    if (!token) return;
    seSubmissionsLoading.value = true;
    try {
        const [articlesRes, tasksRes] = await Promise.allSettled([
            fetch(`/api/articles?status=submitted${isBroadcastHeadUser.value ? '&type=video' : ''}`, {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            }),
            fetch(`/api/tasks?assigned_by=${seUser.value?.id}&status=submitted&type=writing`, {
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
            tasksList = Array.isArray(data) ? data.filter(t => isVideoTask(t) === isBroadcastHeadUser.value) : [];
        }

        const sec = (defaultSection.value || '').toLowerCase();
        const relevantArticles = articlesList.filter(a => {
            // Every submitted video is the Head Broadcasters' to review, including their own
            if (isBroadcastHeadUser.value) return true;
            const aSec = (typeof a.section === 'object' ? (a.section?.name || '') : (a.section || '')).toLowerCase();
            return !sec || aSec.includes(sec) || sec.includes(aSec) || (a.author_id !== seUser.value?.id);
        });

        const combined = relevantArticles.map(a => {
            // The writer's task, not whichever task on the article comes first: the artist's is newer, so it
            // is listed first, and returning it would leave the writer's task stuck in "Submitted"
            const onArticle = tasksList.filter(t => t.article_id === a.id);
            const matched = onArticle.find(t => t.type === 'writing') || onArticle[0];
            return {
                ...a,
                id: a.id,
                article_id: a.id,
                taskId: matched?.id || null,
                authorName: a.author?.name || 'Unknown Writer',
                submitted_at: a.submitted_at || a.updated_at || a.created_at,
                raw: a
            };
        });

        tasksList.forEach(t => {
            const alreadyIn = combined.some(item => (t.article_id && item.article_id === t.article_id) || item.taskId === t.id);
            if (!alreadyIn) {
                combined.push({
                    id: t.article_id || `task-${t.id}`,
                    article_id: t.article_id || null,
                    taskId: t.id,
                    title: t.title,
                    author: t.assignee || { name: 'Unknown Writer' },
                    authorName: t.assignee?.name || 'Unknown Writer',
                    section: t.section?.name || parseNotesField(t.notes, 'Section') || defaultSection.value,
                    submitted_at: t.updated_at || t.created_at,
                    raw: t
                });
            }
        });

        seSubmissions.value = combined;
    } catch (e) {
        console.warn('Could not load SE submissions:', e);
    } finally {
        seSubmissionsLoading.value = false;
    }
};

const openSEReview = (sub) => {
    seReviewTarget.value = sub;
    if (isBroadcastHeadUser.value) {
        isVideoReviewOpen.value = true;
        return;
    }
    isSEReviewModalOpen.value = true;
};

const onSEReviewed = () => {
    loadSESubmissions();
    loadSEAssigned();
    loadSEOverview();
    loadSEArticles();
};

watch(activeTab, (tab) => {
    if (tab === 'assignments') {
        loadSEAssigned();
    } else if (tab === 'submissions') {
        loadSESubmissions();
    } else if (tab === 'contributors') {
        loadSEContributors();
    }
});

// ── Profile Update Listener ───────────────────────────────────────────────────
const onProfileUpdated = (e) => {
    if (e.detail) {
        seUser.value = e.detail;
    }
};

const closeStatusDropdown = (e) => {
    // Close if click is outside a .eic-custom-filter
    if (!e.target.closest('.eic-custom-filter')) {
        articlesStatusDropdownOpen.value = false;
        contributorsStatusDropdownOpen.value = false;
    }
};

// ── Lifecycle ─────────────────────────────────────────────────────────────────
onMounted(async () => {
    // Refresh profile from backend
    if (token) {
        try {
            const res = await fetch('/api/me', {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            if (res.ok) {
                const fresh = await res.json();
                seUser.value = fresh;
                localStorage.setItem('sparky_user', JSON.stringify(fresh));
            }
        } catch (e) {
            console.warn('Could not refresh SE profile:', e);
        }
    }

    await loadSEOverview();
    await loadSEArticles();
    await loadSEPressWorks();
    await loadSEAssigned();
    await loadSESubmissions();
    await loadSEContributors();
    window.addEventListener('sparky:profile-updated', onProfileUpdated);
    document.addEventListener('click', closeStatusDropdown);
});

onUnmounted(() => {
    window.removeEventListener('sparky:profile-updated', onProfileUpdated);
    document.removeEventListener('click', closeStatusDropdown);
});
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

.review-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 16px;
    background: #2563eb;
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.2s;
}
.review-btn:hover {
    background: #1d4ed8;
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
