<template>
    <div class="assigned-task-modal-overlay" v-if="isOpen" @click.self="closeModal">
        <div class="assigned-task-card">

            <!-- Header -->
            <div class="modal-hdr">
                <h2 class="modal-blue-title">Assigned Task</h2>
                <button class="modal-close-btn" @click="closeModal" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <!-- Section Badge -->
            <div class="badge-container">
                <span class="section-pill-badge">{{ (task.section && typeof task.section === 'object') ? (task.section.name || 'News') : (task.section || 'News') }}</span>
            </div>

            <!-- Article Title -->
            <h1 class="task-article-title">{{ task.title || 'Enrollment Update for Second Semester' }}</h1>

            <!-- Dashed Divider -->
            <div class="dashed-divider"></div>

            <!-- Metadata Grid -->
            <div class="meta-grid-2x2">
                <div class="meta-item">
                    <span class="meta-label">Deadline</span>
                    <span class="meta-val-bold">{{ task.deadline || 'No deadline' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Priority</span>
                    <span class="priority-pill-badge" :class="getPriorityClass(task.priority)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                        {{ formatPriority(task.priority) }}
                    </span>
                </div>
                <div class="meta-item full-width-meta" v-if="(writerPill && writerPill.name) || (artistPill && artistPill.name) || crewPills.length">
                    <span class="meta-label">Collaborators / Assignees</span>
                    <div class="collab-pills-row">
                        <!-- Writer pill -->
                        <div class="collab-person-pill" v-if="writerPill && writerPill.name" :title="writerPill.name">
                            <img :src="writerPill.avatar" :alt="writerPill.name" class="collab-person-avatar" />
                            <div class="collab-person-info">
                                <span class="collab-person-name">{{ writerPill.name }}</span>
                                <span class="collab-person-role">{{ writerPill.role }}</span>
                            </div>
                        </div>
                        <!-- Artist / PJ pill -->
                        <div class="collab-person-pill artist" v-if="artistPill && artistPill.name && !isVideoTask" :title="artistPill.name">
                            <img :src="artistPill.avatar" :alt="artistPill.name" class="collab-person-avatar" />
                            <div class="collab-person-info">
                                <span class="collab-person-name">{{ artistPill.name }}</span>
                                <span class="collab-person-role">{{ artistPill.role }}</span>
                            </div>
                        </div>
                        <!-- Video crew (Videographer / Video Editor) -->
                        <div class="collab-person-pill artist" v-for="member in crewPills" :key="member.role + member.name" :title="member.name">
                            <img :src="member.avatar" :alt="member.name" class="collab-person-avatar" />
                            <div class="collab-person-info">
                                <span class="collab-person-name">{{ member.name }}</span>
                                <span class="collab-person-role">{{ member.role }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Box -->
            <div class="description-section">
                <h4 class="description-hdr">Description</h4>
                <div class="description-card-box">
                    <p class="description-text">
                        {{ cleanDescription }}
                    </p>

                    <div class="read-full-row">
                        <button class="read-full-link" type="button" @click="isFullDescriptionModalOpen = true">Read full text</button>
                    </div>
                </div>
            </div>

            <!-- Actions Footer -->
            <div class="modal-actions-footer">
                <button class="btn-grey-pill" @click="closeModal">Close</button>
                <button v-if="task.canOpenWorkspace !== false" class="btn-blue-pill" @click="handleOpenWorkspace">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="7" y1="17" x2="17" y2="7"></line>
                        <polyline points="7 7 17 7 17 17"></polyline>
                    </svg>
                    Open Workspace
                </button>
            </div>

        </div>

        <!-- Full Description Sub-Modal -->
        <div class="full-desc-overlay" v-if="isFullDescriptionModalOpen && task && task.id" @click.self="isFullDescriptionModalOpen = false">
            <div class="full-desc-card">
                <div class="full-desc-hdr">
                    <h3 class="full-desc-title">Description & Guidelines</h3>
                    <button class="modal-x-btn" type="button" @click="isFullDescriptionModalOpen = false" title="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="full-desc-content">
                    <div class="desc-block">
                        <p class="desc-block-text">{{ cleanDescription }}</p>
                    </div>
                </div>
                <div class="full-desc-footer">
                    <button class="btn-blue-pill" type="button" @click="isFullDescriptionModalOpen = false" style="width: 100%;">Got it</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    taskData: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['close', 'open-workspace']);

const isFullDescriptionModalOpen = ref(false);
const task = computed(() => props.taskData || {});

// Debug logging - simplified
console.log('Modal component mounted, task:', task.value);

// Closes the description dialog and the modal.
const closeModal = () => {
    isFullDescriptionModalOpen.value = false;
    emit('close');
};

// The CSS class (low, moderate, high, urgent) for a priority badge.
const getPriorityClass = (priority) => {
    try {
        const p = (priority || '').toLowerCase();
        if (p === 'low') return 'low';
        if (p === 'high') return 'high';
        if (p === 'urgent') return 'urgent';
        return 'moderate';
    } catch (e) {
        console.error('getPriorityClass error:', e, priority);
        return 'moderate';
    }
};

// Shows a task priority as Low, Moderate, High or Urgent.
const formatPriority = (priority) => {
    try {
        const p = (priority || '').toLowerCase();
        if (p === 'medium') return 'Moderate';
        if (p === 'urgent') return 'Urgent';
        if (p === 'high') return 'High';
        if (p === 'low') return 'Low';
        return priority ? priority.charAt(0).toUpperCase() + priority.slice(1) : 'Moderate';
    } catch (e) {
        console.error('formatPriority error:', e, priority);
        return 'Moderate';
    }
};

// Opens the task's workspace where the work is done.
const handleOpenWorkspace = () => {
    isFullDescriptionModalOpen.value = false;
    emit('open-workspace', task.value);
};

const allUsers = ref([]);

// Loads the user list, used to show assignees' names and photos.
const fetchUsers = async () => {
    try {
        const token = localStorage.getItem('sparky_token');
        const res = await fetch('/api/users', {
            headers: token ? { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' } : { 'Accept': 'application/json' }
        });
        if (res.ok) {
            const data = await res.json();
            allUsers.value = Array.isArray(data) ? data : (data.users || []);
        }
    } catch (e) {
        console.warn('Could not fetch users in AssignedTaskModal:', e);
    }
};

watch(() => props.isOpen, (newVal) => {
    if (newVal && allUsers.value.length === 0) {
        fetchUsers();
    }
}, { immediate: true });

const cleanDescription = computed(() => {
    let desc = task.value.articleDesc || task.value.description || '';
    if (!desc || typeof desc !== 'string' || desc.trim() === '') {
        return 'Write a clear update according to section guidelines.';
    }
    // If the description contains HTML tags like <div>, strip them so only plain text is shown
    if (/<[a-z][\s\S]*>/i.test(desc)) {
        const stripped = desc.replace(/<[^>]*>/g, ' ').replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
        return stripped || 'Write a clear update according to section guidelines.';
    }
    return desc;
});

// The picture address for a user: the uploaded photo, otherwise a generated avatar.
const getUserAvatar = (userObj) => {
    if (!userObj) return '';
    if (userObj.profile_picture) return '/storage/' + userObj.profile_picture;
    if (userObj.profile_picture_url) return userObj.profile_picture_url;
    if (userObj.avatar) return userObj.avatar;
    return `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(userObj.name || 'User')}&backgroundColor=ffd5dc`;
};

// ── Video workflow ───────────────────────────────────────────────────────────
// Radio Broadcasting tasks list their crew in the notes ("Videographer: X | Video Editor: Y").
const isVideoTask = computed(() => {
    const t = props.taskData || {};
    if (['videography', 'video_editing'].includes(t.type)) return true;
    const section = (t.section && typeof t.section === 'object') ? t.section.name : t.section;
    return String(section || '').toLowerCase() === 'radio broadcasting';
});

const crewPills = computed(() => {
    if (!isVideoTask.value) return [];
    const notes = props.taskData?.notes || '';
    // Reads one "Key: value" field out of the task's notes text.
    const pick = (key) => {
        const match = notes.match(new RegExp(`${key}:\\s*([^|]+)`, 'i'));
        return match ? match[1].trim() : '';
    };
    return [['Videographer', pick('Videographer')], ['Video Editor', pick('Video Editor')]]
        .filter(([, name]) => name)
        .map(([role, name]) => {
            const found = allUsers.value.find(u => u.name && u.name.trim().toLowerCase() === name.toLowerCase());
            return {
                role,
                name,
                avatar: found ? getUserAvatar(found) : `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(name)}&backgroundColor=fdf4ff`
            };
        });
});

// Build writer pill from assignees or paired writer
const writerPill = computed(() => {
    let ass = props.taskData?.writer || props.taskData?.author;
    if (!ass && props.taskData?.assignee) {
        const role = (props.taskData.assignee.role || '').toLowerCase();
        if (role.includes('writer') || role === 'staff_writer' || role === 'section_editor' || role === 'eic') {
            ass = props.taskData.assignee;
        }
    }
    if (!ass && props.taskData?.user) {
        ass = props.taskData.user;
    }
    if (!ass && Array.isArray(props.taskData?.assignees) && props.taskData.assignees.length) {
        const w = props.taskData.assignees.find(a => !(a.role || '').toLowerCase().includes('artist'));
        if (w) ass = w;
    }

    if (ass) {
        if (typeof ass === 'string') {
            const foundUser = allUsers.value.find(u => u.name && u.name.trim().toLowerCase() === ass.trim().toLowerCase());
            if (foundUser) {
                return {
                    name: foundUser.name,
                    role: foundUser.secondary_role || foundUser.role || 'Staff Writer',
                    avatar: getUserAvatar(foundUser)
                };
            }
            return { name: ass, role: 'Staff Writer', avatar: `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(ass)}&backgroundColor=ffd5dc` };
        }
        if (ass.name) {
            const foundUser = allUsers.value.find(u => u.id === ass.id || (u.name && u.name.trim().toLowerCase() === ass.name.trim().toLowerCase()));
            const userWithPic = (ass.profile_picture || ass.profile_picture_url) ? ass : (foundUser || ass);
            return {
                name: ass.name,
                role: ass.secondary_role || ass.role || 'Staff Writer',
                avatar: getUserAvatar(userWithPic)
            };
        }
    }
    return null;
});

// Build artist pill from mediaArtist field or artist assignee
const artistPill = computed(() => {
    let art = props.taskData?.mediaArtist || props.taskData?.artist || props.taskData?.media_artist;
    if (!art && props.taskData?.assignee) {
        const role = (props.taskData.assignee.role || '').toLowerCase();
        if (role.includes('artist') || role === 'staff_artist' || role.includes('photo') || role.includes('broadcaster')) {
            art = props.taskData.assignee;
        }
    }
    if (!art && Array.isArray(props.taskData?.assignees) && props.taskData.assignees.length) {
        const a = props.taskData.assignees.find(item => {
            const r = (item.role || item.secondary_role || '').toLowerCase();
            return r.includes('artist') || r.includes('photo') || r.includes('cartoon') || r.includes('illustrat') || r.includes('video');
        });
        if (a) art = a;
    }

    if (art) {
        if (typeof art === 'string') {
            const foundUser = allUsers.value.find(u => u.name && u.name.trim().toLowerCase() === art.trim().toLowerCase());
            if (foundUser) {
                return {
                    name: foundUser.name,
                    role: foundUser.secondary_role || props.taskData?.mediaArtistRole || (foundUser.role === 'staff_artist' ? 'Staff Artist' : foundUser.role),
                    avatar: getUserAvatar(foundUser)
                };
            }
            return { name: art, role: props.taskData?.mediaArtistRole || 'Staff Artist', avatar: `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(art)}&backgroundColor=fdf4ff` };
        }
        if (art.name) {
            const foundUser = allUsers.value.find(u => u.id === art.id || (u.name && u.name.trim().toLowerCase() === art.name.trim().toLowerCase()));
            const userWithPic = (art.profile_picture || art.profile_picture_url) ? art : (foundUser || art);
            return {
                name: art.name,
                role: art.secondary_role || art.role || props.taskData?.mediaArtistRole || 'Staff Artist',
                avatar: getUserAvatar(userWithPic)
            };
        }
    }
    return null;
});
</script>

<style scoped>
.assigned-task-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(5px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
}

.assigned-task-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 440px;
    padding: 32px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    text-align: left;
}

@keyframes popIn {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-hdr {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.modal-blue-title {
    color: #1d6bf3;
    font-size: 26px;
    font-weight: 800;
    margin: 0;
    letter-spacing: -0.5px;
    font-family: 'Manrope', -apple-system, sans-serif;
}

.modal-close-btn {
    background: #f1f5f9;
    border: none;
    cursor: pointer;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    transition: background 0.2s, color 0.2s;
    flex-shrink: 0;
}

.modal-close-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
}

.badge-container {
    margin-bottom: 10px;
}

.section-pill-badge {
    background-color: #dbeafe;
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    font-family: 'Manrope', sans-serif;
}

.task-article-title {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 16px 0;
    line-height: 1.3;
    letter-spacing: -0.4px;
    font-family: 'Manrope', sans-serif;
}

.dashed-divider {
    border-bottom: 1.5px dashed #e2e8f0;
    margin-bottom: 20px;
}

.meta-grid-2x2 {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 16px 12px;
    margin-bottom: 24px;
}

.meta-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.meta-label {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 600;
    font-family: 'Manrope', sans-serif;
}

.meta-val-bold {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    font-family: 'Manrope', sans-serif;
}

/* Collaborator Pills */
.collab-pills-row {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 2px;
}

.collab-person-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 22px;
    padding: 5px 14px 5px 5px;
    width: fit-content;
    max-width: 100%;
    transition: border-color 0.2s, background 0.2s;
}

.collab-person-pill:hover {
    border-color: #cbd5e1;
    background: #f1f5f9;
}

.collab-person-pill.artist {
    background: #fdf4ff;
    border-color: #e9d5ff;
}

.collab-person-pill.artist:hover {
    background: #f5e8ff;
    border-color: #d8b4fe;
}

.collab-person-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.collab-person-info {
    display: flex;
    flex-direction: column;
    gap: 1px;
    min-width: 0;
}

.collab-person-name {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    font-family: 'Manrope', sans-serif;
}

.collab-person-role {
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    white-space: nowrap;
    font-family: 'Manrope', sans-serif;
}

.priority-pill-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 14px;
    font-size: 12px;
    font-weight: 700;
    font-family: 'Manrope', sans-serif;
    width: fit-content;
}

.priority-pill-badge.moderate {
    background-color: #fef3c7;
    color: #92400e;
}

.priority-pill-badge.low {
    background-color: #d1fae5;
    color: #065f46;
}

.priority-pill-badge.high {
    background-color: #ffe4e6;
    color: #9f1239;
}

.priority-pill-badge.urgent {
    background-color: #fee2e2;
    color: #991b1b;
}

.description-section {
    margin-bottom: 24px;
}

.description-hdr {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 10px 0;
    font-family: 'Manrope', sans-serif;
}

.description-card-box {
    background: #f8fafc;
    border-radius: 16px;
    padding: 16px;
    border: 1px solid #f1f5f9;
}

.description-text {
    font-size: 13px;
    color: #475569;
    margin: 0;
    line-height: 1.45;
    font-family: 'Manrope', sans-serif;
}

.description-text strong {
    color: #0f172a;
}

.read-full-row {
    display: flex;
    justify-content: flex-end;
    margin-top: 12px;
}

.read-full-link {
    background: none;
    border: none;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    cursor: pointer;
    padding: 0;
    font-family: 'Manrope', sans-serif;
}

.read-full-link:hover {
    color: #1d6bf3;
}

.modal-actions-footer {
    display: grid;
    grid-template-columns: 1fr 1.3fr;
    gap: 12px;
}

.btn-grey-pill {
    background-color: #f1f5f9;
    color: #475569;
    border: none;
    padding: 12px;
    border-radius: 30px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    font-family: 'Manrope', sans-serif;
}

.btn-grey-pill:hover {
    background-color: #e2e8f0;
    color: #0f172a;
}

.btn-blue-pill {
    background-color: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 12px 20px;
    border-radius: 30px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3);
    transition: all 0.2s;
    font-family: 'Manrope', sans-serif;
}

.btn-blue-pill:hover {
    background-color: #1557b0;
    box-shadow: 0 6px 18px rgba(29, 107, 243, 0.4);
}

.full-desc-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(6px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.full-desc-card {
    background: #ffffff;
    border-radius: 24px;
    width: 100%;
    max-width: 520px;
    padding: 28px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
    animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.full-desc-hdr {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.full-desc-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    font-family: 'Manrope', sans-serif;
}

.modal-x-btn {
    background: #f1f5f9;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.modal-x-btn:hover {
    background: #e2e8f0;
}

.full-desc-content {
    max-height: 380px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding-right: 4px;
}

.desc-block {
    background: #f8fafc;
    border-radius: 14px;
    padding: 14px 16px;
    border: 1px solid #e2e8f0;
}

.desc-block-title {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 6px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.desc-block-text {
    font-size: 13.5px;
    color: #475569;
    margin: 0;
    line-height: 1.55;
    white-space: pre-wrap;
    font-family: 'Manrope', sans-serif;
}

.full-desc-footer {
    margin-top: 4px;
}
</style>
