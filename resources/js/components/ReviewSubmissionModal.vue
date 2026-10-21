<template>
    <div v-if="isOpen" class="rs-overlay" @click.self="$emit('close')">
        <div class="rs-card" role="dialog" aria-modal="true" aria-labelledby="rs-title">

            <!-- Header -->
            <div class="rs-hdr">
                <h2 id="rs-title" class="rs-title">Review Submission</h2>
                <button type="button" class="rs-x" title="Close" aria-label="Close" @click="$emit('close')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <p v-if="loading" class="rs-state">Loading the submission…</p>
            <p v-else-if="loadError" class="rs-state rs-state-error">{{ loadError }}</p>

            <template v-else-if="article">
                <div class="rs-meta-row">
                    <span class="rs-section">{{ sectionName }}</span>
                    <button
                        v-if="step === 'review' && tab === 'content'"
                        type="button" class="rs-edit-btn" :class="{ on: editing }"
                        :disabled="savingEdit" @click="editing ? cancelEdit() : startEdit()"
                    >{{ editing ? 'Cancel' : 'Edit' }}</button>
                </div>
                <h1 class="rs-article-title">{{ article.title }}</h1>

                <!-- Step 1: read the article, highlight text, comment -->
                <template v-if="step === 'review'">
                    <div class="rs-tabs" role="tablist">
                        <button v-for="t in TABS" :key="t.id" type="button" role="tab" class="rs-tab" :class="{ active: tab === t.id }" :aria-selected="tab === t.id" @click="tab = t.id">{{ t.label }}</button>
                    </div>

                    <div class="rs-body">
                        <!-- Article Content -->
                        <div v-show="tab === 'content'" class="rs-grid">
                            <div class="rs-left">
                                <div v-if="editing" class="rs-edit-bar">
                                    <button type="button" class="rs-tool" title="Bold" @mousedown.prevent @click="format('bold')"><b>B</b></button>
                                    <button type="button" class="rs-tool" title="Italic" @mousedown.prevent @click="format('italic')"><i>I</i></button>
                                    <button type="button" class="rs-tool" title="Underline" @mousedown.prevent @click="format('underline')"><u>U</u></button>
                                    <span class="rs-edit-hint">Editing the text. Highlights are hidden until you save.</span>
                                    <button type="button" class="rs-save-edit" :disabled="savingEdit" @click="saveEdit">{{ savingEdit ? 'Saving…' : 'Save edits' }}</button>
                                </div>

                                <div class="rs-text-card">
                                    <div
                                        ref="contentRef"
                                        class="rs-text"
                                        :class="{ editable: editing }"
                                        :contenteditable="editing"
                                        v-html="article.content || '<p>No article content to review yet.</p>'"
                                        @mouseup="onPick"
                                        @keyup="onPick"
                                        @touchend="onPickSoon"
                                        @input="onEditInput"
                                    ></div>
                                </div>
                                <p v-if="editError" class="rs-error">{{ editError }}</p>

                                <div class="rs-author-row">
                                    <span class="rs-author">
                                        <img :src="avatarFor(article.author)" class="rs-author-img" alt="" />
                                        {{ article.author?.name || 'Unknown writer' }}
                                    </span>
                                    <span class="rs-words">{{ wordCount }} {{ wordCount === 1 ? 'word' : 'words' }}</span>
                                </div>

                                <div v-if="tags.length" class="rs-tags-block">
                                    <p class="rs-tags-title">Tags</p>
                                    <div class="rs-tags">
                                        <span v-for="tag in tags" :key="tag" class="rs-tag"><span class="rs-hash">#</span>{{ tag }}</span>
                                    </div>
                                </div>
                            </div>

                            <ReviewCommentsPanel
                                class="rs-right"
                                :comments="comments"
                                :active-id="activeId"
                                :draft="draft"
                                :saving="savingDraft"
                                :error="draftError"
                                :busy-id="busyId"
                                :current-user-id="currentUserId"
                                :lost-ids="lostIds"
                                can-comment
                                can-resolve
                                can-reopen
                                @select="selectComment"
                                @save-draft="saveDraft"
                                @cancel-draft="cancelDraft"
                                @resolve="setStatus($event, 'resolved')"
                                @reopen="setStatus($event, 'open')"
                                @remove="removeComment"
                            />
                        </div>

                        <!-- Visual Assets -->
                        <div v-show="tab === 'visuals'" class="rs-visuals">
                            <div class="rs-visual-block">
                                <h3 class="rs-visual-title">Thumbnail</h3>
                                <img v-if="article.cover_image" :src="assetUrl(article.cover_image)" class="rs-thumb" alt="Article thumbnail" />
                                <p v-else class="rs-muted">No thumbnail has been added yet.</p>
                            </div>
                            <div class="rs-visual-block">
                                <h3 class="rs-visual-title">Media</h3>
                                <div v-if="media.length" class="rs-media">
                                    <img v-for="url in media" :key="url" :src="assetUrl(url)" class="rs-media-img" alt="Article media" />
                                </div>
                                <p v-else class="rs-muted">No media has been added yet.</p>
                            </div>
                        </div>

                        <!-- Details -->
                        <div v-if="tab === 'details'" class="rs-details">
                            <ReviewDetailsPanel :article-id="articleId" :article="article" :refresh-key="refreshKey" />
                        </div>
                    </div>

                    <div class="rs-foot">
                        <button type="button" class="rs-back" @click="$emit('close')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            Back
                        </button>
                        <button type="button" class="rs-next" :disabled="!canContinue" :title="continueHint" @click="goNext">Next</button>
                    </div>
                </template>

                <!-- Step 2: send it back -->
                <template v-else>
                    <div class="rs-send">
                        <h3 class="rs-send-title">Return to Writer</h3>
                        <p class="rs-send-text">
                            <strong>{{ article.author?.name || 'The writer' }}</strong> will be notified and will see your
                            <strong>{{ openCount }} open {{ openCount === 1 ? 'comment' : 'comments' }}</strong> highlighted in the article.
                            They mark each one <em>Done</em> as they revise.
                        </p>

                        <label class="rs-label" for="rs-extra">Message to the writer <span class="rs-optional">(optional)</span></label>
                        <textarea id="rs-extra" v-model="extraNote" class="rs-textarea" rows="4" maxlength="500" placeholder="Anything that doesn't belong to one passage, e.g. the overall tone or the deadline."></textarea>
                        <span class="rs-char">{{ extraNote.length }}/500</span>

                        <p v-if="error" class="rs-error">{{ error }}</p>
                    </div>

                    <div class="rs-foot">
                        <button type="button" class="rs-back" :disabled="acting" @click="step = 'review'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            Back
                        </button>
                        <button type="button" class="rs-next rs-return" :disabled="acting" @click="send">{{ acting ? 'Returning…' : 'Return to Writer' }}</button>
                    </div>
                </template>
            </template>
        </div>
    </div>
