<template>
    <div class="booklet-page">
        <header class="booklet-header">
            <button class="booklet-back-btn" type="button" @click="goBack">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"></path><path d="m12 19-7-7 7-7"></path></svg>
                Back
            </button>
            <h1 class="booklet-title">{{ issue?.title || 'Published Issue' }}</h1>
            <div style="width: 90px;"></div>
        </header>

        <div v-if="loading" class="booklet-status">
            <div class="booklet-spinner"></div>
            <p>{{ loadStage === 'fetching' ? 'Loading issue…' : `Preparing booklet… ${loadProgress}%` }}</p>
        </div>

        <div v-else-if="error" class="booklet-status">
            <p class="booklet-error">{{ error }}</p>
        </div>

        <div v-else class="booklet-viewer">
            <div class="book-stage">
                <div class="book" :class="{ closed: isCoverSpread }">
                    <template v-if="isCoverSpread">
                        <div class="book-page cover-page">
                            <img v-if="leftPageSrc" :src="leftPageSrc" alt="" />
                        </div>
                    </template>
                    <template v-else>
                        <div class="book-page left-page">
                            <img v-if="leftPageSrc" :src="leftPageSrc" alt="" />
                        </div>
                        <div class="book-page right-page">
                            <img v-if="rightPageSrc" :src="rightPageSrc" alt="" />
                        </div>
                    </template>
                    <div
                        v-if="flip.active"
                        class="flip-page"
                        :class="[flip.direction, { wide: flip.wide }]"
                    >
                        <div class="flip-face flip-front">
                            <img v-if="flip.frontSrc" :src="flip.frontSrc" alt="" />
                        </div>
                        <div class="flip-face flip-back">
                            <img v-if="flip.backSrc" :src="flip.backSrc" alt="" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="book-controls">
                <button type="button" class="book-nav-btn" @click="prevSpread" :disabled="spreadIndex === 0 || flip.active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Prev
                </button>
                <span class="book-page-label">{{ pageLabel }}</span>
                <button type="button" class="book-nav-btn" @click="nextSpread" :disabled="isLastSpread || flip.active">
                    Next
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

// This view is normally opened as a fresh browser tab (window.open), which has
// no prior history to go "back" to — router.back()/history.back() would silently
// no-op there. Close the tab in that case; only navigate back within the SPA
// history when there actually is some (i.e. it was reached via in-app routing).
const goBack = () => {
    if (window.history.length > 1) {
        router.back();
        return;
    }
    window.close();
    setTimeout(() => {
        router.push('/');
    }, 300);
};

const issue = ref(null);
const loading = ref(true);
const loadStage = ref('fetching'); // 'fetching' | 'rendering'
const loadProgress = ref(0);
const error = ref('');

const pages = ref([]); // rendered page images (data URLs), 0-indexed

// spreadIndex 0 is the cover shown alone, like a closed book. spreadIndex N (N>=1)
// shows pages[2N-1] on the left and pages[2N] on the right — i.e. page 2 sits to
// the left of page 3 on the first opened spread, page 4 to the left of page 5 next, etc.
const spreadIndex = ref(0);

const FLIP_DURATION = 700; // ms, kept in sync with the CSS transition below

const flip = ref({ active: false, direction: 'forward', frontSrc: '', backSrc: '', wide: false });

// The pages are already-rendered data URLs, but a browser still has to decode an image
// the first time it's painted — decode() lets us do that decode ahead of time so the flap's
// back face is already paintable the instant the CSS rotation reveals it, instead of
// flashing in late mid-flip. Once a src has been decoded here it stays fast on reuse.
const decodedSrcs = new Set();
// Loads and decodes a page image ahead of time so flipping pages does not flicker.
const preloadImage = (src) => {
    if (!src || decodedSrcs.has(src)) return Promise.resolve();
    const img = new Image();
    img.src = src;
    const done = img.decode ? img.decode().catch(() => {}) : new Promise((resolve) => {
        img.onload = resolve;
        img.onerror = resolve;
    });
    return Promise.resolve(done).then(() => { decodedSrcs.add(src); });
};

const isCoverSpread = computed(() => spreadIndex.value === 0);

// Plain functions (not computeds) so the same left/right-for-a-given-spread logic can be
// reused to preload the pages a turn is *about* to reveal, before spreadIndex actually moves.
const leftSrcFor = (L) => (L === 0 ? pages.value[0] || null : pages.value[L * 2 - 1] || null);
// The image shown on the right page of a spread (none on the cover spread).
const rightSrcFor = (L) => (L === 0 ? null : pages.value[L * 2] || null);

const leftPageSrc = computed(() => leftSrcFor(spreadIndex.value));
const rightPageSrc = computed(() => rightSrcFor(spreadIndex.value));

