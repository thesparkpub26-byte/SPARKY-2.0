<template>
<div class="monitoring-page-wrapper">
<div class="header-bar">
        <div class="header-title">
            <img src="/assets/Spark_Logo.png" alt="Logo">
            <div>
                <p class="eyebrow">{{ sheet?.press_work?.academic_year || 'Publication workspace' }}</p>
                <h1>{{ sheet?.press_work?.title || 'TheSPARK' }} <span>{{ sheet?.publication_type || 'Newsletter' }} Monitoring Sheet</span></h1>
            </div>
        </div>
        <div class="header-actions">
            <span class="save-status"><i></i> Auto-saving enabled</span>
            <button class="back-button" type="button" @click="goBack">Back to Press Works</button>
            <button class="add-task-button" type="button" @click.prevent="isAddTaskModalOpen = true">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Task
            </button>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="group-header group-basic sticky-col" rowspan="2" style="top:0;">Topic</th>
                    <th class="group-header group-basic" colspan="6">BASIC INFORMATION</th>
                    <th class="group-header group-data" colspan="3">DATA GATHERING</th>
                    <th class="group-header group-edit" colspan="1">EDITING PROCESS</th>
                </tr>
                <tr>
                    <th class="col-header">Section</th>
                    <th class="col-header">Type of Article</th>
                    <th class="col-header">Medium</th>
                    <th class="col-header">Writer Assigned</th>
                    <th class="col-header">Photo/Graphics</th>
                    <th class="col-header">PJ/Artist Assigned</th>

                    <th class="col-header group-data">Interview</th>
                    <th class="col-header group-data">Storage</th>
                    <th class="col-header group-data">Upload Article</th>

                    <th class="col-header group-edit">Current Status</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(sectionEntries, section) in groupedEntries" :key="section">
                    <tr v-for="entry in sectionEntries" :key="entry.id">
                        <td class="sticky-col" style="text-align: center;">{{ entry.topic || 'Untitled' }}</td>
                        <td><strong>{{ entry.section }}</strong></td>
                        <td>
                            <span v-if="entry.article_type" class="pub-badge" :style="getArticleTypeStyle(entry.article_type)">
                                {{ entry.article_type }}
                            </span>
                            <span v-else style="color: #94a3b8; font-size: 12px;">-</span>
                        </td>
                        <td>{{ entry.medium || 'English' }}</td>
                        <td>{{ entry.writer_assigned || '-' }}</td>
                        <td>{{ entry.media_type || 'Photo/s' }}</td>
                        <td>
                            <span v-if="entry.artist_assigned" style="font-weight: 600; color: #334155; font-size: 12px;">
                                {{ entry.artist_assigned }}
                            </span>
                            <span v-else style="color: #94a3b8; font-size: 12px;">-</span>
                        </td>

                        <td><input type="checkbox" v-model="entry.interview_completed" @change="updateEntry(entry)"></td>
                        <td>
                            <!-- No Graphics case -->
                            <span v-if="entry.artist_assigned === 'No Graphics'"
                                style="display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; background: #fef2f2; color: #ef4444; font-weight: 700; font-size: 11px; border-radius: 999px; border: 1px dashed #fca5a5; white-space: nowrap;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                No Graphics Needed
                            </span>
                            <!-- Has files — pressable folder -->
                            <button v-else-if="entry.has_files"
                                @click="openStorage(entry)"
                                style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #e0f2fe; color: #0284c7; font-weight: 700; font-size: 12px; border: 1px solid #bae6fd; border-radius: 6px; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.background='#bae6fd'"
                                onmouseout="this.style.background='#e0f2fe'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                                Open Folder
                            </button>
                            <!-- No files yet — empty folder -->
                            <button v-else
                                @click="openStorage(entry)"
                                style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f8fafc; color: #94a3b8; font-weight: 700; font-size: 12px; border: 1px dashed #cbd5e1; border-radius: 6px; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.background='#f1f5f9'; this.style.color='#475569'"
                                onmouseout="this.style.background='#f8fafc'; this.style.color='#94a3b8'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                                Empty Folder
                            </button>
                        </td>
                        <td>
                            <button @click="openUploadModal(entry)"
                                style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: white; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer; color: #475569; transition: all 0.2s; z-index: 10; position: relative;"
                                onmouseover="this.style.background='#f1f5f9'; this.style.color='#0f172a'; this.style.borderColor='#94a3b8'"
                                onmouseout="this.style.background='white'; this.style.color='#475569'; this.style.borderColor='#cbd5e1'" title="Upload Article">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                            </button>
                        </td>

                        <td>
                            <span class="pub-badge" :style="getStatusStyle(entry.current_status)">
                                {{ entry.current_status || 'Pending' }}
                            </span>
                        </td>
                    </tr>
                </template>
                <tr v-if="entries.length === 0">
                    <td colspan="11" style="text-align: center; padding: 40px; color: #94a3b8;">
                        No entries yet. Click "Add Task" to create your first entry.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Add Task Modal -->
    <AssignTaskModal 
        :is-open="isAddTaskModalOpen" 
        :monitoring-sheet-id="sheet?.id"
        @close="isAddTaskModalOpen = false" 
        @task-added="loadEntries"
    />

    <!-- Upload Article Modal -->
    <div id="uploadModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: white; padding: 32px; border-radius: 16px; width: 80%; max-width: 1200px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #e2e8f0; padding-bottom: 16px;">
                <div>
                    <h2 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 800; color: #0f172a; font-family: 'Manrope', sans-serif;">Upload Article</h2>
                    <p style="margin: 0; color: #64748b; font-size: 14px;">Enter the article details below</p>
                </div>
                <button onclick="window.closeUploadModal()" style="background: #f1f5f9; border: none; cursor: pointer; font-size: 20px; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'; this.style.color='#0f172a'" onmouseout="this.style.background='#f1f5f9'; this.style.color='#64748b'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #1e293b; font-size: 14px; font-family: 'Manrope', sans-serif;">Headline</label>
                <input id="articleHeadline" type="text" placeholder="Enter headline..." style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 15px; color: #0f172a; font-family: 'Inter', sans-serif; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #1e293b; font-size: 14px; font-family: 'Manrope', sans-serif;">Author</label>
                <input id="articleAuthor" type="text" placeholder="Enter author name..." style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 15px; color: #0f172a; font-family: 'Inter', sans-serif; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #1e293b; font-size: 14px; font-family: 'Manrope', sans-serif;">Article Content</label>
                
                <!-- Editing Toolbar -->
                <div style="display: flex; gap: 8px; margin-bottom: 8px; padding: 8px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <button onclick="window.highlightText('#fef08a')" style="padding: 6px 12px; background: #fef08a; border: 1px solid #eab308; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; color: #854d0e; transition: all 0.2s;" onmouseover="this.style.background='#fde047'" onmouseout="this.style.background='#fef08a'">🖍️ Yellow</button>
                    <button onclick="window.highlightText('#fecaca')" style="padding: 6px 12px; background: #fecaca; border: 1px solid #ef4444; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; color: #991b1b; transition: all 0.2s;" onmouseover="this.style.background='#fca5a5'" onmouseout="this.style.background='#fecaca'">🔴 Red</button>
                    <button onclick="window.highlightText('#bbf7d0')" style="padding: 6px 12px; background: #bbf7d0; border: 1px solid #22c55e; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; color: #166534; transition: all 0.2s;" onmouseover="this.style.background='#86efac'" onmouseout="this.style.background='#bbf7d0'">✅ Green</button>
                    <button onclick="window.highlightText('#bfdbfe')" style="padding: 6px 12px; background: #bfdbfe; border: 1px solid #3b82f6; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; color: #1e40af; transition: all 0.2s;" onmouseover="this.style.background='#93c5fd'" onmouseout="this.style.background='#bfdbfe'">🔵 Blue</button>
                    <button onclick="window.clearHighlights()" style="padding: 6px 12px; background: white; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; color: #475569; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">🗑️ Clear</button>
                </div>
                
                <!-- Editable Content Area -->
                <div id="articleContentEditable" contenteditable="true" placeholder="Paste article content here..." style="width: 100%; padding: 16px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 15px; color: #0f172a; font-family: 'Inter', sans-serif; line-height: 1.6; min-height: 200px; max-height: 300px; overflow-y: auto; resize: vertical; transition: all 0.2s; white-space: pre-wrap;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"></div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <button onclick="window.closeUploadModal()" style="padding: 12px 24px; background: white; border: 1.5px solid #e2e8f0; border-radius: 10px; cursor: pointer; font-weight: 600; color: #475569; font-size: 15px; font-family: 'Manrope', sans-serif; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">Cancel</button>
                <button onclick="window.saveArticle()" id="saveArticleBtn" style="padding: 12px 32px; background: #2563eb; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 15px; font-family: 'Manrope', sans-serif; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3); transition: all 0.2s;" onmouseover="this.style.background='#1d4ed8'; this.style.boxShadow='0 6px 18px rgba(37, 99, 235, 0.4)'" onmouseout="this.style.background='#2563eb'; this.style.boxShadow='0 4px 14px rgba(37, 99, 235, 0.3)'">
                    Save Article
                </button>
            </div>
        </div>
    </div>

    