</template>

<script setup>
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';
import ReviewCommentsPanel from './ReviewCommentsPanel.vue';
import ReviewDetailsPanel from './ReviewDetailsPanel.vue';
import { caretOffset, clearHighlights, commentAt, paintHighlights, scrollToComment, selectionAnchor } from '../utils/reviewHighlights';
import { avatarFor } from '../utils/reviewRoles';
import { requireOk } from '../utils/http';

const props = defineProps({
    isOpen: { type: Boolean, default: false },
    articleId: { type: [Number, String], default: null },
    // The parent is carrying out the return (it owns the request, so it also owns busy and error)
    acting: { type: Boolean, default: false },
    error: { type: String, default: '' },
});

const emit = defineEmits(['close', 'submit']);

const TABS = [
    { id: 'content', label: 'Article Content' },
    { id: 'visuals', label: 'Visual Assets' },
    { id: 'details', label: 'Details' },
];

const step = ref('review'); // 'review' | 'send'
const tab = ref('content');
const article = ref(null);
const comments = ref([]);
const loading = ref(false);
const loadError = ref('');

const contentRef = ref(null);
const activeId = ref(null);
const lostIds = ref(new Set());
const draft = ref(null);
const savingDraft = ref(false);
const draftError = ref('');
const busyId = ref(null);
const refreshKey = ref(0);
const extraNote = ref('');

