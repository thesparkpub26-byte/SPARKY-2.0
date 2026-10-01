<template>
    <div v-if="isOpen && video" class="new-user-modal-overlay" @click.self="closeModal">
        <div class="new-user-modal vm-modal" role="dialog" aria-modal="true">
            <div class="new-user-modal-header">
                <h2>{{ headerTitle }}</h2>
                <button class="new-user-close" type="button" aria-label="Close" @click="closeModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <!-- Detail mode -->
            <div v-if="mode === 'detail'" class="new-user-step">
                <div class="vm-player">
                    <img v-if="thumbnail" :src="thumbnail" :alt="video.title" class="vm-thumb" />
                    <div v-else class="vm-thumb-empty">No preview available</div>
                    <div class="vm-play" v-if="thumbnail">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="#ffffff"><polygon points="6 4 20 12 6 20 6 4"></polygon></svg>
                    </div>
                </div>

                <div class="vm-meta-row">
                    <span class="vm-category" :class="{ unset: !video.video_category }">{{ video.video_category || 'Uncategorized' }}</span>
                    <span class="vm-meta-text">{{ video.author?.name || 'Unknown' }} &bull; {{ formatDate(video.published_at || video.created_at) }}</span>
                </div>

                <h4 class="vm-label">Details</h4>
                <p class="vm-description">{{ video.excerpt || video.content || 'No details were provided.' }}</p>

                <template v-if="creditLines.length">
                    <h4 class="vm-label">Credits</h4>
                    <p v-for="line in creditLines" :key="line.label" class="vm-credit-line"><strong>{{ line.label }}:</strong> {{ line.names }}</p>
                </template>

                <div class="modal-footer vm-footer">
                    <button class="btn-back" type="button" style="color: #dc2626;" @click="mode = 'delete'">Delete</button>
                    <div style="display: flex; gap: 14px;">
                        <button class="btn-back" type="button" @click="startEdit">Edit</button>
                        <button class="btn-next" type="button" @click="viewVideo" :disabled="!video.video_url">View Video</button>
                    </div>
                </div>
            </div>

            <!-- Edit mode -->
            <form v-else-if="mode === 'edit'" class="new-user-step" @submit.prevent="saveEdit">
                <div class="form-group">
                    <label class="form-label">Headline</label>
                    <input v-model="form.title" class="form-control" placeholder="Video headline" maxlength="255" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Details</label>
                    <textarea v-model="form.excerpt" class="form-control" rows="5" maxlength="1000" placeholder="What is this video about?"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">YouTube Link</label>
                    <input v-model="form.video_url" type="url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Section</label>
                    <SectionSelect v-model="form.video_category" direction="up" />
                </div>
                <p v-if="errorMessage" class="new-user-error">{{ errorMessage }}</p>
                <div class="modal-footer">
                    <button class="btn-back" type="button" @click="cancelEdit" :disabled="saving">Cancel</button>
                    <button class="btn-next" type="submit" :disabled="saving">{{ saving ? 'Saving...' : 'Save Changes' }}</button>
                </div>
            </form>

            <!-- Delete confirmation -->
            <div v-else class="new-user-step">
                <p>Delete <strong>"{{ video.title }}"</strong>? This will permanently remove the video and its linked tasks. This action cannot be undone.</p>
                <p v-if="errorMessage" class="new-user-error">{{ errorMessage }}</p>
                <div class="modal-footer vm-confirm-footer">
                    <button class="btn-next btn-danger" type="button" :disabled="saving" @click="confirmDelete">{{ saving ? 'Deleting...' : 'Delete Video' }}</button>
                    <button class="btn-back" type="button" @click="mode = 'detail'" :disabled="saving">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import SectionSelect from './SectionSelect.vue';
import { extractYouTubeId, youtubeThumbnail } from '../utils/video';

const props = defineProps({
    isOpen: { type: Boolean, default: false },
    video: { type: Object, default: null },
    // Open straight into the edit form (e.g. from the article preview's Edit button)
    startInEdit: { type: Boolean, default: false }
});

const emit = defineEmits(['close', 'updated', 'deleted']);

const mode = ref('detail'); // 'detail' | 'edit' | 'delete'
const saving = ref(false);
const errorMessage = ref('');
const form = ref({ title: '', excerpt: '', video_url: '', video_category: '' });

const thumbnail = computed(() => youtubeThumbnail(props.video?.video_url) || props.video?.cover_image || '');
const headerTitle = computed(() => {
    if (mode.value === 'edit') return 'Edit Video';
    if (mode.value === 'delete') return 'Delete Video?';
    return props.video?.title || 'Video';
});

