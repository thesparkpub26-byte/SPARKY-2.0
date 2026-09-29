<template>
    <div style="display: flex; flex-direction: column; gap: 14px; width: 100%;">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
            <h1 class="page-title" style="margin-bottom: 0;">Gallery</h1>
            <button class="new-user-btn" type="button" @click="openUploadModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                Upload Photo
            </button>
        </div>

        <div class="card" style="padding: 24px; background: #ffffff; border-radius: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);">
            <div v-if="loading" class="empty-activity" style="text-align: center; padding: 40px;">Loading photos…</div>
            <div v-else-if="shownPhotos.length === 0" class="empty-activity" style="text-align: center; padding: 40px;">{{ photos.length ? 'No photos match your search.' : 'No photos yet. Click "Upload Photo" to add one.' }}</div>
            <div v-else class="gallery-grid">
                <div v-for="photo in shownPhotos" :key="photo.id" class="gallery-card" @click="openViewModal(photo)" style="cursor: pointer;">
                    <div class="gallery-card-img-wrap">
                        <img :src="photo.image_url" :alt="photo.title" class="gallery-card-img" />
                    </div>
                    <div class="gallery-card-body">
                        <span class="gallery-card-title">{{ photo.title }}</span>
                        <span class="gallery-card-meta">{{ photo.artist?.name || photo.uploader?.name || 'Unknown' }} &bull; {{ formatDate(photo.created_at) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals are teleported to <body>: the tab's fade-in animation would otherwise confine the overlay to the content area -->
        <!-- Upload Photo Modal -->
        <Teleport to="body">
        <div v-if="isUploadOpen" class="new-user-modal-overlay" @click.self="isUploadOpen = false">
            <form class="new-user-modal" role="dialog" aria-modal="true" @submit.prevent="submitUpload">
                <div class="new-user-modal-header">
                    <h2>Upload Photo</h2>
                    <button class="new-user-close" type="button" aria-label="Close" @click="isUploadOpen = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="new-user-step">
                    <div class="form-group">
                        <label class="form-label">Title</label>
                        <input v-model="uploadForm.title" class="form-control" placeholder="Photo title" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Author</label>
                        <AuthorSelect v-model="uploadForm.artist_id" :options="artists" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date Published</label>
                        <input v-model="uploadForm.published_at" type="datetime-local" class="form-control" :max="nowLocal">
                        <p class="date-hint">Set an earlier date to post a past photo under its actual date.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Photo</label>
                        <div class="upload-box">
                            <div class="upload-circle" :class="{ 'has-preview': uploadPreview }">
                                <img v-if="uploadPreview" :src="uploadPreview" alt="Photo preview">
                                <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                            </div>
                            <div class="upload-info">
                                <p>Choose a file or drag & drop it here.<br>jpeg, png, gif, webp - Up to 10MB</p>
                                <input ref="uploadFileInput" type="file" accept="image/jpeg,image/png,image/gif,image/webp" hidden @change="handleUploadFileChange">
                                <button class="btn-upload" type="button" @click="uploadFileInput?.click()">Upload photo</button>
                            </div>
                        </div>
                    </div>
                    <p v-if="uploadError" class="new-user-error">{{ uploadError }}</p>
                    <div class="modal-footer">
                        <button class="btn-back" type="button" @click="isUploadOpen = false">Cancel</button>
                        <button class="btn-next" type="submit" :disabled="uploadSaving">{{ uploadSaving ? 'Uploading...' : 'Upload Photo' }}</button>
                    </div>
                </div>
            </form>
        </div>
        </Teleport>

        <!-- Photo View / Edit Modal -->
        <Teleport to="body">
        <div v-if="isViewOpen" class="new-user-modal-overlay" @click.self="closeViewModal">
            <div class="new-user-modal gallery-view-modal" role="dialog" aria-modal="true">
                <div class="new-user-modal-header">
                    <h2>{{ isEditing ? 'Edit Photo' : viewingPhoto?.title }}</h2>
                    <button class="new-user-close" type="button" aria-label="Close" @click="closeViewModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>

                <!-- View mode -->
                <div v-if="!isEditing" class="new-user-step">
                    <img :src="viewingPhoto?.image_url" :alt="viewingPhoto?.title" class="gallery-view-img">
                    <p class="gallery-card-meta" style="margin-top: 10px;">
                        <template v-if="viewingPhoto?.artist">Artist: {{ viewingPhoto.artist.name }} &bull; </template>Uploaded by {{ viewingPhoto?.uploader?.name || 'Unknown' }} &bull; {{ formatDate(viewingPhoto?.created_at) }}
                    </p>
                    <div class="modal-footer">
                        <button class="btn-back" type="button" style="color: #dc2626;" @click="confirmDelete">Delete</button>
                        <button class="btn-next" type="button" @click="startEdit">Edit</button>
                    </div>
                </div>

                <!-- Edit mode -->
                <form v-else class="new-user-step" @submit.prevent="submitEdit">
                    <div class="form-group">
                        <label class="form-label">Title</label>
                        <input v-model="editForm.title" class="form-control" placeholder="Photo title" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Author</label>
                        <AuthorSelect v-model="editForm.artist_id" :options="artists" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date Published</label>
                        <input v-model="editForm.published_at" type="datetime-local" class="form-control" :max="nowLocal">
                        <p class="date-hint">Set an earlier date to post a past photo under its actual date.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Photo</label>
                        <div class="upload-box">
                            <div class="upload-circle has-preview">
                                <img :src="editPreview" alt="Photo preview">
                            </div>
                            <div class="upload-info">
                                <p>Choose a file to replace the current photo.<br>jpeg, png, gif, webp - Up to 10MB</p>
                                <input ref="editFileInput" type="file" accept="image/jpeg,image/png,image/gif,image/webp" hidden @change="handleEditFileChange">
                                <button class="btn-upload" type="button" @click="editFileInput?.click()">Replace photo</button>
                            </div>
                        </div>
                    </div>
                    <p v-if="editError" class="new-user-error">{{ editError }}</p>
                    <div class="modal-footer">
                        <button class="btn-back" type="button" @click="isEditing = false">Cancel</button>
                        <button class="btn-next" type="submit" :disabled="editSaving">{{ editSaving ? 'Saving...' : 'Save Changes' }}</button>
                    </div>
                </form>
            </div>
        </div>
        </Teleport>

        <!-- Delete Photo Confirmation Modal -->
        <Teleport to="body">
        <div v-if="photoToDelete" class="new-user-modal-overlay" @click.self="photoToDelete = null">
            <div class="new-user-modal new-user-confirm-modal" role="dialog" aria-modal="true">
                <div class="new-user-modal-header">
                    <h2>Delete Photo?</h2>
                    <button class="new-user-close" type="button" aria-label="Close" @click="photoToDelete = null">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <p>Delete <strong>"{{ photoToDelete.title }}"</strong>? This action cannot be undone.</p>
                <p v-if="deleteError" class="new-user-error">{{ deleteError }}</p>
                <div class="modal-footer">
                    <button class="btn-next btn-danger" type="button" :disabled="deleteSaving" @click="deletePhoto">{{ deleteSaving ? 'Deleting...' : 'Delete Photo' }}</button>
                    <button class="btn-back" type="button" @click="photoToDelete = null">Cancel</button>
                </div>
            </div>
        </div>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AuthorSelect from './AuthorSelect.vue';

// Gallery management for the Art Editor: view photos, upload new ones, edit and delete existing ones.
const authHeaders = () => ({
    Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
    Accept: 'application/json',
});

const formatDate = (iso) => iso
    ? new Date(iso).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
    : '';

const pad = (n) => String(n).padStart(2, '0');
const toLocalInput = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
const nowLocal = computed(() => toLocalInput(new Date()));

const props = defineProps({ search: { type: String, default: '' } });

const photos = ref([]);
const shownPhotos = computed(() => {
    const needle = props.search.trim().toLowerCase();
    if (!needle) return photos.value;

    return photos.value.filter(photo => [photo.title, photo.artist?.name, photo.uploader?.name]
        .some(value => String(value ?? '').toLowerCase().includes(needle)));
});
const loading = ref(true);
const artists = ref([]);

const errorFrom = (data, fallback) => (data.errors ? Object.values(data.errors)[0]?.[0] : data.message) || fallback;

const loadPhotos = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/gallery', { headers: authHeaders() });
        if (response.ok) photos.value = await response.json();
    } catch {
        photos.value = [];
    } finally {
        loading.value = false;
    }
};