const editing = ref(false);
const editHtml = ref('');
const savingEdit = ref(false);
const editError = ref('');

const currentUserId = computed(() => {
    try { return JSON.parse(localStorage.getItem('sparky_user') || '{}').id ?? null; } catch { return null; }
});

const headers = (json = false) => ({
    Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
    Accept: 'application/json',
    ...(json ? { 'Content-Type': 'application/json' } : {}),
});

const sectionName = computed(() => article.value?.section?.name || 'News');
const openCount = computed(() => comments.value.filter((c) => c.status === 'open').length);
const media = computed(() => (Array.isArray(article.value?.media_files) ? article.value.media_files : []));
const assetUrl = (url) => (url.startsWith('/') || /^https?:/.test(url) ? url : '/storage/' + url);

const plainWords = (html) => (html || '')
    .replace(/<[^>]*>/g, ' ')
    .replace(/&nbsp;/g, ' ')
    .replace(/\s+/g, ' ')
    .trim();

const wordCount = computed(() => {
    const text = plainWords(editing.value ? editHtml.value : article.value?.content);
    return text ? text.split(' ').length : 0;
});

// The article's section and the coverage it was assigned under
const tags = computed(() => {
    const list = [sectionName.value];
    const writingTask = (article.value?.tasks || []).find((t) => t.type === 'writing');
    const coverage = ((writingTask?.notes || '').match(/Coverage:\s*([^|]+)/i) || [])[1]?.trim();
    if (coverage) list.push(coverage.replace(/\s+/g, ''));
    return list;
});

const canContinue = computed(() => !editing.value && !savingDraft.value && openCount.value > 0);
const continueHint = computed(() => {
    if (editing.value) return 'Save or cancel your edits first.';
    if (openCount.value === 0) return 'Highlight text in the article and add at least one comment.';
    return '';
});

// ----- loading -----

const load = async () => {
    loading.value = true;
    loadError.value = '';
    try {
        const [articleRes, commentsRes] = await Promise.all([
            fetch(`/api/articles/${props.articleId}`, { headers: headers() }),
            fetch(`/api/articles/${props.articleId}/review-comments`, { headers: headers() }),
        ]);
        await requireOk(articleRes, 'The article could not be loaded.');
        await requireOk(commentsRes, 'The comments could not be loaded.');
        article.value = await articleRes.json();
        comments.value = await commentsRes.json();
    } catch (e) {
        loadError.value = e.message || 'The submission could not be loaded.';
    } finally {
        loading.value = false;
        repaint();
    }
};

watch(() => props.isOpen, (open) => {
    if (open && props.articleId) {
        step.value = 'review';
        tab.value = 'content';
        article.value = null;
        comments.value = [];
        activeId.value = null;
        draft.value = null;
        draftError.value = '';
        editing.value = false;
        editError.value = '';
        extraNote.value = '';
        lostIds.value = new Set();
        load();
    } else if (!open) {
        clearHighlights();
    }
}, { immediate: true });

// ----- highlights -----

// Marks the open comments in the text (not while the text is being edited, or when another tab is showing).
const repaint = async () => {
    await nextTick();
    if (!props.isOpen || editing.value || tab.value !== 'content' || step.value !== 'review' || !contentRef.value) {
        clearHighlights();
        return;
    }
    lostIds.value = paintHighlights(contentRef.value, comments.value, { activeId: activeId.value, draft: draft.value });
};

watch([comments, activeId, draft, tab, step, editing], repaint, { deep: true });

onBeforeUnmount(() => { clearTimeout(pickTimer); clearHighlights(); });

