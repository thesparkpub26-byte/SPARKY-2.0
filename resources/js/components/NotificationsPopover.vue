<template>
    <div class="notifications-wrapper" ref="wrapperRef">
        <!-- Bell Icon Button Trigger -->
        <button class="bell-btn-trigger" @click.prevent="togglePopover" title="Notifications" :aria-expanded="isOpen">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="bell-badge" v-if="unreadCount > 0">{{ unreadCount > 99 ? '99+' : unreadCount }}</span>
        </button>

        <!-- Notifications Card Popover Dropdown -->
        <transition name="popover-fade">
            <div class="notifications-card-popover" v-if="isOpen">
                <!-- Header -->
                <div class="notif-header">
                    <div class="notif-header-title-wrap">
                        <h3 class="notif-title">Notifications</h3>
                        <span class="notif-unread-pill" v-if="unreadCount > 0">{{ unreadCount }} new</span>
                    </div>
                    <div class="notif-header-actions">
                        <button class="mark-read-btn" @click.stop="markAllAsRead" v-if="unreadCount > 0" :disabled="loadingAction" title="Mark all as read">
                            Mark all as read
                        </button>
                    </div>
                </div>

                <!-- Notifications Body Scroll -->
                <div class="notif-body-scroll">
                    <!-- Loading skeleton -->
                    <div v-if="loading && notifications.length === 0" class="notif-loading-state">
                        <div class="notif-skeleton-item" v-for="n in 3" :key="n">
                            <div class="skeleton-circle"></div>
                            <div class="skeleton-lines">
                                <div class="skeleton-line-title"></div>
                                <div class="skeleton-line-desc"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty state -->
                    <div v-else-if="notifications.length === 0" class="notif-empty-state">
                        <div class="empty-icon-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                        </div>
                        <p class="empty-title">No notifications yet</p>
                        <p class="empty-subtitle">You're all caught up with your updates!</p>
                    </div>

                    <!-- Items grouped by New and Earlier -->
                    <div v-else>
                        <!-- New Section (Unread) -->
                        <div class="notif-section" v-if="newNotifications.length > 0">
                            <div class="notif-section-label">
                                <span>New</span>
                                <span class="section-count-badge">{{ newNotifications.length }}</span>
                            </div>
                            
                            <div 
                                v-for="item in newNotifications.slice(0, 5)" 
                                :key="item.id" 
                                class="notif-item unread" 
                                @click="handleItemClick(item)"
                            >
                                <div class="notif-icon-circle" :class="getTypeClass(item.type)">
                                    <svg v-if="isEndorsementType(item.type)" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                    </svg>
                                    <svg v-else-if="isPressWorkType(item.type)" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="3" y1="9" x2="21" y2="9"></line>
                                        <line x1="9" y1="21" x2="9" y2="9"></line>
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                    </svg>
                                </div>
                                <div class="notif-content">
                                    <div class="notif-item-hdr">
                                        <span class="notif-item-title">{{ item.title }}</span>
                                        <div class="notif-item-top-right">
                                            <span class="unread-blue-dot"></span>
                                            <button 
                                                class="notif-item-delete-btn" 
                                                @click.stop="deleteNotification(item, $event)" 
                                                title="Delete notification" 
                                                aria-label="Delete"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="notif-item-desc" v-html="formatMessage(item.message)"></p>
                                    <span class="notif-item-time">{{ formatTime(item.created_at) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Earlier Section (Read) -->
                        <div class="notif-section" v-if="earlierNotifications.length > 0">
                            <div class="notif-section-label">
                                <span>Earlier</span>
                            </div>
                            
                            <div 
                                v-for="item in earlierNotifications.slice(0, 5)" 
                                :key="item.id" 
                                class="notif-item read-item" 
                                @click="handleItemClick(item)"
                            >
                                <div class="notif-icon-circle read-icon" :class="getTypeClass(item.type)">
                                    <svg v-if="isEndorsementType(item.type)" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                    </svg>
                                    <svg v-else-if="isPressWorkType(item.type)" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="3" y1="9" x2="21" y2="9"></line>
                                        <line x1="9" y1="21" x2="9" y2="9"></line>
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                    </svg>
                                </div>
                                <div class="notif-content">
                                    <div class="notif-item-hdr">
                                        <span class="notif-item-title">{{ item.title }}</span>
                                        <button 
                                            class="notif-item-delete-btn" 
                                            @click.stop="deleteNotification(item, $event)" 
                                            title="Delete notification" 
                                            aria-label="Delete"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="notif-item-desc" v-html="formatMessage(item.message)"></p>
                                    <span class="notif-item-time">{{ formatTime(item.created_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="notif-footer">
                    <button class="see-all-btn" @click="openSeeAllModal">
                        See all
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </button>
                </div>
            </div>
        </transition>

        <!-- "See All Notifications" Modal (Not too big, not too small) -->
        <Teleport to="body">
            <transition name="modal-fade">
                <div class="notif-modal-overlay" v-if="isSeeAllModalOpen" @click.self="closeSeeAllModal">
                    <div class="notif-modal-dialog">
                        <!-- Modal Header -->
                        <div class="notif-modal-header">
                            <div class="modal-hdr-left">
                                <div class="modal-icon-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="notif-modal-title">All Notifications</h2>
                                    <p class="notif-modal-sub">Stay updated with endorsements and press work</p>
                                </div>
                            </div>
                            <div class="modal-hdr-right">
                                <button class="modal-mark-all-btn" @click="markAllAsRead" v-if="unreadCount > 0" title="Mark all as read">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Mark all as read
                                </button>
                                <button class="modal-clear-all-btn" @click="clearAllNotifications" v-if="notifications.length > 0" title="Delete all notifications">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                    Clear all
                                </button>
                                <button class="modal-close-btn" @click="closeSeeAllModal" aria-label="Close modal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Filter Tabs -->
                        <div class="notif-modal-tabs">
                            <button 
                                class="modal-tab-btn" 
                                :class="{ active: activeTab === 'all' }" 
                                @click="activeTab = 'all'"
                            >
                                All
                                <span class="tab-badge">{{ notifications.length }}</span>
                            </button>
                            <button 
                                class="modal-tab-btn" 
                                :class="{ active: activeTab === 'unread' }" 
                                @click="activeTab = 'unread'"
                            >
                                Unread
                                <span class="tab-badge unread-badge" v-if="unreadCount > 0">{{ unreadCount }}</span>
                                <span class="tab-badge" v-else>0</span>
                            </button>
                            <button 
                                class="modal-tab-btn" 
                                :class="{ active: activeTab === 'endorsements' }" 
                                @click="activeTab = 'endorsements'"
                            >
                                Endorsements
                                <span class="tab-badge">{{ endorsementCount }}</span>
                            </button>
                            <button 
                                class="modal-tab-btn" 
                                :class="{ active: activeTab === 'press_works' }" 
                                @click="activeTab = 'press_works'"
                            >
                                Press Works
                                <span class="tab-badge">{{ pressWorkCount }}</span>
                            </button>
                        </div>

                        <!-- Modal Notifications List -->
                        <div class="notif-modal-list">
                            <!-- Empty Filter State -->
                            <div v-if="filteredModalNotifications.length === 0" class="modal-empty-state">
                                <div class="modal-empty-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                </div>
                                <p class="modal-empty-title">No notifications in {{ getTabLabel(activeTab) }}</p>
                                <p class="modal-empty-desc">You're up to date! New updates will appear here automatically.</p>
                            </div>

                            <!-- Filtered Notification Cards -->
                            <div 
                                v-for="item in filteredModalNotifications" 
                                :key="item.id" 
                                class="modal-notif-card" 
                                :class="{ 'is-unread': !item.read_at }"
                                @click="handleItemClick(item)"
                            >
                                <div class="modal-notif-icon-col">
                                    <div class="notif-icon-circle" :class="[getTypeClass(item.type), { 'read-icon': !!item.read_at }]">
                                        <svg v-if="isEndorsementType(item.type)" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                        </svg>
                                        <svg v-else-if="isPressWorkType(item.type)" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="3" y1="9" x2="21" y2="9"></line>
                                            <line x1="9" y1="21" x2="9" y2="9"></line>
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="modal-notif-main">
                                    <div class="modal-notif-row">
                                        <div class="modal-notif-heading-wrap">
                                            <span class="modal-notif-tag" :class="getTypeTagClass(item.type)">
                                                {{ getTypeTag(item.type) }}
                                            </span>
                                            <h4 class="modal-notif-title">{{ item.title }}</h4>
                                        </div>
                                        <div class="modal-notif-right-cluster">
                                            <div class="modal-notif-time-badge">
                                                <span class="unread-dot" v-if="!item.read_at"></span>
                                                {{ formatTime(item.created_at) }}
                                            </div>
                                            <button 
                                                class="modal-notif-delete-btn" 
                                                @click.stop="deleteNotification(item, $event)" 
                                                title="Delete notification"
                                                aria-label="Delete notification"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="modal-notif-desc" v-html="formatMessage(item.message)"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="notif-modal-footer">
                            <span class="modal-footer-stats">
                                Showing {{ filteredModalNotifications.length }} of {{ notifications.length }} notifications
                            </span>
                            <button class="modal-done-btn" @click="closeSeeAllModal">Close</button>
                        </div>
                    </div>
                </div>
            </transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const isOpen = ref(false);
const isSeeAllModalOpen = ref(false);
const activeTab = ref('all');
const loading = ref(false);
const loadingAction = ref(false);
const wrapperRef = ref(null);

// Notifications state
const notifications = ref([]);

// Computed counts
const unreadCount = computed(() => {
    return notifications.value.filter(n => !n.read_at).length;
});

const newNotifications = computed(() => {
    return notifications.value.filter(n => !n.read_at);
});

const earlierNotifications = computed(() => {
    return notifications.value.filter(n => !!n.read_at);
});

const isEndorsementType = (type) => {
    return [
        'article_endorsed', 
        'article_submitted', 
        'article_approved', 
        'article_rejected'
    ].includes(type);
};

const isPressWorkType = (type) => {
    return [
        'press_work_update', 
        'task_assigned', 
        'task_submitted', 
        'task_returned', 
        'task_completed'
    ].includes(type);
};

const endorsementCount = computed(() => {
    return notifications.value.filter(n => isEndorsementType(n.type)).length;
});

const pressWorkCount = computed(() => {
    return notifications.value.filter(n => isPressWorkType(n.type)).length;
});

const filteredModalNotifications = computed(() => {
    if (activeTab.value === 'unread') {
        return notifications.value.filter(n => !n.read_at);
    }
    if (activeTab.value === 'endorsements') {
        return notifications.value.filter(n => isEndorsementType(n.type));
    }
    if (activeTab.value === 'press_works') {
        return notifications.value.filter(n => isPressWorkType(n.type));
    }
    return notifications.value;
});

// Helpers
const getTypeClass = (type) => {
    if (isEndorsementType(type)) return 'type-endorsement';
    if (isPressWorkType(type)) return 'type-presswork';
    return 'type-general';
};

const getTypeTag = (type) => {
    if (isEndorsementType(type)) return 'Endorsement';
    if (isPressWorkType(type)) return 'Press Work';
    return 'Update';
};

const getTypeTagClass = (type) => {
    if (isEndorsementType(type)) return 'tag-endorsement';
    if (isPressWorkType(type)) return 'tag-presswork';
    return 'tag-general';
};

const getTabLabel = (tab) => {
    if (tab === 'unread') return 'Unread';
    if (tab === 'endorsements') return 'Endorsements';
    if (tab === 'press_works') return 'Press Works';
    return 'All';
};

// Messages carry text people typed (task and article titles, notes), and they are shown as HTML so the
// quoted title can be bold. Everything is escaped first; only the bold tags we add ourselves stay.
const escapeHtml = (text) => String(text)
    .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;').replace(/'/g, '&#39;');

const formatMessage = (msg) => {
    if (!msg) return '';
    return escapeHtml(msg).replace(/&#39;([^&]+?)&#39;/g, '<strong>$1</strong>');
};

const formatTime = (dateString) => {
    if (!dateString) return 'Just now';
    try {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMinutes = Math.floor(diffMs / (1000 * 60));
        const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
        const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));

        if (diffMinutes < 1) return 'Just now';
        if (diffMinutes < 60) return `${diffMinutes}m ago`;
        if (diffHours < 24) return `${diffHours}h ago`;
        if (diffDays === 1) return 'Yesterday';
        if (diffDays < 7) return `${diffDays}d ago`;

        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    } catch {
        return dateString;
    }
};

// Read/delete requests still in flight. A refetch must wait for them, otherwise it returns
// the old state and the notification the user just read reappears as unread.
const pendingWrites = new Set();
const trackWrite = (promise) => {
    pendingWrites.add(promise);
    promise.finally(() => pendingWrites.delete(promise));
    return promise;
};

// API calls
const fetchNotifications = async () => {
    const token = localStorage.getItem('sparky_token');
    if (!token) {
        notifications.value = [];
        return;
    }

    try {
        loading.value = true;
        await Promise.allSettled([...pendingWrites]);
        const res = await fetch('/api/notifications?per_page=100', {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json'
            }
        });

        if (res.ok) {
            const data = await res.json();
            notifications.value = data.data || [];
        } else {
            notifications.value = [];
        }
    } catch (e) {
        notifications.value = [];
    } finally {
        loading.value = false;
    }
};

const markAllAsRead = async () => {
    const nowIso = new Date().toISOString();
    notifications.value.forEach(item => {
        if (!item.read_at) item.read_at = nowIso;
    });

    const token = localStorage.getItem('sparky_token');
    if (!token) return;

    try {
        loadingAction.value = true;
        await trackWrite(fetch('/api/notifications/read-all', {
            method: 'POST',
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json',
                'Content-Type': 'application/json'
            }
        }));
    } catch (err) {
        console.error('Failed to mark all notifications as read:', err);
    } finally {
        loadingAction.value = false;
    }
};

const deleteNotification = async (item, event) => {
    if (event) event.stopPropagation();

    // Remove from local reactive state
    const index = notifications.value.findIndex(n => n.id === item.id);
    if (index !== -1) {
        notifications.value.splice(index, 1);
    }

    const token = localStorage.getItem('sparky_token');
    if (token && typeof item.id === 'number') {
        try {
            await trackWrite(fetch(`/api/notifications/${item.id}`, {
                method: 'DELETE',
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json'
                }
            }));
        } catch (err) {
            console.error('Failed to delete notification:', err);
        }
    }
};

