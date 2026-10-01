<template>
    <div class="vw-overlay" v-if="isOpen" @click.self="closeModal">
        <div class="vw-card">

            <!-- Header -->
            <div class="vw-hdr">
                <div class="vw-hdr-left">
                    <h2 class="vw-title">Assignment Workspace</h2>
                    <span v-if="saveFeedback" class="vw-feedback" :class="{ error: saveFeedbackIsError }">{{ saveFeedback }}</span>
                </div>
                <button class="vw-x-btn" @click="closeModal" title="Close" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <span class="vw-section-badge">Radio Broadcasting</span>
            <h1 class="vw-task-title">{{ task.title || 'Untitled Assignment' }}</h1>

            <!-- Revision notes from the reviewer -->
            <div v-if="revisionNotes && ['returned', 'rejected'].includes(task.status)" class="vw-revision-box">
                <strong>{{ returnedByLabel }} sent this back:</strong>
                <p>{{ revisionNotes }}</p>
            </div>

            <!-- Already submitted: read-only until a reviewer sends it back -->
            <div v-if="isLocked" class="vw-locked-box">
                This video has already been submitted, so it can't be edited or submitted again unless it is returned to you.
            </div>

            <div class="vw-body">
                <div class="vw-field">
                    <label class="vw-label" for="vw-headline">Headline</label>
                    <input id="vw-headline" v-model="headline" :readonly="isLocked" type="text" class="vw-input vw-input-headline" placeholder="Enter a headline for your video..." maxlength="255" />
                </div>

                <div class="vw-field">
                    <label class="vw-label" for="vw-description">Description</label>
                    <div class="vw-textarea-wrap">
                        <textarea id="vw-description" v-model="description" :readonly="isLocked" class="vw-textarea" rows="6" maxlength="1000" placeholder="Describe what this video is about..."></textarea>
                        <span class="vw-char-count">{{ description.length }}/1000</span>
                    </div>
                </div>

                <div class="vw-field">
                    <label class="vw-label" for="vw-link">Link to YouTube Video</label>
                    <input id="vw-link" v-model="videoUrl" :readonly="isLocked" type="url" class="vw-input" :class="{ invalid: videoUrl && !isValidLink }" placeholder="https://www.youtube.com/watch?v=..." />
                    <p v-if="videoUrl && !isValidLink" class="vw-hint error">This doesn't look like a YouTube link.</p>
                    <div v-if="isValidLink" class="vw-preview">
                        <iframe :src="embedUrl" title="Video preview" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="vw-footer">
                <span class="vw-status-hint" v-if="task.status">Status: <strong>{{ String(task.status).replace('_', ' ') }}</strong></span>
                <div class="vw-actions">
                    <button type="button" class="vw-btn-outline" @click="saveAsDraft" :disabled="isBusy || isLocked">Save as Draft</button>
                    <button type="button" class="vw-btn-blue" @click="openSubmitConfirm" :disabled="isBusy || isLocked">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        Submit for Review
                    </button>
                </div>
            </div>
        </div>

        <!-- Submit confirmation -->
        <div class="vw-overlay vw-sub" v-if="isSubmitConfirmOpen" @click.self="isSubmitConfirmOpen = false">
            <div class="vw-sub-card">
                <h3 class="vw-sub-title">Submit for review?</h3>
                <p class="vw-sub-text">"{{ headline || task.title }}" will be sent to the Head Broadcaster or Assistant Head Broadcaster for review, then on to the Editor-in-Chief.</p>
                <p v-if="errorMessage" class="vw-hint error">{{ errorMessage }}</p>
                <div class="vw-sub-actions">
                    <button type="button" class="vw-btn-grey" @click="isSubmitConfirmOpen = false" :disabled="isSubmitting">Cancel</button>
                    <button type="button" class="vw-btn-blue" @click="confirmSubmit" :disabled="isSubmitting">{{ isSubmitting ? 'Submitting...' : 'Yes, Submit' }}</button>
                </div>
            </div>
        </div>

        <!-- Success -->
        <div class="vw-overlay vw-sub" v-if="isSuccessOpen">
            <div class="vw-sub-card vw-center">
                <div class="vw-success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <h3 class="vw-sub-title">Video Submitted</h3>
                <p class="vw-sub-text">Your video has been sent for review. You'll be notified if any revisions are needed.</p>
                <button type="button" class="vw-btn-blue vw-full" @click="finishSuccess">Done</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { extractYouTubeId, youtubeEmbedUrl, youtubeThumbnail, parseNotesField } from '../utils/video';

