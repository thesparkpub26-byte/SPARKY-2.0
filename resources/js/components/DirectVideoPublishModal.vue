<template>
    <div class="dv-overlay" v-if="isOpen" @click.self="closeModal">
        <div class="dv-card">

            <div class="dv-hdr">
                <h2 class="dv-title">Publish Automatic/Past Video</h2>
                <button class="dv-x-btn" type="button" @click="closeModal" title="Close" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <div class="dv-note">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                For urgent or past videos. It goes live right away, without the EIC's confirmation.
            </div>

            <div class="dv-grid">
                <!-- Left: the video itself -->
                <div class="dv-col">
                    <div class="dv-field">
                        <label class="dv-label" for="dv-headline">Headline</label>
                        <input id="dv-headline" v-model="headline" class="dv-input dv-headline" maxlength="255" placeholder="Enter the video's headline..." />
                    </div>

                    <div class="dv-field">
                        <label class="dv-label" for="dv-description">Description</label>
                        <div class="dv-textarea-wrap">
                            <textarea id="dv-description" v-model="description" class="dv-input dv-textarea" rows="5" maxlength="1000" placeholder="What is this video about?"></textarea>
                            <span class="dv-char-count">{{ description.length }}/1000</span>
                        </div>
                    </div>

                    <div class="dv-field">
                        <label class="dv-label" for="dv-link">Link to YouTube Video</label>
                        <input id="dv-link" v-model="videoUrl" type="url" class="dv-input" :class="{ invalid: videoUrl && !isValidLink }" placeholder="https://www.youtube.com/watch?v=..." />
                        <p v-if="videoUrl && !isValidLink" class="dv-hint error">This doesn't look like a YouTube link.</p>
                        <div v-if="isValidLink" class="dv-preview">
                            <iframe :src="embedUrl" title="Video preview" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>

                <!-- Right: category, date and credits -->
                <div class="dv-col">
                    <div class="dv-field">
                        <label class="dv-label">Category</label>
                        <SectionSelect v-model="category" placeholder="Select a category" />
                    </div>

                    <div class="dv-field">
                        <label class="dv-label" for="dv-date">Date Published</label>
                        <input id="dv-date" v-model="publishedAt" type="datetime-local" class="dv-input" :max="nowLocal" />
                        <p class="dv-hint">Set an earlier date for a past video. It is filed under that academic year.</p>
                    </div>

                    <MultiUserSelect v-model="credits.reporter" label="Reporter/s" :options="broadcastTeam" placeholder="Select reporter/s" />
                    <MultiUserSelect v-model="credits.scriptwriter" label="Scriptwriter" :options="scriptwriterOptions" placeholder="Select scriptwriter/s" />
                    <MultiUserSelect v-model="credits.videographer" label="Videographer/s" :options="broadcastTeam" placeholder="Select videographer/s" />
                    <MultiUserSelect v-model="credits.video_editor" label="Video Editor/s" :options="broadcastTeam" placeholder="Select video editor/s" />
                </div>
            </div>

            <p v-if="errorMessage" class="dv-error">{{ errorMessage }}</p>

            <div class="dv-footer">
                <button type="button" class="dv-btn-grey" @click="closeModal" :disabled="isPublishing">Cancel</button>
                <button type="button" class="dv-btn-blue" @click="openConfirm" :disabled="isPublishing">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path></svg>
                    Publish Video
                </button>
            </div>
        </div>

        <!-- Confirm -->
        <div class="dv-overlay dv-sub" v-if="isConfirmOpen" @click.self="isConfirmOpen = false">
            <div class="dv-sub-card">
                <h3 class="dv-sub-title">Publish right now?</h3>
                <p class="dv-sub-text">"{{ headline }}" will go live immediately{{ isPast ? `, dated ${formattedPublishedAt}` : '' }}. It skips the EIC's confirmation.</p>
                <p v-if="errorMessage" class="dv-error">{{ errorMessage }}</p>
                <div class="dv-sub-actions">
                    <button type="button" class="dv-btn-grey" @click="isConfirmOpen = false" :disabled="isPublishing">Cancel</button>
                    <button type="button" class="dv-btn-blue" @click="publish" :disabled="isPublishing">{{ isPublishing ? 'Publishing...' : 'Yes, Publish' }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue';
import MultiUserSelect from './MultiUserSelect.vue';
import SectionSelect from './SectionSelect.vue';
import { extractYouTubeId, youtubeEmbedUrl, youtubeThumbnail } from '../utils/video';

const props = defineProps({
    isOpen: { type: Boolean, default: false }
});

const emit = defineEmits(['close', 'published']);

const users = ref([]);
const headline = ref('');
const description = ref('');
const videoUrl = ref('');
const category = ref('');
const publishedAt = ref('');
const credits = reactive({ reporter: [], scriptwriter: [], videographer: [], video_editor: [] });

const isConfirmOpen = ref(false);
const isPublishing = ref(false);
const errorMessage = ref('');

const isValidLink = computed(() => Boolean(extractYouTubeId(videoUrl.value)));
const embedUrl = computed(() => youtubeEmbedUrl(videoUrl.value));

const authHeaders = () => ({
    Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
    Accept: 'application/json',
    'Content-Type': 'application/json'
});

// The broadcasting team: every staff broadcaster plus the Head / Assistant Head Broadcaster
const isBroadcastTeam = (u) => u.role === 'staff_broadcaster'
    || (u.role === 'section_editor' && `${u.secondary_role || ''} ${u.tertiary_role || ''}`.toLowerCase().includes('broadcaster'));
const byName = (a, b) => (a.name || '').localeCompare(b.name || '');
const broadcastTeam = computed(() => users.value.filter(isBroadcastTeam).sort(byName));
// Scriptwriters can also be staff writers, section editors or the EIC
const scriptwriterOptions = computed(() => users.value
    .filter(u => isBroadcastTeam(u) || ['staff_writer', 'section_editor', 'eic'].includes(u.role))
    .sort(byName));

const pad = (n) => String(n).padStart(2, '0');
const toLocalInput = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
const nowLocal = computed(() => toLocalInput(new Date()));
const isPast = computed(() => publishedAt.value && new Date(publishedAt.value).getTime() < Date.now() - 5 * 60 * 1000);
const formattedPublishedAt = computed(() => publishedAt.value
    ? new Date(publishedAt.value).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
    : '');

const loadUsers = async () => {
    if (users.value.length) return;
    try {
        const res = await fetch('/api/users', { headers: authHeaders() });
        if (res.ok) {
            const data = await res.json();
            users.value = (Array.isArray(data) ? data : (data.users || [])).filter(u => u.is_active !== false);
        }
    } catch (e) {
        console.warn('Could not load the team:', e);
    }
};

watch(() => props.isOpen, (open) => {
    if (!open) return;
    headline.value = '';
    description.value = '';
    videoUrl.value = '';
    category.value = '';
    publishedAt.value = toLocalInput(new Date());
    credits.reporter = []; credits.scriptwriter = []; credits.videographer = []; credits.video_editor = [];
    errorMessage.value = '';
    isConfirmOpen.value = false;
    loadUsers();
});

const openConfirm = () => {
    errorMessage.value = '';
    if (!headline.value.trim()) return (errorMessage.value = 'Please add a headline.');
    if (!description.value.trim()) return (errorMessage.value = 'Please add a description.');
    if (!isValidLink.value) return (errorMessage.value = 'Please add a valid YouTube link.');
    if (!category.value) return (errorMessage.value = 'Please choose a category.');
    if (!publishedAt.value) return (errorMessage.value = 'Please set the date published.');
    if (new Date(publishedAt.value).getTime() > Date.now() + 60 * 1000) return (errorMessage.value = 'The date published can\'t be in the future.');
    isConfirmOpen.value = true;
};

const publish = async () => {
    isPublishing.value = true;
    errorMessage.value = '';
    try {
        const res = await fetch('/api/articles/publish-direct-video', {
            method: 'POST',
            headers: authHeaders(),
            body: JSON.stringify({
                title: headline.value.trim(),
                excerpt: description.value,
                video_url: videoUrl.value.trim(),
                video_category: category.value,
                cover_image: youtubeThumbnail(videoUrl.value),
                credits: { ...credits },
                published_at: new Date(publishedAt.value).toISOString()
            })
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Could not publish the video.');
        isConfirmOpen.value = false;
        emit('published', data);
        emit('close');
    } catch (e) {
        errorMessage.value = e.message || 'Could not publish the video.';
    } finally {
        isPublishing.value = false;
    }
};

const closeModal = () => {
    if (isPublishing.value) return;
    emit('close');
};
</script>

<style scoped>
.dv-overlay {
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
.dv-overlay.dv-sub { z-index: 10000; }

.dv-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 980px;
    max-height: 92vh;
    padding: 32px 36px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow-y: auto;
    text-align: left;
    animation: dv-pop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes dv-pop {
    from { opacity: 0; transform: scale(0.97) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.dv-hdr { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.dv-title { color: #1d6bf3; font-size: 24px; font-weight: 800; margin: 0; letter-spacing: -0.5px; }
.dv-x-btn { background: transparent; border: none; cursor: pointer; padding: 6px; border-radius: 50%; display: flex; }
.dv-x-btn:hover { background: #f1f5f9; }

.dv-note {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    color: #92400e;
    border-radius: 14px;
    padding: 10px 14px;
    font-size: 12.5px;
    font-weight: 600;
    line-height: 1.5;
    margin-bottom: 20px;
}
.dv-note svg { flex-shrink: 0; margin-top: 2px; }

.dv-grid { display: grid; grid-template-columns: 1.3fr 1fr; gap: 28px; }
.dv-col { display: flex; flex-direction: column; gap: 18px; min-width: 0; }

.dv-field { display: flex; flex-direction: column; gap: 6px; }
.dv-label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
.dv-hint { margin: 0; font-size: 11.5px; color: #94a3b8; }
.dv-hint.error { color: #dc2626; }

.dv-input {
    width: 100%;
    box-sizing: border-box;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 14px;
    font-size: 14px;
    font-family: inherit;
    color: #0f172a;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.dv-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12); }
.dv-input.invalid { border-color: #ef4444; }
.dv-headline { font-size: 16px; font-weight: 700; }
.dv-textarea { resize: vertical; line-height: 1.6; }
.dv-textarea-wrap { position: relative; }
.dv-char-count { position: absolute; right: 14px; bottom: 10px; font-size: 11px; color: #94a3b8; }

.dv-preview { position: relative; width: 100%; aspect-ratio: 16 / 9; border-radius: 16px; overflow: hidden; background: #0f172a; margin-top: 4px; }
.dv-preview iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }

.dv-btn-blue, .dv-btn-grey {
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
.dv-btn-blue { background: #1d6bf3; color: #fff; border: none; padding: 12px 26px; box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3); }
.dv-btn-blue:hover:not(:disabled) { background: #1557b0; }
.dv-btn-grey { background: #f1f5f9; color: #475569; border: none; padding: 12px 22px; }
.dv-btn-grey:hover:not(:disabled) { background: #e2e8f0; }
.dv-btn-blue:disabled, .dv-btn-grey:disabled { opacity: 0.6; cursor: not-allowed; }

.dv-error { color: #dc2626; font-size: 13px; margin: 16px 0 0; padding: 8px 12px; background: #fef2f2; border-radius: 10px; border: 1px solid #fecaca; }
.dv-footer { display: flex; justify-content: space-between; gap: 12px; margin-top: 22px; padding-bottom: 130px; }

.dv-sub-card { background: #fff; border-radius: 28px; width: 100%; max-width: 440px; padding: 30px; box-sizing: border-box; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); }
.dv-sub-title { margin: 0 0 8px; font-size: 18px; font-weight: 800; color: #0f172a; }
.dv-sub-text { margin: 0 0 20px; font-size: 13.5px; color: #64748b; line-height: 1.6; }
.dv-sub-actions { display: flex; justify-content: space-between; gap: 12px; }

@media (max-width: 860px) {
    .dv-grid { grid-template-columns: 1fr; }
    .dv-card { padding: 24px 20px; }
}
</style>