const totalSpreads = computed(() => 1 + Math.ceil(Math.max(0, pages.value.length - 1) / 2));
const isLastSpread = computed(() => spreadIndex.value >= totalSpreads.value - 1);
const pageLabel = computed(() => {
    if (isCoverSpread.value) return `Page 1 of ${pages.value.length}`;
    const leftNum = spreadIndex.value * 2;
    const rightNum = Math.min(leftNum + 1, pages.value.length);
    return `Page ${leftNum}${rightNum > leftNum ? '-' + rightNum : ''} of ${pages.value.length}`;
});

// Flips forward to the next two-page spread with the page-turn animation.
const nextSpread = () => {
    if (flip.value.active || isLastSpread.value) return;
    const wasCover = isCoverSpread.value;
    const frontSrc = wasCover ? pages.value[0] : rightPageSrc.value;
    const backSrc = pages.value[spreadIndex.value * 2 + 1] || null;
    const newRightSrc = rightSrcFor(spreadIndex.value + 1); // revealed the instant the flap lands, not shown by it directly
    // Kick decoding off in parallel rather than waiting on it — the background warm-up
    // (below) means these are almost always already decoded by the time they're needed,
    // and blocking the click on it would just add a delay before the flip even starts.
    preloadImage(frontSrc);
    preloadImage(backSrc);
    preloadImage(newRightSrc);

    // "wide" is fixed for the whole turn (not toggled mid-flight): the flap's width is a
    // constant pixel value equal to the closed book's width, independent of the book
    // container's own resize, so it never jumps and never exposes the page underneath early.
    flip.value = { active: true, direction: 'forward', frontSrc, backSrc, wide: wasCover };
    // Swap the underlying page only once the flap has fully finished rotating — the flap's
    // own back face already shows the new content while it turns, so there's no need (and
    // no visual benefit) to update the page underneath any earlier.
    setTimeout(() => {
        spreadIndex.value += 1;
        flip.value.active = false;
    }, FLIP_DURATION);
};

// Flips back to the previous two-page spread with the page-turn animation.
const prevSpread = () => {
    if (flip.value.active || spreadIndex.value === 0) return;
    const targetIsCover = spreadIndex.value - 1 === 0;
    const frontSrc = leftPageSrc.value;
    const backSrc = targetIsCover ? pages.value[0] : (pages.value[(spreadIndex.value - 1) * 2] || null);
    const newLeftSrc = leftSrcFor(spreadIndex.value - 1);
    preloadImage(frontSrc);
    preloadImage(backSrc);
    preloadImage(newLeftSrc);

    flip.value = { active: true, direction: 'backward', frontSrc, backSrc, wide: targetIsCover };
    setTimeout(() => {
        spreadIndex.value -= 1;
        flip.value.active = false;
    }, FLIP_DURATION);
};

// Keyboard navigation
const handleKeydown = (e) => {
    if (e.key === 'ArrowRight') nextSpread();
    if (e.key === 'ArrowLeft') prevSpread();
};

// ── PDF.js loading + rendering ──────────────────────────────────────────────
const PDFJS_VERSION = '3.11.174';
// Loads the PDF.js library (once) and returns it.
const loadPdfJs = () => {
    return new Promise((resolve, reject) => {
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
        script.onerror = () => reject(new Error('Could not load the PDF renderer. Check your internet connection.'));
        document.head.appendChild(script);
    });
};

// Renders every page of the issue's PDF into an image.
const renderPdfToImages = async (pdfUrl) => {
    const pdfjsLib = await loadPdfJs();
    const pdf = await pdfjsLib.getDocument(pdfUrl).promise;
    const images = [];
    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
        const page = await pdf.getPage(pageNum);
        const viewport = page.getViewport({ scale: 1.5 });
        const canvas = document.createElement('canvas');
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        const ctx = canvas.getContext('2d');
        await page.render({ canvasContext: ctx, viewport }).promise;
        images.push(canvas.toDataURL('image/jpeg', 0.85));
        loadProgress.value = Math.round((pageNum / pdf.numPages) * 100);
    }
    return images;
};

onMounted(async () => {
    window.addEventListener('keydown', handleKeydown);

    const issueId = route.params.id;
    try {
        const response = await fetch(`/api/reader/issues/${issueId}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('sparky_token')}`,
                Accept: 'application/json',
            },
        });
        if (!response.ok) throw new Error('Could not load this published issue.');
        issue.value = await response.json();

        loadStage.value = 'rendering';
        pages.value = await renderPdfToImages(issue.value.pdf_url);

        // Warm the decode cache for every page in the background (not awaited — the viewer
        // is already usable) so page turns later on rarely hit an undecoded image at all.
        (async () => {
            for (const src of pages.value) await preloadImage(src);
        })();
    } catch (e) {
        error.value = e.message || 'Something went wrong while preparing the booklet.';
    } finally {
        loading.value = false;
    }
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});
</script>

