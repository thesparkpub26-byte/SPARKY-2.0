// Helpers shared by the search modal and the full search page.

export const PERIODS = [
  { value: '', label: 'Any time' },
  { value: 'week', label: 'Past week' },
  { value: 'month', label: 'Past month' },
  { value: 'year', label: 'Past year' },
];

export const SORTS = [
  { value: 'relevance', label: 'Best match' },
  { value: 'newest', label: 'Newest first' },
  { value: 'oldest', label: 'Oldest first' },
];

// "Articles (2)" etc., from the counts the search API returns
export const typeOptions = (kinds = {}) => [
  { value: '', label: 'All types' },
  { value: 'articles', label: `Articles${kinds.article !== undefined ? ` (${kinds.article})` : ''}` },
  { value: 'videos', label: `Videos${kinds.video !== undefined ? ` (${kinds.video})` : ''}` },
  { value: 'photos', label: `Photos${kinds.photo !== undefined ? ` (${kinds.photo})` : ''}` },
];

// Splits text into plain and highlighted parts (so nothing is ever rendered as raw HTML)
export const highlightParts = (text, stems = []) => {
  const safe = stems.filter((s) => s.length >= 2).map((s) => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'));
  if (!text || !safe.length) return [{ text: text || '', hit: false }];

  const re = new RegExp(`(?<![\\p{L}\\p{N}])(?:${safe.join('|')})[\\p{L}\\p{N}]*`, 'giu');
  const out = [];
  let last = 0;
  for (const m of text.matchAll(re)) {
    if (m.index > last) out.push({ text: text.slice(last, m.index), hit: false });
    out.push({ text: m[0], hit: true });
    last = m.index + m[0].length;
  }
  if (last < text.length) out.push({ text: text.slice(last), hit: false });
  return out;
};

// Where a result goes: photos open in the gallery, videos on their own link, the rest on the article page
export const openResult = (router, item) => {
  if (item.kind === 'photo') {
    router.push({ path: '/gallery', query: { photo: item.id } });
  } else if (item.video_url) {
    window.open(item.video_url, '_blank', 'noopener');
  } else {
    router.push(`/article/${item.id}`);
  }
};
