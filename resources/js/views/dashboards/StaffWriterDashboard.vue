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

                <!-- Recent Submissions Nav Item -->
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
                        Recent Submissions
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
                <img src="https://picsum.photos/200?random=15" alt="Profile">
                <div class="user-info">
                    <span class="role-badge" style="background-color: #1a73e8; color: white;">News Writer</span>
                    <h4>News Writer's Name</h4>
                    <p>newswriter@my.cspc.edu.ph</p>
                </div>
                <button class="settings-btn">
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
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" placeholder="Search">
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
                        <!-- Column 1: Pending (3) -->
                        <div class="kanban-column">
                            <h3 class="column-header">Pending (3)</h3>
                            <div class="cards-container">
                                <div class="task-card card-moderate" @click="openTaskModal({ title: 'Enrollment Update for Second Semester', section: 'News', coverage: 'AY 2025 - 2026 Issue 1', deadline: 'Apr 15 • 5:00 PM', priority: 'Moderate' })" style="cursor: pointer;">
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                        </svg>
                                        Moderate
                                    </span>
                                    <h4 class="card-title">Enrollment Update for Second Semester</h4>
                                    <span class="card-subtitle">AY 2025 - 2026 Issue 1</span>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            Apr 15 &bull; 5:00 PM
                                        </div>
                                        <div class="avatar-group">
                                            <img src="https://api.dicebear.com/7.x/lorelei/svg?seed=member1&backgroundColor=ffd5dc"
                                                alt="Assignee" class="assignee-avatar">
                                            <img src="https://api.dicebear.com/7.x/lorelei/svg?seed=member2&backgroundColor=d1fae5"
                                                alt="Assignee" class="assignee-avatar">
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card card-low" @click="openTaskModal({ title: 'Student Election Coverage', section: 'News', coverage: 'Enrollment Period 2026', deadline: 'Apr 13 • 4:00 PM', priority: 'Low' })" style="cursor: pointer;">
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                        </svg>
                                        Low
                                    </span>
                                    <h4 class="card-title">Student Election Coverage</h4>
                                    <span class="card-subtitle">Enrollment Period 2026</span>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            Apr 13 &bull; 4:00 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card card-low" @click="openTaskModal({ title: 'Campus Clean-Up Drive Coverage', section: 'News', coverage: 'AY 2025 - 2026 Issue 2', deadline: 'Apr 18 • 7:30 PM', priority: 'Low' })" style="cursor: pointer;">
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                        </svg>
                                        Low
                                    </span>
                                    <h4 class="card-title">Campus Clean-Up Drive Coverage</h4>
                                    <span class="card-subtitle">AY 2025 - 2026 Issue 2</span>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            Apr 18 &bull; 7:30 PM
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column 2: Ongoing (3) -->
                        <div class="kanban-column">
                            <h3 class="column-header">Ongoing (3)</h3>
                            <div class="cards-container">
                                <div class="task-card card-moderate" @click="openTaskModal({ title: 'Wellness Campaign Launch', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1', deadline: 'Apr 16 • 5:00 PM', priority: 'Moderate' })" style="cursor: pointer;">
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                        </svg>
                                        Moderate
                                    </span>
                                    <h4 class="card-title">Wellness Campaign Launch</h4>
                                    <span class="card-subtitle">AY 2025 - 2026 Issue 1</span>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            Apr 16 &bull; 5:00 PM
                                        </div>
                                        <div class="avatar-group">
                                            <img src="https://api.dicebear.com/7.x/lorelei/svg?seed=member2&backgroundColor=d1fae5"
                                                alt="Assignee" class="assignee-avatar">
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card card-high" @click="openTaskModal({ title: 'Campus Wi-Fi Expansion Project', section: 'News', coverage: 'Tech & Innovation Series', deadline: 'Apr 14 • 5:00 PM', priority: 'High' })" style="cursor: pointer;">
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                        </svg>
                                        High
                                    </span>
                                    <h4 class="card-title">Campus Wi-Fi Expansion Project</h4>
                                    <span class="card-subtitle">Tech &amp; Innovation Series</span>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            Apr 14 &bull; 5:00 PM
                                        </div>
                                        <div class="avatar-group">
                                            <img src="https://api.dicebear.com/7.x/lorelei/svg?seed=member3&backgroundColor=fed7aa"
                                                alt="Assignee" class="assignee-avatar">
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card card-high" @click="openTaskModal({ title: 'New Campus Laboratory Building Opens', section: 'News', coverage: 'Foundation Day 2026', deadline: 'Apr 14 • 8:00 PM', priority: 'High' })" style="cursor: pointer;">
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                        </svg>
                                        High
                                    </span>
                                    <h4 class="card-title">New Campus Laboratory Building Opens</h4>
                                    <span class="card-subtitle">Foundation Day 2026</span>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            Apr 14 &bull; 8:00 PM
                                        </div>
                                        <div class="avatar-group">
                                            <img src="https://api.dicebear.com/7.x/lorelei/svg?seed=member2&backgroundColor=d1fae5"
                                                alt="Assignee" class="assignee-avatar">
                                            <img src="https://api.dicebear.com/7.x/lorelei/svg?seed=member4&backgroundColor=fecdd3"
                                                alt="Assignee" class="assignee-avatar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column 3: Submitted (3) -->
                        <div class="kanban-column">
                            <h3 class="column-header">Submitted (3)</h3>
                            <div class="cards-container">
                                <div class="task-card card-high" @click="openTaskModal({ title: 'The Rise of Campus Creatives', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1', deadline: 'Apr 14 • 10:45 PM', priority: 'High' })" style="cursor: pointer;">
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                        </svg>
                                        High
                                    </span>
                                    <h4 class="card-title">The Rise of Campus Creatives</h4>
                                    <span class="card-subtitle">AY 2025 - 2026 Issue 1</span>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            Apr 14 &bull; 10:45 PM
                                        </div>
                                        <div class="avatar-group">
                                            <img src="https://api.dicebear.com/7.x/lorelei/svg?seed=member2&backgroundColor=d1fae5"
                                                alt="Assignee" class="assignee-avatar">
                                            <img src="https://api.dicebear.com/7.x/lorelei/svg?seed=member4&backgroundColor=fecdd3"
                                                alt="Assignee" class="assignee-avatar">
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card card-moderate" @click="openTaskModal({ title: 'College Fair Highlights', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1', deadline: 'Apr 8 • 6:00 PM', priority: 'Moderate' })" style="cursor: pointer;">
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                        </svg>
                                        Moderate
                                    </span>
                                    <h4 class="card-title">College Fair Highlights</h4>
                                    <span class="card-subtitle">AY 2025 - 2026 Issue 1</span>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            Apr 8 &bull; 6:00 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card card-low" @click="openTaskModal({ title: 'College Fair Attracts Hundreds', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1', deadline: 'Apr 7 • 6:30 PM', priority: 'Low' })" style="cursor: pointer;">
                                    <span class="priority-badge">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                        </svg>
                                        Low
                                    </span>
                                    <h4 class="card-title">College Fair Attracts Hundreds</h4>
                                    <span class="card-subtitle">AY 2025 - 2026 Issue 1</span>
                                    <div class="card-footer">
                                        <div class="date-pill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            Apr 7 &bull; 6:30 PM
                                        </div>
                                        <div class="avatar-group">
                                            <img src="https://api.dicebear.com/7.x/lorelei/svg?seed=member2&backgroundColor=d1fae5"
                                                alt="Assignee" class="assignee-avatar">
                                        </div>
                                    </div>
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
                        <button class="status-filter-btn">
                            Status
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                    </div>
                    <div class="articles-card">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px;">Title</th>
                                    <th>Coverage</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="padding-right: 28px;">Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr @click="openTaskModal({ title: 'Wellness Campaign Launch', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">Wellness Campaign Launch</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 1</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-draft">Draft</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 15 &bull; 3:10 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'Campus Wi-Fi Expansion Project', section: 'News', coverage: 'Tech & Innovation Series' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">Campus Wi-Fi Expansion Project</td>
                                    <td style="color: #64748b;">Tech &amp; Innovation Series</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-for-review">For Review</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 14 &bull; 5:05 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'The Rise of Campus Creatives', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 2' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">The Rise of Campus Creatives</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 2</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-under-revision">Under Revision</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 14 &bull; 10:45 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'New Campus Laboratory Building Opens', section: 'News', coverage: 'Foundation Day 2026' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">New Campus Laboratory Building Opens</td>
                                    <td style="color: #64748b;">Foundation Day 2026</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-published">Published</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 12 &bull; 4:00 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'College Fair Highlights', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">College Fair Highlights</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 1</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-published">Published</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 8 &bull; 6:00 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'College Fair Attracts Hundreds', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">College Fair Attracts Hundreds</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 1</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-published">Published</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 7 &bull; 5:30 PM</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container">
                        <div class="pagination-pill">
                            <button class="page-btn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                                Previous
                            </button>
                            <a href="#" class="page-number active">1</a>
                            <a href="#" class="page-number">2</a>
                            <a href="#" class="page-number">3</a>
                            <a href="#" class="page-number">4</a>
                            <a href="#" class="page-number">5</a>
                            <span class="page-dots">&bull;&bull;&bull;</span>
                            <button class="page-btn">
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>
                            <div class="page-results-count">
                                Showing <strong>6</strong> results
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RECENT SUBMISSIONS TAB -->
                <div v-show="activeTab === 'submissions'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header" style="margin-bottom: 4px;">
                        <h1 class="page-title">Recent Submissions</h1>
                    </div>
                    <div class="articles-card">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px;">Title</th>
                                    <th>Press Work</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="padding-right: 28px;">Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr @click="openTaskModal({ title: 'Wellness Campaign Launch', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">Wellness Campaign Launch</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 1</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-for-review">For Review</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 15 &bull; 3:10 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'Campus Wi-Fi Expansion Project', section: 'News', coverage: 'Tech & Innovation Series' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">Campus Wi-Fi Expansion Project</td>
                                    <td style="color: #64748b;">Tech &amp; Innovation Series</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-for-review">For Review</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 11 &bull; 5:05 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'The Rise of Campus Creatives', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 2' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">The Rise of Campus Creatives</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 2</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-endorsed">Endorsed</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 14 &bull; 10:45 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'New Campus Laboratory Building Opens', section: 'News', coverage: 'Foundation Day 2026' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">New Campus Laboratory Building Opens</td>
                                    <td style="color: #64748b;">Foundation Day 2026</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-published">Published</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 12 &bull; 4:00 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'College Fair Highlights', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">College Fair Highlights</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 1</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-published">Published</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 8 &bull; 6:00 PM</td>
                                </tr>
                                <tr @click="openTaskModal({ title: 'College Fair Attracts Hundreds', section: 'Feature', coverage: 'AY 2025 - 2026 Issue 1' })" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">College Fair Attracts Hundreds</td>
                                    <td style="color: #64748b;">AY 2025 - 2026 Issue 1</td>
                                    <td style="text-align: center;">
                                        <span class="status-pill status-published">Published</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">Apr 7 &bull; 5:30 PM</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container">
                        <div class="pagination-pill">
                            <button class="page-btn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                                Previous
                            </button>
                            <a href="#" class="page-number active">1</a>
                            <a href="#" class="page-number">2</a>
                            <a href="#" class="page-number">3</a>
                            <a href="#" class="page-number">4</a>
                            <a href="#" class="page-number">5</a>
                            <span class="page-dots">&bull;&bull;&bull;</span>
                            <button class="page-btn">
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>
                            <div class="page-results-count">
                                Showing <strong>6</strong> results
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PRESS WORKS TAB -->
                <div v-show="activeTab === 'pressWorks'" style="display: flex; flex-direction: column; gap: 12px; flex-shrink: 0;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
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

                    <div class="pagination-pill">
                        <button class="page-nav" style="border: none; background: none; display: flex; align-items: center; gap: 4px; color: #64748b; font-weight: 500; cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Previous
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
                            Next <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </button>
                        <div class="showing-text" style="color: #64748b; font-size: 13px; margin-left: 16px;">
                            Showing <b>4</b> Press Works
                        </div>
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
    />
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import AssignedTaskModal from '../../components/AssignedTaskModal.vue';
import AssignmentWorkspaceModal from '../../components/AssignmentWorkspaceModal.vue';
import NotificationsPopover from '../../components/NotificationsPopover.vue';
import { signOut as performSignOut } from '../../utils/auth';

const router = useRouter();
const activeTab = ref('tasks');
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
