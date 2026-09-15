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

    return router.push(redirect);
};
