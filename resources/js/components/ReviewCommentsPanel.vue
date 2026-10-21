<template>
    <aside class="rc-panel">
        <h3 class="rc-heading">Comments</h3>

        <div class="rc-tabs" role="tablist">
            <button type="button" role="tab" class="rc-tab" :class="{ active: tab === 'open' }" :aria-selected="tab === 'open'" @click="tab = 'open'">
                Open<span v-if="openComments.length" class="rc-count">{{ openComments.length }}</span>
            </button>
            <button type="button" role="tab" class="rc-tab" :class="{ active: tab === 'resolved' }" :aria-selected="tab === 'resolved'" @click="tab = 'resolved'">
                Resolved
            </button>
        </div>

        <div class="rc-list">
            <!-- A new comment on the words just highlighted -->
            <form v-if="draft && tab === 'open'" class="rc-card rc-composer" @submit.prevent="submitDraft">
                <p class="rc-quote">&ldquo;{{ shortQuote(draft.quote) }}&rdquo;</p>
                <textarea
                    ref="draftInput"
                    v-model="draftBody"
                    class="rc-textarea"
                    rows="3"
                    maxlength="1000"
                    placeholder="What should be changed here?"
                    @keydown.ctrl.enter.prevent="submitDraft"
                    @keydown.meta.enter.prevent="submitDraft"
                ></textarea>
                <div class="rc-composer-foot">
                    <span class="rc-char">{{ draftBody.length }}/1000</span>
                    <div class="rc-composer-actions">
                        <button type="button" class="rc-btn rc-btn-ghost" :disabled="saving" @click="$emit('cancel-draft')">Cancel</button>
                        <button type="submit" class="rc-btn rc-btn-primary" :disabled="saving || !draftBody.trim()">{{ saving ? 'Adding…' : 'Add comment' }}</button>
                    </div>
                </div>
                <p v-if="error" class="rc-error">{{ error }}</p>
            </form>

            <article
                v-for="comment in visible"
                :key="comment.id"
                class="rc-card"
                :class="{ active: comment.id === activeId, lost: lostIds.has(comment.id) }"
                @click="$emit('select', comment.id)"
            >
                <header class="rc-card-head">
                    <span class="rc-author">
                        <img :src="avatarFor(comment.user)" class="rc-avatar" alt="" />
                        <span class="rc-role">{{ roleInfo(comment.author_role).label }}</span>
                    </span>
                    <span class="rc-dot" :style="{ background: roleInfo(comment.author_role).color }" :title="comment.user?.name || ''"></span>
                </header>

                <p v-if="comment.quote" class="rc-quote">&ldquo;{{ shortQuote(comment.quote) }}&rdquo;</p>
                <p class="rc-body">{{ comment.body }}</p>
                <p v-if="lostIds.has(comment.id)" class="rc-lost">This part of the article was changed, so it can't be highlighted any more.</p>

                <footer class="rc-card-foot">
                    <span class="rc-time">{{ stamp(comment.status === 'resolved' ? comment.resolved_at : comment.created_at) }}<template v-if="comment.status === 'resolved' && comment.resolver"> · done by {{ comment.resolver.name }}</template></span>

                    <span class="rc-actions">
                        <button
                            v-if="comment.status === 'open' && comment.user_id === currentUserId && canComment"
                            type="button" class="rc-icon-btn" title="Delete comment" aria-label="Delete comment"
                            @click.stop="$emit('remove', comment.id)"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        </button>

                        <button
                            v-if="comment.status === 'open' && canResolve"
                            type="button" class="rc-done-btn" :disabled="busyId === comment.id"
                            @click.stop="$emit('resolve', comment.id)"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><polyline points="8 12.5 11 15.5 16 9.5"/></svg>
                            Done
                        </button>

                        <button
                            v-else-if="comment.status === 'resolved' && canReopen"
                            type="button" class="rc-done-btn rc-reopen" :disabled="busyId === comment.id"
                            @click.stop="$emit('reopen', comment.id)"
                        >
                            Reopen
                        </button>
                    </span>
                </footer>
            </article>

            <p v-if="!visible.length && !(draft && tab === 'open')" class="rc-empty">
                {{ emptyText }}
            </p>
        </div>
    </aside>
</template>

<script setup>
import { computed, nextTick, ref, watch } from 'vue';
import { avatarFor, roleInfo, stamp } from '../utils/reviewRoles';

const props = defineProps({
    comments: { type: Array, default: () => [] },
    activeId: { type: Number, default: null },
    // The highlighted words being commented on right now ({ quote }), or null
    draft: { type: Object, default: null },
    saving: { type: Boolean, default: false },
    error: { type: String, default: '' },
    busyId: { type: Number, default: null },
    currentUserId: { type: Number, default: null },
    // Comments whose words can't be found in the article any more
    lostIds: { type: Object, default: () => new Set() },
    canComment: { type: Boolean, default: false },
    canResolve: { type: Boolean, default: false },
    canReopen: { type: Boolean, default: false },
});

const emit = defineEmits(['select', 'resolve', 'reopen', 'remove', 'save-draft', 'cancel-draft']);

const tab = ref('open');
const draftBody = ref('');
const draftInput = ref(null);

const openComments = computed(() => props.comments.filter((c) => c.status === 'open'));
const resolvedComments = computed(() => props.comments.filter((c) => c.status === 'resolved'));
const visible = computed(() => (tab.value === 'open' ? openComments.value : resolvedComments.value));

