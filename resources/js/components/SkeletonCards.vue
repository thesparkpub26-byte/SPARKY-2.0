<template>
  <!-- Placeholder shapes shown while a list loads. They reuse the real cards' classes so nothing jumps when the content arrives. -->
  <div :class="wrapperClass" role="status" aria-label="Loading">
    <template v-if="variant === 'article'">
      <div v-for="n in count" :key="n" class="article-card skeleton-card" aria-hidden="true">
        <div class="article-thumb-wrapper skeleton"></div>
        <div class="article-body">
          <span class="skeleton skeleton-line skeleton-line--title"></span>
          <span class="skeleton skeleton-line skeleton-line--wide"></span>
          <span class="skeleton skeleton-line skeleton-line--medium"></span>
          <span class="skeleton skeleton-line skeleton-line--short"></span>
        </div>
      </div>
    </template>

    <template v-else-if="variant === 'list'">
      <div v-for="n in count" :key="n" class="category-article-card skeleton-card" aria-hidden="true">
        <div class="category-card-img-wrapper skeleton"></div>
        <div class="category-card-body">
          <div>
            <span class="skeleton skeleton-line skeleton-line--title"></span>
            <span class="skeleton skeleton-line skeleton-line--wide"></span>
            <span class="skeleton skeleton-line skeleton-line--medium"></span>
          </div>
          <span class="skeleton skeleton-line skeleton-line--short"></span>
        </div>
      </div>
    </template>

    <template v-else-if="variant === 'issue'">
      <div v-for="n in count" :key="n" class="issue-card skeleton-card" aria-hidden="true">
        <div class="issue-cover-wrapper skeleton"></div>
        <span class="skeleton skeleton-line skeleton-line--medium"></span>
        <span class="skeleton skeleton-line skeleton-line--short"></span>
      </div>
    </template>

    <template v-else-if="variant === 'gallery'">
      <div v-for="n in count" :key="n" class="gallery-grid-item skeleton skeleton-gallery-item" aria-hidden="true"></div>
    </template>

    <template v-else-if="variant === 'strip'">
      <div v-for="n in count" :key="n" class="gallery-card skeleton skeleton-gallery-item" aria-hidden="true"></div>
    </template>
  </div>
</template>

<script setup>
import { computed } from 'vue';

// article: the 3-column card grid · list: the Categories page rows · issue: issue cards
// gallery: the Gallery page grid · strip: the home page's photo row
const props = defineProps({
  variant: { type: String, default: 'article' },
  count: { type: Number, default: 3 },
});

const wrapperClass = computed(() => ({
  article: 'articles-grid',
  list: 'category-articles-list',
  issue: 'issues-grid',
  gallery: 'gallery-grid',
  strip: 'gallery-cards-row',
}[props.variant]));
</script>
