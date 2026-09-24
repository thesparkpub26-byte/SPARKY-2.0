// Who is signed in, as the SERVER sees it.
//
// The browser keeps a copy of the user in localStorage for display, but anyone can edit that copy in the
// developer tools (changing "reader" to "eic", say). Route guards therefore never trust it: they ask the
// server, which knows the truth from the sign-in token. The real protection is still the API (every staff
// endpoint checks the role again); this stops people from even opening pages that aren't theirs.

export const STAFF_ROLES = ['admin', 'eic', 'section_editor', 'staff_writer', 'staff_artist', 'staff_broadcaster'];

const DASHBOARDS = {
    admin: '/admin',
    eic: '/eic',
    section_editor: '/editor',
    staff_writer: '/writer',
    staff_artist: '/artist',
    staff_broadcaster: '/broadcaster',
};

// Where each role belongs (readers go to the public home page)
export const dashboardFor = (role) => DASHBOARDS[role] || '/';

const FRESH_FOR_MS = 30 * 1000;
let cached = { token: null, user: null, at: 0 };

export const forgetSession = () => { cached = { token: null, user: null, at: 0 }; };

// The signed-in user according to the server, or null (no token, expired token, deactivated, or server down).
// Re-checked at most every 30 seconds so moving around the dashboards stays quick.
export const verifiedUser = async () => {
    const token = localStorage.getItem('sparky_token');
    if (!token) return null;

    if (cached.token === token && cached.user && Date.now() - cached.at < FRESH_FOR_MS) {
        return cached.user;
    }

    try {
        const response = await fetch('/api/me', { headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' } });

        if (!response.ok) {
            if ([401, 403].includes(response.status)) {
                localStorage.removeItem('sparky_token');
                localStorage.removeItem('sparky_user');
            }
            forgetSession();
            return null;
        }

        const user = await response.json();
        if (!user || !user.is_active) {
            forgetSession();
            return null;
        }

        // Replace the browser's copy with the truth, so the pages that read it show the right person and role
        localStorage.setItem('sparky_user', JSON.stringify(user));
        cached = { token, user, at: Date.now() };

        return user;
    } catch {
        forgetSession();
        return null;
    }
};
