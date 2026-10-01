import { forgetLastPage } from './returnTo';

// Signs the user out: tells the server, clears the saved sign-in, then goes to the given page.
export const signOut = (router, redirect = '/') => {
    const token = localStorage.getItem('sparky_token');

    localStorage.removeItem('sparky_token');
    localStorage.removeItem('sparky_user');

    if (token) {
        fetch('/api/logout', {
            method: 'POST',
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json',
            },
        }).catch(() => {});
    }

    // The next person to sign in on this tab should start at their dashboard, not at this user's last page
    return router.push(redirect).then(forgetLastPage);
};
