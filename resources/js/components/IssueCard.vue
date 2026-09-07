<template>
  <div class="issue-card">
    <div class="issue-cover-wrapper">
      <img :src="issue.image" :alt="issue.title">
    </div>
    <h3 class="issue-title">{{ issue.title }}</h3>
    <p class="issue-desc">{{ issue.desc }}</p>
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

const onExplore = () => {
  emit('explore', props.issue);
};
</script>
