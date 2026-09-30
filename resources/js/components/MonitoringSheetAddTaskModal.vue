<template>
    <div class="ms-modal-overlay" v-if="isOpen" @click.self="closeModal">
        <!-- PHASES 1 & 2: FORM PHASES -->
        <div class="ms-modal-card" v-if="currentPhase === 1 || currentPhase === 2">
            <!-- Header -->
            <div class="ms-modal-header">
                <div>
                    <h2 class="ms-modal-title">Add Task to Monitoring Sheet</h2>
                    <p class="ms-modal-subtitle">
                        {{ currentPhase === 1 ? 'Phase 1: Task Information & Details' : 'Phase 2: Contributors & Instructions' }}
                    </p>
                </div>
                <button class="ms-modal-close-btn" @click="closeModal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Progress Step Indicators -->
            <div class="ms-stepper-bar">
                <div class="ms-step-node" :class="{ active: currentPhase === 1, completed: currentPhase > 1 }">
                    <span class="ms-step-num">1</span>
                    <span class="ms-step-txt">Task Info</span>
                </div>
                <div class="ms-step-connector" :class="{ active: currentPhase > 1 }"></div>
                <div class="ms-step-node" :class="{ active: currentPhase === 2, completed: currentPhase > 2 }">
                    <span class="ms-step-num">2</span>
                    <span class="ms-step-txt">Contributors</span>
                </div>
            </div>

            <!-- PHASE 1: Basic Information -->
            <form v-if="currentPhase === 1" @submit.prevent="goToPhase2" class="ms-modal-form">
                <div class="ms-form-grid">
                    <!-- Topic (Full Width) -->
                    <div class="ms-form-group full-width">
                        <label class="ms-form-label">Topic / Article Title <span class="required">*</span></label>
                        <input 
                            v-model="form.topic" 
                            type="text" 
                            class="ms-form-input" 
                            placeholder="e.g. SSC Water and Sanitary Dispenser"
                            required
                        />
                    </div>

                    <!-- Section -->
                    <div class="ms-form-group">
                        <label class="ms-form-label">Section <span class="required">*</span></label>
                        <select v-model="form.section" class="ms-form-select" required @change="onSectionChange">
                            <option value="News">News</option>
                            <option value="Op-Ed">Op-Ed</option>
                            <option value="Opinion">Opinion</option>
                            <option value="Editorial">Editorial</option>
                            <option value="Feature">Feature</option>
                            <option value="Sci-Tech">Sci-Tech</option>
                            <option value="DevCom">DevCom</option>
                            <option value="Sports">Sports</option>
                            <option value="Literary">Literary</option>
                            <option value="Radio Broadcasting">Radio Broadcasting</option>
                        </select>
                    </div>

                    <!-- Type of Article -->
                    <div class="ms-form-group">
                        <label class="ms-form-label">Type of Article</label>
                        <select v-model="form.article_type" class="ms-form-select">
                            <option value="">Select type...</option>
                            <option v-for="t in availableTypes" :key="t" :value="t">{{ t }}</option>
                        </select>
                    </div>

                    <!-- Medium -->
                    <div class="ms-form-group">
                        <label class="ms-form-label">Medium</label>
                        <select v-model="form.medium" class="ms-form-select">
                            <option value="English">English</option>
                            <option value="Filipino">Filipino</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="ms-form-group">
                        <label class="ms-form-label">Priority</label>
                        <select v-model="form.priority" class="ms-form-select">
                            <option value="Low">Low</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>

                    <!-- Deadline Date -->
                    <div class="ms-form-group full-width">
                        <label class="ms-form-label">Deadline Date</label>
                        <input 
                            v-model="form.deadline" 
                            type="date" 
                            class="ms-form-input"
                        />
                    </div>
                </div>

                <!-- Error display -->
                <p v-if="phase1Error" class="ms-error-msg">{{ phase1Error }}</p>

                <!-- Actions -->
                <div class="ms-modal-actions">
                    <button type="button" class="ms-btn-cancel" @click="closeModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Cancel
                    </button>
                    <button type="submit" class="ms-btn-submit">
                        Next
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- PHASE 2: Contributors & Notes -->
            <form v-if="currentPhase === 2" @submit.prevent="submitTask" class="ms-modal-form">
                <div class="ms-form-grid">
                    <!-- Writer Assigned (Searchable dropdown with max 3 visible items) -->
                    <div class="ms-form-group full-width" ref="writerDropdownRef">
                        <label class="ms-form-label">Writer Assigned</label>
                        <div class="search-select-wrapper">
                            <div class="search-input-box" @click="toggleWriterMenu">
                                <input 
                                    v-model="writerSearchQuery" 
                                    type="text" 
                                    class="search-text-input" 
                                    placeholder="Search or select writer (Staff Writers, EIC, Section Editors)..."
                                    @focus="isWriterMenuOpen = true"
                                />
                                <button type="button" class="search-dropdown-arrow" @click.stop="toggleWriterMenu">
                                    <svg :class="{ rotated: isWriterMenuOpen }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                            </div>

                            <!-- Dropdown list limited to 3 items height with scrolling -->
                            <div class="search-dropdown-menu" v-if="isWriterMenuOpen">
                                <div 
                                    v-for="user in filteredWriterOptions" 
                                    :key="user.id" 
                                    class="search-dropdown-item"
                                    :class="{ selected: form.writer_assigned === user.name }"
                                    @click="selectWriter(user)"
                                >
                                    <span class="dropdown-item-name">{{ user.name }}</span>
                                    <span class="dropdown-item-role">{{ formatRole(user.role) }}</span>
                                </div>
                                <div v-if="filteredWriterOptions.length === 0" class="search-dropdown-empty">
                                    No writers found matching "{{ writerSearchQuery }}"
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PJ / Artist Assigned & No Graphics option -->
                    <div class="ms-form-group full-width" ref="artistDropdownRef">
                        <div class="label-with-checkbox-row">
                            <label class="ms-form-label">PJ / Artist Assigned</label>
                            <label class="no-graphics-toggle-label">
                                <input 
                                    type="checkbox" 
                                    v-model="noGraphics" 
                                    @change="handleNoGraphicsChange"
                                    class="no-graphics-checkbox"
                                />
                                <span>No Graphics Needed</span>
                            </label>
                        </div>

                        <div class="search-select-wrapper" :class="{ 'is-disabled': noGraphics }">
                            <div class="search-input-box" @click="!noGraphics && toggleArtistMenu()">
                                <input 
                                    v-model="artistSearchQuery" 
                                    type="text" 
                                    class="search-text-input" 
                                    :placeholder="noGraphics ? 'No Graphics Needed (Folder will not be generated)' : 'Search or select artist (Staff Artists)...'"
                                    :disabled="noGraphics"
                                    @focus="!noGraphics && (isArtistMenuOpen = true)"
                                />
                                <button type="button" class="search-dropdown-arrow" :disabled="noGraphics" @click.stop="!noGraphics && toggleArtistMenu()">
                                    <svg :class="{ rotated: isArtistMenuOpen }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                            </div>

                            <!-- Dropdown list limited to 3 items height with scrolling -->
                            <div class="search-dropdown-menu" v-if="isArtistMenuOpen && !noGraphics">
                                <div 
                                    v-for="user in filteredArtistOptions" 
                                    :key="user.id" 
                                    class="search-dropdown-item"
                                    :class="{ selected: form.artist_assigned === user.name }"
                                    @click="selectArtist(user)"
                                >
                                    <span class="dropdown-item-name">{{ user.name }}</span>
                                    <span class="dropdown-item-role">{{ user.secondary_role || 'Staff Artist' }}</span>
                                </div>
                                <div v-if="filteredArtistOptions.length === 0" class="search-dropdown-empty">
                                    No staff artists found matching "{{ artistSearchQuery }}"
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description / Instructions (Full Width) -->
                    <div class="ms-form-group full-width">
                        <label class="ms-form-label">Description / Instructions</label>
                        <textarea 
                            v-model="form.description" 
                            class="ms-form-textarea" 
                            placeholder="Add background notes or coverage instructions..."
                            rows="3"
                        ></textarea>
                    </div>
                </div>

                <!-- Error display -->
                <p v-if="errorMessage" class="ms-error-msg">{{ errorMessage }}</p>

                <!-- Actions -->
                <div class="ms-modal-actions">
                    <button type="button" class="ms-btn-cancel" @click="currentPhase = 1" :disabled="isSubmitting">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Back
                    </button>
                    <button type="submit" class="ms-btn-submit" :disabled="isSubmitting">
                        <svg v-if="!isSubmitting" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        {{ isSubmitting ? 'Assigning...' : 'Assign Task' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- PHASE 3: SUCCESS PHASE (Matches Photo 2) -->
        <div class="ms-success-card" v-else-if="currentPhase === 3">
            <div class="success-icon-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h2 class="success-title">Assignment Created Successfully</h2>
            <p class="success-subtitle">
                The task has been assigned to the selected contributors and is now ready for progress tracking
            </p>

            <div class="success-btn-stack">
                <button type="button" class="btn-success-close" @click="handleFinish">
                    Close
                </button>
                <button type="button" class="btn-success-view" @click="handleFinish">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="7" y1="17" x2="17" y2="7"></line>
                        <polyline points="7 7 17 7 17 17"></polyline>
                    </svg>
                    View Assignments
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    sheetId: {
        type: [Number, String],
        required: true
    },
    sheetTitle: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['close', 'entry-added']);

const currentPhase = ref(1);
const isSubmitting = ref(false);
const phase1Error = ref('');
const errorMessage = ref('');
const allUsers = ref([]);

// Search and dropdown states
const writerSearchQuery = ref('');
const isWriterMenuOpen = ref(false);
const writerDropdownRef = ref(null);

const artistSearchQuery = ref('');
const isArtistMenuOpen = ref(false);
const artistDropdownRef = ref(null);
const noGraphics = ref(false);

const articleTypesMap = {
    'News': ['Full News', 'Special Report', 'News Bit', 'News Feature', 'In-Depth News'],
    'Op-Ed': ['Opinion', 'Spark Agent', 'Letter to the Editor', 'Editorial'],
    'Opinion': ['Column', 'Spark Agent', 'Letter to the Editor'],
    'Editorial': ['Editorial', 'Editorial Board Stance'],
    'Feature': ['General Feature', 'Human Interest', 'Profile', 'SciTech', 'Lifestyle'],
    'Sci-Tech': ['Science Feature', 'Tech Innovation', 'Health & Environment', 'Research Spotlight'],
    'DevCom': ['Community Development', 'Advocacy Piece', 'Campus Development', 'Feature-Style'],
    'Sports': ['News', 'News Feature', 'Sports Column', 'Game Coverage', 'Athlete Profile'],
    'Literary': ['Poem/Tula', 'Flash Fiction/Dagli', 'Short Story/Maikling Kwento', 'Screenplay', 'Essay/Sanaysay'],
    'Radio Broadcasting': ['Newscast', 'Radio Drama', 'Infomercial', 'Documentary', 'Field Report', 'Talk Show']
};

const form = ref({
    topic: '',
    section: 'News',
    article_type: 'Full News',
    medium: 'English',
    writer_assigned: '',
    media_type: 'Photo/s',
    artist_assigned: '',
    priority: 'Moderate',
    deadline: '',
    description: ''
});

const formatRole = (role) => {
    if (role === 'eic') return 'Editor-in-Chief';
    if (role === 'section_editor') return 'Section Editor';
    if (role === 'staff_writer') return 'Staff Writer';
    if (role === 'staff_artist') return 'Staff Artist';
    return (role || '').replace('_', ' ');
};

const availableTypes = computed(() => {
    return articleTypesMap[form.value.section] || [];
});

const onSectionChange = () => {
    const types = availableTypes.value;
    form.value.article_type = types.length > 0 ? types[0] : '';
};

// Fetch users for dropdown selections
const fetchUsers = async () => {
    try {
        const token = localStorage.getItem('sparky_token');
        const response = await fetch('/api/users', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        if (response.ok) {
            const data = await response.json();
            allUsers.value = Array.isArray(data) ? data : (data.users || []);
        }
    } catch (e) {
        console.warn('Could not fetch users list', e);
    }
};

// Filter writers: staff_writers, eic, section_editor
const filteredWriterOptions = computed(() => {
    const query = writerSearchQuery.value.toLowerCase().trim();
    const writers = allUsers.value.filter(u => {
        if (u.is_active === false) return false;
        return ['staff_writer', 'eic', 'section_editor'].includes(u.role);
    });

    if (!query) return writers;
    return writers.filter(u => u.name.toLowerCase().includes(query));
});

// Filter artists: staff_artist
const filteredArtistOptions = computed(() => {
    const query = artistSearchQuery.value.toLowerCase().trim();
    const artists = allUsers.value.filter(u => {
        if (u.is_active === false) return false;
        return u.role === 'staff_artist';
    });

    if (!query) return artists;
    return artists.filter(u => u.name.toLowerCase().includes(query));
});

const toggleWriterMenu = () => {
    isWriterMenuOpen.value = !isWriterMenuOpen.value;
    if (isWriterMenuOpen.value) isArtistMenuOpen.value = false;
};

const selectWriter = (user) => {
    form.value.writer_assigned = user.name;
    writerSearchQuery.value = user.name;
    isWriterMenuOpen.value = false;
};

const toggleArtistMenu = () => {
    isArtistMenuOpen.value = !isArtistMenuOpen.value;
    if (isArtistMenuOpen.value) isWriterMenuOpen.value = false;
};

const selectArtist = (user) => {
    form.value.artist_assigned = user.name;
    artistSearchQuery.value = user.name;
    isArtistMenuOpen.value = false;
};

const handleNoGraphicsChange = () => {
    if (noGraphics.value) {
        form.value.artist_assigned = 'No Graphics';
        form.value.media_type = 'No Media';
        artistSearchQuery.value = '';
        isArtistMenuOpen.value = false;
    } else {
        form.value.artist_assigned = '';
        form.value.media_type = 'Photo/s';
    }
};

const goToPhase2 = () => {
    phase1Error.value = '';
    if (!form.value.topic || !form.value.topic.trim()) {
        phase1Error.value = 'Please enter a topic / article title.';
        return;
    }
    currentPhase.value = 2;
};

const submitTask = async () => {
    try {
        isSubmitting.value = true;
        errorMessage.value = '';
        const token = localStorage.getItem('sparky_token');

        const artistValue = noGraphics.value ? 'No Graphics' : (form.value.artist_assigned || null);
        const mediaTypeValue = noGraphics.value ? 'No Media' : (form.value.media_type || 'Photo/s');

        const response = await fetch(`/api/monitoring-sheets/${props.sheetId}/entries`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                topic: form.value.topic,
                section: form.value.section,
                article_type: form.value.article_type || null,
                medium: form.value.medium || 'English',
                writer_assigned: form.value.writer_assigned || null,
                artist_assigned: artistValue,
                media_type: mediaTypeValue,
                interview_completed: false,
                has_files: false,
                description: form.value.description || null,
                priority: form.value.priority || 'Moderate',
                deadline: form.value.deadline || null,
                current_status: 'Pending'
            })
        });

        if (response.ok) {
            emit('entry-added');
            currentPhase.value = 3; // Advance to Success Phase
        } else {
            const data = await response.json().catch(() => ({}));
            errorMessage.value = data.message || 'Failed to add entry to monitoring sheet.';
        }
    } catch (e) {
        console.error('Error adding monitoring sheet entry:', e);
        errorMessage.value = 'An error occurred. Please try again.';
    } finally {
        isSubmitting.value = false;
    }
};

