// Turns an article's stored rich-text HTML into safe display blocks and places the article's
// media uploads among them.

const BLOCK_TAGS = new Set(['DIV', 'P', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'UL', 'OL', 'BLOCKQUOTE', 'PRE']);
const KEEP_TAGS = new Set(['B', 'STRONG', 'I', 'EM', 'U', 'S', 'BR', 'A', 'SPAN', 'UL', 'OL', 'LI', 'BLOCKQUOTE', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'P', 'DIV']);
const DROP_TAGS = new Set(['SCRIPT', 'STYLE', 'IFRAME', 'OBJECT', 'EMBED', 'NOSCRIPT', 'TEMPLATE']);

const escapeHtml = (text) => text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

// Rebuilds a node as a string, keeping only harmless tags and (safe) link hrefs
const clean = (node) => {
    if (node.nodeType === Node.TEXT_NODE) return escapeHtml(node.textContent);
    if (node.nodeType !== Node.ELEMENT_NODE || DROP_TAGS.has(node.tagName)) return '';

    const inner = Array.from(node.childNodes).map(clean).join('');
    if (!KEEP_TAGS.has(node.tagName)) return inner;

    const tag = node.tagName.toLowerCase();
    if (tag === 'br') return '<br>';
    if (tag === 'a') {
        const href = node.getAttribute('href') || '';
        return /^(https?:|mailto:)/i.test(href)
            ? `<a href="${escapeHtml(href).replace(/"/g, '&quot;')}" target="_blank" rel="noopener noreferrer">${inner}</a>`
            : inner;
    }
    return `<${tag}>${inner}</${tag}>`;
};

const isBlank = (html) => html.replace(/<br\s*\/?>/gi, '').replace(/&nbsp;|\s/g, '').replace(/<[^>]*>/g, '') === '';

// Each non-empty top-level block of the article is one paragraph; empty spacer lines are dropped.
export const paragraphsOf = (html) => {
    const body = new DOMParser().parseFromString(`<body>${html || ''}</body>`, 'text/html').body;
    const blocks = [];
    let inline = '';

    const flushInline = () => {
        // Loose text (e.g. plain-text articles) is split on line breaks into paragraphs
        inline.split(/(?:<br>\s*){1,}|\n+/i).forEach((part) => {
            if (!isBlank(part)) blocks.push({ tag: 'p', html: part.trim() });
        });
        inline = '';
    };

    body.childNodes.forEach((node) => {
        if (node.nodeType === Node.ELEMENT_NODE && BLOCK_TAGS.has(node.tagName)) {
            flushInline();
            const inner = Array.from(node.childNodes).map(clean).join('');
            if (isBlank(inner)) return;
            const tag = node.tagName.toLowerCase();
            blocks.push({ tag: /^(h[1-6]|ul|ol|blockquote)$/.test(tag) ? tag : 'p', html: inner });
        } else {
            inline += clean(node);
        }
    });
    flushInline();

    return blocks;
};

// First photo goes on top (returned separately). The second follows the 2nd paragraph and the third
// the 4th; if the article is too short for that, the leftover photos just follow each other at the end.
export const buildArticleBody = (html, media = []) => {
    const paragraphs = paragraphsOf(html);
    const [, second, third] = media;
    const out = [];
    let secondPlaced = false;
    let thirdPlaced = false;

    paragraphs.forEach((paragraph, i) => {
        out.push({ type: 'text', ...paragraph });
        if (i + 1 === 2 && second) { out.push({ type: 'image', src: second }); secondPlaced = true; }
        if (i + 1 === 4 && third) { out.push({ type: 'image', src: third }); thirdPlaced = true; }
    });
    if (second && !secondPlaced) out.push({ type: 'image', src: second });
    if (third && !thirdPlaced) out.push({ type: 'image', src: third });

    return { cover: media[0] || null, blocks: out };
};
