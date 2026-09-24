// Helpers so a failed request is never silently ignored.

// A step the rest of the flow depends on: throws (with the server's message when it gave one) unless it worked.
export const requireOk = async (response, fallbackMessage) => {
    if (response.ok) return response;

    const body = await response.json().catch(() => ({}));
    throw new Error(body.message || fallbackMessage);
};

// A follow-up step whose failure shouldn't undo what already succeeded. Resolves to '' when it worked,
// or to a short sentence describing what went wrong so it can be shown to the person.
export const followUp = async (failureText, request) => {
    try {
        const response = await request();
        if (response.ok) return '';

        const body = await response.json().catch(() => ({}));
        console.error(failureText, response.status, body);
    } catch (error) {
        console.error(failureText, error);
    }

    return failureText;
};

// The sentence added to a success message when some follow-up steps failed
export const followUpNote = (problems) => {
    const list = problems.filter(Boolean);
    return list.length ? ` Note: ${list.join(' ')} Please refresh the page and check.` : '';
};
