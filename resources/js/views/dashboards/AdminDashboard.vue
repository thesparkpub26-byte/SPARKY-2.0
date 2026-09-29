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
                <a href="#" class="nav-item" @click.prevent="activeTab = 'overview'" :class="{ active: activeTab === 'overview' }">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        Overview
                    </div>
                </a>
                
                <a href="#" class="nav-item has-dropdown" @click.prevent="openDropdown = openDropdown === 'user-management' ? null : 'user-management'" :class="{ active: activeTab === 'user-management' || activeTab === 'editorial-board' || activeTab === 'staff-writers' || activeTab === 'readers' }">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        User Management
                    </div>
                    <!-- Chevron Down for collapsed -->
                    <svg class="chevron" :style="{ transform: openDropdown === 'user-management' ? 'rotate(180deg)' : 'rotate(0deg)', transition: 'transform 0.2s' }" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </a>
                
                <!-- Sub Menu -->
                <div class="sub-menu" v-show="openDropdown === 'user-management'" :class="{ open: openDropdown === 'user-management' }">
                    <a href="#" class="sub-item" @click.prevent="activeTab = 'editorial-board'; openDropdown = 'user-management'" :class="{ active: activeTab === 'user-management' || activeTab === 'editorial-board' }">
                        Editorial Board
                        <svg v-if="activeTab === 'user-management' || activeTab === 'editorial-board'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                    </a>
                    <a href="#" class="sub-item" @click.prevent="activeTab = 'staff-writers'; openDropdown = 'user-management'" :class="{ active: activeTab === 'staff-writers' }">
                        Staff Writers
                        <svg v-if="activeTab === 'staff-writers'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                    </a>
                    <a href="#" class="sub-item" @click.prevent="activeTab = 'readers'; openDropdown = 'user-management'" :class="{ active: activeTab === 'readers' }">
                        Readers
                        <svg v-if="activeTab === 'readers'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                    </a>
                </div>
                
                <a href="#" class="nav-item" @click.prevent="activeTab = 'articles'" :class="{ active: activeTab === 'articles' }">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Articles
                    </div>
                </a>
                
                <a href="#" class="nav-item" @click.prevent="activeTab = 'press-works'" :class="{ active: activeTab === 'press-works' }">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        Press Works
                    </div>
                </a>
                
                <a href="#" class="nav-item" @click.prevent="activeTab = 'archive'" :class="{ active: activeTab === 'archive' }">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
                        Archive
                    </div>
                </a>
                
                <a href="#" class="nav-item" @click.prevent="activeTab = 'analytics'" :class="{ active: activeTab === 'analytics' }">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        Analytics
                    </div>
                </a>
            </nav>

            <div class="user-profile">
                <img :src="adminUser.profile_picture_url || 'https://picsum.photos/200?random=1'" alt="Profile">
                <div class="user-info">
                    <span class="role-badge">{{ adminUser.role || 'admin' }}</span>
                    <h4>{{ adminUser.name || 'Administrator' }}</h4>
                    <p>{{ adminUser.email || 'admin@sparky.com' }}</p>
                </div>
                <button class="settings-btn" type="button" aria-label="Open administrator profile" @click="router.push('/profile')">
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
            <header class="top-header">
                <MobileNavToggle />
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input v-model="searchInput" type="search" :placeholder="searchPlaceholder" aria-label="Search current view">
                </div>
                <div class="top-header-right" style="display: flex; align-items: center; gap: 16px;">
                    <button v-if="activeTab !== 'press-works'" class="new-user-btn" type="button" @click="openNewUserModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        New User
                    </button>
                    <button v-else class="new-user-btn" type="button" @click="openPressworkModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Add Presswork
                    </button>
                </div>
            </header>

            <!-- Overview Section -->
            <section id="section-overview" class="content-section" :class="{ active: activeTab === 'overview' }" v-show="activeTab === 'overview'">
                <div class="page-header">
                <h1 class="page-title">Overview</h1>
            </div>

            <div class="overview-grid">
                <!-- System Summary -->
                <div class="card">
                    <h3 class="card-header">System Summary</h3>
                    <div class="metric-grid">
                        <div class="metric-box blue">
                            <div class="metric-value">{{ formatCount(overview.summary.articles) }}</div>
                            <div class="metric-label">Total Articles</div>
                            <div class="metric-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none">
                                    <path d="M20 10V18C20 19.8856 20 20.8284 19.4142 21.4142C18.8284 22 17.8856 22 16 22H8C6.11438 22 5.17157 22 4.58579 21.4142C4 20.8284 4 19.8856 4 18V6C4 4.11438 4 3.17157 4.58579 2.58579C5.17157 2 6.11438 2 8 2H12C12.9428 2 13.4142 2 13.7929 2.19526C14.1716 2.39052 14.5052 2.72412 15.1725 3.39132L18.6087 6.82748C19.2759 7.49472 19.6095 7.82834 19.8047 8.20711C20 8.58588 20 9.05719 20 10Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                                    <path d="M8 12H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8 16H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="metric-box users">
                            <div class="metric-value">{{ formatCount(overview.summary.users) }}</div>
                            <div class="metric-label">Total Users</div>
                            <div class="metric-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none">
                                    <path d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" stroke="currentColor" stroke-width="1.5" />
                                    <path d="M12 14C10.067 14 8.5 12.433 8.5 10.5C8.5 8.567 10.067 7 12 7C13.933 7 15.5 8.567 15.5 10.5C15.5 12.433 13.933 14 12 14Z" stroke="currentColor" stroke-width="1.5" />
                                    <path d="M6 19.5C6 17.567 7.567 16 9.5 16H14.5C16.433 16 18 17.567 18 19.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="metric-box light">
                            <div class="metric-value">{{ formatCount(overview.summary.pending) }}</div>
                            <div class="metric-label">Pending</div>
                            <div class="metric-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none">
                                    <path d="M6 2H18C18 2 18 3.5 18 6C18 9.31371 15.3137 12 12 12C8.68629 12 6 9.31371 6 6C6 3.5 6 2 6 2Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                                    <path d="M12 12C15.3137 12 18 14.6863 18 18C18 20.5 18 22 18 22H6C6 22 6 20.5 6 18C6 14.6863 8.68629 12 12 12Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                                    <path d="M6 2H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6 22H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10.5 17.5H13.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="metric-box dark">
                            <div class="metric-value">{{ formatCount(overview.summary.published) }}</div>
                            <div class="metric-label">Published</div>
                            <div class="metric-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none">
                                    <path d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" stroke="currentColor" stroke-width="1.5" />
                                    <path d="M8 12.5L10.5 15L16 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="updated-text">{{ updatedLabel }}</p>
                </div>

                <!-- Right Column: User Activity & Workflow Status -->
                <div class="right-cards">
                    <!-- User Activity -->
                    <div class="card">
                        <h3 class="card-header">User Activity</h3>
                        <div class="sub-card-container">
                            <div class="sub-card">
                                <p class="sub-card-title">Active Now</p>
                                <div class="avatar-stack">
                                    <img v-for="member in overview.user_activity.active.slice(0, 4)" :key="`active-${member.id}`" :src="member.profile_picture_url || avatarFallback(member.name)" :alt="member.name">
                                    <div class="avatar-more">+ {{ Math.max(overview.user_activity.active_count - overview.user_activity.active.slice(0, 4).length, 0) }}</div>
                                </div>
                            </div>
                            <div class="sub-card">
                                <p class="sub-card-title">New Users</p>
                                <div class="avatar-stack">
                                    <img v-for="member in overview.user_activity.new.slice(0, 4)" :key="`new-${member.id}`" :src="member.profile_picture_url || avatarFallback(member.name)" :alt="member.name">
                                    <div class="avatar-more">+ {{ Math.max(overview.user_activity.new_count - overview.user_activity.new.slice(0, 4).length, 0) }}</div>
                                </div>
                            </div>
                        </div>
                        <p class="updated-text">{{ updatedLabel }}</p>
                    </div>

                    <!-- Workflow Status Summary -->
                    <div class="card">
                        <h3 class="card-header">Workflow Status Summary</h3>
                        <div class="sub-card-container">
                            <div class="sub-card" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p class="sub-card-title" style="margin-bottom: 4px;">Submitted</p>
                                    <div class="workflow-value">{{ overview.workflow.submitted }}</div>
                                </div>
                                <div class="metric-icon" style="position: static; background-color: #e2e8f0; color: #555;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                </div>
                            </div>
                            <div class="sub-card" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p class="sub-card-title" style="margin-bottom: 4px;">In Review</p>
                                    <div class="workflow-value">{{ overview.workflow.in_review }}</div>
                                </div>
                                <div class="metric-icon" style="position: static; background-color: #111; color: white;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                </div>
                            </div>
                        </div>
                        <p class="updated-text">{{ updatedLabel }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Activities Table -->
            <div class="card">
                <h3 class="card-header">Recent Activities</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Activities</th>
                                <th>User</th>
                                <th>Role</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="activity in filteredOverviewActivities" :key="activity.id">
                                <td>{{ activity.action }}<span v-if="activity.subject">: {{ activity.subject }}</span></td>
                                <td>{{ activity.user }}</td>
                                <td><span class="role-pill">{{ formatRole(activity.role) }}</span></td>
                                <td>{{ formatDate(activity.created_at) }}</td>
                            </tr>
                            <tr v-if="!filteredOverviewActivities.length">
                                <td colspan="4" class="empty-activity">No activity has been recorded yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </section>

            <!-- User-management Section -->
            <section id="section-user-management" class="content-section" :class="{ active: activeTab === 'user-management' || activeTab === 'editorial-board' || activeTab === 'staff-writers' || activeTab === 'readers' }" v-show="activeTab === 'user-management' || activeTab === 'editorial-board' || activeTab === 'staff-writers' || activeTab === 'readers'">
                <div class="page-header" style="align-items: center;">
                <h1 class="page-title">{{ activeTab === 'staff-writers' ? 'Staff Writers' : activeTab === 'readers' ? 'Readers' : activeTab === 'editorial-board' ? 'Editorial Board' : 'User Management' }}</h1>
                <div class="filters admin-user-filters">
                    <div v-if="activeTab !== 'readers'" class="custom-filter-dropdown admin-user-filter section-filter" @click.stop>
                        <button type="button" class="custom-filter-trigger" @click="toggleManagementDropdown('section')">
                            <span>{{ managementSection === 'all' ? 'All Sections' : managementSection }}</span>
                            <svg :class="{ rotated: activeManagementDropdown === 'section' }" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6" /></svg>
                        </button>
                        <div v-if="activeManagementDropdown === 'section'" class="custom-filter-menu">
                            <button type="button" :class="{ selected: managementSection === 'all' }" @click="selectManagementFilter('section', 'all')">All Sections</button>
                            <button v-for="sec in managementSectionOptions" :key="sec" type="button" :class="{ selected: managementSection === sec }" @click="selectManagementFilter('section', sec)">{{ sec }}</button>
                        </div>
                    </div>
                    <div v-if="activeTab === 'user-management' || activeTab === 'staff-writers'" class="custom-filter-dropdown admin-user-filter role-filter" @click.stop>
                        <button type="button" class="custom-filter-trigger" @click="toggleManagementDropdown('role')">
                            <span>{{ managementRole === 'all' ? 'All Roles' : formatRole(managementRole) }}</span>
                            <svg :class="{ rotated: activeManagementDropdown === 'role' }" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6" /></svg>
                        </button>
                        <div v-if="activeManagementDropdown === 'role'" class="custom-filter-menu">
                            <button type="button" :class="{ selected: managementRole === 'all' }" @click="selectManagementFilter('role', 'all')">All Roles</button>
                            <button v-for="role in managementRoles" :key="role" type="button" :class="{ selected: managementRole === role }" @click="selectManagementFilter('role', role)">{{ formatRole(role) }}</button>
                        </div>
                    </div>
                    <div class="custom-filter-dropdown admin-user-filter sort-filter" @click.stop>
                        <button type="button" class="custom-filter-trigger" @click="toggleManagementDropdown('sort')">
                            <span>{{ managementSortLabels[managementSort] }}</span>
                            <svg :class="{ rotated: activeManagementDropdown === 'sort' }" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6" /></svg>
                        </button>
                        <div v-if="activeManagementDropdown === 'sort'" class="custom-filter-menu">
                            <button v-for="(label, value) in managementSortLabels" :key="value" type="button" :class="{ selected: managementSort === value }" @click="selectManagementFilter('sort', value)">{{ label }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card" style="flex: 1; padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                <div class="table-container" style="flex: 1; padding: 24px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th v-if="activeTab !== 'readers'">Section</th>
                                <th v-if="activeTab !== 'readers'">Program</th>
                                <th v-if="activeTab !== 'readers'">Year/Section</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="['user-management', 'editorial-board', 'staff-writers', 'readers'].includes(activeTab)">
                            <tr v-for="member in paginatedManagementUsers" :key="member.id">
                                <td>{{ member.name }}</td>
                                <td>{{ member.email }}</td>
                                <td><span class="role-pill">{{ formatRole(member.role) }}</span></td>
                                <td v-if="activeTab !== 'readers'">
                                    <span v-if="member.secondary_role" class="section-badge" style="background-color: #dbeafe; color: #1e40af; font-weight: 600; padding: 4px 10px; border-radius: 999px; font-size: 12px; display: inline-block;">{{ member.secondary_role }}</span>
                                    <span v-if="member.tertiary_role" class="section-badge" style="background-color: #ede9fe; color: #5b21b6; font-weight: 600; padding: 4px 10px; border-radius: 999px; font-size: 12px; display: inline-block; margin-left: 4px;">{{ member.tertiary_role }}</span>
                                    <span v-if="!member.secondary_role && !member.tertiary_role" style="color: #94a3b8;">—</span>
                                </td>
                                <td v-if="activeTab !== 'readers'">{{ member.program || '—' }}</td>
                                <td v-if="activeTab !== 'readers'">{{ member.year_section || '—' }}</td>
                                <td>{{ formatDate(member.created_at) }}</td>
                                <td>
                                    <div class="action-icons">
                                        <button class="action-btn edit" type="button" aria-label="Edit user" @click="openEditUser(member)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>
                                        </button>
                                        <button class="action-btn delete" type="button" aria-label="Delete user" @click="openDeleteUser(member)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 1 2 1 2v2"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </template>
                            <tr v-if="['user-management', 'editorial-board', 'staff-writers', 'readers'].includes(activeTab) && !filteredManagementUsers.length">
                                <td :colspan="activeTab === 'readers' ? 5 : 8" class="empty-activity">No matching users found.</td>
                            </tr>
                            <tr v-show="false">
                                <td>Fernan Matthew A. Enimedez</td>
                                <td>fenimedez@my.cspc.edu.ph</td>
                                <td><span class="role-pill">Editor-in-Chief</span></td>
                                <td><span class="role-pill"
                                        style="background-color: #b9d5ff; color: #1a73e8;">BSBA-FM</span></td>
                                <td>3C</td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <div class="action-icons">
                                        <button class="action-btn edit"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                <path d="m15 5 4 4"></path>
                                            </svg></button>
                                        <button class="action-btn delete"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            </svg></button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-show="false">
                                <td>Dustin Jake E. Nas</td>
                                <td>dujanas@my.cspc.edu.ph</td>
                                <td><span class="role-pill">Copy Editor</span></td>
                                <td><span class="role-pill"
                                        style="background-color: #b9d5ff; color: #1a73e8;">BSIT</span></td>
                                <td>2A</td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <div class="action-icons">
                                        <button class="action-btn edit"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                <path d="m15 5 4 4"></path>
                                            </svg></button>
                                        <button class="action-btn delete"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            </svg></button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-show="false">
                                <td>Emher Kenji A. Turiano</td>
                                <td>emturiano@my.cspc.edu.ph</td>
                                <td><span class="role-pill">Video Editor</span></td>
                                <td><span class="role-pill"
                                        style="background-color: #b9d5ff; color: #1a73e8;">BSIT</span></td>
                                <td>3C</td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <div class="action-icons">
                                        <button class="action-btn edit"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                <path d="m15 5 4 4"></path>
                                            </svg></button>
                                        <button class="action-btn delete"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            </svg></button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-show="false">
                                <td>Johan Abinal</td>
                                <td>jabinal@my.cspc.edu.ph</td>
                                <td><span class="role-pill">Sports Writer</span></td>
                                <td><span class="role-pill"
                                        style="background-color: #b9d5ff; color: #1a73e8;">BPED</span></td>
                                <td>3C</td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <div class="action-icons">
                                        <button class="action-btn edit"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                <path d="m15 5 4 4"></path>
                                            </svg></button>
                                        <button class="action-btn delete"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            </svg></button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-show="false">
                                <td>Gabrielle M. Loquias</td>
                                <td>gloquias@my.cspc.edu.ph</td>
                                <td><span class="role-pill">Feature Writer</span></td>
                                <td><span class="role-pill"
                                        style="background-color: #b9d5ff; color: #1a73e8;">BSCS</span></td>
                                <td>3C</td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <div class="action-icons">
                                        <button class="action-btn edit"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                <path d="m15 5 4 4"></path>
                                            </svg></button>
                                        <button class="action-btn delete"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            </svg></button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-show="false">
                                <td>Alaissa Nolasco</td>
                                <td>alnolasco@my.cspc.edu.ph</td>
                                <td><span class="role-pill">Layout Artist</span></td>
                                <td><span class="role-pill"
                                        style="background-color: #b9d5ff; color: #1a73e8;">BLIS</span></td>
                                <td>3C</td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <div class="action-icons">
                                        <button class="action-btn edit"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                <path d="m15 5 4 4"></path>
                                            </svg></button>
                                        <button class="action-btn delete"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            </svg></button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-show="false">
                                <td>Samantha Ciscon</td>
                                <td>samciscon@my.cspc.edu.ph</td>
                                <td><span class="role-pill">Copyreader</span></td>
                                <td><span class="role-pill"
                                        style="background-color: #b9d5ff; color: #1a73e8;">BSBA-FM</span></td>
                                <td>3C</td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <div class="action-icons">
                                        <button class="action-btn edit"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                <path d="m15 5 4 4"></path>
                                            </svg></button>
                                        <button class="action-btn delete"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            </svg></button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-show="false">
                                <td>Rica Subang</td>
                                <td>rsubang@my.cspc.edu.ph</td>
                                <td><span class="role-pill" style="background-color: #f1f5f9; color: #475569;">Reader</span></td>
                                <td><span class="role-pill"
                                        style="background-color: #b9d5ff; color: #1a73e8;">BSBA-FM</span></td>
                                <td>3C</td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <div class="action-icons">
                                        <button class="action-btn edit"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                <path d="m15 5 4 4"></path>
                                            </svg></button>
                                        <button class="action-btn delete"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            </svg></button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-show="false">
                                <td>Angelica Belano</td>
                                <td>angebelano@my.cspc.edu.ph</td>
                                <td><span class="role-pill" style="background-color: #f1f5f9; color: #475569;">Reader</span></td>
                                <td><span class="role-pill"
                                        style="background-color: #b9d5ff; color: #1a73e8;">BSBA</span></td>
                                <td>1A</td>
                                <td>Apr 5, 2026 10:15:00</td>
                                <td>
                                    <div class="action-icons">
                                        <button class="action-btn edit"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                <path d="m15 5 4 4"></path>
                                            </svg></button>
                                        <button class="action-btn delete"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            </svg></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-container">
                    <div v-if="['user-management', 'editorial-board', 'staff-writers', 'readers'].includes(activeTab)" class="pagination-pill">
                        <button class="page-btn" :disabled="managementPage === 1" @click="managementPage--">Previous</button>
                        <button v-for="page in managementPageCount" :key="page" class="page-number" :class="{ active: managementPage === page }" @click="managementPage = page">{{ page }}</button>
                        <button class="page-btn" :disabled="managementPage === managementPageCount" @click="managementPage++">Next</button>
                        <div class="page-results-count">Showing <strong>{{ paginatedManagementUsers.length }}</strong> of <strong>{{ filteredManagementUsers.length }}</strong> users</div>
                    </div>
                    <div v-else class="pagination-pill">
                        <button class="page-btn">Previous</button>
                        <button class="page-number">1</button>
                        <button class="page-number">2</button>
                        <button class="page-number active">3</button>
                        <button class="page-number">4</button>
                        <button class="page-number">5</button>
                        <span class="page-dots">&bull;&bull;&bull;</span>
                        <button class="page-btn">Next</button>
                        <div class="page-results-count">Showing <strong>12</strong> of <strong>61</strong> users</div>
                    </div>
                </div>
            </div>
            </section>

            <!-- Articles Section -->
            <section id="section-articles" class="content-section" :class="{ active: activeTab === 'articles' }" v-show="activeTab === 'articles'">
                <div class="page-header" style="align-items: center;">
                <h1 class="page-title">Articles</h1>
                <div class="filters admin-article-filters">
                    <div v-for="filter in articleFilterDefinitions" :key="filter.key" class="custom-filter-dropdown article-filter" @click.stop>
                        <button type="button" class="custom-filter-trigger" @click="toggleArticleDropdown(filter.key)">
                            <span>{{ articleFilterLabel(filter.key) }}</span>
                            <svg :class="{ rotated: activeArticleDropdown === filter.key }" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6" /></svg>
                        </button>
                        <div v-if="activeArticleDropdown === filter.key" class="custom-filter-menu">
                            <button v-for="option in filter.options" :key="option.value" type="button" :class="{ selected: articleFilters[filter.key] === option.value }" @click="selectArticleFilter(filter.key, option.value)">{{ option.label }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card" style="flex: 1; padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                <div class="table-container" style="flex: 1; padding: 24px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Section</th>
                                <th>Writer</th>
                                <th>Status</th>
                                <th>Date & Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="article in paginatedArticles" :key="article.id">
                                <td>{{ article.title }}</td>
                                <td><span class="section-pill">{{ article.section?.name || 'Unassigned' }}</span></td>
                                <td>{{ article.author?.name || 'Unknown' }}</td>
                                <td><span class="status-pill" :class="articleStatusClass(article.status)">{{ articleStatusLabel(article.status) }}</span></td>
                                <td>{{ formatDate(article.created_at) }}</td>
                                <td><div class="action-icons"><button v-if="!article.archive_kind" class="action-btn edit" type="button" aria-label="Edit article" @click="openEditArticle(article)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg></button><button v-if="!article.archive_kind" class="action-btn delete" type="button" aria-label="Delete article" @click="openDeleteArticle(article)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg></button></div></td>
                            </tr>
                            <tr v-if="!filteredArticles.length"><td colspan="6" class="empty-activity">No articles found.</td></tr>
                        </tbody>
                        <tbody v-if="false">
                            <tr>
                                <td>Enrollment Update</td>
                                <td><span class="section-pill">Sports</span></td>
                                <td>AY '25 - 26 Issue 1</td>
                                <td>Hanna Grace C.</td>
                                <td><span class="status-pill published">Published</span></td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Campus Creatives</td>
                                <td><span class="section-pill">Feature</span></td>
                                <td>AY '25 - 26 Issue 1</td>
                                <td>Gabrielle L.</td>
                                <td><span class="status-pill published">Published</span></td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td>CSPC Launches New...</td>
                                <td><span class="section-pill">News</span></td>
                                <td>AY '25 - 26 Issue 1</td>
                                <td>Jhea Nicole N.</td>
                                <td><span class="status-pill published">Published</span></td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Beyond the Classroom...</td>
                                <td><span class="section-pill">Feature</span></td>
                                <td>AY '25 - 26 Issue 1</td>
                                <td>Sybil Jeky C.</td>
                                <td><span class="status-pill draft">Draft</span></td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td>AI in Education</td>
                                <td><span class="section-pill">Sci & Tech</span></td>
                                <td>Tech & Innovation '26</td>
                                <td>Adrian D.</td>
                                <td><span class="status-pill revision">Under Revision</span></td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Student Journalism</td>
                                <td><span class="section-pill">Opinion</span></td>
                                <td>Student Voice S.</td>
                                <td>Ma. Rosezhen S.</td>
                                <td><span class="status-pill draft">Draft</span></td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Sportsmanship</td>
                                <td><span class="section-pill">Sports</span></td>
                                <td>Sports Coverage '26</td>
                                <td>Denise I.</td>
                                <td><span class="status-pill approved">Approved</span></td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Sa Likod ng Tinta</td>
                                <td><span class="section-pill">Literary</span></td>
                                <td>Literary Collection '26</td>
                                <td>Gerald C.</td>
                                <td><span class="status-pill published">Published</span></td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td>The Weight of Silence</td>
                                <td><span class="section-pill">Editorial</span></td>
                                <td>Student Voice S.</td>
                                <td>Marc Nixie E.</td>
                                <td><span class="status-pill revision">Under Revision</span></td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Scholarship Program</td>
                                <td><span class="section-pill">News</span></td>
                                <td>Academic Programs</td>
                                <td>Vien L.</td>
                                <td><span class="status-pill review">For Review</span></td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Free Wi-Fi Expansion</td>
                                <td><span class="section-pill">Sci & Tech</span></td>
                                <td>Tech & Innovation '26</td>
                                <td>Chenelene B.</td>
                                <td><span class="status-pill draft">Draft</span></td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Sa Gitna ng Katahimikan</td>
                                <td><span class="section-pill">Literary</span></td>
                                <td>Literary Collection '26</td>
                                <td>Gerald C.</td>
                                <td><span class="status-pill draft">Draft</span></td>
                                <td>Apr 4, 2026 17:07:42</td>
                                <td>
                                    <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-container">
                    <div class="pagination-pill">
                        <button class="page-btn" :disabled="articlePage === 1" @click="articlePage--">Previous</button>
                        <button v-for="page in articlePageCount" :key="page" class="page-number" :class="{ active: articlePage === page }" @click="articlePage = page">{{ page }}</button>
                        <button class="page-btn" :disabled="articlePage === articlePageCount" @click="articlePage++">Next</button>
                        <div class="page-results-count">Showing <strong>{{ paginatedArticles.length }}</strong> of <strong>{{ filteredArticles.length }}</strong> articles</div>
                    </div>
                </div>
                <div v-if="false" class="pagination-container">
                    <div class="pagination-pill">
                        <button class="page-btn">Previous</button>
                        <button class="page-number">1</button>
                        <button class="page-number">2</button>
                        <button class="page-number active">3</button>
                        <button class="page-number">4</button>
                        <button class="page-number">5</button>
                        <span class="page-dots">&bull;&bull;&bull;</span>
                        <button class="page-btn">Next</button>
                        <div class="page-results-count">Showing <strong>12</strong> of <strong>2,137</strong> articles</div>
                    </div>
                </div>
            </div>
            </section>

            <!-- Press Works Section -->
            <section id="section-press-works" class="content-section" :class="{ active: activeTab === 'press-works' }" v-show="activeTab === 'press-works'">
                <div class="page-header" style="align-items: center; margin-bottom: 20px;">
                    <h1 class="page-title">Press Works</h1>
                    <button class="new-user-btn" type="button" @click="openPressworkModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        New Academic Year
                    </button>
                </div>

                <div class="card" style="padding: 24px; background: #ffffff; border-radius: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);">
                    <div v-if="academicYears.length === 0" class="empty-activity" style="text-align: center; padding: 40px;">
                        No academic years found. Click "New Academic Year" to create one.
                    </div>
                    
                    <div v-else-if="filteredAcademicYears.length" class="academic-years-container">
                        <div v-for="yearGroup in filteredAcademicYears" :key="yearGroup.academic_year" class="academic-year-folder">
                            <div class="folder-header" @click="toggleYear(yearGroup.academic_year)">
                                <svg class="folder-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <span class="folder-title">{{ yearGroup.academic_year }}</span>
                                <span class="folder-count">{{ yearGroup.monitoring_sheets.length }} Monitoring Sheet{{ yearGroup.monitoring_sheets.length !== 1 ? 's' : '' }}</span>
                                <button class="delete-year-btn" type="button" @click.stop="openDeleteYearModal(yearGroup.academic_year)" title="Delete Academic Year">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                                <svg class="chevron-icon" :style="{ transform: expandedYears[yearGroup.academic_year] ? 'rotate(180deg)' : 'rotate(0deg)' }" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                            
                            <div v-show="expandedYears[yearGroup.academic_year]" class="folder-content">
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
            </section>

            <!-- Archive Section -->
            <section id="section-archive" class="content-section" :class="{ active: activeTab === 'archive' }" v-show="activeTab === 'archive'">
                <div class="page-header" style="align-items: center;">
                <h1 class="page-title">Archive</h1>
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

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-pill">
                    <button class="page-btn" :disabled="archivePage === 1" @click="archivePage--">Previous</button>
                    <button v-for="page in archivePageNumbers" :key="page" class="page-number" :class="{ active: archivePage === page }" @click="archivePage = page">{{ page }}</button>
                    <span v-if="archivePageCount > 5 && archivePage < archivePageCount - 1" class="page-dots">&bull;&bull;&bull;</span>
                    <button class="page-btn" :disabled="archivePage === archivePageCount" @click="archivePage++">Next</button>
                    <div class="page-results-count">Showing <strong>{{ paginatedArchiveFolders.length }}</strong> of <strong>{{ filteredArchiveFolders.length }}</strong> folders</div>
                </div>
            </div>
            </section>

            <section id="section-archive-year" class="content-section" :class="{ active: activeTab === 'archive-year' }" v-show="activeTab === 'archive-year'">
                <div class="page-header" style="align-items: center; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <button class="btn-back" type="button" @click="closeArchiveFolder" style="padding: 0; color: #1a73e8; font-size: 14px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            Back
                        </button>
                        <h1 class="page-title" style="margin: 0;">{{ selectedArchiveFolder?.label || 'Archive' }}</h1>
                    </div>
                </div>

                <div class="card" style="flex: 1; padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                    <div class="table-container" style="flex: 1; padding: 24px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Section</th>
                                    <th>Writer</th>
                                    <th>Status</th>
                                    <th>Date & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="article in paginatedArchiveArticles" :key="article.id">
                                    <td>{{ article.title }}</td>
                                    <td><span class="section-pill">{{ article.section?.name || 'Unassigned' }}</span></td>
                                    <td>{{ article.author?.name || 'Unknown' }}</td>
                                    <td><span class="status-pill" :class="articleStatusClass(article.status)">{{ articleStatusLabel(article.status) }}</span></td>
                                    <td>{{ formatDate(article.created_at) }}</td>
                                    <td>
                                        <div class="action-icons">
                                            <button class="action-btn edit" type="button" aria-label="View" @click="openArchiveItem(article)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg></button>
                                            <button class="action-btn edit" type="button" aria-label="Edit article" @click="openEditArticle(article)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg></button>
                                            <button class="action-btn delete" type="button" aria-label="Delete article" @click="openDeleteArticle(article)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!selectedArchiveArticles.length"><td colspan="6" class="empty-activity">No articles found for this academic year.</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-container">
                        <div class="pagination-pill">
                            <button class="page-btn" :disabled="archiveYearPage === 1" @click="archiveYearPage--">Previous</button>
                            <button v-for="page in archiveYearPageCount" :key="page" class="page-number" :class="{ active: archiveYearPage === page }" @click="archiveYearPage = page">{{ page }}</button>
                            <button class="page-btn" :disabled="archiveYearPage === archiveYearPageCount" @click="archiveYearPage++">Next</button>
                            <div class="page-results-count">Showing <strong>{{ paginatedArchiveArticles.length }}</strong> of <strong>{{ selectedArchiveArticles.length }}</strong> articles</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Analytics Section -->
            <section id="section-analytics" class="content-section" :class="{ active: activeTab === 'analytics' }" v-show="activeTab === 'analytics'">
                <div class="page-header" style="align-items: center;">
                <h1 class="page-title">Analytics</h1>
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
                                <tr v-for="item in recentPublications" :key="item.key">
                                    <td style="border-bottom: none;">{{ item.title }}</td>
                                    <td style="border-bottom: none;"><span class="section-pill">{{ item.category }}</span></td>
                                    <td style="border-bottom: none;">{{ formatDate(item.date) }}</td>
                                    <td style="border-bottom: none; font-weight: 600;">{{ item.views === null ? '—' : formatCount(item.views) }}</td>
                                </tr>
                                <tr v-if="!recentPublications.length"><td colspan="4" class="empty-activity">Nothing has been published yet.</td></tr>
                            </tbody>
                        </table>
</div>
                    </div>
                    
                </div>

                <!-- Right Column -->
                <div class="right-col" style="display: flex; flex-direction: column; gap: 24px;">
                    
                    <!-- Engagement Metrics -->
                    <div class="card">
                        <h3 class="card-header">Engagement Metrics</h3>
                        <div class="engagement-grid">
                            <div class="engagement-box">
                                <div class="engagement-label">Total Views</div>
                                <div class="engagement-value">{{ formatCount(analytics.metrics.page_views) }}</div>
                            </div>
                            <div class="engagement-box">
                                <div class="engagement-label">Tracked Visitors</div>
                                <div class="engagement-value">{{ formatCount(analytics.metrics.sessions) }}</div>
                            </div>
                            <div class="engagement-box">
                                <div class="engagement-label">Unique Visitors</div>
                                <div class="engagement-value">{{ formatCount(analytics.metrics.active_users) }}</div>
                            </div>
                            <div class="engagement-box">
                                <div class="engagement-label">Published Articles</div>
                                <div class="engagement-value">{{ formatCount(articles.filter(article => article.status === 'published').length) }}</div>
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
                                <div class="status-count">{{ formatCount(articleStatusCounts.draft || 0) }}</div>
                            </div>
                            <div class="status-list-item">
                                <span>In Review</span>
                                <div class="status-count">{{ formatCount((articleStatusCounts.submitted || 0) + (articleStatusCounts.under_review || 0) + (articleStatusCounts.endorsed || 0)) }}</div>
                            </div>
                            <div class="status-list-item">
                                <span>Under Revision</span>
                                <div class="status-count">{{ formatCount(articleStatusCounts.rejected || 0) }}</div>
                            </div>
                            <div class="status-list-item">
                                <span>Approved</span>
                                <div class="status-count">{{ formatCount(articleStatusCounts.approved || 0) }}</div>
                            </div>
                            <div class="status-list-item">
                                <span>Published</span>
                                <div class="status-count">{{ formatCount(articleStatusCounts.published || 0) }}</div>
                            </div>
                        </div>
                    </div>

                    <button class="filter-dropdown">
                        Last 30 days
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                </div>
            </div>

            </section>

        </main>
    </div>

    <!-- Add New User Modal -->
    <div v-if="isNewUserModalOpen" class="new-user-modal-overlay" @click.self="closeNewUserModal">
        <div class="new-user-modal" role="dialog" aria-modal="true" aria-labelledby="new-user-modal-title">
            <div class="new-user-modal-header">
                <h2 id="new-user-modal-title">Add New User</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="closeNewUserModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <!-- Step 1 -->
            <div v-if="newUserStep === 1" class="new-user-step">
                <div class="form-group">
                    <label class="form-label">Display Picture</label>
                    <div class="upload-box">
                        <div class="upload-circle" :class="{ 'has-preview': newUserImagePreview }">
                            <img v-if="newUserImagePreview" :src="newUserImagePreview" alt="Uploaded profile preview">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        </div>
                        <div class="upload-info">
                            <p>Choose a file or drag & drop it here.<br>jpeg, png - Up to 10MB</p>
                            <input ref="newUserFileInput" type="file" accept="image/jpeg,image/png" hidden @change="handleNewUserImage">
                            <button class="btn-upload" type="button" @click="newUserFileInput?.click()">Upload image <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 2px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="7" y2="8"></line></svg></button>
                        </div>
                    </div>
                </div>

                <div v-if="newUserForm.role !== 'reader'" class="form-row">
                    <div class="form-group">
                        <label class="form-label">Program</label>
                        <input v-model="newUserForm.program" type="text" class="form-control" placeholder="Program">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Year/Section</label>
                        <input v-model="newUserForm.year_section" type="text" class="form-control" placeholder="Year/Section">
                    </div>
                </div>

                <div class="form-group new-user-username-group">
                    <label class="form-label">Username</label>
                    <input v-model="newUserForm.name" type="text" class="form-control" placeholder="Username">
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input v-model="newUserForm.email" type="email" class="form-control" placeholder="handle@my.cspc.edu.ph">
                </div>

                <div class="modal-footer">
                    <button class="btn-back" type="button" @click="closeNewUserModal"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Back</button>
                    <button class="btn-next" type="button" :disabled="!isNewUserStep1Valid" @click="newUserStep = 2">Next</button>
                </div>
            </div>

            <!-- Step 2 -->
            <div v-else-if="newUserStep === 2" class="new-user-step">
                <div class="form-group">
                    <label class="form-label">Account Details</label>
                    <div class="form-row">
                        <div class="form-group">
                            <div class="input-icon-wrap custom-select-wrap">
                                <select v-model="newUserForm.role" class="form-control select-control" @change="newUserForm.secondary_role = ''; newUserForm.tertiary_role = ''"><option value="" disabled>Role</option><option value="eic">Editor in Chief</option><option value="section_editor">Section Editor</option><option value="staff_writer">Staff Writer</option><option value="staff_artist">Staff Artist</option><option value="staff_broadcaster">Staff Broadcaster</option><option value="reader">Reader</option></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon-wrap custom-select-wrap">
                                <select v-model="newUserForm.status" class="form-control select-control"><option value="" disabled>Status</option><option value="active">Active</option><option value="inactive">Inactive</option></select>
                            </div>
                        </div>
                    </div>
                    <div v-if="SECONDARY_ROLES[newUserForm.role]" class="form-group" style="margin-top: 12px;">
                        <div class="input-icon-wrap custom-select-wrap">
                            <select v-model="newUserForm.secondary_role" class="form-control select-control" @change="newUserForm.tertiary_role = ''">
                                <option value="">Section / Secondary Role (Optional)</option>
                                <option v-for="secRole in SECONDARY_ROLES[newUserForm.role]" :key="secRole" :value="secRole">{{ secRole }}</option>
                            </select>
                        </div>
                    </div>
                    <div v-if="['section_editor', 'eic'].includes(newUserForm.role) && newUserForm.secondary_role" class="form-group" style="margin-top: 12px;">
                        <div class="input-icon-wrap custom-select-wrap">
                            <select v-model="newUserForm.tertiary_role" class="form-control select-control">
                                <option value="">Additional Section Editor Role (Optional)</option>
                                <option v-for="secRole in SECONDARY_ROLES[newUserForm.role].filter(r => r !== newUserForm.secondary_role)" :key="secRole" :value="secRole">{{ secRole }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Set Password</label>
                    <div class="input-icon-wrap" style="margin-bottom: 12px;">
                        <input v-model="newUserForm.password" type="password" class="form-control" placeholder="Create Password">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </div>
                    <div class="input-icon-wrap">
                        <input v-model="newUserForm.passwordConfirmation" type="password" class="form-control" placeholder="Retype Password">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn-back" type="button" @click="newUserStep = 1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Back</button>
                    <button class="btn-next" type="button" :disabled="newUserSaving || !isNewUserStep2Valid" @click="saveNewUser">{{ newUserSaving ? 'Saving...' : 'Save Details' }}</button>
                </div>
                <p v-if="newUserError" class="new-user-error">{{ newUserError }}</p>
            </div>

            <!-- Step 3 (Success) -->
            <div v-else class="new-user-step">
                <div class="success-step">
                    <div class="success-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <h3 class="success-title">User Added Successfully</h3>
                    <p class="success-text">A new user has been successfully created</p>
                    <button class="btn-next btn-full" type="button" @click="closeNewUserModal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div v-if="isEditUserModalOpen" class="new-user-modal-overlay" @click.self="closeEditUser">
        <form class="new-user-modal" role="dialog" aria-modal="true" @submit.prevent="saveEditedUser">
            <div class="new-user-modal-header">
                <h2>Edit User</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="closeEditUser">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="new-user-step">
                <div class="form-group">
                    <label class="form-label">Display Picture</label>
                    <div class="upload-box">
                        <div class="upload-circle" :class="{ 'has-preview': editUserImagePreview }">
                            <img v-if="editUserImagePreview" :src="editUserImagePreview" alt="Profile preview">
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        </div>
                        <div class="upload-info">
                            <p>Choose a new photo for this user.<br>jpeg, png, webp - Up to 4MB</p>
                            <input ref="editUserFileInput" type="file" accept="image/jpeg,image/png,image/webp" hidden @change="handleEditUserImage">
                            <button class="btn-upload" type="button" @click="editUserFileInput?.click()">{{ editUserImagePreview ? 'Change image' : 'Upload image' }} <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 2px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="7" y2="8"></line></svg></button>
                        </div>
                    </div>
                </div>
                <div class="form-group"><label class="form-label">Username</label><input v-model="editUserForm.name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Email</label><input v-model="editUserForm.email" type="email" class="form-control" required></div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Role</label><select v-model="editUserForm.role" class="form-control select-control" required @change="editUserForm.secondary_role = ''; editUserForm.tertiary_role = ''"><option value="admin">Administrator</option><option value="eic">Editor in Chief</option><option value="section_editor">Section Editor</option><option value="staff_writer">Staff Writer</option><option value="staff_artist">Staff Artist</option><option value="staff_broadcaster">Staff Broadcaster</option><option value="reader">Reader</option></select></div>
                    <div class="form-group"><label class="form-label">Status</label><select v-model="editUserForm.is_active" class="form-control select-control"><option :value="true">Active</option><option :value="false">Inactive</option></select></div>
                </div>
                <div v-if="SECONDARY_ROLES[editUserForm.role]" class="form-group" style="margin-top: 8px;">
                    <label class="form-label">Section / Secondary Role</label>
                    <select v-model="editUserForm.secondary_role" class="form-control select-control" @change="editUserForm.tertiary_role = ''">
                        <option value="">None / Default</option>
                        <option v-for="secRole in SECONDARY_ROLES[editUserForm.role]" :key="secRole" :value="secRole">{{ secRole }}</option>
                    </select>
                </div>
                <div v-if="['section_editor', 'eic'].includes(editUserForm.role) && editUserForm.secondary_role" class="form-group" style="margin-top: 8px;">
                    <label class="form-label">Additional Section Editor Role</label>
                    <select v-model="editUserForm.tertiary_role" class="form-control select-control">
                        <option value="">None</option>
                        <option v-for="secRole in SECONDARY_ROLES[editUserForm.role].filter(r => r !== editUserForm.secondary_role)" :key="secRole" :value="secRole">{{ secRole }}</option>
                    </select>
                </div>
                <div v-if="['eic', 'section_editor', 'staff_writer', 'staff_artist', 'staff_broadcaster'].includes(editUserForm.role)" class="form-row edit-user-academic-group">
                    <div class="form-group"><label class="form-label">Program</label><input v-model="editUserForm.program" class="form-control" placeholder="Program"></div>
                    <div class="form-group"><label class="form-label">Year/Section</label><input v-model="editUserForm.year_section" class="form-control" placeholder="Year/Section"></div>
                </div>
                <div class="form-group edit-user-password-group"><label class="form-label">New Password <span class="optional">(leave blank to keep)</span></label><input v-model="editUserForm.password" type="password" class="form-control" minlength="8"></div>
                <p v-if="editUserError" class="new-user-error">{{ editUserError }}</p>
                <div class="modal-footer"><button class="btn-back" type="button" @click="closeEditUser">Cancel</button><button class="btn-next" type="submit" :disabled="editUserSaving">{{ editUserSaving ? 'Saving...' : 'Save Changes' }}</button></div>
            </div>
        </form>
    </div>
    <div v-if="isDeleteUserModalOpen" class="new-user-modal-overlay" @click.self="closeDeleteUser">
        <div class="new-user-modal new-user-confirm-modal" role="dialog" aria-modal="true">
            <div class="new-user-modal-header"><h2>Delete User?</h2><button class="new-user-close" type="button" aria-label="Close" @click="closeDeleteUser"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button></div>
            <p>Delete <strong>{{ selectedUser?.name }}</strong>? This action cannot be undone.</p>
            <p v-if="deleteUserError" class="new-user-error">{{ deleteUserError }}</p>
            <div class="modal-footer"><button class="btn-next btn-danger" type="button" :disabled="deleteUserSaving" @click="deleteSelectedUser">{{ deleteUserSaving ? 'Deleting...' : 'Delete User' }}</button><button class="btn-back" type="button" @click="closeDeleteUser">Cancel</button></div>
        </div>
    </div>
    <div v-if="isEditArticleModalOpen" class="new-user-modal-overlay" @click.self="closeEditArticle">
        <form class="new-user-modal" role="dialog" aria-modal="true" @submit.prevent="saveEditedArticle">
            <div class="new-user-modal-header"><h2>Edit Article</h2><button class="new-user-close" type="button" aria-label="Close" @click="closeEditArticle"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button></div>
            <div class="new-user-step">
                <div class="form-group"><label class="form-label">Title</label><input v-model="editArticleForm.title" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Status</label><select v-model="editArticleForm.status" class="form-control select-control"><option value="draft">Draft</option><option value="submitted">Submitted</option><option value="under_review">Under Review</option><option value="endorsed">Endorsed</option><option value="approved">Approved</option><option value="rejected">Rejected</option><option value="published">Published</option></select></div>
                <div class="form-group"><label class="form-label">Excerpt</label><textarea v-model="editArticleForm.excerpt" class="form-control article-excerpt-input" rows="8"></textarea></div>
                <p v-if="editArticleError" class="new-user-error">{{ editArticleError }}</p>
                <div class="modal-footer"><button class="btn-back" type="button" @click="closeEditArticle">Cancel</button><button class="btn-next" type="submit" :disabled="editArticleSaving">{{ editArticleSaving ? 'Saving...' : 'Save Changes' }}</button></div>
            </div>
        </form>
    </div>
    <div v-if="isDeleteArticleModalOpen" class="new-user-modal-overlay" @click.self="closeDeleteArticle">
        <div class="new-user-modal new-user-confirm-modal" role="dialog" aria-modal="true">
            <div class="new-user-modal-header"><h2>Delete Article?</h2><button class="new-user-close" type="button" aria-label="Close" @click="closeDeleteArticle"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="2" y2="6"></line></svg></button></div>
            <p>Delete <strong>{{ selectedArticle?.title }}</strong>? This action cannot be undone.</p>
            <p v-if="deleteArticleError" class="new-user-error">{{ deleteArticleError }}</p>
            <div class="modal-footer"><button class="btn-next btn-danger" type="button" :disabled="deleteArticleSaving" @click="deleteSelectedArticle">{{ deleteArticleSaving ? 'Deleting...' : 'Delete Article' }}</button><button class="btn-back" type="button" @click="closeDeleteArticle">Cancel</button></div>
        </div>
    </div>
    <div v-if="isPressworkModalOpen" class="new-user-modal-overlay" @click.self="closePressworkModal">
        <form class="new-user-modal" role="dialog" aria-modal="true" @submit.prevent="createPresswork">
            <div class="new-user-modal-header"><h2>Add Academic Year</h2><button class="new-user-close" type="button" aria-label="Close" @click="closePressworkModal"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button></div>
            <div class="new-user-step">
                <p class="presswork-modal-note">This will create a new academic year folder with Newsletter, Tabloid, Magazine, and Litfolio monitoring sheets.</p>
                <div class="form-group"><label class="form-label" for="presswork-year">Academic Year</label><input id="presswork-year" v-model="pressworkForm.academic_year" class="form-control" placeholder="2025-2026" required></div>
                <p v-if="yearAlreadyExists" class="new-user-error" style="color: #dc2626;">⚠️ This academic year already exists!</p>
                <p v-if="pressworkError" class="new-user-error">{{ pressworkError }}</p>
                <div class="modal-footer"><button class="btn-back" type="button" @click="closePressworkModal">Cancel</button><button class="btn-next" type="submit" :disabled="pressworkSaving || yearAlreadyExists">{{ pressworkSaving ? 'Creating...' : 'Create Academic Year' }}</button></div>
            </div>
        </form>
    </div>

    <!-- Delete Academic Year Confirmation Modal -->
    <div v-if="isDeleteYearModalOpen" class="new-user-modal-overlay" @click.self="closeDeleteYearModal">
        <div class="new-user-modal new-user-confirm-modal" role="dialog" aria-modal="true">
            <div class="new-user-modal-header"><h2>Delete Academic Year?</h2><button class="new-user-close" type="button" aria-label="Close" @click="closeDeleteYearModal"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button></div>
            <p>Delete <strong>{{ yearToDelete }}</strong>? This will permanently delete all monitoring sheets and their entries for this academic year. This action cannot be undone.</p>
            <p v-if="deleteYearError" class="new-user-error">{{ deleteYearError }}</p>
            <div class="modal-footer"><button class="btn-next btn-danger" type="button" :disabled="deleteYearSaving" @click="deleteYear">{{ deleteYearSaving ? 'Deleting...' : 'Delete Academic Year' }}</button><button class="btn-back" type="button" @click="closeDeleteYearModal">Cancel</button></div>
        </div>
    </div>
    <!-- Archive Article Preview (read-only) -->
    <ArticlePreviewModal
        :is-open="isArchivePreviewOpen"
        :article-data="archivePreviewArticle"
        archived
        @close="isArchivePreviewOpen = false"
        @edit-article="handleArchiveEdit"
        @action-complete="handleArchiveDeleted"
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
import MobileNavToggle from '../../components/MobileNavToggle.vue';
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { lazyModal } from '../../utils/lazyModal';
import { useDebouncedSearch } from '../../utils/dashboardSearch';
import { useRouter } from 'vue-router';
const AssignedTaskModal = lazyModal(() => import('../../components/AssignedTaskModal.vue'));
const AssignmentWorkspaceModal = lazyModal(() => import('../../components/AssignmentWorkspaceModal.vue'));
const ArticlePreviewModal = lazyModal(() => import('../../components/ArticlePreviewModal.vue'));
import PeakTimeCard from '../../components/PeakTimeCard.vue';
import { signOut as performSignOut } from '../../utils/auth';

const router = useRouter();
const activeTab = ref('overview');
const { input: searchInput, query: searchQuery } = useDebouncedSearch();
const adminUser = ref(JSON.parse(localStorage.getItem('sparky_user') || '{}'));
const overview = ref({
    summary: { articles: 0, users: 0, pending: 0, published: 0 },
    user_activity: { active: [], new: [], active_count: 0, new_count: 0 },
    workflow: { submitted: 0, in_review: 0 },
    activities: [],
    updated_at: null,
});
const users = ref([]);
const articles = ref([]);
const academicYears = ref([]);
const expandedYears = ref({});
const isPressworkModalOpen = ref(false);
const isDeleteYearModalOpen = ref(false);
const yearToDelete = ref('');
const deleteYearError = ref('');
const deleteYearSaving = ref(false);
const pressworkSaving = ref(false);
const pressworkError = ref('');
const pressworkForm = ref({ academic_year: '2025-2026' });
const articlePage = ref(1);
const articlePageSize = 8;
const managementPage = ref(1);
const managementPageSize = 8;
const managementRole = ref('all');
const managementSection = ref('all');
const managementSort = ref('newest');
const activeManagementDropdown = ref(null);
const activeArticleDropdown = ref(null);
const activeAnalyticsDropdown = ref(false);
const articleFilters = reactive({ status: 'all', section: 'all', date: 'newest' });
const managementSortLabels = {
    newest: 'Newest',
    oldest: 'Oldest',
    name_asc: 'Name A-Z',
    name_desc: 'Name Z-A',
    section_asc: 'Section A-Z',
    section_desc: 'Section Z-A',
};

const toggleManagementDropdown = (dropdown) => {
    activeManagementDropdown.value = activeManagementDropdown.value === dropdown ? null : dropdown;
};

const selectManagementFilter = (filter, value) => {
    if (filter === 'role') managementRole.value = value;
    if (filter === 'section') managementSection.value = value;
    if (filter === 'sort') managementSort.value = value;
    activeManagementDropdown.value = null;
};

const toggleArticleDropdown = (filter) => {
    activeArticleDropdown.value = activeArticleDropdown.value === filter ? null : filter;
};

const selectArticleFilter = (filter, value) => {
    articleFilters[filter] = value;
    activeArticleDropdown.value = null;
};

const analyticsPeriodLabels = {
    7: 'Last 7 days',
    30: 'Last 30 days',
    90: 'Last 90 days',
};

const toggleAnalyticsDropdown = () => {
    activeAnalyticsDropdown.value = !activeAnalyticsDropdown.value;
};

const selectAnalyticsPeriod = (period) => {
    analyticsPeriod.value = period;
    activeAnalyticsDropdown.value = false;
    loadAnalytics();
};
const archivePage = ref(1);
const archivePageSize = 14;
const archiveYearPage = ref(1);
const archiveYearPageSize = 8;
const selectedArchiveFolder = ref(null);
const analyticsPeriod = ref('30');
const analytics = ref({
    configured: false,
    metrics: { page_views: 0, active_users: 0, sessions: 0, average_session_duration: 0 },
    top_pages: [],
    hourly: { views: [], visitors: [] },
    start_date: null,
    end_date: null,
});
const analyticsError = ref('');

const normalizedSearch = computed(() => searchQuery.value.trim().toLowerCase());
const searchPlaceholder = computed(() => activeTab.value === 'archive-year'
    ? 'Search archived articles'
    : activeTab.value === 'archive'
        ? 'Search archive folders'
        : activeTab.value === 'press-works'
            ? 'Search press works'
            : activeTab.value === 'articles'
                ? 'Search articles'
                : ['user-management', 'editorial-board', 'staff-writers', 'readers'].includes(activeTab.value)
                    ? 'Search users'
                    : activeTab.value === 'overview'
                        ? 'Search recent activities'
                        : activeTab.value === 'analytics'
                            ? 'Search publications and traffic'
                            : 'Search');

const matchesSearch = (...values) => !normalizedSearch.value
    || values.some(value => String(value ?? '').toLowerCase().includes(normalizedSearch.value));
const filteredOverviewActivities = computed(() => overview.value.activities.filter(activity => matchesSearch(
    activity.action,
    activity.subject,
    activity.user,
    activity.role,
)));
const filteredAnalyticsPages = computed(() => analytics.value.top_pages.filter(page => matchesSearch(
    page.title,
    'Site analytics',
)));

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

const archiveGallery = ref([]);
const archiveIssues = ref([]);
const archiveVideos = ref([]);

const loadArchiveExtras = async () => {
    const headers = { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json' };
    const load = async (url, target) => {
        try {
            const response = await fetch(url, { headers });
            if (response.ok) target.value = await response.json();
        } catch {
            // Keep whatever is already loaded.
        }
    };
    await Promise.all([
        load('/api/gallery', archiveGallery),
        load('/api/published-issues', archiveIssues),
        load('/api/articles?type=video', archiveVideos),
    ]);
};

// The latest things to go live, whatever the kind (article, video, gallery photo or published issue)
const RECENT_PUBLICATIONS_LIMIT = 3;
const recentPublications = computed(() => {
    const live = (item) => item.status === 'published';
    return [
        ...articles.value.filter(live).map(article => ({
            key: `article-${article.id}`,
            title: article.title,
            category: article.section?.name || 'Article',
            date: article.published_at || article.created_at,
            views: Number(article.reads_count || 0),
        })),
        ...archiveVideos.value.filter(live).map(video => ({
            key: `video-${video.id}`,
            title: video.title,
            category: 'Video',
            date: video.published_at || video.created_at,
            views: null,
        })),
        ...archiveGallery.value.map(photo => ({
            key: `gallery-${photo.id}`,
            title: photo.title,
            category: 'Gallery',
            date: photo.created_at,
            views: null,
        })),
        ...archiveIssues.value.map(issue => ({
            key: `issue-${issue.id}`,
            title: issue.title,
            category: 'Published Issue',
            date: issue.created_at,
            views: null,
        })),
    ]
        .filter(item => matchesSearch(item.title, item.category))
        .sort((a, b) => new Date(b.date || 0) - new Date(a.date || 0))
        .slice(0, RECENT_PUBLICATIONS_LIMIT);
});

// An archive row can be an article, a gallery photo, a published issue or a video
const openArchiveItem = (item = {}) => {
    if (item.archive_kind === 'gallery') {
        if (item.source?.image_url) window.open(item.source.image_url, '_blank', 'noopener');
    } else if (item.archive_kind === 'issue') {
        window.open(`/booklet/${item.source.id}`, '_blank', 'noopener');
    } else if (item.archive_kind === 'video') {
        openArchivePreview(item.source);
    } else {
        openArchivePreview(item);
    }
};

watch(activeTab, (tab) => {
    if (['archive', 'archive-year', 'analytics'].includes(tab)) loadArchiveExtras();
});

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
        ...articles.value,
        ...archiveGallery.value.map(photo => ({
            id: `gallery-${photo.id}`,
            archive_kind: 'gallery',
            title: photo.title,
            section: { name: 'Gallery' },
            author: photo.uploader,
            status: 'published',
            created_at: photo.created_at,
            source: photo,
        })),
        ...archiveIssues.value.map(issue => ({
            id: `issue-${issue.id}`,
            archive_kind: 'issue',
            title: issue.title,
            section: { name: 'Published Issue' },
            author: issue.uploader,
            status: 'published',
            created_at: issue.created_at,
            source: issue,
        })),
        ...archiveVideos.value
            .filter(video => ['published', 'scheduled'].includes(video.status))
            .map(video => ({ ...video, id: `video-${video.id}`, section: { name: 'Video' }, archive_kind: 'video', source: video })),
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

const managementSectionOptions = computed(() => {
    const tabRoleMap = {
        'editorial-board': ['eic', 'section_editor'],
        'staff-writers': ['staff_writer', 'staff_artist', 'staff_broadcaster'],
        'user-management': ['section_editor', 'staff_writer', 'staff_artist', 'staff_broadcaster'],
    };

    const targetRoles = tabRoleMap[activeTab.value] || [];
    const secRolesSet = new Set();

    if (managementRole.value !== 'all') {
        (SECONDARY_ROLES[managementRole.value] || []).forEach(secRole => secRolesSet.add(secRole));
    } else {
        targetRoles.forEach(r => {
            (SECONDARY_ROLES[r] || []).forEach(secRole => secRolesSet.add(secRole));
        });
    }

    users.value.forEach(u => {
        if (targetRoles.length === 0 || targetRoles.includes(u.role)) {
            if (managementRole.value === 'all' || u.role === managementRole.value) {
                if (u.secondary_role) secRolesSet.add(u.secondary_role);
                if (u.tertiary_role) secRolesSet.add(u.tertiary_role);
            }
        }
    });

    return Array.from(secRolesSet).sort();
});

const filteredManagementUsers = computed(() => {
    const rolesByTab = {
        'user-management': ['admin', 'eic', 'section_editor', 'staff_writer', 'staff_artist', 'staff_broadcaster', 'reader'],
        'editorial-board': ['eic', 'section_editor'],
        'staff-writers': ['staff_writer', 'staff_artist', 'staff_broadcaster'],
        readers: ['reader'],
    };
    const filtered = users.value
        .filter(user => rolesByTab[activeTab.value]?.includes(user.role))
        .filter(user => managementRole.value === 'all' || user.role === managementRole.value)
        .filter(user => managementSection.value === 'all' || user.secondary_role === managementSection.value || user.tertiary_role === managementSection.value)
        .filter(user => matchesSearch(
            user.name,
            user.email,
            user.secondary_role,
            user.tertiary_role,
            user.program,
            user.year_section,
            formatRole(user.role),
            getAcademicYearMeta(user.created_at).key,
            getAcademicYearMeta(user.created_at).label,
        ));

    return filtered.sort((first, second) => {
        if (managementSort.value === 'name_asc') return first.name.localeCompare(second.name);
        if (managementSort.value === 'name_desc') return second.name.localeCompare(first.name);
        if (managementSort.value === 'section_asc') return (first.secondary_role || '').localeCompare(second.secondary_role || '');
        if (managementSort.value === 'section_desc') return (second.secondary_role || '').localeCompare(first.secondary_role || '');

        const firstDate = new Date(first.created_at || 0).getTime();
        const secondDate = new Date(second.created_at || 0).getTime();
        return managementSort.value === 'oldest' ? firstDate - secondDate : secondDate - firstDate;
    });
});

watch(activeTab, () => {
    managementRole.value = 'all';
    managementSection.value = 'all';
    managementPage.value = 1;
    activeManagementDropdown.value = null;
});

const managementRoles = computed(() => {
    const rolesByTab = {
        'user-management': ['admin', 'eic', 'section_editor', 'staff_writer', 'staff_artist', 'staff_broadcaster', 'reader'],
        'editorial-board': ['eic', 'section_editor'],
        'staff-writers': ['staff_writer', 'staff_artist', 'staff_broadcaster'],
        readers: ['reader'],
    };
    const tabAllowedRoles = rolesByTab[activeTab.value] || ['admin', 'eic', 'section_editor', 'staff_writer', 'staff_artist', 'staff_broadcaster', 'reader'];
    const rolesSet = new Set(tabAllowedRoles);
    users.value.forEach(user => {
        if (rolesByTab[activeTab.value]?.includes(user.role)) {
            rolesSet.add(user.role);
        }
    });
    return Array.from(rolesSet);
});

const managementPageCount = computed(() => Math.max(1, Math.ceil(filteredManagementUsers.value.length / managementPageSize)));
const paginatedManagementUsers = computed(() => {
    const start = (managementPage.value - 1) * managementPageSize;
    return filteredManagementUsers.value.slice(start, start + managementPageSize);
});

const adminSections = ['News', 'Opinion', 'Editorial', 'Feature', 'Sci-Tech', 'DevCom', 'Sports', 'Literary', 'Videos'];

const articleFilterDefinitions = computed(() => [
    {
        key: 'status',
        options: [
            { value: 'all', label: 'Status' },
            ...[...new Set(articles.value.map(article => article.status).filter(Boolean))].sort().map(status => ({ value: status, label: articleStatusLabel(status) })),
        ],
    },
    {
        key: 'section',
        options: [
            { value: 'all', label: 'Section' },
            ...adminSections.map(section => ({ value: section, label: section })),
        ],
    },
    {
        key: 'date',
        options: [
            { value: 'newest', label: 'Newest' },
            { value: 'oldest', label: 'Oldest' },
        ],
    },
]);
const articleFilterLabel = (filter) => articleFilterDefinitions.value
    .find(definition => definition.key === filter)?.options
    .find(option => option.value === articleFilters[filter])?.label || filter;
const filteredArticles = computed(() => {
    const filtered = articles.value.filter(article => matchesSearch(
        article.title,
        article.author?.name,
        article.section?.name,
        articleStatusLabel(article.status),
    ) && (articleFilters.status === 'all' || article.status === articleFilters.status)
        && (articleFilters.section === 'all' || article.section?.name === articleFilters.section));

    return filtered.sort((first, second) => {
        const firstDate = new Date(first.created_at || 0).getTime();
        const secondDate = new Date(second.created_at || 0).getTime();
        return articleFilters.date === 'oldest' ? firstDate - secondDate : secondDate - firstDate;
    });
});
const articlePageCount = computed(() => Math.max(1, Math.ceil(filteredArticles.value.length / articlePageSize)));
const paginatedArticles = computed(() => {
    const start = (articlePage.value - 1) * articlePageSize;
    return filteredArticles.value.slice(start, start + articlePageSize);
});

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

const articleStatusCounts = computed(() => articles.value.reduce((counts, article) => {
    counts[article.status] = (counts[article.status] || 0) + 1;
    return counts;
}, {}));
const pressworkSheets = computed(() => {
    const allSheets = [];
    academicYears.value.forEach(yearGroup => {
        yearGroup.monitoring_sheets.forEach(sheet => {
            allSheets.push({
                ...sheet,
                academic_year: yearGroup.academic_year
            });
        });
    });
    return allSheets;
});

const filteredAcademicYears = computed(() => academicYears.value
    .map(yearGroup => ({
        ...yearGroup,
        monitoring_sheets: yearGroup.monitoring_sheets.filter(sheet => matchesSearch(
            yearGroup.academic_year,
            sheet.title,
            sheet.publication_type,
        )),
    }))
    .filter(yearGroup => matchesSearch(yearGroup.academic_year) || yearGroup.monitoring_sheets.length));

const yearAlreadyExists = computed(() => {
    return academicYears.value.some(year => year.academic_year === pressworkForm.value.academic_year);
});

// Clear error when academic year changes
watch(() => pressworkForm.value.academic_year, () => {
    pressworkError.value = '';
});

watch([managementPageCount, activeTab], ([pageCount]) => {
    if (managementPage.value > pageCount) managementPage.value = pageCount;
    if (activeTab.value !== 'user-management') managementPage.value = 1;
});

watch([managementRole, managementSort], () => {
    managementPage.value = 1;
});

watch(articlePageCount, (pageCount) => {
    if (articlePage.value > pageCount) articlePage.value = pageCount;
});

watch(archivePageCount, (pageCount) => {
    if (archivePage.value > pageCount) archivePage.value = pageCount;
});

watch(searchQuery, () => {
    articlePage.value = 1;
    managementPage.value = 1;
    archivePage.value = 1;
    archiveYearPage.value = 1;
});

watch(articleFilters, () => {
    articlePage.value = 1;
}, { deep: true });

const updatedLabel = computed(() => overview.value.updated_at
    ? `Updated ${new Date(overview.value.updated_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })}`
    : 'Loading...');

const formatCount = (value) => Number(value || 0).toLocaleString();

const formatRole = (role) => {
    if (role === 'system') return 'System';
    if (role === 'eic') return 'Editor in Chief';
    if (role === 'staff_broadcaster') return 'Staff Broadcaster';
    return (role || '').split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
};

const formatDate = (date) => date
    ? new Date(date).toLocaleString([], { dateStyle: 'medium', timeStyle: 'short' })
    : '—';

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

const avatarFallback = (name) => `https://ui-avatars.com/api/?name=${encodeURIComponent(name || 'User')}&background=dbeafe&color=1d4ed8`;

const loadOverview = async () => {
    try {
        const response = await fetch('/api/admin/overview', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        if (response.ok) overview.value = await response.json();
    } catch {
        // Keep the zero-state visible if the overview endpoint is unavailable.
    }
};

const loadUsers = async () => {
    try {
        const response = await fetch('/api/users', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        if (response.ok) users.value = await response.json();
    } catch {
        users.value = [];
    }
};

const loadArticles = async () => {
    try {
        const response = await fetch('/api/articles', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        if (response.ok) articles.value = await response.json();
    } catch {
        articles.value = [];
    }
};

const loadAnalytics = async () => {
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
            analyticsError.value = data.message || 'Google Analytics data is unavailable.';
            return;
        }

        analytics.value = data;
    } catch {
        analyticsError.value = 'Unable to load Google Analytics data.';
    }
};

const loadPressWorks = async () => {
    try {
        const token = localStorage.getItem('sparky_token');
        console.log('Loading press works, token exists:', !!token);
        
        const response = await fetch('/api/press-works', { headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' } });
        
        console.log('Press works response status:', response.status);
        
        if (response.ok) {
            const data = await response.json();
            academicYears.value = data.academic_years || [];
            console.log('Loaded academic years:', academicYears.value);
            // Auto-expand the first year if available and none are currently expanded
            if (academicYears.value.length > 0 && Object.keys(expandedYears.value).length === 0) {
                expandedYears.value[academicYears.value[0].academic_year] = true;
            }
        } else {
            console.error('Failed to load press works:', response.status, response.statusText);
            academicYears.value = [];
        }
    } catch (error) {
        console.error('Error loading press works:', error);
        academicYears.value = [];
    }
};

onMounted(() => {
    loadOverview();
    loadUsers();
    loadArticles();
    loadAnalytics();
    loadPressWorks();
});
const openDropdown = ref(null);
const isAssignedTaskModalOpen = ref(false);
const isWorkspaceModalOpen = ref(false);
const selectedTask = ref({});
const isNewUserModalOpen = ref(false);
const newUserStep = ref(1);
const newUserFileInput = ref(null);
const newUserImagePreview = ref('');
const editUserFileInput = ref(null);
const editUserImage = ref(null);
const editUserImagePreview = ref('');
const newUserSaving = ref(false);
const newUserError = ref('');
const isEditUserModalOpen = ref(false);
const isDeleteUserModalOpen = ref(false);
const selectedUser = ref(null);
const editUserSaving = ref(false);
const deleteUserSaving = ref(false);
const editUserError = ref('');
const deleteUserError = ref('');
const editUserForm = ref({ name: '', email: '', role: '', secondary_role: '', tertiary_role: '', is_active: true, program: '', year_section: '', password: '' });
const isEditArticleModalOpen = ref(false);
const isDeleteArticleModalOpen = ref(false);
const selectedArticle = ref(null);
const editArticleSaving = ref(false);
const deleteArticleSaving = ref(false);
const editArticleError = ref('');
const deleteArticleError = ref('');
const editArticleForm = ref({ title: '', status: 'draft', excerpt: '' });
const newUserForm = ref({
    name: '',
    email: '',
    role: '',
    secondary_role: '',
    tertiary_role: '',
    status: '',
    password: '',
    passwordConfirmation: '',
    program: '',
    year_section: '',
    image: null,
});

const isNewUserStep1Valid = computed(() => {
    return newUserForm.value.name.trim() && newUserForm.value.email.trim();
});

const isNewUserStep2Valid = computed(() => {
    return newUserForm.value.role
        && newUserForm.value.status
        && newUserForm.value.password
        && newUserForm.value.passwordConfirmation
        && newUserForm.value.password === newUserForm.value.passwordConfirmation;
});

const openNewUserModal = () => {
    newUserStep.value = 1;
    newUserError.value = '';
    isNewUserModalOpen.value = true;
};

const closeNewUserModal = () => {
    isNewUserModalOpen.value = false;
    newUserImagePreview.value = '';
    newUserForm.value = { name: '', email: '', role: '', secondary_role: '', tertiary_role: '', status: '', password: '', passwordConfirmation: '', program: '', year_section: '', image: null };
};

const handleNewUserImage = (event) => {
    const image = event.target.files?.[0] || null;
    newUserForm.value.image = image;
    newUserImagePreview.value = image ? URL.createObjectURL(image) : '';
};

const openEditUser = (member) => {
    selectedUser.value = member;
    editUserForm.value = {
        name: member.name,
        email: member.email,
        role: member.role,
        secondary_role: member.secondary_role || '',
        tertiary_role: member.tertiary_role || '',
        is_active: member.is_active !== false,
        program: member.program || '',
        year_section: member.year_section || '',
        password: '',
    };
    editUserImage.value = null;
    editUserImagePreview.value = member.profile_picture_url || '';
    editUserError.value = '';
    isEditUserModalOpen.value = true;
};

const closeEditUser = () => { isEditUserModalOpen.value = false; };

const handleEditUserImage = (event) => {
    const image = event.target.files?.[0] || null;
    if (!image) return;
    editUserImage.value = image;
    editUserImagePreview.value = URL.createObjectURL(image);
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
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Could not update user.');
        const index = users.value.findIndex(user => user.id === data.id);
        if (index !== -1) users.value[index] = data;
        closeEditUser();
    } catch (error) {
        editUserError.value = error.message;
    } finally {
        editUserSaving.value = false;
    }
};

const openDeleteUser = (member) => {
    selectedUser.value = member;
    deleteUserError.value = '';
    isDeleteUserModalOpen.value = true;
};

const closeDeleteUser = () => { isDeleteUserModalOpen.value = false; };

const deleteSelectedUser = async () => {
    if (!selectedUser.value) return;
    deleteUserSaving.value = true;
    deleteUserError.value = '';
    try {
        const userIdToDelete = selectedUser.value.id;
        const response = await fetch(`/api/users/${userIdToDelete}`, {
            method: 'DELETE',
            headers: { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json' },
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(data.message || 'Could not delete user.');
        users.value = users.value.filter(user => user.id !== userIdToDelete);
        articles.value = articles.value.filter(a => a.author_id !== userIdToDelete && a.author?.id !== userIdToDelete);
        closeDeleteUser();
    } catch (error) {
        deleteUserError.value = error.message;
    } finally {
        deleteUserSaving.value = false;
    }
};

const isArchivePreviewOpen = ref(false);
const archivePreviewArticle = ref({});

const openArchivePreview = (article = {}) => {
    const tasks = Array.isArray(article.tasks) ? article.tasks : [];
    const artist = tasks.find(t => ['illustration', 'photography', 'layout'].includes(t.type))?.assignee || null;
    const fileName = (url) => String(url).split('/').pop() || 'file';
    const files = [];
    if (article.cover_image) files.push({ name: fileName(article.cover_image), type: 'image', url: article.cover_image });
    (Array.isArray(article.media_files) ? article.media_files : []).forEach(url => files.push({ name: fileName(url), type: 'image', url }));

    archivePreviewArticle.value = {
        ...article,
        raw_status: article.status,
        artist_name: artist?.name || '',
        artist_avatar: artist?.profile_picture_url || '',
        attached_files: files,
    };
    isArchivePreviewOpen.value = true;
};

const handleArchiveEdit = (article) => {
    isArchivePreviewOpen.value = false;
    openEditArticle(article);
};

const handleArchiveDeleted = () => {
    const deletedId = archivePreviewArticle.value?.id;
    isArchivePreviewOpen.value = false;
    articles.value = articles.value.filter(article => article.id !== deletedId);
    archiveVideos.value = archiveVideos.value.filter(video => video.id !== deletedId);
};

const openEditArticle = (article) => {
    selectedArticle.value = article;
    editArticleForm.value = { title: article.title, status: article.status, excerpt: article.excerpt || '' };
    editArticleError.value = '';
    isEditArticleModalOpen.value = true;
};

const closeEditArticle = () => { isEditArticleModalOpen.value = false; };

const saveEditedArticle = async () => {
    if (!selectedArticle.value) return;
    editArticleSaving.value = true;
    editArticleError.value = '';
    try {
        const response = await fetch(`/api/articles/${selectedArticle.value.id}`, {
            method: 'PUT',
            headers: { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(editArticleForm.value),
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Could not update article.');
        const index = articles.value.findIndex(article => article.id === data.id);
        if (index !== -1) articles.value[index] = data;
        closeEditArticle();
    } catch (error) {
        editArticleError.value = error.message;
    } finally {
        editArticleSaving.value = false;
    }
};

const openDeleteArticle = (article) => {
    selectedArticle.value = article;
    deleteArticleError.value = '';
    isDeleteArticleModalOpen.value = true;
};

const closeDeleteArticle = () => { isDeleteArticleModalOpen.value = false; };

const deleteSelectedArticle = async () => {
    if (!selectedArticle.value) return;
    deleteArticleSaving.value = true;
    deleteArticleError.value = '';
    try {
        const response = await fetch(`/api/articles/${selectedArticle.value.id}`, {
            method: 'DELETE',
            headers: { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json' },
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(data.message || 'Could not delete article.');
        articles.value = articles.value.filter(article => article.id !== selectedArticle.value.id);
        closeDeleteArticle();
    } catch (error) {
        deleteArticleError.value = error.message;
    } finally {
        deleteArticleSaving.value = false;
    }
};

const saveNewUser = async () => {
    newUserError.value = '';
    if (!newUserForm.value.name || !newUserForm.value.email || !newUserForm.value.role || !newUserForm.value.password) {
        newUserError.value = 'Please complete all required fields.';
        return;
    }
    if (newUserForm.value.password !== newUserForm.value.passwordConfirmation) {
        newUserError.value = 'Passwords do not match.';
        return;
    }

    newUserSaving.value = true;
    const payload = new FormData();
    payload.append('name', newUserForm.value.name);
    payload.append('email', newUserForm.value.email);
    payload.append('role', newUserForm.value.role);
    if (newUserForm.value.secondary_role) {
        payload.append('secondary_role', newUserForm.value.secondary_role);
    }
    if (newUserForm.value.tertiary_role) {
        payload.append('tertiary_role', newUserForm.value.tertiary_role);
    }
    if (newUserForm.value.role !== 'reader') {
        payload.append('program', newUserForm.value.program || '');
        payload.append('year_section', newUserForm.value.year_section || '');
    }
    payload.append('password', newUserForm.value.password);
    payload.append('is_active', newUserForm.value.status !== 'inactive' ? '1' : '0');
    if (newUserForm.value.image) payload.append('profile_picture', newUserForm.value.image);

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
            throw new Error(validationError || `Could not create user (${response.status}).`);
        }
        if (data.id) users.value.push(data);
        newUserStep.value = 3;
    } catch (error) {
        newUserError.value = error.message || 'Could not connect to the server.';
    } finally {
        newUserSaving.value = false;
    }
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
    isWorkspaceModalOpen.value = true;
};

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

const closePressworkModal = () => { isPressworkModalOpen.value = false; };

const createPresswork = async () => {
    // Check for duplicate before making API call
    if (yearAlreadyExists.value) {
        pressworkError.value = 'An academic year with this name already exists.';
        return;
    }

    pressworkSaving.value = true;
    pressworkError.value = '';
    try {
        const response = await fetch('/api/press-works', {
            method: 'POST',
            headers: { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(pressworkForm.value),
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            if (response.status === 409) {
                throw new Error('An academic year with this name already exists.');
            }
            throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Could not create academic year.');
        }
        
        // Reload the press works to get the updated structure
        await loadPressWorks();
        
        // Expand the newly created year
        expandedYears.value[pressworkForm.value.academic_year] = true;
        
        closePressworkModal();
    } catch (error) {
        pressworkError.value = error.message;
    } finally {
        pressworkSaving.value = false;
    }
};

const openMonitoringSheet = (sheet) => {
    window.open(`/monitoring-sheet/${sheet.id}`, '_blank');
};

const toggleYear = (year) => {
    expandedYears.value[year] = !expandedYears.value[year];
};

const openArchiveFolder = (folder) => {
    selectedArchiveFolder.value = folder;
    archiveYearPage.value = 1;
    activeTab.value = 'archive-year';
};

const closeArchiveFolder = () => {
    activeTab.value = 'archive';
    selectedArchiveFolder.value = null;
    archiveYearPage.value = 1;
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
            headers: { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json' },
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw new Error(data.message || 'Could not delete academic year.');
        }
        
        // Reload the press works to get the updated structure
        await loadPressWorks();
        
        closeDeleteYearModal();
    } catch (error) {
        deleteYearError.value = error.message;
    } finally {
        deleteYearSaving.value = false;
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
</style>
