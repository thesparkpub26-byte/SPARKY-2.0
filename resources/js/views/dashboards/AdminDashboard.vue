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
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" placeholder="Search">
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
                    <NotificationsPopover />
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
                            <tr v-for="activity in overview.activities" :key="activity.id">
                                <td>{{ activity.action }}<span v-if="activity.subject">: {{ activity.subject }}</span></td>
                                <td>{{ activity.user }}</td>
                                <td><span class="role-pill">{{ formatRole(activity.role) }}</span></td>
                                <td>{{ formatDate(activity.created_at) }}</td>
                            </tr>
                            <tr v-if="!overview.activities.length">
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
                <div class="filters">
                    <button class="filter-dropdown">
                        AY 2025 - 2026
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <button class="filter-dropdown">
                        All Roles
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <button class="filter-dropdown">
                        Newest
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
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
                                <th>Program</th>
                                <th>Year/Section</th>
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
                                <td>—</td>
                                <td>—</td>
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
                                <td colspan="7" class="empty-activity">No matching users found.</td>
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
                <div
                    style="padding: 16px 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                    <div style="flex: 1; display: flex; justify-content: center;">
                        <div v-if="['user-management', 'editorial-board', 'staff-writers', 'readers'].includes(activeTab)" class="pagination">
                            <button class="page-nav" :disabled="managementPage === 1" @click="managementPage--">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="m15 18-6-6 6-6" /></svg>
                                Previous
                            </button>
                            <button v-for="page in managementPageCount" :key="page" class="page-btn" :class="{ active: managementPage === page }" @click="managementPage = page">{{ page }}</button>
                            <button class="page-nav" :disabled="managementPage === managementPageCount" @click="managementPage++">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><path d="m9 18 6-6-6-6" /></svg>
                            </button>
                        </div>
                        <div v-else class="pagination">
                            <button class="page-nav">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" style="margin-right: 4px;">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                                Previous
                            </button>
                            <button class="page-btn">1</button>
                            <button class="page-btn">2</button>
                            <button class="page-btn active">3</button>
                            <button class="page-btn">4</button>
                            <button class="page-btn">5</button>
                            <span style="margin: 0 4px; color: #555; font-weight: 700;">...</span>
                            <button class="page-nav">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" style="margin-left: 4px;">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="page-info">
                        Showing <strong>{{ ['user-management', 'editorial-board', 'staff-writers', 'readers'].includes(activeTab) ? paginatedManagementUsers.length : 12 }}</strong> of <strong>{{ ['user-management', 'editorial-board', 'staff-writers', 'readers'].includes(activeTab) ? filteredManagementUsers.length : 61 }}</strong> users
                    </div>
                </div>
            </div>
            </section>

            <!-- Articles Section -->
            <section id="section-articles" class="content-section" :class="{ active: activeTab === 'articles' }" v-show="activeTab === 'articles'">
                <div class="page-header" style="align-items: center;">
                <h1 class="page-title">Articles</h1>
                <div class="filters">
                    <button class="filter-dropdown">
                        Status
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <button class="filter-dropdown">
                        Section
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <button class="filter-dropdown">
                        Coverage
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <button class="filter-dropdown">
                        Date
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
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
                                <th>Coverage</th>
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
                                <td>{{ article.monitoring_sheet_url || '—' }}</td>
                                <td>{{ article.author?.name || 'Unknown' }}</td>
                                <td><span class="status-pill" :class="articleStatusClass(article.status)">{{ articleStatusLabel(article.status) }}</span></td>
                                <td>{{ formatDate(article.created_at) }}</td>
                                <td><div class="action-icons"><button class="action-btn edit" type="button" aria-label="Edit article" @click="openEditArticle(article)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg></button><button class="action-btn delete" type="button" aria-label="Delete article" @click="openDeleteArticle(article)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg></button></div></td>
                            </tr>
                            <tr v-if="!articles.length"><td colspan="7" class="empty-activity">No articles found.</td></tr>
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
                <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                    <div style="flex: 1; display: flex; justify-content: center;">
                        <div class="pagination">
                            <button class="page-nav" :disabled="articlePage === 1" @click="articlePage--">Previous</button>
                            <button v-for="page in articlePageCount" :key="page" class="page-btn" :class="{ active: articlePage === page }" @click="articlePage = page">{{ page }}</button>
                            <button class="page-nav" :disabled="articlePage === articlePageCount" @click="articlePage++">Next</button>
                        </div>
                    </div>
                    <div class="page-info">Showing <strong>{{ paginatedArticles.length }}</strong> of <strong>{{ articles.length }}</strong> articles</div>
                </div>
                <div v-if="false" style="padding: 16px 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                    <div style="flex: 1; display: flex; justify-content: center;">
                        <div class="pagination">
                            <button class="page-nav">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="m15 18-6-6 6-6"/></svg>
                                Previous
                            </button>
                            <button class="page-btn">1</button>
                            <button class="page-btn">2</button>
                            <button class="page-btn active">3</button>
                            <button class="page-btn">4</button>
                            <button class="page-btn">5</button>
                            <span style="margin: 0 4px; color: #555; font-weight: 700;">...</span>
                            <button class="page-nav">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="page-info">
                        Showing <strong>12</strong> of <strong>2,137</strong> articles
                    </div>
                </div>
            </div>
            </section>

            <!-- Press Works Section -->
            <section id="section-press-works" class="content-section" :class="{ active: activeTab === 'press-works' }" v-show="activeTab === 'press-works'">
                <div class="page-header" style="align-items: center; margin-bottom: 20px;">
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
                                <tr v-for="sheet in pressworkSheets" :key="sheet.id" @click="openMonitoringSheet(sheet)" style="cursor: pointer;" class="clickable-row">
                                    <td style="padding-left: 24px;">{{ sheet.press_work.title }}</td>
                                    <td><span class="pub-badge" :class="pressworkBadgeClass(sheet.publication_type)">{{ sheet.publication_type }}</span></td>
                                    <td>{{ sheet.press_work?.academic_year || '—' }}</td>
                                    <td>—</td>
                                    <td>{{ formatDate(sheet.created_at) }}</td>
                                    <td style="padding-right: 24px;"><button class="action-menu-btn" type="button" @click.stop="openMonitoringSheet(sheet)" aria-label="Open monitoring sheet"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg></button></td>
                                </tr>
                                <tr v-if="!pressWorks.length"><td colspan="6" class="empty-activity">No press works found.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="floating-pagination" style="margin-top: 20px;">
                    <div class="pagination" style="margin-top: 0;">
                        <button class="page-nav" disabled>Previous</button>
                        <button class="page-btn active">1</button>
                        <button class="page-nav" disabled>Next</button>
                    </div>
                    <div class="page-info" style="margin-left: 0; padding-left: 32px; border-left: 1px solid #eef0f4;">
                        Showing <strong>{{ pressworkSheets.length }}</strong> Monitoring Sheets
                    </div>
                </div>
            </section>

            <!-- Archive Section -->
            <section id="section-archive" class="content-section" :class="{ active: activeTab === 'archive' }" v-show="activeTab === 'archive'">
                <div class="page-header" style="align-items: center;">
                <h1 class="page-title">Archive</h1>
                <div class="filters">
                    <button class="filter-dropdown">
                        Sort from
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                </div>
            </div>

            <!-- Archive Grid -->
            <div class="archive-grid">
                <!-- Template for SVG Folder -->
                <svg width="0" height="0" style="position:absolute">
                    <defs>
                        <linearGradient id="folderGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#90bbf8" />
                            <stop offset="100%" stop-color="#719bf0" />
                        </linearGradient>
                        <g id="folderIcon">
                            <!-- White Paper -->
                            <path d="M 20 20 C 20 5, 30 0, 40 0 L 160 0 C 170 0, 180 5, 180 20 L 180 100 L 20 100 Z" fill="#ffffff" />
                            <!-- Blue Folder -->
                            <path d="M 0 50 C 0 30, 10 20, 30 20 L 80 20 C 90 20, 95 35, 100 40 C 105 45, 110 50, 120 50 L 170 50 C 190 50, 200 60, 200 80 L 200 200 L 0 200 Z" fill="url(#folderGrad)" />
                        </g>
                    </defs>
                </svg>

                <!-- Cards -->
                <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg><div class="archive-card-content"><h4>AY 2025 - 2026</h4><p>218 articles</p></div></div>
                <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg><div class="archive-card-content"><h4>AY 2024 - 2025</h4><p>206 articles</p></div></div>
                <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg><div class="archive-card-content"><h4>AY 2023 - 2024</h4><p>194 articles</p></div></div>
                <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg><div class="archive-card-content"><h4>AY 2022 - 2023</h4><p>181 articles</p></div></div>
                <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg><div class="archive-card-content"><h4>AY 2021 - 2022</h4><p>169 articles</p></div></div>
                <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg><div class="archive-card-content"><h4>AY 2020 - 2021</h4><p>158 articles</p></div></div>
                <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg><div class="archive-card-content"><h4>AY 2019 - 2020</h4><p>147 articles</p></div></div>
                <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg><div class="archive-card-content"><h4>AY 2018 - 2019</h4><p>136 articles</p></div></div>
                <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg><div class="archive-card-content"><h4>AY 2017 - 2018</h4><p>125 articles</p></div></div>
                <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg><div class="archive-card-content"><h4>AY 2016 - 2017</h4><p>114 articles</p></div></div>
                <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg><div class="archive-card-content"><h4>AY 2015 - 2016</h4><p>103 articles</p></div></div>
                <div class="archive-card"><svg class="folder-bg" viewBox="0 0 200 200" preserveAspectRatio="none"><use href="#folderIcon" /></svg><div class="archive-card-content"><h4>AY 2014 - 2015</h4><p>92 articles</p></div></div>
            </div>

            <!-- Floating Pagination -->
            <div class="floating-pagination">
                <div class="pagination" style="margin-top: 0;">
                    <button class="page-nav">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="m15 18-6-6 6-6"/></svg>
                        Previous
                    </button>
                    <button class="page-btn">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn active">3</button>
                    <button class="page-btn">4</button>
                    <button class="page-btn">5</button>
                    <span style="margin: 0 4px; color: #555; font-weight: 700;">...</span>
                    <button class="page-nav">
                        Next
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                </div>
                <div class="page-info" style="margin-left: 0; padding-left: 32px; border-left: 1px solid #eef0f4;">
                    Showing <strong>12</strong> of <strong>2,137</strong> articles
                </div>
            </div>
            </section>

            <!-- Analytics Section -->
            <section id="section-analytics" class="content-section" :class="{ active: activeTab === 'analytics' }" v-show="activeTab === 'analytics'">
                <div class="page-header" style="align-items: center;">
                <h1 class="page-title">Analytics</h1>
                <div class="filters">
                    <button class="filter-dropdown">
                        AY 2025 - 2026
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <button class="filter-dropdown">
                        Last 30 days
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="overview-grid" style="grid-template-columns: 1.5fr 1fr;">
                
                <!-- Left Column -->
                <div class="left-col" style="display: flex; flex-direction: column; gap: 24px;">
                    
                    <!-- Content Performance -->
                    <div class="card">
                        <h3 class="card-header">Content Performance</h3>
                        
                        <div class="analytics-list-item">
                            <div class="analytics-item-left">
                                <div class="analytics-item-title">CSPC Launches New Digital Learning Hub</div>
                                <div class="analytics-item-meta">
                                    <span class="section">News</span>
                                    <span>Jhea Nicole N. Comandante</span>
                                </div>
                            </div>
                            <div class="analytics-item-right">
                                <div class="metric-pill">
                                    2,031
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 17 9.2-9.2M17 17V7H7"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="analytics-list-item">
                            <div class="analytics-item-left">
                                <div class="analytics-item-title">Blue Stallions Dominate Regional Meet...</div>
                                <div class="analytics-item-meta">
                                    <span class="section">Sports</span>
                                    <span>Hanna Grace A. Clevillas</span>
                                </div>
                            </div>
                            <div class="analytics-item-right">
                                <div class="metric-pill">
                                    1,245
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="analytics-list-item">
                            <div class="analytics-item-left">
                                <div class="analytics-item-title">The Rise of Campus Creatives</div>
                                <div class="analytics-item-meta">
                                    <span class="section">Feature</span>
                                    <span>Gabrielle M. Loquias</span>
                                </div>
                            </div>
                            <div class="analytics-item-right">
                                <div class="metric-pill">
                                    1,102
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="view-all">View All <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -2px;"><path d="m9 18 6-6-6-6"/></svg></a>
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
                                <tr>
                                    <td style="border-bottom: none;">Blue Stallions Dominate Regional Me...</td>
                                    <td style="border-bottom: none;"><span class="section-pill">Sports</span></td>
                                    <td style="border-bottom: none;">Apr 4, 2026</td>
                                    <td style="border-bottom: none; font-weight: 600;">1,245 <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -3px; margin-left: 4px;"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: none;">CSPC Launches New Digital Learning...</td>
                                    <td style="border-bottom: none;"><span class="section-pill">News</span></td>
                                    <td style="border-bottom: none;">Apr 4, 2026</td>
                                    <td style="border-bottom: none; font-weight: 600;">2,031 <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -3px; margin-left: 4px;"><path d="m7 17 9.2-9.2M17 17V7H7"/></svg></td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: none;">Sa Likod ng Tinta</td>
                                    <td style="border-bottom: none;"><span class="section-pill">Literary</span></td>
                                    <td style="border-bottom: none;">Apr 4, 2026</td>
                                    <td style="border-bottom: none; font-weight: 600;">876 <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -3px; margin-left: 4px;"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: none;">The Rise of Campus Creatives</td>
                                    <td style="border-bottom: none;"><span class="section-pill">Feature</span></td>
                                    <td style="border-bottom: none;">Apr 4, 2026</td>
                                    <td style="border-bottom: none; font-weight: 600;">1,102 <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -3px; margin-left: 4px;"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></td>
                                </tr>
                            </tbody>
                        </table>
                        <a href="#" class="view-all">View All <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -2px;"><path d="m9 18 6-6-6-6"/></svg></a>
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
                                    <div class="engagement-value">82.5K</div>
                                </div>
                                <div class="engagement-box" style="flex: 1;">
                                    <div class="engagement-label">Average Views per Article</div>
                                    <div class="engagement-value">341</div>
                                </div>
                            </div>
                            <div class="engagement-box tall">
                                <div class="engagement-label" style="margin-bottom: 16px;">Most Viewed Categories</div>
                                
                                <div class="category-bar news">
                                    <span>News</span>
                                    <span>7.1K</span>
                                </div>
                                <div class="category-bar sports">
                                    <span>Sports</span>
                                    <span>2.9K</span>
                                </div>
                                <div class="category-bar literary">
                                    <span>Literary</span>
                                    <span>1.3K</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Workflow Efficiency -->
                    <div class="card">
                        <h3 class="card-header">Workflow Efficiency</h3>
                        <div class="workflow-grid">
                            <div class="workflow-box">
                                <div class="workflow-label">Avg. Time to Publish</div>
                                <div class="workflow-value-large">2.4<span style="font-size: 16px;">d</span></div>
                            </div>
                            <div class="workflow-box">
                                <div class="workflow-label">Articles to Review</div>
                                <div class="workflow-value-large">88</div>
                            </div>
                            <div class="workflow-box">
                                <div class="workflow-label">Revision Rate</div>
                                <div class="workflow-value-large">28<span style="font-size: 16px;">%</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Article Status Overview -->
                    <div class="card">
                        <h3 class="card-header">Article Status Overview</h3>
                        <div class="status-list">
                            <div class="status-list-item">
                                <span>Draft</span>
                                <div class="status-count">45</div>
                            </div>
                            <div class="status-list-item">
                                <span>In Review</span>
                                <div class="status-count">88</div>
                            </div>
                            <div class="status-list-item">
                                <span>Under Revision</span>
                                <div class="status-count">32</div>
                            </div>
                            <div class="status-list-item">
                                <span>Approved</span>
                                <div class="status-count">217</div>
                            </div>
                            <div class="status-list-item">
                                <span>Published</span>
                                <div class="status-count">1.9K</div>
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

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input v-model="newUserForm.name" type="text" class="form-control" placeholder="Username">
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input v-model="newUserForm.email" type="email" class="form-control" placeholder="handle@my.cspc.edu.ph">
                </div>

                <div class="modal-footer">
                    <button class="btn-back" type="button" @click="closeNewUserModal"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Back</button>
                    <button class="btn-next" type="button" @click="newUserStep = 2">Next</button>
                </div>
            </div>

            <!-- Step 2 -->
            <div v-else-if="newUserStep === 2" class="new-user-step">
                <div class="form-group">
                    <label class="form-label">Account Details</label>
                    <div class="form-row">
                        <div class="form-group">
                            <div class="input-icon-wrap">
                                <select v-model="newUserForm.role" class="form-control select-control"><option value="" disabled>Role</option><option value="admin">Administrator</option><option value="eic">Editor in Chief</option><option value="section_editor">Section Editor</option><option value="staff_writer">Staff Writer</option><option value="staff_artist">Staff Artist</option><option value="reader">Reader</option></select>
                                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a73e8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon-wrap">
                                <select v-model="newUserForm.status" class="form-control select-control"><option value="" disabled>Status</option><option value="active">Active</option><option value="inactive">Inactive</option></select>
                                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a73e8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
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
                    <button class="btn-next" type="button" :disabled="newUserSaving" @click="saveNewUser">{{ newUserSaving ? 'Saving...' : 'Save Details' }}</button>
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
                <div class="form-group"><label class="form-label">Username</label><input v-model="editUserForm.name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Email</label><input v-model="editUserForm.email" type="email" class="form-control" required></div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Role</label><select v-model="editUserForm.role" class="form-control select-control" required><option value="admin">Administrator</option><option value="eic">Editor in Chief</option><option value="section_editor">Section Editor</option><option value="staff_writer">Staff Writer</option><option value="staff_artist">Staff Artist</option><option value="reader">Reader</option></select></div>
                    <div class="form-group"><label class="form-label">Status</label><select v-model="editUserForm.is_active" class="form-control select-control"><option :value="true">Active</option><option :value="false">Inactive</option></select></div>
                </div>
                <div class="form-group"><label class="form-label">New Password <span class="optional">(leave blank to keep)</span></label><input v-model="editUserForm.password" type="password" class="form-control" minlength="8"></div>
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
            <div class="modal-footer"><button class="btn-back" type="button" @click="closeDeleteUser">Cancel</button><button class="btn-next btn-danger" type="button" :disabled="deleteUserSaving" @click="deleteSelectedUser">{{ deleteUserSaving ? 'Deleting...' : 'Delete User' }}</button></div>
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
            <div class="modal-footer"><button class="btn-back" type="button" @click="closeDeleteArticle">Cancel</button><button class="btn-next btn-danger" type="button" :disabled="deleteArticleSaving" @click="deleteSelectedArticle">{{ deleteArticleSaving ? 'Deleting...' : 'Delete Article' }}</button></div>
        </div>
    </div>
    <div v-if="isPressworkModalOpen" class="new-user-modal-overlay" @click.self="closePressworkModal">
        <form class="new-user-modal" role="dialog" aria-modal="true" @submit.prevent="createPresswork">
            <div class="new-user-modal-header"><h2>Add Presswork</h2><button class="new-user-close" type="button" aria-label="Close" @click="closePressworkModal"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button></div>
            <div class="new-user-step">
                <p class="presswork-modal-note">This creates one monitoring sheet for Newsletter, Tabloid, Magazine, and Litfolio.</p>
                <div class="form-group"><label class="form-label" for="presswork-title">Presswork Name</label><input id="presswork-title" v-model="pressworkForm.title" class="form-control" placeholder="Issue 1" required></div>
                <div class="form-group"><label class="form-label" for="presswork-year">Academic Year</label><input id="presswork-year" v-model="pressworkForm.academic_year" class="form-control" placeholder="2025-2026" required></div>
                <p v-if="pressworkError" class="new-user-error">{{ pressworkError }}</p>
                <div class="modal-footer"><button class="btn-back" type="button" @click="closePressworkModal">Cancel</button><button class="btn-next" type="submit" :disabled="pressworkSaving">{{ pressworkSaving ? 'Creating...' : 'Create Presswork' }}</button></div>
            </div>
        </form>
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
    />
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import AssignedTaskModal from '../../components/AssignedTaskModal.vue';
import AssignmentWorkspaceModal from '../../components/AssignmentWorkspaceModal.vue';
import NotificationsPopover from '../../components/NotificationsPopover.vue';
import { signOut as performSignOut } from '../../utils/auth';

