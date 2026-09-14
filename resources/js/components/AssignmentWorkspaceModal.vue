<template>
    <div class="workspace-modal-overlay" v-if="isOpen" @click.self="closeModal">
        <div class="workspace-modal-card">
            
            <!-- Modal Header -->
            <div class="modal-hdr">
                <h2 class="modal-blue-title">Assignment Workspace</h2>
                <button class="modal-x-btn" @click="closeModal" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Article Information -->
            <div class="article-meta-hdr">
                <span class="section-pill-badge">{{ task.section || 'News' }}</span>
                <h1 class="article-main-title">{{ task.title || 'Enrollment Update for Second Semester' }}</h1>
            </div>

            <!-- Navigation Tabs Header -->
            <div class="workspace-tabs-nav">
                <button class="tab-nav-btn" :class="{ active: currentTab === 'content' }" @click="currentTab = 'content'">
                    Article Content
                </button>
                <button class="tab-nav-btn" :class="{ active: currentTab === 'visuals' }" @click="currentTab = 'visuals'">
                    Visual Assets
                </button>
                <button class="tab-nav-btn" :class="{ active: currentTab === 'details' }" @click="currentTab = 'details'">
                    Details
                </button>
            </div>

            <!-- TAB 1: ARTICLE CONTENT -->
            <div class="tab-content-body" v-if="currentTab === 'content'">
                <div class="editor-container-card">
                    <!-- Rich Text Toolbar -->
                    <div class="rich-toolbar">
                        <select class="toolbar-select">
                            <option>Paragraph</option>
                            <option>Heading 1</option>
                            <option>Heading 2</option>
                        </select>
                        <div class="toolbar-divider"></div>
                        <button class="tool-btn" title="Bold"><b>B</b></button>
                        <button class="tool-btn" title="Italic"><i>I</i></button>
                        <button class="tool-btn" title="Underline"><u>U</u></button>
                        <div class="toolbar-divider"></div>
                        <button class="tool-btn" title="Align Left">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                        </button>
                        <button class="tool-btn" title="Align Center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="10" x2="6" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="18" y1="18" x2="6" y2="18"/></svg>
                        </button>
                        <button class="tool-btn" title="Align Right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="21" y1="10" x2="7" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="7" y2="18"/></svg>
                        </button>
                        <div class="toolbar-divider"></div>
                        <button class="tool-btn" title="Link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        </button>
                        <div class="toolbar-divider"></div>
                        <button class="tool-btn" title="Undo">↶</button>
                        <button class="tool-btn" title="Redo">↷</button>
                    </div>

                    <!-- Article Text Editor Area -->
                    <div class="article-text-body" contenteditable="true">
                        <p>Camarines Sur Polytechnic Colleges (CSPC) has announced the start of second semester enrollment for Academic Year 2025-2026 on April 18. The process will be conducted both online and on-site to accommodate students.</p>
                        <p>The Office of the Registrar encourages early registration to avoid delays, especially during peak hours. Minor system improvements were also introduced to ensure a smoother enrollment experience.</p>
                        <p>Students are advised to stay updated through official announcements and coordinate with their departments for further assistance.</p>
                    </div>

                    <!-- Author Footer Bar -->
                    <div class="editor-footer-bar">
                        <div class="author-pill">
                            <img src="https://picsum.photos/100?random=201" class="author-avatar" alt="Author" />
                            <span>Gabrielle M. Loquias</span>
                        </div>
                        <span class="word-count-text">76 words</span>
                    </div>
                </div>

                <!-- Tags Section -->
                <div class="tags-section">
                    <h4 class="tags-hdr">Tags</h4>
                    <div class="tags-card-box">
                        <span class="tag-chip"># News</span>
                        <span class="tag-chip"># TheSPARK</span>
                        <span class="tag-chip"># Enrollment2026</span>
                    </div>
                </div>

                <!-- Notice Banner -->
                <div class="notice-banner-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <span>You cannot make changes to this section.</span>
                </div>
            </div>

            <!-- TAB 2: VISUAL ASSETS -->
            <div class="tab-content-body" v-if="currentTab === 'visuals'">
                <div class="visuals-section-card">
                    <!-- Thumbnail Box -->
                    <div class="asset-group">
                        <div class="asset-hdr">
                            <div>
                                <h4 class="asset-title">Thumbnail</h4>
                                <p class="asset-subtitle">This image will represent your article in the publication.</p>
                            </div>
                            <div class="assignee-pill">
                                <img src="https://picsum.photos/100?random=202" class="assignee-avatar" alt="Nicole" />
                                <span>Nicole Orcine</span>
                            </div>
                        </div>

                        <div class="dashed-dropzone">
                            <button class="btn-blue-pill-small" @click="triggerFileInput">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/><polyline points="16 16 12 12 8 16"/></svg>
                                Browse File
                            </button>
                            <p class="dropzone-text"><strong>Drag your file here or browse</strong><br><span class="sub">Max file size up to 10 MB</span></p>
                        </div>
                    </div>

                    <div class="divider-line"></div>

                    <!-- Media Uploads Box -->
                    <div class="asset-group">
                        <div class="asset-hdr">
                            <div>
                                <h4 class="asset-title">Media Uploads</h4>
                                <p class="asset-subtitle">Upload photos, graphics, and other media files related to this article.</p>
                            </div>
                            <div class="assignee-pill">
                                <img src="https://picsum.photos/100?random=203" class="assignee-avatar" alt="Johnrey" />
                                <span>Johnrey Frongoso</span>
                            </div>
                        </div>

                        <div class="dashed-dropzone">
                            <button class="btn-grey-pill-small" @click="triggerFileInput">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/><polyline points="16 16 12 12 8 16"/></svg>
                                Browse File
                            </button>
                            <p class="dropzone-text"><strong>Drag your file here or browse</strong><br><span class="sub">Max file size up to 10 MB</span></p>
                        </div>

                        <div class="notice-banner-warning" style="margin-top: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            <span>You cannot make changes to this section.</span>
                        </div>
                    </div>

                    <!-- Footer Action Bar -->
                    <div class="visuals-actions-footer">
                        <button class="btn-grey-pill-action">Save as Draft</button>
                        <button class="btn-blue-pill-action" @click="isSubmitModalOpen = true">Submit for Review</button>
                    </div>
                </div>
            </div>

            <!-- TAB 3: DETAILS -->
            <div class="tab-content-body" v-if="currentTab === 'details'">
                <div class="details-container-card">
                    <!-- Section Editor Notes -->
                    <div class="notes-card-box">
                        <h4 class="notes-hdr">
                            <span class="role-icon-circle">Ⓐ</span>
                            Section Editor Notes
                        </h4>
                        <div class="notes-content-empty">
                            Notes from the Section Editor will appear here once submitted for review.
                        </div>
                    </div>

                    <!-- EIC Notes -->
                    <div class="notes-card-box" style="margin-top: 16px;">
                        <h4 class="notes-hdr">
                            <span class="role-icon-circle">ⓖ</span>
                            EIC Notes
                        </h4>
                        <div class="notes-content-empty">
                            Notes from the Editor-in-Chief will appear here once endorsed.
                        </div>
                    </div>

                    <!-- Metadata Grid Summary Card -->
                    <div class="metadata-summary-card">
                        <div class="meta-row-grid">
                            <div class="meta-col">
                                <span class="meta-lbl">Coverage</span>
                                <span class="meta-val-bold">{{ task.coverage || 'AY 2025 - 2026 Issue 1' }}</span>
                            </div>
                            <div class="meta-col">
                                <span class="meta-lbl">Deadline</span>
                                <span class="meta-val-bold">{{ task.deadline || 'Apr 16 • 5:00 PM' }}</span>
                            </div>
                            <div class="meta-col">
                                <span class="meta-lbl">Last updated</span>
                                <span class="meta-val-bold">Apr 14 • 2:21 PM</span>
                            </div>
                        </div>

                        <div class="meta-row-grid" style="margin-top: 16px;">
                            <div class="meta-col">
                                <span class="meta-lbl">Assigned To</span>
                                <div class="collab-stack-row">
                                    <img src="https://picsum.photos/100?random=301" class="collab-avatar" />
                                    <img src="https://picsum.photos/100?random=302" class="collab-avatar" />
                                    <img src="https://picsum.photos/100?random=303" class="collab-avatar" />
                                </div>
                            </div>
                            <div class="meta-col">
                                <span class="meta-lbl">Status</span>
                                <span class="status-pill-draft">Draft</span>
                            </div>
                            <div class="meta-col">
                                <span class="meta-lbl">Priority</span>
                                <span class="priority-pill-badge moderate">⚡ Moderate</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- SUBMIT FOR REVIEW CONFIRMATION MODAL -->
        <div class="submodal-overlay" v-if="isSubmitModalOpen">
            <div class="submodal-card">
                <div class="submit-icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </div>

                <h3 class="submodal-title">Are you sure you want to submit this for review?</h3>
                <p class="submodal-desc">You will no longer be able to edit your submission until it is returned</p>

                <div class="submodal-actions">
                    <button class="btn-grey-pill" @click="isSubmitModalOpen = false">Cancel</button>
                    <button class="btn-blue-pill btn-full-width" @click="confirmSubmit">Yes, Submit</button>
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

                <h3 class="submodal-title">Your submission is successful!</h3>
                <p class="submodal-desc">The section editor will review your submission and will notify you once there are updates</p>

                <div class="submodal-actions">
                    <button class="btn-grey-pill" @click="closeAllModals">Done</button>
                    <button class="btn-blue-pill btn-full-width" @click="closeAllModals">View Submissions</button>
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
    taskData: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['close']);

