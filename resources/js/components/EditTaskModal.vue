<template>
    <div class="edit-task-overlay" v-if="isOpen" @click.self="$emit('close')">
        <div class="edit-task-card">
            <div class="edit-task-hdr">
                <h2 class="edit-task-title">Edit Task</h2>
                <button class="modal-x-btn" @click="$emit('close')" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <div class="edit-task-body">
                <!-- Title -->
                <div class="et-field-group">
                    <label class="et-label">Article Title</label>
                    <input v-model="form.title" type="text" class="et-input" placeholder="Article title..." />
                </div>

                <!-- Priority + Deadline row -->
                <div class="et-row">
                    <div class="et-field-group">
                        <label class="et-label">Priority</label>
                        <div class="et-select-wrap">
                            <select v-model="form.priority" class="et-select">
                                <option value="">Choose Level</option>
                                <option value="low">Low</option>
                                <option value="medium">Moderate</option>
                                <option value="urgent">Urgent</option>
                            </select>
                            <svg class="et-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>

                    <div class="et-field-group">
                        <label class="et-label">Deadline</label>
                        <div class="et-deadline-row">
                            <input v-model="form.dueDate" type="date" class="et-input-sm" />
                            <input v-model="form.dueTime" type="time" class="et-input-sm" />
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="et-field-group">
                    <label class="et-label">Description / Instructions</label>
                    <div class="et-textarea-wrap">
                        <textarea v-model="form.description" class="et-textarea" maxlength="500" placeholder="Write instructions or context for the assignee..."></textarea>
                        <div class="et-char-count">{{ form.description.length }}/500</div>
                    </div>
                </div>

                <p v-if="errorMsg" class="et-error">{{ errorMsg }}</p>
            </div>

            <div class="edit-task-footer">
                <button class="btn-grey-pill" @click="$emit('close')" :disabled="saving">Cancel</button>
                <button class="btn-blue-pill" @click="saveTask" :disabled="saving">
                    <svg v-if="saving" class="spin-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ saving ? 'Saving...' : 'Save Changes' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    isOpen: { type: Boolean, default: false },
    task: { type: Object, default: () => ({}) }
});

const emit = defineEmits(['close', 'task-updated']);

const saving = ref(false);
const errorMsg = ref('');

const form = ref({
    title: '',
    priority: '',
    dueDate: '',
    dueTime: '',
    description: ''
});

// Populate form when task changes or modal opens
watch(() => [props.isOpen, props.task], () => {
    if (props.isOpen && props.task) {
        const t = props.task;
        form.value.title = t.title || '';
        form.value.priority = t.priority || '';
        form.value.description = t.description || '';

        // Parse deadline if present
        const raw = t.raw?.deadline || t.deadline || '';
        if (raw && typeof raw === 'string' && raw.includes('T')) {
            form.value.dueDate = raw.split('T')[0];
            const timePart = raw.split('T')[1];
            if (timePart) form.value.dueTime = timePart.substring(0, 5);
        } else {
            form.value.dueDate = '';
            form.value.dueTime = '';
        }
        errorMsg.value = '';
    }
}, { immediate: true, deep: true });

const saveTask = async () => {
    if (!form.value.title.trim()) {
        errorMsg.value = 'Title is required.';
        return;
    }

    saving.value = true;
    errorMsg.value = '';

    try {
        const token = localStorage.getItem('sparky_token');
        const taskId = props.task?.id || props.task?.raw?.id;
        if (!taskId) throw new Error('Missing task ID');

        // Build deadline string
        let deadline = null;
        if (form.value.dueDate) {
            deadline = form.value.dueTime
                ? `${form.value.dueDate}T${form.value.dueTime}:00`
                : form.value.dueDate;
        }

        const payload = {
            title: form.value.title.trim(),
            priority: form.value.priority || null,
            deadline: deadline,
            description: form.value.description || null,
        };

        const res = await fetch(`/api/tasks/${taskId}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            throw new Error(err.message || 'Failed to update task.');
        }

        const updated = await res.json();
        emit('task-updated', updated);
        emit('close');
    } catch (e) {
        errorMsg.value = e.message || 'An error occurred.';
    } finally {
        saving.value = false;
    }
};
</script>

<style scoped>
.edit-task-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.52);
    backdrop-filter: blur(4px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
}

.edit-task-card {
    background: #ffffff;
    border-radius: 24px;
    width: 100%;
    max-width: 520px;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    overflow: hidden;
}

@keyframes popIn {
    from { opacity: 0; transform: scale(0.96) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.edit-task-hdr {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px 28px 16px;
    border-bottom: 1px solid #f1f5f9;
}

.edit-task-title {
    font-size: 17px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.modal-x-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    transition: background 0.15s;
}
.modal-x-btn:hover { background: #f1f5f9; }

.edit-task-body {
    padding: 20px 28px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.et-field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
}

.et-row {
    display: flex;
    gap: 16px;
}

.et-label {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.et-input {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 14px;
    color: #1e293b;
    outline: none;
    transition: border-color 0.15s;
    width: 100%;
    box-sizing: border-box;
}
.et-input:focus { border-color: #2563eb; }

.et-select-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.et-select {
    appearance: none;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 36px 10px 14px;
    font-size: 14px;
    color: #1e293b;
    outline: none;
    width: 100%;
    background: white;
    cursor: pointer;
    transition: border-color 0.15s;
}
.et-select:focus { border-color: #2563eb; }

.et-chevron {
    position: absolute;
    right: 12px;
    pointer-events: none;
}

.et-deadline-row {
    display: flex;
    gap: 8px;
}

.et-input-sm {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 9px 10px;
    font-size: 13px;
    color: #1e293b;
    outline: none;
    flex: 1;
    min-width: 0;
    transition: border-color 0.15s;
}
.et-input-sm:focus { border-color: #2563eb; }

.et-textarea-wrap {
    position: relative;
}

.et-textarea {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 14px;
    color: #1e293b;
    width: 100%;
    min-height: 100px;
    resize: vertical;
    outline: none;
    box-sizing: border-box;
    font-family: inherit;
    transition: border-color 0.15s;
}
.et-textarea:focus { border-color: #2563eb; }

.et-char-count {
    font-size: 11px;
    color: #94a3b8;
    text-align: right;
    margin-top: 4px;
}

.et-error {
    color: #dc2626;
    font-size: 13px;
    margin: 0;
    padding: 8px 12px;
    background: #fef2f2;
    border-radius: 8px;
    border: 1px solid #fecaca;
}

.edit-task-footer {
    padding: 16px 28px 24px;
    display: flex;
    gap: 12px;
    justify-content: space-between;
    border-top: 1px solid #f1f5f9;
}

.btn-grey-pill {
    padding: 10px 20px;
    border-radius: 50px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    color: #64748b;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-grey-pill:hover:not(:disabled) { background: #f1f5f9; }

.btn-blue-pill {
    padding: 10px 22px;
    border-radius: 50px;
    border: none;
    background: #2563eb;
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s;
}
.btn-blue-pill:hover:not(:disabled) { background: #1d4ed8; }
.btn-blue-pill:disabled, .btn-grey-pill:disabled { opacity: 0.6; cursor: not-allowed; }

.spin-icon { animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 520px) {
    .et-row { flex-direction: column; gap: 0; }
}
</style>
