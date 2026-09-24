<template>
    <div class="dp-overlay" v-if="isOpen" @click.self="closeModal">
        <div class="dp-card">

            <div class="dp-hdr">
                <div class="dp-hdr-left">
                    <h2 class="dp-title">Publish Automatic/Past Article</h2>
                    <span v-if="feedback" class="dp-feedback">{{ feedback }}</span>
                </div>
                <button class="dp-x-btn" type="button" @click="closeModal" title="Close" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <div class="dp-note">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                For urgent or past articles. It goes live right away, without the EIC's or the copyreader's confirmation.
            </div>

            <div class="dp-grid">
                <!-- Left: the article itself -->
                <div class="dp-col">
                    <div class="dp-field">
                        <label class="dp-label">Section</label>
                        <SectionSelect v-model="sectionName" :options="sectionNames" placeholder="Select a section" />
                    </div>

                    <div class="dp-field">
                        <label class="dp-label" for="dp-headline">Article Headline</label>
                        <input id="dp-headline" v-model="headline" class="dp-input dp-headline" maxlength="500" placeholder="Enter the article's headline..." />
                    </div>

                    <div class="dp-field">
                        <label class="dp-label">Article Content</label>
                        <div class="dp-editor">
                            <div class="dp-toolbar">
                                <button type="button" class="dp-tool" title="Bold" @click="formatDoc('bold')"><b>B</b></button>
                                <button type="button" class="dp-tool" title="Italic" @click="formatDoc('italic')"><i>I</i></button>
                                <button type="button" class="dp-tool" title="Underline" @click="formatDoc('underline')"><u>U</u></button>
                                <span class="dp-tool-divider"></span>
                                <button type="button" class="dp-tool" title="Heading" @click="formatDoc('formatBlock', 'h2')">H</button>
                                <button type="button" class="dp-tool" title="Paragraph" @click="formatDoc('formatBlock', 'p')">¶</button>
                                <button type="button" class="dp-tool" title="Bulleted list" @click="formatDoc('insertUnorderedList')">•</button>
                                <span class="dp-tool-divider"></span>
                                <button type="button" class="dp-tool" title="Undo" @click="formatDoc('undo')">↶</button>
                                <button type="button" class="dp-tool" title="Redo" @click="formatDoc('redo')">↷</button>
                            </div>
                            <div ref="editorRef" class="dp-editor-body" contenteditable="true" @input="handleEditorInput" data-placeholder="Write or paste the article here..."></div>
                            <div class="dp-editor-foot">{{ wordCount }} {{ wordCount === 1 ? 'word' : 'words' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Right: who made it, when, and the visuals -->
                <div class="dp-col">
                    <MultiUserSelect v-model="writerIds" single label="Writer" :options="writerOptions" placeholder="Choose the writer" />

                    <div class="dp-field">
                        <div class="dp-label-row">
                            <label class="dp-label">PJ/Artist</label>
                            <label class="dp-check"><input type="checkbox" v-model="noArtist" /> No PJ/Artist</label>
                        </div>
                        <MultiUserSelect v-if="!noArtist" v-model="artistIds" single label="" :options="artistOptions" placeholder="Choose the PJ/Artist" class="dp-no-label" />
                        <div v-else class="dp-none-pill">No PJ/Artist credited</div>
                    </div>

                    <div class="dp-field">
                        <label class="dp-label" for="dp-date">Date Published</label>
                        <input id="dp-date" v-model="publishedAt" type="datetime-local" class="dp-input" :max="nowLocal" />
                        <p class="dp-hint">Set an earlier date for a past article. It is filed under that academic year.</p>
                    </div>

                    <div class="dp-field">
                        <label class="dp-label">Thumbnail</label>
                        <div class="dp-upload" :class="{ has: thumbnail }">
                            <img v-if="thumbnail" :src="thumbnail" alt="Thumbnail preview" class="dp-thumb" />
                            <div v-else class="dp-upload-empty">No thumbnail yet</div>
                        </div>
                        <input ref="thumbInput" type="file" accept="image/jpeg,image/png,image/webp,image/gif" hidden @change="uploadThumbnail" />
                        <div class="dp-upload-actions">
                            <button type="button" class="dp-btn-outline" :disabled="uploading" @click="thumbInput?.click()">{{ thumbnail ? 'Replace thumbnail' : 'Upload thumbnail' }}</button>
                            <button v-if="thumbnail" type="button" class="dp-link-btn" @click="thumbnail = ''">Remove</button>
                        </div>
                    </div>

                    <div class="dp-field">
                        <label class="dp-label">Media Uploads <span class="dp-hint-inline">(up to 3)</span></label>
                        <div v-if="mediaFiles.length" class="dp-media-row">
                            <div v-for="(url, idx) in mediaFiles" :key="url" class="dp-media-item">
                                <img :src="url" alt="Media" />
                                <button type="button" class="dp-media-x" :aria-label="`Remove media ${idx + 1}`" @click="mediaFiles.splice(idx, 1)">&times;</button>
                            </div>
                        </div>
                        <input ref="mediaInput" type="file" accept="image/jpeg,image/png,image/webp,image/gif" multiple hidden @change="uploadMedia" />
                        <button type="button" class="dp-btn-outline" :disabled="uploading || mediaFiles.length >= 3" @click="mediaInput?.click()">Add media</button>
                    </div>
                </div>
            </div>

            <p v-if="errorMessage" class="dp-error">{{ errorMessage }}</p>

            <div class="dp-footer">
                <button type="button" class="dp-btn-grey" @click="closeModal" :disabled="isPublishing">Cancel</button>
                <button type="button" class="dp-btn-blue" @click="openConfirm" :disabled="isPublishing || uploading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path></svg>
                    Publish Article
                </button>
            </div>
        </div>

        <!-- Confirm -->
        <div class="dp-overlay dp-sub" v-if="isConfirmOpen" @click.self="isConfirmOpen = false">
            <div class="dp-sub-card">
                <h3 class="dp-sub-title">Publish right now?</h3>
                <p class="dp-sub-text">"{{ headline }}" will go live immediately{{ isPast ? `, dated ${formattedPublishedAt}` : '' }}. It skips the EIC and copyreader confirmation.</p>
                <p v-if="errorMessage" class="dp-error">{{ errorMessage }}</p>
                <div class="dp-sub-actions">
                    <button type="button" class="dp-btn-grey" @click="isConfirmOpen = false" :disabled="isPublishing">Cancel</button>
                    <button type="button" class="dp-btn-blue" @click="publish" :disabled="isPublishing">{{ isPublishing ? 'Publishing...' : 'Yes, Publish' }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, nextTick } from 'vue';
import MultiUserSelect from './MultiUserSelect.vue';
import SectionSelect from './SectionSelect.vue';

const props = defineProps({
    isOpen: { type: Boolean, default: false }
});

const emit = defineEmits(['close', 'published']);

const sections = ref([]);
const users = ref([]);

const sectionName = ref('');
const headline = ref('');
const content = ref('');
const writerIds = ref([]);
const artistIds = ref([]);
const noArtist = ref(false);
const publishedAt = ref('');
const thumbnail = ref('');
const mediaFiles = ref([]);

const editorRef = ref(null);
const thumbInput = ref(null);
const mediaInput = ref(null);
const uploading = ref(false);
const isConfirmOpen = ref(false);
const isPublishing = ref(false);
const errorMessage = ref('');
const feedback = ref('');

const authHeaders = (json = true) => {
    const headers = { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json' };
    if (json) headers['Content-Type'] = 'application/json';
    return headers;
};

// The sections table still holds stale duplicates (two "News", "Features" vs "Feature", ...),
// so only the real print sections are offered, once each. Video is its own workflow.
const PRINT_SECTIONS = ['News', 'Opinion', 'Editorial', 'Feature', 'Sci-Tech', 'DevCom', 'Sports', 'Literary'];
const sectionNames = computed(() => PRINT_SECTIONS.filter(name => sections.value.some(s => s.name === name)));

const byName = (a, b) => (a.name || '').localeCompare(b.name || '');
const writerOptions = computed(() => users.value.filter(u => ['staff_writer', 'section_editor', 'eic'].includes(u.role)).sort(byName));
const artistOptions = computed(() => users.value.filter(u => u.role === 'staff_artist').sort(byName));

const pad = (n) => String(n).padStart(2, '0');
const toLocalInput = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
const nowLocal = computed(() => toLocalInput(new Date()));
const isPast = computed(() => publishedAt.value && new Date(publishedAt.value).getTime() < Date.now() - 5 * 60 * 1000);
const formattedPublishedAt = computed(() => publishedAt.value
    ? new Date(publishedAt.value).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
    : '');

const wordCount = computed(() => {
    const plain = content.value.replace(/<[^>]*>/g, ' ').replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
    return plain ? plain.split(' ').length : 0;
});

const formatDoc = (command, value = null) => {
    editorRef.value?.focus();
    document.execCommand(command, false, value);
    handleEditorInput();
};
const handleEditorInput = () => { content.value = editorRef.value?.innerHTML || ''; };

const loadLookups = async () => {
    try {
        const [secRes, userRes] = await Promise.all([
            sections.value.length ? null : fetch('/api/sections', { headers: authHeaders(false) }),
            users.value.length ? null : fetch('/api/users', { headers: authHeaders(false) }),
        ]);
        if (secRes?.ok) sections.value = await secRes.json();
        if (userRes?.ok) {
            const data = await userRes.json();
            users.value = (Array.isArray(data) ? data : (data.users || [])).filter(u => u.is_active !== false);
        }
    } catch (e) {
        console.warn('Could not load sections and people:', e);
    }
};

watch(() => props.isOpen, async (open) => {
    if (!open) return;
    sectionName.value = '';
    headline.value = '';
    content.value = '';
    writerIds.value = [];
    artistIds.value = [];
    noArtist.value = false;
    publishedAt.value = toLocalInput(new Date());
    thumbnail.value = '';
    mediaFiles.value = [];
    errorMessage.value = '';
    feedback.value = '';
    isConfirmOpen.value = false;
    loadLookups();
    await nextTick();
    if (editorRef.value) editorRef.value.innerHTML = '';
});

const uploadFiles = async (files) => {
    const body = new FormData();
    files.forEach(file => body.append('files[]', file));
    const res = await fetch('/api/articles/upload-media', { method: 'POST', headers: authHeaders(false), body });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Upload failed.');
    return data.urls || [];
};

const flash = (message) => {
    feedback.value = message;
    setTimeout(() => { feedback.value = ''; }, 3000);
};

const uploadThumbnail = async (event) => {
    const file = event.target.files?.[0];
    event.target.value = '';
    if (!file) return;
    uploading.value = true;
    errorMessage.value = '';
    try {
        const [url] = await uploadFiles([file]);
        if (url) thumbnail.value = url;
        flash('✓ Thumbnail uploaded');
    } catch (e) {
        errorMessage.value = e.message;
    } finally {
        uploading.value = false;
    }
};

const uploadMedia = async (event) => {
    const files = [...(event.target.files || [])].slice(0, 3 - mediaFiles.value.length);
    event.target.value = '';
    if (!files.length) return;
    uploading.value = true;
    errorMessage.value = '';
    try {
        mediaFiles.value.push(...await uploadFiles(files));
        flash('✓ Media uploaded');
    } catch (e) {
        errorMessage.value = e.message;
    } finally {
        uploading.value = false;
    }
};

const openConfirm = () => {
    errorMessage.value = '';
    if (!sectionName.value) return (errorMessage.value = 'Please choose a section.');
    if (!headline.value.trim()) return (errorMessage.value = 'Please add a headline.');
    if (wordCount.value === 0) return (errorMessage.value = 'Please write the article content.');
    if (!writerIds.value.length) return (errorMessage.value = 'Please choose the writer.');
    if (!noArtist.value && !artistIds.value.length) return (errorMessage.value = 'Please choose the PJ/Artist, or tick "No PJ/Artist".');
    if (!publishedAt.value) return (errorMessage.value = 'Please set the date published.');
    if (new Date(publishedAt.value).getTime() > Date.now() + 60 * 1000) return (errorMessage.value = 'The date published can\'t be in the future.');
    isConfirmOpen.value = true;
};

const publish = async () => {
    isPublishing.value = true;
    errorMessage.value = '';
    try {
        const section = sections.value.find(s => s.name === sectionName.value);
        const res = await fetch('/api/articles/publish-direct', {
            method: 'POST',
            headers: authHeaders(),
            body: JSON.stringify({
                title: headline.value.trim(),
                content: content.value,
                section_id: section?.id,
                author_id: writerIds.value[0],
                artist_id: noArtist.value ? null : artistIds.value[0],
                cover_image: thumbnail.value || null,
                media_files: mediaFiles.value,
                published_at: new Date(publishedAt.value).toISOString()
            })
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Could not publish the article.');
        isConfirmOpen.value = false;
        emit('published', data);
        emit('close');
    } catch (e) {
        errorMessage.value = e.message || 'Could not publish the article.';
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
.dp-overlay {
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
.dp-overlay.dp-sub { z-index: 10000; }

.dp-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 1040px;
    max-height: 92vh;
    padding: 32px 36px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow-y: auto;
    text-align: left;
    animation: dp-pop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes dp-pop {
    from { opacity: 0; transform: scale(0.97) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.dp-hdr { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.dp-hdr-left { display: flex; align-items: center; gap: 14px; }
.dp-title { color: #1d6bf3; font-size: 24px; font-weight: 800; margin: 0; letter-spacing: -0.5px; }
.dp-feedback { background: #dcfce7; color: #15803d; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 20px; }
.dp-x-btn { background: transparent; border: none; cursor: pointer; padding: 6px; border-radius: 50%; display: flex; }
.dp-x-btn:hover { background: #f1f5f9; }

.dp-note {
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
.dp-note svg { flex-shrink: 0; margin-top: 2px; }

.dp-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 28px; }
.dp-col { display: flex; flex-direction: column; gap: 18px; min-width: 0; }

.dp-field { display: flex; flex-direction: column; gap: 6px; }
.dp-label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
.dp-label-row { display: flex; justify-content: space-between; align-items: center; }
.dp-check { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #475569; cursor: pointer; }
.dp-hint { margin: 0; font-size: 11.5px; color: #94a3b8; }
.dp-hint-inline { text-transform: none; letter-spacing: 0; font-weight: 500; color: #94a3b8; }

.dp-input {
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
.dp-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12); }
.dp-headline { font-size: 16px; font-weight: 700; }

.dp-editor { border: 1.5px solid #e2e8f0; border-radius: 16px; overflow: hidden; }
.dp-toolbar { display: flex; align-items: center; gap: 4px; padding: 8px 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
.dp-tool { width: 30px; height: 30px; border: none; background: transparent; border-radius: 8px; color: #475569; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.dp-tool:hover { background: #e2e8f0; }
.dp-tool-divider { width: 1px; height: 18px; background: #e2e8f0; margin: 0 4px; }
.dp-editor-body { min-height: 260px; max-height: 380px; overflow-y: auto; padding: 16px 18px; font-size: 14px; line-height: 1.7; color: #1e293b; outline: none; }
.dp-editor-body:empty::before { content: attr(data-placeholder); color: #94a3b8; }
.dp-editor-body :deep(h2) { font-size: 18px; margin: 12px 0 6px; }
.dp-editor-foot { padding: 6px 14px; border-top: 1px solid #f1f5f9; font-size: 12px; color: #94a3b8; text-align: right; }

.dp-none-pill { padding: 12px 14px; border: 1.5px dashed #cbd5e1; border-radius: 14px; font-size: 13px; color: #94a3b8; }
.dp-no-label :deep(.mus-label) { display: none; }

.dp-upload { border: 1.5px dashed #cbd5e1; border-radius: 14px; height: 140px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8fafc; }
.dp-upload.has { border-style: solid; border-color: #e2e8f0; }
.dp-upload-empty { font-size: 13px; color: #94a3b8; }
.dp-thumb { width: 100%; height: 100%; object-fit: cover; }
.dp-upload-actions { display: flex; align-items: center; gap: 12px; }

.dp-media-row { display: flex; gap: 8px; flex-wrap: wrap; }
.dp-media-item { position: relative; width: 72px; height: 72px; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; }
.dp-media-item img { width: 100%; height: 100%; object-fit: cover; }
.dp-media-x { position: absolute; top: 3px; right: 3px; width: 20px; height: 20px; border-radius: 50%; border: none; background: rgba(15, 23, 42, 0.7); color: #fff; font-size: 14px; line-height: 1; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; }

.dp-btn-blue, .dp-btn-grey, .dp-btn-outline {
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
.dp-btn-blue { background: #1d6bf3; color: #fff; border: none; padding: 12px 26px; box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3); }
.dp-btn-blue:hover:not(:disabled) { background: #1557b0; }
.dp-btn-grey { background: #f1f5f9; color: #475569; border: none; padding: 12px 22px; }
.dp-btn-grey:hover:not(:disabled) { background: #e2e8f0; }
.dp-btn-outline { background: transparent; color: #1d6bf3; border: 1.5px solid #1d6bf3; padding: 8px 18px; font-size: 13px; width: fit-content; }
.dp-btn-outline:hover:not(:disabled) { background: #eff6ff; }
.dp-btn-blue:disabled, .dp-btn-grey:disabled, .dp-btn-outline:disabled { opacity: 0.6; cursor: not-allowed; }
.dp-link-btn { border: none; background: none; color: #dc2626; font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit; }

.dp-error { color: #dc2626; font-size: 13px; margin: 16px 0 0; padding: 8px 12px; background: #fef2f2; border-radius: 10px; border: 1px solid #fecaca; }
.dp-footer { display: flex; justify-content: flex-end; gap: 12px; margin-top: 22px; }

.dp-sub-card { background: #fff; border-radius: 28px; width: 100%; max-width: 440px; padding: 30px; box-sizing: border-box; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); }
.dp-sub-title { margin: 0 0 8px; font-size: 18px; font-weight: 800; color: #0f172a; }
.dp-sub-text { margin: 0 0 20px; font-size: 13.5px; color: #64748b; line-height: 1.6; }
.dp-sub-actions { display: flex; justify-content: flex-end; gap: 12px; }

@media (max-width: 860px) {
    .dp-grid { grid-template-columns: 1fr; }
    .dp-card { padding: 24px 20px; }
}
</style>
