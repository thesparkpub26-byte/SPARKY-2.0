<template>
    <div class="assigned-task-modal-overlay" v-if="isOpen" @click.self="closeModal">
        <div class="assigned-task-card">
            
            <!-- Header -->
            <div class="modal-hdr">
                <h2 class="modal-blue-title">Assigned Task</h2>
                <button class="modal-dots-btn" @click="closeModal" title="Options / Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="1.5"/>
                        <circle cx="12" cy="5" r="1.5"/>
                        <circle cx="12" cy="19" r="1.5"/>
                    </svg>
                </button>
            </div>

            <!-- Section Badge -->
            <div class="badge-container">
                <span class="section-pill-badge">{{ task.section || 'News' }}</span>
            </div>

            <!-- Article Title -->
            <h1 class="task-article-title">{{ task.title || 'Enrollment Update for Second Semester' }}</h1>

            <!-- Dashed Divider -->
            <div class="dashed-divider"></div>

            <!-- Metadata Grid -->
            <div class="meta-grid-2x2">
                <div class="meta-item">
                    <span class="meta-label">Coverage</span>
                    <span class="meta-val-bold">{{ task.coverage || 'AY 2025 - 2026 Issue 1' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Deadline</span>
                    <span class="meta-val-bold">{{ task.deadline || 'Apr 16 • 5:00 PM' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Collaborators</span>
                    <div class="collaborator-avatars-row">
                        <img src="https://picsum.photos/100?random=101" alt="Collaborator" class="collab-avatar" />
                        <img src="https://picsum.photos/100?random=102" alt="Collaborator" class="collab-avatar" />
                    </div>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Priority</span>
                    <span class="priority-pill-badge moderate">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                        {{ task.priority || 'Moderate' }}
                    </span>
                </div>
            </div>

            <!-- Description Box -->
            <div class="description-section">
                <h4 class="description-hdr">Description</h4>
                <div class="description-card-box">
                    <p class="description-text">
                        <strong>Article:</strong> {{ task.articleDesc || 'Write a clear update about second semester enrollment, including dates, procedures, and registrar announcements.' }}
                    </p>
                    <p class="description-text" style="margin-top: 8px;">
                        <strong>Thumbnail:</strong> {{ task.thumbnailDesc || 'Create a clean thumbnail using campus-related visuals with readable title placement...' }}
                    </p>
                    
                    <div class="read-full-row">
                        <button class="read-full-link" @click="handleOpenWorkspace">Read full text</button>
                    </div>
                </div>
            </div>

            <!-- Actions Footer -->
            <div class="modal-actions-footer">
                <button class="btn-grey-pill" @click="closeModal">Close</button>
                <button class="btn-blue-pill" @click="handleOpenWorkspace">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="7" y1="17" x2="17" y2="7"></line>
                        <polyline points="7 7 17 7 17 17"></polyline>
                    </svg>
                    Open Workspace
                </button>
            </div>

        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

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

const task = computed(() => props.taskData || {});

const closeModal = () => {
    emit('close');
};

const handleOpenWorkspace = () => {
    emit('open-workspace', task.value);
};
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

.modal-dots-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 6px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.modal-dots-btn:hover {
    background: #f1f5f9;
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

.collaborator-avatars-row {
    display: flex;
    align-items: center;
}

.collab-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid #ffffff;
    margin-right: -8px;
    object-fit: cover;
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
</style>
