// PDF.js (CDN) — renders the first page of a PDF to a JPEG data URL, used as an issue's cover.
const PDFJS_VERSION = '3.11.174';

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

export const renderPdfCover = async (pdfUrl, scale = 1.3) => {
    const pdfjsLib = await loadPdfJs();
    const pdf = await pdfjsLib.getDocument(pdfUrl).promise;
    const page = await pdf.getPage(1);
    const viewport = page.getViewport({ scale });
    const canvas = document.createElement('canvas');
    canvas.width = viewport.width;
    canvas.height = viewport.height;
    await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
    return canvas.toDataURL('image/jpeg', 0.85);
};
