// PDF.js (CDN) — renders the first page of a PDF to a JPEG data URL, used as an issue's cover.
const PDFJS_VERSION = '3.11.174';

// Loads the PDF.js library from the CDN (once) and returns it.
const loadPdfJs = () => new Promise((resolve, reject) => {
    if (window.pdfjsLib) {
        resolve(window.pdfjsLib);
        return;
    }
    const script = document.createElement('script');
    script.src = `https://cdnjs.cloudflare.com/ajax/libs/pdf.js/${PDFJS_VERSION}/pdf.min.js`;
    script.onload = () => {
        window.pdfjsLib.GlobalWorkerOptions.workerSrc = `https://cdnjs.cloudflare.com/ajax/libs/pdf.js/${PDFJS_VERSION}/pdf.worker.min.js`;
        resolve(window.pdfjsLib);
    };
    script.onerror = () => reject(new Error('Could not load PDF renderer.'));
    document.head.appendChild(script);
});

// A finished cover is kept in this browser, so an issue's PDF (often tens of MB) is only opened once per
// visitor instead of on every visit. An edited issue gets a new file name, so it is never stale.
const CACHE_PREFIX = 'sparky_cover:';

// The saved cover image for a PDF address, or null if there is none or storage is blocked.
const readCached = (pdfUrl) => {
    try {
        return localStorage.getItem(CACHE_PREFIX + pdfUrl);
    } catch {
        return null;
    }
};

// Saves a drawn cover image so the next visit does not have to draw it again.
const writeCached = (pdfUrl, dataUrl) => {
    try {
        localStorage.setItem(CACHE_PREFIX + pdfUrl, dataUrl);
    } catch {
        // Storage full or blocked: the cover is simply drawn again next time.
    }
};

// Covers are drawn two at a time, so a page of issues doesn't freeze a phone
const MAX_AT_ONCE = 2;
let running = 0;
const waiting = [];

// Waits for a free slot, so only a few covers are drawn at the same time.
const takeTurn = () => new Promise((resolve) => {
    if (running < MAX_AT_ONCE) {
        running++;
        resolve();
    } else {
        waiting.push(resolve);
    }
});

// Gives the slot to the next waiting cover, or frees it.
const endTurn = () => {
    const next = waiting.shift();
    if (next) next();
    else running--;
};

// Draws page 1 of a PDF onto a canvas and returns it as an image (data URL).
const draw = async (pdfUrl, scale) => {
    const pdfjsLib = await loadPdfJs();
    // Only the parts of the file that page 1 needs are downloaded, and nothing more once it is drawn
    const pdf = await pdfjsLib.getDocument({ url: pdfUrl, disableAutoFetch: true }).promise;
    try {
        const page = await pdf.getPage(1);
        const viewport = page.getViewport({ scale });
        const canvas = document.createElement('canvas');
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
        return canvas.toDataURL('image/jpeg', 0.75);
    } finally {
        pdf.destroy();
    }
};

const inFlight = new Map();

// Returns the cover image of a PDF: from the saved copy if there is one, otherwise drawn once and saved.
export const renderPdfCover = (pdfUrl, scale = 1.1) => {
    const cached = readCached(pdfUrl);
    if (cached) return Promise.resolve(cached);

    // The same issue asked for twice at once (home page + list) is drawn once
    if (inFlight.has(pdfUrl)) return inFlight.get(pdfUrl);

    // Waits for its turn, draws the cover, saves it, and always gives the turn back.
    const job = (async () => {
        await takeTurn();
        try {
            const cover = await draw(pdfUrl, scale);
            writeCached(pdfUrl, cover);
            return cover;
        } finally {
            endTurn();
            inFlight.delete(pdfUrl);
        }
    })();

    inFlight.set(pdfUrl, job);
    return job;
};