const clearAllNotifications = async () => {
    notifications.value = [];
    const token = localStorage.getItem('sparky_token');
    if (token) {
        try {
            await trackWrite(fetch('/api/notifications', {
                method: 'DELETE',
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json'
                }
            }));
        } catch (err) {
            console.error('Failed to clear notifications:', err);
        }
    }
};

const handleItemClick = async (item) => {
    if (!item.read_at) {
        item.read_at = new Date().toISOString();
        const token = localStorage.getItem('sparky_token');
        if (token && typeof item.id === 'number') {
            try {
                const res = await trackWrite(fetch(`/api/notifications/${item.id}/read`, {
                    method: 'PATCH',
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json'
                    }
                }));
                // Server refused: show it as unread again instead of pretending it was saved
                if (!res.ok) item.read_at = null;
            } catch (err) {
                item.read_at = null;
                console.error('Failed to mark notification as read:', err);
            }
        }
    }
};

// UI Toggles
const togglePopover = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        fetchNotifications();
    }
};

const openSeeAllModal = () => {
    isOpen.value = false;
    isSeeAllModalOpen.value = true;
    activeTab.value = 'all';
    fetchNotifications();
};

const closeSeeAllModal = () => {
    isSeeAllModalOpen.value = false;
};

const handleClickOutside = (event) => {
    if (wrapperRef.value && !wrapperRef.value.contains(event.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    fetchNotifications();
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
.notifications-wrapper {
    position: relative;
    display: inline-block;
    font-family: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Bell Trigger */
.bell-btn-trigger {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0f172a;
    cursor: pointer;
    position: relative;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.bell-btn-trigger:hover {
    background-color: #f8fafc;
    border-color: #cbd5e1;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
}

.bell-badge {
    position: absolute;
    top: -3px;
    right: -3px;
    background-color: #1d6bf3;
    color: #ffffff;
    border-radius: 9999px;
    min-width: 19px;
    height: 19px;
    padding: 0 4px;
    font-size: 11px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ffffff;
    font-family: 'Manrope', sans-serif;
    box-shadow: 0 2px 4px rgba(29, 107, 243, 0.3);
    animation: pulseBadge 2s infinite ease-in-out;
}

@keyframes pulseBadge {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.06); }
}

/* Dropdown Popover */
.notifications-card-popover {
    position: absolute;
    top: calc(100% + 12px);
    right: 0;
    z-index: 9999;
    width: 390px;
    max-width: 90vw;
    background: #ffffff;
    border-radius: 24px;
    padding: 22px 22px 18px 22px;
    box-sizing: border-box;
    box-shadow: 0 20px 45px rgba(15, 23, 42, 0.16), 0 4px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid #e2e8f0;
    text-align: left;
}

.popover-fade-enter-active,
.popover-fade-leave-active {
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
}

.popover-fade-enter-from,
.popover-fade-leave-to {
    opacity: 0;
    transform: translateY(-10px) scale(0.97);
}

/* Header */
.notif-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 14px;
    border-bottom: 1px solid #f1f5f9;
}

.notif-header-title-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
}

.notif-title {
    font-size: 19px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.02em;
}

.notif-unread-pill {
    background: #eff6ff;
    color: #1d6bf3;
    font-size: 11px;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 9999px;
    border: 1px solid #dbeafe;
}

.notif-header-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}