const emptyText = computed(() => {
    if (tab.value === 'resolved') return 'Nothing to show';
    return props.canComment ? 'Highlight some text in the article to add a comment.' : 'No open comments.';
});

// A long highlight is shortened so the card stays small.
const shortQuote = (quote) => {
    const text = (quote || '').replace(/\s+/g, ' ').trim();
    return text.length > 140 ? text.slice(0, 137) + '…' : text;
};

const submitDraft = () => {
    if (!draftBody.value.trim() || props.saving) return;
    emit('save-draft', draftBody.value.trim());
};

// A fresh highlight opens the composer on the Open tab with the cursor in the box; a finished comment empties it.
watch(() => props.draft, async (draft, before) => {
    if (draft && !before) {
        tab.value = 'open';
        draftBody.value = '';
        await nextTick();
        draftInput.value?.focus();
    } else if (!draft) {
        draftBody.value = '';
    }
});

// Choosing a resolved comment (from elsewhere) shows it
watch(() => props.activeId, (id) => {
    const comment = props.comments.find((c) => c.id === id);
    if (comment) tab.value = comment.status === 'resolved' ? 'resolved' : 'open';
});
</script>

<style scoped>
.rc-panel {
    display: flex;
    flex-direction: column;
    min-height: 0;
    background: #ebebeb;
    border-radius: 20px;
    padding: 18px 16px 16px;
    font-family: 'Manrope', sans-serif;
    box-sizing: border-box;
}

.rc-heading {
    margin: 0 0 8px 4px;
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
}

.rc-tabs {
    display: flex;
    gap: 18px;
    margin: 0 0 12px 4px;
}

.rc-tab {
    background: none;
    border: none;
    padding: 4px 0;
    font: inherit;
    font-size: 13.5px;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-bottom: 2px solid transparent;
}

.rc-tab.active {
    color: #1d6bf3;
    border-bottom-color: #1d6bf3;
}

.rc-count {
    background: #1d6bf3;
    color: #fff;
    border-radius: 10px;
    padding: 0 6px;
    font-size: 11px;
    font-weight: 800;
    line-height: 17px;
}

.rc-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    overflow-y: auto;
    min-height: 0;
    flex: 1 1 auto;
    padding-right: 2px;
}

.rc-card {
    background: #fff;
    border: 1.5px solid transparent;
    border-radius: 16px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    cursor: pointer;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.rc-card:hover { box-shadow: 0 2px 10px rgba(15, 23, 42, 0.08); }
.rc-card.active { border-color: #f6c453; box-shadow: 0 2px 12px rgba(246, 196, 83, 0.35); }
.rc-card.lost { opacity: 0.85; }

.rc-composer { cursor: default; border-color: #bfdbfe; }

.rc-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.rc-author {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f1f5f9;
    border-radius: 50px;
    padding: 3px 12px 3px 3px;
}

.rc-avatar {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
}

.rc-role {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
}

.rc-dot {
    width: 11px;
    height: 11px;
    border-radius: 50%;
    flex-shrink: 0;
}

.rc-quote {
    margin: 0;
    padding-left: 10px;
    border-left: 3px solid #f6c453;
    font-size: 12px;
    font-style: italic;
    color: #64748b;
    line-height: 1.5;
}

.rc-composer .rc-quote { border-left-color: #93c5fd; }

.rc-body {
    margin: 0;
    font-size: 13px;
    line-height: 1.55;
    color: #1e293b;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.rc-lost {
    margin: 0;
    font-size: 11.5px;
    color: #b45309;
}

.rc-card-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.rc-time {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
}

.rc-actions {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.rc-icon-btn {
    background: none;
    border: none;
    color: #94a3b8;
    padding: 4px;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
}
.rc-icon-btn:hover { background: #fee2e2; color: #dc2626; }

.rc-done-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #fff;
    border: 1.5px solid #cbd5e1;
    color: #334155;
    border-radius: 50px;
    padding: 4px 11px 4px 8px;
    font: inherit;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s;
}
.rc-done-btn:hover:not(:disabled) { background: #dcfce7; border-color: #16a34a; color: #15803d; }
.rc-done-btn:disabled { opacity: 0.55; cursor: not-allowed; }
.rc-reopen { padding: 4px 11px; }

.rc-textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 12px;
    font: inherit;
    font-size: 13px;
    color: #1e293b;
    resize: vertical;
    outline: none;
}
.rc-textarea:focus { border-color: #1d6bf3; }

.rc-composer-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.rc-char { font-size: 11px; color: #94a3b8; }
.rc-composer-actions { display: inline-flex; gap: 8px; }

.rc-btn {
    border-radius: 50px;
    padding: 6px 14px;
    font: inherit;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    border: 1.5px solid transparent;
}
.rc-btn:disabled { opacity: 0.55; cursor: not-allowed; }
.rc-btn-ghost { background: #f8fafc; border-color: #e2e8f0; color: #64748b; }
.rc-btn-primary { background: #1d6bf3; color: #fff; }
.rc-btn-primary:hover:not(:disabled) { background: #1558cc; }

.rc-error {
    margin: 0;
    font-size: 12px;
    color: #dc2626;
}

.rc-empty {
    margin: 0;
    flex: 1 1 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    background: #f7f7f7;
    border-radius: 16px;
    padding: 28px 18px;
    font-size: 13px;
    color: #475569;
    min-height: 120px;
}
</style>