const currentTab = ref('content');
const isSubmitModalOpen = ref(false);
const isSuccessModalOpen = ref(false);

const task = computed(() => props.taskData || {});

const closeModal = () => {
    emit('close');
};

const confirmSubmit = () => {
    isSubmitModalOpen.value = false;
    isSuccessModalOpen.value = true;
};

const closeAllModals = () => {
    isSuccessModalOpen.value = false;
    closeModal();
};

const triggerFileInput = () => {
    alert('File upload browser opened.');
};
</script>

<style scoped>
.workspace-modal-overlay,
.submodal-overlay {
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

.workspace-modal-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 680px;
    max-height: 90vh;
    padding: 32px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    display: flex;
    flex-direction: column;
    overflow-y: auto;
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
    font-family: 'Manrope', sans-serif;
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

.article-meta-hdr {
    margin-bottom: 16px;
}

.section-pill-badge {
    background-color: #dbeafe;
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
    margin-bottom: 8px;
    font-family: 'Manrope', sans-serif;
}

.article-main-title {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    line-height: 1.3;
    letter-spacing: -0.4px;
    font-family: 'Manrope', sans-serif;
}

.workspace-tabs-nav {
    display: flex;
    gap: 24px;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 20px;
}

.tab-nav-btn {
    background: none;
    border: none;
    padding: 10px 0;
    font-size: 14px;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    position: relative;
    font-family: 'Manrope', sans-serif;
    transition: color 0.2s;
}

.tab-nav-btn.active {
    color: #1d6bf3;
}

.tab-nav-btn.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 2.5px;
    background-color: #1d6bf3;
    border-radius: 2px;
}

