<template>
    <div class="se-review-overlay" v-if="isOpen" @click.self="handleClose">
        <div class="se-review-card">

            <!-- Header -->
            <div class="review-hdr">
                <h2 class="review-title">Review Submission</h2>
                <button class="modal-x-btn" @click="handleClose" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <!-- Main review view -->
            <div v-if="step === 'review'" class="review-body review-body-scrollable">
                <div class="review-scroll-content">
                    <!-- Article Info -->
                    <div class="review-article-info">
                        <div class="review-section-badge">{{ submissionData.section || 'News' }}</div>
                        <input v-model="editableTitle" class="review-title-input" placeholder="Article headline" />
                        <div class="review-meta-row">
                            <div class="review-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <span>{{ submissionData.authorName || 'Unknown Writer' }}</span>
                            </div>
                            <div class="review-meta-item" v-if="submissionData.submittedAt">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <span>Submitted {{ formatDate(submissionData.submittedAt) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="review-divider"></div>

                    <!-- Live word count -->
                    <div class="review-stats-row" v-if="liveWordCount">
                        <div class="review-stat-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            {{ liveWordCount }} words
                        </div>
                    </div>

                    <div class="review-article-preview" v-if="submissionData.coverImage || submissionData.content">
                        <img v-if="submissionData.coverImage" :src="submissionData.coverImage.startsWith('/') ? submissionData.coverImage : '/storage/' + submissionData.coverImage" class="review-cover-img" alt="">

                        <div v-if="submissionData.content" class="review-editor-wrap">
                            <div class="review-rich-toolbar">
                                <button class="review-tool-btn" type="button" title="Bold" @click="formatDoc('bold')"><b>B</b></button>
                                <button class="review-tool-btn" type="button" title="Italic" @click="formatDoc('italic')"><i>I</i></button>
                                <button class="review-tool-btn" type="button" title="Underline" @click="formatDoc('underline')"><u>U</u></button>
                                <button class="review-tool-btn" type="button" title="Highlight" @click="formatDoc('hiliteColor', '#fef08a')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 11-6 6v3h3l6-6"/><path d="m22 12-4.6 4.6a2 2 0 0 1-2.8 0l-5.2-5.2a2 2 0 0 1 0-2.8L14 4"/></svg>
                                </button>
                                <button class="review-tool-btn" type="button" title="Remove Highlight" @click="formatDoc('hiliteColor', 'transparent')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 11-6 6v3h3l6-6"/><path d="m22 12-4.6 4.6a2 2 0 0 1-2.8 0l-5.2-5.2a2 2 0 0 1 0-2.8L14 4"/></svg><line x1="3" y1="3" x2="21" y2="21"/>
                                </button>
                                <div class="review-toolbar-divider"></div>
                                <button class="review-tool-btn" type="button" title="Undo" @click="formatDoc('undo')">↶</button>
                                <button class="review-tool-btn" type="button" title="Redo" @click="formatDoc('redo')">↷</button>
                            </div>
                            <div
                                ref="editorRef"
                                class="review-article-body review-article-body-editable"
                                contenteditable="true"
                                @input="handleEditorInput"
                            ></div>
                        </div>
                        <p v-else class="review-no-content">No article content to preview.</p>
                    </div>
                </div>

                <div class="review-actions-footer">
                    <p class="review-instruction">Choose what to do with this submission:</p>

                    <!-- Action Buttons -->
                    <div class="review-actions-grid">
                        <!-- Submit to Copyreader -->
                        <button class="review-action-btn endorse" @click="openCopyreaderStep" :disabled="acting">
                            <div class="review-action-icon endorse-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                            </div>
                            <div class="review-action-text">
                                <span class="review-action-label">Submit to Copyreader</span>
                                <span class="review-action-sub">Endorse this article for editorial review</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </button>

                        <!-- Return to Writer -->
                        <button class="review-action-btn return" @click="step = 'return-notes'" :disabled="acting">
                            <div class="review-action-icon return-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></svg>
                            </div>
                            <div class="review-action-text">
                                <span class="review-action-label">Return to Writer</span>
                                <span class="review-action-sub">Send back with revision notes</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </button>
                    </div>

                    <p v-if="errorMsg" class="se-error-msg">{{ errorMsg }}</p>
                </div>
            </div>

            <!-- Return to Writer: notes step -->
            <div v-if="step === 'return-notes'" class="review-body">
                <button class="back-btn" @click="step = 'review'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Back
                </button>
                <h3 class="review-step-heading">Return to Writer</h3>
                <p class="review-step-sub">Provide revision notes to <strong>{{ submissionData.authorName }}</strong>. They will be notified and the task will appear as <em>Returned</em> in their dashboard.</p>

                <div class="et-field-group" style="margin-top: 16px;">
                    <label class="et-label">Revision Notes <span style="color: #dc2626;">*</span></label>
                    <div class="et-textarea-wrap">
                        <textarea
                            v-model="returnNotes"
                            class="et-textarea"
                            maxlength="800"
                            rows="5"
                            placeholder="E.g. Please add more context to the second paragraph. Revise the lead..."
                        ></textarea>
                        <div class="et-char-count">{{ returnNotes.length }}/800</div>
                    </div>
                </div>

                <p v-if="errorMsg" class="se-error-msg">{{ errorMsg }}</p>

                <div class="review-step-footer">
                    <button class="btn-grey-pill" @click="step = 'review'" :disabled="acting">Cancel</button>
                    <button class="btn-return-pill" @click="confirmReturn" :disabled="acting || !returnNotes.trim()">
                        <svg v-if="acting" class="spin-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></svg>
                        {{ acting ? 'Returning...' : 'Return to Writer' }}
                    </button>
                </div>
            </div>

            <!-- Select Copyreader step -->
            <div v-if="step === 'select-copyreader'" class="review-body">
                <button class="back-btn" @click="step = 'review'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Back
                </button>
                <h3 class="review-step-heading">Select a Copyreader</h3>
                <p class="review-step-sub">Choose who will copyedit <strong>"{{ editableTitle || submissionData.title }}"</strong> before it moves forward.</p>

                <div v-if="loadingCopyreaders" class="review-step-sub" style="margin-top: 12px;">Loading copyreaders…</div>
                <p v-else-if="copyreaders.length === 0" class="review-no-content" style="margin-top: 12px; border: 1px solid #e2e8f0; border-radius: 12px;">
                    No active Copy Editors or Copyreaders found. Assign the "Copy Editor" or "Copyreader" role to a contributor first.
                </p>
                <div v-else class="copyreader-list">
                    <button
                        v-for="cr in copyreaders"
                        :key="cr.id"
                        type="button"
                        class="copyreader-option"
                        :class="{ selected: selectedCopyreaderId === cr.id }"
                        @click="selectedCopyreaderId = cr.id"
                    >
                        <img :src="cr.profile_picture ? '/storage/' + cr.profile_picture : (cr.profile_picture_url || copyreaderAvatarFallback(cr.name))" class="copyreader-avatar" :alt="cr.name">
                        <div class="copyreader-info">
                            <span class="copyreader-name">{{ cr.name }}</span>
                            <span class="copyreader-role">{{ cr.secondary_role === 'Copy Editor' || cr.secondary_role === 'Copyreader' ? cr.secondary_role : cr.tertiary_role }}</span>
                        </div>
                        <svg v-if="selectedCopyreaderId === cr.id" class="copyreader-check" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </button>
                </div>

                <p v-if="errorMsg" class="se-error-msg">{{ errorMsg }}</p>

                <div class="review-step-footer">
                    <button class="btn-grey-pill" @click="step = 'review'">Cancel</button>
                    <button class="btn-endorse-pill" @click="step = 'confirm-endorse'" :disabled="!selectedCopyreaderId">
                        Continue
                    </button>
                </div>
            </div>

            <!-- Confirm Endorse step -->
            <div v-if="step === 'confirm-endorse'" class="review-body">
                <button class="back-btn" @click="step = 'select-copyreader'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Back
                </button>
                <div class="confirm-endorse-icon-wrap">
                    <div class="confirm-endorse-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    </div>
                </div>
                <h3 class="review-step-heading" style="margin-top: 12px;">Send to {{ selectedCopyreader?.name || 'Copyreader' }}?</h3>
                <p class="review-step-sub">
                    <strong>"{{ editableTitle || submissionData.title }}"</strong> will be sent to <strong>{{ selectedCopyreader?.name || 'the Copyreader' }}</strong> for copyediting.
                    The writer will be notified that their article has been endorsed.
                </p>

                <p v-if="errorMsg" class="se-error-msg">{{ errorMsg }}</p>

                <div class="review-step-footer">
                    <button class="btn-grey-pill" @click="step = 'review'" :disabled="acting">Cancel</button>
                    <button class="btn-endorse-pill" @click="confirmEndorse" :disabled="acting">
                        <svg v-if="acting" class="spin-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ acting ? 'Submitting...' : 'Yes, Submit to Copyreader' }}
                    </button>
                </div>
            </div>

            <!-- Success step -->
            <div v-if="step === 'success'" class="review-body success-body">
                <div class="success-icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <h3 class="success-title">{{ successTitle }}</h3>
                <p class="success-sub">{{ successMessage }}</p>
                <button class="btn-blue-pill" style="width: 100%; justify-content: center;" @click="handleClose">Done</button>
            </div>

        </div>
    </div>
</template>

<script setup>
import { requireOk, followUp, followUpNote } from '../utils/http';
import { ref, computed, watch, nextTick } from 'vue';

const props = defineProps({
    isOpen: { type: Boolean, default: false },
    submission: { type: Object, default: () => ({}) }
});

const emit = defineEmits(['close', 'reviewed']);

const step = ref('review'); // 'review' | 'return-notes' | 'select-copyreader' | 'confirm-endorse' | 'success'
const returnNotes = ref('');
const acting = ref(false);
const errorMsg = ref('');
const successTitle = ref('');
const successMessage = ref('');

const submissionData = ref({});

const editableTitle = ref('');
const articleContent = ref('');
const editorRef = ref(null);

const formatDoc = (cmd, val = null) => {
    document.execCommand(cmd, false, val);
    handleEditorInput();
};

const handleEditorInput = () => {
    if (editorRef.value) {
        articleContent.value = editorRef.value.innerHTML;
    }
};

const liveWordCount = computed(() => {
    const clean = (articleContent.value || '')
        .replace(/<[^>]*>/g, ' ')
        .replace(/&nbsp;/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();
    if (!clean) return 0;
    return clean.split(/\s+/).filter(Boolean).length;
});

const copyreaders = ref([]);
const loadingCopyreaders = ref(false);
const selectedCopyreaderId = ref(null);
const selectedCopyreader = computed(() => copyreaders.value.find(cr => cr.id === selectedCopyreaderId.value) || null);

const copyreaderAvatarFallback = (name) => `https://ui-avatars.com/api/?name=${encodeURIComponent(name || 'User')}&background=dbeafe&color=1d4ed8`;

const loadCopyreaders = async () => {
    loadingCopyreaders.value = true;
    try {
        const token = localStorage.getItem('sparky_token');
        const res = await fetch('/api/users', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        if (res.ok) {
            const data = await res.json();
            const list = Array.isArray(data) ? data : (data.users || []);
            copyreaders.value = list.filter(u =>
                u.is_active !== false &&
                (['Copy Editor', 'Copyreader'].includes(u.secondary_role) || ['Copy Editor', 'Copyreader'].includes(u.tertiary_role))
            );
        }
    } catch (e) {
        console.warn('Could not load copyreaders:', e);
    } finally {
        loadingCopyreaders.value = false;
    }
};

const openCopyreaderStep = () => {
    step.value = 'select-copyreader';
    if (copyreaders.value.length === 0) loadCopyreaders();
};

watch(() => [props.isOpen, props.submission], () => {
    if (props.isOpen && props.submission) {
        submissionData.value = {
            articleId: props.submission.id || props.submission.article_id,
            // Always the writer's task: it is the one that has to go back to "Returned" so they can revise and resubmit
            taskId: props.submission.taskId || props.submission.task_id
                || (props.submission.raw?.tasks || []).find(t => t.type === 'writing')?.id
                || props.submission.raw?.tasks?.[0]?.id || null,
            title: props.submission.title,
            section: props.submission.section?.name || props.submission.section || 'News',
            authorName: props.submission.author?.name || props.submission.authorName || '—',
            submittedAt: props.submission.submitted_at || props.submission.raw?.submitted_at,
            wordCount: props.submission.word_count || props.submission.raw?.word_count,
            content: props.submission.content || props.submission.raw?.content || '',
            coverImage: props.submission.cover_image || props.submission.raw?.cover_image || '',
        };
        step.value = 'review';
        returnNotes.value = '';
        errorMsg.value = '';
        selectedCopyreaderId.value = null;
        editableTitle.value = submissionData.value.title || '';
        articleContent.value = submissionData.value.content || '';

        nextTick(() => {
            if (editorRef.value) {
                editorRef.value.innerHTML = articleContent.value;
            }
        });
    }
}, { immediate: true, deep: true });

const formatDate = (d) => {
    if (!d) return '';
    const date = new Date(d);
    if (isNaN(date.getTime())) return d;
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) +
        ' • ' + date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
};

const handleClose = () => {
    emit('close');
};

const confirmReturn = async () => {
    if (!returnNotes.value.trim()) {
        errorMsg.value = 'Please provide revision notes.';
        return;
    }

    // Prefer task-based return (sets task status = returned + notifies writer)
    const taskId = submissionData.value.taskId;
    const articleId = submissionData.value.articleId;

    acting.value = true;
    errorMsg.value = '';

    try {
        const token = localStorage.getItem('sparky_token');
        let success = false;

        if (taskId) {
            // Return via task route — status = 'returned', notifies assignee
            const res = await fetch(`/api/tasks/${taskId}/return`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ notes: returnNotes.value.trim() })
            });
            if (!res.ok) {
                const d = await res.json().catch(() => ({}));
                throw new Error(d.message || 'Failed to return task.');
            }
            success = true;
        }

        // Save the section editor's edits and reset article status back to draft
        if (articleId) {
            await requireOk(await fetch(`/api/articles/${articleId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title: editableTitle.value || submissionData.value.title,
                    content: articleContent.value,
                    word_count: liveWordCount.value,
                    status: 'draft',
                })
            }), 'The writer was notified, but the article could not be moved back to draft.');
        }

        if (!taskId && !success) throw new Error('Could not find task to return.');

        successTitle.value = 'Article Returned';
        successMessage.value = `"${editableTitle.value || submissionData.value.title}" has been returned to ${submissionData.value.authorName} with your revision notes. They will be notified.`;
        step.value = 'success';
        emit('reviewed', { action: 'returned' });
    } catch (e) {
        errorMsg.value = e.message || 'An error occurred.';
    } finally {
        acting.value = false;
    }
};

const confirmEndorse = async () => {
    if (!selectedCopyreaderId.value) {
        errorMsg.value = 'Please select a copyreader.';
        step.value = 'select-copyreader';
        return;
    }

    acting.value = true;
    errorMsg.value = '';

    try {
        const token = localStorage.getItem('sparky_token');
        const articleId = submissionData.value.articleId;
        if (!articleId) throw new Error('Missing article ID.');

        // Reuse an existing editing task for this article if one already exists
        // (e.g. it was previously sent to a copyreader and returned), instead of
        // creating a duplicate every time the SE re-submits. Also inherit the
        // deadline/priority from the writer's original task so the copyreader
        // sees the same assignment details, not defaults.
        let existingEditingTaskId = null;
        let writingTask = null;
        const artLookupRes = await fetch(`/api/articles/${articleId}`, {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        if (artLookupRes.ok) {
            const artLookupData = await artLookupRes.json();
            existingEditingTaskId = (artLookupData.tasks || []).find(t => t.type === 'editing')?.id || null;
            writingTask = (artLookupData.tasks || []).find(t => t.type === 'writing') || null;
        }

        const taskPayload = {
            title: editableTitle.value || submissionData.value.title,
            article_id: articleId,
            assignee_id: selectedCopyreaderId.value,
            type: 'editing',
            priority: writingTask?.priority || 'medium',
            deadline: writingTask?.deadline || null,
            status: 'pending',
            notes: `Section: ${submissionData.value.section || 'News'}`,
        };

        const taskRes = existingEditingTaskId
            ? await fetch(`/api/tasks/${existingEditingTaskId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(taskPayload)
            })
            : await fetch('/api/tasks', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(taskPayload)
            });

        if (!taskRes.ok) {
            const d = await taskRes.json().catch(() => ({}));
            throw new Error(d.message || 'Failed to assign copyreader.');
        }

        // Save the section editor's edits and move the article into copyediting
        await requireOk(await fetch(`/api/articles/${articleId}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                title: editableTitle.value || submissionData.value.title,
                content: articleContent.value,
                word_count: liveWordCount.value,
                status: 'under_review',
            })
        }), 'The copyreader was assigned, but the article could not be moved to copyediting.');

        // Also complete the linked writing task
        const problems = [];
        const taskId = submissionData.value.taskId;
        if (taskId) {
            problems.push(await followUp("The writer's task could not be marked complete.", () => fetch(`/api/tasks/${taskId}/complete`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })));
        }

        successTitle.value = 'Sent to Copyreader!';
        successMessage.value = `"${editableTitle.value || submissionData.value.title}" has been sent to ${selectedCopyreader.value?.name || 'the Copyreader'} for copyediting. The writer has been notified.` + followUpNote(problems);
        step.value = 'success';
        emit('reviewed', { action: 'endorsed' });
    } catch (e) {
        errorMsg.value = e.message || 'An error occurred.';
    } finally {
        acting.value = false;
    }
};
</script>

<style scoped>
.se-review-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(5px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
}

.se-review-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 520px;
    max-height: 88vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 25px 60px -12px rgba(0,0,0,0.3);
    animation: popIn 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    overflow: hidden;
}

@keyframes popIn {
    from { opacity: 0; transform: scale(0.95) translateY(12px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.review-hdr {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px 24px 16px;
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0;
}

.review-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.modal-x-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    transition: background 0.15s;
}
.modal-x-btn:hover { background: #f1f5f9; }

.review-body {
    padding: 20px 24px 24px;
    display: flex;
    flex-direction: column;
    gap: 0;
}

.review-body-scrollable {
    padding: 0;
    flex: 1 1 auto;
    min-height: 0;
    overflow: hidden;
}

.review-scroll-content {
    padding: 20px 24px 4px;
    overflow-y: auto;
    flex: 1 1 auto;
    min-height: 0;
}

.review-actions-footer {
    padding: 16px 24px 24px;
    border-top: 1px solid #f1f5f9;
    flex-shrink: 0;
}

.review-article-info {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 16px;
}

.review-section-badge {
    display: inline-flex;
    align-self: flex-start;
    padding: 3px 10px;
    background: #dbeafe;
    color: #1d4ed8;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.review-article-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin: 4px 0 0;
    line-height: 1.3;
}

.review-title-input {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin: 4px 0 0;
    line-height: 1.3;
    font-family: inherit;
    border: 1.5px solid transparent;
    border-radius: 8px;
    padding: 4px 6px;
    margin-left: -6px;
    width: calc(100% + 12px);
    outline: none;
    transition: border-color 0.15s, background 0.15s;
}

.review-title-input:hover {
    background: #f8fafc;
}

.review-title-input:focus {
    border-color: #93c5fd;
    background: #eff6ff;
}

.review-meta-row {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-top: 4px;
}

.review-meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    color: #64748b;
}

.review-divider {
    height: 1px;
    background: #f1f5f9;
    margin-bottom: 16px;
}

.review-stats-row {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}

.review-stat-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 50px;
    font-size: 12px;
    color: #475569;
    font-weight: 500;
}

.review-instruction {
    font-size: 13px;
    color: #64748b;
    margin: 0 0 12px;
}

.review-article-preview {
    margin-bottom: 16px;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
}

.review-cover-img {
    width: 100%;
    max-height: 180px;
    object-fit: cover;
    display: block;
}

.review-article-body {
    padding: 14px 16px;
    font-size: 13.5px;
    line-height: 1.7;
    color: #334155;
}

.review-article-body :deep(p) {
    margin: 0 0 10px;
}

.review-editor-wrap {
    display: flex;
    flex-direction: column;
}

.review-rich-toolbar {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 8px 10px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.review-tool-btn {
    width: 26px;
    height: 26px;
    border: none;
    background: transparent;
    border-radius: 6px;
    color: #475569;
    font-size: 12.5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s;
}

.review-tool-btn:hover {
    background: #e2e8f0;
}

.review-toolbar-divider {
    width: 1px;
    height: 16px;
    background: #e2e8f0;
    margin: 0 4px;
}

.review-article-body-editable {
    min-height: 140px;
    outline: none;
    cursor: text;
}

.review-article-body-editable:focus {
    background: #fefefe;
}

.review-article-body :deep(p:last-child) {
    margin-bottom: 0;
}

.review-no-content {
    padding: 14px 16px;
    font-size: 13px;
    color: #94a3b8;
    margin: 0;
}

.review-actions-grid {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.review-action-btn {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px;
    border-radius: 16px;
    border: 2px solid transparent;
    cursor: pointer;
    text-align: left;
    transition: all 0.15s;
    width: 100%;
    background: #f8fafc;
}

.review-action-btn.endorse {
    border-color: #bfdbfe;
    background: #eff6ff;
}
.review-action-btn.endorse:hover:not(:disabled) {
    background: #dbeafe;
    border-color: #93c5fd;
}

.review-action-btn.return {
    border-color: #fde68a;
    background: #fffbeb;
}
.review-action-btn.return:hover:not(:disabled) {
    background: #fef3c7;
    border-color: #fbbf24;
}

.review-action-btn:disabled { opacity: 0.5; cursor: not-allowed; }

.review-action-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.endorse-icon { background: #2563eb; color: white; }
.return-icon { background: #f59e0b; color: white; }

.review-action-text {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.review-action-label {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
}

.review-action-sub {
    font-size: 12px;
    color: #64748b;
}

.se-error-msg {
    color: #dc2626;
    font-size: 13px;
    margin: 8px 0 0;
    padding: 8px 12px;
    background: #fef2f2;
    border-radius: 8px;
    border: 1px solid #fecaca;
}

/* Return notes & confirm steps */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: none;
    border: none;
    color: #64748b;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
    margin-bottom: 12px;
    transition: color 0.15s;
}
.back-btn:hover { color: #1e293b; }

.review-step-heading {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 8px;
}

.review-step-sub {
    font-size: 13.5px;
    color: #475569;
    line-height: 1.6;
    margin: 0 0 4px;
}

.copyreader-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 14px;
    max-height: 280px;
    overflow-y: auto;
}

.copyreader-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: 14px;
    border: 2px solid #e2e8f0;
    background: #f8fafc;
    cursor: pointer;
    text-align: left;
    transition: all 0.15s;
    width: 100%;
}

.copyreader-option:hover {
    border-color: #93c5fd;
    background: #eff6ff;
}

.copyreader-option.selected {
    border-color: #2563eb;
    background: #eff6ff;
}

.copyreader-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.copyreader-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1px;
    min-width: 0;
}

.copyreader-name {
    font-size: 13.5px;
    font-weight: 700;
    color: #1e293b;
}

.copyreader-role {
    font-size: 12px;
    color: #64748b;
}

.copyreader-check {
    flex-shrink: 0;
}

.et-field-group { display: flex; flex-direction: column; gap: 6px; }
.et-label { font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
.et-textarea-wrap { position: relative; }
.et-textarea {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 14px;
    color: #1e293b;
    width: 100%;
    resize: vertical;
    outline: none;
    box-sizing: border-box;
    font-family: inherit;
    transition: border-color 0.15s;
}
.et-textarea:focus { border-color: #2563eb; }
.et-char-count { font-size: 11px; color: #94a3b8; text-align: right; margin-top: 4px; }

.review-step-footer {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn-grey-pill {
    padding: 10px 20px;
    border-radius: 50px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    color: #64748b;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-grey-pill:hover:not(:disabled) { background: #f1f5f9; }
.btn-grey-pill:disabled { opacity: 0.6; cursor: not-allowed; }

.btn-blue-pill {
    padding: 10px 22px;
    border-radius: 50px;
    border: none;
    background: #2563eb;
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s;
}
.btn-blue-pill:hover:not(:disabled) { background: #1d4ed8; }
.btn-blue-pill:disabled { opacity: 0.6; cursor: not-allowed; }

.btn-return-pill {
    padding: 10px 22px;
    border-radius: 50px;
    border: none;
    background: #f59e0b;
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s;
}
.btn-return-pill:hover:not(:disabled) { background: #d97706; }
.btn-return-pill:disabled { opacity: 0.6; cursor: not-allowed; }

.btn-endorse-pill {
    padding: 10px 22px;
    border-radius: 50px;
    border: none;
    background: #16a34a;
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s;
}
.btn-endorse-pill:hover:not(:disabled) { background: #15803d; }
.btn-endorse-pill:disabled { opacity: 0.6; cursor: not-allowed; }

/* Confirm endorse */
.confirm-endorse-icon-wrap {
    display: flex;
    justify-content: center;
    margin-top: 8px;
}

.confirm-endorse-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #16a34a, #15803d);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(22,163,74,0.3);
}

/* Success */
.success-body {
    align-items: center;
    text-align: center;
    padding: 28px 24px;
    gap: 12px;
}

.success-icon-circle {
    width: 72px;
    height: 72px;
    background: #f0fdf4;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #bbf7d0;
}

.success-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin: 4px 0 0;
}

.success-sub {
    font-size: 13.5px;
    color: #64748b;
    line-height: 1.6;
    max-width: 340px;
    margin: 0 0 8px;
}

.spin-icon { animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>
