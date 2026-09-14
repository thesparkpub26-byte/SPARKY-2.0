<template>
    <div>
        <!-- MAIN ARTICLE DETAILS MODAL -->
        <div class="article-details-overlay" v-if="isOpen && !isReturnModalOpen && !isReturnSuccessOpen && !isApprovedSuccessOpen && !isPublishedSuccessOpen" @click.self="closeModal">
            <div class="article-details-card">
                
                <!-- Header -->
                <div class="modal-hdr">
                    <h2 class="modal-blue-title">Article Details</h2>
                    <button class="modal-x-btn" @click="closeModal" title="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Section Badge & Article Title -->
                <div class="article-title-block">
                    <span class="section-pill-badge">{{ article.section || 'News' }}</span>
                    <h1 class="article-main-heading">{{ article.title || 'Campus Wi-Fi Expansion Project' }}</h1>
                </div>

                <!-- Top Row: Grid Details & Section Editor Remarks -->
                <div class="top-info-row">
                    <!-- Left: Metadata Grid -->
                    <div class="meta-details-grid">
                        <div class="meta-item">
                            <span class="meta-label">Coverage</span>
                            <span class="meta-value">{{ article.coverage || 'Tech & Innovation Series' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Writer</span>
                            <span class="meta-value">{{ article.writer || 'Gabrielle M. Loquias' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Date Endorsed</span>
                            <span class="meta-value">{{ article.dateEndorsed || 'Apr 14 • 10:31 AM' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Deadline</span>
                            <span class="meta-value">{{ article.deadline || 'Apr 16 • 5:00 PM' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Status</span>
                            <span class="status-badge-yellow">{{ article.status || 'For Approval' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Word Count</span>
                            <span class="word-count-chip">{{ article.wordCount || '95' }}</span>
                        </div>
                    </div>

                    <!-- Right: Section Editor Remarks Card -->
                    <div class="editor-remarks-card">
                        <h4 class="remarks-title">Section Editor Remarks</h4>
                        <div class="quote-icon">“</div>
                        <p class="remarks-text">
                            {{ article.remarks || 'This article has been reviewed and revised on the initial feedback. It is now endorsed for your final review and approval.' }}
                        </p>
                        <div class="editor-profile-bar">
                            <img src="https://picsum.photos/100?random=88" alt="Editor" class="editor-avatar" />
                            <div class="editor-info">
                                <div class="role-pill-badge">News Editor</div>
                                <div class="editor-name">Johan Abinal</div>
                                <div class="editor-email">sec.editor@thesparkpub.com</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Row: Article Preview & Attached Files -->
                <div class="middle-content-row">
                    <!-- Left: Article Preview Card -->
                    <div class="preview-box-card">
                        <h4 class="card-box-title">Article Preview</h4>
                        <p class="preview-text">
                            Campus creatives continue to shape a more expressive student community as more students explore their talents beyond academics. From writing, graphic design, photography, and video editing, students find new ways to share ideas and support campus organizations. Student publications and multimedia groups help young creatives showcase their skills and contribute meaningful work to the institution.
                        </p>
                        <p class="preview-text" style="margin-top: 10px;">
                            Their work also strengthens school identity and student engagement through events, awareness...
                        </p>
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
                            <div class="file-item">
                                <img src="https://picsum.photos/100?random=51" alt="Thumbnail" class="file-thumb" />
                                <div class="file-info">
                                    <span class="file-name">imageThumbnail.png</span>
                                    <span class="file-size">2.3 MB</span>
                                </div>
                            </div>
                            <div class="file-item">
                                <img src="https://picsum.photos/100?random=52" alt="Image 1" class="file-thumb" />
                                <div class="file-info">
                                    <span class="file-name">imageOne.jpeg</span>
                                    <span class="file-size">2.9 MB</span>
                                </div>
                            </div>
                            <div class="file-item">
                                <img src="https://picsum.photos/100?random=53" alt="Image 2" class="file-thumb" />
                                <div class="file-info">
                                    <span class="file-name">imageTwo.jpeg</span>
                                    <span class="file-size">2.3 MB</span>
                                </div>
                            </div>
                        </div>
                        <div class="attachments-count-footer">
                            <span class="attachments-pill">+ 3 attachments</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Bar -->
                <div class="modal-footer-actions">
                    <button class="btn-grey-pill" @click="isReturnModalOpen = true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 14 4 9 9 4"></polyline>
                            <path d="M20 20v-7a4 4 0 0 0-4-4H4"></path>
                        </svg>
                        Return to Editor
                    </button>
                    
                    <button class="btn-green-light-pill" @click="handleApprove">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Approve
                    </button>

                    <button class="btn-blue-primary-pill" @click="handlePublish">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 11 18-5v12L3 14v-3z"></path>
                            <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>
                        </svg>
                        Publish Article
                    </button>
                </div>

            </div>
        </div>

        <!-- SUB-MODAL 1: RETURN TO SECTION EDITOR -->
        <div class="article-details-overlay" v-if="isOpen && isReturnModalOpen && !isReturnSuccessOpen" @click.self="isReturnModalOpen = false">
            <div class="dialog-card-medium">
                <div class="modal-hdr">
                    <h2 class="modal-blue-title">Return to Section Editor</h2>
                    <button class="modal-x-btn" @click="isReturnModalOpen = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="notes-form-group">
                    <label class="notes-label">Notes</label>
                    <div class="textarea-relative-wrapper">
                        <textarea 
                            v-model="returnNotes" 
                            maxlength="500" 
                            placeholder="Write your thoughts here..." 
                            class="notes-textarea"
                        ></textarea>
                        <span class="char-count-badge">{{ returnNotes.length }}/500</span>
                    </div>
                    <p class="notes-subtext">This note will be visible to both assignee and editor.</p>
                </div>

                <div class="dialog-actions-row">
                    <button class="btn-grey-pill" @click="isReturnModalOpen = false">Cancel</button>
                    <button class="btn-blue-pill" @click="submitReturnToEditor">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 9L9 20L4 15"></path>
                        </svg>
                        Return to Editor
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
                <h3 class="dialog-success-title">This article was successfully returned to section editor</h3>
                <p class="dialog-success-subtext">The Section Editor has been notified and can now review your notes and manage the next revision.</p>
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
                <h3 class="dialog-success-title">This article has been approved</h3>
                <p class="dialog-success-subtext">The article is now ready for publishing and can proceed to the final release stage.</p>
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

const handleApprove = () => {
    isApprovedSuccessOpen.value = true;
};

const handlePublish = () => {
    isPublishedSuccessOpen.value = true;
};

const submitReturnToEditor = () => {
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
    window.open('/article-details', '_blank');
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
    font-size: 26px;
    font-weight: 800;
    margin: 0;
    letter-spacing: -0.5px;
    font-family: 'Manrope', -apple-system, sans-serif;
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
    font-family: 'Manrope', sans-serif;
}

.article-main-heading {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin: 8px 0 0 0;
    line-height: 1.3;
    font-family: 'Manrope', sans-serif;
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
    font-family: 'Manrope', sans-serif;
}

.meta-value {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    font-family: 'Manrope', sans-serif;
}

.status-badge-yellow {
    background-color: #fef3c7;
    color: #92400e;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    width: fit-content;
    font-family: 'Manrope', sans-serif;
}

.word-count-chip {
    background-color: #dbeafe;
    color: #1d6bf3;
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 800;
    width: fit-content;
    font-family: 'Manrope', sans-serif;
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
    font-family: 'Manrope', sans-serif;
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
    font-family: 'Manrope', sans-serif;
    font-style: italic;
}

.editor-profile-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: auto;
}

.editor-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    object-fit: cover;
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
    font-family: 'Manrope', sans-serif;
}

.editor-name {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    font-family: 'Manrope', sans-serif;
}

.editor-email {
    font-size: 11px;
    color: #64748b;
    font-family: 'Manrope', sans-serif;
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
    font-family: 'Manrope', sans-serif;
}

.preview-text {
    font-size: 12.5px;
    color: #475569;
    margin: 0;
    line-height: 1.5;
    font-family: 'Manrope', sans-serif;
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
    font-family: 'Manrope', sans-serif;
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
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.file-thumb {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    object-fit: cover;
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
    font-family: 'Manrope', sans-serif;
}

.file-size {
    font-size: 11px;
    color: #94a3b8;
    font-family: 'Manrope', sans-serif;
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
    font-family: 'Manrope', sans-serif;
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
    font-family: 'Manrope', sans-serif;
}

.btn-grey-pill:hover {
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
    font-family: 'Manrope', sans-serif;
}

.btn-green-light-pill:hover {
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
    font-family: 'Manrope', sans-serif;
}

.btn-blue-primary-pill:hover, .btn-blue-pill:hover {
    background-color: #1557b0;
    box-shadow: 0 6px 18px rgba(29, 107, 243, 0.4);
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
    font-family: 'Manrope', sans-serif;
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
    font-family: 'Manrope', sans-serif;
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
    font-family: 'Manrope', sans-serif;
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
    font-family: 'Manrope', sans-serif;
}

.notes-subtext {
    font-size: 12px;
    color: #64748b;
    margin: 8px 0 0 0;
    font-family: 'Manrope', sans-serif;
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
    font-family: 'Manrope', sans-serif;
}

.dialog-success-subtext {
    font-size: 13px;
    color: #64748b;
    margin: 0;
    line-height: 1.45;
    font-family: 'Manrope', sans-serif;
}
</style>
