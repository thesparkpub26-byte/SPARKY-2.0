<template>
    <div class="storage-page">
        <!-- Header -->
        <div class="storage-header">
            <div class="storage-header-left">
                <img src="/assets/Spark_Logo.png" alt="TheSPARK" class="storage-logo" />
                <div>
                    <p class="storage-eyebrow">File Storage</p>
                    <h1 class="storage-title">
                        {{ entryTopic || 'Article Folder' }}
                        <span class="storage-badge" v-if="entrySection">{{ entrySection }}</span>
                    </h1>
                </div>
            </div>
            <div class="storage-header-right">
                <button class="upload-btn" @click="triggerUpload">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Upload Files
                </button>
                <input ref="fileInput" type="file" multiple class="hidden-input" @change="handleUpload" />
            </div>
        </div>

        <!-- Toolbar -->
        <div class="storage-toolbar">
            <div class="breadcrumb">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                <span>Article Files</span>
                <span class="crumb-sep">/</span>
                <span class="crumb-active">{{ entryTopic || 'Entry ' + entryId }}</span>
            </div>
            <div class="toolbar-right">
                <span class="file-count">{{ files.length }} file{{ files.length !== 1 ? 's' : '' }}</span>
                <button class="view-toggle" :class="{ active: viewMode === 'grid' }" @click="viewMode = 'grid'" title="Grid view">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                </button>
                <button class="view-toggle" :class="{ active: viewMode === 'list' }" @click="viewMode = 'list'" title="List view">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                </button>
            </div>
        </div>

        <!-- Drop Zone (empty state) -->
        <div v-if="files.length === 0"
             class="drop-zone"
             @dragover.prevent="dragging = true"
             @dragleave="dragging = false"
             @drop.prevent="handleDrop"
             :class="{ 'drop-zone--active': dragging }">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            <p class="drop-title">No files yet</p>
            <p class="drop-sub">Drag &amp; drop files here, or click <strong>Upload Files</strong></p>
        </div>

        <!-- Grid View -->
        <div v-else-if="viewMode === 'grid'"
             class="file-grid"
             @dragover.prevent="dragging = true"
             @dragleave="dragging = false"
             @drop.prevent="handleDrop"
             :class="{ 'file-grid--dragging': dragging }">

            <div v-for="file in files" :key="file.id" class="file-card" @click="previewFile(file)">
                <div class="file-icon-wrap" :style="iconBg(file)">
                    <img v-if="isImage(file)" :src="file.dataUrl" class="file-thumb" :alt="file.name" />
                    <span v-else class="file-ext-icon">{{ extLabel(file.name) }}</span>
                </div>
                <div class="file-card-body">
                    <p class="file-name" :title="file.name">{{ file.name }}</p>
                    <p class="file-meta">{{ formatSize(file.size) }} &middot; {{ formatDate(file.uploadedAt) }}</p>
                </div>
                <div class="file-card-actions">
                    <button class="icon-btn" @click.stop="downloadFile(file)" title="Download">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    </button>
                    <button class="icon-btn icon-btn--danger" @click.stop="removeFile(file.id)" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                    </button>
                </div>
            </div>

            <div class="file-card file-card--add" @click="triggerUpload">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <p>Upload more</p>
            </div>
        </div>

        <!-- List View -->
        <div v-else class="file-list-wrap"
             @dragover.prevent="dragging = true"
             @dragleave="dragging = false"
             @drop.prevent="handleDrop">
            <table class="file-list-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Type</th>
                        <th>Uploaded</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="file in files" :key="file.id" class="file-list-row" @click="previewFile(file)">
                        <td class="file-list-name">
                            <span class="list-ext-badge" :style="extColor(file.name)">{{ extLabel(file.name) }}</span>
                            {{ file.name }}
                        </td>
                        <td>{{ formatSize(file.size) }}</td>
                        <td>{{ file.type || 'Unknown' }}</td>
                        <td>{{ formatDate(file.uploadedAt) }}</td>
                        <td class="file-list-actions">
                            <button class="icon-btn" @click.stop="downloadFile(file)" title="Download">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            </button>
                            <button class="icon-btn icon-btn--danger" @click.stop="removeFile(file.id)" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Image Preview Lightbox -->
        <div v-if="preview" class="lightbox" @click.self="preview = null">
            <div class="lightbox-card">
                <div class="lightbox-header">
                    <span class="lightbox-name">{{ preview.name }}</span>
                    <div class="lightbox-actions">
                        <button class="icon-btn" @click="downloadFile(preview)" title="Download">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        </button>
                        <button class="icon-btn icon-btn--close" @click="preview = null">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                </div>
                <img v-if="isImage(preview)" :src="preview.dataUrl" class="lightbox-img" :alt="preview.name" />
                <div v-else class="lightbox-nopreview">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <p>No preview available for this file type.</p>
                    <button class="upload-btn" style="margin-top:16px" @click="downloadFile(preview)">Download to open</button>
                </div>
            </div>
        </div>

        <!-- Toast -->
        <transition name="toast-fade">
            <div v-if="toast" class="toast" :class="'toast--' + toast.type">{{ toast.message }}</div>
        </transition>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const entryId = computed(() => route.params.entryId);
