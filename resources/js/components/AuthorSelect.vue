<template>
    <div class="as" ref="root">
        <button
            type="button"
            class="as-trigger"
            :class="{ open: isOpen, empty: !selected }"
            aria-haspopup="listbox"
            :aria-expanded="isOpen"
            @click="isOpen = !isOpen"
        >
            <template v-if="selected">
                <img class="as-avatar" :src="avatarFor(selected)" :alt="selected.name">
                <span class="as-text">
                    <span class="as-name">{{ selected.name }}</span>
                    <span class="as-role">{{ roleLabel(selected) }}</span>
                </span>
            </template>
            <span v-else class="as-placeholder">{{ placeholder }}</span>
            <svg class="as-chevron" :class="{ rotated: isOpen }" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>

        <div v-if="isOpen" class="as-menu" role="listbox">
            <p v-if="!options.length" class="as-empty">No artists available.</p>
            <button
                v-for="option in options"
                :key="option.id"
                type="button"
                class="as-option"
                :class="{ selected: option.id === modelValue }"
                role="option"
                :aria-selected="option.id === modelValue"
                @click="select(option)"
            >
                <img class="as-avatar" :src="avatarFor(option)" :alt="option.name">
                <span class="as-text">
                    <span class="as-name">{{ option.name }}</span>
                    <span class="as-role">{{ roleLabel(option) }}</span>
                </span>
                <svg v-if="option.id === modelValue" class="as-check" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

// Author picker for gallery photos: the staff artists and the Art Editor, with avatar and role.
const props = defineProps({
    modelValue: { type: [Number, String], default: '' },
    options: { type: Array, default: () => [] },
    placeholder: { type: String, default: 'Select an artist or the Art Editor' }
});

const emit = defineEmits(['update:modelValue']);

const root = ref(null);
const isOpen = ref(false);

const selected = computed(() => props.options.find(o => o.id === props.modelValue) || null);

const roleLabel = (a) => a.role === 'staff_artist' ? (a.secondary_role || 'Staff Artist') : 'Art Editor';

const avatarFor = (a) => a.profile_picture
    ? `/storage/${a.profile_picture}`
    : `https://ui-avatars.com/api/?name=${encodeURIComponent(a.name || 'User')}&background=dbeafe&color=1d4ed8`;

const select = (option) => {
    emit('update:modelValue', option.id);
    isOpen.value = false;
};

const closeOnOutsideClick = (event) => {
    if (isOpen.value && root.value && !root.value.contains(event.target)) isOpen.value = false;
};

onMounted(() => document.addEventListener('click', closeOnOutsideClick));
onUnmounted(() => document.removeEventListener('click', closeOnOutsideClick));
</script>

<style scoped>
.as { position: relative; width: 100%; }

.as-trigger {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
    height: 39px;
    padding: 0 12px;
    border: 1px solid #e1e1e1;
    border-radius: 12px;
    background: #ffffff;
    color: #0f172a;
    font-family: inherit;
    text-align: left;
    cursor: pointer;
    box-sizing: border-box;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.as-trigger:hover { border-color: #b8c7e6; }
.as-trigger.open { border-color: #1769ff; box-shadow: 0 0 0 3px rgba(23, 105, 255, 0.1); }

.as-placeholder { flex: 1; font-size: 11px; font-weight: 400; color: #8a8a8a; }

.as-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 1px solid #e2e8f0;
}

.as-text { flex: 1; min-width: 0; display: flex; flex-direction: column; line-height: 1.25; }
.as-name { font-size: 12.5px; font-weight: 700; color: #0f172a; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.as-role { font-size: 11px; font-weight: 600; color: #64748b; }

/* Inside the closed field, keep everything compact so it lines up with the 39px inputs */
.as-trigger .as-avatar { width: 26px; height: 26px; }
.as-trigger .as-name { font-size: 12px; }
.as-trigger .as-role { font-size: 10.5px; }

.as-chevron { flex-shrink: 0; color: #2563eb; margin-left: auto; transition: transform 0.2s; }
.as-chevron.rotated { transform: rotate(180deg); }

.as-menu {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    z-index: 30;
    max-height: 232px;
    overflow-y: auto;
    background: #ffffff;
    border: 1px solid #e1e1e1;
    border-radius: 12px;
    box-shadow: 0 18px 40px -10px rgba(15, 23, 42, 0.25);
    padding: 6px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    animation: as-pop 0.14s ease-out;
}

@keyframes as-pop {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}

.as-option {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 8px 10px;
    border: none;
    border-radius: 10px;
    background: none;
    cursor: pointer;
    font-family: inherit;
    text-align: left;
    transition: background 0.12s;
}
.as-option:hover { background: #f1f5f9; }
.as-option.selected { background: #eff6ff; }
.as-option.selected .as-name { color: #1d4ed8; }
.as-check { color: #2563eb; flex-shrink: 0; }

.as-empty { padding: 12px; font-size: 13px; color: #94a3b8; font-weight: 600; text-align: center; }
</style>
