<template>
    <div class="workspace-modal-overlay" v-if="isOpen" @click.self="closeModal">
        <div class="workspace-modal-card">
            
            <!-- Modal Header -->
            <div class="modal-hdr">
                <div class="modal-hdr-left">
                    <h2 class="modal-blue-title">Artist Workspace</h2>
                    <span v-if="saveFeedback" class="save-feedback-badge">{{ saveFeedback }}</span>
                </div>
                <button class="modal-x-btn" @click="closeModal" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Task / Article Information -->
            <div class="article-meta-hdr">
                <span class="section-pill-badge">{{ sectionName }}</span>
                <h1 class="article-main-title">{{ task.title || 'Untitled Visual Assignment' }}</h1>
            </div>

            <!-- Navigation Tabs Header -->
            <div class="workspace-tabs-nav">
                <button class="tab-nav-btn" :class="{ active: currentTab === 'assets' }" @click="currentTab = 'assets'">
                    Visual Assets
                    <span class="tab-asset-count" v-if="mediaPreviews.length || thumbnailPreview">
                        {{ (thumbnailPreview ? 1 : 0) + mediaPreviews.length }}
                    </span>
                </button>
                <button class="tab-nav-btn" :class="{ active: currentTab === 'details' }" @click="currentTab = 'details'">
                    Assignment Details
                </button>
            </div>

            <!-- TAB 1: VISUAL ASSETS -->
            <div class="tab-content-body" v-if="currentTab === 'assets'">
                <div class="visuals-container-card">

                    <!-- Assigned Writer Banner -->
                    <div class="writer-info-banner" v-if="writerInfo">
                        <div class="writer-info-left">
                            <img :src="writerInfo.avatar" :alt="writerInfo.name" class="writer-banner-avatar" />
                            <div>
                                <span class="writer-banner-label">Assigned Writer:</span>
                                <h4 class="writer-banner-name">{{ writerInfo.name }} <span class="writer-banner-role">({{ writerInfo.role }})</span></h4>
                            </div>
                        </div>
                        <span class="writer-recipient-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            Recipient of your media
                        </span>
                    </div>

                    <!-- 1. Thumbnail Upload Section -->
                    <div class="asset-group">
                        <div class="asset-hdr">
                            <div>
                                <h4 class="asset-title">Article Thumbnail / Cover Graphic</h4>
                                <p class="asset-subtitle">Upload the main cover image or thumbnail for this story.</p>
                            </div>
                            <span class="asset-format-badge">16:9 or Square Recommended</span>
                        </div>

                        <!-- Thumbnail Preview Card -->
                        <div v-if="thumbnailPreview" class="thumbnail-preview-card">
                            <div class="preview-img-wrap">
                                <img :src="thumbnailPreview" alt="Thumbnail Preview" class="thumbnail-img" />
                                <div class="preview-actions-overlay">
                                    <button type="button" class="preview-action-btn danger" @click="removeThumbnail" title="Remove">Remove</button>
                                </div>
                            </div>
                        </div>

                        <!-- Thumbnail uploading spinner -->
                        <div v-if="isUploadingThumbnail" class="media-uploading-overlay">
                            <span class="upload-spinner"></span>
                            <span>Uploading thumbnail...</span>
                        </div>

                        <!-- Thumbnail Dropzone -->
                        <div 
                            v-else-if="!thumbnailPreview"
                            class="dashed-dropzone" 
                            @dragover.prevent 
                            @drop.prevent="handleThumbnailDrop"
                            @click="triggerThumbnailInput"
                        >
                            <input 
                                type="file" 
                                ref="thumbnailInputRef" 
                                accept="image/*" 
                                class="hidden-file-input" 
                                @change="onThumbnailSelected"
                            />
                            <button type="button" class="btn-blue-pill-small" @click.stop="triggerThumbnailInput">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/><polyline points="16 16 12 12 8 16"/></svg>
                                Browse Thumbnail
                            </button>
                            <p class="dropzone-text"><strong>Drag your cover image here or browse</strong><br><span class="sub">PNG, JPG, WEBP up to 10 MB</span></p>
                        </div>
                    </div>

                    <div class="divider-line"></div>

                    <!-- 2. Media Uploads Section (Up to 3 Photos / Graphics) -->
                    <div class="asset-group">
                        <div class="asset-hdr">
                            <div>
                                <h4 class="asset-title">Media Uploads ({{ mediaPreviews.length }}/3)</h4>
                                <p class="asset-subtitle">Upload photos, illustrations, info-graphics, and visual materials.</p>
                            </div>
                            <span class="asset-format-badge">Up to 3 Visuals</span>
                        </div>

                        <!-- Media Previews Grid -->
                        <div v-if="mediaPreviews.length > 0" class="media-grid-container">
                            <div 
                                v-for="(media, idx) in mediaPreviews" 
                                :key="idx" 
                                class="media-preview-card"
                            >
                                <img :src="media.url" :alt="media.name || 'Media Upload'" class="media-preview-img" />
                                <button type="button" class="remove-media-circle" @click="removeMedia(idx)" title="Remove Photo">
                                    &times;
                                </button>
                                <span class="media-name-tag">{{ media.name || `Photo ${idx + 1}` }}</span>
                            </div>
                        </div>

                        <!-- Uploading Spinner -->
                        <div v-if="isUploadingMedia" class="media-uploading-overlay">
                            <span class="upload-spinner"></span>
                            <span>Uploading media...</span>
                        </div>

                        <!-- Media Dropzone (if < 3) -->
                        <div 
                            v-if="mediaPreviews.length < 3 && !isUploadingMedia"
                            class="dashed-dropzone" 
                            :class="{ 'compact-dropzone': mediaPreviews.length > 0 }"
                            @dragover.prevent 
                            @drop.prevent="handleMediaDrop"
                            @click="triggerMediaInput"
                        >
                            <input 
                                type="file" 
                                ref="mediaInputRef" 
                                accept="image/*" 
                                multiple
                                class="hidden-file-input" 
                                @change="onMediaSelected"
                            />
                            <button type="button" class="btn-grey-pill-small" @click.stop="triggerMediaInput">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/><polyline points="16 16 12 12 8 16"/></svg>
                                {{ mediaPreviews.length > 0 ? 'Add More Media' : 'Browse Media Files' }}
                            </button>
                            <p class="dropzone-text">
                                <strong>Drag photos/graphics here or browse</strong><br>
                                <span class="sub">Upload up to 3 photos (Max 10 MB each)</span>
                            </p>
                        </div>
                    </div>

                    <!-- Actions Footer -->
                    <div class="visuals-actions-footer">
                        <button type="button" class="btn-secondary-pill" @click="closeModal">
                            Close
                        </button>
                        <div class="actions-right">
                            <span v-if="isAlreadySubmitted" class="already-submitted-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                Already submitted
                            </span>
                            <button type="button" class="btn-outline-pill" @click="saveAsDraft" :disabled="isSaving || isAlreadySubmitted">
                                {{ isSaving ? 'Saving...' : 'Save as Draft' }}
                            </button>
                            <button
                                type="button"
                                class="btn-blue-pill-action"
                                @click="isSubmitModalOpen = true"
                                :disabled="isAlreadySubmitted || (!thumbnailPreview && mediaPreviews.length === 0)"
                                :title="isAlreadySubmitted ? 'Visuals have already been submitted for this task' : ''"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                </svg>
                                {{ isAlreadySubmitted ? 'Submitted' : 'Send to Writer' }}
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- TAB 2: ASSIGNMENT DETAILS -->
            <div class="tab-content-body" v-if="currentTab === 'details'">
                <div class="details-container-card">
                    
                    <!-- Metadata Grid -->
                    <div class="details-grid">
                        <div class="detail-block">
                            <span class="detail-label">Section</span>
                            <span class="detail-val-bold">{{ sectionName }}</span>
                        </div>
                        <div class="detail-block">
                            <span class="detail-label">Deadline</span>
                            <span class="detail-val-bold">{{ task.deadline || 'No deadline specified' }}</span>
                        </div>
                        <div class="detail-block">
                            <span class="detail-label">Priority</span>
                            <span class="priority-pill-badge" :class="task.priority || 'medium'">
                                {{ formatPriority(task.priority) }}
                            </span>
                        </div>
                    </div>

                    <!-- Description & Requirements Box -->
                    <div class="notes-card-box">
                        <div class="notes-hdr-row">
                            <h4 class="notes-hdr">
                                <span class="role-icon-circle blue">📝</span>
                                Visual Requirements & Task Description
                            </h4>
                        </div>
                        <div class="notes-body-text">
                            {{ task.articleDesc || task.description || 'Create visuals, graphics, or photographs according to section guidelines.' }}
                        </div>
                    </div>

                    <!-- Assigned Writer Card -->
                    <div class="notes-card-box" v-if="writerInfo">
                        <div class="notes-hdr-row">
                            <h4 class="notes-hdr">
                                <span class="role-icon-circle green">✍️</span>
                                Paired Staff Writer
                            </h4>
                        </div>
                        <div class="paired-writer-row">
                            <img :src="writerInfo.avatar" :alt="writerInfo.name" class="writer-avatar-large" />
                            <div class="paired-writer-meta">
                                <h4 class="paired-writer-name">{{ writerInfo.name }}</h4>
                                <p class="paired-writer-sub">{{ writerInfo.role }} • Receives submitted visuals in Artist's Submissions</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Bar for Details -->
                    <div class="visuals-actions-footer">
                        <button type="button" class="btn-secondary-pill" @click="currentTab = 'assets'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                            Back to Assets
                        </button>
                        <div class="actions-right">
                            <span v-if="isAlreadySubmitted" class="already-submitted-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                Already submitted
                            </span>
                            <button type="button" class="btn-outline-pill" @click="saveAsDraft" :disabled="isSaving || isAlreadySubmitted">
                                {{ isSaving ? 'Saving...' : 'Save as Draft' }}
                            </button>
                            <button
                                type="button"
                                class="btn-blue-pill-action"
                                @click="isSubmitModalOpen = true"
                                :disabled="isAlreadySubmitted || (!thumbnailPreview && mediaPreviews.length === 0)"
                                :title="isAlreadySubmitted ? 'Visuals have already been submitted for this task' : ''"
                            >
                                {{ isAlreadySubmitted ? 'Submitted' : 'Send to Writer' }}
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- SUBMIT TO WRITER CONFIRMATION MODAL -->
        <div class="submodal-overlay" v-if="isSubmitModalOpen" @click.self="isSubmitModalOpen = false">
            <div class="submodal-card">
                <div class="submit-icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </div>

                <h3 class="submodal-title">Send visual assets to writer?</h3>
                <p class="submodal-desc">
                    Your thumbnail and <strong>{{ mediaPreviews.length }} media file(s)</strong> will be sent directly to <strong>{{ writerInfo?.name || 'the assigned writer' }}</strong>.
                    The writer will receive these in their <em>Artist's Submissions</em> tab to download and include in the final article.
                </p>

                <div class="submodal-actions">
                    <button type="button" class="btn-grey-pill" @click="isSubmitModalOpen = false" :disabled="isSubmitting">Cancel</button>
                    <button type="button" class="btn-blue-pill btn-full-width" @click="confirmSendToWriter" :disabled="isSubmitting">
                        {{ isSubmitting ? 'Sending...' : 'Yes, Send to Writer' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- SUBMISSION SUCCESS MODAL -->
        <div class="submodal-overlay" v-if="isSuccessModalOpen">
            <div class="submodal-card">
                <div class="success-icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>

                <h3 class="submodal-title">Sent to Writer Successfully!</h3>
                <p class="submodal-desc">
                    Your visual assets have been submitted to <strong>{{ writerInfo?.name || 'the writer' }}</strong>.
                    The writer has been notified and can now review and download your assets.
                </p>

                <div class="submodal-actions">
                    <button type="button" class="btn-blue-pill btn-full-width" @click="closeAllModals">Done</button>
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

const emit = defineEmits(['close', 'task-submitted', 'task-saved-as-draft']);

const currentTab = ref('assets');
const isSubmitModalOpen = ref(false);
const isSuccessModalOpen = ref(false);
const isSubmitting = ref(false);
const isSaving = ref(false);
const saveFeedback = ref('');

// Visual Assets State
const thumbnailInputRef = ref(null);
const mediaInputRef = ref(null);
const thumbnailPreview = ref('');
const mediaPreviews = ref([]);
const isUploadingMedia = ref(false);
const isUploadingThumbnail = ref(false);

const task = computed(() => props.taskData || {});

// Disable submission when task is already submitted or completed/published
const isAlreadySubmitted = computed(() => {
    const s = (task.value?.status || '').toLowerCase();
    return s === 'submitted' || s === 'completed' || s === 'published';
});

const sectionName = computed(() => {
    const s = task.value?.section;
    if (typeof s === 'object' && s !== null) return s.name || 'News';
    return s || 'News';
});

const parseNotesField = (notes, key) => {
    if (!notes || typeof notes !== 'string') return '';
    const match = notes.match(new RegExp(`${key}:\\s*([^|]+)`, 'i'));
    return match ? match[1].trim() : '';
};

// Paired Writer info
const writerInfo = computed(() => {
    const w = task.value?.writer || task.value?.assignees?.find(a => a.role === 'staff_writer' || a.role === 'Staff Writer');
    if (w && w.name) {
        return {
            id: w.id,
            name: w.name,
            role: w.secondary_role || w.role || 'Staff Writer',
            avatar: w.profile_picture ? `/storage/${w.profile_picture}` : (w.profile_picture_url || `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(w.name)}&backgroundColor=ffd5dc`)
        };
    }
    // Fallback: search in notes or assignees
    const writerNameInNotes = parseNotesField(task.value?.notes, 'Writer');
    if (writerNameInNotes) {
        return {
            name: writerNameInNotes,
            role: 'Staff Writer',
            avatar: `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(writerNameInNotes)}&backgroundColor=ffd5dc`
        };
    }
    return {
        name: 'Assigned Staff Writer',
        role: 'Staff Writer',
        avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=writer&backgroundColor=ffd5dc'
    };
});

const formatPriority = (priority) => {
    const p = (priority || '').toLowerCase();
    if (p === 'low') return 'Low';
    if (p === 'high') return 'High';
    if (p === 'urgent') return 'Urgent';
    return 'Moderate';
};

// Initialize assets when modal opens
watch(() => props.isOpen, (newVal) => {
    if (newVal) {
        currentTab.value = 'assets';
        saveFeedback.value = '';

        // Extract thumbnail from notes or task
        const thumbFromNotes = parseNotesField(task.value?.notes, 'Thumbnail');
        thumbnailPreview.value = thumbFromNotes || task.value?.thumbnail || task.value?.cover_image || '';

        // Extract media uploads from notes
        const mediaFromNotes = parseNotesField(task.value?.notes, 'Media Uploads');
        if (mediaFromNotes) {
            const urls = mediaFromNotes.split(',').map(u => u.trim()).filter(Boolean);
            mediaPreviews.value = urls.map((url, i) => ({
                url,
                name: url.split('/').pop() || `Graphic ${i + 1}`
            }));
        } else if (Array.isArray(task.value?.mediaUploads) && task.value.mediaUploads.length > 0) {
            mediaPreviews.value = task.value.mediaUploads.map(item => 
                typeof item === 'string' ? { url: item, name: item.split('/').pop() } : item
            );
        } else {
            mediaPreviews.value = [];
        }
    }
}, { immediate: true });

// Thumbnail handlers
const triggerThumbnailInput = () => {
    if (thumbnailInputRef.value) thumbnailInputRef.value.click();
};

const uploadThumbnailFile = async (file) => {
    if (!file || !file.type.startsWith('image/')) return;
    isUploadingThumbnail.value = true;
    try {
        const token = localStorage.getItem('sparky_token');
        const formData = new FormData();
        formData.append('files[]', file);
        const response = await fetch('/api/articles/upload-media', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: formData
        });
        if (response.ok) {
            const data = await response.json();
            thumbnailPreview.value = data.urls[0] || '';
        } else {
            const err = await response.json().catch(() => ({}));
            alert('Failed to upload thumbnail: ' + (err.message || 'Please try again.'));
        }
    } catch (e) {
        console.error('Thumbnail upload error:', e);
        alert('Failed to upload thumbnail. Please check your connection.');
    } finally {
        isUploadingThumbnail.value = false;
        if (thumbnailInputRef.value) thumbnailInputRef.value.value = '';
    }
};

const onThumbnailSelected = (e) => {
    const file = e.target.files[0];
    if (file) uploadThumbnailFile(file);
};

const handleThumbnailDrop = (e) => {
    const file = e.dataTransfer.files[0];
    if (file) uploadThumbnailFile(file);
};

const removeThumbnail = () => {
    thumbnailPreview.value = '';
    if (thumbnailInputRef.value) thumbnailInputRef.value.value = '';
};

// Media handlers (up to 3 photos)
const triggerMediaInput = () => {
    if (mediaInputRef.value) mediaInputRef.value.click();
};

const onMediaSelected = (e) => {
    const files = Array.from(e.target.files);
    addMediaFiles(files);
};

const handleMediaDrop = (e) => {
    const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
    addMediaFiles(files);
};

const addMediaFiles = async (files) => {
    const remainingSlots = 3 - mediaPreviews.value.length;
    if (remainingSlots <= 0) return;

    const filesToUpload = files.slice(0, remainingSlots);
    if (filesToUpload.length === 0) return;

    isUploadingMedia.value = true;
    try {
        const token = localStorage.getItem('sparky_token');
        const formData = new FormData();
        filesToUpload.forEach(file => formData.append('files[]', file));

        const response = await fetch('/api/articles/upload-media', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            data.urls.forEach((url, i) => {
                if (mediaPreviews.value.length < 3) {
                    mediaPreviews.value.push({
                        name: filesToUpload[i]?.name || url.split('/').pop(),
                        url
                    });
                }
            });
        } else {
            const err = await response.json().catch(() => ({}));
            alert('Failed to upload media: ' + (err.message || 'Please try again.'));
        }
    } catch (e) {
        console.error('Media upload error:', e);
        alert('Failed to upload media. Please check your connection.');
    } finally {
        isUploadingMedia.value = false;
        if (mediaInputRef.value) mediaInputRef.value.value = '';
    }
};

const removeMedia = (index) => {
    mediaPreviews.value.splice(index, 1);
};

// Build updated notes string preserving existing fields
const buildUpdatedNotes = (baseNotes = '') => {
    let cleanNotes = baseNotes || '';

    // Remove existing Thumbnail and Media Uploads
    cleanNotes = cleanNotes.replace(/Thumbnail:\s*[^|]+(\|)?/gi, '');
    cleanNotes = cleanNotes.replace(/Media Uploads:\s*[^|]+(\|)?/gi, '');
    cleanNotes = cleanNotes.replace(/\|\s*\|/g, '|').trim();
    if (cleanNotes.endsWith('|')) cleanNotes = cleanNotes.slice(0, -1).trim();

    const parts = cleanNotes ? [cleanNotes] : [];

    if (thumbnailPreview.value) {
        parts.push(`Thumbnail: ${thumbnailPreview.value}`);
    }

    if (mediaPreviews.value.length > 0) {
        const mediaUrls = mediaPreviews.value.map(m => m.url).join(',');
        parts.push(`Media Uploads: ${mediaUrls}`);
    }

    return parts.filter(Boolean).join(' | ');
};

// Save as draft
const saveAsDraft = async () => {
    if (!task.value?.id) return;
    isSaving.value = true;
    try {
        const token = localStorage.getItem('sparky_token');
        const updatedNotes = buildUpdatedNotes(task.value.notes);

        const response = await fetch(`/api/tasks/${task.value.id}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                notes: updatedNotes,
                status: 'in_progress'
            })
        });

        if (response.ok) {
            const updated = await response.json();
            saveFeedback.value = '✓ Draft saved';
            emit('task-saved-as-draft', {
                ...task.value,
                notes: updatedNotes,
                status: 'in_progress',
                thumbnail: thumbnailPreview.value,
                mediaUploads: mediaPreviews.value.map(m => m.url)
            });
            setTimeout(() => { saveFeedback.value = ''; }, 3000);
        }
    } catch (e) {
        console.error('Error saving draft:', e);
        saveFeedback.value = '✗ Save failed';
        setTimeout(() => { saveFeedback.value = ''; }, 3000);
    } finally {
        isSaving.value = false;
    }
};

// Confirm and Send to Writer
const confirmSendToWriter = async () => {
    if (!task.value?.id) return;
    isSubmitting.value = true;
    try {
        const token = localStorage.getItem('sparky_token');
        const updatedNotes = buildUpdatedNotes(task.value.notes);

        // Submit task on backend
        const response = await fetch(`/api/tasks/${task.value.id}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                notes: updatedNotes,
                status: 'submitted'
            })
        });

        if (response.ok) {
            // Also notify writer if writer ID is available
            const writerId = writerInfo.value?.id;
            if (writerId) {
                await fetch('/api/notifications', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: writerId,
                        title: 'Visual Assets Submitted',
                        message: `Visual assets have been submitted for "${task.value.title}". Check your Artist's Submissions tab.`
                    })
                }).catch(() => {});
            }

            emit('task-submitted', {
                ...task.value,
                notes: updatedNotes,
                status: 'submitted',
                thumbnail: thumbnailPreview.value,
                mediaUploads: mediaPreviews.value.map(m => m.url)
            });

            isSubmitModalOpen.value = false;
            isSuccessModalOpen.value = true;
        } else {
            const err = await response.json().catch(() => ({}));
            alert('Failed to submit: ' + (err.message || 'Please try again.'));
        }
    } catch (e) {
        console.error('Submit error:', e);
        alert('An error occurred. Please try again.');
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    emit('close');
};

