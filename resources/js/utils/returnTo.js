// "Take me back to where I was."
//
// The last page a visitor looked at is remembered (per browser tab), so signing in, signing up or leaving
// the profile page puts them back on that page, e.g. the article they were reading, instead of a dashboard.
// Pages that are only steps along the way (sign-in, sign-up, verification, password reset, the profile
// itself) are never remembered, otherwise "go back" would just lead to them again.

const KEY = 'sparky_last_page';

const NOT_REMEMBERED = new Set([
    'LoginView', 'SignUpView', 'OtpView', 'ForgotPasswordView', 'ResetPasswordView',
    'ProfileView', 'UnsubscribeView', 'NotFoundView',
]);

// Only addresses on this site: a stored value can't send anyone elsewhere
const isSitePath = (path) => typeof path === 'string' && path.startsWith('/') && !path.startsWith('//');

// Reads the remembered page from session storage, or null if it cannot be read.
const read = () => { try { return sessionStorage.getItem(KEY); } catch { return null; } };

/** Remember a route as "the page I was on". Does nothing for the in-between pages listed above. */
export const rememberPage = (route) => {
    if (!route || NOT_REMEMBERED.has(route.name) || !isSitePath(route.fullPath)) return;
    try { sessionStorage.setItem(KEY, route.fullPath); } catch { /* private mode: we just won't remember */ }
};

/** The remembered page, or null. */
export const lastPage = () => {
    const path = read();
    return isSitePath(path) ? path : null;
};

// Forgets the remembered page.
export const forgetLastPage = () => {
    try { sessionStorage.removeItem(KEY); } catch { /* nothing to clear */ }
};

/** Remembers every page as it is opened. */
export const trackLastPage = (router) => {
    router.afterEach((to, from, failure) => {
        if (!failure) rememberPage(to);
    });
};
