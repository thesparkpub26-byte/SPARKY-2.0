<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <section class="search-page">
        <h1 class="section-headline">Search</h1>

        <form class="search-page-bar" @submit.prevent="submit">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="search-bar-icon">
            <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
          <input v-model="draft" class="search-input" type="text" maxlength="120" autocomplete="off" placeholder="Search articles, videos, photos, authors…" aria-label="Search">
          <button type="submit" class="search-page-submit">Search</button>
        </form>

        <template v-if="q.length >= 2">
          <div class="search-filters search-page-filters">
            <div class="search-chips">
              <button type="button" :class="['search-chip', { active: !category }]" @click="setCategory('')">All categories</button>
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
              <SearchDropdown :model-value="period" :options="PERIODS" icon="calendar" aria-label="Date" @update:model-value="(v) => setParam('period', v)" />
              <SearchDropdown :model-value="sort" :options="SORTS" icon="sort" aria-label="Sort by" @update:model-value="(v) => setParam('sort', v)" />
            </div>
          </div>

          <SkeletonCards v-if="loading && !results.length" variant="list" :count="4" />
          <p v-else-if="error" class="section-empty">Search is unavailable right now. Please try again.</p>

          <template v-else>
            <p v-if="corrected" class="search-note">
              Showing results for <strong>{{ corrected }}</strong>.
              <button type="button" class="search-link" @click="searchExact">Search for “{{ q }}” instead</button>
            </p>

            <div v-if="!results.length" class="search-empty">
              <div class="search-empty-title">No results for “{{ q }}”</div>
              <p>Try different or fewer words{{ category || period || type ? ', or remove a filter' : '' }}.</p>
            </div>

            <template v-else>
              <div class="search-count">{{ total }} {{ total === 1 ? 'result' : 'results' }}</div>
              <div class="search-page-results">
                <a v-for="a in results" :key="`${a.kind}-${a.id}`" href="#" class="search-result" @click.prevent="open(a)">
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
                </a>
              </div>

              <div v-if="page < lastPage" class="load-more-wrapper">
                <button type="button" class="btn-load-more btn-load-more-btn" :disabled="loading" @click="loadMore">{{ loading ? 'Loading…' : 'Load More' }}</button>
              </div>
            </template>
          </template>
        </template>
        <p v-else class="section-empty">Type at least two letters to search articles, videos and photos.</p>
      </section>

      <NewsletterCard />
      <Footer />
    </main>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Navbar from '../../components/Navbar.vue';
import SkeletonCards from '../../components/SkeletonCards.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import SearchDropdown from '../../components/SearchDropdown.vue';
import { PERIODS, SORTS, typeOptions, highlightParts, openResult } from '../../utils/search';

const route = useRoute();
const router = useRouter();
const fallbackImage = '/images/hero_banner.jpg';
const PER_PAGE = 10;

// The address is the source of truth: /search?q=…&type=…&category=…&period=…&sort=…
const q = computed(() => String(route.query.q || '').trim());
const type = computed(() => String(route.query.type || ''));
const category = computed(() => String(route.query.category || ''));
const period = computed(() => String(route.query.period || ''));
const sort = computed(() => String(route.query.sort || 'relevance'));

const draft = ref(q.value);
const results = ref([]);
const categories = ref([]);
const kinds = ref({});
const highlight = ref([]);
const corrected = ref(null);
const total = ref(0);
const page = ref(1);
const lastPage = ref(1);
const loading = ref(false);
const error = ref(false);

let controller = null;

// Fetches one page of search results, cancelling any request still running.
const fetchPage = async (n) => {
  controller?.abort();
  const mine = (controller = new AbortController());
  loading.value = true;
  error.value = false;

  try {
    const params = new URLSearchParams({ q: q.value, sort: sort.value, page: n, per_page: PER_PAGE });
    if (type.value) params.set('type', type.value);
    if (category.value) params.set('category', category.value);
    if (period.value) params.set('period', period.value);
    if (route.query.exact) params.set('exact', '1');

    const res = await fetch(`/api/reader/search?${params}`, { headers: { Accept: 'application/json' }, signal: mine.signal });
    if (!res.ok) throw new Error('search failed');
    const body = await res.json();

    results.value = n === 1 ? body.data : [...results.value, ...body.data];
    categories.value = body.categories;
    kinds.value = body.kinds;
    highlight.value = body.highlight || [];
    corrected.value = body.corrected_query;
    total.value = body.total;
    page.value = body.current_page;
    lastPage.value = body.last_page;
  } catch (e) {
    if (e.name === 'AbortError') return;
    error.value = true;
    results.value = [];
  } finally {
    if (controller === mine) loading.value = false;
  }
};

// Starts a new search from page 1 (needs at least 2 characters).
const runFresh = () => {
  results.value = [];
  if (q.value.length < 2) { controller?.abort(); loading.value = false; return; }
  fetchPage(1);
};

// Loads the next page of results.
const loadMore = () => fetchPage(page.value + 1);

// Updates the search address with the changed query or filters.
const go = (changes) => router.replace({ path: '/search', query: { ...route.query, ...changes } });
// Searches for the typed text and resets the filters.
const submit = () => go({ q: draft.value.trim(), category: undefined, type: undefined, exact: undefined });
// Filters by category (and clears the type filter).
const setCategory = (name) => go({ category: name || undefined, type: undefined });
// Filters by type (and clears the category filter).
const setType = (value) => go({ type: value || undefined, category: undefined });
// Sets or clears one filter in the search address.
const setParam = (key, value) => go({ [key]: value || undefined });
// Repeats the search requiring exact matches.
const searchExact = () => go({ exact: '1' });

// Splits a text into highlighted and plain parts for the search words.
const parts = (text) => highlightParts(text, highlight.value);
// Opens a search result.
const open = (item) => openResult(router, item);

watch(() => route.query, () => {
  if (route.path !== '/search') return;
  draft.value = q.value;
  runFresh();
});
onMounted(runFresh);
onBeforeUnmount(() => controller?.abort());
</script>