.mark-read-btn {
    background: none;
    border: none;
    font-size: 13px;
    font-weight: 700;
    color: #1d6bf3;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 8px;
    font-family: 'Manrope', sans-serif;
    transition: all 0.2s ease;
}

.mark-read-btn:hover {
    background: #eff6ff;
    color: #1557b0;
}

/* Scroll Area */
.notif-body-scroll {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 4px;
    margin-top: 6px;
}

.notif-body-scroll::-webkit-scrollbar {
    width: 5px;
}

.notif-body-scroll::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}

.notif-body-scroll::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}

/* Sections */
.notif-section {
    margin-bottom: 12px;
}

.notif-section-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 800;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-top: 14px;
    margin-bottom: 10px;
}

.section-count-badge {
    background: #1d6bf3;
    color: #ffffff;
    font-size: 10px;
    font-weight: 800;
    padding: 1px 6px;
    border-radius: 9999px;
}

/* Notification Items */
.notif-item {
    background: #f8fafc;
    border: 1px solid transparent;
    border-radius: 16px;
    padding: 13px 14px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
    position: relative;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.notif-item.unread {
    background: #f0f6ff;
    border-color: #dbeafe;
}

.notif-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
    background: #f1f5f9;
}

.notif-item.unread:hover {
    background: #e6f0fd;
}

