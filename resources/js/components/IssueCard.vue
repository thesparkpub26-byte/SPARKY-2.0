<template>
  <div class="issue-card">
    <div class="issue-cover-wrapper">
      <img v-if="issue.image" :src="issue.image" :alt="issue.title">
      <svg v-else width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><polyline points="14 2 14 8 20 8" />
      </svg>
    </div>
    <h3 class="issue-title">{{ issue.title }}</h3>
    <p v-if="issue.desc" class="issue-desc">{{ issue.desc }}</p>
    <div class="issue-footer">
      <span>{{ issue.date }}</span>
      <a href="#" class="btn-explore-now" @click.prevent="onExplore">Explore Now</a>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  issue: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['explore']);

// Tells the parent the user wants to open this issue.
const onExplore = () => {
  emit('explore', props.issue);
};
</script>