<style scoped>
.booklet-page {
    min-height: 100vh;
    background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
    display: flex;
    flex-direction: column;
    font-family: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.booklet-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 28px;
    color: #ffffff;
}

.booklet-back-btn {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #ffffff;
    padding: 10px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.2s;
}

.booklet-back-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.booklet-title {
    font-size: 16px;
    font-weight: 800;
    margin: 0;
    text-align: center;
}

.booklet-status {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #cbd5e1;
    gap: 16px;
    font-size: 14px;
}

.booklet-error {
    color: #fca5a5;
    font-weight: 600;
}

.booklet-spinner {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 3px solid rgba(255, 255, 255, 0.15);
    border-top-color: #ffffff;
    animation: booklet-spin 0.8s linear infinite;
}

@keyframes booklet-spin {
    to { transform: rotate(360deg); }
}

.booklet-viewer {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 26px;
    padding: 20px;
}

.book-stage {
    perspective: 2400px;
}

.book {
    position: relative;
    display: flex;
    width: min(900px, 86vw);
    height: min(620px, 70vh);
    background: #ffffff;
    border-radius: 6px;
    box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.6);
    overflow: hidden;
    transition: width 0.35s ease;
}

.book.closed {
    /* exactly half the open width, so a flap sized 100% of this equals 50% of the open book */
    width: calc(min(900px, 86vw) / 2);
}

.book-page.cover-page {
    width: 100%;
}

.book-page {
    width: 50%;
    height: 100%;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.book-page img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.left-page {
    border-right: 1px solid #e2e8f0;
    box-shadow: inset -8px 0 16px -12px rgba(0, 0, 0, 0.25);
}

.right-page {
    box-shadow: inset 8px 0 16px -12px rgba(0, 0, 0, 0.25);
}

.flip-page {
    position: absolute;
    top: 0;
    width: 50%;
    height: 100%;
    transform-style: preserve-3d;
    z-index: 5;
}

/* The back face only becomes visible once the flap has rotated past 90deg — that's a hard
   constraint of a two-sided flip (any earlier and it'd show as a mirror image), so it can't
   start "revealing" sooner. What we control is how much of the 0.7s that first 90deg eats up:
   these keyframes cross it by 35% of the duration (~245ms) instead of the midpoint, leaving
   a long, slow, deliberate reveal for the remaining 65% instead of a rushed half-and-half split. */
.flip-page.forward {
    right: 0;
    transform-origin: left center;
    animation: flip-forward 0.7s linear forwards;
}

.flip-page.backward {
    left: 0;
    transform-origin: right center;
    animation: flip-backward 0.7s linear forwards;
}

@keyframes flip-forward {
    0% { transform: rotateY(0deg); }
    35% { transform: rotateY(-90deg); }
    100% { transform: rotateY(-180deg); }
}

@keyframes flip-backward {
    0% { transform: rotateY(0deg); }
    35% { transform: rotateY(90deg); }
    100% { transform: rotateY(180deg); }
}

/* Fixed pixel width (not a %), so it stays put while the .book container resizes
   underneath it during a cover<->spread transition — a %-based width would track the
   parent's animating size and jump/distort mid-rotation, popping the next page into
   view before the flip finishes. */
.flip-page.forward.wide {
    left: 0;
    right: auto;
    width: calc(min(900px, 86vw) / 2);
}

.flip-page.backward.wide {
    width: calc(min(900px, 86vw) / 2);
}

.flip-face {
    position: absolute;
    inset: 0;
    background: #ffffff;
    backface-visibility: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
}

.flip-face img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.flip-face.flip-back {
    transform: rotateY(180deg);
}

.book-controls {
    display: flex;
    align-items: center;
    gap: 20px;
    color: #ffffff;
}

.book-nav-btn {
    background: #ffffff;
    color: #0f172a;
    border: none;
    padding: 12px 22px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}

.book-nav-btn:hover:not(:disabled) {
    background: #e2e8f0;
}

.book-nav-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.book-page-label {
    font-size: 13px;
    font-weight: 700;
    color: #cbd5e1;
    min-width: 140px;
    text-align: center;
}

@media (max-width: 640px) {
    .book {
        width: 92vw;
        height: 60vh;
    }

    .book.closed {
        width: calc(92vw / 2);
    }

    .flip-page.forward.wide,
    .flip-page.backward.wide {
        width: calc(92vw / 2);
    }
}
</style>
