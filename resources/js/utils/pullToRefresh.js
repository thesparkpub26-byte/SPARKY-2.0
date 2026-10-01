// Pull-to-refresh for touch screens.
//
// The browser's own gesture never fires here: <html> and <body> don't scroll (each page scrolls inside its
// own container), so the browser sees a document that is always "at the top" of nothing. This does the same
// job: drag down while the scrolling area under your finger is at its top, release past the threshold, and
// the page reloads.

const THRESHOLD = 70; // px of pull (after resistance) needed to refresh
const MAX_PULL = 110;
const RESISTANCE = 0.5;
const START_SLOP = 8; // px of downward movement before it counts as a pull

// Places where a downward drag means something else (or where a reload would lose work)
const BLOCKED = [
  'input', 'textarea', 'select', '[contenteditable="true"]', 'iframe', 'canvas',
  '.sidebar', '[role="dialog"]', '[class*="modal"]', '[class*="overlay"]', '[class*="backdrop"]',
  '[class*="popover"]', '[class*="lightbox"]',
].join(',');

// True when the element can scroll vertically (so the page itself is not at its top).
const isScrollable = (el) => {
  const overflowY = getComputedStyle(el).overflowY;
  return (overflowY === 'auto' || overflowY === 'scroll') && el.scrollHeight > el.clientHeight;
};

// True when nothing between the finger and the page has been scrolled down
const atTop = (target) => {
  for (let el = target; el && el !== document.documentElement; el = el.parentElement) {
    if (isScrollable(el) && el.scrollTop > 0) return false;
  }
  return window.scrollY <= 0;
};

// Turns on pull-down-to-refresh on touch screens: pulling down from the top of the page reloads it.
export const enablePullToRefresh = () => {
  if (!window.matchMedia('(pointer: coarse)').matches) return;

  const indicator = document.createElement('div');
  indicator.className = 'ptr-indicator';
  indicator.setAttribute('aria-hidden', 'true');
  indicator.innerHTML = '<span class="ptr-spinner"></span>';
  document.body.appendChild(indicator);

  let startX = 0;
  let startY = 0;
  let distance = 0;
  let pulling = false;
  let refreshing = false;

  // Moves and rotates the pull indicator while the finger is dragging.
  const show = (offset, progress) => {
    indicator.style.transition = 'none';
    indicator.style.transform = `translate(-50%, ${offset}px)`;
    indicator.style.opacity = String(Math.min(1, progress));
    indicator.firstChild.style.transform = `rotate(${progress * 270}deg)`;
  };

  // Slides the pull indicator back out of view.
  const hide = () => {
    indicator.style.transition = 'transform 0.2s ease, opacity 0.2s ease';
    indicator.style.transform = 'translate(-50%, -60px)';
    indicator.style.opacity = '0';
  };

  // Follows the finger while it moves; treats a pull down from the top of the page as the refresh gesture.
  const onMove = (event) => {
    const touch = event.touches[0];
    const dy = touch.clientY - startY;
    const dx = touch.clientX - startX;

    if (!pulling) {
      // Sideways or upward movement is an ordinary scroll or swipe, so leave it alone
      if (dy < 0 || Math.abs(dx) > Math.abs(dy)) return stop();
      if (dy < START_SLOP) return;
      pulling = true;
    }

    if (event.cancelable) event.preventDefault(); // stops the inner scroller from rubber-banding
    distance = Math.min(MAX_PULL, dy * RESISTANCE);
    show(distance - 40, distance / THRESHOLD);
    indicator.classList.toggle('ptr-ready', distance >= THRESHOLD);
  };

  // Stops listening for the gesture and resets its state.
  const stop = () => {
    document.removeEventListener('touchmove', onMove);
    document.removeEventListener('touchend', onEnd);
    document.removeEventListener('touchcancel', onEnd);
    pulling = false;
    distance = 0;
  };

  // When the finger lifts: refreshes the page if the pull was long enough, otherwise hides the indicator.
  const onEnd = () => {
    const shouldRefresh = pulling && distance >= THRESHOLD;
    stop();
    indicator.classList.remove('ptr-ready');

    if (!shouldRefresh) return hide();

    refreshing = true;
    indicator.classList.add('ptr-refreshing');
    show(THRESHOLD - 40, 1);
    window.location.reload();
  };

  document.addEventListener('touchstart', (event) => {
    if (refreshing || event.touches.length !== 1) return;
    if (document.documentElement.classList.contains('mobile-nav-open')) return;
    if (window.location.pathname.startsWith('/booklet')) return; // the issue reader owns its gestures

    const target = event.target;
    if (!(target instanceof Element) || target.closest(BLOCKED) || !atTop(target)) return;

    startX = event.touches[0].clientX;
    startY = event.touches[0].clientY;
    document.addEventListener('touchmove', onMove, { passive: false });
    document.addEventListener('touchend', onEnd);
    document.addEventListener('touchcancel', onEnd);
  }, { passive: true });
};