// Someone let go of the mouse (or a key) in the text: a selection starts a comment, a click on a mark opens its comment.
const onPick = () => {
    if (editing.value) return;

    const anchor = selectionAnchor(contentRef.value);
    if (anchor) {
        const quote = anchor.quote.length > 2000 ? anchor.quote.slice(0, 2000) : anchor.quote;
        draft.value = { quote, start: anchor.start, end: anchor.start + quote.length };
        draftError.value = '';
        activeId.value = null;
        return;
    }

    const hit = commentAt(contentRef.value, comments.value, caretOffset(contentRef.value));
    if (hit) {
        activeId.value = hit.id;
        draft.value = null;
    }
};

// Touch screens finish a selection a moment after the finger lifts.
let pickTimer = null;
const onPickSoon = () => {
    clearTimeout(pickTimer);
    pickTimer = setTimeout(onPick, 350);
};

const selectComment = (id) => {
    activeId.value = id;
    draft.value = null;
    const comment = comments.value.find((c) => c.id === id);
    if (comment?.status === 'open') nextTick(() => scrollToComment(contentRef.value, comment));
};

// ----- comments -----

const cancelDraft = () => {
    draft.value = null;
    draftError.value = '';
    window.getSelection()?.removeAllRanges();
};

const saveDraft = async (body) => {
    if (!draft.value) return;
    savingDraft.value = true;
    draftError.value = '';
    try {
        const res = await requireOk(await fetch(`/api/articles/${props.articleId}/review-comments`, {
            method: 'POST',
            headers: headers(true),
            body: JSON.stringify({
                quote: draft.value.quote,
                start_offset: draft.value.start,
                end_offset: draft.value.end,
                body,
            }),
        }), 'The comment could not be added.');
        const created = await res.json();
        comments.value = [...comments.value, created];
        draft.value = null;
        activeId.value = created.id;
        refreshKey.value++;
        window.getSelection()?.removeAllRanges();
    } catch (e) {
        draftError.value = e.message || 'The comment could not be added.';
    } finally {
        savingDraft.value = false;
    }
};

const setStatus = async (id, status) => {
    busyId.value = id;
    try {
        const res = await requireOk(await fetch(`/api/review-comments/${id}`, {
            method: 'PATCH',
            headers: headers(true),
            body: JSON.stringify({ status }),
        }), 'The comment could not be updated.');
        const updated = await res.json();
        comments.value = comments.value.map((c) => (c.id === id ? updated : c));
        refreshKey.value++;
    } catch (e) {
        draftError.value = e.message;
    } finally {
        busyId.value = null;
    }
};

const removeComment = async (id) => {
    busyId.value = id;
    try {
        await requireOk(await fetch(`/api/review-comments/${id}`, { method: 'DELETE', headers: headers() }), 'The comment could not be deleted.');
        comments.value = comments.value.filter((c) => c.id !== id);
        if (activeId.value === id) activeId.value = null;
    } catch (e) {
        draftError.value = e.message;
    } finally {
        busyId.value = null;
    }
};

// ----- editing the text -----

const startEdit = () => {
    if (draft.value) cancelDraft();
    editError.value = '';
    editHtml.value = article.value.content || '';
    editing.value = true;
    nextTick(() => contentRef.value?.focus());
};

const cancelEdit = () => {
    editing.value = false;
    editError.value = '';
    // v-html was not re-rendered while typing, so put the saved text back by hand
    if (contentRef.value) contentRef.value.innerHTML = article.value.content || '<p>No article content to review yet.</p>';
};

const onEditInput = () => {
    if (editing.value && contentRef.value) editHtml.value = contentRef.value.innerHTML;
};

const format = (command) => {
    document.execCommand(command, false, null);
    onEditInput();
};

const saveEdit = async () => {
    onEditInput();
    savingEdit.value = true;
    editError.value = '';
    try {
        const res = await requireOk(await fetch(`/api/articles/${props.articleId}`, {
            method: 'PUT',
            headers: headers(true),
            body: JSON.stringify({ content: editHtml.value, word_count: wordCount.value }),
        }), 'Your edits could not be saved.');
        const saved = await res.json();
        article.value = { ...article.value, content: saved.content, word_count: saved.word_count, updated_at: saved.updated_at };
        editing.value = false;
        refreshKey.value++;
    } catch (e) {
        editError.value = e.message || 'Your edits could not be saved.';
    } finally {
        savingEdit.value = false;
    }
};