.notif-icon-circle {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #ffffff;
    background: #1d6bf3;
    box-shadow: 0 2px 6px rgba(29, 107, 243, 0.25);
}

.notif-icon-circle.type-endorsement {
    background: linear-gradient(135deg, #1d6bf3 0%, #3b82f6 100%);
}

.notif-icon-circle.type-presswork {
    background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
    box-shadow: 0 2px 6px rgba(139, 92, 246, 0.25);
}

.notif-icon-circle.type-general {
    background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
}

.notif-icon-circle.read-icon {
    background: #e2e8f0;
    color: #64748b;
    box-shadow: none;
}

.notif-content {
    flex: 1;
    min-width: 0;
}

.notif-item-hdr {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3px;
}

.notif-item-title {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.3;
}

.notif-item-top-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.unread-blue-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #1d6bf3;
    flex-shrink: 0;
    box-shadow: 0 0 0 2px rgba(29, 107, 243, 0.2);
}

.notif-item-delete-btn {
    background: transparent;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 3px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    opacity: 0.7;
    transition: all 0.2s;
}

.notif-item:hover .notif-item-delete-btn {
    opacity: 1;
}

.notif-item-delete-btn:hover {
    color: #ef4444;
    background: #fee2e2;
}

.notif-item-desc {
    font-size: 12.5px;
    color: #475569;
    margin: 0 0 5px 0;
    line-height: 1.4;
    word-break: break-word;
}

