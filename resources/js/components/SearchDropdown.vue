<template>
  <div ref="root" class="sdrop" :class="{ open, changed }">
    <button
      type="button"
      class="sdrop-btn"
      aria-haspopup="listbox"
      :aria-expanded="open"
      :aria-label="ariaLabel"
      @click="open = !open"
      @keydown.esc.stop="open = false"
    >
      <svg v-if="icon === 'calendar'" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="4" width="18" height="18" rx="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" />
      </svg>
      <svg v-else-if="icon === 'sort'" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 6h18M6 12h12M10 18h4" />
      </svg>
      <span>{{ current.label }}</span>
      <svg class="sdrop-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6" /></svg>
    </button>

    <Transition name="sdrop-pop">
      <ul v-if="open" class="sdrop-menu" role="listbox" @keydown.esc.stop="open = false">
        <li
          v-for="o in options"
          :key="o.value"
          role="option"
          :aria-selected="o.value === modelValue"
          :class="['sdrop-item', { selected: o.value === modelValue }]"
          @click="pick(o.value)"
        >
          <span>{{ o.label }}</span>
          <svg v-if="o.value === modelValue" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12" /></svg>
        </li>
      </ul>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
  modelValue: { type: String, default: '' },
  options: { type: Array, required: true }, // [{ value, label }]
  icon: { type: String, default: '' },
  ariaLabel: { type: String, default: '' },
});
const emit = defineEmits(['update:modelValue']);

const root = ref(null);
const open = ref(false);

const current = computed(() => props.options.find(o => o.value === props.modelValue) || props.options[0]);
// Highlight the pill when it's no longer on its first (default) option
const changed = computed(() => current.value !== props.options[0]);

// Picks an option, tells the parent and closes the list.
const pick = (value) => {
  emit('update:modelValue', value);
  open.value = false;
};

// Closes the list when the user clicks outside it.
const onOutside = (e) => {
  if (open.value && !root.value?.contains(e.target)) open.value = false;
};
onMounted(() => document.addEventListener('mousedown', onOutside));
onBeforeUnmount(() => document.removeEventListener('mousedown', onOutside));
</script>
