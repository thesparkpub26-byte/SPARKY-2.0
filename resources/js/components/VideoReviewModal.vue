<template>
    <div class="vr-overlay" v-if="isOpen" @click.self="handleClose">
        <div class="vr-card">

            <div class="vr-hdr">
                <h2 class="vr-title">Review Video</h2>
                <button class="vr-x-btn" @click="handleClose" title="Close" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <!-- Review step -->
            <div v-if="step === 'review'" class="vr-body">
                <div class="vr-scroll">
                    <span class="vr-badge">Radio Broadcasting</span>
                    <h3 class="vr-headline">{{ info.title || 'Untitled Video' }}</h3>
                    <div class="vr-meta">
                        <span>{{ info.authorName }}</span>
                        <span v-if="info.submittedAt">Submitted {{ formatDate(info.submittedAt) }}</span>
                    </div>

                    <div v-if="embedUrl" class="vr-player">
                        <iframe :src="embedUrl" title="Video preview" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <p v-else class="vr-empty">No valid YouTube link was submitted.</p>

                    <a v-if="info.videoUrl" :href="info.videoUrl" target="_blank" rel="noopener" class="vr-link">
                        {{ info.videoUrl }}
                    </a>

                    <h4 class="vr-label">Description</h4>
                    <p class="vr-description">{{ info.description || 'No description was provided.' }}</p>
                </div>

                <div class="vr-footer">
                    <p class="vr-instruction">This video will go to the Editor-in-Chief for final review.</p>
                    <button class="vr-send" @click="step = 'final'" :disabled="acting">
                        <span class="vr-send-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </span>
                        <span class="vr-send-text">
                            <span class="vr-send-label">Send to EIC</span>
                            <span class="vr-send-sub">Make final edits and credit the team</span>
                        </span>
                    </button>
                    <p v-if="errorMsg" class="vr-error">{{ errorMsg }}</p>
                </div>
            </div>

            <!-- Final edits & credits step -->
            <div v-if="step === 'final'" class="vr-body">
                <div class="vr-scroll">
                    <button class="vr-back" @click="step = 'review'" :disabled="acting">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Back
                    </button>
                    <h3 class="vr-step-title">Final edits</h3>

                    <div class="vr-field">
                        <label class="vr-field-label">Headline</label>
                        <input v-model="edit.title" class="vr-input" maxlength="255" placeholder="Video headline" />
                    </div>
                    <div class="vr-field">
                        <label class="vr-field-label">Description</label>
                        <textarea v-model="edit.description" class="vr-input vr-textarea" rows="4" maxlength="1000" placeholder="What is this video about?"></textarea>
                    </div>
                    <div class="vr-field">
                        <label class="vr-field-label">Link to YouTube Video</label>
                        <input v-model="edit.videoUrl" type="url" class="vr-input" placeholder="https://www.youtube.com/watch?v=..." />
                    </div>

                    <div class="vr-field">
                        <label class="vr-field-label">Section</label>
                        <SectionSelect v-model="edit.category" />
                    </div>

                    <h3 class="vr-step-title vr-credits-title">Credits</h3>
                    <p class="vr-step-text vr-credits-sub">Everyone you add can see this video in their own list. Each role can have more than one person.</p>

                    <div class="vr-credits">
                        <MultiUserSelect v-model="credits.reporter" label="Reporter" :options="broadcastTeam" placeholder="Select reporter/s" />
                        <MultiUserSelect v-model="credits.scriptwriter" label="Scriptwriter" :options="scriptwriterOptions" placeholder="Select scriptwriter/s" />
                        <MultiUserSelect v-model="credits.videographer" label="Videographer/s" :options="broadcastTeam" placeholder="Select videographer/s" />
                        <MultiUserSelect v-model="credits.video_editor" label="Video Editor/s" :options="broadcastTeam" placeholder="Select video editor/s" />
                    </div>
                    <p v-if="loadingUsers" class="vr-step-text">Loading the team…</p>
                    <p v-if="errorMsg" class="vr-error">{{ errorMsg }}</p>
                </div>
                <div class="vr-footer vr-footer-row">
                    <button class="vr-btn-grey" @click="step = 'review'" :disabled="acting">Cancel</button>
                    <button class="vr-btn-green" @click="goToConfirm" :disabled="acting">Continue</button>
                </div>
            </div>

            <!-- Confirm step -->
            <div v-if="step === 'confirm'" class="vr-body vr-pad">
                <h3 class="vr-step-title">Send to the Editor-in-Chief?</h3>
                <p class="vr-step-text"><strong>"{{ edit.title || info.title }}"</strong> will be sent to the Editor-in-Chief for review and publishing. The presenter will be notified that it was endorsed.</p>
                <p v-if="errorMsg" class="vr-error">{{ errorMsg }}</p>
                <div class="vr-step-actions">
                    <button class="vr-btn-grey" @click="step = 'final'" :disabled="acting">Back</button>
                    <button class="vr-btn-green" @click="confirmSend" :disabled="acting">{{ acting ? 'Sending...' : 'Yes, Send to EIC' }}</button>
                </div>
            </div>

            <!-- Success step -->
            <div v-if="step === 'success'" class="vr-body vr-pad vr-center">
                <div class="vr-success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <h3 class="vr-step-title">Sent to the EIC!</h3>
                <p class="vr-step-text">"{{ info.title }}" is now waiting in the Editor-in-Chief's Endorsements.</p>
                <button class="vr-btn-blue" @click="handleClose">Done</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { followUp } from '../utils/http';
