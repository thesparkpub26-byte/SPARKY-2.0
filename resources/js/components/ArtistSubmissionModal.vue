<template>
    <div class="submission-modal-overlay" v-if="isOpen" @click.self="closeModal">
        <div class="submission-modal-card">
            
            <!-- Header -->
            <div class="modal-hdr">
                <div class="modal-hdr-left">
                    <h2 class="modal-blue-title">Artist Submission</h2>
                    <span class="status-badge" :class="submissionData?.status || 'submitted'">
                        {{ formatStatus(submissionData?.status) }}
                    </span>
                </div>
                <button class="modal-close-btn" @click="closeModal" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <!-- Content Area -->
            <div class="submission-content-scroll">
                
                <!-- Article & Artist Info Header Card -->
                <div class="submission-info-card">
                    <div class="info-top-row">
                        <div>
                            <span class="section-tag">{{ submissionData?.section || 'Visuals / Graphics' }}</span>
                            <h1 class="submission-article-title">{{ submissionData?.articleTitle || submissionData?.title || 'Article Visual Submission' }}</h1>
                        </div>
                    </div>

                    <div class="info-meta-divider"></div>

                    <div class="info-bottom-row">
                        <!-- Artist Profile -->
                        <div class="artist-profile-box">
                            <img 
                                :src="artistAvatar" 
                                :alt="submissionData?.artistName || 'Artist'" 
                                class="artist-modal-avatar"
                            />
                            <div>
                                <span class="artist-sub-label">Submitted by:</span>
                                <h4 class="artist-modal-name">{{ submissionData?.artistName || 'Staff Artist' }}</h4>
                                <span class="artist-modal-role">{{ submissionData?.artistRole || 'Staff Artist / PJ' }}</span>
                            </div>
                        </div>

                        <!-- Submission Timestamp -->
                        <div class="submitted-date-box" v-if="submissionData?.submittedAt">
                            <span class="artist-sub-label">Date Submitted:</span>
                            <span class="submission-date-val">{{ formatDateTime(submissionData.submittedAt) }}</span>
                        </div>
                    </div>
                </div>

                <!-- 1. Thumbnail Section -->
                <div class="asset-section" v-if="thumbnailUrl">
                    <div class="asset-section-hdr">
                        <div>
                            <h3 class="asset-section-title">Cover Image / Thumbnail</h3>
                            <p class="asset-section-sub">Uploaded cover visual for the article.</p>
                        </div>
                        <button type="button" class="btn-download-pill" @click="downloadFile(thumbnailUrl, getFileName(thumbnailUrl, 'thumbnail.png'))">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download Thumbnail
                        </button>
                    </div>

                    <div class="thumbnail-display-card">
                        <img :src="thumbnailUrl" alt="Cover Thumbnail" class="thumbnail-display-img" />
                        <div class="thumbnail-display-footer">
                            <span class="file-name-text">{{ getFileName(thumbnailUrl, 'Cover Image') }}</span>
                            <a :href="thumbnailUrl" target="_blank" class="view-original-link">View Full Size</a>
                        </div>
                    </div>
                </div>

                <!-- 2. Media Uploads Section -->
                <div class="asset-section" v-if="mediaList.length > 0">
                    <div class="asset-section-hdr">
                        <div>
                            <h3 class="asset-section-title">Media Files & Photos ({{ mediaList.length }})</h3>
                            <p class="asset-section-sub">Supporting graphics, photojournalist captures, and illustrations.</p>
                        </div>
                        <button type="button" class="btn-download-pill secondary" @click="downloadAllMedia">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download All Media
                        </button>
                    </div>

                    <div class="media-cards-grid">
                        <div v-for="(mediaUrl, idx) in mediaList" :key="idx" class="media-display-card">
                            <div class="media-img-wrap">
                                <img :src="mediaUrl" :alt="`Media ${idx + 1}`" class="media-display-img" />
                            </div>
                            <div class="media-card-bottom">
                                <span class="media-card-title">{{ getFileName(mediaUrl, `Photo ${idx + 1}`) }}</span>
                                <div class="media-card-actions">
                                    <button 
                                        type="button" 
                                        class="btn-icon-download" 
                                        title="Download Image"
                                        @click="downloadFile(mediaUrl, getFileName(mediaUrl, `photo_${idx + 1}.png`))"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7 10 12 15 17 10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                        Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State if no visuals -->
                <div v-if="!thumbnailUrl && mediaList.length === 0" class="no-visuals-card">
                    <p>No media files or thumbnail attached with this submission yet.</p>
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer-actions">
                <button type="button" class="btn-grey-pill" @click="closeModal">Close</button>
                <button 
                    v-if="thumbnailUrl || mediaList.length > 0" 
                    type="button" 
                    class="btn-blue-pill" 
                    @click="downloadAll"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Download All Visuals
                </button>
            </div>

        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    submissionData: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['close']);

const parseNotesField = (notes, key) => {
    if (!notes || typeof notes !== 'string') return '';
    const match = notes.match(new RegExp(`${key}:\\s*([^|]+)`, 'i'));
    return match ? match[1].trim() : '';
};

// Thumbnail URL from submissionData
const thumbnailUrl = computed(() => {
    return props.submissionData?.thumbnail || 
           parseNotesField(props.submissionData?.notes, 'Thumbnail') || 
           parseNotesField(props.submissionData?.raw?.notes, 'Thumbnail') || 
           '';
});

