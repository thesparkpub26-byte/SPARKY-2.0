<template>
    <div class="assign-task-modal-overlay" v-if="isOpen" @click.self="closeModal">
        <div class="assign-task-modal-card">
            
            <!-- STEP 1 & 2 HEADER -->
            <div class="modal-hdr" v-if="currentStep < 3">
                <h2 class="modal-blue-title">Assign New Task</h2>
                <button class="modal-x-btn" @click="closeModal" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- STEP 1: Article Info, Section, Priority, Deadline -->
            <div class="modal-body-step" v-if="currentStep === 1">
                <div class="field-group">
                    <label class="field-label">Article About</label>
                    <input type="text" class="pill-input" v-model="form.title" placeholder="Write what the article is about..." />
                </div>

                <div class="field-row">
                    <div class="field-group">
                        <label class="field-label">Section</label>
                        <div class="select-wrapper">
                            <select class="pill-select" v-model="form.section">
                                <option value="News">News</option>
                                <option value="Opinion">Opinion</option>
                                <option value="Editorial">Editorial</option>
                                <option value="Feature">Feature</option>
                                <option value="Sci-Tech">Sci-Tech</option>
                                <option value="DevCom">DevCom</option>
                                <option value="Sports">Sports</option>
                                <option value="Literary">Literary</option>
                                <option value="Radio Broadcasting">Radio Broadcasting</option>
                            </select>
                            <svg class="select-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Priority</label>
                        <div class="select-wrapper">
                            <select class="pill-select" v-model="form.priority">
                                <option value="">Choose Level</option>
                                <option value="Low">Low</option>
                                <option value="Moderate">Moderate</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                            <svg class="select-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Deadline</label>
                    <div class="deadline-inputs-row">
                        <div class="input-with-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <input type="date" class="pill-input-inner" v-model="form.dueDate" />
                        </div>
                        <div class="input-with-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <input type="time" class="pill-input-inner" v-model="form.dueTime" />
                        </div>
                    </div>
                </div>

                <div class="modal-footer-row">
                    <button class="back-link-btn" @click="closeModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Back
                    </button>
                    <button class="btn-blue-pill" @click="currentStep = 2">Next</button>
                </div>
            </div>

            <!-- STEP 2: Assignees & Description -->
            <div class="modal-body-step" v-if="currentStep === 2">
                <div class="field-group">
                    <label class="field-label">Writer Assignee</label>
                    <div class="select-wrapper">
                        <select class="pill-select" v-model="form.writer">
                            <option value="">Select Writer</option>
                            <option :value="currentUser.name || 'Yourself'">
                                Yourself ({{ currentUser.name || 'Current User' }})
                            </option>
                            <option v-for="user in filteredWriters" :key="user.id || user.name" :value="user.name">
                                {{ user.name }} ({{ user.secondary_role || formatRole(user.role) }})
                            </option>
                        </select>
                        <svg class="select-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </div>
                </div>

                <div class="field-group">
                    <div class="media-label-row">
                        <label class="field-label">
                            {{ isRadioBroadcasting ? 'Videographer' : 'PJ/Artist Assignee' }}
                        </label>
                        <label class="no-graphics-toggle">
                            <input type="checkbox" v-model="form.noGraphics" />
                            <span>{{ isRadioBroadcasting ? 'No Video Needed' : 'No Graphics Needed' }}</span>
                        </label>
                    </div>
                    <div v-if="!form.noGraphics" class="select-wrapper">
                        <select class="pill-select" v-model="form.mediaArtist">
                            <option value="">
                                {{ isRadioBroadcasting ? 'Select Videographer / Media' : 'Select Artist / PJ' }}
                            </option>
                            <option v-for="user in filteredMediaAssignees" :key="user.id || user.name" :value="user.name">
                                {{ user.name }} ({{ user.secondary_role || (isRadioBroadcasting ? 'Videographer' : 'Staff Artist') }})
                            </option>
                        </select>
                        <svg class="select-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </div>
                    <div v-else class="no-graphics-pill">
                        {{ isRadioBroadcasting ? 'No Video Needed' : 'No Graphics Needed' }}
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Description</label>
                    <div class="textarea-container">
                        <textarea class="pill-textarea" v-model="form.description" maxlength="500" placeholder="Write something here..."></textarea>
                        <div class="char-count">{{ form.description.length }}/500</div>
                    </div>
                </div>

                <div class="modal-footer-row">
                    <button class="back-link-btn" @click="currentStep = 1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Back
                    </button>
                    <button class="btn-blue-pill" @click="submitTask" :disabled="isSubmitting">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ isSubmitting ? 'Adding...' : 'Done' }}
                    </button>
                </div>
                <p v-if="errorMessage" class="assign-task-error">{{ errorMessage }}</p>
            </div>

            <!-- STEP 3: Confirmation -->
            <div class="modal-body-step confirmation-step" v-if="currentStep === 3">
                <div class="success-icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                
                <h3 class="success-title">Assignment Created Successfully</h3>
                <p class="success-subtitle">
                    The task has been assigned to the selected contributors and is now ready for progress tracking
                </p>

                <div class="success-actions-col">
                    <button class="btn-grey-pill" @click="closeModal">Close</button>
                    <button class="btn-blue-pill btn-full-width" @click="onViewAssignments">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                        View Assignments
                    </button>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    defaultSection: {
        type: String,
        default: 'News'
    },
    monitoringSheetId: {
        type: Number,
        required: false,
        default: null
    }
});

