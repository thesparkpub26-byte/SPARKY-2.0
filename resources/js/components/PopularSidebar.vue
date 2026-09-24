<template>
  <aside class="article-sidebar">
    <div class="sidebar-card-container">
      <div class="sidebar-header">
        <h3 class="sidebar-title">Popular Now</h3>
        <p class="sidebar-subtitle">You might like to read these posts.</p>
      </div>

      <p v-if="!popular.length" class="section-empty" style="padding: 8px 0;">No published articles yet.</p>

      <div v-for="(story, idx) in popular" :key="story.id" class="top-story-item">
        <!-- The newest story is featured with its image -->
        <div v-if="idx === 0" class="top-story-featured-img-wrapper">
          <span v-if="story.badge" class="top-story-badge-overlay badge-category">{{ story.badge }}</span>
          <img :src="story.image || fallbackImage" :alt="story.title" class="top-story-featured-img">
        </div>
        <span v-else-if="story.badge" class="top-story-badge">{{ story.badge }}</span>
        <router-link :to="`/article/${story.id}`" class="top-story-title">{{ story.title }}</router-link>
        <p class="top-story-desc">{{ story.excerpt }}</p>
        <div class="top-story-footer">
          <span>{{ story.date }}</span>
          <router-link :to="`/article/${story.id}`" class="sidebar-read-more">Read More &rarr;</router-link>
        </div>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// "Popular Now": the 5 latest published articles (shared by the Categories and Article pages)
const fallbackImage = '/images/hero_banner.jpg';
const popular = ref([]);

onMounted(async () => {
  try {
    const res = await fetch('/api/reader/articles?limit=5', { headers: { Accept: 'application/json' } });
    if (res.ok) popular.value = await res.json();
  } catch {
    // The sidebar just stays empty if the request fails.
  }
});
</script>
