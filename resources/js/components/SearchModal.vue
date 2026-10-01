<template>
  <Transition name="search-fade">
    <div v-if="open" class="search-overlay" @mousedown.self="close">
      <div class="search-modal" role="dialog" aria-modal="true" aria-label="Smart search">
        <!-- Smart search bar -->
        <div class="search-bar">
          <svg class="search-bar-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
          <input
            ref="inputEl"
            v-model="query"
            class="search-input"
            type="text"
            maxlength="120"
            autocomplete="off"
            spellcheck="false"
            placeholder="Search articles, topics, authors…"
            @keydown.down.prevent="move(1)"
            @keydown.up.prevent="move(-1)"
            @keydown.enter.prevent="onEnter"
            @keydown.esc="close"
          >
          <button v-if="query" class="search-clear" type="button" title="Clear" @click="clearQuery">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg>
          </button>
          <button class="search-close" type="button" title="Close (Esc)" @click="close">Esc</button>
        </div>

        <!-- Smart filter: shown once there is a query -->
        <div v-if="searched" class="search-filters">
          <div class="search-chips">
            <button type="button" :class="['search-chip', { active: !category }]" @click="setCategory('')">
              All <span v-if="!category">{{ total }}</span>
            </button>
            <button
              v-for="c in categories"
              :key="c.name"
              type="button"
              :class="['search-chip', { active: category === c.name }]"
              @click="setCategory(c.name)"
            >{{ c.name }} <span>{{ c.count }}</span></button>
          </div>
          <div class="search-selects">
            <SearchDropdown :model-value="type" :options="typeOptions(kinds)" icon="sort" aria-label="Type" @update:model-value="setType" />
            <SearchDropdown v-model="period" :options="PERIODS" icon="calendar" aria-label="Date" />
            <SearchDropdown v-model="sort" :options="SORTS" icon="sort" aria-label="Sort by" />
          </div>
        </div>

        <div class="search-body" ref="bodyEl">
          <!-- Typing hint / suggestions -->
          <template v-if="!trimmed">
            <div v-if="recent.length" class="search-section">
              <div class="search-section-head">
                <span>Recent searches</span>
                <button type="button" class="search-link" @click="clearRecent">Clear</button>
              </div>
              <div class="search-tags">
                <button v-for="r in recent" :key="r" type="button" class="search-tag" @click="useQuery(r)">{{ r }}</button>
              </div>
            </div>

            <div class="search-section">
              <div class="search-section-head"><span>Browse by category</span></div>
              <div class="search-tags">
                <button v-for="name in CATEGORIES" :key="name" type="button" class="search-tag" @click="goToCategory(name)">{{ name }}</button>
              </div>
            </div>

            <div v-if="popular.length" class="search-section">
              <div class="search-section-head"><span>Popular now</span></div>
              <button v-for="a in popular" :key="a.id" type="button" class="search-result" @click="openArticle(a)">
                <img class="search-thumb" :src="a.image || fallbackImage" :alt="a.title">
                <div class="search-result-text">
                  <div class="search-result-title">{{ a.title }}</div>
                  <div class="search-result-meta">{{ a.badge }}<template v-if="a.badge"> · </template>{{ a.date }}</div>
                </div>
              </button>
            </div>
          </template>

          <p v-else-if="trimmed.length < 2" class="search-state">Keep typing…</p>
          <p v-else-if="loading && !results.length" class="search-state">Searching…</p>
          <p v-else-if="error" class="search-state">Search is unavailable right now. Please try again.</p>

          <!-- Results -->
          <template v-else>
            <p v-if="corrected" class="search-note">
              Showing results for <strong>{{ corrected }}</strong>.
              <button type="button" class="search-link" @click="searchExact">Search for “{{ trimmed }}” instead</button>
            </p>

            <div v-if="!results.length" class="search-empty">
              <div class="search-empty-title">No articles found for “{{ trimmed }}”</div>
              <p>Try different or fewer words{{ category || period ? ', or remove a filter' : '' }}.</p>
              <button v-if="category || period" type="button" class="search-tag" @click="resetFilters">Clear filters</button>
            </div>

            <template v-else>
              <div class="search-count">{{ total }} {{ total === 1 ? 'result' : 'results' }}<template v-if="total > results.length"> · showing the top {{ results.length }}</template></div>
              <button
                v-for="(a, i) in results"
                :key="a.id"
                type="button"
                :ref="el => { if (el) rowEls[i] = el }"
                :class="['search-result', { active: i === activeIndex }]"
                @mouseenter="activeIndex = i"
                @click="openArticle(a)"
              >
                <img class="search-thumb" :src="a.image || fallbackImage" :alt="a.title">
                <div class="search-result-text">
                  <div class="search-result-title"><template v-for="(seg, k) in parts(a.title)" :key="k"><mark v-if="seg.hit">{{ seg.text }}</mark><template v-else>{{ seg.text }}</template></template></div>
                  <div v-if="a.snippet" class="search-result-snippet"><template v-for="(seg, k) in parts(a.snippet)" :key="k"><mark v-if="seg.hit">{{ seg.text }}</mark><template v-else>{{ seg.text }}</template></template></div>
                  <div class="search-result-meta">
                    <span v-if="a.badge" class="search-result-badge">{{ a.badge }}</span>
                    <span>{{ a.date }}</span>
                    <template v-if="a.author && a.kind !== 'photo'"><span>·</span><span>{{ a.author }}</span></template>
                    <template v-if="a.matched_in?.length"><span>·</span><span class="search-result-why">Found in {{ a.matched_in.join(', ').toLowerCase() }}</span></template>
                  </div>
                </div>
              </button>
            </template>
          </template>
        </div>

        <div class="search-footer">
          <span><kbd>↑</kbd><kbd>↓</kbd> to navigate</span>
          <span><kbd>Enter</kbd> to open</span>
          <span><kbd>Esc</kbd> to close</span>
          <button v-if="trimmed.length >= 2" type="button" class="search-link search-viewall" @click="viewAll">View all results →</button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, computed, watch, nextTick, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import SearchDropdown from './SearchDropdown.vue';