:deep(.notif-item-desc strong),
:deep(.modal-notif-desc strong) {
    color: #0f172a;
    font-weight: 700;
}

.notif-item-time {
    font-size: 11.5px;
    color: #94a3b8;
    font-weight: 600;
}

/* Empty State */
.notif-empty-state {
    text-align: center;
    padding: 36px 16px;
}

.empty-icon-circle {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px auto;
}

.empty-title {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 4px 0;
}

.empty-subtitle {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}

/* Skeleton Loading */
.notif-skeleton-item {
    display: flex;
    gap: 12px;
    padding: 12px;
    margin-bottom: 8px;
}

.skeleton-circle {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: #f1f5f9;
    animation: skeletonPulse 1.5s infinite ease-in-out;
}

.skeleton-lines {
    flex: 1;
}

.skeleton-line-title {
    height: 14px;
    width: 60%;
    background: #f1f5f9;
    border-radius: 4px;
    margin-bottom: 6px;
    animation: skeletonPulse 1.5s infinite ease-in-out;
}

.skeleton-line-desc {
    height: 12px;
    width: 90%;
    background: #f1f5f9;
    border-radius: 4px;
    animation: skeletonPulse 1.5s infinite ease-in-out;
}

@keyframes skeletonPulse {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
}

/* Popover Footer */
.notif-footer {
    display: flex;
    justify-content: flex-end;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
    margin-top: 6px;
}

