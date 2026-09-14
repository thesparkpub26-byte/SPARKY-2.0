<template>
    <div class="notifications-wrapper" ref="wrapperRef">
        <!-- Bell Icon Button -->
        <button class="bell-btn-trigger" @click.prevent="togglePopover" title="Notifications">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="bell-badge" v-if="unreadCount > 0">{{ unreadCount }}</span>
        </button>

        <!-- Notifications Card Popover -->
        <div class="notifications-card-popover" v-if="isOpen">
            <!-- Header -->
            <div class="notif-header">
                <h3 class="notif-title">Notifications ({{ unreadCount }})</h3>
                <button class="mark-read-btn" @click="markAllAsRead" v-if="unreadCount > 0">Mark all as read</button>
            </div>

            <div class="notif-body-scroll">
                <!-- Section 1: New -->
                <div class="notif-section">
                    <div class="notif-section-label">New</div>
                    
                    <!-- Item 1 -->
                    <div class="notif-item" :class="{ unread: notif1Unread }" @click="notif1Unread = false">
                        <div class="notif-icon-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </div>
                        <div class="notif-content">
                            <div class="notif-item-hdr">
                                <span class="notif-item-title">Endorsed to EIC</span>
                                <span class="unread-blue-dot" v-if="notif1Unread"></span>
                            </div>
                            <p class="notif-item-desc"><strong>The Rise of Campus Creatives</strong> was endorsed for final review.</p>
                            <span class="notif-item-time">2h ago</span>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="notif-item" :class="{ unread: notif2Unread }" @click="notif2Unread = false">
                        <div class="notif-icon-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 14 4 9 9 4"></polyline>
                                <path d="M20 20v-7a4 4 0 0 0-4-4H4"></path>
                            </svg>
                        </div>
                        <div class="notif-content">
                            <div class="notif-item-hdr">
                                <span class="notif-item-title">Returned for Revision</span>
                                <span class="unread-blue-dot" v-if="notif2Unread"></span>
                            </div>
                            <p class="notif-item-desc"><strong>Enrollment Update for Second Semester</strong> was returned for revision.</p>
                            <span class="notif-item-time">9:41 AM</span>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Earlier This Week -->
                <div class="notif-section">
                    <div class="notif-section-label">Earlier This Week</div>
                    
                    <!-- Item 3 -->
                    <div class="notif-item read-item">
                        <div class="notif-icon-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </div>
                        <div class="notif-content">
                            <div class="notif-item-hdr">
                                <span class="notif-item-title">Endorsed to EIC</span>
                            </div>
                            <p class="notif-item-desc"><strong>Campus Wi-Fi Expansion Project</strong> was endorsed for final review.</p>
                            <span class="notif-item-time">Apr 8 • 11:05 AM</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="notif-footer">
                <button class="see-all-btn" @click="isOpen = false">
                    See all
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const isOpen = ref(false);
const notif1Unread = ref(true);
const notif2Unread = ref(true);
const wrapperRef = ref(null);

const unreadCount = computed(() => {
    let count = 0;
    if (notif1Unread.value) count++;
    if (notif2Unread.value) count++;
    return count;
});

const togglePopover = () => {
    isOpen.value = !isOpen.value;
};

const markAllAsRead = () => {
    notif1Unread.value = false;
    notif2Unread.value = false;
};

const handleClickOutside = (event) => {
    if (wrapperRef.value && !wrapperRef.value.contains(event.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
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
}

.bell-btn-trigger {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0f172a;
    cursor: pointer;
    position: relative;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    transition: all 0.2s;
}

.bell-btn-trigger:hover {
    background-color: #f8fafc;
    border-color: #cbd5e1;
}

.bell-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background-color: #1d6bf3;
    color: #ffffff;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 11px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ffffff;
    font-family: 'Manrope', sans-serif;
}

.notifications-card-popover {
    position: absolute;
    top: calc(100% + 12px);
    right: 0;
    z-index: 9999;
    width: 380px;
    background: #ffffff;
    border-radius: 24px;
    padding: 24px;
    box-sizing: border-box;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15), 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
    animation: popoverFadeIn 0.2s ease-out;
    text-align: left;
}

@keyframes popoverFadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

.notif-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
}

.notif-title {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    font-family: 'Manrope', sans-serif;
}

.mark-read-btn {
    background: none;
    border: none;
    font-size: 13px;
    font-weight: 700;
    color: #1d6bf3;
    cursor: pointer;
    padding: 0;
    font-family: 'Manrope', sans-serif;
    transition: opacity 0.2s;
}

.mark-read-btn:hover {
    opacity: 0.8;
}

.notif-body-scroll {
    max-height: 420px;
    overflow-y: auto;
    padding-right: 4px;
}

.notif-body-scroll::-webkit-scrollbar {
    width: 4px;
}

.notif-body-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.notif-section-label {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin-top: 18px;
    margin-bottom: 12px;
    font-family: 'Manrope', sans-serif;
}

.notif-item {
    background: #f0f5ff;
    border-radius: 18px;
    padding: 16px;
    display: flex;
    gap: 14px;
    align-items: flex-start;
    position: relative;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.2s;
}

.notif-item.read-item,
.notif-item:not(.unread) {
    background: #f8fafc;
}

.notif-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.notif-icon-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background-color: #1d6bf3;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notif-content {
    flex: 1;
    min-width: 0;
}

.notif-item-hdr {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.notif-item-title {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    font-family: 'Manrope', sans-serif;
}

.unread-blue-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #1d6bf3;
    position: absolute;
    top: 18px;
    right: 18px;
}

.notif-item-desc {
    font-size: 13px;
    color: #475569;
    margin: 0 0 6px 0;
    line-height: 1.45;
    font-family: 'Manrope', sans-serif;
}

.notif-item-desc strong {
    color: #0f172a;
    font-weight: 700;
}

.notif-item-time {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 600;
    font-family: 'Manrope', sans-serif;
}

.notif-footer {
    display: flex;
    justify-content: flex-end;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
    margin-top: 8px;
}

.see-all-btn {
    background: none;
    border: none;
    font-size: 14px;
    font-weight: 700;
    color: #1d6bf3;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-family: 'Manrope', sans-serif;
}

.see-all-btn:hover {
    color: #1557b0;
}
</style>
