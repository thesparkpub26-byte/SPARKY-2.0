<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <div v-if="loading" class="skeleton-article" role="status" aria-label="Loading video">
        <span class="skeleton skeleton-line skeleton-line--title"></span>
        <span class="skeleton skeleton-line skeleton-line--medium"></span>
        <div class="skeleton skeleton-image"></div>
      </div>

      <div v-else-if="notFound" class="article-not-found">
        <h1 class="section-headline">Video not found</h1>
        <p class="section-subtext">It may have been removed or isn't published yet.</p>
        <router-link to="/videos" class="btn-load-more">Back to videos</router-link>
      </div>

      <template v-else>
        <article class="video-page">
          <h1 class="article-title video-title">{{ video.title }}</h1>

          <div class="article-meta-header">
            <span class="badge-category">{{ video.category }}</span>
            <span class="article-date-text">{{ formatLongDate(video.published_at) }}</span>
          </div>

          <!-- The video plays here on the page (YouTube's player, embedded) -->
          <div class="video-player">
            <iframe
              v-if="video.youtube_id"
              :src="`https://www.youtube-nocookie.com/embed/${video.youtube_id}?rel=0`"
              :title="video.title"
              loading="lazy"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              referrerpolicy="strict-origin-when-cross-origin"
              allowfullscreen
            ></iframe>
            <!-- A link that isn't a YouTube video can't be embedded: show its picture and open it -->
            <a v-else :href="video.video_url" target="_blank" rel="noopener" class="video-fallback">
              <img v-if="video.thumbnail" :src="video.thumbnail" :alt="video.title">
              <span class="video-fallback-play">Watch video</span>
            </a>
          </div>

          <section v-if="video.description" class="video-section">
            <h2 class="video-section-title">Details</h2>
            <p class="video-description">{{ video.description }}</p>
          </section>

          <section v-if="video.credits.length" class="video-section">
            <h2 class="video-section-title">Credits</h2>
            <dl class="video-credits">
              <div v-for="row in video.credits" :key="row.role" class="video-credit-row">
                <dt>{{ row.role }}</dt>
                <dd>{{ row.people.map((p) => p.name).join(', ') }}</dd>
              </div>
            </dl>
          </section>
        </article>

        <section v-if="video.more.length" class="popular-section" style="margin-top: 56px;">
          <h2 class="section-headline">More videos</h2>
          <p class="section-subtext">Watch what our broadcasting team made</p>

          <div class="articles-grid">
            <ArticleCard v-for="v in video.more" :key="v.id" :article="v" />
          </div>
          <div class="load-more-wrapper">
            <router-link to="/videos" class="btn-load-more">All videos</router-link>
          </div>
        </section>
      </template>

      <NewsletterCard />
      <Footer />
    </main>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import ArticleCard from '../../components/ArticleCard.vue';

const route = useRoute();
const video = ref(null);
const loading = ref(true);
const notFound = ref(false);

const formatLongDate = (iso) => iso
  ? new Date(iso).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
  : '';

const load = async () => {
  loading.value = true;
  notFound.value = false;
  try {
    const res = await fetch(`/api/reader/videos/${route.params.id}`, { headers: { Accept: 'application/json' } });
    if (!res.ok) throw new Error('not found');
    video.value = await res.json();
    document.title = `${video.value.title} | TheSPARK`;
    document.querySelector('.reader-page')?.scrollTo({ top: 0 });
    recordView();
  } catch {
    video.value = null;
    notFound.value = true;
    document.title = 'Video not found | TheSPARK';
  } finally {
    loading.value = false;
  }
};

// Counted in the site's traffic numbers like an article page; analytics must never get in the way of watching
const recordView = () => {
  fetch('/api/analytics/page-view', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
    body: JSON.stringify({ page_path: window.location.pathname, page_title: video.value.title, article_id: video.value.id }),
  }).catch(() => {});
};

onMounted(load);
// Moving from one video to another (a card under "More videos") reuses this page
watch(() => route.params.id, (id, previous) => { if (id && id !== previous) load(); });
</script>

<style scoped>
.video-page {
  width: 100%;
  max-width: 960px;
  margin: 24px auto 0;
}

.video-title {
  overflow-wrap: anywhere;
}

.video-player {
  position: relative;
  width: 100%;
  aspect-ratio: 16 / 9;
  margin: 8px 0 28px;
  border-radius: 20px;
  overflow: hidden;
  background: #0f172a;
  box-shadow: 0 12px 32px rgba(15, 23, 42, 0.18);
}

.video-player iframe,
.video-fallback {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  border: 0;
}

.video-fallback {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffffff;
  text-decoration: none;
}

.video-fallback img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0.7;
}

.video-fallback-play {
  position: relative;
  padding: 12px 26px;
  border-radius: 999px;
  background: #1d6bf3;
  font-weight: 700;
}

.video-section {
  margin-bottom: 28px;
}

.video-section-title {
  margin: 0 0 10px;
  font-size: 13px;
  font-weight: 800;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  color: #475569;
}

.video-description {
  margin: 0;
  font-size: 16px;
  line-height: 1.75;
  color: #334155;
  white-space: pre-line;
  overflow-wrap: anywhere;
}

.video-credits {
  display: grid;
  gap: 10px;
  margin: 0;
}

.video-credit-row {
  display: flex;
  flex-wrap: wrap;
  gap: 4px 16px;
  padding: 12px 16px;
  border-radius: 14px;
  background: #f8fafc;
  border: 1px solid #eef2f7;
}

.video-credit-row dt {
  min-width: 130px;
  font-size: 13px;
  font-weight: 800;
  color: #1d6bf3;
}

.video-credit-row dd {
  margin: 0;
  font-size: 14px;
  font-weight: 600;
  color: #0f172a;
}

@media (max-width: 640px) {
  .video-player {
    border-radius: 14px;
    margin-bottom: 22px;
  }

  .video-credit-row dt {
    min-width: 100%;
  }
}
</style>