const props = defineProps({
    isOpen: { type: Boolean, default: false },
    taskData: { type: Object, default: () => ({}) }
});

const emit = defineEmits(['close', 'task-submitted', 'task-saved-as-draft']);

const task = computed(() => props.taskData || {});

const headline = ref('');
const description = ref('');
const videoUrl = ref('');
const saveFeedback = ref('');
const saveFeedbackIsError = ref(false);
const isBusy = ref(false);
const isSubmitting = ref(false);
const isSubmitConfirmOpen = ref(false);
const isSuccessOpen = ref(false);
const errorMessage = ref('');
const videoSectionId = ref(null);
const linkedArticleId = ref(null);

// Editable only while it's still the presenter's to work on
const EDITABLE_STATUSES = ['', 'pending', 'draft', 'in_progress', 'returned', 'rejected'];
const isLocked = computed(() => !EDITABLE_STATUSES.includes(String(task.value.status || '').toLowerCase()));

const isValidLink = computed(() => Boolean(extractYouTubeId(videoUrl.value)));
const embedUrl = computed(() => youtubeEmbedUrl(videoUrl.value));

const revisionNotes = computed(() => parseNotesField(task.value.notes || task.value.raw?.notes, 'Revision Notes'));
const returnedByLabel = computed(() => {
    const role = task.value.returned_by_role || task.value.raw?.returned_by_role;
    if (role === 'eic') return 'The Editor-in-Chief';
    if (role === 'section_editor') return 'Your editor';
    return 'A reviewer';
});

// Request headers with the sign-in token (and a JSON content type unless told otherwise).
const authHeaders = (json = true) => {
    const token = localStorage.getItem('sparky_token');
    const headers = { Authorization: `Bearer ${token}`, Accept: 'application/json' };
    if (json) headers['Content-Type'] = 'application/json';
    return headers;
};

// Finds the id of the video section (once).
const loadVideoSection = async () => {
    if (videoSectionId.value) return;
    try {
        const res = await fetch('/api/sections', { headers: authHeaders(false) });
        if (res.ok) {
            const sections = await res.json();
            videoSectionId.value = (sections.find(s => String(s.name).toLowerCase() === 'video') || {}).id || null;
        }
    } catch {
        // The article can still be saved without a section.
    }
};

// Shows a message (or an error) for a few seconds.
const showFeedback = (message, isError = false, ms = 3000) => {
    saveFeedback.value = message;
    saveFeedbackIsError.value = isError;
    setTimeout(() => { saveFeedback.value = ''; }, ms);
};

// Prefill from the saved article (if the presenter already started) or the task itself
watch(() => [props.isOpen, props.taskData], async () => {
    if (!props.isOpen) return;

    isSubmitConfirmOpen.value = false;
    isSuccessOpen.value = false;
    errorMessage.value = '';
    saveFeedback.value = '';
    headline.value = task.value.title || '';
    description.value = '';
    videoUrl.value = '';
    linkedArticleId.value = task.value.article_id || task.value.raw?.article_id || task.value.article?.id || null;

    loadVideoSection();

    if (linkedArticleId.value) {
        try {
            const res = await fetch(`/api/articles/${linkedArticleId.value}`, { headers: authHeaders(false) });
            if (res.ok) {
                const article = await res.json();
                headline.value = article.title || headline.value;
                description.value = article.excerpt || '';
                videoUrl.value = article.video_url || '';
            }
        } catch {
            // Fall back to the blank form.
        }
    }
}, { immediate: true });

// Crew tasks are created before the article exists; attach them once it does so the
// article knows its videographer and video editor.
const linkCrewTasks = async (articleId) => {
    try {
        const res = await fetch('/api/tasks', { headers: authHeaders(false) });
        if (!res.ok) return;
        const all = await res.json();
        const clean = (t) => String(t || '').replace(/\s*\([^)]*(visuals|video|graphics|photo|illustration|pj)[^)]*\)/i, '').trim().toLowerCase();
        const base = clean(task.value.title);
        if (!base) return;

        const siblings = all.filter(t =>
            ['videography', 'video_editing', 'layout'].includes(t.type)
            && !t.article_id
            && clean(t.title) === base
        );
        await Promise.all(siblings.map(t => fetch(`/api/tasks/${t.id}`, {
            method: 'PUT',
            headers: authHeaders(),
            body: JSON.stringify({ article_id: articleId })
        })));
    } catch (e) {
        console.warn('Could not link video crew tasks:', e);
    }
};

