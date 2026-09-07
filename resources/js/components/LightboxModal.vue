<template>
  <div v-if="isOpen" class="gallery-modal active" @click="handleOverlayClick">
    <button class="gallery-modal-close" @click="$emit('close')">&times;</button>
    <img class="gallery-modal-content" :src="imageSrc" alt="Zoomed Photo">
  </div>
</template>

<script setup>
const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  imageSrc: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['close']);

const handleOverlayClick = (event) => {
  if (event.target.classList.contains('gallery-modal') || event.target.classList.contains('gallery-modal-close')) {
    emit('close');
  }
};
</script>