const loadArtists = async () => {
    try {
        const response = await fetch('/api/gallery/artists', { headers: authHeaders() });
        if (response.ok) artists.value = await response.json();
    } catch {
        artists.value = [];
    }
};

// ── Upload ──────────────────────────────────────────────────────────────────
const isUploadOpen = ref(false);
const uploadForm = ref({ title: '', artist_id: '', published_at: '' });
const uploadFile = ref(null);
const uploadPreview = ref('');
const uploadFileInput = ref(null);
const uploadError = ref('');
const uploadSaving = ref(false);

const openUploadModal = () => {
    loadArtists();
    uploadForm.value = { title: '', artist_id: '', published_at: toLocalInput(new Date()) };
    uploadFile.value = null;
    uploadPreview.value = '';
    uploadError.value = '';
    isUploadOpen.value = true;
};

const handleUploadFileChange = (event) => {
    const file = event.target.files?.[0] || null;
    uploadFile.value = file;
    uploadPreview.value = file ? URL.createObjectURL(file) : '';
};

const submitUpload = async () => {
    uploadError.value = '';
    if (!uploadForm.value.title.trim()) {
        uploadError.value = 'Please provide a title.';
        return;
    }
    if (!uploadForm.value.artist_id) {
        uploadError.value = 'Please select the author of the photo.';
        return;
    }
    if (!uploadFile.value) {
        uploadError.value = 'Please choose a photo to upload.';
        return;
    }
    if (uploadForm.value.published_at && new Date(uploadForm.value.published_at).getTime() > Date.now() + 60 * 1000) {
        uploadError.value = 'The date published can\'t be in the future.';
        return;
    }

    uploadSaving.value = true;
    const payload = new FormData();
    payload.append('title', uploadForm.value.title);
    payload.append('artist_id', uploadForm.value.artist_id);
    payload.append('photo', uploadFile.value);
    if (uploadForm.value.published_at) payload.append('published_at', new Date(uploadForm.value.published_at).toISOString());

    try {
        const response = await fetch('/api/gallery', { method: 'POST', headers: authHeaders(), body: payload });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(errorFrom(data, 'Could not upload photo.'));
        photos.value.unshift(data);
        isUploadOpen.value = false;
    } catch (error) {
        uploadError.value = error.message;
    } finally {
        uploadSaving.value = false;
    }
};

