// How the review screens name and colour each person who touches an article.

const ROLES = {
    section_editor: { label: 'Section Editor', color: '#f2b705' },
    copyreader: { label: 'Copyreader', color: '#8b5cf6' },
    eic: { label: 'Editor-in-Chief', color: '#2f9e1f' },
    admin: { label: 'Admin', color: '#64748b' },
    staff_writer: { label: 'Writer', color: '#2f6bf0' },
};

// The label and colour for a review role ('section_editor', 'copyreader', 'eic'...).
export const roleInfo = (role) => ROLES[role] || { label: role ? String(role).replace(/_/g, ' ') : 'Staff', color: '#2f6bf0' };

// The picture to show for a person, or a generated one with their initials.
export const avatarFor = (user) => {
    if (user?.profile_picture) return '/storage/' + user.profile_picture;
    if (user?.profile_picture_url) return user.profile_picture_url;
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'User')}&background=dbeafe&color=1d4ed8&size=96`;
};

const timeOf = (date) => date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

// "5:41 PM · Today", "5:41 PM · Yesterday" or "5:41 PM · Apr 12".
export const stamp = (value) => {
    const date = new Date(value);
    if (!value || isNaN(date.getTime())) return '';

    const midnight = (d) => new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime();
    const daysAgo = Math.round((midnight(new Date()) - midnight(date)) / 86400000);
    const day = daysAgo === 0 ? 'Today'
        : daysAgo === 1 ? 'Yesterday'
        : date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    return `${timeOf(date)} · ${day}`;
};

// "Apr 12, 2026 · 9:25 PM"
export const fullStamp = (value) => {
    const date = new Date(value);
    if (!value || isNaN(date.getTime())) return '';
    return `${date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })} · ${timeOf(date)}`;
};