.tab-content-body {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* Editor Container */
.editor-container-card {
    background: #f8fafc;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.rich-toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
}

.toolbar-select {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 4px 8px;
    font-size: 13px;
    font-family: 'Manrope', sans-serif;
    color: #0f172a;
}

.toolbar-divider {
    width: 1px;
    height: 18px;
    background: #cbd5e1;
}

.tool-btn {
    background: none;
    border: none;
    padding: 4px 8px;
    font-size: 14px;
    color: #475569;
    cursor: pointer;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tool-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
}

.article-text-body {
    padding: 20px;
    min-height: 160px;
    font-size: 14px;
    color: #334155;
    line-height: 1.6;
    outline: none;
    background: #ffffff;
    font-family: 'Manrope', sans-serif;
}

.editor-footer-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.author-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    padding: 4px 12px 4px 4px;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
}

.author-avatar,
.assignee-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    object-fit: cover;
}

.word-count-text {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 600;
}

.tags-section {
    margin-top: 4px;
}

.tags-hdr {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 8px 0;
    font-family: 'Manrope', sans-serif;
}

.tags-card-box {
    background: #f8fafc;
    border-radius: 16px;
    padding: 12px 16px;
    display: flex;
    gap: 8px;
    border: 1px solid #f1f5f9;
}