const handleFinish = () => {
    emit('entry-added');
    closeModal();
};

const closeModal = () => {
    emit('close');
    setTimeout(() => {
        currentPhase.value = 1;
    }, 200);
};

const handleClickOutside = (event) => {
    if (writerDropdownRef.value && !writerDropdownRef.value.contains(event.target)) {
        isWriterMenuOpen.value = false;
    }
    if (artistDropdownRef.value && !artistDropdownRef.value.contains(event.target)) {
        isArtistMenuOpen.value = false;
    }
};

watch(() => props.isOpen, (newVal) => {
    if (newVal) {
        currentPhase.value = 1;
        phase1Error.value = '';
        errorMessage.value = '';
        writerSearchQuery.value = '';
        artistSearchQuery.value = '';
        noGraphics.value = false;
        form.value = {
            topic: '',
            section: 'News',
            article_type: 'Full News',
            medium: 'English',
            writer_assigned: '',
            media_type: 'Photo/s',
            artist_assigned: '',
            priority: 'Moderate',
            deadline: '',
            description: ''
        };
        if (allUsers.value.length === 0) {
            fetchUsers();
        }
    }
});

onMounted(() => {
    fetchUsers();
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
.ms-modal-overlay {
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
    font-family: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.ms-modal-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 620px;
    padding: 32px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: msModalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    max-height: 90vh;
    overflow-y: auto;
}

@keyframes msModalPop {
    from { opacity: 0; transform: scale(0.96) translateY(8px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.ms-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 18px;
}

.ms-modal-title {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.4px;
}

.ms-modal-subtitle {
    font-size: 13px;
    color: #64748b;
    margin: 4px 0 0 0;
    font-weight: 600;
}

.ms-modal-close-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 6px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.ms-modal-close-btn:hover {
    background: #f1f5f9;
}

/* Stepper Bar */
.ms-stepper-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 24px;
    padding: 10px 16px;
    background: #f8fafc;
    border-radius: 16px;
    border: 1px solid #f1f5f9;
}

.ms-step-node {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #94a3b8;
    font-size: 13px;
    font-weight: 700;
}

.ms-step-node.active {
    color: #1d6bf3;
}

.ms-step-node.completed {
    color: #16a34a;
}

.ms-step-num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 800;
}

.ms-step-node.active .ms-step-num {
    background: #1d6bf3;
    color: #ffffff;
}

.ms-step-node.completed .ms-step-num {
    background: #dcfce7;
    color: #16a34a;
}

.ms-step-connector {
    width: 40px;
    height: 2px;
    background: #e2e8f0;
}

.ms-step-connector.active {
    background: #16a34a;
}

/* Form Styles */
.ms-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px 14px;
}

.ms-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    position: relative;
}

