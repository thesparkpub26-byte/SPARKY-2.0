<template>
    <div class="mus" ref="root">
        <label class="mus-label">{{ label }}</label>

        <div class="mus-box" :class="{ open: isOpen }" @click="toggle">
            <div class="mus-chips">
                <span v-for="user in selectedUsers" :key="user.id" class="mus-chip">
                    {{ user.name }}
                    <button type="button" class="mus-chip-x" :aria-label="`Remove ${user.name}`" @click.stop="remove(user.id)">&times;</button>
                </span>
                <span v-if="!selectedUsers.length" class="mus-placeholder">{{ placeholder }}</span>
            </div>
            <svg class="mus-chevron" :class="{ rotated: isOpen }" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </div>

        <div v-if="isOpen" class="mus-menu" @click.stop>
            <input v-model="query" type="search" class="mus-search" placeholder="Search name or role..." />
            <div class="mus-options">
                <button
                    v-for="user in filteredOptions"
                    :key="user.id"
                    type="button"
                    class="mus-option"
                    :class="{ selected: isSelected(user.id) }"
                    @click="toggleUser(user.id)"
                >
                    <span class="mus-check">
                        <svg v-if="isSelected(user.id)" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </span>
                    <span class="mus-option-text">
                        <span class="mus-option-name">{{ user.name }}</span>
                        <span class="mus-option-role">{{ roleLabel(user) }}</span>
                    </span>
                </button>
                <p v-if="!filteredOptions.length" class="mus-empty">No matching people.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    label: { type: String, required: true },
    // Full user objects the picker can choose from
    options: { type: Array, default: () => [] },
    modelValue: { type: Array, default: () => [] },
    placeholder: { type: String, default: 'Select people' },
    // Pick exactly one person: choosing replaces the selection and closes the menu
    single: { type: Boolean, default: false }
});

const emit = defineEmits(['update:modelValue']);

const root = ref(null);
const isOpen = ref(false);
const query = ref('');

const roleLabel = (user) => user.secondary_role
    || ({ eic: 'Editor-in-Chief', section_editor: 'Section Editor', staff_writer: 'Staff Writer', staff_broadcaster: 'Staff Broadcaster' }[user.role] || user.role);

const selectedUsers = computed(() => props.modelValue
    .map(id => props.options.find(u => u.id === id))
    .filter(Boolean));

const filteredOptions = computed(() => {
    const q = query.value.trim().toLowerCase();
    return props.options.filter(u => !q || `${u.name} ${roleLabel(u)}`.toLowerCase().includes(q));
});

const isSelected = (id) => props.modelValue.includes(id);

const toggle = () => { isOpen.value = !isOpen.value; if (!isOpen.value) query.value = ''; };
const toggleUser = (id) => {
    if (props.single) {
        emit('update:modelValue', isSelected(id) ? [] : [id]);
        isOpen.value = false;
        query.value = '';
        return;
    }
    emit('update:modelValue', isSelected(id) ? props.modelValue.filter(v => v !== id) : [...props.modelValue, id]);
};
const remove = (id) => emit('update:modelValue', props.modelValue.filter(v => v !== id));

const closeOnOutsideClick = (event) => {
    if (isOpen.value && root.value && !root.value.contains(event.target)) {
        isOpen.value = false;
        query.value = '';
    }
};

onMounted(() => document.addEventListener('click', closeOnOutsideClick));
onUnmounted(() => document.removeEventListener('click', closeOnOutsideClick));
</script>

<style scoped>
.mus { position: relative; display: flex; flex-direction: column; gap: 6px; }
.mus-label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }

.mus-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    min-height: 44px;
    padding: 6px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    background: #ffffff;
    cursor: pointer;
    box-sizing: border-box;
    transition: border-color 0.15s;
}
.mus-box:hover, .mus-box.open { border-color: #2563eb; }

.mus-chips { display: flex; flex-wrap: wrap; gap: 6px; flex: 1; min-width: 0; }
.mus-placeholder { color: #94a3b8; font-size: 13.5px; padding: 4px 0; }

.mus-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 6px 4px 10px;
    border-radius: 20px;
    background: #dbeafe;
    color: #1e40af;
    font-size: 12.5px;
    font-weight: 700;
}
.mus-chip-x {
    border: none;
    background: rgba(30, 64, 175, 0.12);
    color: #1e40af;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    line-height: 1;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}
.mus-chip-x:hover { background: rgba(30, 64, 175, 0.25); }

.mus-chevron { flex-shrink: 0; transition: transform 0.2s; }
.mus-chevron.rotated { transform: rotate(180deg); }

.mus-menu {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    z-index: 20;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 18px 40px -10px rgba(15, 23, 42, 0.25);
    padding: 8px;
}

.mus-search {
    width: 100%;
    box-sizing: border-box;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 8px 12px;
    font-size: 13px;
    font-family: inherit;
    outline: none;
    margin-bottom: 6px;
}
.mus-search:focus { border-color: #2563eb; }

.mus-options { max-height: 190px; overflow-y: auto; display: flex; flex-direction: column; gap: 2px; }

.mus-option {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 8px 10px;
    border: none;
    background: none;
    border-radius: 10px;
    cursor: pointer;
    text-align: left;
    font-family: inherit;
}
.mus-option:hover { background: #f1f5f9; }
.mus-option.selected { background: #eff6ff; }

.mus-check {
    width: 18px;
    height: 18px;
    border-radius: 5px;
    border: 1.5px solid #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.mus-option.selected .mus-check { background: #2563eb; border-color: #2563eb; }

.mus-option-text { display: flex; flex-direction: column; min-width: 0; }
.mus-option-name { font-size: 13.5px; font-weight: 700; color: #1e293b; }
.mus-option-role { font-size: 11.5px; color: #64748b; }
.mus-empty { margin: 8px; font-size: 12.5px; color: #94a3b8; }
</style>