// Media list from submissionData
const mediaList = computed(() => {
    const rawMedia = props.submissionData?.mediaUploads || 
                     parseNotesField(props.submissionData?.notes, 'Media Uploads') || 
                     parseNotesField(props.submissionData?.raw?.notes, 'Media Uploads') || 
                     '';

    if (Array.isArray(rawMedia)) {
        return rawMedia.map(m => typeof m === 'string' ? m : m.url).filter(Boolean);
    }
    if (typeof rawMedia === 'string' && rawMedia) {
        return rawMedia.split(',').map(s => s.trim()).filter(Boolean);
    }
    return [];
});

const artistAvatar = computed(() => {
    const name = props.submissionData?.artistName || 'Artist';
    const pic = props.submissionData?.raw?.assignee?.profile_picture;
    if (pic) return `/storage/${pic}`;
    return `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(name)}&backgroundColor=fdf4ff`;
});

const getFileName = (url, fallback) => {
    if (!url) return fallback;
    const name = url.split('/').pop();
    return name && name.length < 35 ? name : fallback;
};

const formatStatus = (status) => {
    const s = (status || 'submitted').toLowerCase();
    if (s === 'submitted') return 'Submitted by Artist';
    if (s === 'completed' || s === 'published') return 'Completed';
    return s.charAt(0).toUpperCase() + s.slice(1);
};

const formatDateTime = (dateStr) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' • ' + d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
};

// Cross-browser download helper
const downloadFile = async (url, filename = 'visual.png') => {
    try {
        const response = await fetch(url);
        const blob = await response.blob();
        const blobUrl = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = blobUrl;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(blobUrl);
    } catch (e) {
        window.open(url, '_blank');
    }
};

const downloadAllMedia = () => {
    mediaList.value.forEach((url, i) => {
        downloadFile(url, getFileName(url, `photo_${i + 1}.png`));
    });
};

const downloadAll = () => {
    if (thumbnailUrl.value) {
        downloadFile(thumbnailUrl.value, getFileName(thumbnailUrl.value, 'thumbnail.png'));
    }
    downloadAllMedia();
};

const closeModal = () => {
    emit('close');
};
</script>

<style scoped>
.submission-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(8px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    box-sizing: border-box;
}

.submission-modal-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 760px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    padding: 32px;
    box-sizing: border-box;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.3);
    animation: popIn 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    overflow: hidden;
    text-align: left;
}

@keyframes popIn {
    from { opacity: 0; transform: scale(0.96) translateY(12px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-hdr {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.modal-hdr-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.modal-blue-title {
    color: #1d6bf3;
    font-size: 24px;
    font-weight: 800;
    margin: 0;
    letter-spacing: -0.4px;
    font-family: 'Manrope', sans-serif;
}

.status-badge {
    background: #dcfce7;
    color: #15803d;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    border: 1px solid #bbf7d0;
}

.modal-close-btn {
    background: #f1f5f9;
    border: none;
    cursor: pointer;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    transition: all 0.2s;
}

.modal-close-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
}

.submission-content-scroll {
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding-right: 4px;
}

.submission-info-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 20px;
}

.section-tag {
    background: #dbeafe;
    color: #1e40af;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 10px;
    display: inline-block;
    margin-bottom: 8px;
}

.submission-article-title {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    line-height: 1.35;
}

.info-meta-divider {
    height: 1px;
    background: #e2e8f0;
    margin: 16px 0;
}

.info-bottom-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.artist-profile-box {
    display: flex;
    align-items: center;
    gap: 12px;
}

.artist-modal-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.artist-sub-label {
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    display: block;
}

.artist-modal-name {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin: 1px 0 0 0;
}

.artist-modal-role {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
}

.submission-date-val {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
}

/* Asset Sections */
.asset-section {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.asset-section-hdr {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.asset-section-title {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 2px 0;
}

.asset-section-sub {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}

.btn-download-pill {
    background: #1d6bf3;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}

.btn-download-pill:hover {
    background: #1557b0;
}

.btn-download-pill.secondary {
    background: #eff6ff;
    color: #1d6bf3;
    border: 1px solid #bfdbfe;
}

.btn-download-pill.secondary:hover {
    background: #dbeafe;
}

/* Thumbnail Display */
.thumbnail-display-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    overflow: hidden;
}

.thumbnail-display-img {
    width: 100%;
    max-height: 260px;
    object-fit: cover;
    display: block;
    background: #0f172a;
}

.thumbnail-display-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 18px;
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
}

.file-name-text {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
}

.view-original-link {
    font-size: 12px;
    font-weight: 700;
    color: #1d6bf3;
    text-decoration: none;
}

.view-original-link:hover {
    text-decoration: underline;
}

/* Media Grid */
.media-cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.media-display-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.media-img-wrap {
    height: 140px;
    background: #f8fafc;
}

.media-display-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.media-card-bottom {
    padding: 10px 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 6px;
}

.media-card-title {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.btn-icon-download {
    background: #f1f5f9;
    border: none;
    color: #1d6bf3;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s;
    flex-shrink: 0;
}

.btn-icon-download:hover {
    background: #dbeafe;
}

.no-visuals-card {
    padding: 40px;
    text-align: center;
    color: #94a3b8;
    background: #f8fafc;
    border-radius: 16px;
    border: 1px dashed #cbd5e1;
}

/* Modal Actions Footer */
.modal-footer-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 14px;
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
}

.btn-grey-pill {
    background-color: #f1f5f9;
    color: #475569;
    border: none;
    padding: 10px 20px;
    border-radius: 24px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
}

.btn-blue-pill {
    background-color: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 10px 22px;
    border-radius: 24px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3);
}

.btn-blue-pill:hover {
    background-color: #1557b0;
}
</style>
