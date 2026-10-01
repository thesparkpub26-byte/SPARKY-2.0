<template>
  <!-- Phone/tablet only (see responsive.css): opens the dashboard sidebar as a slide-in drawer. -->
  <button type="button" class="mobile-nav-toggle" :aria-expanded="open" aria-label="Open navigation menu" @click="toggle">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
      stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="4" y1="6" x2="20" y2="6" />
      <line x1="4" y1="12" x2="20" y2="12" />
      <line x1="4" y1="18" x2="20" y2="18" />
    </svg>
  </button>
  <div class="mobile-nav-backdrop" @click="close"></div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const OPEN_CLASS = 'mobile-nav-open';
const open = ref(false);

// Opens or closes the drawer by switching a class on the page.
const set = (value) => {
  open.value = value;
  document.documentElement.classList.toggle(OPEN_CLASS, value);
};
// Opens the drawer if it is closed, closes it if it is open.
const toggle = () => set(!open.value);
// Closes the drawer.
const close = () => set(false);

// Picking a destination (not a menu that only expands) closes the drawer.
const DESTINATION = '.nav-item:not(.has-dropdown), .sub-item, .settings-btn, .sign-out-btn';
// Closes the drawer when a destination inside the sidebar is clicked.
const onDocumentClick = (event) => {
  if (open.value && event.target.closest && event.target.closest('.sidebar') && event.target.closest(DESTINATION)) close();
};
// Closes the drawer when Escape is pressed.
const onKeydown = (event) => {
  if (event.key === 'Escape') close();
};

onMounted(() => {
  document.addEventListener('click', onDocumentClick);
  document.addEventListener('keydown', onKeydown);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', onDocumentClick);
  document.removeEventListener('keydown', onKeydown);
  close();
});
</script>
