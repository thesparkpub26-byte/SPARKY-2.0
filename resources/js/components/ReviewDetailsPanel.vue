<template>
    <div class="rd-panel">
        <!-- Activities -->
        <section class="rd-block">
            <h3 class="rd-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h4"/><path d="M14 3l5 5v2"/><polyline points="14 3 14 8 19 8"/><circle cx="17" cy="17" r="4"/><polyline points="17 15 17 17 18.5 18"/></svg>
                Activities
            </h3>
            <div class="rd-card rd-table-wrap">
                <p v-if="loading" class="rd-muted">Loading…</p>
                <p v-else-if="failed" class="rd-muted">The activity could not be loaded.</p>
                <p v-else-if="!activities.length" class="rd-muted">Nothing has happened on this article yet.</p>
                <table v-else class="rd-table">
                    <thead>
                        <tr><th>Date &amp; Time</th><th>User</th><th>Action</th><th>Section</th></tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in activities" :key="row.id">
                            <td class="rd-when"><span class="rd-dot" :style="{ background: roleInfo(row.role).color }"></span>{{ fullDate(row.created_at) }}</td>
                            <td class="rd-user">{{ who(row.role, row.user) }}</td>
                            <td class="rd-action">{{ row.action }}</td>
                            <td class="rd-user">{{ row.section }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Version history -->
        <section class="rd-block">
            <h3 class="rd-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7"/><polyline points="3 4 3 9 8 9"/><polyline points="12 7 12 12 15 14"/></svg>
                Version History
            </h3>
            <div class="rd-card rd-versions">
                <p v-if="loading" class="rd-muted">Loading…</p>
                <p v-else-if="!versions.length" class="rd-muted">A version is saved when the article is submitted for review or a reviewer edits it.</p>
                <div v-for="version in versions" :key="version.id" class="rd-version">
                    <span class="rd-doc">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><polyline points="14 3 14 8 19 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="13" y2="17"/></svg>
                    </span>
                    <div class="rd-version-text">
                        <p class="rd-version-name">v{{ version.version }} - {{ version.label }}<span v-if="version.is_latest" class="rd-latest">Latest</span></p>
                        <p class="rd-version-by">{{ who(version.role, version.user) }} • {{ fullDate(version.created_at) }}</p>
                    </div>
                    <button type="button" class="rd-preview-btn" @click="openPreview(version)">Preview</button>
                </div>
            </div>
        </section>

        <!-- The assignment at a glance -->
        <section class="rd-card rd-meta">
            <div class="rd-meta-item"><span class="rd-meta-label">Coverage</span><span class="rd-meta-value">{{ coverage }}</span></div>
            <div class="rd-meta-item"><span class="rd-meta-label">Deadline</span><span class="rd-meta-value">{{ deadline }}</span></div>
            <div class="rd-meta-item"><span class="rd-meta-label">Last updated</span><span class="rd-meta-value">{{ lastUpdated }}</span></div>
            <div class="rd-meta-item">
                <span class="rd-meta-label">Assigned To</span>
                <span class="rd-people">
                    <img v-for="person in assignees" :key="person.id" :src="avatarFor(person)" :title="person.name" class="rd-person" alt="" />
                    <span v-if="!assignees.length" class="rd-meta-value">—</span>
                </span>
            </div>
            <div class="rd-meta-item"><span class="rd-meta-label">Status</span><span class="rd-pill" :class="'status-' + (article?.status || 'draft')">{{ statusLabel }}</span></div>
            <div class="rd-meta-item"><span class="rd-meta-label">Priority</span><span class="rd-pill rd-priority" :class="'priority-' + priority"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2 4 14h6l-1 8 9-12h-6z"/></svg>{{ priorityLabel }}</span></div>
        </section>

        <!-- Preview of one saved version -->
        <div v-if="preview" class="rd-overlay" @click.self="preview = null">
            <div class="rd-preview">
                <div class="rd-preview-head">
                    <div>
                        <p class="rd-preview-kicker">v{{ preview.version }} - {{ preview.label }}</p>
                        <h4 class="rd-preview-title">{{ preview.title }}</h4>
                    </div>
                    <button type="button" class="rd-close" aria-label="Close preview" @click="preview = null">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <p v-if="previewLoading" class="rd-muted">Loading…</p>
                <p v-else-if="previewFailed" class="rd-muted">This version could not be loaded.</p>
                <div v-else class="rd-preview-body" v-html="preview.content || '<p>This version has no text.</p>'"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { avatarFor, fullStamp, roleInfo } from '../utils/reviewRoles';

const props = defineProps({
    articleId: { type: [Number, String], default: null },
    // The article as /api/articles/{id} returns it (with its tasks), for the assignment details
    article: { type: Object, default: null },
    // Changes whenever the lists should be loaded again
    refreshKey: { type: Number, default: 0 },
});

const activities = ref([]);
const versions = ref([]);
const loading = ref(false);
const failed = ref(false);

const preview = ref(null);
const previewLoading = ref(false);
const previewFailed = ref(false);

const headers = () => ({ Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json' });

const REVIEWER_ROLES = ['section_editor', 'copyreader', 'eic'];
// Editors are shown by title, everyone else by name
const who = (role, name) => (REVIEWER_ROLES.includes(role) ? roleInfo(role).label : name);

const fullDate = fullStamp;

const load = async () => {
    if (!props.articleId) {
        activities.value = [];
        versions.value = [];
        return;
    }
    loading.value = true;
    failed.value = false;
    try {
        const res = await fetch(`/api/articles/${props.articleId}/review-details`, { headers: headers() });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();
        activities.value = data.activities || [];
        versions.value = data.versions || [];
    } catch (e) {
        console.warn('Could not load the review details:', e);
        failed.value = true;
    } finally {
        loading.value = false;
    }
};

watch(() => [props.articleId, props.refreshKey], load, { immediate: true });

const openPreview = async (version) => {
    preview.value = { ...version, title: '', content: '' };
    previewLoading.value = true;
    previewFailed.value = false;
    try {
        const res = await fetch(`/api/articles/${props.articleId}/versions/${version.id}`, { headers: headers() });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();
        preview.value = { ...preview.value, title: data.title, content: data.content };
    } catch (e) {
        console.warn('Could not load the version:', e);
        previewFailed.value = true;
    } finally {
        previewLoading.value = false;
    }
};

// The assignment details come from the writer's task on the article
const writingTask = computed(() => (props.article?.tasks || []).find((t) => t.type === 'writing') || (props.article?.tasks || [])[0] || null);

const noteField = (notes, key) => {
    const match = (notes || '').match(new RegExp(`${key}:\\s*([^|]+)`, 'i'));
    return match ? match[1].trim() : '';
};

const longDate = (value, withTime) => {
    const date = new Date(value);
    if (!value || isNaN(date.getTime())) return '—';
    const day = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    return withTime ? `${day} • ${date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}` : day;
};

const coverage = computed(() => noteField(writingTask.value?.notes, 'Coverage') || props.article?.section?.name || '—');
const deadline = computed(() => longDate(writingTask.value?.deadline, false));
const lastUpdated = computed(() => longDate(props.article?.updated_at, true));

const assignees = computed(() => {
    const seen = new Map();
    for (const task of props.article?.tasks || []) {
        if (task.assignee && !seen.has(task.assignee.id)) seen.set(task.assignee.id, task.assignee);
    }
    return [...seen.values()];
});

const STATUS_LABELS = {
    draft: 'Draft', submitted: 'Submitted', under_review: 'Under Review', endorsed: 'Endorsed',
    approved: 'Approved', rejected: 'Returned', published: 'Published', scheduled: 'Scheduled',
};
const statusLabel = computed(() => STATUS_LABELS[props.article?.status] || 'Draft');

const priority = computed(() => writingTask.value?.priority || 'medium');
const priorityLabel = computed(() => priority.value.charAt(0).toUpperCase() + priority.value.slice(1));
</script>

<style scoped>
.rd-panel {
    display: flex;
    flex-direction: column;
    gap: 18px;
    background: #ebebeb;
    border-radius: 20px;
    padding: 20px 22px 22px;
    font-family: 'Manrope', sans-serif;
    color: #0f172a;
}

.rd-title {
    display: flex;
    align-items: center;
    gap: 9px;
    margin: 0 0 10px;
    font-size: 17px;
    font-weight: 800;
}

.rd-card {
    background: #fff;
    border-radius: 16px;
    padding: 16px 20px;
}

.rd-muted {
    margin: 4px 0;
    font-size: 13px;
    color: #64748b;
}

.rd-table-wrap { padding: 14px 18px; overflow-x: auto; }

.rd-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
}

.rd-table th {
    text-align: left;
    font-weight: 800;
    padding: 4px 12px 10px 0;
    white-space: nowrap;
}

.rd-table td {
    padding: 7px 12px 7px 0;
    color: #475569;
    white-space: nowrap;
}

.rd-when { display: flex; align-items: center; gap: 9px; }
.rd-action { font-weight: 800; color: #0f172a; }

.rd-dot {
    width: 11px;
    height: 11px;
    border-radius: 50%;
    flex-shrink: 0;
}

.rd-versions { padding: 6px 18px; }

.rd-version {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid #e2e8f0;
}
.rd-version:last-child { border-bottom: none; }

.rd-doc {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    background: #cfe0fb;
    color: #1d6bf3;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.rd-version-text { flex: 1 1 auto; min-width: 0; }

.rd-version-name {
    margin: 0;
    font-size: 13.5px;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.rd-latest {
    background: #1d6bf3;
    color: #fff;
    border-radius: 6px;
    padding: 1px 8px;
    font-size: 10.5px;
    font-weight: 700;
}

.rd-version-by {
    margin: 2px 0 0;
    font-size: 12px;
    color: #64748b;
}

.rd-preview-btn {
    background: #fff;
    border: 1.5px solid #1d6bf3;
    color: #1d6bf3;
    border-radius: 50px;
    padding: 5px 16px;
    font: inherit;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    flex-shrink: 0;
}
.rd-preview-btn:hover { background: #eff6ff; }

.rd-meta {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px 20px;
}

.rd-meta-item { display: flex; flex-direction: column; gap: 5px; min-width: 0; }
.rd-meta-label { font-size: 12px; color: #64748b; }
.rd-meta-value { font-size: 13px; font-weight: 800; overflow-wrap: anywhere; }

.rd-people { display: flex; align-items: center; }
.rd-person {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    margin-left: -6px;
    box-shadow: 0 0 0 1px #e2e8f0;
}
.rd-person:first-child { margin-left: 0; }

.rd-pill {
    align-self: flex-start;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 50px;
    padding: 3px 12px;
    font-size: 11.5px;
    font-weight: 700;
    background: #e2e8f0;
    color: #334155;
}
.rd-pill.status-endorsed { background: #f3d9f7; color: #86198f; }
.rd-pill.status-approved, .rd-pill.status-published { background: #dcfce7; color: #15803d; }
.rd-pill.status-submitted, .rd-pill.status-under_review { background: #fef3c7; color: #b45309; }
.rd-pill.status-rejected { background: #ffe4e6; color: #be123c; }
.rd-pill.status-scheduled { background: #dbeafe; color: #1e40af; }
.rd-priority.priority-high, .rd-priority.priority-urgent { background: #f6c9d0; color: #9f1239; }
.rd-priority.priority-medium { background: #fde68a; color: #92400e; }
.rd-priority.priority-low { background: #dcfce7; color: #15803d; }

.rd-overlay {
    position: fixed;
    inset: 0;
    z-index: 10100;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.rd-preview {
    background: #fff;
    border-radius: 24px;
    width: 100%;
    max-width: 640px;
    max-height: 85vh;
    overflow-y: auto;
    padding: 24px 28px 28px;
    box-sizing: border-box;
    box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.35);
}

.rd-preview-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 14px;
}

.rd-preview-kicker { margin: 0 0 4px; font-size: 12px; font-weight: 700; color: #1d6bf3; }
.rd-preview-title { margin: 0; font-size: 20px; font-weight: 800; line-height: 1.3; }

.rd-close {
    background: none;
    border: none;
    padding: 6px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
}
.rd-close:hover { background: #f1f5f9; }

.rd-preview-body {
    font-size: 14px;
    line-height: 1.75;
    color: #334155;
}
.rd-preview-body :deep(p) { margin: 0 0 12px; }
.rd-preview-body :deep(img) { max-width: 100%; height: auto; }

@media (max-width: 640px) {
    .rd-meta { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .rd-panel { padding: 14px; }
}
</style>
