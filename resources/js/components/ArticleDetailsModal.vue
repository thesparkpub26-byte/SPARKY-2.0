<template>
    <div>
        <!-- MAIN ARTICLE DETAILS MODAL -->
        <div class="article-details-overlay" v-if="isOpen && !isReturnModalOpen && !isReturnSuccessOpen" @click.self="closeModal">
            <div class="article-details-card">
                
                <!-- Header -->
                <div class="modal-hdr">
                    <h2 class="modal-blue-title">{{ isVideo ? 'Video Details' : 'Article Details' }}</h2>
                    <button class="modal-x-btn" @click="closeModal" title="Close" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Section Badge & Article Title -->
                <div class="article-title-block">
                    <span class="section-pill-badge">{{ displaySection }}</span>
                    <h1 class="article-main-heading">{{ displayTitle }}</h1>
                </div>

                <!-- Top Row: Grid Details & Assigned Team -->
                <div class="top-info-row">
                    <!-- Left: Metadata Grid -->
                    <div class="meta-details-grid">
                        <div class="meta-item">
                            <span class="meta-label">Date Endorsed</span>
                            <span class="meta-value">{{ displayDateEndorsed }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Deadline</span>
                            <span class="meta-value">{{ displayDeadline }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Status</span>
                            <span class="status-badge" :class="statusBadgeClass">{{ displayStatus }}</span>
                        </div>
                        <div class="meta-item" v-if="!isVideo">
                            <span class="meta-label">Word Count</span>
                            <span class="word-count-chip">{{ displayWordCount }}</span>
                        </div>
                        <div class="meta-item" v-else>
                            <span class="meta-label">Video Section</span>
                            <span class="word-count-chip">{{ article.video_category || 'Not set yet' }}</span>
                        </div>
                    </div>

                    <!-- Right: Assigned Team Card -->
                    <div class="editor-remarks-card">
                        <h4 class="remarks-title">Assigned Team</h4>
                        <div class="editor-profile-bar" style="margin-top: 10px;">
                            <img :src="writerAvatarUrl" :alt="displayWriter" class="editor-avatar" />
                            <div class="editor-info">
                                <div class="role-pill-badge">{{ displayWriterRole }}</div>
                                <div class="editor-name">{{ displayWriter }}</div>
                                <div v-if="displayWriterEmail" class="editor-email">{{ displayWriterEmail }}</div>
                            </div>
                        </div>
                        <div v-if="isVideo" v-for="member in crewMembers" :key="member.role + member.name" class="editor-profile-bar artist-profile-row">
                            <img :src="member.avatar" :alt="member.name" class="editor-avatar" />
                            <div class="editor-info">
                                <div class="role-pill-badge">{{ member.role }}</div>
                                <div class="editor-name">{{ member.name }}</div>
                                <div v-if="member.email" class="editor-email">{{ member.email }}</div>
                            </div>
                        </div>
                        <div v-if="!isVideo" class="editor-profile-bar artist-profile-row">
                            <img v-if="hasArtist" :src="artistAvatarUrl" :alt="displayArtistName" class="editor-avatar" />
                            <div class="editor-info">
                                <div v-if="hasArtist" class="role-pill-badge">{{ displayArtistRole }}</div>
                                <div class="editor-name">{{ displayArtistName }}</div>
                                <div v-if="displayArtistEmail" class="editor-email">{{ displayArtistEmail }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Row: Article Preview & Attached Files -->
                <div class="middle-content-row">
                    <!-- Left: Article Preview Card -->
                    <div class="preview-box-card">
                        <h4 class="card-box-title">{{ isVideo ? 'Video Preview' : 'Article Preview' }}</h4>
                        <div v-if="isVideo && embedUrl" class="video-embed">
                            <iframe :src="embedUrl" title="Video preview" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <div class="preview-text-wrapper">
                            <p class="preview-text" v-if="displayPreviewParagraphs.length > 0" v-for="(para, idx) in displayPreviewParagraphs" :key="idx" :style="idx > 0 ? 'margin-top: 10px;' : ''">
                                {{ para }}
                            </p>
                            <p class="preview-text" v-else>
                                {{ defaultPreviewText }}
                            </p>
                        </div>
                        <button class="view-full-article-link" @click="handleViewFullArticle">
                            {{ isVideo ? 'Watch on YouTube' : 'View Full Article' }} 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                <polyline points="15 3 21 3 21 9"></polyline>
                                <line x1="10" y1="14" x2="21" y2="3"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Right: Attached Files Card -->
                    <div class="attached-files-card">
                        <h4 class="card-box-title">Attached Files</h4>
                        <div class="files-list">
                            <div class="file-item" v-for="(file, fIdx) in displayAttachedFiles" :key="fIdx">
                                <img v-if="file.url" :src="file.url" :alt="file.name" class="file-thumb-img" />
                                <div v-else class="file-thumb-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                </div>
                                <div class="file-info">
                                    <span class="file-name">{{ file.name }}</span>
                                </div>
                            </div>
                            <p v-if="displayAttachedFiles.length === 0" class="preview-text">No files attached.</p>
                        </div>
                        <div class="attachments-count-footer" v-if="displayAttachedFiles.length > 0">
                            <span class="attachments-pill">{{ displayAttachedFiles.length }} file{{ displayAttachedFiles.length === 1 ? '' : 's' }} attached</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Bar -->
                <div class="modal-footer-actions">
                    <button class="btn-grey-pill" @click="openReturnModal" :disabled="isSubmitting">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 14 4 9 9 4"></polyline>
                            <path d="M20 20v-7a4 4 0 0 0-4-4H4"></path>
                        </svg>
                        Return to Writer
                    </button>

                    <button class="btn-blue-primary-pill" @click="requestPublishPreview" :disabled="isSubmitting">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 11 18-5v12L3 14v-3z"></path>
                            <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>
                        </svg>
                        {{ isVideo ? 'Publish Video' : 'Publish Article' }}
                    </button>
                </div>

            </div>
        </div>

        <!-- SUB-MODAL 1: RETURN TO WRITER -->
        <div class="article-details-overlay" v-if="isOpen && isReturnModalOpen && !isReturnSuccessOpen" @click.self="isReturnModalOpen = false">
            <div class="dialog-card-medium">
                <div class="modal-hdr">
                    <h2 class="modal-blue-title">Return to Writer</h2>
                    <button class="modal-x-btn" @click="isReturnModalOpen = false" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="notes-form-group">
                    <label class="notes-label">Notes / Revision Instructions</label>
                    <div class="textarea-relative-wrapper">
                        <textarea
                            v-model="returnNotes"
                            maxlength="500"
                            placeholder="Specify requested revisions and editorial guidance..."
                            class="notes-textarea"
                        ></textarea>
                        <span class="char-count-badge">{{ returnNotes.length }}/500</span>
                    </div>
                    <p class="notes-subtext">This note will be visible to the writer. They'll need to revise and resubmit the article through the full review process.</p>
                </div>

                <p v-if="returnError" class="notes-subtext" style="color: #dc2626;">{{ returnError }}</p>

                <div class="dialog-actions-row">
                    <button class="btn-grey-pill" @click="isReturnModalOpen = false" :disabled="isSubmitting">Cancel</button>
                    <button class="btn-blue-pill" @click="submitReturnToWriter" :disabled="isSubmitting">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 9L9 20L4 15"></path>
                        </svg>
                        {{ isSubmitting ? 'Returning...' : 'Return to Writer' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- SUB-MODAL 2: RETURN SUCCESSFUL -->
        <div class="article-details-overlay" v-if="isOpen && isReturnSuccessOpen" @click.self="finishAll">
            <div class="dialog-card-small">
                <div class="circle-icon-wrap green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <h3 class="dialog-success-title">Article Returned to Writer</h3>
                <p class="dialog-success-subtext">The writer has been notified and can now revise the article and resubmit it for review.</p>
                <button class="btn-blue-full" @click="finishAll">Done</button>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { youtubeEmbedUrl } from '../utils/video';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    articleData: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['close', 'view-full-article', 'action-complete', 'request-publish-preview']);

const article = computed(() => props.articleData || {});

// Videos share this review modal; they show a player and the video crew instead of article text
const isVideo = computed(() => article.value.type === 'video');
const embedUrl = computed(() => youtubeEmbedUrl(article.value.video_url));
const crewMembers = computed(() => Array.isArray(article.value.video_crew) ? article.value.video_crew : []);

const isReturnModalOpen = ref(false);
const returnNotes = ref('');
const returnError = ref('');
const isReturnSuccessOpen = ref(false);
const isSubmitting = ref(false);

const openReturnModal = () => {
    returnNotes.value = '';
    returnError.value = '';
    isReturnModalOpen.value = true;
};

// Extract clean section name
const displaySection = computed(() => {
    const sec = article.value.section;
    if (!sec) return 'Unassigned';
    if (typeof sec === 'string') return sec;
    if (typeof sec === 'object' && sec.name) return sec.name;
    return 'Unassigned';
});

const displayTitle = computed(() => {
    return article.value.title || 'Untitled Article';
});

const displayWriter = computed(() => {
    if (article.value.author && typeof article.value.author === 'object' && article.value.author.name) {
        return article.value.author.name;
    }
    if (article.value.writer && typeof article.value.writer === 'string') {
        return article.value.writer;
    }
    return 'Unknown Writer';
});

const displayWriterRole = computed(() => {
    return article.value.author?.secondary_role || 'Staff Writer';
});

const displayWriterEmail = computed(() => {
    return article.value.author?.email || '';
});

const writerAvatarUrl = computed(() => {
    if (article.value.author?.profile_picture_url) return article.value.author.profile_picture_url;
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(displayWriter.value)}&background=dbeafe&color=1e40af&size=100`;
});

const formatDateTime = (dateVal) => {
    if (!dateVal) return '—';
    try {
        const d = new Date(dateVal);
        if (isNaN(d.getTime())) return String(dateVal);
        return d.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    } catch {
        return String(dateVal);
    }
};

const displayDateEndorsed = computed(() => {
    return formatDateTime(article.value.endorsed_at || article.value.submitted_at || article.value.created_at || article.value.dateEndorsed);
});

const displayDeadline = computed(() => {
    return formatDateTime(article.value.deadline || article.value.endorsed_at || article.value.created_at);
});

const rawStatus = computed(() => {
    const s = article.value.raw_status || article.value.status || 'submitted';
    return String(s).toLowerCase();
});

const displayStatus = computed(() => {
    const s = rawStatus.value;
    if (s === 'submitted') return 'submitted';
    if (s === 'endorsed') return 'endorsed';
    if (s === 'approved') return 'approved';
    if (s === 'published') return 'published';
    if (s === 'rejected' || s === 'returned') return 'returned';
    return s.charAt(0).toUpperCase() + s.slice(1);
});

const statusBadgeClass = computed(() => {
    const s = rawStatus.value;
    if (s === 'approved' || s === 'published') return 'status-green';
    if (s === 'rejected' || s === 'returned') return 'status-red';
    return 'status-yellow';
});

const displayWordCount = computed(() => {
    if (article.value.word_count) return article.value.word_count;
    if (article.value.wordCount) return article.value.wordCount;
    if (article.value.content && typeof article.value.content === 'string') {
        return article.value.content.trim().split(/\s+/).filter(Boolean).length;
    }
    return 0;
});

const hasArtist = computed(() => Boolean(article.value.artist_name));

const displayArtistRole = computed(() => {
    return article.value.artist_role || 'Staff Artist';
});

const displayArtistName = computed(() => {
    return article.value.artist_name || 'Not yet assigned';
});

const displayArtistEmail = computed(() => {
    return article.value.artist_email || '';
});

const artistAvatarUrl = computed(() => {
    if (article.value.artist_avatar) return article.value.artist_avatar;
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(displayArtistName.value)}&background=ffd5dc&color=9f1239&size=100`;
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

const displayAttachedFiles = computed(() => {
    if (Array.isArray(article.value.attached_files)) {
        return article.value.attached_files;
    }
    return [];
});

const closeModal = () => {
    isReturnModalOpen.value = false;
    isReturnSuccessOpen.value = false;
    emit('close');
};

const handleViewFullArticle = () => {
    if (isVideo.value) {
        if (article.value.video_url) window.open(article.value.video_url, '_blank', 'noopener');
        return;
    }
    emit('view-full-article', article.value);
};

const requestPublishPreview = () => {
    emit('request-publish-preview', article.value);
};

const submitReturnToWriter = async () => {
    const token = localStorage.getItem('sparky_token');
    const articleId = article.value.id;
    if (!token || !articleId) {
        returnError.value = 'Missing article or session — please reopen this article and try again.';
        return;
    }

    try {
        isSubmitting.value = true;
        returnError.value = '';
        const res = await fetch(`/api/articles/${articleId}/reject`, {
            method: 'POST',
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ rejection_reason: returnNotes.value || 'Please revise based on EIC feedback.' })
        });
        if (!res.ok) {
            const data = await res.json().catch(() => ({}));
            throw new Error(data.message || 'Failed to return the article to the writer.');
        }
        isReturnModalOpen.value = false;
        isReturnSuccessOpen.value = true;
    } catch (e) {
        returnError.value = e.message || 'Failed to return the article to the writer.';
    } finally {
        isSubmitting.value = false;
    }
};

const finishAll = () => {
    isReturnModalOpen.value = false;
    isReturnSuccessOpen.value = false;
    emit('action-complete');
    emit('close');
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

.article-title-block {
    margin-bottom: 20px;
}

.section-pill-badge {
    background-color: #dbeafe;
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
}

.article-main-heading {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin: 8px 0 0 0;
    line-height: 1.3;
}

/* TOP INFO ROW */
.top-info-row {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    gap: 20px;
    margin-bottom: 18px;
}

.meta-details-grid {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    gap: 16px 12px;
    background: #ffffff;
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

.status-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    width: fit-content;
}

.status-badge.status-yellow {
    background-color: #fef3c7;
    color: #92400e;
}

.status-badge.status-green {
    background-color: #dcfce7;
    color: #166534;
}

.status-badge.status-red {
    background-color: #fee2e2;
    color: #991b1b;
}

.word-count-chip {
    background-color: #dbeafe;
    color: #1d6bf3;
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 800;
    width: fit-content;
}

/* EDITOR REMARKS CARD */
.editor-remarks-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
}

.remarks-title {
    font-size: 12px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 2px 0;
}

.quote-icon {
    font-size: 24px;
    color: #94a3b8;
    line-height: 1;
    font-family: Georgia, serif;
}

.remarks-text {
    font-size: 12px;
    color: #475569;
    margin: 4px 0 14px 0;
    line-height: 1.45;
    font-style: italic;
}

.editor-profile-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: auto;
}

.artist-profile-row {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid #e2e8f0;
}

.editor-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #e2e8f0;
    flex-shrink: 0;
}

.editor-info {
    display: flex;
    flex-direction: column;
    gap: 1px;
    min-width: 0;
}

.role-pill-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 1px 7px;
    border-radius: 8px;
    font-size: 9px;
    font-weight: 700;
    width: fit-content;
}