import { ref, reactive, watch, computed } from 'vue';
import MultiUserSelect from './MultiUserSelect.vue';
import SectionSelect from './SectionSelect.vue';
import { youtubeEmbedUrl, youtubeThumbnail, extractYouTubeId } from '../utils/video';

const props = defineProps({
    isOpen: { type: Boolean, default: false },
    submission: { type: Object, default: () => ({}) }
});

const emit = defineEmits(['close', 'reviewed']);

const step = ref('review'); // 'review' | 'final' | 'confirm' | 'success'
const acting = ref(false);
const errorMsg = ref('');
const info = ref({});

const embedUrl = computed(() => youtubeEmbedUrl(info.value.videoUrl));

// Final edits + credits the Head makes before the video goes to the EIC
const edit = reactive({ title: '', description: '', videoUrl: '', category: '' });
const credits = reactive({ reporter: [], scriptwriter: [], videographer: [], video_editor: [] });
const allUsers = ref([]);
const loadingUsers = ref(false);

// Request headers with the sign-in token and a JSON content type.
const authHeaders = () => ({
    Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
    Accept: 'application/json',
    'Content-Type': 'application/json'
});

// The broadcasting team: every staff broadcaster plus the Head / Assistant Head Broadcaster
const isBroadcastTeam = (u) => u.role === 'staff_broadcaster'
    || (u.role === 'section_editor' && `${u.secondary_role || ''} ${u.tertiary_role || ''}`.toLowerCase().includes('broadcaster'));
// Sorts users by name.
const byName = (a, b) => (a.name || '').localeCompare(b.name || '');

const broadcastTeam = computed(() => allUsers.value.filter(isBroadcastTeam).sort(byName));
// Scriptwriters can also be staff writers, section editors or the EIC
const scriptwriterOptions = computed(() => allUsers.value
    .filter(u => isBroadcastTeam(u) || ['staff_writer', 'section_editor', 'eic'].includes(u.role))
    .sort(byName));

// Loads the users for the crew drop-downs (once).
const loadUsers = async () => {
    if (allUsers.value.length) return;
    loadingUsers.value = true;
    try {
        const res = await fetch('/api/users', { headers: authHeaders() });
        if (res.ok) {
            const data = await res.json();
            allUsers.value = (Array.isArray(data) ? data : (data.users || [])).filter(u => u.is_active !== false);
        }
    } catch (e) {
        console.warn('Could not load the broadcasting team:', e);
    } finally {
        loadingUsers.value = false;
    }
};

// Start from the saved credits; otherwise suggest the presenter and the assigned crew
const prefillCredits = async () => {
    credits.reporter = []; credits.scriptwriter = []; credits.videographer = []; credits.video_editor = [];
    try {
        const res = await fetch(`/api/articles/${info.value.articleId}`, { headers: authHeaders() });
        if (!res.ok) return;
        const art = await res.json();

        // The list item can be a bare task (no article data), so fill the video details in from the article
        if (!info.value.videoUrl && art.video_url) {
            info.value.videoUrl = art.video_url;
            edit.videoUrl = art.video_url;
        }
        if (!info.value.description && (art.excerpt || art.content)) {
            info.value.description = art.excerpt || art.content;
            edit.description = info.value.description;
        }
        if (art.title && (!info.value.title || info.value.title !== art.title)) {
            info.value.title = art.title;
            edit.title = art.title;
        }
        if (!edit.category && art.video_category) edit.category = art.video_category;

        if ((art.credits || []).length) {
            art.credits.forEach(c => { if (credits[c.role]) credits[c.role].push(c.user_id); });
            return;
        }
        if (art.author_id) credits.reporter = [art.author_id];
        (art.tasks || []).forEach(t => {
            if (t.type === 'videography' && t.assignee_id) credits.videographer.push(t.assignee_id);
            if (t.type === 'video_editing' && t.assignee_id) credits.video_editor.push(t.assignee_id);
        });
    } catch (e) {
        console.warn('Could not prefill credits:', e);
    }
};

