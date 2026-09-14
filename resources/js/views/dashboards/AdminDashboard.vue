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
                <a href="#" class="nav-item active" @click.prevent="activeTab = 'overview'" :class="{ active: activeTab === 'overview' }">
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
                    <a href="#" class="sub-item" @click.prevent="activeTab = 'user-management'; openDropdown = 'user-management'" :class="{ active: activeTab === 'user-management' || activeTab === 'editorial-board' }">
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
                <img src="https://picsum.photos/200?random=1" alt="Profile">
                <div class="user-info">
                    <span class="role-badge">Administrator</span>
                    <h4>Administrator's Name</h4>
                    <p>admin@thesparkpub.com</p>
                </div>
                <button class="settings-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"></path><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"></path><path d="M12 2v2"></path><path d="M12 22v-2"></path><path d="m17 4-1.5 1.5"></path><path d="M22 12h-2"></path><path d="m17 20-1.5-1.5"></path><path d="M2 12h2"></path><path d="m7 4 1.5 1.5"></path><path d="m7 20 1.5-1.5"></path></svg>
                </button>
            </div>
            
            <button class="sign-out-btn" style="margin-top: 15px;" @click.prevent="$router.push('/login')">
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
                    <button class="new-user-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        New User
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
                            <div class="metric-value">2.1K</div>
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
                            <div class="metric-value">61</div>
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
                            <div class="metric-value">225</div>
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
                            <div class="metric-value">1.9K</div>
                            <div class="metric-label">Published</div>
                            <div class="metric-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none">
                                    <path d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" stroke="currentColor" stroke-width="1.5" />
                                    <path d="M8 12.5L10.5 15L16 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="updated-text">Updated just now</p>
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
                                    <img src="https://picsum.photos/200?random=2" alt="User">
                                    <img src="https://picsum.photos/200?random=3" alt="User">
                                    <img src="https://picsum.photos/200?random=4" alt="User">
                                    <img src="https://picsum.photos/200?random=5" alt="User">
                                    <div class="avatar-more">+ 57</div>
                                </div>
                            </div>
                            <div class="sub-card">
                                <p class="sub-card-title">New Users</p>
                                <div class="avatar-stack">
                                    <img src="https://picsum.photos/200?random=6" alt="User">
                                    <img src="https://picsum.photos/200?random=7" alt="User">
                                    <img src="https://picsum.photos/200?random=8" alt="User">
                                    <img src="https://picsum.photos/200?random=9" alt="User">
                                    <div class="avatar-more">+ 57</div>
                                </div>
                            </div>
                        </div>
                        <p class="updated-text">Updated just now</p>
                    </div>

                    <!-- Workflow Status Summary -->
                    <div class="card">
                        <h3 class="card-header">Workflow Status Summary</h3>
                        <div class="sub-card-container">
                            <div class="sub-card" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p class="sub-card-title" style="margin-bottom: 4px;">Submitted</p>
                                    <div class="workflow-value">137</div>
                                </div>
                                <div class="metric-icon" style="position: static; background-color: #e2e8f0; color: #555;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                </div>
                            </div>
                            <div class="sub-card" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p class="sub-card-title" style="margin-bottom: 4px;">In Review</p>
                                    <div class="workflow-value">88</div>
                                </div>
                                <div class="metric-icon" style="position: static; background-color: #111; color: white;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                </div>
                            </div>
                        </div>
                        <p class="updated-text">Updated just now</p>
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
                            <tr>
                                <td>Submitted an article</td>
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
                                <td>Approved an article</td>
                                <td>Fernan Matthew A. Enimedez</td>
                                <td><span class="role-pill">Editor-in-Chief</span></td>
                                <td>Apr 3, 2026 07:07:42</td>
                            </tr>
                            <tr>
                                <td>Added as Video Editor</td>
                                <td>Emher Kenji A. Turiano</td>
                                <td><span class="role-pill">Video Editor</span></td>
                                <td>Apr 1, 2026 21:37:12</td>
                            </tr>
                            <tr>
                                <td>Logged in</td>
                                <td>Ma. Katrina P. Oliveros</td>
                                <td><span class="role-pill">Managing Editor</span></td>
                                <td>Apr 1, 2026 23:08:22</td>
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
                            <tr v-show="activeTab === 'user-management' || activeTab === 'editorial-board'">
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
                            <tr v-show="activeTab === 'user-management' || activeTab === 'editorial-board'">
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
                            <tr v-show="activeTab === 'user-management' || activeTab === 'editorial-board'">
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
                            <tr v-show="activeTab === 'user-management' || activeTab === 'staff-writers'">
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
                            <tr v-show="activeTab === 'user-management' || activeTab === 'staff-writers'">
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
                            <tr v-show="activeTab === 'user-management' || activeTab === 'staff-writers'">
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
                            <tr v-show="activeTab === 'user-management' || activeTab === 'staff-writers'">
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
                            <tr v-show="activeTab === 'user-management' || activeTab === 'readers'">
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
                            <tr v-show="activeTab === 'user-management' || activeTab === 'readers'">
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
                        <div class="pagination">
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
                        Showing <strong>12</strong> of <strong>61</strong> users
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

                <div class="floating-pagination" style="margin-top: 20px;">
                    <div class="pagination" style="margin-top: 0;">
                        <button class="page-nav">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="m15 18-6-6 6-6"/></svg>
                            Previous
                        </button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">4</button>
                        <button class="page-btn">5</button>
                        <span style="margin: 0 4px; color: #555; font-weight: 700;">...</span>
                        <button class="page-nav">
                            Next
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><path d="m9 18 6-6-6-6"/></svg>
                        </button>
                    </div>
                    <div class="page-info" style="margin-left: 0; padding-left: 32px; border-left: 1px solid #eef0f4;">
                        Showing <strong>4</strong> Press Works
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
    <div class="modal-overlay" id="newUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Add New User</h2>
                <button class="close-modal" id="closeModalBtnTop">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <!-- Step 1 -->
            <div class="modal-step active" id="step1">
                <div class="form-group">
                    <label class="form-label">Display Picture</label>
                    <div class="upload-box">
                        <div class="upload-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        </div>
                        <div class="upload-info">
                            <p>Choose a file or drag & drop it here.<br>jpeg, png - Up to 10MB</p>
                            <button class="btn-upload">Upload image <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 2px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" placeholder="Given Names" style="margin-bottom: 12px;">
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Middle Name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Last Name">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">CSPC Mail</label>
                    <input type="email" class="form-control" placeholder="handle@my.cspc.edu.ph">
                </div>

                <div class="form-group">
                    <label class="form-label">Academic Information</label>
                    <div class="form-row">
                        <div class="form-group">
                            <div class="input-icon-wrap">
                                <input type="text" class="form-control" placeholder="Program" style="color: #1a73e8;">
                                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a73e8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Year & Section">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn-back" id="closeModalBtnBottom"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Back</button>
                    <button class="btn-next" onclick="goToStep(2)">Next</button>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="modal-step" id="step2">
                <div class="form-group">
                    <label class="form-label">Account Details</label>
                    <div class="form-row">
                        <div class="form-group">
                            <div class="input-icon-wrap">
                                <input type="text" class="form-control" placeholder="Role" style="color: #1a73e8;">
                                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a73e8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon-wrap">
                                <input type="text" class="form-control" placeholder="Status" style="color: #1a73e8;">
                                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a73e8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Set Password</label>
                    <div class="input-icon-wrap" style="margin-bottom: 12px;">
                        <input type="password" class="form-control" placeholder="Create Password">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </div>
                    <div class="input-icon-wrap">
                        <input type="password" class="form-control" placeholder="Retype Password">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn-back" onclick="goToStep(1)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Back</button>
                    <button class="btn-next" onclick="goToStep(3)">Save Details</button>
                </div>
            </div>

            <!-- Step 3 (Success) -->
            <div class="modal-step" id="step3">
                <div class="modal-header" style="border:none; margin-bottom: 0;">
                    <div></div> <!-- spacer -->
                </div>
                <div class="success-step">
                    <div class="success-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <h3 class="success-title">User Added Successfully</h3>
                    <p class="success-text">A new user has been successfully created</p>
                    <button class="btn-next btn-full" id="closeModalBtnSuccess">Close</button>
                </div>
            </div>
        </div>
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
import { ref } from 'vue';
import AssignedTaskModal from '../../components/AssignedTaskModal.vue';
import AssignmentWorkspaceModal from '../../components/AssignmentWorkspaceModal.vue';
import NotificationsPopover from '../../components/NotificationsPopover.vue';

const activeTab = ref('overview');
const openDropdown = ref(null);
const isAssignedTaskModalOpen = ref(false);
const isWorkspaceModalOpen = ref(false);
const selectedTask = ref({});

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