import { PERIODS, SORTS, typeOptions, highlightParts, openResult } from '../utils/search';

const props = defineProps({ open: { type: Boolean, default: false } });
const emit = defineEmits(['close']);

const router = useRouter();

const CATEGORIES = ['News', 'Opinion', 'Editorial', 'Feature', 'Sci-Tech', 'DevCom', 'Sports', 'Literary'];
const RECENT_KEY = 'sparky_recent_searches';
const fallbackImage = '/images/hero_banner.jpg';

const inputEl = ref(null);
const bodyEl = ref(null);
const rowEls = ref([]);

const query = ref('');
const category = ref('');
const type = ref('');
const exact = ref(false);
const kinds = ref({});
const period = ref('');
const sort = ref('relevance');

const results = ref([]);
const categories = ref([]);
const highlight = ref([]);
const corrected = ref(null);
const total = ref(0);
const loading = ref(false);
const error = ref(false);
const searched = ref(false);
const activeIndex = ref(-1);

const popular = ref([]);
const recent = ref([]);

const trimmed = computed(() => query.value.trim());

// ── Recent searches (per browser) ────────────────────────────────────────────
const loadRecent = () => {
  try { recent.value = JSON.parse(localStorage.getItem(RECENT_KEY) || '[]').slice(0, 5); } catch { recent.value = []; }
};
// Remembers a search term (the latest five, no repeats).
const saveRecent = (term) => {
  const t = term.trim();
  if (t.length < 2) return;
  recent.value = [t, ...recent.value.filter(r => r.toLowerCase() !== t.toLowerCase())].slice(0, 5);
  try { localStorage.setItem(RECENT_KEY, JSON.stringify(recent.value)); } catch { /* storage unavailable */ }
};
// Forgets the remembered searches.
const clearRecent = () => {
  recent.value = [];
  try { localStorage.removeItem(RECENT_KEY); } catch { /* storage unavailable */ }
};

// ── Searching ────────────────────────────────────────────────────────────────
let timer = null;
let controller = null;

