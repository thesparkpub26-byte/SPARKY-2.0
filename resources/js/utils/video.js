// Shared helpers for the broadcasting team's video workflow.

export const VIDEO_SECTION = 'Radio Broadcasting';
export const VIDEO_CATEGORIES = ['Documentary', 'Reel', 'Telesiklab'];

// Task types the crew (videographer / video editor) are assigned. They can view a
// task but never open the workspace — only the assigned news presenter submits.
export const VIDEO_CREW_TYPES = ['videography', 'video_editing'];

// Pulls the 11-character video id out of any common YouTube URL shape
// (watch?v=, youtu.be/, /embed/, /shorts/, /live/).
export const extractYouTubeId = (url) => {
    if (!url || typeof url !== 'string') return '';
    const match = url.trim().match(/(?:youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/|v\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/i);
    return match ? match[1] : '';
};

export const isYouTubeUrl = (url) => Boolean(extractYouTubeId(url));

export const youtubeThumbnail = (url) => {
    const id = extractYouTubeId(url);
    return id ? `https://img.youtube.com/vi/${id}/hqdefault.jpg` : '';
};

export const youtubeEmbedUrl = (url) => {
    const id = extractYouTubeId(url);
    return id ? `https://www.youtube.com/embed/${id}` : '';
};

export const parseNotesField = (notes, key) => {
    if (!notes || typeof notes !== 'string') return '';
    const match = notes.match(new RegExp(`${key}:\\s*([^|]+)`, 'i'));
    return match ? match[1].trim() : '';
};

// A task belongs to the video workflow when it is a crew task, is linked to a video,
// or was assigned under the Radio Broadcasting section.
export const isVideoTask = (task = {}) => {
    if (VIDEO_CREW_TYPES.includes(task.type)) return true;
    if (task.article?.type === 'video' || task.raw?.article?.type === 'video') return true;
    const section = task.section?.name || (typeof task.section === 'string' ? task.section : '')
        || parseNotesField(task.notes, 'Section');
    return section.toLowerCase() === VIDEO_SECTION.toLowerCase();
};

export const isVideoCrewTask = (task = {}) => VIDEO_CREW_TYPES.includes(task.type)
    || (task.type === 'layout' && isVideoTask(task));

export const isBroadcastHead = (user = {}) => {
    const roles = `${user.secondary_role || ''} ${user.tertiary_role || ''}`.toLowerCase();
    return user.role === 'section_editor' && roles.includes('broadcaster');
};

export const VIDEO_CREDIT_LABELS = {
    reporter: 'Reporter',
    scriptwriter: 'Scriptwriter',
    videographer: 'Videographer',
    video_editor: 'Video Editor',
};

// Videos a user was credited on by the Head / Assistant Head Broadcaster. Credited people
// see them in "My Videos" (broadcasters) or "My Articles" (writers, section editors, EIC).
export const fetchCreditedVideos = async (userId) => {
    if (!userId) return [];
    try {
        const res = await fetch(`/api/articles?type=video&credited_to=${userId}`, {
            headers: { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json' },
        });
        if (!res.ok) return [];
        const data = await res.json();
        return Array.isArray(data) ? data : [];
    } catch {
        return [];
    }
};

// Shape ArticlePreviewModal expects for a (read-only) video preview
export const buildVideoPreviewData = (item = {}) => ({
    ...item,
    raw_status: item.status,
    attached_files: item.cover_image
        ? [{ name: String(item.cover_image).split('/').pop() || 'thumbnail', type: 'image', url: item.cover_image }]
        : [],
});
