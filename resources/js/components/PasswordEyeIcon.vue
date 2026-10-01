<template>
  <!-- The show / hide eye for a password box. Styled by `.input-icon`; the parent flips `visible` on click. -->
  <svg class="input-icon password-eye" role="button" tabindex="0" :aria-label="visible ? 'Hide password' : 'Show password'"
    xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <template v-if="!visible">
      <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
      <circle cx="12" cy="12" r="3"></circle>
    </template>
    <template v-else>
      <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-10-8-10-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 10 8 10 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
      <line x1="1" y1="1" x2="23" y2="23"></line>
    </template>
  </svg>
</template>

<script setup>
defineProps({ visible: { type: Boolean, default: false } });
</script>
