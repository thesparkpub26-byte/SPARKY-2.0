import { forgetLastPage } from './returnTo';

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
