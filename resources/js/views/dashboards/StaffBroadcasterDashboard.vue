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
                <!-- My Tasks -->
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

                <!-- My Videos -->
                <a href="#" class="nav-item" :class="{ active: activeTab === 'videos' }" @click.prevent="activeTab = 'videos'">
                    <div class="nav-item-left">
                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="23 7 16 12 23 17 23 7"/>
                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                        </svg>
                        My Videos
                    </div>
                </a>

                <!-- Press Works -->
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
                <img :src="user.profile_picture ? ('/storage/' + user.profile_picture) : (user.profile_picture_url || 'https://picsum.photos/200?random=15')" alt="Profile">
                <div class="user-info">
                    <span class="role-badge" style="background-color: #1a73e8; color: white;">{{ user.secondary_role || 'Staff Broadcaster' }}</span>
                    <h4>{{ user.name || 'Staff Broadcaster' }}</h4>
                    <p>{{ user.email || 'broadcaster@thesparkpub.com' }}</p>
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

            <div class="content-container fade-in">

                <!-- MY TASKS TAB -->
                <div v-show="activeTab === 'tasks'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header" style="margin-bottom: 4px;">
                        <h1 class="page-title">My Tasks</h1>
                    </div>
                    <div class="kanban-board">
                        <div v-for="column in kanbanColumns" :key="column.key" class="kanban-column">
                            <h3 class="column-header">{{ column.label }} ({{ column.tasks.length }})</h3>
                            <div class="cards-container">
                                <div
                                    v-for="task in column.tasks"
                                    :key="task.id"
                                    class="task-card-enhanced"
                                    :class="getPriorityClass(task.priority)"
                                    @click="openTaskModal(task)"
                                    style="cursor: pointer;"
                                >
                                    <div class="task-card-header">
                                        <span class="priority-badge">
                                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" /></svg>
                                            {{ formatPriorityLabel(task.priority) }}
                                        </span>
                                        <div style="display: flex; gap: 6px; align-items: center;">
                                            <span v-if="task.status === 'returned'" class="status-badge" style="background: #fee2e2; color: #dc2626; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 9999px;">Returned</span>
                                            <span class="section-badge">{{ task.roleLabel }}</span>
                                        </div>
                                    </div>
                                    <h4 class="card-title">{{ task.title }}</h4>
                                    <div class="task-card-details">
                                        <div class="task-detail-item">
                                            <span class="detail-label">Deadline:</span>
                                            <span class="detail-value">{{ task.deadline }}</span>
                                        </div>
                                        <div class="task-detail-item" v-if="task.status === 'returned' && task.revisionNotes" style="margin-top: 4px;">
                                            <span class="detail-label" style="color: #dc2626; font-weight: 600;">Revision Notes:</span>
                                            <span class="detail-value" style="color: #991b1b; font-size: 12px; line-height: 1.4;">{{ task.revisionNotes }}</span>
                                        </div>
                                    </div>
                                    <div class="task-card-footer">
                                        <span class="date-pill" v-if="task.deadline && task.deadline !== 'No deadline'">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                            {{ task.deadline }}
                                        </span>
                                        <div class="avatar-group">
                                            <img :src="avatarFor(task.raw.assignee, column.avatarBg)" :alt="task.raw.assignee?.name || 'Assignee'" class="assignee-avatar">
                                        </div>
                                    </div>
                                </div>
                                <div v-if="column.tasks.length === 0" class="empty-column-state">{{ column.empty }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MY VIDEOS TAB -->
                <div v-show="activeTab === 'videos'" style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <h1 class="page-title" style="margin-bottom: 0;">My Videos</h1>
                        <div class="filter-pills-group">
                            <div class="custom-filter" @click.stop>
                                <button type="button" class="filter-trigger" @click="activeVideoFilter = !activeVideoFilter">
                                    <span>{{ videoFilterLabel }}</span>
                                    <svg :class="{ rotated: activeVideoFilter }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6" /></svg>
                                </button>
                                <div v-if="activeVideoFilter" class="filter-menu">
                                    <button v-for="option in videoFilterOptions" :key="option.value" type="button" :class="{ selected: selectedVideoFilter === option.value }" @click="selectVideoFilter(option.value)">{{ option.label }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="articles-card">
                        <table class="articles-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 28px;">Title</th>
                                    <th>My Role</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="padding-right: 28px;">Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="video in paginatedVideos" :key="video.id" @click="openVideo(video)" style="cursor: pointer;">
                                    <td style="padding-left: 28px; font-weight: 700;">{{ video.title }}</td>
                                    <td><span class="section-badge">{{ video.roleLabel }}</span></td>
                                    <td style="text-align: center;">
                                        <span class="status-pill" :class="getVideoStatusClass(video.status)">{{ formatVideoStatus(video.status) }}</span>
                                    </td>
                                    <td style="padding-right: 28px; color: #64748b;">{{ formatDateTime(video.lastUpdated) }}</td>
                                </tr>
                                <tr v-if="filteredVideos.length === 0">
                                    <td colspan="4" style="text-align: center; padding: 40px; color: #64748b;">No videos found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-container" v-if="filteredVideos.length > 0">
                        <div class="pagination-pill">
                            <button class="page-btn" @click="videosPage--" :disabled="videosPage === 1">Previous</button>
                            <a v-for="page in Math.min(totalVideoPages, 5)" :key="page" href="#" class="page-number" :class="{ active: page === videosPage }" @click.prevent="videosPage = page">{{ page }}</a>
                            <span v-if="totalVideoPages > 5" class="page-dots">&bull;&bull;&bull;</span>
                            <button class="page-btn" @click="videosPage++" :disabled="videosPage === totalVideoPages">Next</button>
                            <div class="page-results-count">
                                Showing <strong>{{ paginatedVideos.length }}</strong> of {{ filteredVideos.length }} videos
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PRESS WORKS TAB -->
                <div v-show="activeTab === 'pressWorks'" style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
                    <div class="page-header" style="margin-bottom: 4px;">
                        <h1 class="page-title">Press Works</h1>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div v-for="yearGroup in shownAcademicYears" :key="yearGroup.academic_year" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #ffffff;">
                            <div class="folder-header-btn" @click="toggleStaffYear(yearGroup.academic_year)" style="padding: 16px; cursor: pointer; display: flex; align-items: center; gap: 12px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; user-select: none;">
                                <svg class="folder-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                                <span class="folder-title" style="font-weight: 700; flex: 1; color: #0f172a;">{{ yearGroup.academic_year }}</span>
                                <span style="color: #64748b; font-size: 13px; font-weight: 600;">{{ (yearGroup.monitoring_sheets || []).length }} Monitoring Sheet{{ (yearGroup.monitoring_sheets || []).length !== 1 ? 's' : '' }}</span>
                                <svg class="chevron-icon" :style="{ transform: staffExpandedYears[yearGroup.academic_year] ? 'rotate(180deg)' : 'rotate(0deg)', transition: 'transform 0.2s' }" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                            <div v-show="staffExpandedYears[yearGroup.academic_year]" style="padding: 16px 20px; display: flex; flex-direction: column; gap: 8px;">
                                <div v-for="sheet in yearGroup.monitoring_sheets" :key="sheet.id" class="monitoring-sheet-row" @click="openMonitoringSheet(sheet)" style="padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 12px; transition: all 0.2s;">
                                    <span :class="`pub-badge pub-${(sheet.publication_type || '').toLowerCase()}`" style="padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; white-space: nowrap;">{{ sheet.publication_type }}</span>
                                    <span style="flex: 1; font-weight: 600; color: #0f172a;">{{ sheet.title }}</span>
                                    <span style="color: #94a3b8; font-size: 13px;">{{ formatDate(sheet.created_at) }}</span>
                                </div>
                                <div v-if="(yearGroup.monitoring_sheets || []).length === 0" style="padding: 20px; text-align: center; color: #94a3b8;">No monitoring sheets yet</div>
                            </div>
                        </div>
                    </div>

                    <div v-if="shownAcademicYears.length === 0" style="padding: 40px; text-align: center; color: #94a3b8; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        {{ noPressWorksText(searchQuery.trim() !== '') }}
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Task details (crew members can only view; the presenter can open the workspace) -->
    <AssignedTaskModal
        :is-open="isAssignedTaskModalOpen"
        :task-data="selectedTask"
        @close="isAssignedTaskModalOpen = false"
        @open-workspace="handleOpenWorkspace"
    />

    <!-- Video workspace: Headline, Description, Link to YouTube Video -->
    <VideoWorkspaceModal
        :is-open="isWorkspaceModalOpen"
        :task-data="selectedTask"
        @close="isWorkspaceModalOpen = false; refreshAll();"
        @task-submitted="refreshAll"
        @task-saved-as-draft="refreshAll"
    />

    <!-- Read-only preview for published videos -->
    <ArticlePreviewModal
        :is-open="isPreviewOpen"
        :article-data="selectedPreview"
        read-only
        @close="isPreviewOpen = false"
    />
</template>

<script setup>
import MobileNavToggle from '../../components/MobileNavToggle.vue';
import { makeMatcher, searchAcademicYears, noPressWorksText, useDebouncedSearch } from '../../utils/dashboardSearch';
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { lazyModal } from '../../utils/lazyModal';
import { useRouter } from 'vue-router';
const AssignedTaskModal = lazyModal(() => import('../../components/AssignedTaskModal.vue'));
const VideoWorkspaceModal = lazyModal(() => import('../../components/VideoWorkspaceModal.vue'));
const ArticlePreviewModal = lazyModal(() => import('../../components/ArticlePreviewModal.vue'));
import NotificationsPopover from '../../components/NotificationsPopover.vue';
import { signOut as performSignOut } from '../../utils/auth';
import { parseNotesField, VIDEO_SECTION, VIDEO_CREDIT_LABELS, buildVideoPreviewData } from '../../utils/video';

const router = useRouter();
const activeTab = ref('tasks');
const { input: searchInput, query: searchQuery } = useDebouncedSearch();
const matches = makeMatcher(searchQuery);
const searchPlaceholder = computed(() => ({
    tasks: 'Search my tasks',
    videos: 'Search my videos',
    pressWorks: 'Search press works',
}[activeTab.value] || 'Search'));
const user = ref(JSON.parse(localStorage.getItem('sparky_user') || '{}'));
const token = localStorage.getItem('sparky_token');
const authHeaders = { Authorization: `Bearer ${token}`, Accept: 'application/json' };

const isAssignedTaskModalOpen = ref(false);
const isWorkspaceModalOpen = ref(false);
const isPreviewOpen = ref(false);
const selectedTask = ref({});
const selectedPreview = ref({});

const allTasks = ref([]);
const videos = ref([]);

// ── Formatting helpers ───────────────────────────────────────────────────────
const formatPriorityLabel = (priority) => {
    const p = (priority || '').toLowerCase();
    if (p === 'medium') return 'Moderate';
    return p ? p.charAt(0).toUpperCase() + p.slice(1) : 'Moderate';
};

const getPriorityClass = (priority) => {
    const p = (priority || '').toLowerCase();
    if (p === 'low') return 'card-low';
    if (p === 'high') return 'card-high';
    if (p === 'urgent') return 'card-urgent';
    return 'card-moderate';
};

const formatDeadline = (dateStr, dueTimeStr) => {
    if (!dateStr) return 'No deadline';
    const cleanDate = String(dateStr).split('T')[0].split(' ')[0];
    const parts = cleanDate.split('-');
    let datePart;
    if (parts.length === 3) {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        datePart = `${months[parseInt(parts[1], 10) - 1] || ''} ${parseInt(parts[2], 10)}`;
    } else {
        const d = new Date(dateStr);
        datePart = !isNaN(d.getTime()) ? d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : String(dateStr);
    }

    let timePart = '';
    if (dueTimeStr) {
        if (/AM|PM/i.test(dueTimeStr)) {
            timePart = dueTimeStr;
        } else if (dueTimeStr.includes(':')) {
            const [h, m] = dueTimeStr.split(':').map(Number);
            timePart = `${h % 12 || 12}:${m < 10 ? `0${m}` : m} ${h >= 12 ? 'PM' : 'AM'}`;
        }
    }
    return timePart ? `${datePart} • ${timePart}` : datePart;
};

const formatDate = (dateStr) => {
    const d = new Date(dateStr);
    return isNaN(d.getTime()) ? '' : d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const formatDateTime = (dateStr) => {
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return '';
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
        + ' • ' + d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
};

const avatarFor = (person, background = 'ffd5dc') => {
    if (person?.profile_picture) return `/storage/${person.profile_picture}`;
    if (person?.profile_picture_url) return person.profile_picture_url;
    return `https://api.dicebear.com/7.x/lorelei/svg?seed=${encodeURIComponent(person?.name || 'broadcaster')}&backgroundColor=${background}`;
};

const cleanBaseTitle = (title) => String(title || '')
    .replace(/\s*\([^)]*(visuals|video|graphics|photo|illustration|pj)[^)]*\)/i, '')
    .trim()
    .toLowerCase();

// The role I hold on a given task
const roleLabelFor = (task) => {
    if (task.type === 'videography') return 'Videographer';
    if (task.type === 'video_editing') return 'Video Editor';
    if (task.type === 'layout') return 'Videographer';
    return 'News Presenter';
};

// The presenter's task is the only one that opens the workspace
const canOpenWorkspace = (task) => task.type === 'writing';

// ── Tasks ────────────────────────────────────────────────────────────────────
const articleById = computed(() => Object.fromEntries(videos.value.map(v => [v.id, v])));

// The presenter holding the writing task of the same video (crew tasks link by article, else by title)
const presenterTaskFor = (task) => {
    if (task.type === 'writing') return task;
    if (task.article_id) {
        const sibling = allTasks.value.find(o => o.type === 'writing' && o.article_id === task.article_id);
        if (sibling) return sibling;
    }
    const base = cleanBaseTitle(task.title);
    return allTasks.value.find(o => o.type === 'writing' && cleanBaseTitle(o.title) === base) || null;
};

const linkedArticleFor = (task) => {
    const articleId = task.article_id || presenterTaskFor(task)?.article_id;
    return task.article || (articleId ? articleById.value[articleId] : null) || null;
};

const myTasks = computed(() => {
    const q = searchQuery.value.trim().toLowerCase();
    return allTasks.value
        .filter(t => t.assignee_id === user.value.id && t.type !== 'editing')
        // Once the video is published the job is done, so it drops off the working queue
        .filter(t => linkedArticleFor(t)?.status !== 'published')
        .map(t => {
            const dueTime = parseNotesField(t.notes, 'Due Time');
            const presenter = presenterTaskFor(t)?.assignee || null;
            return {
                id: t.id,
                title: t.title,
                type: t.type,
                section: VIDEO_SECTION,
                roleLabel: roleLabelFor(t),
                coverage: parseNotesField(t.notes, 'Coverage'),
                dueTime,
                deadline: formatDeadline(t.deadline, dueTime),
                priority: t.priority || 'medium',
                status: t.status || 'pending',
                articleDesc: t.description || '',
                notes: t.notes || '',
                revisionNotes: parseNotesField(t.notes, 'Revision Notes'),
                returned_by_role: t.returned_by_role,
                article_id: t.article_id,
                canOpenWorkspace: canOpenWorkspace(t),
                writer: presenter ? {
                    name: presenter.name,
                    role: presenter.secondary_role || 'News Presenter',
                    avatar: avatarFor(presenter, 'd1fae5')
                } : null,
                raw: t
            };
        })
        .filter(t => !q || t.title.toLowerCase().includes(q) || t.roleLabel.toLowerCase().includes(q))
        .sort((a, b) => new Date(b.raw.created_at) - new Date(a.raw.created_at));
});

const kanbanColumns = computed(() => [
    { key: 'pending', label: 'Pending', empty: 'No pending tasks', avatarBg: 'ffd5dc', tasks: myTasks.value.filter(t => t.status === 'pending') },
    { key: 'ongoing', label: 'Ongoing', empty: 'No ongoing tasks', avatarBg: 'd1fae5', tasks: myTasks.value.filter(t => ['in_progress', 'ongoing', 'returned'].includes(t.status)) },
    { key: 'submitted', label: 'Submitted', empty: 'No submitted tasks', avatarBg: 'fecdd3', tasks: myTasks.value.filter(t => ['submitted', 'completed'].includes(t.status)) },
]);

const fetchTasks = async () => {
    if (!token) return;
    try {
        const res = await fetch('/api/tasks', { headers: authHeaders });
        if (res.ok) {
            const data = await res.json();
            allTasks.value = Array.isArray(data) ? data : [];
        }
    } catch (err) {
        console.warn('Could not fetch tasks:', err);
    }
};

const fetchVideos = async () => {
    if (!token) return;
    try {
        const res = await fetch('/api/articles?type=video', { headers: authHeaders });
        if (res.ok) {
            const data = await res.json();
            videos.value = Array.isArray(data) ? data : [];
        }
    } catch (err) {
        console.warn('Could not fetch videos:', err);
    }
};

const refreshAll = async () => {
    await fetchTasks();
    await fetchVideos();
};

// ── My Videos ────────────────────────────────────────────────────────────────
const videosPerPage = 8;
const videosPage = ref(1);
const activeVideoFilter = ref(false);
const selectedVideoFilter = ref('all');

const videoFilterOptions = [
    { value: 'all', label: 'All Status' },
    { value: 'draft', label: 'Draft' },
    { value: 'submitted', label: 'For Review' },
    { value: 'endorsed', label: 'Endorsed' },
    { value: 'rejected', label: 'Rejected' },
    { value: 'scheduled', label: 'Scheduled' },
    { value: 'published', label: 'Published' },
];
const videoFilterLabel = computed(() => videoFilterOptions.find(o => o.value === selectedVideoFilter.value)?.label || 'Status');

const selectVideoFilter = (value) => {
    selectedVideoFilter.value = value;
    activeVideoFilter.value = false;
    videosPage.value = 1;
};

// Videos I presented, was assigned to as crew, or was credited on by the Head Broadcaster
const myVideos = computed(() => {
    const q = searchQuery.value.trim().toLowerCase();
    return videos.value
        .map(v => {
            const myTask = (v.tasks || []).find(t => t.assignee_id === user.value.id && t.type !== 'editing');
            const myCredits = (v.credits || []).filter(c => c.user_id === user.value.id);
            if (v.author_id !== user.value.id && !myTask && !myCredits.length) return null;

            // Credits (set at final review) describe the real role; fall back to the assignment
            const labels = myCredits.map(c => VIDEO_CREDIT_LABELS[c.role] || c.role);
            if (!labels.length) labels.push(roleLabelFor(v.author_id === user.value.id ? { type: 'writing' } : myTask));
            return {
                id: v.id,
                title: v.title || 'Untitled Video',
                status: v.status,
                roleLabel: [...new Set(labels)].join(', '),
                lastUpdated: v.updated_at || v.created_at,
                task: allTasks.value.find(t => t.id === myTask?.id) || allTasks.value.find(t => t.article_id === v.id && t.assignee_id === user.value.id) || null,
                raw: v
            };
        })
        .filter(Boolean)
        .filter(v => !q || v.title.toLowerCase().includes(q))
        .sort((a, b) => new Date(b.lastUpdated) - new Date(a.lastUpdated));
});

const filteredVideos = computed(() => selectedVideoFilter.value === 'all'
    ? myVideos.value
    : myVideos.value.filter(v => v.status === selectedVideoFilter.value));
const totalVideoPages = computed(() => Math.max(1, Math.ceil(filteredVideos.value.length / videosPerPage)));
const paginatedVideos = computed(() => {
    const start = (videosPage.value - 1) * videosPerPage;
    return filteredVideos.value.slice(start, start + videosPerPage);
});

watch([searchQuery], () => { videosPage.value = 1; });

const VIDEO_STATUS_LABELS = { draft: 'Draft', submitted: 'For Review', under_review: 'Under Review', endorsed: 'Endorsed', approved: 'Approved', rejected: 'Returned', scheduled: 'Scheduled', published: 'Published' };
const VIDEO_STATUS_CLASSES = { draft: 'status-draft', submitted: 'status-for-review', under_review: 'status-under-revision', endorsed: 'status-endorsed', approved: 'status-approved', rejected: 'status-rejected', scheduled: 'status-for-review', published: 'status-published' };
const formatVideoStatus = (status) => VIDEO_STATUS_LABELS[status] || (status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Draft');
const getVideoStatusClass = (status) => VIDEO_STATUS_CLASSES[status] || 'status-draft';

const extractFileName = (url) => String(url || '').split('/').pop() || 'file';

// Same read-only preview shape the other dashboards use
const buildPreviewData = (item) => {
    const itemTasks = Array.isArray(item.tasks) ? item.tasks : [];
    const writingTask = itemTasks.find(t => t.type === 'writing') || null;
    return {
        ...item,
        raw_status: item.status,
        coverage: parseNotesField(writingTask?.notes, 'Coverage') || '',
        attached_files: item.cover_image ? [{ name: extractFileName(item.cover_image), type: 'image', url: item.cover_image }] : [],
    };
};

const openVideo = (video) => {
    // Published videos, and ones I'm only credited on (no task of my own), open as a preview
    if (video.status === 'published' || !video.task) {
        selectedPreview.value = buildPreviewData(video.raw);
        isPreviewOpen.value = true;
    } else if (video.task) {
        const task = myTasks.value.find(t => t.id === video.task.id);
        if (task) openTaskModal(task);
    }
};

// ── Modals ───────────────────────────────────────────────────────────────────
const openTaskModal = (task = {}) => {
    selectedTask.value = {
        ...task,
        title: task.title || 'Untitled Task',
        section: VIDEO_SECTION,
        deadline: task.deadline || 'No deadline',
        priority: task.priority || 'Moderate',
        articleDesc: task.articleDesc || '',
        notes: task.notes || '',
        assignees: task.raw?.assignee ? [{
            name: task.raw.assignee.name,
            secondary_role: task.raw.assignee.secondary_role || '',
            role: task.raw.assignee.role || 'staff_broadcaster',
            avatar: avatarFor(task.raw.assignee)
        }] : []
    };
    isAssignedTaskModalOpen.value = true;
};

const handleOpenWorkspace = (taskData) => {
    // Only the assigned news presenter may open the workspace
    if (taskData?.canOpenWorkspace === false) return;
    isAssignedTaskModalOpen.value = false;
    selectedTask.value = taskData || selectedTask.value;
    isWorkspaceModalOpen.value = true;
};

// ── Press Works ──────────────────────────────────────────────────────────────
const staffAcademicYears = ref([]);
const shownAcademicYears = computed(() => searchAcademicYears(staffAcademicYears.value, matches));
const staffExpandedYears = ref({});

const fetchPressWorks = async () => {
    if (!token) return;
    try {
        const res = await fetch('/api/press-works', { headers: authHeaders });
        if (res.ok) {
            const data = await res.json();
            if (Array.isArray(data.academic_years)) {
                staffAcademicYears.value = data.academic_years;
                staffExpandedYears.value = Object.fromEntries(data.academic_years.map(y => [y.academic_year, false]));
            }
        }
    } catch (err) {
        console.warn('Could not fetch press works:', err);
    }
};

const toggleStaffYear = (year) => {
    staffExpandedYears.value[year] = !staffExpandedYears.value[year];
};

const openMonitoringSheet = (sheet) => {
    window.open(sheet?.id ? `/monitoring-sheet/${sheet.id}` : '/monitoring-sheet', '_blank');
};

// ── Lifecycle ────────────────────────────────────────────────────────────────
watch(activeTab, (tab) => {
    if (tab === 'tasks' || tab === 'videos') refreshAll();
});

const onProfileUpdated = (e) => {
    if (e.detail) user.value = e.detail;
};

const closeVideoFilter = (event) => {
    if (activeVideoFilter.value && !event.target.closest('.custom-filter')) activeVideoFilter.value = false;
};

onMounted(async () => {
    if (token) {
        try {
            const res = await fetch('/api/me', { headers: authHeaders });
            if (res.ok) {
                const fresh = await res.json();
                user.value = fresh;
                localStorage.setItem('sparky_user', JSON.stringify(fresh));
            }
        } catch (e) {
            console.warn('Could not refresh profile:', e);
        }
    }

    await refreshAll();
    await fetchPressWorks();

    window.addEventListener('sparky:profile-updated', onProfileUpdated);
    document.addEventListener('click', closeVideoFilter);
});

onUnmounted(() => {
    window.removeEventListener('sparky:profile-updated', onProfileUpdated);
    document.removeEventListener('click', closeVideoFilter);
});
</script>