const CREDIT_LABELS = { reporter: 'Reporter', scriptwriter: 'Scriptwriter', videographer: 'Videographer', video_editor: 'Video Editor' };
const creditLines = computed(() => Object.entries(CREDIT_LABELS)
    .map(([role, label]) => ({
        label,
        names: (props.video?.credits || []).filter(c => c.role === role && c.user).map(c => c.user.name).join(', ')
    }))
    .filter(line => line.names));

// Switches to edit mode with the video's current details.
const startEdit = () => {
    form.value = {
        title: props.video?.title || '',
        excerpt: props.video?.excerpt || props.video?.content || '',
        video_url: props.video?.video_url || '',
        video_category: props.video?.video_category || ''
    };
    errorMessage.value = '';
    mode.value = 'edit';
};

// Leaves edit mode (closing the modal if it was opened straight into editing).
const cancelEdit = () => {
    if (props.startInEdit) closeModal();
    else mode.value = 'detail';
};

watch(() => [props.isOpen, props.video, props.startInEdit], () => {
    if (!props.isOpen || !props.video) return;
    errorMessage.value = '';
    saving.value = false;
    if (props.startInEdit) startEdit();
    else mode.value = 'detail';
}, { immediate: true });

// Formats a date as "Oct 1, 2026" (empty when invalid).
const formatDate = (dateStr) => {
    const d = new Date(dateStr);
    return isNaN(d.getTime()) ? '' : d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

// Request headers with the sign-in token and a JSON content type.
const authHeaders = () => ({
    Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
    Accept: 'application/json',
    'Content-Type': 'application/json'
});

// Opens the video's YouTube link in a new tab.
const viewVideo = () => {
    if (props.video?.video_url) window.open(props.video.video_url, '_blank', 'noopener');
};

// Saves the video's changes (it needs a valid YouTube link).
const saveEdit = async () => {
    if (!extractYouTubeId(form.value.video_url)) {
        errorMessage.value = 'Please enter a valid YouTube link.';
        return;
    }
    if (!form.value.video_category) {
        errorMessage.value = 'Please choose a section.';
        return;
    }

    saving.value = true;
    errorMessage.value = '';
    try {
        const res = await fetch(`/api/articles/${props.video.id}`, {
            method: 'PUT',
            headers: authHeaders(),
            body: JSON.stringify({
                title: form.value.title.trim(),
                excerpt: form.value.excerpt,
                content: form.value.excerpt,
                video_url: form.value.video_url.trim(),
                video_category: form.value.video_category,
                cover_image: youtubeThumbnail(form.value.video_url)
            })
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Could not update the video.');
        }
        emit('updated', data);
        if (props.startInEdit) closeModal();
        else mode.value = 'detail';
    } catch (e) {
        errorMessage.value = e.message || 'Could not update the video.';
    } finally {
        saving.value = false;
    }
};

// Deletes the video.
const confirmDelete = async () => {
    saving.value = true;
    errorMessage.value = '';
    try {
        const res = await fetch(`/api/articles/${props.video.id}`, { method: 'DELETE', headers: authHeaders() });
        if (!res.ok) {
            const data = await res.json().catch(() => ({}));
            throw new Error(data.message || 'Could not delete the video.');
        }
        emit('deleted', props.video.id);
        closeModal();
    } catch (e) {
        errorMessage.value = e.message || 'Could not delete the video.';
    } finally {
        saving.value = false;
    }
};

// Closes the modal.
const closeModal = () => emit('close');
</script>

<style scoped>
.vm-modal { max-width: 560px; }

.vm-player {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    border-radius: 14px;
    overflow: hidden;
    background: #0f172a;
    display: flex;
    align-items: center;
    justify-content: center;
}

.vm-thumb { width: 100%; height: 100%; object-fit: cover; display: block; }
.vm-thumb-empty { color: #94a3b8; font-size: 13px; }

.vm-play {
    position: absolute;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(15, 23, 42, 0.65);
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.vm-meta-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
.vm-category { background: #dbeafe; color: #1e40af; padding: 3px 12px; border-radius: 12px; font-size: 12px; font-weight: 700; }
.vm-category.unset { background: #f1f5f9; color: #64748b; }
.vm-meta-text { font-size: 12.5px; color: #94a3b8; }

.vm-label { font-size: 13px; font-weight: 800; color: #0f172a; margin: 16px 0 6px; }
.vm-description { font-size: 13.5px; color: #475569; line-height: 1.7; margin: 0; white-space: pre-wrap; }

.vm-credit-line { font-size: 13px; color: #475569; margin: 0 0 4px; line-height: 1.5; }

.vm-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }

/* Delete confirmation: danger action on top, Cancel below, both full width */
.vm-confirm-footer {
    flex-direction: column;
    align-items: stretch;
    gap: 12px;
}

.vm-confirm-footer .btn-back,
.vm-confirm-footer .btn-next {
    width: 100%;
    justify-content: center;
}
</style>
