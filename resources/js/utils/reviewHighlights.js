// Highlighted review comments. A comment remembers the words it was written on (and where they were), and the
// words are marked with the browser's CSS Custom Highlight API, so the article's own HTML is never touched
// and nothing extra can end up in the published text. Browsers without that API still list the comments, just
// without the yellow marks.

import '../../css/reviewComments.css';

export const supportsHighlights = typeof CSS !== 'undefined' && !!CSS.highlights && typeof Highlight !== 'undefined';

const OPEN = 'review-open';
const ACTIVE = 'review-active';
const DRAFT = 'review-draft';

// Every text node under the element, with where each one starts in the element's text
const textNodes = (root) => {
    const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
    const nodes = [];
    let offset = 0;
    for (let node = walker.nextNode(); node; node = walker.nextNode()) {
        nodes.push({ node, start: offset });
        offset += node.nodeValue.length;
    }
    return nodes;
};

// How far into the element's text a point (a node and an offset inside it) is
const offsetOf = (root, container, offsetInside) => {
    const probe = document.createRange();
    probe.selectNodeContents(root);
    probe.setEnd(container, offsetInside);
    return probe.toString().length;
};

// The words the person selected inside the element: { quote, start, end }, or null when nothing useful is selected.
export const selectionAnchor = (root) => {
    const selection = window.getSelection();
    if (!root || !selection || selection.rangeCount === 0 || selection.isCollapsed) return null;

    const range = selection.getRangeAt(0);
    if (!root.contains(range.startContainer) || !root.contains(range.endContainer)) return null;

    const quote = range.toString();
    if (!quote.trim()) return null;

    const start = offsetOf(root, range.startContainer, range.startOffset);
    return { quote, start, end: start + quote.length };
};

// Where the caret is inside the element's text (used to find the comment that was clicked), or null.
export const caretOffset = (root) => {
    const selection = window.getSelection();
    if (!root || !selection || selection.rangeCount === 0 || !selection.isCollapsed) return null;
    if (!root.contains(selection.anchorNode)) return null;
    return offsetOf(root, selection.anchorNode, selection.anchorOffset);
};

// A DOM range over the element's text from start to end.
const rangeFor = (root, nodes, start, end) => {
    const find = (position, preferEnd) => nodes.find(({ node, start: from }) => {
        const to = from + node.nodeValue.length;
        return preferEnd ? position > from && position <= to : position >= from && position < to;
    }) || nodes[nodes.length - 1];

    const first = find(start, false);
    const last = find(end, true);
    if (!first || !last) return null;

    const range = document.createRange();
    range.setStart(first.node, Math.min(start - first.start, first.node.nodeValue.length));
    range.setEnd(last.node, Math.min(end - last.start, last.node.nodeValue.length));
    return range;
};

// Where a comment's words are in the element's text now: the saved place if they are still there, otherwise
// the closest place the same words appear (the writer may have edited around them), otherwise null.
export const locate = (text, comment) => {
    const quote = comment.quote || '';
    if (!quote) return null;

    const saved = comment.start_offset;
    if (saved != null && text.substr(saved, quote.length) === quote) {
        return { start: saved, end: saved + quote.length };
    }

    let best = null;
    for (let at = text.indexOf(quote); at !== -1; at = text.indexOf(quote, at + 1)) {
        if (best === null || Math.abs(at - (saved ?? 0)) < Math.abs(best - (saved ?? 0))) best = at;
    }
    return best === null ? null : { start: best, end: best + quote.length };
};

// Marks the open comments in the element (the chosen one stronger, and the passage being commented on right now).
// Returns the comments that could not be found in the text any more, as a set of ids.
export const paintHighlights = (root, comments, { activeId = null, draft = null } = {}) => {
    clearHighlights();
    const lost = new Set();
    if (!root) return lost;

    const text = root.textContent;
    const nodes = textNodes(root);
    const open = [];
    const active = [];
    const drafted = [];

    for (const comment of comments) {
        if (comment.status !== 'open') continue;
        const spot = locate(text, comment);
        if (!spot) {
            lost.add(comment.id);
            continue;
        }
        const range = nodes.length ? rangeFor(root, nodes, spot.start, spot.end) : null;
        if (range) (comment.id === activeId ? active : open).push(range);
    }

    if (draft) {
        const range = nodes.length ? rangeFor(root, nodes, draft.start, draft.end) : null;
        if (range) drafted.push(range);
    }

    if (supportsHighlights) {
        if (open.length) CSS.highlights.set(OPEN, new Highlight(...open));
        if (active.length) CSS.highlights.set(ACTIVE, new Highlight(...active));
        if (drafted.length) CSS.highlights.set(DRAFT, new Highlight(...drafted));
    }

    return lost;
};

// Removes every review mark from the page.
export const clearHighlights = () => {
    if (!supportsHighlights) return;
    [OPEN, ACTIVE, DRAFT].forEach((name) => CSS.highlights.delete(name));
};

// Scrolls the comment's words into view.
export const scrollToComment = (root, comment) => {
    if (!root || !comment) return;
    const spot = locate(root.textContent, comment);
    const nodes = textNodes(root);
    const range = spot && nodes.length ? rangeFor(root, nodes, spot.start, spot.end) : null;
    range?.startContainer.parentElement?.scrollIntoView({ block: 'center', behavior: 'smooth' });
};

// The comment whose words contain a position in the text, if any (used when someone clicks highlighted text).
export const commentAt = (root, comments, position) => {
    if (position == null) return null;
    const text = root.textContent;
    return comments.find((comment) => {
        if (comment.status !== 'open') return false;
        const spot = locate(text, comment);
        return spot && position >= spot.start && position <= spot.end;
    }) || null;
};