watch(() => [props.isOpen, props.submission], () => {
    if (!props.isOpen || !props.submission) return;
    const s = props.submission;
    info.value = {
        articleId: s.id || s.article_id,
        taskId: s.taskId || null,
        title: s.title,
        authorName: s.author?.name || s.authorName || '—',
        submittedAt: s.submitted_at || s.raw?.submitted_at,
        videoUrl: s.video_url || s.raw?.video_url || '',
        description: s.excerpt || s.raw?.excerpt || s.content || s.raw?.content || '',
    };
    step.value = 'review';
    errorMsg.value = '';
    edit.title = info.value.title || '';
    edit.description = info.value.description || '';
    edit.videoUrl = info.value.videoUrl || '';
    edit.category = props.submission.video_category || props.submission.raw?.video_category || '';
    loadUsers();
    prefillCredits();
}, { immediate: true, deep: true });

// Formats a date and time for display.
const formatDate = (d) => {
    const date = new Date(d);
    if (isNaN(date.getTime())) return d;
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
        + ' • ' + date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
};

// Closes the review modal.
const handleClose = () => emit('close');

// Checks the video details, then moves to the confirm step.
const goToConfirm = () => {
    errorMsg.value = '';
    if (!edit.title.trim()) {
        errorMsg.value = 'The video needs a headline.';
        return;
    }
    if (!extractYouTubeId(edit.videoUrl)) {
        errorMsg.value = 'Please enter a valid YouTube link.';
        return;
    }
    if (!edit.category) {
        errorMsg.value = 'Please choose a section for this video.';
        return;
    }
    step.value = 'confirm';
};

// Saves the reviewed video and credits, endorses it to the EIC and completes the presenter's task.
const confirmSend = async () => {
    acting.value = true;
    errorMsg.value = '';
    try {
        const headers = authHeaders();
        const articleId = info.value.articleId;
        if (!articleId) throw new Error('Missing video ID.');

        // 1. Save the final edits
        const saveRes = await fetch(`/api/articles/${articleId}`, {
            method: 'PUT',
            headers,
            body: JSON.stringify({
                title: edit.title.trim(),
                excerpt: edit.description,
                content: edit.description,
                video_url: edit.videoUrl.trim(),
                video_category: edit.category,
                cover_image: youtubeThumbnail(edit.videoUrl)
            })
        });
        if (!saveRes.ok) {
            const data = await saveRes.json().catch(() => ({}));
            throw new Error(data.message || 'Could not save the final edits.');
        }

        // 2. Save who worked on it
        const creditRes = await fetch(`/api/articles/${articleId}/credits`, {
            method: 'PUT',
            headers,
            body: JSON.stringify({ credits: { ...credits } })
        });
        if (!creditRes.ok) {
            const data = await creditRes.json().catch(() => ({}));
            throw new Error(data.message || 'Could not save the credits.');
        }

        // 3. Send it on to the EIC

        const res = await fetch(`/api/articles/${articleId}/endorse`, {
            method: 'POST',
            headers,
            body: JSON.stringify({ editor_notes: '' })
        });
        if (!res.ok) {
            const data = await res.json().catch(() => ({}));
            throw new Error(data.message || 'Failed to send the video to the Editor-in-Chief.');
        }

        // The presenter's task is done once the video is endorsed
        let taskId = info.value.taskId;
        if (!taskId) {
            const artRes = await fetch(`/api/articles/${articleId}`, { headers });
            if (artRes.ok) {
                const art = await artRes.json();
                taskId = (art.tasks || []).find(t => t.type === 'writing')?.id || null;
            }
        }
        if (taskId) {
            // The video is already with the EIC; if the presenter's task can't be closed, say so in the log
            await followUp("The presenter's task could not be marked complete.", () => fetch(`/api/tasks/${taskId}/complete`, { method: 'POST', headers }));
        }

        step.value = 'success';
        emit('reviewed', { action: 'endorsed' });
    } catch (e) {
        errorMsg.value = e.message || 'An error occurred.';
        step.value = 'final';
    } finally {
        acting.value = false;
    }
};
</script>

<style scoped>
.vr-overlay {
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

.vr-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 560px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 25px 60px -12px rgba(0,0,0,0.3);
    animation: vr-pop 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    overflow: hidden;
}