// Creates or updates the video article behind this task and returns its id
const persistArticle = async () => {
    const payload = {
        title: headline.value.trim() || task.value.title,
        excerpt: description.value,
        content: description.value,
        type: 'video',
        video_url: videoUrl.value.trim() || null,
        cover_image: youtubeThumbnail(videoUrl.value) || null,
        section_id: videoSectionId.value,
        word_count: 0
    };

    let articleId = linkedArticleId.value;
    if (articleId) {
        const res = await fetch(`/api/articles/${articleId}`, { method: 'PUT', headers: authHeaders(), body: JSON.stringify(payload) });
        if (!res.ok) {
            const body = await res.json().catch(() => ({}));
            throw new Error(body.message || `Server error ${res.status}`);
        }
    } else {
        const res = await fetch('/api/articles', { method: 'POST', headers: authHeaders(), body: JSON.stringify(payload) });
        if (!res.ok) {
            const body = await res.json().catch(() => ({}));
            throw new Error(body.message || `Server error ${res.status}`);
        }
        articleId = (await res.json()).id;
        linkedArticleId.value = articleId;

        if (task.value.id) {
            await fetch(`/api/tasks/${task.value.id}`, { method: 'PUT', headers: authHeaders(), body: JSON.stringify({ article_id: articleId }) });
        }
    }

    await linkCrewTasks(articleId);
    return articleId;
};

// Saves the video as a draft (a valid YouTube link is needed if one is entered).
const saveAsDraft = async () => {
    if (videoUrl.value && !isValidLink.value) {
        showFeedback('✗ Enter a valid YouTube link', true, 4000);
        return;
    }
    isBusy.value = true;
    try {
        const articleId = await persistArticle();
        if (task.value.id) {
            await fetch(`/api/tasks/${task.value.id}`, { method: 'PUT', headers: authHeaders(), body: JSON.stringify({ status: 'in_progress' }) });
        }
        emit('task-saved-as-draft', { ...task.value, title: headline.value || task.value.title, status: 'in_progress', article_id: articleId });
        closeModal();
    } catch (e) {
        console.error('Error saving video draft:', e);
        showFeedback(`✗ Save failed: ${e.message}`, true, 4000);
    } finally {
        isBusy.value = false;
    }
};

// Checks that the video is complete, then opens the submit confirmation.
const openSubmitConfirm = () => {
    errorMessage.value = '';
    if (!headline.value.trim()) {
        showFeedback('✗ Add a headline first', true, 4000);
        return;
    }
    if (!description.value.trim()) {
        showFeedback('✗ Add a description first', true, 4000);
        return;
    }
    if (!videoUrl.value.trim() || !isValidLink.value) {
        showFeedback('✗ Add a valid YouTube link first', true, 4000);
        return;
    }
    isSubmitConfirmOpen.value = true;
};

// Saves the video and submits it for review.
const confirmSubmit = async () => {
    isSubmitting.value = true;
    errorMessage.value = '';
    try {
        const articleId = await persistArticle();

        if (task.value.id) {
            const res = await fetch(`/api/tasks/${task.value.id}/submit`, {
                method: 'POST',
                headers: authHeaders(),
                body: JSON.stringify({ notes: task.value.notes || undefined })
            });
            if (!res.ok) throw new Error('Could not submit the task.');
        }

        const submitRes = await fetch(`/api/articles/${articleId}/submit`, { method: 'POST', headers: authHeaders() });
        if (!submitRes.ok) throw new Error('Could not submit the video for review.');

        isSubmitConfirmOpen.value = false;
        isSuccessOpen.value = true;
        emit('task-submitted', { ...task.value, title: headline.value || task.value.title, status: 'submitted', article_id: articleId });
    } catch (e) {
        console.error('Error submitting video:', e);
        errorMessage.value = e.message || 'Something went wrong. Please try again.';
    } finally {
        isSubmitting.value = false;
    }
};

// Closes the success dialog and the workspace.
const finishSuccess = () => {
    isSuccessOpen.value = false;
    closeModal();
};

// Closes the workspace.
const closeModal = () => emit('close');
</script>

<style scoped>
.vw-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(6px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    box-sizing: border-box;
    font-family: 'Manrope', sans-serif;
}

.vw-overlay.vw-sub { z-index: 10000; }

