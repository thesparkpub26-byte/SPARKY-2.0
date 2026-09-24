<template>
    <div class="ss" ref="root">
        <button
            type="button"
            class="ss-trigger"
            :class="{ open: isOpen, empty: !modelValue }"
            :disabled="disabled"
            aria-haspopup="listbox"
            :aria-expanded="isOpen"
            @click="isOpen = !isOpen"
        >
            <span v-if="modelValue" class="ss-dot" :style="{ background: colorFor(modelValue) }"></span>
            <span class="ss-value">{{ modelValue || placeholder }}</span>
            <svg class="ss-chevron" :class="{ rotated: isOpen }" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>

        <div v-if="isOpen" class="ss-menu" :class="{ up: direction === 'up' }" role="listbox">
            <button
                v-for="option in options"
                :key="option"
                type="button"
                class="ss-option"
                :class="{ selected: option === modelValue }"
                role="option"
                :aria-selected="option === modelValue"
                @click="select(option)"
            >
                <span class="ss-dot" :style="{ background: colorFor(option) }"></span>
                <span class="ss-option-name">{{ option }}</span>
                <svg v-if="option === modelValue" class="ss-check" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { VIDEO_CATEGORIES } from '../utils/video';

defineProps({
    modelValue: { type: String, default: '' },
    options: { type: Array, default: () => VIDEO_CATEGORIES },
    placeholder: { type: String, default: 'Select a section' },
    // Open the menu above the field when there's no room below (e.g. at the bottom of a modal)
    direction: { type: String, default: 'down' },
    disabled: { type: Boolean, default: false }
});

const emit = defineEmits(['update:modelValue']);

const root = ref(null);
const isOpen = ref(false);

const COLORS = { Documentary: '#2563eb', Reel: '#db2777', Telesiklab: '#16a34a' };
const colorFor = (option) => COLORS[option] || '#64748b';

const select = (option) => {
    emit('update:modelValue', option);
    isOpen.value = false;
};

const closeOnOutsideClick = (event) => {
    if (isOpen.value && root.value && !root.value.contains(event.target)) isOpen.value = false;
};

onMounted(() => document.addEventListener('click', closeOnOutsideClick));
onUnmounted(() => document.removeEventListener('click', closeOnOutsideClick));
</script>

<style scoped>
.ss { position: relative; width: 100%; }

.ss-trigger {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    background: #ffffff;
    color: #0f172a;
    font-size: 14px;
    font-weight: 600;
    font-family: inherit;
    text-align: left;
    cursor: pointer;
    box-sizing: border-box;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.ss-trigger:hover:not(:disabled) { border-color: #93c5fd; }
.ss-trigger.open { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12); }
.ss-trigger.empty .ss-value { color: #94a3b8; font-weight: 500; }
.ss-trigger:disabled { opacity: 0.6; cursor: not-allowed; }

.ss-value { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.ss-chevron { flex-shrink: 0; color: #2563eb; transition: transform 0.2s; }
.ss-chevron.rotated { transform: rotate(180deg); }

.ss-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

.ss-menu {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    z-index: 30;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 18px 40px -10px rgba(15, 23, 42, 0.25);
    padding: 6px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    animation: ss-pop 0.14s ease-out;
}
.ss-menu.up { top: auto; bottom: calc(100% + 6px); }

@keyframes ss-pop {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}

.ss-option {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 10px 12px;
    border: none;
    border-radius: 10px;
    background: none;
    cursor: pointer;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    text-align: left;
    transition: background 0.12s;
}
.ss-option:hover { background: #f1f5f9; }
.ss-option.selected { background: #eff6ff; color: #1d4ed8; }
.ss-option-name { flex: 1; }
.ss-check { color: #2563eb; flex-shrink: 0; }
</style>
