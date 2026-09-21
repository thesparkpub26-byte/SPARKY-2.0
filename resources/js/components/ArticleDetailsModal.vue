<template>
    <div>
        <!-- MAIN ARTICLE DETAILS MODAL -->
        <div class="article-details-overlay" v-if="isOpen && !isReturnModalOpen && !isReturnSuccessOpen && !isApprovedSuccessOpen && !isPublishedSuccessOpen" @click.self="closeModal">
            <div class="article-details-card">
                
                <!-- Header -->
                <div class="modal-hdr">
                    <h2 class="modal-blue-title">Article Details</h2>
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

                <!-- Top Row: Grid Details & Section Editor Remarks -->
                <div class="top-info-row">
                    <!-- Left: Metadata Grid -->
                    <div class="meta-details-grid">
                        <div class="meta-item">
                            <span class="meta-label">Coverage</span>
                            <span class="meta-value">{{ displayCoverage }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Writer</span>
                            <span class="meta-value">{{ displayWriter }}</span>
                        </div>
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
                        <div class="meta-item">
                            <span class="meta-label">Word Count</span>
                            <span class="word-count-chip">{{ displayWordCount }}</span>
                        </div>
                    </div>

                    <!-- Right: Section Editor Remarks Card -->
                    <div class="editor-remarks-card">
                        <h4 class="remarks-title">Section Editor Remarks</h4>
                        <div class="quote-icon">“</div>
                        <p class="remarks-text">
                            {{ displayRemarks }}
                        </p>
                        <div class="editor-profile-bar">
                            <img :src="editorAvatarUrl" :alt="displayEditorName" class="editor-avatar" />
                            <div class="editor-info">
                                <div class="role-pill-badge">{{ displayEditorRole }}</div>
                                <div class="editor-name">{{ displayEditorName }}</div>
                                <div class="editor-email">{{ displayEditorEmail }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Row: Article Preview & Attached Files -->
                <div class="middle-content-row">
                    <!-- Left: Article Preview Card -->
                    <div class="preview-box-card">
                        <h4 class="card-box-title">Article Preview</h4>
                        <div class="preview-text-wrapper">
                            <p class="preview-text" v-if="displayPreviewParagraphs.length > 0" v-for="(para, idx) in displayPreviewParagraphs" :key="idx" :style="idx > 0 ? 'margin-top: 10px;' : ''">
                                {{ para }}
                            </p>
                            <p class="preview-text" v-else>
                                {{ defaultPreviewText }}
                            </p>
                        </div>
                        <button class="view-full-article-link" @click="handleViewFullArticle">
                            View Full Article 
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
                                <div class="file-thumb-icon">
                                    <svg v-if="file.type === 'image'" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                </div>
                                <div class="file-info">
                                    <span class="file-name">{{ file.name }}</span>
                                    <span class="file-size">{{ file.size }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="attachments-count-footer" v-if="displayAttachedFiles.length > 0">
                            <span class="attachments-pill">+ {{ displayAttachedFiles.length }} attachments</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Bar -->
                <div class="modal-footer-actions">
                    <button class="btn-grey-pill" @click="isReturnModalOpen = true" :disabled="isSubmitting">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 14 4 9 9 4"></polyline>
                            <path d="M20 20v-7a4 4 0 0 0-4-4H4"></path>
                        </svg>
                        Return to Editor
                    </button>
                    
                    <button class="btn-green-light-pill" @click="handleApprove" :disabled="isSubmitting">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        {{ isSubmitting ? 'Approving...' : 'Approve' }}
                    </button>

                    <button class="btn-blue-primary-pill" @click="handlePublish" :disabled="isSubmitting">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 11 18-5v12L3 14v-3z"></path>
                            <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>
                        </svg>
                        {{ isSubmitting ? 'Publishing...' : 'Publish Article' }}
                    </button>
                </div>

            </div>
        </div>

        <!-- SUB-MODAL 1: RETURN TO SECTION EDITOR -->
        <div class="article-details-overlay" v-if="isOpen && isReturnModalOpen && !isReturnSuccessOpen" @click.self="isReturnModalOpen = false">
            <div class="dialog-card-medium">
                <div class="modal-hdr">
                    <h2 class="modal-blue-title">Return to Section Editor</h2>
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
                    <p class="notes-subtext">This note will be visible to both the writer and section editor.</p>
                </div>

                <div class="dialog-actions-row">
                    <button class="btn-grey-pill" @click="isReturnModalOpen = false" :disabled="isSubmitting">Cancel</button>
                    <button class="btn-blue-pill" @click="submitReturnToEditor" :disabled="isSubmitting">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 9L9 20L4 15"></path>
                        </svg>
                        {{ isSubmitting ? 'Returning...' : 'Return to Editor' }}
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
                <h3 class="dialog-success-title">Article Returned to Section Editor</h3>
                <p class="dialog-success-subtext">The Section Editor has been notified and can now review your notes and coordinate necessary revisions.</p>
                <button class="btn-blue-full" @click="finishAll">Done</button>
            </div>
        </div>

        <!-- SUB-MODAL 3: APPROVED SUCCESSFUL -->
        <div class="article-details-overlay" v-if="isOpen && isApprovedSuccessOpen" @click.self="finishAll">
            <div class="dialog-card-small">
                <div class="circle-icon-wrap green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <h3 class="dialog-success-title">Article Approved</h3>
                <p class="dialog-success-subtext">The article has been marked as approved and is ready for the publication queue.</p>
                <button class="btn-blue-full" @click="finishAll">Done</button>
            </div>
        </div>

        <!-- SUB-MODAL 4: PUBLISHED SUCCESSFUL -->
        <div class="article-details-overlay" v-if="isOpen && isPublishedSuccessOpen" @click.self="finishAll">
            <div class="dialog-card-small">
                <div class="circle-icon-wrap green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <h3 class="dialog-success-title">Article Published Successfully</h3>
                <p class="dialog-success-subtext">This article is now live on the publication site and accessible to readers.</p>
                <div class="dialog-actions-row" style="margin-top: 20px;">
                    <button class="btn-grey-pill" @click="finishAll">Done</button>
                    <button class="btn-blue-pill" @click="visitArticle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="7" y1="17" x2="17" y2="7"></line>
                            <polyline points="7 7 17 7 17 17"></polyline>
                        </svg>
                        Visit Article
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

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

const emit = defineEmits(['close', 'view-full-article', 'action-complete']);

const article = computed(() => props.articleData || {});

const isReturnModalOpen = ref(false);
const returnNotes = ref('');
const isReturnSuccessOpen = ref(false);
const isApprovedSuccessOpen = ref(false);
const isPublishedSuccessOpen = ref(false);
const isSubmitting = ref(false);

// Extract clean section name
const displaySection = computed(() => {
    const sec = article.value.section;
    if (!sec) return 'News';
    if (typeof sec === 'string') return sec;
    if (typeof sec === 'object' && sec.name) return sec.name;
    return 'News';
});

const displayTitle = computed(() => {
    return article.value.title || 'Untitled Article';
});

const displayCoverage = computed(() => {
    if (article.value.coverage && typeof article.value.coverage === 'string') {
        return article.value.coverage;
    }
    return `${displaySection.value} Coverage`;
});

const displayWriter = computed(() => {
    if (article.value.author && typeof article.value.author === 'object' && article.value.author.name) {
        return article.value.author.name;
    }
    if (article.value.writer && typeof article.value.writer === 'string') {
        return article.value.writer;
    }
    return 'Samantha Ciscon';
});

const formatDateTime = (dateVal) => {
    if (!dateVal) return 'Sep 15, 2026, 2:12 AM';
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
    return 850;
});

const displayRemarks = computed(() => {
    return article.value.editor_notes || article.value.remarks || 'This article has been reviewed and revised on the initial feedback.';
});

const displayEditorRole = computed(() => {
    return `${displaySection.value} Editor`;
});

const displayEditorName = computed(() => {
    return article.value.editor_name || 'Johan Abinal';
});

const displayEditorEmail = computed(() => {
    return article.value.editor_email || 'sec.editor@thesparkpub.com';
});

const editorAvatarUrl = computed(() => {
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(displayEditorName.value)}&background=ffd5dc&color=9f1239&size=100`;
});

const defaultPreviewText = 'Campus creatives continue to shape a more expressive student community as more students explore their talents beyond academics. From writing, graphic design, photography, and video editing, students find new ways to share ideas and support campus organizations. Student publications and multimedia groups help young creatives showcase their skills and contribute meaningful work to the institution.\n\nTheir work also strengthens school identity and student engagement through events, awareness...';

const displayPreviewParagraphs = computed(() => {
    const text = article.value.excerpt || article.value.content || defaultPreviewText;
    return text.split('\n\n').filter(Boolean);
});

const displayAttachedFiles = computed(() => {
    if (Array.isArray(article.value.attached_files) && article.value.attached_files.length > 0) {
        return article.value.attached_files;
    }
    return [
        { name: 'imageThumbnail.png', size: '2.3 MB', type: 'image' },
        { name: 'imageOne.jpeg', size: '2.9 MB', type: 'image' },
        { name: 'imageTwo.jpeg', size: '2.3 MB', type: 'image' }
    ];
});

const closeModal = () => {
    isReturnModalOpen.value = false;
    isReturnSuccessOpen.value = false;
    isApprovedSuccessOpen.value = false;
    isPublishedSuccessOpen.value = false;
    emit('close');
};

const handleViewFullArticle = () => {
    emit('view-full-article', article.value);
};

const handleApprove = async () => {
    const token = localStorage.getItem('sparky_token');
    const articleId = article.value.id;
    if (token && articleId) {
        try {
            isSubmitting.value = true;
            await fetch(`/api/articles/${articleId}/approve`, {
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ eic_notes: 'Approved by EIC' })
            });
        } catch (e) {
            console.error(e);
        } finally {
            isSubmitting.value = false;
        }
    }
    isApprovedSuccessOpen.value = true;
};

const handlePublish = async () => {
    const token = localStorage.getItem('sparky_token');
    const articleId = article.value.id;
    if (token && articleId) {
        try {
            isSubmitting.value = true;
            await fetch(`/api/articles/${articleId}`, {
                method: 'PATCH',
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: 'published' })
            });
        } catch (e) {
            console.error(e);
        } finally {
            isSubmitting.value = false;
        }
    }
    isPublishedSuccessOpen.value = true;
};

const submitReturnToEditor = async () => {
    const token = localStorage.getItem('sparky_token');
    const articleId = article.value.id;
    if (token && articleId) {
        try {
            isSubmitting.value = true;
            await fetch(`/api/articles/${articleId}/reject`, {
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ rejection_reason: returnNotes.value || 'Please revise based on EIC feedback.' })
            });
        } catch (e) {
            console.error(e);
        } finally {
            isSubmitting.value = false;
        }
    }
    isReturnModalOpen.value = false;
    isReturnSuccessOpen.value = true;
};

const finishAll = () => {
    isReturnModalOpen.value = false;
    isReturnSuccessOpen.value = false;
    isApprovedSuccessOpen.value = false;
    isPublishedSuccessOpen.value = false;
    emit('action-complete');
    emit('close');
};

const visitArticle = () => {
    if (article.value.id) {
        window.open(`/article/${article.value.id}`, '_blank');
    } else {
        window.open('/article-details', '_blank');
    }
    finishAll();
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
    margin-bottom: 24px;
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
    padding: 18px;
    display: flex;
    flex-direction: column;
    position: relative;
}

.remarks-title {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 4px 0;
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
    gap: 10px;
    margin-top: auto;
}

.editor-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #e2e8f0;
}

.editor-info {
    display: flex;
    flex-direction: column;
    gap: 1px;
}

.role-pill-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 1px 8px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 700;
    width: fit-content;
}

.editor-name {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
}

.editor-email {
    font-size: 11px;
    color: #64748b;
}

/* MIDDLE CONTENT ROW */
.middle-content-row {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    gap: 20px;
    margin-bottom: 28px;
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
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.file-thumb-icon {
    width: 38px;
    height: 38px;
    border-radius: 8px;
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
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
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
    grid-template-columns: 1fr 1fr 1.3fr;
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