@keyframes vr-pop {
    from { opacity: 0; transform: scale(0.95) translateY(12px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.vr-hdr { display: flex; align-items: center; justify-content: space-between; padding: 22px 24px 16px; border-bottom: 1px solid #f1f5f9; flex-shrink: 0; }
.vr-title { font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; }
.vr-x-btn { background: none; border: none; cursor: pointer; padding: 4px; border-radius: 8px; display: flex; }
.vr-x-btn:hover { background: #f1f5f9; }

.vr-body { display: flex; flex-direction: column; min-height: 0; flex: 1 1 auto; }
.vr-pad { padding: 24px; }
.vr-center { align-items: center; text-align: center; }
.vr-scroll { padding: 20px 24px 8px; overflow-y: auto; flex: 1 1 auto; min-height: 0; }

.vr-badge {
    display: inline-flex;
    padding: 3px 10px;
    background: #dbeafe;
    color: #1d4ed8;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.vr-headline { font-size: 19px; font-weight: 800; color: #0f172a; margin: 8px 0 6px; line-height: 1.3; }
.vr-meta { display: flex; gap: 14px; flex-wrap: wrap; font-size: 13px; color: #64748b; margin-bottom: 14px; }

.vr-player { position: relative; width: 100%; aspect-ratio: 16 / 9; border-radius: 16px; overflow: hidden; background: #0f172a; }
.vr-player iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
.vr-empty { padding: 20px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 14px; font-size: 13px; color: #94a3b8; margin: 0; text-align: center; }
.vr-link { display: block; margin-top: 8px; font-size: 12.5px; color: #1d6bf3; word-break: break-all; }
.vr-label { font-size: 13px; font-weight: 800; color: #0f172a; margin: 16px 0 6px; }
.vr-description { font-size: 13.5px; color: #475569; line-height: 1.7; margin: 0 0 12px; white-space: pre-wrap; }

.vr-footer { padding: 16px 24px 24px; border-top: 1px solid #f1f5f9; flex-shrink: 0; }
.vr-instruction { font-size: 13px; color: #64748b; margin: 0 0 12px; }

.vr-send {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px;
    border-radius: 16px;
    border: 2px solid #bfdbfe;
    background: #eff6ff;
    cursor: pointer;
    text-align: left;
    width: 100%;
    transition: all 0.15s;
    font-family: inherit;
}
.vr-send:hover:not(:disabled) { background: #dbeafe; border-color: #93c5fd; }
.vr-send:disabled { opacity: 0.5; cursor: not-allowed; }
.vr-send-icon { width: 44px; height: 44px; border-radius: 12px; background: #2563eb; color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.vr-send-text { display: flex; flex-direction: column; gap: 2px; }
.vr-send-label { font-size: 14px; font-weight: 700; color: #1e293b; }
.vr-send-sub { font-size: 12px; color: #64748b; }

.vr-error { color: #dc2626; font-size: 13px; margin: 10px 0 0; padding: 8px 12px; background: #fef2f2; border-radius: 8px; border: 1px solid #fecaca; }

.vr-back { display: inline-flex; align-items: center; gap: 4px; background: none; border: none; color: #64748b; font-size: 13px; font-weight: 600; cursor: pointer; padding: 0; margin-bottom: 12px; font-family: inherit; }
.vr-back:hover { color: #1e293b; }
.vr-field { display: flex; flex-direction: column; gap: 6px; margin-top: 12px; }
.vr-field-label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
.vr-input { width: 100%; box-sizing: border-box; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 10px 14px; font-size: 14px; color: #1e293b; font-family: inherit; outline: none; transition: border-color 0.15s; }
.vr-input:focus { border-color: #2563eb; }
.vr-textarea { resize: vertical; line-height: 1.6; }
.vr-credits-title { margin-top: 22px; }
.vr-credits-sub { margin-bottom: 4px; }
.vr-credits { display: flex; flex-direction: column; gap: 14px; margin-top: 12px; padding-bottom: 150px; }
.vr-footer-row { display: flex; justify-content: space-between; gap: 12px; }

.vr-step-title { font-size: 17px; font-weight: 700; color: #0f172a; margin: 0 0 8px; }
.vr-step-text { font-size: 13.5px; color: #475569; line-height: 1.6; margin: 0 0 4px; }
.vr-step-actions { display: flex; gap: 12px; justify-content: space-between; margin-top: 20px; }

.vr-btn-grey, .vr-btn-green, .vr-btn-blue {
    padding: 10px 22px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.15s;
}
.vr-btn-grey { border: 1.5px solid #e2e8f0; background: #f8fafc; color: #64748b; }
.vr-btn-grey:hover:not(:disabled) { background: #f1f5f9; }
.vr-btn-green { border: none; background: #16a34a; color: white; }
.vr-btn-green:hover:not(:disabled) { background: #15803d; }
.vr-btn-blue { border: none; background: #2563eb; color: white; width: 100%; margin-top: 12px; }
.vr-btn-blue:hover { background: #1d4ed8; }
.vr-btn-grey:disabled, .vr-btn-green:disabled { opacity: 0.6; cursor: not-allowed; }

.vr-success-icon { width: 72px; height: 72px; background: #f0fdf4; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #bbf7d0; margin-bottom: 12px; }
</style>