// Runs the search for the typed text and filters, cancelling any search still running.
const runSearch = async () => {
  clearTimeout(timer);
  timer = null;
  controller?.abort();

  if (trimmed.value.length < 2) {
    results.value = []; categories.value = []; corrected.value = null; total.value = 0;
    loading.value = false; error.value = false; searched.value = false;
    return;
  }

  const mine = (controller = new AbortController());
  loading.value = true;
  error.value = false;
  try {
    const params = new URLSearchParams({ q: trimmed.value, sort: sort.value });
    if (category.value) params.set('category', category.value);
    if (type.value) params.set('type', type.value);
    if (exact.value) params.set('exact', '1');
    if (period.value) params.set('period', period.value);
    const res = await fetch(`/api/reader/search?${params}`, { headers: { Accept: 'application/json' }, signal: mine.signal });
    if (!res.ok) throw new Error('search failed');
    const body = await res.json();

    results.value = body.data;
    categories.value = body.categories;
    kinds.value = body.kinds || {};
    highlight.value = body.highlight || [];
    corrected.value = body.corrected_query;
    total.value = body.total;
    searched.value = true;
    activeIndex.value = results.value.length ? 0 : -1;
    rowEls.value = [];
  } catch (e) {
    if (e.name === 'AbortError') return;
    error.value = true;
    results.value = [];
  } finally {
    if (controller === mine) loading.value = false;
  }
};

// Waits 300 ms after typing stops before searching.
const debounced = () => {
  clearTimeout(timer);
  timer = setTimeout(runSearch, 300);
};

// A new wording starts fresh (the category counts belong to the old one); a changed filter re-runs right away
watch(query, () => {
  exact.value = false;
  if (category.value && !categories.value.some(c => c.name === category.value)) category.value = '';
  debounced();
});
watch([category, type, period, sort], runSearch);

// Filters by category (and clears the type filter).
const setCategory = (name) => { category.value = name; if (name) type.value = ''; };
// Filters by type (and clears the category filter).
const setType = (value) => { type.value = value; if (value) category.value = ''; };
// Clears the category, type and period filters.
const resetFilters = () => { category.value = ''; type.value = ''; period.value = ''; };
// Repeats the search requiring exact matches.
const searchExact = () => { exact.value = true; runSearch(); };
// Opens the full search page for the current text and filters.
const viewAll = () => {
  saveRecent(trimmed.value);
  close();
  const q = { q: trimmed.value };
  if (category.value) q.category = category.value;
  if (type.value) q.type = type.value;
  if (period.value) q.period = period.value;
  if (sort.value !== 'relevance') q.sort = sort.value;
  if (exact.value) q.exact = '1';
  router.push({ path: '/search', query: q });
};
// Clears the search text and category and puts the cursor back in the search box.
const clearQuery = () => { query.value = ''; category.value = ''; nextTick(() => inputEl.value?.focus()); };
// Searches for a suggested or recent text.
const useQuery = (text) => { query.value = text; category.value = ''; runSearch(); nextTick(() => inputEl.value?.focus()); };

// ── Keyboard + navigation ────────────────────────────────────────────────────
const move = (step) => {
  if (!results.value.length) return;
  activeIndex.value = (activeIndex.value + step + results.value.length) % results.value.length;
  nextTick(() => rowEls.value[activeIndex.value]?.scrollIntoView({ block: 'nearest' }));
};

// Enter key: opens the top result, waiting for the search first if it is still running.
const onEnter = async () => {
  if (loading.value || timer) {
    // Don't wait for the debounce; open the top result once it's in
    await runSearch();
  }
  const pick = results.value[activeIndex.value] || results.value[0];
  if (pick) openArticle(pick);
};

// Opens the chosen result and closes the search.
const openArticle = (a) => {
  saveRecent(trimmed.value || '');
  close();
  openResult(router, a);
};

// Opens the category page and closes the search.
const goToCategory = (name) => {
  close();
  router.push({ path: '/categories', query: { category: name } });
};

// Closes the search.
const close = () => emit('close');

// ── Open / close ─────────────────────────────────────────────────────────────
watch(() => props.open, async (isOpen) => {
  if (!isOpen) {
    controller?.abort();
    clearTimeout(timer);
    timer = null;
    return;
  }
  loadRecent();
  await nextTick();
  inputEl.value?.focus();
  inputEl.value?.select();

  if (!popular.value.length) {
    try {
      const res = await fetch('/api/reader/popular?limit=4', { headers: { Accept: 'application/json' } });
      if (res.ok) popular.value = await res.json();
    } catch { /* suggestions are optional */ }
  }
});

onBeforeUnmount(() => { controller?.abort(); clearTimeout(timer); });

// ── Highlighting (split into segments so nothing is rendered as raw HTML) ────
const parts = (text) => highlightParts(text, highlight.value);
</script>
