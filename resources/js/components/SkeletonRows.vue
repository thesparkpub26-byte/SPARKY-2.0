<template>
  <!-- Placeholder table rows for a dashboard table that is still loading: drop it inside <tbody> -->
  <tr v-for="n in rows" :key="n" class="skeleton-row" aria-hidden="true">
    <td v-for="c in columns" :key="c">
      <span class="skeleton skeleton-line" :style="{ width: widths[(n + c) % widths.length] }"></span>
    </td>
  </tr>
</template>

<script setup>
defineProps({
  columns: { type: Number, required: true },
  rows: { type: Number, default: 4 },
});

// Varied widths look like text and avoid a stripey grid
const widths = ['85%', '60%', '72%', '45%', '90%'];
</script>