const emit = defineEmits(['close', 'view-assignments', 'task-added']);

const currentStep = ref(1);
const errorMessage = ref('');
const isSubmitting = ref(false);
const allUsers = ref([]);

const currentUser = ref(JSON.parse(localStorage.getItem('sparky_user') || '{}'));

const formatRole = (role) => {
    if (role === 'eic') return 'Editor in Chief';
    if (role === 'system') return 'System';
    if (role === 'staff_broadcaster') return 'Staff Broadcaster';
    return (role || '').split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
};

const form = ref({
    title: '',
    coverage: '',
    section: props.defaultSection || 'News',
    noGraphics: false,
    priority: '',
    dueDate: '',
    dueTime: '',
    writer: '',
    mediaArtist: '',
    description: ''
});

const isRadioBroadcasting = computed(() => form.value.section === 'Radio Broadcasting');

// Fetch users from API
const fetchUsers = async () => {
    try {
        const response = await fetch('/api/users', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('sparky_token')}`,
                'Accept': 'application/json'
            }
        });
        if (response.ok) {
            const data = await response.json();
            allUsers.value = Array.isArray(data) ? data : (data.users || []);
        }
    } catch (e) {
        console.warn('Could not fetch users list, using fallback defaults', e);
    }
};

onMounted(() => {
    fetchUsers();
});

// Filter writers matching the selected section from registered users in database
const filteredWriters = computed(() => {
    const section = form.value.section;
    const currentName = currentUser.value?.name || '';
    
    return allUsers.value
        .filter(u => u.is_active !== false)
        .filter(u => {
            // Exclude logged in user from regular list since they are present as "Yourself"
            if (currentName && u.name === currentName) return false;

            const secRole = (u.secondary_role || '').toLowerCase();
            const role = (u.role || '').toLowerCase();

            // Video roles (videographers, video editors, technical directors) are NEVER writers
            const isVideoRole = ['videographer', 'video editor', 'technical director'].some(vr => secRole.includes(vr));
            if (isVideoRole) {
                return false;
            }

            // Artists are never writers
            if (role === 'staff_artist' || secRole.includes('artist') || secRole.includes('photojournalist') || secRole.includes('cartoonist') || secRole.includes('illustrator')) {
                return false;
            }

            const isPresenterRole = secRole.includes('presenter') || secRole.includes('head broadcaster');

            // Radio Broadcasting: ONLY news presenters
            if (section === 'Radio Broadcasting') {
                return isPresenterRole;
            }

            // Presenters can only be assigned under Radio Broadcasting
            if (isPresenterRole || role === 'staff_broadcaster') {
                return false;
            }

            // Standard print/text sections:
            if (section === 'News') {
                return secRole.includes('news') && !secRole.includes('presenter');
            } else if (section === 'Opinion') {
                return secRole.includes('opinion');
            } else if (section === 'Editorial') {
                return secRole.includes('editorial') || secRole.includes('copyreader');
            } else if (section === 'Feature') {
                return secRole.includes('feature');
            } else if (section === 'Sci-Tech') {
                return secRole.includes('sci') || secRole.includes('tech');
            } else if (section === 'DevCom') {
                return secRole.includes('devcom') || secRole.includes('devcomm');
            } else if (section === 'Sports') {
                return secRole.includes('sport');
            } else if (section === 'Literary') {
                return secRole.includes('literary');
            }
            return false;
        });
});

// Filter media assignees: staff_artist for regular sections, Videographer/Video Editor/Technical Director for Radio Broadcasting
const filteredMediaAssignees = computed(() => {
    if (isRadioBroadcasting.value) {
        // Only Videographer, Video Editor, Technical Director (EXCLUDE News Presenter and other non-video roles)
        const videoKeywords = ['videographer', 'video editor', 'technical director'];
        return allUsers.value
            .filter(u => u.is_active !== false)
            .filter(u => {
                const secRole = (u.secondary_role || '').toLowerCase();
                return videoKeywords.some(keyword => secRole.includes(keyword)) && !secRole.includes('presenter');
            });
    } else {
        // Regular sections: staff_artist only (Photojournalist, Layout Artist, Graphic Artist, Cartoonist, Illustrator)
        return allUsers.value
            .filter(u => u.is_active !== false)
            .filter(u => {
                const secRole = (u.secondary_role || '').toLowerCase();
                const role = (u.role || '').toLowerCase();
                return role === 'staff_artist' || secRole.includes('artist') || secRole.includes('photojournalist') || secRole.includes('cartoonist') || secRole.includes('illustrator');
            });
    }
});

// Reset writer and mediaArtist whenever section changes
watch(() => form.value.section, () => {
    form.value.writer = '';
    form.value.mediaArtist = '';
});

watch(() => props.isOpen, (newVal) => {
    if (newVal) {
        currentStep.value = 1;
        errorMessage.value = '';
        currentUser.value = JSON.parse(localStorage.getItem('sparky_user') || '{}');
        if (allUsers.value.length === 0) {
            fetchUsers();
        }
    }
});

const closeModal = () => {
    emit('close');
    setTimeout(() => {
        currentStep.value = 1;
    }, 200);
};

const onViewAssignments = () => {
    emit('view-assignments');
    closeModal();
};

const submitTask = async () => {
    errorMessage.value = '';
    isSubmitting.value = true;

    // Validation: Check required fields
    const requiredFields = [
        { field: form.value.title, name: 'Article About' },
        { field: form.value.section, name: 'Section' },
        { field: form.value.priority, name: 'Priority' },
        { field: form.value.dueDate, name: 'Deadline Date' },
        { field: form.value.writer, name: 'Writer Assignee' }
    ];

    const missingFields = requiredFields.filter(({ field }) => !field || field.trim() === '');

    if (missingFields.length > 0) {
        const fieldNames = missingFields.map(({ name }) => name).join(', ');
        errorMessage.value = `Please fill in the following required fields: ${fieldNames}`;
        isSubmitting.value = false;
        return;
    }

    // Validate media assignment if graphics/video are needed
    if (!form.value.noGraphics && !form.value.mediaArtist) {
        const mediaField = isRadioBroadcasting.value ? 'Videographer' : 'PJ/Artist Assignee';
        const toggleOption = isRadioBroadcasting.value ? 'No Video Needed' : 'No Graphics Needed';
        errorMessage.value = `Please select a ${mediaField} or check "${toggleOption}"`;
        isSubmitting.value = false;
        return;
    }

    try {
        const token = localStorage.getItem('sparky_token');
        const priorityMap = {
            'Low': 'low',
            'Moderate': 'medium',
            'Urgent': 'urgent'
        };
        const mappedPriority = priorityMap[form.value.priority] || 'medium';

        // 1. Resolve Writer User
        let writerUser = null;
        if (form.value.writer === 'Yourself' || form.value.writer === currentUser.value?.name) {
            writerUser = currentUser.value;
        } else {
            writerUser = allUsers.value.find(u => u.name === form.value.writer);
        }

        const writerId = writerUser ? writerUser.id : currentUser.value?.id;

        // 2. Submit Writer Task to /api/tasks
        const notesContent = [
            form.value.section ? `Section: ${form.value.section}` : '',
            form.value.coverage ? `Coverage: ${form.value.coverage}` : '',
            form.value.dueTime ? `Due Time: ${form.value.dueTime}` : '',
            form.value.mediaArtist ? `Media Artist: ${form.value.mediaArtist}` : ''
        ].filter(Boolean).join(' | ');

        const responseWriter = await fetch('/api/tasks', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                title: form.value.title,
                description: form.value.description || `Article assignment about "${form.value.title}" in ${form.value.section}.`,
                assignee_id: writerId,
                type: 'writing',
                priority: mappedPriority,
                deadline: form.value.dueDate || null,
                notes: notesContent || null
            })
        });

        if (!responseWriter.ok) {
            const data = await responseWriter.json().catch(() => ({}));
            errorMessage.value = data.message || 'Failed to assign writer task. Please try again.';
            isSubmitting.value = false;
            return;
        }

        // 3. If Media Artist / Videographer is selected, create their task on /api/tasks too
        if (!form.value.noGraphics && form.value.mediaArtist) {
            const artistUser = allUsers.value.find(u => u.name === form.value.mediaArtist);
            if (artistUser) {
                await fetch('/api/tasks', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        title: `${form.value.title} (${isRadioBroadcasting.value ? 'Video Production' : 'Visuals / Graphics'})`,
                        description: form.value.description || `Media assignment for "${form.value.title}" in ${form.value.section}.`,
                        assignee_id: artistUser.id,
                        type: isRadioBroadcasting.value ? 'layout' : 'illustration',
                        priority: mappedPriority,
                        deadline: form.value.dueDate || null,
                        notes: notesContent || null
                    })
                });
            }
        }

        emit('task-added');
        currentStep.value = 3;
    } catch (error) {
        console.error('Error assigning task:', error);
        errorMessage.value = 'An error occurred. Please try again.';
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<style scoped>
.assign-task-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(5px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
}

.assign-task-modal-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 600px;
    padding: 32px 36px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: modalPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes modalPopIn {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-hdr {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.modal-blue-title {
    color: #1d6bf3;
    font-size: 26px;
    font-weight: 800;
    margin: 0;
    letter-spacing: -0.5px;
    font-family: 'Manrope', -apple-system, sans-serif;
}

.modal-x-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: background 0.2s;
}

.modal-x-btn:hover {
    background: #f1f5f9;
}

.modal-body-step {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.field-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 100%;
}

.field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    width: 100%;
}

.modal-body-step > .field-row:has(.field-group:only-child) {
    grid-template-columns: 1fr;
}

.field-label {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    text-align: left;
    font-family: 'Manrope', sans-serif;
}

.pill-input {
    width: 100%;
    height: 44px;
    padding: 0 16px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    color: #0f172a;
    box-sizing: border-box;
    outline: none;
    transition: all 0.2s;
    background: #ffffff;
    font-family: 'Manrope', sans-serif;
}

.pill-input:focus {
    border-color: #1d6bf3;
    box-shadow: 0 0 0 3px rgba(29, 107, 243, 0.12);
}

.select-wrapper {
    position: relative;
    width: 100%;
}

.pill-select {
    width: 100%;
    height: 44px;
    padding: 0 38px 0 16px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    color: #0f172a;
    box-sizing: border-box;
    outline: none;
    appearance: none;
    background: #ffffff;
    cursor: pointer;
    font-family: 'Manrope', sans-serif;
    transition: all 0.2s;
}

.pill-select:focus {
    border-color: #1d6bf3;
    box-shadow: 0 0 0 3px rgba(29, 107, 243, 0.12);
}

.select-chevron {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}

.deadline-inputs-row {
    display: flex;
    gap: 10px;
    width: 100%;
}

.input-with-icon {
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 0 12px;
    height: 44px;
    flex: 1;
    box-sizing: border-box;
    background: #ffffff;
    transition: all 0.2s;
}

.input-with-icon:focus-within {
    border-color: #1d6bf3;
    box-shadow: 0 0 0 3px rgba(29, 107, 243, 0.12);
}

.pill-input-inner {
    border: none;
    background: transparent;
    outline: none;
    font-size: 13px;
    color: #0f172a;
    width: 100%;
    font-family: 'Manrope', sans-serif;
}

.textarea-container {
    position: relative;
    width: 100%;
}

.pill-textarea {
    width: 100%;
    min-height: 120px;
    padding: 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    font-size: 14px;
    color: #0f172a;
    box-sizing: border-box;
    outline: none;
    resize: vertical;
    font-family: 'Manrope', sans-serif;
    transition: all 0.2s;
}

.pill-textarea:focus {
    border-color: #1d6bf3;
    box-shadow: 0 0 0 3px rgba(29, 107, 243, 0.12);
}

.char-count {
    position: absolute;
    right: 14px;
    bottom: 12px;
    font-size: 11px;
    color: #94a3b8;
    font-weight: 600;
}

.modal-footer-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 12px;
    width: 100%;
}

.back-link-btn {
    background: transparent;
    border: none;
    color: #64748b;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.2s;
}

.back-link-btn:hover {
    color: #0f172a;
    background: #f1f5f9;
}

.btn-blue-pill {
    background-color: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 12px 36px;
    border-radius: 30px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3);
    transition: all 0.2s;
}

.btn-blue-pill:hover {
    background-color: #1558c6;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(29, 107, 243, 0.4);
}

.btn-blue-pill:disabled {
    background-color: #93c5fd;
    cursor: wait;
    opacity: 0.7;
    transform: none;
    box-shadow: none;
}

.assign-task-error {
    margin: 12px 0 0;
    color: #c62828;
    font-size: 12px;
    text-align: right;
    font-weight: 600;
}

.btn-blue-pill:active {
    transform: translateY(0);
}

.btn-full-width {
    width: 100%;
}

.confirmation-step {
    align-items: center;
    text-align: center;
    padding: 12px 0 0 0;
}

.success-icon-circle {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background-color: #dcfce7;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.success-title {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 8px 0;
    font-family: 'Manrope', sans-serif;
}

.success-subtitle {
    font-size: 13px;
    color: #64748b;
    line-height: 1.5;
    margin: 0 0 28px 0;
    font-family: 'Manrope', sans-serif;
    max-width: 340px;
}

.success-actions-col {
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
}

.btn-grey-pill {
    background-color: #f1f5f9;
    color: #475569;
    border: none;
    padding: 12px;
    width: 100%;
    border-radius: 30px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    font-family: 'Manrope', sans-serif;
}

.btn-grey-pill:hover {
    background-color: #e2e8f0;
    color: #0f172a;
}

.media-label-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.no-graphics-toggle {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    font-family: 'Manrope', sans-serif;
    user-select: none;
}

.no-graphics-toggle input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #ef4444;
    cursor: pointer;
    flex-shrink: 0;
}

.no-graphics-pill {
    height: 44px;
    display: flex;
    align-items: center;
    padding: 0 16px;
    background: #fef2f2;
    border: 1.5px dashed #fca5a5;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 700;
    color: #ef4444;
    font-family: 'Manrope', sans-serif;
    letter-spacing: 0.2px;
}
</style>
