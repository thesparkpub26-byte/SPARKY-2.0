<template>
  <div v-if="isOpen" class="gallery-modal active" @click="handleOverlayClick">
    <button class="gallery-modal-close" @click="$emit('close')">&times;</button>
    <figure class="gallery-modal-figure">
      <img class="gallery-modal-content" :src="imageSrc" :alt="title || 'Zoomed Photo'">
      <figcaption v-if="title" class="gallery-modal-caption">
        <h3 class="gallery-modal-title">{{ title }}</h3>
        <p class="gallery-modal-meta">
          <span v-if="artist">Artist: {{ artist }}</span>
          <span v-if="formattedDate">Posted {{ formattedDate }}</span>
        </p>
      </figcaption>
    </figure>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  imageSrc: {
    type: String,
    default: ''
  },
  title: {
    type: String,
    default: ''
  },
  artist: {
    type: String,
    default: ''
  },
  date: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['close']);

const formattedDate = computed(() => props.date
  ? new Date(props.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
  : '');

const handleOverlayClick = (event) => {
  const cls = event.target.classList;
  if (cls.contains('gallery-modal') || cls.contains('gallery-modal-close') || cls.contains('gallery-modal-figure')) {
    emit('close');
  }
};
</script>