// ----- sending it back -----

const goNext = () => {
    if (!canContinue.value) return;
    step.value = 'send';
};

const send = () => {
    const extra = extraNote.value.trim().replace(/\|/g, '/');
    const lead = `Please review the ${openCount.value} highlighted ${openCount.value === 1 ? 'comment' : 'comments'} on your article.`;
    emit('submit', {
        notes: extra ? `${lead} ${extra}` : lead,
        commentCount: openCount.value,
    });
};
</script>

<style scoped>
.rs-overlay {
    position: fixed;
    inset: 0;
    z-index: 10050;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
}

.rs-card {
    background: #fff;
    border-radius: 28px;
    width: 100%;
    max-width: 900px;
    max-height: 92vh;
    overflow-y: auto;
    padding: 28px 32px 24px;
    box-sizing: border-box;
    box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.35);
    font-family: 'Manrope', sans-serif;
    color: #0f172a;
    text-align: left;
    animation: rs-pop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes rs-pop {
    from { opacity: 0; transform: scale(0.97) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.rs-hdr {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 14px;
    border-bottom: 1.5px solid #e5e7eb;
    margin-bottom: 18px;
}

.rs-title {
    margin: 0;
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -0.5px;
    color: #1d6bf3;
}

.rs-x {
    background: none;
    border: none;
    padding: 6px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
}
.rs-x:hover { background: #f1f5f9; }

.rs-state { margin: 40px 0; text-align: center; color: #64748b; font-size: 14px; }
.rs-state-error { color: #dc2626; }

.rs-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.rs-section {
    background: #bcd3ef;
    color: #0f172a;
    border-radius: 50px;
    padding: 3px 12px;
    font-size: 11.5px;
    font-weight: 800;
}

.rs-edit-btn {
    background: #e5e7eb;
    border: none;
    color: #475569;
    border-radius: 50px;
    padding: 4px 16px;
    font: inherit;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}
.rs-edit-btn:hover:not(:disabled) { background: #d6d9de; }
.rs-edit-btn.on { background: #fee2e2; color: #b91c1c; }

.rs-article-title {
    margin: 0 0 14px;
    font-size: 26px;
    font-weight: 800;
    line-height: 1.25;
    letter-spacing: -0.4px;
}

.rs-tabs {
    display: flex;
    gap: 30px;
    border-bottom: 1.5px solid #e5e7eb;
    margin-bottom: 18px;
}

.rs-tab {
    background: none;
    border: none;
    padding: 8px 0 10px;
    font: inherit;
    font-size: 14.5px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    position: relative;
}
.rs-tab.active { color: #1d6bf3; }
.rs-tab.active::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: -2px;
    height: 3px;
    border-radius: 3px;
    background: #1d6bf3;
}

.rs-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 14px;
    height: min(500px, 58vh);
}

.rs-left {
    background: #ebebeb;
    border-radius: 20px;
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-height: 0;
    overflow-y: auto;
}

.rs-right { min-height: 0; }

.rs-text-card {
    background: #f8f8f8;
    border-radius: 16px;
    padding: 14px 18px;
    flex: 1 1 auto;
    min-height: 160px;
    overflow-y: auto;
}

.rs-text {
    font-size: 13.5px;
    line-height: 1.7;
    color: #475569;
    outline: none;
    overflow-wrap: anywhere;
}
.rs-text :deep(p) { margin: 0 0 14px; }
.rs-text :deep(p:last-child) { margin-bottom: 0; }
.rs-text :deep(img) { max-width: 100%; height: auto; }
.rs-text.editable { cursor: text; color: #0f172a; box-shadow: inset 0 0 0 2px #bfdbfe; border-radius: 8px; padding: 6px; }

.rs-edit-bar {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
}

.rs-tool {
    width: 28px;
    height: 28px;
    border: none;
    border-radius: 8px;
    background: #fff;
    color: #334155;
    cursor: pointer;
}
.rs-tool:hover { background: #dbeafe; }

.rs-edit-hint { font-size: 11.5px; color: #64748b; flex: 1 1 140px; }

.rs-save-edit {
    background: #16a34a;
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: 5px 14px;
    font: inherit;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}
.rs-save-edit:disabled { opacity: 0.6; cursor: not-allowed; }

.rs-author-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.rs-author {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border-radius: 50px;
    padding: 4px 14px 4px 4px;
    font-size: 12.5px;
    font-weight: 700;
}

.rs-author-img { width: 28px; height: 28px; border-radius: 50%; object-fit: cover; }
.rs-words { font-size: 12px; font-weight: 600; color: #64748b; }

.rs-tags-title { margin: 4px 0 6px; font-size: 14px; font-weight: 800; }
.rs-tags { display: flex; flex-wrap: wrap; gap: 8px; background: #f8f8f8; border-radius: 14px; padding: 10px 12px; }

.rs-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #bcd3ef;
    border-radius: 50px;
    padding: 3px 11px;
    font-size: 11.5px;
    font-weight: 700;
}
.rs-hash { color: #475569; font-weight: 600; }

.rs-visuals {
    background: #ebebeb;
    border-radius: 20px;
    padding: 18px;
    display: flex;
    flex-direction: column;
    gap: 18px;
    min-height: 240px;
}

.rs-visual-title { margin: 0 0 8px; font-size: 15px; font-weight: 800; }
.rs-thumb { max-width: 100%; max-height: 260px; border-radius: 14px; display: block; }
.rs-media { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 10px; }
.rs-media-img { width: 100%; height: 130px; object-fit: cover; border-radius: 12px; display: block; }
.rs-muted { margin: 0; font-size: 13px; color: #64748b; }

.rs-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 20px;
}

.rs-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    font: inherit;
    font-size: 13.5px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    padding: 6px 4px;
}
.rs-back:hover { color: #0f172a; }

.rs-next {
    background: #1d6bf3;
    color: #fff;
    border: none;
    border-radius: 50px;
    min-width: 150px;
    padding: 13px 28px;
    font: inherit;
    font-size: 14.5px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 8px 18px -6px rgba(29, 107, 243, 0.55);
    transition: background 0.15s, transform 0.15s;
}
.rs-next:hover:not(:disabled) { background: #1558cc; transform: translateY(-1px); }
.rs-next:disabled { background: #a9c3f2; box-shadow: none; cursor: not-allowed; }
.rs-return { background: #f59e0b; box-shadow: 0 8px 18px -6px rgba(245, 158, 11, 0.6); }
.rs-return:hover:not(:disabled) { background: #d97706; }
.rs-return:disabled { background: #fcd9a0; }

.rs-send { display: flex; flex-direction: column; gap: 8px; max-width: 560px; }
.rs-send-title { margin: 6px 0 0; font-size: 20px; font-weight: 800; }
.rs-send-text { margin: 0 0 8px; font-size: 14px; line-height: 1.65; color: #475569; }
.rs-label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
.rs-optional { text-transform: none; letter-spacing: 0; font-weight: 600; color: #94a3b8; }

.rs-textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
    font: inherit;
    font-size: 14px;
    color: #1e293b;
    resize: vertical;
    outline: none;
}
.rs-textarea:focus { border-color: #1d6bf3; }
.rs-char { font-size: 11px; color: #94a3b8; text-align: right; }

.rs-error {
    margin: 4px 0 0;
    font-size: 13px;
    color: #dc2626;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 8px;
    padding: 8px 12px;
}

@media (max-width: 760px) {
    .rs-card { padding: 20px 16px 18px; border-radius: 22px; }
    .rs-title { font-size: 23px; }
    .rs-article-title { font-size: 21px; }
    .rs-tabs { gap: 18px; overflow-x: auto; }
    .rs-tab { white-space: nowrap; font-size: 13.5px; }
    .rs-grid { grid-template-columns: minmax(0, 1fr); height: auto; }
    .rs-left { overflow: visible; }
    .rs-text-card { max-height: 320px; }
    .rs-right { max-height: 420px; }
    .rs-next { min-width: 0; padding: 12px 24px; }
}
</style>