const router = useRouter();
const activeTab = ref('overview');
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
const pressWorks = ref([]);
const isPressworkModalOpen = ref(false);
const pressworkSaving = ref(false);
const pressworkError = ref('');
const pressworkForm = ref({ title: '', academic_year: '2025-2026' });
const articlePage = ref(1);
const articlePageSize = 8;
const managementPage = ref(1);
const managementPageSize = 8;

const filteredManagementUsers = computed(() => {
    const rolesByTab = {
        'user-management': ['admin', 'eic', 'section_editor', 'staff_writer', 'staff_artist', 'reader'],
        'editorial-board': ['eic', 'section_editor'],
        'staff-writers': ['staff_writer', 'staff_artist'],
        readers: ['reader'],
    };
    return users.value
    .filter(user => rolesByTab[activeTab.value]?.includes(user.role))
    .sort((first, second) => first.name.localeCompare(second.name));
});

const managementPageCount = computed(() => Math.max(1, Math.ceil(filteredManagementUsers.value.length / managementPageSize)));
const paginatedManagementUsers = computed(() => {
    const start = (managementPage.value - 1) * managementPageSize;
    return filteredManagementUsers.value.slice(start, start + managementPageSize);
});

const articlePageCount = computed(() => Math.max(1, Math.ceil(articles.value.length / articlePageSize)));
const paginatedArticles = computed(() => {
    const start = (articlePage.value - 1) * articlePageSize;
    return articles.value.slice(start, start + articlePageSize);
});

