<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <!-- Published Issues Section -->
      <section class="published-issues-section" style="margin-top: 32px; width: 100%;">
        <h1 class="section-headline" style="margin-bottom: 28px;">Published Issues</h1>

        <SkeletonCards v-if="loading" variant="issue" :count="3" />
        <p v-else-if="!issues.length" class="section-empty">No published issues yet.</p>

        <div v-else class="issues-grid">
          <IssueCard
            v-for="item in issues"
            :key="item.id"
            :issue="item"
            @explore="openIssue"
          />
        </div>
        <div v-if="page < lastPage" class="load-more-wrapper">
          <button type="button" class="btn-load-more btn-load-more-btn" :disabled="loadingMore" @click="loadMore">{{ loadingMore ? 'Loading…' : 'Load More' }}</button>
        </div>
      </section>

      <!-- Subscribe To Our Newsletter Section -->
      <NewsletterCard />

      <!-- Footer Section -->
      <Footer />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Navbar from '../../components/Navbar.vue';
import SkeletonCards from '../../components/SkeletonCards.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import IssueCard from '../../components/IssueCard.vue';
import { renderPdfCover } from '../../utils/pdfThumbnail';

const issues = ref([]);
const loading = ref(true);
const loadingMore = ref(false);
const page = ref(0);
const lastPage = ref(1);

const formatDate = (iso) => iso
  ? new Date(iso).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
  : '';

// Issues are PDFs, so each cover is the rendered first page. They're drawn one at a time
// so a long archive doesn't make the browser decode every PDF at once.
const renderCovers = async () => {
  for (const issue of issues.value) {
    if (!issue.pdf_url || issue.image) continue;
    try {
      issue.image = await renderPdfCover(issue.pdf_url);
    } catch (e) {
      console.warn('Could not render issue cover:', e);
    }
  }
};

// Issues come six at a time; "Load More" appends the next six
const loadPage = async (next) => {
  const res = await fetch(`/api/reader/issues?page=${next}`, { headers: { Accept: 'application/json' } });
  if (!res.ok) return;
  const body = await res.json();
  const fresh = body.data.map((i) => ({ ...i, image: null, date: formatDate(i.created_at) }));
  issues.value = next === 1 ? fresh : [...issues.value, ...fresh];
  page.value = body.current_page;
  lastPage.value = body.last_page;
};

const loadMore = async () => {
  loadingMore.value = true;
  try {
    await loadPage(page.value + 1);
  } catch {
    // Keep what is already showing; the button stays so they can try again.
  } finally {
    loadingMore.value = false;
  }
  renderCovers();
};

onMounted(async () => {
  try {
    await loadPage(1);
  } catch {
    // Falls through to the empty state.
  } finally {
    loading.value = false;
  }
  renderCovers();
});

const openIssue = (issue) => {
  window.open(`/booklet/${issue.id}`, '_blank');
};
</script>