const closeAllModals = () => {
    isSuccessModalOpen.value = false;
    isSubmitModalOpen.value = false;
    emit('close');
};
</script>

<style scoped>
.workspace-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(8px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    box-sizing: border-box;
}

.workspace-modal-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 780px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    padding: 32px;
    box-sizing: border-box;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.3);
    animation: popIn 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    overflow: hidden;
    text-align: left;
}

@keyframes popIn {
    from { opacity: 0; transform: scale(0.96) translateY(12px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-hdr {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}

.modal-hdr-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.modal-blue-title {
    color: #1d6bf3;
    font-size: 24px;
    font-weight: 800;
    margin: 0;
    letter-spacing: -0.4px;
    font-family: 'Manrope', sans-serif;
}

.save-feedback-badge {
    background: #ecfdf5;
    color: #059669;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    border: 1px solid #a7f3d0;
}

.modal-x-btn {
    background: #f1f5f9;
    border: none;
    cursor: pointer;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.modal-x-btn:hover {
    background: #e2e8f0;
}

.article-meta-hdr {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 16px;
}

.section-pill-badge {
    background-color: #dbeafe;
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    width: fit-content;
}

.article-main-title {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    line-height: 1.3;
}

/* Tabs */
.workspace-tabs-nav {
    display: flex;
    gap: 8px;
    border-bottom: 1.5px solid #e2e8f0;
    margin-bottom: 20px;
}

.tab-nav-btn {
    background: none;
    border: none;
    padding: 10px 18px;
    font-size: 14px;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    border-bottom: 2.5px solid transparent;
    margin-bottom: -1.5px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    font-family: 'Manrope', sans-serif;
}

.tab-nav-btn:hover {
    color: #0f172a;
}

.tab-nav-btn.active {
    color: #1d6bf3;
    border-bottom-color: #1d6bf3;
}

.tab-asset-count {
    background: #dbeafe;
    color: #1e40af;
    font-size: 11px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 10px;
}

.tab-content-body {
    flex: 1;
    overflow-y: auto;
    padding-right: 4px;
}

/* Visuals Layout */
.visuals-container-card {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.writer-info-banner {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 16px;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.writer-info-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.writer-banner-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
}

.writer-banner-label {
    font-size: 11px;
    font-weight: 700;
    color: #15803d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.writer-banner-name {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin: 2px 0 0 0;
}

.writer-banner-role {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
}

.writer-recipient-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #ffffff;
    color: #15803d;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 12px;
    border: 1px solid #bbf7d0;
}

.asset-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.asset-hdr {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.asset-title {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 2px 0;
}

.asset-subtitle {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}

.asset-format-badge {
    background: #f8fafc;
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.divider-line {
    height: 1px;
    background: #f1f5f9;
}

/* Dropzone & Preview */
.dashed-dropzone {
    border: 2px dashed #cbd5e1;
    border-radius: 18px;
    padding: 28px 20px;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
}

.dashed-dropzone:hover {
    border-color: #1d6bf3;
    background: #eff6ff;
}

.dashed-dropzone.compact-dropzone {
    padding: 16px;
}

.hidden-file-input {
    display: none;
}

.dropzone-text {
    font-size: 13px;
    color: #0f172a;
    margin: 0;
}

.dropzone-text .sub {
    font-size: 11.5px;
    color: #64748b;
    font-weight: 500;
}

.thumbnail-preview-card {
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    max-height: 220px;
    background: #0f172a;
}

.preview-img-wrap {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.thumbnail-img {
    width: 100%;
    max-height: 220px;
    object-fit: cover;
}

.preview-actions-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
}

.preview-action-btn.danger {
    background: rgba(220, 38, 38, 0.9);
    color: white;
    border: none;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.media-grid-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.media-preview-card {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    height: 130px;
}

.media-preview-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.remove-media-circle {
    position: absolute;
    top: 6px;
    right: 6px;
    background: rgba(15, 23, 42, 0.75);
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.media-name-tag {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: white;
    font-size: 11px;
    font-weight: 600;
    padding: 12px 8px 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.media-uploading-overlay {
    padding: 24px;
    background: #f8fafc;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    color: #1d6bf3;
    font-weight: 700;
    font-size: 13px;
}

.upload-spinner {
    width: 18px;
    height: 18px;
    border: 2px solid #bfdbfe;
    border-top-color: #1d6bf3;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Details Tab */
.details-container-card {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.details-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr 1fr;
    gap: 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 18px;
}

.detail-block {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.detail-label {
    font-size: 11.5px;
    color: #94a3b8;
    font-weight: 700;
    text-transform: uppercase;
}

.detail-val-bold {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
}

.priority-pill-badge {
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    width: fit-content;
}

.priority-pill-badge.medium, .priority-pill-badge.moderate { background: #fef3c7; color: #92400e; }
.priority-pill-badge.low { background: #d1fae5; color: #065f46; }
.priority-pill-badge.high { background: #ffe4e6; color: #9f1239; }
.priority-pill-badge.urgent { background: #fee2e2; color: #991b1b; }

.notes-card-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 18px;
}

.notes-hdr-row {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.notes-hdr {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.role-icon-circle {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.notes-body-text {
    font-size: 13.5px;
    color: #475569;
    line-height: 1.5;
}

.notes-body-subtext {
    font-size: 13px;
    color: #1e293b;
    margin-top: 10px;
    padding-top: 8px;
    border-top: 1px dashed #cbd5e1;
}

.paired-writer-row {
    display: flex;
    align-items: center;
    gap: 12px;
}

.writer-avatar-large {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.paired-writer-name {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 2px 0;
}

.paired-writer-sub {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}

/* Footer Actions */
.visuals-actions-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
}

.actions-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-secondary-pill {
    background: #f1f5f9;
    color: #475569;
    border: none;
    padding: 10px 18px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-outline-pill {
    background: #ffffff;
    color: #334155;
    border: 1.5px solid #cbd5e1;
    padding: 10px 18px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-outline-pill:hover {
    background: #f8fafc;
    border-color: #94a3b8;
}

.btn-blue-pill-action {
    background: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 10px 22px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3);
    transition: all 0.2s;
}

.btn-blue-pill-action:hover:not(:disabled) {
    background: #1557b0;
}

.btn-blue-pill-action:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    box-shadow: none;
}

.btn-outline-pill:disabled {
    opacity: 0.45;
    cursor: not-allowed;
    pointer-events: none;
}

.already-submitted-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #dcfce7;
    color: #16a34a;
    border-radius: 999px;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.1px;
}

.btn-blue-pill-small {
    background: #1d6bf3;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 18px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-grey-pill-small {
    background: #e2e8f0;
    color: #334155;
    border: none;
    padding: 8px 16px;
    border-radius: 18px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Submodals */
.submodal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(6px);
    z-index: 10001;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.submodal-card {
    background: #ffffff;
    border-radius: 24px;
    width: 100%;
    max-width: 440px;
    padding: 28px;
    text-align: center;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
    animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.submit-icon-circle {
    width: 60px;
    height: 60px;
    background: #1d6bf3;
    border-radius: 50%;
    margin: 0 auto 16px auto;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 16px rgba(29, 107, 243, 0.3);
}

.success-icon-circle {
    width: 60px;
    height: 60px;
    background: #dcfce7;
    border-radius: 50%;
    margin: 0 auto 16px auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

.submodal-title {
    font-size: 19px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 8px 0;
}

.submodal-desc {
    font-size: 13.5px;
    color: #64748b;
    margin: 0 0 24px 0;
    line-height: 1.5;
}

.submodal-actions {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 10px;
}

.btn-grey-pill {
    background: #f1f5f9;
    color: #475569;
    border: none;
    padding: 12px;
    border-radius: 24px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
}

.btn-blue-pill {
    background: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 12px;
    border-radius: 24px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
}

.btn-blue-pill.btn-full-width {
    width: 100%;
}
</style>
