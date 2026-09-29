<template>
    <div>
        <!-- MAIN PREVIEW MODAL -->
        <div class="article-details-overlay" v-if="isOpen && !isScheduleModalOpen && !isDeleteConfirmOpen" @click.self="closeModal">
            <div class="article-details-card">

                <!-- Header -->
                <div class="modal-hdr">
                    <h2 class="modal-blue-title">{{ isVideo ? 'Video Preview' : 'Article Preview' }}</h2>
                    <button class="modal-x-btn" @click="closeModal" title="Close" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <h1 class="article-main-heading" style="margin-bottom: 18px;">{{ displayTitle }}</h1>

                <!-- Metadata Row -->
                <div class="preview-meta-row">
                    <div class="meta-item">
                        <span class="meta-label">{{ isVideo ? 'Video Section' : 'Coverage' }}</span>
                        <span class="meta-value">{{ isVideo ? (article.video_category || 'Not set yet') : displayCoverage }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Category</span>
                        <span class="category-pill">{{ displaySection }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">{{ isVideo ? 'Presenter' : 'Writer' }}</span>
                        <span class="meta-value">{{ displayWriter }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">{{ dateLabel }}</span>
                        <span class="meta-value">{{ dateValue }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Status</span>
                        <span class="status-badge" :class="statusBadgeClass">{{ displayStatus }}</span>
                    </div>
                </div>

                <!-- Content Row -->
                <div class="preview-content-row">
                    <!-- Left: Body preview -->
                    <div class="preview-body-card">
                        <div v-if="isVideo && embedUrl" class="video-embed">
                            <iframe :src="embedUrl" title="Video preview" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <p class="preview-text" v-if="displayPreviewParagraphs.length > 0" v-for="(para, idx) in displayPreviewParagraphs" :key="idx" :style="idx > 0 ? 'margin-top: 10px;' : ''">
                            {{ para }}
                        </p>
                        <p class="preview-text" v-else>{{ defaultPreviewText }}</p>
                    </div>

                    <!-- Right: Thumbnail + Media Uploads -->
                    <div class="preview-side-col">
                        <div class="thumb-preview-card">
                            <h4 class="card-box-title">Thumbnail Preview</h4>
                            <div class="thumb-preview-box" :style="coverImageUrl ? { backgroundImage: `url(${coverImageUrl})` } : {}">
                                <div class="thumb-overlay-text">
                                    <div class="thumb-overlay-title">{{ displayTitle }}</div>
                                    <div class="thumb-overlay-sub">{{ displaySection }}</div>
                                </div>
                                <div class="thumb-avatar-pill" v-if="hasArtist">
                                    <img :src="artistAvatarUrl" :alt="displayArtistName" class="thumb-avatar-img" />
                                    <span>{{ artistShortName }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="media-uploads-card" v-if="isVideo">
                            <h4 class="card-box-title">Video Link</h4>
                            <a v-if="article.video_url" :href="article.video_url" target="_blank" rel="noopener" class="video-link">{{ article.video_url }}</a>
                            <p v-else class="preview-text">No video link submitted.</p>
                        </div>
                        <div class="media-uploads-card" v-else>
                            <h4 class="card-box-title">Media Uploads</h4>
                            <div class="media-thumb-row" v-if="mediaThumbnails.length > 0">
                                <div class="media-thumb-item" v-for="(file, fIdx) in mediaThumbnails" :key="fIdx">
                                    <img :src="file.url" :alt="file.name" class="media-thumb-img" />
                                    <span class="media-thumb-name">{{ file.name }}</span>
                                </div>
                            </div>
                            <p v-else class="preview-text">No media uploaded.</p>
                            <div class="thumb-avatar-pill standalone" v-if="hasArtist">
                                <img :src="artistAvatarUrl" :alt="displayArtistName" class="thumb-avatar-img" />
                                <span>{{ artistShortName }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="preview-footer-row">
                    <div class="footer-info-text">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        {{ footerMessage }}
                    </div>

                    <!-- Read-only: just a way to view the live article -->
                    <button type="button" class="btn-blue-primary-pill" v-if="readOnly" @click="visitArticle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                        {{ visitLabel }}
                    </button>

                    <!-- Pending: Publish dropdown -->
                    <div class="publish-btn-wrap" v-else-if="!archived && !isScheduled && !isPublished" ref="publishBtnWrap" @click.stop>
                        <button type="button" class="btn-blue-primary-pill" @click="isPublishMenuOpen = !isPublishMenuOpen" :disabled="isSubmitting">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 11 18-5v12L3 14v-3z"></path>
                                <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>
                            </svg>
                            {{ isVideo ? 'Publish Video' : 'Publish Article' }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div class="publish-dropdown-menu" v-if="isPublishMenuOpen">
                            <button type="button" class="publish-dropdown-item" @click="handlePublishNow">
                                <span class="pd-icon">⚡</span>
                                <span class="pd-text">
                                    <span class="pd-title">Publish Now</span>
                                    <span class="pd-desc">Make this article live immediately.</span>
                                </span>
                            </button>
                            <button type="button" class="publish-dropdown-item" @click="openScheduleModal">
                                <span class="pd-icon">📅</span>
                                <span class="pd-text">
                                    <span class="pd-title">Schedule Publication</span>
                                    <span class="pd-desc">Create a date and time to publish.</span>
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Scheduled: pill -->
                    <button type="button" class="scheduled-pill" v-else-if="!archived && isScheduled" @click="openScheduleModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <span class="scheduled-pill-text">
                            <span class="sp-title">Publication Scheduled</span>
                            <span class="sp-date">{{ scheduledDateLabel }}</span>
                        </span>
                    </button>

                    <!-- Published: Visit / Edit / Delete -->
                    <div class="published-actions-row" v-else>
                        <button type="button" class="btn-grey-pill" @click="requestEdit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4Z"></path></svg>
                            Edit
                        </button>
                        <button type="button" class="btn-danger-pill" @click="isDeleteConfirmOpen = true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            Delete
                        </button>
                        <button type="button" class="btn-blue-primary-pill" @click="visitArticle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                            {{ visitLabel }}
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- SUB-MODAL: SCHEDULE PUBLICATION -->
        <div class="article-details-overlay" v-if="isOpen && isScheduleModalOpen" @click.self="isScheduleModalOpen = false">
            <div class="dialog-card-medium">
                <div class="modal-hdr">
                    <h2 class="modal-blue-title">Schedule Publication</h2>
                    <button class="modal-x-btn" @click="isScheduleModalOpen = false" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="notes-form-group">
                    <label class="notes-label">Publication Date</label>
                    <input type="date" v-model="scheduleDate" class="schedule-input" :min="todayDateStr" />
                </div>
                <div class="notes-form-group">
                    <label class="notes-label">Publication Time</label>
                    <input type="time" v-model="scheduleTime" class="schedule-input" />
                </div>

                <p v-if="scheduleError" class="notes-subtext" style="color: #dc2626;">{{ scheduleError }}</p>

                <div class="schedule-info-note">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    This article will be automatically published on the selected date and time.
                </div>

                <div class="dialog-actions-row" style="margin-top: 16px;">
                    <button class="btn-grey-pill" @click="isScheduleModalOpen = false" :disabled="isSubmitting">Cancel</button>
                    <button class="btn-blue-pill" @click="submitSchedule" :disabled="isSubmitting">
                        {{ isSubmitting ? 'Scheduling...' : 'Schedule Article' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- SUB-MODAL: DELETE CONFIRM -->
        <div class="article-details-overlay" v-if="isOpen && isDeleteConfirmOpen" @click.self="isDeleteConfirmOpen = false">
            <div class="dialog-card-medium">
                <div class="modal-hdr">
                    <h2 class="modal-blue-title">{{ isVideo ? 'Delete Video?' : 'Delete Article?' }}</h2>
                    <button class="modal-x-btn" @click="isDeleteConfirmOpen = false" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <p class="notes-subtext" style="font-size: 13px;">
                    Delete "<strong>{{ displayTitle }}</strong>"? This will permanently remove the {{ isVideo ? 'video' : 'article' }} and its linked tasks. This action cannot be undone.
                </p>
                <p v-if="deleteError" class="notes-subtext" style="color: #dc2626;">{{ deleteError }}</p>
                <div class="dialog-actions-row confirm-delete-actions-row" style="margin-top: 16px;">
                    <button class="btn-danger-pill" @click="confirmDelete" :disabled="isSubmitting">
                        {{ isSubmitting ? 'Deleting...' : (isVideo ? 'Delete Video' : 'Delete Article') }}
                    </button>
                    <button class="btn-grey-pill" @click="isDeleteConfirmOpen = false" :disabled="isSubmitting">Cancel</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { youtubeEmbedUrl } from '../utils/video';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    articleData: {
        type: Object,
        default: () => ({})
    },
    readOnly: {
        type: Boolean,
        default: false
    },
    // Archived articles always get the Edit / Delete / Visit actions, whatever their status
    archived: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close', 'action-complete', 'article-updated', 'edit-article']);

const article = computed(() => props.articleData || {});

// Videos reuse this preview: a player replaces the article text and "Visit" becomes "Watch"
const isVideo = computed(() => article.value.type === 'video');
const embedUrl = computed(() => youtubeEmbedUrl(article.value.video_url));
const visitLabel = computed(() => isVideo.value ? 'Watch Video' : 'Visit Article');

const isSubmitting = ref(false);
const isPublishMenuOpen = ref(false);
const isScheduleModalOpen = ref(false);
const isDeleteConfirmOpen = ref(false);
const scheduleDate = ref('');
const scheduleTime = ref('');
const scheduleError = ref('');
const deleteError = ref('');
const publishBtnWrap = ref(null);

const closePublishMenu = () => { isPublishMenuOpen.value = false; };

watch(() => props.isOpen, (open) => {
    if (open) {
        isPublishMenuOpen.value = false;
        isScheduleModalOpen.value = false;
        isDeleteConfirmOpen.value = false;
        scheduleError.value = '';
        deleteError.value = '';
        window.addEventListener('click', closePublishMenu);
    } else {
        window.removeEventListener('click', closePublishMenu);
    }
});

const todayDateStr = computed(() => new Date().toISOString().slice(0, 10));

const rawStatus = computed(() => String(article.value.raw_status || article.value.status || '').toLowerCase());
const isScheduled = computed(() => rawStatus.value === 'scheduled');
const isPublished = computed(() => rawStatus.value === 'published');

const displaySection = computed(() => {
    const sec = article.value.section;
    if (!sec) return 'Unassigned';
    if (typeof sec === 'string') return sec;
    if (typeof sec === 'object' && sec.name) return sec.name;
    return 'Unassigned';
});

const displayTitle = computed(() => article.value.title || 'Untitled Article');

const displayCoverage = computed(() => article.value.coverage || `${displaySection.value} Coverage`);

const displayWriter = computed(() => {
    if (article.value.author && typeof article.value.author === 'object' && article.value.author.name) {
        return article.value.author.name;
    }
    if (article.value.writer && typeof article.value.writer === 'string') {
        return article.value.writer;
    }
    return 'Unknown Writer';
});

const formatDateShort = (dateVal) => {
    if (!dateVal) return '—';
    const d = new Date(dateVal);
    if (isNaN(d.getTime())) return '—';
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const dateLabel = computed(() => {
    if (isPublished.value) return 'Date Published';
    if (isScheduled.value) return 'Scheduled Date';
    return 'Date';
});

const dateValue = computed(() => {
    if (isPublished.value) return formatDateShort(article.value.published_at || article.value.updated_at);
    if (isScheduled.value) return formatDateShort(article.value.scheduled_at);
    return formatDateShort(article.value.endorsed_at || article.value.dateEndorsed || article.value.created_at);
});

const displayStatus = computed(() => {
    if (isPublished.value) return 'Published';
    if (isScheduled.value) return 'Scheduled';
    if (rawStatus.value === 'endorsed') return 'Endorsed';
    return rawStatus.value ? rawStatus.value.charAt(0).toUpperCase() + rawStatus.value.slice(1) : 'Endorsed';
});

const statusBadgeClass = computed(() => {
    if (isPublished.value || isScheduled.value) return 'status-blue';
    return 'status-purple';
});

const footerMessage = computed(() => {
    const noun = isVideo.value ? 'video' : 'article';
    if (props.archived) return `This ${noun} is part of the archive.`;
    if (props.readOnly && !isPublished.value && !isScheduled.value) return `This ${noun} is still going through review.`;
    if (isPublished.value) return `This ${noun} is now live.`;
    if (isScheduled.value) return `This ${noun} is scheduled for automatic publishing.`;
    return `You have approved this ${noun} and it is ready for publishing.`;
});

const scheduledDateLabel = computed(() => {
    if (!article.value.scheduled_at) return '';
    const d = new Date(article.value.scheduled_at);
    if (isNaN(d.getTime())) return '';
    return d.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
});

const hasArtist = computed(() => Boolean(article.value.artist_name));
const displayArtistName = computed(() => article.value.artist_name || '');
const artistShortName = computed(() => {
    const name = displayArtistName.value;
    if (!name) return '';
    const parts = name.trim().split(/\s+/);
    if (parts.length === 1) return parts[0];
    return `${parts[0]} ${parts[parts.length - 1].charAt(0)}.`;
});
const artistAvatarUrl = computed(() => {
    if (article.value.artist_avatar) return article.value.artist_avatar;
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(displayArtistName.value || 'artist')}&background=ffd5dc&color=9f1239&size=100`;
});

const coverImageUrl = computed(() => article.value.cover_image || '');

const mediaThumbnails = computed(() => {
    if (!Array.isArray(article.value.attached_files)) return [];
    return article.value.attached_files.filter(f => f.url);
});

// Strip HTML tags from rich-text content so the preview shows plain, readable text
const stripHtml = (html) => {
    if (!html || typeof html !== 'string') return '';
    const withBreaks = html
        .replace(/<br\s*\/?>/gi, '\n')
        .replace(/<\/(p|div|li|h[1-6])>/gi, '\n\n')
        .replace(/<[^>]+>/g, '');
    const entities = { '&nbsp;': ' ', '&amp;': '&', '&lt;': '<', '&gt;': '>', '&quot;': '"', '&#39;': "'" };
    return withBreaks
        .replace(/&nbsp;|&amp;|&lt;|&gt;|&quot;|&#39;/g, (match) => entities[match])
        .replace(/\n{3,}/g, '\n\n')
        .trim();
};

const displayPreviewParagraphs = computed(() => {
    const text = stripHtml(article.value.excerpt || article.value.content || '');
    return text.split('\n\n').filter(Boolean);
});

const defaultPreviewText = computed(() => isVideo.value ? 'No video description has been submitted yet.' : 'No article content has been submitted yet.');

const closeModal = () => {
    isPublishMenuOpen.value = false;
    emit('close');
};

const requestEdit = () => {
    emit('edit-article', article.value);
};

const visitArticle = () => {
    if (isVideo.value) {
        if (article.value.video_url) window.open(article.value.video_url, '_blank', 'noopener');
        return;
    }
    if (article.value.id) {
        window.open(`/article/${article.value.id}`, '_blank');
    } else {
        window.open('/article-details', '_blank');
    }
};

const patchArticle = async (body) => {
    const token = localStorage.getItem('sparky_token');
    const articleId = article.value.id;
    if (!token || !articleId) throw new Error('Missing article or session — please reopen this article and try again.');

    const res = await fetch(`/api/articles/${articleId}`, {
        method: 'PATCH',
        headers: {
            Authorization: `Bearer ${token}`,
            Accept: 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(body)
    });
    if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        throw new Error(data.message || 'Something went wrong. Please try again.');
    }
    return res.json();
};

const handlePublishNow = async () => {
    isPublishMenuOpen.value = false;
    try {
        isSubmitting.value = true;
        const updated = await patchArticle({ status: 'published' });
        emit('article-updated', updated);
    } catch (e) {
        console.error(e);
    } finally {
        isSubmitting.value = false;
    }
};

const openScheduleModal = () => {
    isPublishMenuOpen.value = false;
    scheduleError.value = '';
    if (article.value.scheduled_at) {
        const d = new Date(article.value.scheduled_at);
        if (!isNaN(d.getTime())) {
            scheduleDate.value = d.toISOString().slice(0, 10);
            scheduleTime.value = d.toTimeString().slice(0, 5);
        }
    } else {
        scheduleDate.value = '';
        scheduleTime.value = '';
    }
    isScheduleModalOpen.value = true;
};

const submitSchedule = async () => {
    if (!scheduleDate.value || !scheduleTime.value) {
        scheduleError.value = 'Please choose both a date and a time.';
        return;
    }
    const scheduledAt = new Date(`${scheduleDate.value}T${scheduleTime.value}`);
    if (isNaN(scheduledAt.getTime()) || scheduledAt.getTime() <= Date.now()) {
        scheduleError.value = 'Please choose a date and time in the future.';
        return;
    }

    try {
        isSubmitting.value = true;
        scheduleError.value = '';
        const updated = await patchArticle({ status: 'scheduled', scheduled_at: scheduledAt.toISOString() });
        emit('article-updated', updated);
        isScheduleModalOpen.value = false;
    } catch (e) {
        scheduleError.value = e.message || 'Failed to schedule this article.';
    } finally {
        isSubmitting.value = false;
    }
};

const confirmDelete = async () => {
    const token = localStorage.getItem('sparky_token');
    const articleId = article.value.id;
    if (!token || !articleId) {
        deleteError.value = 'Missing article or session — please reopen this article and try again.';
        return;
    }

    try {
        isSubmitting.value = true;
        deleteError.value = '';
        const res = await fetch(`/api/articles/${articleId}`, {
            method: 'DELETE',
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json'
            }
        });
        if (!res.ok) {
            const data = await res.json().catch(() => ({}));
            throw new Error(data.message || 'Failed to delete this article.');
        }
        isDeleteConfirmOpen.value = false;
        emit('action-complete');
        emit('close');
    } catch (e) {
        deleteError.value = e.message || 'Failed to delete this article.';
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<style scoped>
.article-details-overlay {
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
    font-family: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.article-details-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 820px;
    padding: 32px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    max-height: 90vh;
    overflow-y: auto;
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
    font-size: 24px;
    font-weight: 800;
    margin: 0;
    letter-spacing: -0.5px;
}

.modal-x-btn {
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

.modal-x-btn:hover {
    background: #f1f5f9;
}

.article-main-heading {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin: 8px 0 0 0;
    line-height: 1.3;
}

/* META ROW */
.preview-meta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 22px;
    margin-bottom: 20px;
    padding-bottom: 18px;
    border-bottom: 1px solid #f1f5f9;
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
}

.meta-value {
    font-size: 13.5px;
    font-weight: 800;
    color: #0f172a;
}

.category-pill {
    background-color: #dbeafe;
    color: #1e40af;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    width: fit-content;
}

.status-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    width: fit-content;
}

.status-badge.status-purple {
    background-color: #ede9fe;
    color: #6d28d9;
}

.status-badge.status-blue {
    background-color: #dbeafe;
    color: #1d4ed8;
}

/* CONTENT ROW */
.preview-content-row {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}

.preview-body-card {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    padding: 20px;
}

.video-embed {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    border-radius: 14px;
    overflow: hidden;
    background: #0f172a;
    margin-bottom: 14px;
}

.video-embed iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: 0;
}

.video-link {
    font-size: 12.5px;
    color: #1d6bf3;
    font-weight: 600;
    word-break: break-all;
}

.preview-text {
    font-size: 12.5px;
    color: #475569;
    margin: 0;
    line-height: 1.6;
}

.preview-side-col {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.card-box-title {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 10px 0;
}

.thumb-preview-card,
.media-uploads-card {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    padding: 16px;
}

.thumb-preview-box {
    position: relative;
    border: 2px dashed #cbd5e1;
    border-radius: 14px;
    height: 130px;
    background-color: #0f172a;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: flex-end;
    overflow: hidden;
}

.thumb-overlay-text {
    padding: 10px 12px;
    color: #ffffff;
    text-shadow: 0 2px 6px rgba(0, 0, 0, 0.6);
}

.thumb-overlay-title {
    font-size: 13px;
    font-weight: 800;
    line-height: 1.25;
}

.thumb-overlay-sub {
    font-size: 10px;
    font-weight: 600;
    opacity: 0.85;
}

.thumb-avatar-pill {
    position: absolute;
    left: 10px;
    bottom: 10px;
    background: #ffffff;
    border-radius: 20px;
    padding: 3px 10px 3px 3px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    color: #0f172a;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.thumb-avatar-pill.standalone {
    position: static;
    margin-top: 12px;
    width: fit-content;
    box-shadow: none;
    border: 1px solid #e2e8f0;
}

.thumb-avatar-img {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    object-fit: cover;
}

.media-thumb-row {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.media-thumb-item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #ffffff;
    border-radius: 12px;
    padding: 8px;
}

.media-thumb-img {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    object-fit: cover;
    flex-shrink: 0;
}

.media-thumb-name {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    word-break: break-all;
}

/* FOOTER */
.preview-footer-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.footer-info-text {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
    color: #1d6bf3;
    font-weight: 600;
}

.publish-btn-wrap {
    position: relative;
}

.publish-dropdown-menu {
    position: absolute;
    bottom: calc(100% + 10px);
    right: 0;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.25);
    padding: 8px;
    width: 260px;
    z-index: 10;
}

.publish-dropdown-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    width: 100%;
    background: none;
    border: none;
    text-align: left;
    padding: 10px;
    border-radius: 12px;
    cursor: pointer;
    transition: background 0.15s;
}

.publish-dropdown-item:hover {
    background: #f1f5f9;
}

.pd-icon {
    font-size: 16px;
    line-height: 1.4;
}

.pd-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.pd-title {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
}

.pd-desc {
    font-size: 11.5px;
    color: #64748b;
}

.scheduled-pill {
    background-color: #dbeafe;
    color: #1d4ed8;
    border: none;
    padding: 10px 18px;
    border-radius: 30px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.scheduled-pill:hover {
    background-color: #bfdbfe;
}

.scheduled-pill-text {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    line-height: 1.3;
}

.sp-title {
    font-size: 13px;
    font-weight: 800;
}

.sp-date {
    font-size: 11px;
    font-weight: 600;
}

.published-actions-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* BUTTONS */
.btn-grey-pill {
    background-color: #f1f5f9;
    color: #475569;
    border: none;
    padding: 12px 18px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s;
}

.btn-grey-pill:hover:not(:disabled) {
    background-color: #e2e8f0;
    color: #0f172a;
}

.btn-danger-pill {
    background-color: #fee2e2;
    color: #b91c1c;
    border: none;
    padding: 12px 18px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s;
}

.btn-danger-pill:hover:not(:disabled) {
    background-color: #fecaca;
}

.btn-blue-primary-pill, .btn-blue-pill {
    background-color: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 12px 20px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3);
    transition: all 0.2s;
}

.btn-blue-primary-pill:hover:not(:disabled), .btn-blue-pill:hover:not(:disabled) {
    background-color: #1557b0;
    box-shadow: 0 6px 18px rgba(29, 107, 243, 0.4);
}

.btn-grey-pill:disabled,
.btn-danger-pill:disabled,
.btn-blue-primary-pill:disabled,
.btn-blue-pill:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* DIALOG CARDS (Schedule / Delete) */
.dialog-card-medium {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 460px;
    padding: 32px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.notes-form-group {
    margin: 0 0 16px 0;
    text-align: left;
}

.notes-label {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    display: block;
    margin-bottom: 8px;
}

.schedule-input {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 14px;
    box-sizing: border-box;
    font-size: 13px;
    font-family: inherit;
    color: #0f172a;
    outline: none;
}

.schedule-input:focus {
    border-color: #1d6bf3;
}

.notes-subtext {
    font-size: 12px;
    color: #64748b;
    margin: 8px 0 0 0;
}

.schedule-info-note {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    background: #eff6ff;
    color: #1d4ed8;
    border-radius: 14px;
    padding: 12px 14px;
    font-size: 12px;
    font-weight: 600;
    line-height: 1.4;
    margin-top: 8px;
}

.dialog-actions-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

/* Delete confirmation: danger action on top, Cancel below, both full width */
.confirm-delete-actions-row {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 12px;
}

.confirm-delete-actions-row .btn-danger-pill,
.confirm-delete-actions-row .btn-grey-pill {
    width: 100%;
    justify-content: center;
}

@media (max-width: 640px) {
    .preview-content-row {
        grid-template-columns: 1fr;
    }
    .published-actions-row {
        flex-wrap: wrap;
    }
}
</style>