// ── View / edit ─────────────────────────────────────────────────────────────
const isViewOpen = ref(false);
const viewingPhoto = ref(null);
const isEditing = ref(false);
const editForm = ref({ title: '', artist_id: '', published_at: '' });
const editFile = ref(null);
const editPreview = ref('');
const editFileInput = ref(null);
const editError = ref('');
const editSaving = ref(false);

const openViewModal = (photo) => {
    viewingPhoto.value = photo;
    isEditing.value = false;
    isViewOpen.value = true;
};

const closeViewModal = () => {
    isViewOpen.value = false;
    isEditing.value = false;
};

const startEdit = () => {
    loadArtists();
    editForm.value = {
        title: viewingPhoto.value?.title || '',
        artist_id: viewingPhoto.value?.artist_id || '',
        published_at: viewingPhoto.value?.created_at ? toLocalInput(new Date(viewingPhoto.value.created_at)) : toLocalInput(new Date()),
    };
    editFile.value = null;
    editPreview.value = viewingPhoto.value?.image_url || '';
    editError.value = '';
    isEditing.value = true;
};

const handleEditFileChange = (event) => {
    const file = event.target.files?.[0] || null;
    if (!file) return;
    editFile.value = file;
    editPreview.value = URL.createObjectURL(file);
};

const submitEdit = async () => {
    editError.value = '';
    if (!editForm.value.title.trim()) {
        editError.value = 'Please provide a title.';
        return;
    }
    if (!editForm.value.artist_id) {
        editError.value = 'Please select the author of the photo.';
        return;
    }
    if (editForm.value.published_at && new Date(editForm.value.published_at).getTime() > Date.now() + 60 * 1000) {
        editError.value = 'The date published can\'t be in the future.';
        return;
    }

    editSaving.value = true;
    const payload = new FormData();
    payload.append('title', editForm.value.title);
    payload.append('artist_id', editForm.value.artist_id);
    if (editFile.value) payload.append('photo', editFile.value);
    if (editForm.value.published_at) payload.append('published_at', new Date(editForm.value.published_at).toISOString());

    try {
        const response = await fetch(`/api/gallery/${viewingPhoto.value.id}`, { method: 'POST', headers: authHeaders(), body: payload });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(errorFrom(data, 'Could not save changes.'));
        viewingPhoto.value = data;
        const idx = photos.value.findIndex(p => p.id === data.id);
        if (idx !== -1) photos.value[idx] = data;
        isEditing.value = false;
    } catch (error) {
        editError.value = error.message;
    } finally {
        editSaving.value = false;
    }
};

// ── Delete ──────────────────────────────────────────────────────────────────
const photoToDelete = ref(null);
const deleteError = ref('');
const deleteSaving = ref(false);

const confirmDelete = () => {
    photoToDelete.value = viewingPhoto.value;
    deleteError.value = '';
    closeViewModal();
};

const deletePhoto = async () => {
    if (!photoToDelete.value) return;
    deleteSaving.value = true;
    deleteError.value = '';
    try {
        const response = await fetch(`/api/gallery/${photoToDelete.value.id}`, { method: 'DELETE', headers: authHeaders() });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(data.message || 'Could not delete photo.');
        photos.value = photos.value.filter(p => p.id !== photoToDelete.value.id);
        photoToDelete.value = null;
    } catch (error) {
        deleteError.value = error.message;
    } finally {
        deleteSaving.value = false;
    }
};

onMounted(loadPhotos);
</script>

<style scoped>
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 18px;
}

.gallery-card {
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    background: #ffffff;
}

.gallery-card-img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 4 / 3;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.gallery-card-body {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 12px 14px;
}

.gallery-card-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
}

.gallery-card-meta {
    font-size: 11.5px;
    color: #94a3b8;
}

.gallery-view-modal {
    max-width: 560px;
}

.gallery-view-img {
    width: 100%;
    max-height: 420px;
    object-fit: contain;
    background: #f1f5f9;
    border-radius: 14px;
    display: block;
}

.date-hint {
    margin: 4px 0 0;
    font-size: 11.5px;
    color: #94a3b8;
}
</style>
