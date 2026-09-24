<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <!-- Published Issues Section -->
      <section class="published-issues-section" style="margin-top: 32px; width: 100%;">
        <h1 class="section-headline" style="margin-bottom: 28px;">Published Issues</h1>

        <p v-if="loading" class="section-empty">Loading issues…</p>
        <p v-else-if="!issues.length" class="section-empty">No published issues yet.</p>

        <div v-else class="issues-grid">
          <IssueCard
            v-for="item in issues"
            :key="item.id"
            :issue="item"
            @explore="openIssue"
          />
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
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import IssueCard from '../../components/IssueCard.vue';
import { renderPdfCover } from '../../utils/pdfThumbnail';

const issues = ref([]);
const loading = ref(true);

const formatDate = (iso) => iso
  ? new Date(iso).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
  : '';

// Issues are PDFs, so each cover is the rendered first page. They're drawn one at a time
// so a long archive doesn't make the browser decode every PDF at once.
const renderCovers = async () => {
  for (const issue of issues.value) {
    if (!issue.pdf_url) continue;
    try {
      issue.image = await renderPdfCover(issue.pdf_url);
    } catch (e) {
      console.warn('Could not render issue cover:', e);
    }
  }
};

onMounted(async () => {
  try {
    const res = await fetch('/api/reader/issues', { headers: { Accept: 'application/json' } });
    if (res.ok) {
      const data = await res.json();
      issues.value = data.map((i) => ({ ...i, image: null, date: formatDate(i.created_at) }));
    }
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