.see-all-btn {
    background: none;
    border: none;
    font-size: 13.5px;
    font-weight: 800;
    color: #1d6bf3;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 6px;
    border-radius: 8px;
    font-family: 'Manrope', sans-serif;
    transition: all 0.2s;
}

.see-all-btn:hover {
    background: #eff6ff;
    color: #1557b0;
}

/* ==========================================================================
   "SEE ALL NOTIFICATIONS" MODAL (Balanced: Not too big, not too small)
   ========================================================================== */
.notif-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    box-sizing: border-box;
    font-family: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.notif-modal-dialog {
    background: #ffffff;
    width: 92%;
    max-width: 580px;
    max-height: 82vh;
    border-radius: 24px;
    box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(15, 23, 42, 0.05);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: modalScaleIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes modalScaleIn {
    from { opacity: 0; transform: scale(0.95) translateY(8px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.2s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}

/* Modal Header */
.notif-modal-header {
    padding: 22px 24px 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
}

.modal-hdr-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.modal-icon-badge {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: #eff6ff;
    color: #1d6bf3;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dbeafe;
}

.notif-modal-title {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.02em;
}

.notif-modal-sub {
    font-size: 13px;
    color: #64748b;
    margin: 2px 0 0 0;
    font-weight: 500;
}

.modal-hdr-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-mark-all-btn {
    background: #eff6ff;
    color: #1d6bf3;
    border: 1px solid #dbeafe;
    padding: 7px 11px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: 'Manrope', sans-serif;
    transition: all 0.2s;
}

.modal-mark-all-btn:hover {
    background: #1d6bf3;
    color: #ffffff;
    border-color: #1d6bf3;
}

.modal-clear-all-btn {
    background: #fef2f2;
    color: #ef4444;
    border: 1px solid #fee2e2;
    padding: 7px 11px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: 'Manrope', sans-serif;
    transition: all 0.2s;
}

.modal-clear-all-btn:hover {
    background: #ef4444;
    color: #ffffff;
    border-color: #ef4444;
}

.modal-close-btn {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-close-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #cbd5e1;
}

/* Modal Tabs */
.notif-modal-tabs {
    display: flex;
    gap: 6px;
    padding: 12px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
    overflow-x: auto;
}

.notif-modal-tabs::-webkit-scrollbar {
    display: none;
}

.modal-tab-btn {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #64748b;
    font-size: 13px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 20px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    transition: all 0.2s;
    font-family: 'Manrope', sans-serif;
}

.modal-tab-btn:hover {
    border-color: #cbd5e1;
    color: #0f172a;
}

.modal-tab-btn.active {
    background: #0f172a;
    border-color: #0f172a;
    color: #ffffff;
}

.tab-badge {
    background: #f1f5f9;
    color: #475569;
    font-size: 11px;
    font-weight: 800;
    padding: 1px 6px;
    border-radius: 9999px;
}

.modal-tab-btn.active .tab-badge {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}

.tab-badge.unread-badge {
    background: #1d6bf3;
    color: #ffffff;
}

/* Modal List */
.notif-modal-list {
    flex: 1;
    overflow-y: auto;
    padding: 16px 24px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.notif-modal-list::-webkit-scrollbar {
    width: 6px;
}

.notif-modal-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.modal-notif-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px;
    display: flex;
    gap: 14px;
    align-items: flex-start;
    cursor: pointer;
    position: relative;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.modal-notif-card:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    transform: translateY(-1px);
}

.modal-notif-card.is-unread {
    background: #f0f6ff;
    border-color: #bfdbfe;
}

.modal-notif-card.is-unread:hover {
    background: #e6f0fd;
}

.modal-notif-main {
    flex: 1;
    min-width: 0;
}

.modal-notif-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
    gap: 8px;
}

.modal-notif-heading-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.modal-notif-tag {
    font-size: 11px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.modal-notif-tag.tag-endorsement {
    background: #dbeafe;
    color: #1d4ed8;
}

.modal-notif-tag.tag-presswork {
    background: #ede9fe;
    color: #6d28d9;
}

.modal-notif-tag.tag-general {
    background: #e0f2fe;
    color: #0369a1;
}

.modal-notif-title {
    font-size: 14.5px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    line-height: 1.3;
}

.modal-notif-right-cluster {
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-notif-time-badge {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.unread-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background-color: #1d6bf3;
}

.modal-notif-delete-btn {
    background: transparent;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.modal-notif-card:hover .modal-notif-delete-btn {
    color: #64748b;
}

.modal-notif-delete-btn:hover {
    color: #ef4444 !important;
    background: #fee2e2;
}

.modal-notif-desc {
    font-size: 13.5px;
    color: #475569;
    margin: 0;
    line-height: 1.45;
}

/* Modal Empty */
.modal-empty-state {
    text-align: center;
    padding: 40px 20px;
}

.modal-empty-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px auto;
}

.modal-empty-title {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 6px 0;
}

.modal-empty-desc {
    font-size: 13px;
    color: #64748b;
    margin: 0;
    max-width: 320px;
    margin: 0 auto;
    line-height: 1.4;
}

/* Modal Footer */
.notif-modal-footer {
    padding: 14px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top: 1px solid #f1f5f9;
    background: #ffffff;
}

.modal-footer-stats {
    font-size: 12.5px;
    color: #94a3b8;
    font-weight: 600;
}

.modal-done-btn {
    background: #0f172a;
    color: #ffffff;
    border: none;
    padding: 8px 18px;
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Manrope', sans-serif;
    transition: background 0.2s;
}

.modal-done-btn:hover {
    background: #1e293b;
}

/* Responsive */
@media (max-width: 640px) {
    .notifications-card-popover {
        width: 340px;
        right: -40px;
    }

    .notif-modal-dialog {
        max-width: 95%;
        max-height: 90vh;
        border-radius: 20px;
    }

    .notif-modal-header {
        padding: 18px 18px 14px 18px;
    }

    .notif-modal-tabs {
        padding: 10px 18px;
    }

    .notif-modal-list {
        padding: 12px 18px;
    }

    .notif-modal-footer {
        padding: 12px 18px;
    }
}
</style>