.ms-form-group.full-width {
    grid-column: span 2;
}

.ms-form-label {
    font-size: 12.5px;
    font-weight: 700;
    color: #334155;
}

.required {
    color: #ef4444;
}

.label-with-checkbox-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.no-graphics-toggle-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    color: #ef4444;
    cursor: pointer;
}

.no-graphics-checkbox {
    accent-color: #ef4444;
    cursor: pointer;
}

.ms-form-input,
.ms-form-select,
.ms-form-textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 13.5px;
    color: #0f172a;
    box-sizing: border-box;
    outline: none;
    background: #ffffff;
    font-family: inherit;
    transition: all 0.2s;
}

.ms-form-input:focus,
.ms-form-select:focus,
.ms-form-textarea:focus {
    border-color: #1d6bf3;
    box-shadow: 0 0 0 3px rgba(29, 107, 243, 0.12);
}

.ms-form-textarea {
    resize: vertical;
    min-height: 85px;
}

/* Searchable Combobox with Max 3 Items Visible */
.search-select-wrapper {
    position: relative;
    width: 100%;
}

.search-select-wrapper.is-disabled {
    opacity: 0.6;
    pointer-events: none;
}

.search-input-box {
    display: flex;
    align-items: center;
    position: relative;
}

.search-text-input {
    width: 100%;
    padding: 10px 36px 10px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 13.5px;
    color: #0f172a;
    box-sizing: border-box;
    outline: none;
    background: #ffffff;
    font-family: inherit;
    transition: all 0.2s;
}