.tag-chip {
    background: #dbeafe;
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 14px;
    font-size: 12px;
    font-weight: 700;
    font-family: 'Manrope', sans-serif;
}

.notice-banner-warning {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #1d6bf3;
    padding: 10px 0;
}

/* Visual Assets Styling */
.visuals-section-card,
.details-container-card {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.asset-group {
    background: #f8fafc;
    border-radius: 20px;
    padding: 20px;
    border: 1px solid #e2e8f0;
}

.asset-hdr {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 14px;
}

.asset-title {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 4px 0;
}

.asset-subtitle {
    font-size: 12px;
    color: #64748b;
    margin: 0;
}

.assignee-pill {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    padding: 4px 10px 4px 4px;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
}

.dashed-dropzone {
    border: 1.5px dashed #cbd5e1;
    border-radius: 16px;
    background: #ffffff;
    padding: 28px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-align: center;
}

.dropzone-text {
    font-size: 13px;
    color: #0f172a;
    margin: 0;
    line-height: 1.4;
}

.dropzone-text .sub {
    font-size: 11px;
    color: #94a3b8;
}

.btn-blue-pill-small {
    background-color: #1d6bf3;
    color: white;
    border: none;
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-grey-pill-small {
    background-color: #475569;
    color: white;
    border: none;
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
}

.divider-line {
    height: 1px;
    background: #e2e8f0;
}

.visuals-actions-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 10px;
}

.btn-grey-pill-action {
    background-color: #f1f5f9;
    color: #475569;
    border: none;
    padding: 12px 24px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
}

.btn-blue-pill-action {
    background-color: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 12px 28px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3);
}

/* Details Tab Styling */
.notes-card-box {
    background: #f8fafc;
    border-radius: 20px;
    padding: 20px;
    border: 1px solid #e2e8f0;
}

.notes-hdr {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 12px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.role-icon-circle {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 1.5px solid #0f172a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
}

.notes-content-empty {
    background: #ffffff;
    border-radius: 14px;
    padding: 32px 20px;
    font-size: 13px;
    color: #64748b;
    text-align: center;
    border: 1px solid #f1f5f9;
}

.metadata-summary-card {
    background: #f8fafc;
    border-radius: 20px;
    padding: 20px;
    border: 1px solid #e2e8f0;
}

.meta-row-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.meta-col {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.meta-lbl {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 600;
}

.meta-val-bold {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
}

.collab-stack-row {
    display: flex;
    align-items: center;
}

.collab-avatar {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    border: 2px solid #ffffff;
    margin-right: -6px;
    object-fit: cover;
}

.status-pill-draft {
    background: #e2e8f0;
    color: #475569;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    width: fit-content;
}

.priority-pill-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 14px;
    font-size: 12px;
    font-weight: 700;
    width: fit-content;
}

.priority-pill-badge.moderate {
    background-color: #fef3c7;
    color: #92400e;
}

/* Sub-modals */
.submodal-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 400px;
    padding: 32px;
    text-align: center;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.submit-icon-circle {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background-color: #1d6bf3;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px auto;
}

.success-icon-circle {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background-color: #dcfce7;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px auto;
}

.submodal-title {
    font-size: 19px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 8px 0;
    font-family: 'Manrope', sans-serif;
    line-height: 1.35;
}

.submodal-desc {
    font-size: 13px;
    color: #64748b;
    line-height: 1.45;
    margin: 0 0 24px 0;
    font-family: 'Manrope', sans-serif;
}

.submodal-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-grey-pill {
    background-color: #f1f5f9;
    color: #475569;
    border: none;
    padding: 12px;
    width: 100%;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Manrope', sans-serif;
}

.btn-blue-pill {
    background-color: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 12px;
    width: 100%;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3);
    font-family: 'Manrope', sans-serif;
}
</style>