const pressworkSheets = computed(() => pressWorks.value.flatMap(work => work.monitoring_sheets.map(sheet => ({ ...sheet, press_work: work }))));

watch([managementPageCount, activeTab], ([pageCount]) => {
    if (managementPage.value > pageCount) managementPage.value = pageCount;
    if (activeTab.value !== 'user-management') managementPage.value = 1;
});

watch(articlePageCount, (pageCount) => {
    if (articlePage.value > pageCount) articlePage.value = pageCount;
});

const updatedLabel = computed(() => overview.value.updated_at
    ? `Updated ${new Date(overview.value.updated_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })}`
    : 'Loading...');

const formatCount = (value) => Number(value || 0).toLocaleString();

const formatRole = (role) => {
    if (role === 'system') return 'System';
    if (role === 'eic') return 'Editor in Chief';
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

const loadPressWorks = async () => {
    try {
        const response = await fetch('/api/press-works', { headers: { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json' } });
        if (response.ok) pressWorks.value = await response.json();
    } catch {
        pressWorks.value = [];
    }
};

onMounted(() => {
    loadOverview();
    loadUsers();
    loadArticles();
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
const newUserSaving = ref(false);
const newUserError = ref('');
const isEditUserModalOpen = ref(false);
const isDeleteUserModalOpen = ref(false);
const selectedUser = ref(null);
const editUserSaving = ref(false);
const deleteUserSaving = ref(false);
const editUserError = ref('');
const deleteUserError = ref('');
const editUserForm = ref({ name: '', email: '', role: '', is_active: true, password: '' });
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
    status: '',
    password: '',
    passwordConfirmation: '',
    image: null,
});

const openNewUserModal = () => {
    newUserStep.value = 1;
    newUserError.value = '';
    isNewUserModalOpen.value = true;
};

const closeNewUserModal = () => {
    isNewUserModalOpen.value = false;
    newUserImagePreview.value = '';
    newUserForm.value = { name: '', email: '', role: '', status: '', password: '', passwordConfirmation: '', image: null };
};

const handleNewUserImage = (event) => {
    const image = event.target.files?.[0] || null;
    newUserForm.value.image = image;
    newUserImagePreview.value = image ? URL.createObjectURL(image) : '';
};

const openEditUser = (member) => {
    selectedUser.value = member;
    editUserForm.value = { name: member.name, email: member.email, role: member.role, is_active: member.is_active !== false, password: '' };
    editUserError.value = '';
    isEditUserModalOpen.value = true;
};

const closeEditUser = () => { isEditUserModalOpen.value = false; };

const saveEditedUser = async () => {
    if (!selectedUser.value) return;
    editUserSaving.value = true;
    editUserError.value = '';
    const payload = { ...editUserForm.value };
    if (!payload.password) delete payload.password;
    try {
        const response = await fetch(`/api/users/${selectedUser.value.id}`, {
            method: 'PUT',
            headers: { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });
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
        const response = await fetch(`/api/users/${selectedUser.value.id}`, {
            method: 'DELETE',
            headers: { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json' },
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(data.message || 'Could not delete user.');
        users.value = users.value.filter(user => user.id !== selectedUser.value.id);
        closeDeleteUser();
    } catch (error) {
        deleteUserError.value = error.message;
    } finally {
        deleteUserSaving.value = false;
    }
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
    pressworkForm.value = { title: '', academic_year: '2025-2026' };
    pressworkError.value = '';
    isPressworkModalOpen.value = true;
};

const closePressworkModal = () => { isPressworkModalOpen.value = false; };

const createPresswork = async () => {
    pressworkSaving.value = true;
    pressworkError.value = '';
    try {
        const response = await fetch('/api/press-works', {
            method: 'POST',
            headers: { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(pressworkForm.value),
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Could not create presswork.');
        pressWorks.value.unshift(data);
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
</script>