.editor-name {
    font-size: 12px;
    font-weight: 800;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.editor-email {
    font-size: 10px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* MIDDLE CONTENT ROW */
.middle-content-row {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    gap: 20px;
    margin-bottom: 28px;
}

.video-embed {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    border-radius: 14px;
    overflow: hidden;
    background: #0f172a;
    margin-bottom: 12px;
}

.video-embed iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: 0;
}

.preview-box-card, .attached-files-card {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    padding: 20px;
    display: flex;
    flex-direction: column;
}

.card-box-title {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 12px 0;
}

.preview-text {
    font-size: 12.5px;
    color: #475569;
    margin: 0;
    line-height: 1.5;
}

.view-full-article-link {
    background: none;
    border: none;
    color: #0f172a;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 14px;
    padding: 0;
    transition: color 0.2s;
}

.view-full-article-link:hover {
    color: #1d6bf3;
}

.files-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.file-item {
    background: #f1f5f9;
    border: none;
    border-radius: 16px;
    padding: 10px;
    display: flex;
    align-items: center;
    gap: 14px;
}

.file-thumb-img {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    object-fit: cover;
    flex-shrink: 0;
}

.file-thumb-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    background: #eff6ff;
    color: #1d6bf3;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.file-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.file-name {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    word-break: break-all;
}

