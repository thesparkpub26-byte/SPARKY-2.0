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

// True when the link is a YouTube video link.
export const isYouTubeUrl = (url) => Boolean(extractYouTubeId(url));

// The thumbnail image address of a YouTube video link, or an empty string.
export const youtubeThumbnail = (url) => {
    const id = extractYouTubeId(url);
    return id ? `https://img.youtube.com/vi/${id}/hqdefault.jpg` : '';
};

// The embeddable player address of a YouTube video link, or an empty string.
export const youtubeEmbedUrl = (url) => {
    const id = extractYouTubeId(url);
    return id ? `https://www.youtube.com/embed/${id}` : '';
};

// Reads one "Key: value" field out of a task's notes text (fields are separated by "|").
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

// True for the video crew's tasks (videographer, video editor) and for layout tasks linked to a video.
export const isVideoCrewTask = (task = {}) => VIDEO_CREW_TYPES.includes(task.type)
    || (task.type === 'layout' && isVideoTask(task));

// True for a section editor whose title is a broadcaster one (the Head Broadcaster).
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