.search-text-input:focus {
    border-color: #1d6bf3;
    box-shadow: 0 0 0 3px rgba(29, 107, 243, 0.12);
}

.search-dropdown-arrow {
    position: absolute;
    right: 12px;
    background: transparent;
    border: none;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2px;
}

.search-dropdown-arrow svg {
    transition: transform 0.2s;
}

.search-dropdown-arrow svg.rotated {
    transform: rotate(180deg);
}

/* Dropdown Menu - EXACTLY 3 ITEMS VISIBLE WITH SMOOTH SCROLL */
.search-dropdown-menu {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
    z-index: 50;
    max-height: 135px; /* exactly 3 items at ~44px each */
    overflow-y: auto;
    padding: 4px;
}

.search-dropdown-menu::-webkit-scrollbar {
    width: 5px;
}

.search-dropdown-menu::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.search-dropdown-item {
    padding: 9px 12px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.15s;
}

.search-dropdown-item:hover {
    background: #f1f5f9;
}

.search-dropdown-item.selected {
    background: #eff6ff;
    color: #1d6bf3;
}

.dropdown-item-name {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
}

.dropdown-item-role {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    background: #f8fafc;
    padding: 2px 6px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}

.search-dropdown-empty {
    padding: 12px;
    text-align: center;
    color: #94a3b8;
    font-size: 12.5px;
}