</div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import AssignTaskModal from '../../components/AssignTaskModal.vue';

const router = useRouter();
const route = useRoute();
const sheet = ref(null);
const entries = ref([]);
const isAddTaskModalOpen = ref(false);
const isUploadModalOpen = ref(false);
const selectedEntry = ref(null);
const isSaving = ref(false);
const articleForm = ref({
    headline: '',
    author: '',
    content: ''
});

// Debug: log initial state
console.log('Component mounted, isUploadModalOpen:', isUploadModalOpen.value);

onMounted(async () => {
    if (!route.params.monitoringSheet) return;
    await loadSheet();
    await loadEntries();
    
    // Make functions globally available after component is mounted
    window.closeUploadModal = closeUploadModal;
    window.saveArticle = saveArticle;
    window.highlightText = highlightText;
    window.clearHighlights = clearHighlights;
});

const loadSheet = async () => {
    const response = await fetch(`/api/monitoring-sheets/${route.params.monitoringSheet}`, {
        headers: { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json' },
    });
    if (response.ok) sheet.value = await response.json();
};

const loadEntries = async () => {
    const response = await fetch(`/api/monitoring-sheets/${route.params.monitoringSheet}`, {
        headers: { Authorization: `Bearer ${localStorage.getItem('sparky_token')}`, Accept: 'application/json' },
    });
    if (response.ok) {
        const data = await response.json();
        entries.value = data.entries || [];
        // Save per-entry metadata so FileStorageView can read topic/section
        entries.value.forEach(entry => {
            localStorage.setItem(`sparky_entry_meta_${entry.id}`, JSON.stringify({
                topic: entry.topic,
                section: entry.section,
                sheetId: route.params.monitoringSheet
            }));
        });
    }
};

const selectedCategory = ref('');

const articleTypesMap = {
    'News': ['Full News', 'Special Report', 'News Bit', 'News Feature', 'In-Depth News'],
    'Op-Ed': ['Opinion', 'Spark Agent', 'Letter to the Editor', 'Editorial'],
    'Feature': ['SciTech', 'General Feature'],
    'DevCom': ['Feature-Style'],
    'Sports': ['News', 'News Feature', 'Opinion'],
    'Literary': ['Poem/Tula', 'Flash Fiction/Dagli', 'Short Story/Maikling Kwento', 'Screenplay']
};

const availableArticleTypes = computed(() => {
    if (!selectedCategory.value) return [];
    return articleTypesMap[selectedCategory.value] || [];
});

const groupedEntries = computed(() => {
    const groups = {};
    entries.value.forEach(entry => {
        const section = entry.section || 'News';
        if (!groups[section]) {
            groups[section] = [];
        }
        groups[section].push(entry);
    });
    return groups;
});

const allSections = ['News', 'Feature', 'Editorial', 'Sports', 'Literary', 'DevComm'];

const getArticleTypeStyle = (type) => {
    const styles = {
        'Special Report': { background: '#fce7f3', color: '#db2777' },
        'Full News': { background: '#fef08a', color: '#a16207' },
        'In-Depth News': { background: '#fae8ff', color: '#c026d3' },
        'Opinion': { background: '#f3e8ff', color: '#7e22ce' },
        'News Bit': { background: '#dbeafe', color: '#1d4ed8' },
        'News Feature': { background: '#dcfce7', color: '#15803d' },
        'SciTech': { background: '#e0f2fe', color: '#0284c7' },
        'General Feature': { background: '#fef3c7', color: '#d97706' },
        'Feature-Style': { background: '#f1f5f9', color: '#475569' },
        'Poem/Tula': { background: '#fce7f3', color: '#db2777' },
        'Flash Fiction/Dagli': { background: '#ddd6fe', color: '#7c3aed' },
        'Short Story/Maikling Kwento': { background: '#fecaca', color: '#dc2626' },
        'Screenplay': { background: '#fed7aa', color: '#ea580c' },
    };
    return styles[type] || { background: '#f1f5f9', color: '#475569' };
};

const getStatusStyle = (status) => {
    const styles = {
        'Pending': { background: '#f1f5f9', color: '#475569' },
        'Copyreader': { background: '#fef08a', color: '#a16207' },
        'Section Editor': { background: '#dbeafe', color: '#1d4ed8' },
        'For Layouting': { background: '#dcfce7', color: '#15803d' },
        'Completed': { background: '#dcfce7', color: '#15803d' },
        'In Progress': { background: '#dbeafe', color: '#1d4ed8' },
    };
    return styles[status] || { background: '#f1f5f9', color: '#475569' };
};

const updateEntry = async (entry) => {
    try {
        const response = await fetch(`/api/monitoring-sheets/${route.params.monitoringSheet}/entries/${entry.id}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('sparky_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                artist_assigned: entry.artist_assigned,
                interview_completed: entry.interview_completed,
                current_status: entry.current_status
            })
        });
        
        if (!response.ok) {
            console.error('Failed to update entry');
        }
    } catch (error) {
        console.error('Error updating entry:', error);
    }
};

const goBack = () => {
    if (window.history.length > 1) {
        router.back();
    } else {
        router.push('/editor');
    }
};

const openStorage = (entry) => {
    window.open(`/storage/${entry.id}`, '_blank');
};

const openUploadModal = (entry) => {
    console.log('openUploadModal called with entry:', entry);
    selectedEntry.value = entry;
    
    // Populate form fields using DOM
    document.getElementById('articleHeadline').value = entry.topic || '';
    document.getElementById('articleAuthor').value = entry.writer_assigned || '';
    
    // Load existing article content if available
    const existingContent = entry.article_content || '';
    document.getElementById('articleContentEditable').innerHTML = existingContent;
    
    // Show modal using DOM
    document.getElementById('uploadModal').style.display = 'flex';
};

const testUploadModal = () => {
    console.log('testUploadModal called');
    console.log('Current isUploadModalOpen value:', isUploadModalOpen.value);
    console.log('Type of isUploadModalOpen:', typeof isUploadModalOpen.value);
    
    try {
        selectedEntry.value = entries.value[0] || null;
        articleForm.value = {
            headline: 'Test Headline',
            author: 'Test Author',
            content: 'Test content here...'
        };
        
        isUploadModalOpen.value = true;
        
        console.log('After setting to true, isUploadModalOpen:', isUploadModalOpen.value);
        
        // Direct DOM manipulation as fallback
        const modal = document.querySelector('.modal-overlay');
        if (modal) {
            modal.style.display = 'flex';
            console.log('Modal shown via DOM manipulation');
        } else {
            console.log('Modal element not found in DOM');
        }
        
        // Force a reactivity check
        setTimeout(() => {
            console.log('After timeout, isUploadModalOpen:', isUploadModalOpen.value);
        }, 100);
    } catch (error) {
        console.error('Error in testUploadModal:', error);
    }
};

const closeUploadModal = () => {
    isUploadModalOpen.value = false;
    selectedEntry.value = null;
    articleForm.value = {
        headline: '',
        author: '',
        content: ''
    };
    
    // Hide modal using DOM
    document.getElementById('uploadModal').style.display = 'none';
};

const saveArticle = async () => {
    if (!selectedEntry.value) return;
    
    isSaving.value = true;
    const saveBtn = document.getElementById('saveArticleBtn');
    if (saveBtn) saveBtn.textContent = 'Saving...';
    
    try {
        const headline = document.getElementById('articleHeadline').value;
        const author = document.getElementById('articleAuthor').value;
        const content = document.getElementById('articleContentEditable').innerHTML;
        
        const response = await fetch(`/api/monitoring-sheets/${route.params.monitoringSheet}/entries/${selectedEntry.value.id}/article`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('sparky_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                headline: headline,
                author: author,
                content: content
            })
        });

        if (response.ok) {
            // Update the entry to show that article has been uploaded
            await loadEntries();
            // Don't close modal - allow continued editing
            alert('Article saved successfully! You can continue editing.');
        } else {
            console.error('Failed to save article');
            alert('Failed to save article. Please try again.');
        }
    } catch (error) {
        console.error('Error saving article:', error);
        alert('An error occurred while saving the article.');
    } finally {
        isSaving.value = false;
        if (saveBtn) saveBtn.textContent = 'Save Article';
    }
};

// Highlight functions
const highlightText = (color) => {
    const editableDiv = document.getElementById('articleContentEditable');
    const selection = window.getSelection();
    
    // Only proceed if selection is within the editable div
    if (selection.rangeCount > 0 && editableDiv.contains(selection.anchorNode)) {
        const range = selection.getRangeAt(0);
        
        // Create highlight span
        const span = document.createElement('span');
        span.style.backgroundColor = color;
        span.style.padding = '2px 4px';
        span.style.borderRadius = '2px';
        
        try {
            // Check if selection is entirely within text nodes
            if (range.startContainer.nodeType === Node.TEXT_NODE && range.endContainer.nodeType === Node.TEXT_NODE) {
                range.surroundContents(span);
            } else {
                // For complex selections, use execCommand as fallback
                document.execCommand('hiliteColor', false, color);
            }
            selection.removeAllRanges();
        } catch (e) {
            console.error('Error highlighting text:', e);
            // Fallback to execCommand
            try {
                document.execCommand('hiliteColor', false, color);
            } catch (e2) {
                console.error('Fallback highlight failed:', e2);
            }
        }
    }
};

const clearHighlights = () => {
    const editableDiv = document.getElementById('articleContentEditable');
    
    // Remove highlight spans
    const highlights = editableDiv.querySelectorAll('span[style*="background-color"]');
    highlights.forEach(span => {
        const parent = span.parentNode;
        while (span.firstChild) {
            parent.insertBefore(span.firstChild, span);
        }
        parent.removeChild(span);
    });
    
    // Also try to remove any inline background styles
    document.execCommand('removeFormat', false, null);
};
</script>

<style scoped>
.monitoring-page-wrapper {
    margin: 0;
    padding: 0;
    font-family: 'Inter', sans-serif;
    background-color: #f1f5f9;
    color: #0f172a;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .header-bar {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            padding: 20px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
            flex-shrink: 0;
            z-index: 20;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-title img {
            height: 36px;
        }

        .header-title h1 {
            font-family: 'Manrope', sans-serif;
            font-size: 22px;
            font-weight: 800;
            margin: 0;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .table-container {
            flex: 1;
            overflow: auto;
            background: #ffffff;
            margin: 24px;
            border-radius: 16px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 10px -5px rgba(0, 0, 0, 0.02);
            border: 1px solid #e2e8f0;
        }

        table {
            border-collapse: separate;
            border-spacing: 0;
            width: max-content;
            min-width: 100%;
        }

        th,
        td {
            padding: 16px 24px;
            text-align: center;
            border-right: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            white-space: nowrap;
        }

        /* Top Header Row */
        th.group-header {
            font-family: 'Manrope', sans-serif;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-top: none;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .group-basic {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            color: #475569;
        }

        .group-data {
            background: linear-gradient(180deg, #ecfdf5 0%, #dcfce7 100%);
            color: #166534;
        }

        .group-edit {
            background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%);
            color: #1e40af;
        }

        /* Sub Header Row */
        th.col-header {
            font-weight: 700;
            color: #64748b;
            font-size: 13px;
            position: sticky;
            top: 59px;
            z-index: 5;
            box-shadow: 0 1px 0 #e2e8f0, 0 -1px 0 #e2e8f0;
        }

        th.col-header.group-basic {
            background: #f8fafc;
        }

        th.col-header.group-data {
            background: #dcfce7;
        }

        th.col-header.group-edit {
            background: #dbeafe;
        }

        /* Sticky First Rows & Columns */
        thead th {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Sticky Topic Column */
        .sticky-col {
            position: sticky;
            left: 0;
            background: #ffffff;
            z-index: 6;
            box-shadow: 1px 0 0 #e2e8f0;
            text-align: center;
            font-weight: 700;
            color: #0f172a;
            max-width: 320px;
            white-space: normal;
            min-width: 240px;
            line-height: 1.5;
        }

        thead .sticky-col {
            z-index: 15;
            box-shadow: 1px 1px 0 #e2e8f0;
            background: #f8fafc;
        }

        /* Checkbox Styling */
        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #2563eb;
            cursor: pointer;
            margin: 0;
        }

        input[type="checkbox"]:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* Badges */
        .pub-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        /* Row Hover */
        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:hover td {
            background-color: #f8fafc;
        }

        /* Modals */
        .modal-overlay {
            display: flex;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 500px;
            padding: 32px;
            box-sizing: border-box;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-title {
            font-family: 'Manrope', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .modal-close:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 8px;
            text-align: left;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #0f172a;
            box-sizing: border-box;
            transition: all 0.2s;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 32px;
        }

        .btn-outline {
            padding: 10px 16px;
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .btn-primary {
            padding: 10px 24px;
            background: #2563eb;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
            transition: background-color 0.2s;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .inline-select {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            color: #334155;
            background-color: white;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            transition: all 0.2s;
            width: 100%;
        }

        .inline-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .monitoring-page-wrapper {
            background: #eef3f8;
            color: #172033;
        }

        .header-bar {
            min-height: 82px;
            padding: 16px 28px;
            background: #ffffff;
            border-bottom: 1px solid #dbe4ee;
            box-shadow: 0 8px 24px rgba(32, 54, 78, 0.06);
        }

        .header-title {
            gap: 14px;
        }

        .header-title img {
            width: 42px;
            height: 42px;
            object-fit: contain;
        }

        .header-title h1 {
            font-size: 19px;
            letter-spacing: -0.2px;
        }

        .header-title h1 span {
            color: #2563eb;
        }

        .eyebrow {
            margin: 0 0 4px;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .save-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-right: 8px;
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
        }

        .save-status i {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 3px #dcfce7;
        }

        .back-button,
        .add-task-button {
            min-height: 38px;
            padding: 0 15px;
            border-radius: 9px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }

        .back-button {
            border: 1px solid #d7e0ea;
            background: #ffffff;
            color: #475569;
        }

        .back-button:hover {
            background: #f8fafc;
            border-color: #b8c7d8;
        }

        .add-task-button {
            display: flex;
            align-items: center;
            gap: 8px;
            border: 0;
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 5px 12px rgba(37, 99, 235, 0.22);
        }

        .add-task-button:hover {
            background: #1d4ed8;
        }

        .table-container {
            margin: 18px 12px 24px;
            width: calc(100% - 24px);
            box-sizing: border-box;
            border: 1px solid #dbe4ee;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 12px 30px rgba(32, 54, 78, 0.08);
            padding-right: 0;
        }

        .table-container table {
            width: 100%;
            min-width: 1040px;
            margin-right: 0;
            table-layout: fixed;
        }

        .table-container th {
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .table-container td {
            white-space: normal;
        }

        .table-container thead tr:first-child th {
            height: 61px;
            min-height: 61px;
            padding-top: 0;
            padding-bottom: 0;
            vertical-align: middle;
            box-sizing: border-box;
        }

        .table-container thead tr:nth-child(2) th.col-header {
            top: 61px;
            z-index: 11;
            border-top: 1px solid #dbe4ee;
        }

        .table-container thead tr:first-child th:last-child {
            border-top-right-radius: 10px;
        }

        .table-container tbody tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }

        th,
        td {
            padding: 13px 16px;
            font-size: 12px;
            border-color: #e8eef5;
        }

        th.group-header {
            padding-top: 15px;
            padding-bottom: 15px;
            font-size: 11px;
            letter-spacing: 0.9px;
        }

        th.col-header {
            padding-top: 11px;
            padding-bottom: 11px;
            font-size: 11px;
            letter-spacing: 0.2px;
        }

        .sticky-col {
            min-width: 230px;
            max-width: 280px;
        }

        tbody tr:nth-child(even) td {
            background: #fbfdff;
        }

        tbody tr:hover td,
        tbody tr:hover .sticky-col {
            background: #eff6ff;
        }

        .pub-badge {
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 10px;
        }

        .inline-select {
            min-width: 105px;
            padding: 7px 9px;
            border-radius: 7px;
            font-size: 11px;
        }

        input[type="checkbox"] {
            width: 17px;
            height: 17px;
        }

        @media (max-width: 760px) {
            .header-bar {
                align-items: flex-start;
                flex-direction: column;
                gap: 14px;
                padding: 16px 18px;
            }

            .header-actions {
                width: 100%;
                flex-wrap: wrap;
            }

            .save-status {
                width: 100%;
                margin: 0;
            }

            .table-container {
                margin: 14px 12px 18px;
            }
        }

        @media (min-width: 761px) {
            .table-container {
                overflow-x: hidden;
                overflow-y: hidden;
            }
        }
</style>