.vw-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 760px;
    max-height: 90vh;
    padding: 32px 36px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow-y: auto;
    text-align: left;
    animation: vw-pop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes vw-pop {
    from { opacity: 0; transform: scale(0.97) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.vw-hdr { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
.vw-hdr-left { display: flex; align-items: center; gap: 14px; }
.vw-title { color: #1d6bf3; font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -0.5px; }

.vw-feedback {
    background: #dcfce7;
    color: #15803d;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
}
.vw-feedback.error { background: #fee2e2; color: #b91c1c; }

.vw-x-btn { background: transparent; border: none; cursor: pointer; padding: 6px; border-radius: 50%; display: flex; }
.vw-x-btn:hover { background: #f1f5f9; }

.vw-section-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
    margin-bottom: 8px;
}

.vw-task-title { font-size: 24px; font-weight: 800; color: #0f172a; margin: 0 0 18px; line-height: 1.3; letter-spacing: -0.4px; }

.vw-revision-box {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 14px;
    padding: 12px 16px;
    margin-bottom: 18px;
    font-size: 13px;
    color: #92400e;
}
.vw-revision-box p { margin: 4px 0 0; color: #78350f; white-space: pre-wrap; }

.vw-locked-box {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 14px;
    padding: 12px 16px;
    margin-bottom: 18px;
    font-size: 13px;
    color: #1e40af;
}

.vw-input[readonly], .vw-textarea[readonly] { background: #f8fafc; color: #64748b; cursor: default; }

.vw-body { display: flex; flex-direction: column; gap: 20px; }
.vw-field { display: flex; flex-direction: column; gap: 6px; }
.vw-label { font-size: 13px; font-weight: 700; color: #475569; }

.vw-input, .vw-textarea {
    width: 100%;
    padding: 13px 18px;
    border: 1.5px solid #cbd5e1;
    border-radius: 16px;
    font-size: 14px;
    color: #0f172a;
    box-sizing: border-box;
    font-family: inherit;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.vw-input-headline { font-size: 16px; font-weight: 700; }
.vw-textarea { resize: vertical; line-height: 1.6; }
.vw-input:focus, .vw-textarea:focus { border-color: #1d6bf3; box-shadow: 0 0 0 3px rgba(29, 107, 243, 0.15); }
.vw-input.invalid { border-color: #ef4444; }

.vw-textarea-wrap { position: relative; }
.vw-char-count { position: absolute; right: 14px; bottom: 10px; font-size: 11px; color: #94a3b8; }

.vw-hint { font-size: 12px; margin: 0; color: #64748b; }
.vw-hint.error { color: #dc2626; }

.vw-preview { position: relative; width: 100%; aspect-ratio: 16 / 9; border-radius: 16px; overflow: hidden; background: #0f172a; margin-top: 4px; }
.vw-preview iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }

.vw-footer { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 24px; }
.vw-status-hint { font-size: 13px; color: #64748b; text-transform: capitalize; }
.vw-actions { display: flex; align-items: center; gap: 12px; margin-left: auto; }

.vw-btn-blue, .vw-btn-outline, .vw-btn-grey {
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    font-family: inherit;
    transition: all 0.2s;
}
.vw-btn-blue { background: #1d6bf3; color: #ffffff; border: none; padding: 12px 24px; box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3); }
.vw-btn-blue:hover:not(:disabled) { background: #1557b0; }
.vw-btn-outline { background: transparent; color: #1d6bf3; border: 1.5px solid #1d6bf3; padding: 11px 22px; }
.vw-btn-outline:hover:not(:disabled) { background: #eff6ff; }
.vw-btn-grey { background: #f1f5f9; color: #475569; border: none; padding: 12px 22px; }
.vw-btn-grey:hover:not(:disabled) { background: #e2e8f0; }
.vw-btn-blue:disabled, .vw-btn-outline:disabled, .vw-btn-grey:disabled { opacity: 0.6; cursor: not-allowed; }
.vw-full { width: 100%; }

.vw-sub-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 420px;
    padding: 30px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
    animation: vw-pop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.vw-sub-card.vw-center { text-align: center; }
.vw-sub-title { margin: 0 0 8px; font-size: 18px; font-weight: 800; color: #0f172a; }
.vw-sub-text { margin: 0 0 20px; font-size: 13.5px; color: #64748b; line-height: 1.6; }
.vw-sub-actions { display: flex; justify-content: space-between; gap: 12px; }
.vw-success-icon {
    width: 64px; height: 64px; margin: 0 auto 12px; border-radius: 50%;
    background: #f0fdf4; border: 2px solid #bbf7d0;
    display: flex; align-items: center; justify-content: center;
}

@media (max-width: 640px) {
    .vw-card { padding: 24px 20px; }
}
</style>