.ms-error-msg {
    color: #ef4444;
    font-size: 12.5px;
    font-weight: 600;
    margin: 14px 0 0 0;
}

/* Actions */
.ms-modal-actions {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin-top: 24px;
    padding-top: 18px;
    border-top: 1px solid #f1f5f9;
}

/* Same look as the Back / Next buttons of the Assign Task modal */
.ms-btn-cancel {
    background: transparent;
    border: none;
    color: #64748b;
    font-size: 14px;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.2s;
}

.ms-btn-cancel:hover:not(:disabled) {
    color: #0f172a;
    background: #f1f5f9;
}

.ms-btn-submit {
    background-color: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 12px 36px;
    border-radius: 30px;
    font-size: 15px;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3);
    transition: all 0.2s;
}

.ms-btn-submit:hover:not(:disabled) {
    background-color: #1558c6;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(29, 107, 243, 0.4);
}

.ms-btn-submit:disabled {
    background-color: #93c5fd;
    cursor: wait;
    opacity: 0.7;
    transform: none;
    box-shadow: none;
}

.ms-btn-cancel:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* ==========================================================================
   PHASE 3: SUCCESS STATE (MATCHES PHOTO 2 EXACTLY)
   ========================================================================== */
.ms-success-card {
    background: #ffffff;
    border-radius: 32px;
    width: 100%;
    max-width: 480px;
    padding: 44px 36px 36px 36px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    text-align: center;
    animation: msModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.success-icon-circle {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    background-color: #dcfce7;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px auto;
}

.success-title {
    font-size: 23px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 10px 0;
    letter-spacing: -0.4px;
}

.success-subtitle {
    font-size: 14.5px;
    color: #475569;
    margin: 0 0 32px 0;
    line-height: 1.45;
}

.success-btn-stack {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.btn-success-close {
    width: 100%;
    background-color: #e2e8f0;
    color: #475569;
    border: none;
    padding: 14px;
    border-radius: 30px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.2s;
}

.btn-success-close:hover {
    background-color: #cbd5e1;
    color: #0f172a;
}

.btn-success-view {
    width: 100%;
    background-color: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 14px;
    border-radius: 30px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: inherit;
    box-shadow: 0 6px 18px rgba(29, 107, 243, 0.35);
    transition: all 0.2s;
}

.btn-success-view:hover {
    background-color: #1557b0;
    box-shadow: 0 8px 22px rgba(29, 107, 243, 0.45);
}

@media (max-width: 600px) {
    .ms-form-grid {
        grid-template-columns: 1fr;
    }
    .ms-form-group.full-width {
        grid-column: span 1;
    }
}
</style>
