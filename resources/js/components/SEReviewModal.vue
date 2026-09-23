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
            <div v-if="step === 'review'" class="review-body">
                <!-- Article Info -->
                <div class="review-article-info">
                    <div class="review-section-badge">{{ submissionData.section || 'News' }}</div>
                    <h3 class="review-article-title">{{ submissionData.title }}</h3>
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

                <!-- Word count if available -->
                <div class="review-stats-row" v-if="submissionData.wordCount">
                    <div class="review-stat-pill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        {{ submissionData.wordCount }} words
                    </div>
                </div>

                <p class="review-instruction">Choose what to do with this submission:</p>

                <!-- Action Buttons -->
                <div class="review-actions-grid">
                    <!-- Submit to Copyreader -->
                    <button class="review-action-btn endorse" @click="step = 'confirm-endorse'" :disabled="acting">
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

            <!-- Confirm Endorse step -->
            <div v-if="step === 'confirm-endorse'" class="review-body">
                <button class="back-btn" @click="step = 'review'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Back
                </button>
                <div class="confirm-endorse-icon-wrap">
                    <div class="confirm-endorse-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    </div>
                </div>
                <h3 class="review-step-heading" style="margin-top: 12px;">Submit to Copyreader?</h3>
                <p class="review-step-sub">
                    <strong>"{{ submissionData.title }}"</strong> will be sent to the Copyreader for editorial review.
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
import { ref, watch } from 'vue';

const props = defineProps({
    isOpen: { type: Boolean, default: false },
    submission: { type: Object, default: () => ({}) }
});

const emit = defineEmits(['close', 'reviewed']);

const step = ref('review'); // 'review' | 'return-notes' | 'confirm-endorse' | 'success'
const returnNotes = ref('');
const acting = ref(false);
const errorMsg = ref('');
const successTitle = ref('');
const successMessage = ref('');

const submissionData = ref({});

watch(() => [props.isOpen, props.submission], () => {
    if (props.isOpen && props.submission) {
        submissionData.value = {
            articleId: props.submission.id || props.submission.article_id,
            taskId: props.submission.taskId || props.submission.task_id || props.submission.raw?.tasks?.[0]?.id || null,
            title: props.submission.title,
            section: props.submission.section?.name || props.submission.section || 'News',
            authorName: props.submission.author?.name || props.submission.authorName || '—',
            submittedAt: props.submission.submitted_at || props.submission.raw?.submitted_at,
            wordCount: props.submission.word_count || props.submission.raw?.word_count,
        };
        step.value = 'review';
        returnNotes.value = '';
        errorMsg.value = '';
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

        // Also reset article status back to draft
        if (articleId) {
            await fetch(`/api/articles/${articleId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: 'draft' })
            }).catch(() => {});
        }

        if (!taskId && !success) throw new Error('Could not find task to return.');

        successTitle.value = 'Article Returned';
        successMessage.value = `"${submissionData.value.title}" has been returned to ${submissionData.value.authorName} with your revision notes. They will be notified.`;
        step.value = 'success';
        emit('reviewed', { action: 'returned' });
    } catch (e) {
        errorMsg.value = e.message || 'An error occurred.';
    } finally {
        acting.value = false;
    }
};

const confirmEndorse = async () => {
    acting.value = true;
    errorMsg.value = '';

    try {
        const token = localStorage.getItem('sparky_token');
        const articleId = submissionData.value.articleId;
        if (!articleId) throw new Error('Missing article ID.');

        const res = await fetch(`/api/articles/${articleId}/endorse`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ editor_notes: '' })
        });

        if (!res.ok) {
            const d = await res.json().catch(() => ({}));
            throw new Error(d.message || 'Failed to endorse article.');
        }

        // Also complete the linked writing task
        const taskId = submissionData.value.taskId;
        if (taskId) {
            await fetch(`/api/tasks/${taskId}/complete`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            }).catch(() => {});
        }

        successTitle.value = 'Submitted to Copyreader!';
        successMessage.value = `"${submissionData.value.title}" has been endorsed and sent to the Copyreader for editorial review. The writer has been notified.`;
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
    max-width: 480px;
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
