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
                    <a href="#" class="nav-item has-dropdown" @click.prevent="openDropdown = openDropdown === 'content' ? null : 'content'" :class="{ active: activeTab === 'articles' || activeTab === 'press-works' }">
                        <div class="nav-item-left">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            Content
                        </div>
                        <svg class="chevron" :style="{ transform: openDropdown === 'content' ? 'rotate(180deg)' : 'rotate(0deg)', transition: 'transform 0.2s' }" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </a>
                    <div class="sub-menu" v-show="openDropdown === 'content'" :class="{ open: openDropdown === 'content' }">
                        <a href="#" class="sub-item" @click.prevent="activeTab = 'articles'; openDropdown = 'content'" :class="{ active: activeTab === 'articles' }">
                            Articles
                            <svg v-if="activeTab === 'articles'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                        <a href="#" class="sub-item" @click.prevent="activeTab = 'press-works'; openDropdown = 'content'" :class="{ active: activeTab === 'press-works' }">
                            Press Works
                            <svg v-if="activeTab === 'press-works'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
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
                    <h4>{{ seUser.name || 'Section Editor' }}</h4>
                    <p>{{ seUser.email || 'sec.editor@thesparkpub.com' }}</p>
                </div>
                <button class="settings-btn">
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
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" placeholder="Search">
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
                        <div class="summary-value">641</div>
                        <div class="summary-label">Total Articles</div>
                    </div>
                </div>

                <!-- Pending Review -->
                <div class="summary-card grey">
                    <div class="summary-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 2h14"></path><path d="M5 22h14"></path><path d="M19 2v4c0 3.3-2.7 6-6 6s-6-2.7-6-6V2"></path><path d="M5 22v-4c0-3.3 2.7-6 6-6s6 2.7 6 6v4"></path></svg>
                    </div>
                    <div>
                        <div class="summary-value">225</div>
                        <div class="summary-label">Pending Review</div>
                    </div>
                </div>

                <!-- Ready for EIC -->
                <div class="summary-card navy">
                    <div class="summary-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    </div>
                    <div>
                        <div class="summary-value">42</div>
                        <div class="summary-label">Ready for EIC</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="updated-timestamp">Updated just now</div>
    </div>

    <!-- Right Column: Contributors Activity -->
    <div class="card">
        <div>
            <div class="card-title" style="margin-bottom: 16px;">Contributors Activity</div>
            <div class="contributors-activity-box">
                <!-- Recent Submissions -->
                <div class="contributors-sub-card">
                    <div class="contributors-sub-title">Recent Submissions</div>
                    <div class="avatar-stack">
                        <img src="https://picsum.photos/100?random=1" alt="Contributor">
                        <img src="https://picsum.photos/100?random=2" alt="Contributor">
                        <img src="https://picsum.photos/100?random=3" alt="Contributor">
                        <img src="https://picsum.photos/100?random=4" alt="Contributor">
                        <span class="avatar-badge">+ 2 users</span>
                    </div>
                </div>

                <!-- Active Contributors -->
                <div class="contributors-sub-card">
                    <div class="contributors-sub-title">Active Contributors</div>
                    <div class="avatar-stack">
                        <img src="https://picsum.photos/100?random=5" alt="Contributor">
                        <img src="https://picsum.photos/100?random=6" alt="Contributor">
                        <img src="https://picsum.photos/100?random=7" alt="Contributor">
                        <img src="https://picsum.photos/100?random=8" alt="Contributor">
                        <span class="avatar-badge">+ 4 users</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="updated-timestamp">Updated just now</div>
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
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Submitted draft for review</td>
                        <td>Gabrielle M. Loquias</td>
                        <td><span class="role-pill">News Writer</span></td>
                        <td>Apr 4, 2026 17:07:42</td>
                    </tr>
                    <tr>
                        <td>Reviewed an article</td>
                        <td>Dustin Jake E. Nas</td>
                        <td><span class="role-pill">Copy Editor</span></td>
                        <td>Apr 4, 2026 09:01:52</td>
                    </tr>
                    <tr>
                        <td>Returned article for revision</td>
                        <td>Johan Abinal</td>
                        <td><span class="role-pill">News Editor</span></td>
                        <td>Apr 3, 2026 07:07:42</td>
                    </tr>
                    <tr>
                        <td>Endorsed article to EIC</td>
                        <td>Johan Abinal</td>
                        <td><span class="role-pill">News Editor</span></td>
                        <td>Apr 1, 2026 21:37:12</td>
                    </tr>
                    <tr>
                        <td>Updated supporting image</td>
                        <td>Johnrey Frongoso</td>
                        <td><span class="role-pill">Photojournalists</span></td>
                        <td>Apr 1, 2026 23:08:22</td>
                    </tr>
                    <tr>
                        <td>Submitted thumbnail design</td>
                        <td>Nicole Orcine</td>
                        <td><span class="role-pill">Graphic Artist</span></td>
                        <td>Apr 1, 2026 21:37:12</td>
                    </tr>
                    <tr>
                        <td>Approved thumbnail submission</td>
                        <td>Johan Abinal</td>
                        <td><span class="role-pill">News Editor</span></td>
                        <td>Apr 1, 2026 23:08:22</td>
                    </tr>
                    <tr>
                        <td>Endorsed article to EIC</td>
                        <td>Johan Abinal</td>
                        <td><span class="role-pill">News Editor</span></td>
                        <td>Apr 1, 2026 21:37:12</td>
                    </tr>
                    <tr>
                        <td>Revised an article</td>
                        <td>Gabrielle M. Loquias</td>
                        <td><span class="role-pill">News Writer</span></td>
                        <td>Apr 1, 2026 23:08:22</td>
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
        <h1 class="page-title">Published Articles</h1>
        <div class="filters" style="display: flex; gap: 12px;">
            <button class="filter-btn">Status <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg ></button>
            <button class="filter-btn">Coverage <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg ></button>
            <button class="filter-btn">Date <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg ></button>
            <button class="filter-btn">Contributor <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg ></button>
        </div>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="activities-table articles-table">
                <thead>
                    <tr>
                        <th style="padding-left: 24px;">Title</th>
                        <th>Coverage</th>
                        <th>Writer</th>
                        <th>Status</th>
                        <th style="padding-right: 24px;">Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding-left: 24px;">The Rise of Campus Creatives</td>
                        <td>AY 2025 - 2026 Issue 1</td>
                        <td>Gabrielle L.</td>
                        <td><span class="status-badge status-under-revision">Under Revision</span></td>
                        <td style="padding-right: 24px; color: #64748b; font-weight: 500;">Apr 13 &bull; 10:40 PM</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 24px;">Scholarship Program Expanded</td>
                        <td>AY 2025 - 2026 Issue 2</td>
                        <td>Vien L.</td>
                        <td><span class="status-badge status-under-revision">Under Revision</span></td>
                        <td style="padding-right: 24px; color: #64748b; font-weight: 500;">Apr 11 &bull; 4:30 PM</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 24px;">Enrollment Update for Second Semester</td>
                        <td>AY 2025 - 2026 Issue 1</td>
                        <td>Alaissa N.</td>
                        <td><span class="status-badge status-draft">Draft</span></td>
                        <td style="padding-right: 24px; color: #64748b; font-weight: 500;">Apr 13 &bull; 10:20 AM</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 24px;">New Laboratory Building Opens</td>
                        <td>Foundation Day 2026</td>
                        <td>Samantha C.</td>
                        <td><span class="status-badge status-published">Published</span></td>
                        <td style="padding-right: 24px; color: #64748b; font-weight: 500;">Apr 8 &bull; 1:45 PM</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 24px;">Student Leaders Hold Town Hall Meeting</td>
                        <td>AY 2025 - 2026 Issue 1</td>
                        <td>Gabrielle L.</td>
                        <td><span class="status-badge status-published">Published</span></td>
                        <td style="padding-right: 24px; color: #64748b; font-weight: 500;">Apr 5 &bull; 3:10 PM</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 24px;">Campus Wi-Fi Expansion Project</td>
                        <td>Tech & Innovation Series</td>
                        <td>Vien L.</td>
                        <td><span class="status-badge status-draft">Draft</span></td>
                        <td style="padding-right: 24px; color: #64748b; font-weight: 500;">Apr 14 &bull; 11:00 AM</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 24px;">Wellness Campaign Launch</td>
                        <td>AY 2025 - 2026 Issue 1</td>
                        <td>Samantha C.</td>
                        <td><span class="status-badge status-endorsed">Endorsed</span></td>
                        <td style="padding-right: 24px; color: #64748b; font-weight: 500;">Apr 15 &bull; 9:40 AM</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 24px;">College Fair Attracts Hundreds</td>
                        <td>AY 2025 - 2026 Issue 1</td>
                        <td>Alaissa N.</td>
                        <td><span class="status-badge status-published">Published</span></td>
                        <td style="padding-right: 24px; color: #64748b; font-weight: 500;">Apr 2 &bull; 5:20 PM</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 24px;">New Scholarship Guidelines Released</td>
                        <td>Enrollment Period 2026</td>
                        <td>Gabrielle L.</td>
                        <td><span class="status-badge status-under-revision">Under Revision</span></td>
                        <td style="padding-right: 24px; color: #64748b; font-weight: 500;">Apr 6 &bull; 8:50 AM</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 24px;">Student Government Election Begins</td>
                        <td>Enrollment Period 2026</td>
                        <td>Vien L.</td>
                        <td><span class="status-badge status-for-review">For Review</span></td>
                        <td style="padding-right: 24px; color: #64748b; font-weight: 500;">Apr 13 &bull; 11:00 AM</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 24px;">Campus Security Measures Updated</td>
                        <td>AY 2025 - 2026 Issue 2</td>
                        <td>Samantha C.</td>
                        <td><span class="status-badge status-draft">Draft</span></td>
                        <td style="padding-right: 24px; color: #64748b; font-weight: 500;">Apr 12 &bull; 12:15 PM</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 24px; border-bottom: none;">Library Extends Weekend Hours</td>
                        <td style="border-bottom: none;">AY 2025 - 2026 Issue 1</td>
                        <td style="border-bottom: none;">Alaissa N.</td>
                        <td style="border-bottom: none;"><span class="status-badge status-draft">Draft</span></td>
                        <td style="padding-right: 24px; border-bottom: none; color: #64748b; font-weight: 500;">Apr 16 &bull; 7:40 AM</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="pagination-container" style="display: flex; justify-content: center; align-items: center; padding-top: 20px; margin-top: 20px; border-top: 1px solid #f1f5f9; gap: 16px;">
            <button class="page-nav"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg > Previous</button>
            <div class="page-numbers" style="display: flex; gap: 8px;">
                <button class="page-num">1</button>
                <button class="page-num">2</button>
                <button class="page-num active">3</button>
                <button class="page-num">4</button>
                <button class="page-num">5</button>
                <span class="page-ellipsis">...</span>
            </div>
            <button class="page-nav">Next <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg ></button>
            
            <div class="showing-text" style="color: #64748b; font-size: 13px; margin-left: 16px;">
                Showing <b>12</b> of <b>2,137</b> articles
            </div>
        </div>
    </div>