const entryTopic = ref('');
const entrySection = ref('');

const files = ref([]);
const viewMode = ref('grid');
const dragging = ref(false);
const preview = ref(null);
const fileInput = ref(null);
const toast = ref(null);

const storageKey = computed(() => `sparky_files_${entryId.value}`);

onMounted(() => {
    const meta = JSON.parse(localStorage.getItem(`sparky_entry_meta_${entryId.value}`) || '{}');
    entryTopic.value = meta.topic || '';
    entrySection.value = meta.section || '';
    files.value = JSON.parse(localStorage.getItem(storageKey.value) || '[]');
});

function persist() {
    localStorage.setItem(storageKey.value, JSON.stringify(files.value));
    try {
        const token = localStorage.getItem('sparky_token');
        const meta = JSON.parse(localStorage.getItem(`sparky_entry_meta_${entryId.value}`) || '{}');
        if (token && meta.sheetId) {
            fetch(`/api/monitoring-sheets/${meta.sheetId}/entries/${entryId.value}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ has_files: files.value.length > 0 })
            }).catch((error) => console.warn('Could not update the monitoring sheet file flag:', error));
        }
    } catch {}
}

function triggerUpload() { fileInput.value?.click(); }

function handleUpload(e) {
    readFiles(Array.from(e.target.files));
    e.target.value = '';
}

function handleDrop(e) {
    dragging.value = false;
    readFiles(Array.from(e.dataTransfer.files));
}

function readFiles(fileList) {
    fileList.forEach(f => {
        const reader = new FileReader();
        reader.onload = (ev) => {
            files.value.push({
                id: Date.now() + Math.random(),
                name: f.name,
                size: f.size,
                type: f.type,
                dataUrl: ev.target.result,
                uploadedAt: new Date().toISOString()
            });
            persist();
        };
        reader.readAsDataURL(f);
    });
    showToast(`${fileList.length} file(s) uploaded`, 'success');
}

function removeFile(id) {
    files.value = files.value.filter(f => f.id !== id);
    persist();
    showToast('File removed', 'info');
    if (preview.value?.id === id) preview.value = null;
}

function downloadFile(file) {
    const a = document.createElement('a');
    a.href = file.dataUrl;
    a.download = file.name;
    a.click();
}

function previewFile(file) { preview.value = file; }
function isImage(file) { return file.type?.startsWith('image/'); }

function extLabel(name) {
    const parts = name.split('.');
    return parts.length > 1 ? parts.pop().toUpperCase().slice(0, 4) : 'FILE';
}

const extColorMap = {
    PDF:  { background: '#fce7f3', color: '#db2777' },
    DOC:  { background: '#dbeafe', color: '#1d4ed8' },
    DOCX: { background: '#dbeafe', color: '#1d4ed8' },
    PNG:  { background: '#dcfce7', color: '#15803d' },
    JPG:  { background: '#dcfce7', color: '#15803d' },
    JPEG: { background: '#dcfce7', color: '#15803d' },
    GIF:  { background: '#fef9c3', color: '#a16207' },
    MP4:  { background: '#f3e8ff', color: '#7e22ce' },
    MOV:  { background: '#f3e8ff', color: '#7e22ce' },
    TXT:  { background: '#f1f5f9', color: '#475569' },
    PPTX: { background: '#fef3c7', color: '#d97706' },
    XLSX: { background: '#d1fae5', color: '#065f46' },
};

function extColor(name) {
    const ext = extLabel(name);
    return extColorMap[ext] || { background: '#f1f5f9', color: '#475569' };
}

function iconBg(file) {
    if (isImage(file)) return { background: '#f8fafc' };
    return extColor(file.name);
}

function formatSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

function formatDate(iso) {
    return new Date(iso).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' });
}

function showToast(message, type = 'info') {
    toast.value = { message, type };
    setTimeout(() => toast.value = null, 2800);
}
</script>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }

.storage-page {
    min-height: 100vh;
    background: #f1f5f9;
    font-family: 'Manrope', 'Inter', sans-serif;
    color: #0f172a;
    display: flex;
    flex-direction: column;
}

.storage-header {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px -4px rgba(0,0,0,0.06);
    padding: 18px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    position: sticky;
    top: 0;
    z-index: 20;
}

.storage-header-left { display: flex; align-items: center; gap: 14px; }
.storage-logo { width: 38px; height: 38px; object-fit: contain; }

.storage-eyebrow {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 3px;
}

.storage-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.storage-badge {
    font-size: 11px;
    font-weight: 700;
    background: #dbeafe;
    color: #1d4ed8;
    padding: 3px 10px;
    border-radius: 999px;
}

.hidden-input { display: none; }

.upload-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #2563eb;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(37,99,235,0.25);
    transition: all 0.2s;
    font-family: inherit;
}
.upload-btn:hover { background: #1d4ed8; transform: translateY(-1px); }

.storage-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 32px;
    background: #fff;
    border-bottom: 1px solid #f1f5f9;
}

.breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #64748b; font-weight: 600; }
.crumb-sep { color: #cbd5e1; }
.crumb-active { color: #0f172a; font-weight: 700; }
.toolbar-right { display: flex; align-items: center; gap: 8px; }
.file-count { font-size: 12px; color: #94a3b8; font-weight: 600; margin-right: 4px; }

.view-toggle {
    width: 30px;
    height: 30px;
    border: 1px solid #e2e8f0;
    border-radius: 7px;
    background: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    transition: all 0.15s;
}
.view-toggle:hover, .view-toggle.active { background: #eff6ff; border-color: #bfdbfe; color: #2563eb; }

.drop-zone {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 14px;
    padding: 80px 32px;
    text-align: center;
    border: 2.5px dashed #cbd5e1;
    border-radius: 16px;
    margin: 24px 32px;
    background: #fff;
    transition: all 0.2s;
    cursor: pointer;
}
.drop-zone--active, .drop-zone:hover { border-color: #93c5fd; background: #eff6ff; }
.drop-title { font-size: 17px; font-weight: 800; color: #334155; }
.drop-sub { font-size: 13px; color: #94a3b8; line-height: 1.6; }

.file-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 16px;
    padding: 24px 32px;
    transition: background 0.2s;
}
.file-grid--dragging {
    background: #eff6ff;
    outline: 2.5px dashed #93c5fd;
    outline-offset: -8px;
    border-radius: 12px;
}

.file-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 8px -2px rgba(0,0,0,0.04);
}
.file-card:hover { border-color: #93c5fd; box-shadow: 0 8px 20px -4px rgba(37,99,235,0.12); transform: translateY(-2px); }

.file-card--add {
    background: #f8fafc;
    border: 2px dashed #cbd5e1;
    align-items: center;
    justify-content: center;
    min-height: 160px;
    gap: 8px;
    color: #94a3b8;
    font-size: 12px;
    font-weight: 600;
}
.file-card--add:hover { border-color: #93c5fd; background: #eff6ff; color: #2563eb; }

.file-icon-wrap { height: 110px; display: flex; align-items: center; justify-content: center; }
.file-thumb { width: 100%; height: 100%; object-fit: cover; }
.file-ext-icon { font-size: 18px; font-weight: 800; letter-spacing: 0.5px; }

.file-card-body { padding: 10px 12px 6px; flex: 1; }
.file-name { font-size: 12px; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.file-meta { font-size: 10px; color: #94a3b8; margin-top: 3px; font-weight: 500; }
.file-card-actions { display: flex; justify-content: flex-end; gap: 6px; padding: 6px 10px 10px; }

.file-list-wrap { padding: 24px 32px; }
.file-list-table { width: 100%; border-collapse: separate; border-spacing: 0; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; }
.file-list-table th { padding: 12px 18px; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.7px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: left; }
.file-list-row { cursor: pointer; transition: background 0.15s; }
.file-list-row:hover td { background: #eff6ff; }
.file-list-row td { padding: 13px 18px; font-size: 13px; font-weight: 500; color: #334155; border-bottom: 1px solid #f1f5f9; }
.file-list-name { display: flex; align-items: center; gap: 10px; font-weight: 700 !important; color: #0f172a !important; }
.list-ext-badge { font-size: 10px; font-weight: 800; padding: 3px 7px; border-radius: 5px; letter-spacing: 0.3px; flex-shrink: 0; }
.file-list-actions { display: flex; gap: 6px; justify-content: flex-end; }

.icon-btn { width: 28px; height: 28px; border: 1px solid #e2e8f0; background: #f8fafc; border-radius: 7px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #475569; transition: all 0.15s; }
.icon-btn:hover { background: #eff6ff; border-color: #bfdbfe; color: #2563eb; }
.icon-btn--danger:hover { background: #fef2f2; border-color: #fca5a5; color: #ef4444; }
.icon-btn--close:hover { background: #f1f5f9; border-color: #cbd5e1; color: #0f172a; }

.lightbox { position: fixed; inset: 0; background: rgba(15,23,42,0.75); backdrop-filter: blur(8px); z-index: 999; display: flex; align-items: center; justify-content: center; padding: 24px; }
.lightbox-card { background: #fff; border-radius: 18px; width: 100%; max-width: 800px; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 30px 60px -10px rgba(0,0,0,0.35); animation: popIn 0.2s cubic-bezier(0.16,1,0.3,1); }
@keyframes popIn { from { opacity: 0; transform: scale(0.95) translateY(8px); } to { opacity: 1; transform: scale(1) translateY(0); } }
.lightbox-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; gap: 12px; }
.lightbox-name { font-size: 14px; font-weight: 700; color: #0f172a; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.lightbox-actions { display: flex; gap: 8px; flex-shrink: 0; }
.lightbox-img { max-width: 100%; max-height: calc(90vh - 70px); object-fit: contain; display: block; margin: 0 auto; }
.lightbox-nopreview { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; padding: 60px; color: #94a3b8; font-size: 13px; }

.toast { position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%); padding: 12px 24px; border-radius: 10px; font-size: 13px; font-weight: 700; z-index: 9999; box-shadow: 0 8px 24px -4px rgba(0,0,0,0.15); }
.toast--success { background: #dcfce7; color: #15803d; }
.toast--info    { background: #dbeafe; color: #1d4ed8; }
.toast--error   { background: #fef2f2; color: #dc2626; }
.toast-fade-enter-active, .toast-fade-leave-active { transition: all 0.3s; }
.toast-fade-enter-from, .toast-fade-leave-to { opacity: 0; transform: translateX(-50%) translateY(12px); }
</style>