.file-size {
    font-size: 11px;
    color: #94a3b8;
}

.attachments-count-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 14px;
}

.attachments-pill {
    background: #e2e8f0;
    color: #475569;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 12px;
}

/* FOOTER ACTIONS */
.modal-footer-actions {
    display: grid;
    grid-template-columns: 1fr 1.4fr;
    gap: 12px;
}

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

.btn-green-light-pill {
    background-color: #dcfce7;
    color: #15803d;
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

.btn-green-light-pill:hover:not(:disabled) {
    background-color: #bbf7d0;
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
.btn-green-light-pill:disabled,
.btn-blue-primary-pill:disabled,
.btn-blue-pill:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-blue-full {
    background-color: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 13px;
    border-radius: 30px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    width: 100%;
    margin-top: 20px;
    box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3);
}

.btn-blue-full:hover {
    background-color: #1557b0;
}

/* DIALOG CARDS */
.dialog-card-medium {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 520px;
    padding: 32px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.dialog-card-small {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 440px;
    padding: 36px 32px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    text-align: center;
}

.notes-form-group {
    margin: 16px 0 24px 0;
    text-align: left;
}

.notes-label {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    display: block;
    margin-bottom: 8px;
}

.textarea-relative-wrapper {
    position: relative;
}

.notes-textarea {
    width: 100%;
    min-height: 120px;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 14px;
    box-sizing: border-box;
    font-size: 13px;
    font-family: inherit;
    color: #0f172a;
    outline: none;
    resize: vertical;
}

.notes-textarea:focus {
    border-color: #1d6bf3;
}

.char-count-badge {
    position: absolute;
    bottom: 12px;
    right: 14px;
    font-size: 11px;
    color: #94a3b8;
    font-weight: 600;
}

.notes-subtext {
    font-size: 12px;
    color: #64748b;
    margin: 8px 0 0 0;
}

.dialog-actions-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.circle-icon-wrap {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px auto;
}

.circle-icon-wrap.green {
    background-color: #dcfce7;
}

.dialog-success-title {
    font-size: 19px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 8px 0;
    line-height: 1.35;
    letter-spacing: -0.3px;
}

.dialog-success-subtext {
    font-size: 13px;
    color: #64748b;
    margin: 0;
    line-height: 1.45;
}

@media (max-width: 640px) {
    .top-info-row,
    .middle-content-row,
    .modal-footer-actions {
        grid-template-columns: 1fr;
    }
}
</style>