</div>
<!-- </body> -->
    </div>
    <div v-show="activeTab === 'press-works'">
        <div style="display: flex; flex-direction: column; gap: 12px; flex-shrink: 0;">
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h1 class="page-title">Press Works</h1>
    </div>

    <div class="card" style="padding: 16px 24px;">
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
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg > Previous
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
            Next <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg >
        </button>
        
        <div class="showing-text" style="color: #64748b; font-size: 13px; margin-left: 16px;">
            Showing <b>4</b> Press Works
        </div>
    </div>
</div>
<!-- </body> -->
    </div>
    <div v-show="activeTab === 'assignments'">
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h1 class="page-title">Assignments</h1>
                <div class="filters" style="display: flex; gap: 12px;">
                    <button class="filter-btn">Status <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button>
                    <button class="filter-btn">Date Range <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button>
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
                                <th style="padding-right: 24px;">Priority</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr @click="openTaskModal({ title: 'Enrollment Update for Second Semester', section: 'News', deadline: 'Apr 15 • 5:00 PM', priority: 'Moderate' })" style="cursor: pointer;">
                                <td style="padding-left: 24px;">Enrollment Update for Second Semester</td>
                                <td><b>Gabrielle M. Loquias</b> <span class="avatar-badge" style="background: #1a73e8; color: white;">+2</span></td>
                                <td>Apr 15 &bull; 5:00 PM</td>
                                <td style="padding-right: 24px;"><span class="status-badge status-under-revision">Ongoing</span></td>
                            </tr>
                            <tr @click="openTaskModal({ title: 'Wellness Campaign Launch', section: 'Feature', deadline: 'Apr 12 • 5:00 PM', priority: 'Moderate' })" style="cursor: pointer;">
                                <td style="padding-left: 24px;">Wellness Campaign Launch</td>
                                <td><b>Alyssa Nolasco</b> <span class="avatar-badge" style="background: #1a73e8; color: white;">+1</span></td>
                                <td>Apr 12 &bull; 17:00</td>
                                <td style="padding-right: 24px;"><span class="status-badge status-under-revision">Ongoing</span></td>
                            </tr>
                            <tr @click="openTaskModal({ title: 'Student Election Coverage', section: 'News', deadline: 'Apr 12 • 5:00 PM', priority: 'Low' })" style="cursor: pointer;">
                                <td style="padding-left: 24px;">Student Election Coverage</td>
                                <td><b>Gabrielle M. Loquias</b> <span class="avatar-badge" style="background: #1a73e8; color: white;">+2</span></td>
                                <td>Apr 12 &bull; 17:00</td>
                                <td style="padding-right: 24px;"><span class="status-badge status-under-revision">Ongoing</span></td>
                            </tr>
                            <tr @click="openTaskModal({ title: 'Intramural Opening Highlights', section: 'Sports', deadline: 'Apr 12 • 5:00 PM', priority: 'High' })" style="cursor: pointer;">
                                <td style="padding-left: 24px; border-bottom: none;">Intramural Opening Highlights</td>
                                <td style="border-bottom: none;"><b>Samantha Ciscon</b> <span class="avatar-badge" style="background: #1a73e8; color: white;">+2</span></td>
                                <td style="border-bottom: none;">Apr 12 &bull; 17:00</td>
                                <td style="padding-right: 24px; border-bottom: none;"><span class="status-badge" style="background: #dcfce7; color: #15803d;">Submitted</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-pill">
                    <button class="page-nav" style="border: none; background: none; display: flex; align-items: center; gap: 4px; color: #64748b; font-weight: 500; cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Previous
                    </button>
                    <div class="page-numbers" style="display: flex; gap: 8px;">
                        <button class="page-num active" style="background: #1a73e8; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center;">1</button>
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
                        Showing <b>12</b> results
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-show="activeTab === 'submissions'">
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h1 class="page-title">Submissions</h1>
                <div class="filters" style="display: flex; gap: 12px;">
                    <button class="filter-btn">Status <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button>
                    <button class="filter-btn">Date Range <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button>
                </div>
            </div>

            <div class="card">
                <div class="table-wrapper">
                    <table class="activities-table articles-table">
                        <thead>
                            <tr>
                                <th style="padding-left: 24px;">Title</th>
                                <th>Submitted By</th>
                                <th>Submitted Date</th>
                                <th style="padding-right: 24px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr @click="openTaskModal({ title: 'Enrollment Update for Second Semester', section: 'News' })" style="cursor: pointer;">
                                <td style="padding-left: 24px;">Enrollment Update for Second Semester</td>
                                <td><b>Gabrielle M. Loquias</b> <span class="avatar-badge" style="background: #1a73e8; color: white;">+2</span></td>
                                <td>Apr 14 &bull; 10:00 PM</td>
                                <td style="padding-right: 24px;"><span class="status-badge status-for-review">For Review</span></td>
                            </tr>
                            <tr @click="openTaskModal({ title: 'Student Election Coverage', section: 'News' })" style="cursor: pointer;">
                                <td style="padding-left: 24px;">Student Election Coverage</td>
                                <td><b>Alyssa Nolasco</b> <span class="avatar-badge" style="background: #1a73e8; color: white;">+1</span></td>
                                <td>Apr 15 &bull; 1:00 PM</td>
                                <td style="padding-right: 24px;"><span class="status-badge status-for-review">For Review</span></td>
                            </tr>
                            <tr @click="openTaskModal({ title: 'Wellness Campaign Launch', section: 'Feature' })" style="cursor: pointer;">
                                <td style="padding-left: 24px;">Wellness Campaign Launch</td>
                                <td><b>Samantha Ciscon</b> <span class="avatar-badge" style="background: #1a73e8; color: white;">+2</span></td>
                                <td>Apr 15 &bull; 4:15 PM</td>
                                <td style="padding-right: 24px;"><span class="status-badge status-under-revision">Returned</span></td>
                            </tr>
                            <tr @click="openTaskModal({ title: 'The Rise of Campus Creatives', section: 'Feature' })" style="cursor: pointer;">
                                <td style="padding-left: 24px;">The Rise of Campus Creatives</td>
                                <td><b>Gabrielle M. Loquias</b> <span class="avatar-badge" style="background: #1a73e8; color: white;">+2</span></td>
                                <td>Apr 16 &bull; 9:00 AM</td>
                                <td style="padding-right: 24px;"><span class="status-badge status-endorsed">Endorsed</span></td>
                            </tr>
                            <tr @click="openTaskModal({ title: 'AI Tools in Education', section: 'News' })" style="cursor: pointer;">
                                <td style="padding-left: 24px;">AI Tools in Education</td>
                                <td><b>Karl Lumactod</b> <span class="avatar-badge" style="background: #1a73e8; color: white;">+1</span></td>
                                <td>Apr 16 &bull; 11:20 AM</td>
                                <td style="padding-right: 24px;"><span class="status-badge status-published">Published</span></td>
                            </tr>
                            <tr @click="openTaskModal({ title: 'AI Tools in Education', section: 'News' })" style="cursor: pointer;">
                                <td style="padding-left: 24px; border-bottom: none;">AI Tools in Education</td>
                                <td style="border-bottom: none;"><b>Vien Lacoste</b></td>
                                <td style="border-bottom: none;">Apr 16 &bull; 2:45 PM</td>
                                <td style="padding-right: 24px; border-bottom: none;"><span class="status-badge status-published">Published</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-pill">
                    <button class="page-nav" style="border: none; background: none; display: flex; align-items: center; gap: 4px; color: #64748b; font-weight: 500; cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Previous
                    </button>
                    <div class="page-numbers" style="display: flex; gap: 8px;">
                        <button class="page-num active" style="background: #1a73e8; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center;">1</button>
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
                        Showing <b>6</b> results
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-show="activeTab === 'contributors'">
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h1 class="page-title">Contributors</h1>
                <div class="filters" style="display: flex; gap: 12px;">
                    <button class="filter-btn">Status <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button>
                </div>
            </div>

            <div class="card">
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
                        <tbody>
                            <tr>
                                <td style="padding-left: 24px;">Gabrielle Loquias</td>
                                <td><span class="role-pill">News Writer</span></td>
                                <td style="color: #64748b; font-weight: 500;">gabloquias@my.cspc.edu.ph</td>
                                <td><span style="background: #93c5fd; color: #1e3a8a; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">3</span></td>
                                <td style="padding-right: 24px;"><span class="status-badge" style="background: #dcfce7; color: #15803d;">Active</span></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;">Vien Lacoste</td>
                                <td><span class="role-pill">News Writer</span></td>
                                <td style="color: #64748b; font-weight: 500;">vienlacoste@my.cspc.edu.ph</td>
                                <td><span style="background: #93c5fd; color: #1e3a8a; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">2</span></td>
                                <td style="padding-right: 24px;"><span class="status-badge" style="background: #dcfce7; color: #15803d;">Active</span></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;">Alaissa Nolasco</td>
                                <td><span class="role-pill">News Writer</span></td>
                                <td style="color: #64748b; font-weight: 500;">alnolasco@my.cspc.edu.ph</td>
                                <td><span style="background: #93c5fd; color: #1e3a8a; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">2</span></td>
                                <td style="padding-right: 24px;"><span class="status-badge" style="background: #dcfce7; color: #15803d;">Active</span></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;">Samantha Ciscon</td>
                                <td><span class="role-pill">News Writer</span></td>
                                <td style="color: #64748b; font-weight: 500;">samciscon@my.cspc.edu.ph</td>
                                <td><span style="background: #93c5fd; color: #1e3a8a; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">2</span></td>
                                <td style="padding-right: 24px;"><span class="status-badge" style="background: #dcfce7; color: #15803d;">Active</span></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;">Mark Marmol</td>
                                <td><span class="role-pill">Copyreader</span></td>
                                <td style="color: #64748b; font-weight: 500;">markmarmol@my.cspc.edu.ph</td>
                                <td><span style="background: #93c5fd; color: #1e3a8a; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">1</span></td>
                                <td style="padding-right: 24px;"><span class="status-badge status-draft">Available</span></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;">Jell Reginaldo</td>
                                <td><span class="role-pill">Copyreader</span></td>
                                <td style="color: #64748b; font-weight: 500;">jellreginaldo@my.cspc.edu.ph</td>
                                <td><span style="background: #93c5fd; color: #1e3a8a; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">2</span></td>
                                <td style="padding-right: 24px;"><span class="status-badge" style="background: #dcfce7; color: #15803d;">Active</span></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;">Johnrey Frongoso</td>
                                <td><span class="role-pill">Photojournalist</span></td>
                                <td style="color: #64748b; font-weight: 500;">jofrongoso@my.cspc.edu.ph</td>
                                <td><span style="background: #93c5fd; color: #1e3a8a; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">2</span></td>
                                <td style="padding-right: 24px;"><span class="status-badge" style="background: #dcfce7; color: #15803d;">Active</span></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;">Nicole Orcine</td>
                                <td><span class="role-pill">Layout Artist</span></td>
                                <td style="color: #64748b; font-weight: 500;">nicorcine@my.cspc.edu.ph</td>
                                <td><span style="background: #93c5fd; color: #1e3a8a; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">1</span></td>
                                <td style="padding-right: 24px;"><span class="status-badge status-draft">Available</span></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px; border-bottom: none;">Angelica Belano</td>
                                <td style="border-bottom: none;"><span class="role-pill">Graphic Artist</span></td>
                                <td style="border-bottom: none; color: #64748b; font-weight: 500;">angebelano@my.cspc.edu.ph</td>
                                <td style="border-bottom: none;"><span style="background: #93c5fd; color: #1e3a8a; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">1</span></td>
                                <td style="padding-right: 24px; border-bottom: none;"><span class="status-badge status-draft">Available</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-pill" style="margin: 24px auto; align-self: center;">
                    <button class="page-nav" style="border: none; background: none; display: flex; align-items: center; gap: 4px; color: #64748b; font-weight: 500; cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Previous
                    </button>
                    <div class="page-numbers" style="display: flex; gap: 8px;">
                        <button class="page-num active" style="background: #1a73e8; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center;">1</button>
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
                        Showing <b>9</b> results
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
        @view-assignments="activeTab = 'assignments'; openDropdown = 'workflow'" 
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
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import AssignTaskModal from '../../components/AssignTaskModal.vue';
import AssignedTaskModal from '../../components/AssignedTaskModal.vue';
import AssignmentWorkspaceModal from '../../components/AssignmentWorkspaceModal.vue';
import NotificationsPopover from '../../components/NotificationsPopover.vue';
import { signOut as performSignOut } from '../../utils/auth';

const router = useRouter();
const activeTab = ref('overview');
const openDropdown = ref(null);
const isAssignTaskModalOpen = ref(false);
const isAssignedTaskModalOpen = ref(false);
const isWorkspaceModalOpen = ref(false);
const selectedTask = ref({});
const seUser = ref(JSON.parse(localStorage.getItem('sparky_user') || '{}'));

const defaultSection = computed(() => {
    const secRole = seUser.value?.secondary_role || '';
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
    isWorkspaceModalOpen.value = true;
};

const openMonitoringSheet = () => {
    window.open('/monitoring-sheet', '_blank');
};
</script>
