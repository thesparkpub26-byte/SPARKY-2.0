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
                    <tr v-for="entry in sectionEntries" :key="entry.id" @click="openTaskDetail(entry)" :class="{ 'scratched-row': entry.current_status === 'Scratched' }" style="cursor: pointer;">
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

                        <td @click.stop><input type="checkbox" v-model="entry.interview_completed" @change="updateEntry(entry)"></td>
                        <td @click.stop>
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
                        <td @click.stop>
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

    <!-- Task Detail Modal -->
    <div v-if="isTaskDetailModalOpen" class="task-detail-modal-overlay" @click.self="closeTaskDetailModal">
        <div class="task-detail-modal-card">
            <div class="task-detail-header">
                <h2 class="task-detail-title">{{ isEditingTask ? 'Edit Task' : 'Task Details' }}</h2>
                <button class="task-detail-close" @click="closeTaskDetailModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div class="task-detail-content">
                <!-- View Mode -->
                <div v-if="!isEditingTask" class="task-detail-view">
                    <div class="task-detail-compact-grid">
                        <!-- Basic Information -->
                        <div class="task-detail-compact-section">
                            <h3 class="task-detail-section-title">Basic Information</h3>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Topic</span>
                                <span class="task-detail-value">{{ selectedTaskForDetail?.topic || 'Untitled' }}</span>
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Section</span>
                                <span class="task-detail-value">{{ selectedTaskForDetail?.section }}</span>
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Article Type</span>
                                <span class="task-detail-value">{{ selectedTaskForDetail?.article_type || '-' }}</span>
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Medium</span>
                                <span class="task-detail-value">{{ selectedTaskForDetail?.medium || 'English' }}</span>
                            </div>
                        </div>

                        <!-- Assignments -->
                        <div class="task-detail-compact-section">
                            <h3 class="task-detail-section-title">Assignments</h3>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Writer Assigned</span>
                                <span class="task-detail-value">{{ selectedTaskForDetail?.writer_assigned || '-' }}</span>
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Artist Assigned</span>
                                <span class="task-detail-value">{{ selectedTaskForDetail?.artist_assigned || '-' }}</span>
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Media Type</span>
                                <span class="task-detail-value">{{ selectedTaskForDetail?.media_type || 'Photo/s' }}</span>
                            </div>
                        </div>

                        <!-- Priority & Deadline -->
                        <div class="task-detail-compact-section">
                            <h3 class="task-detail-section-title">Priority & Deadline</h3>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Priority</span>
                                <span class="task-detail-value">{{ selectedTaskForDetail?.priority || '-' }}</span>
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Deadline</span>
                                <span class="task-detail-value">{{ selectedTaskForDetail?.deadline ? new Date(selectedTaskForDetail.deadline).toLocaleDateString() : '-' }} {{ selectedTaskForDetail?.deadline_time || '' }}</span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="task-detail-compact-section">
                            <h3 class="task-detail-section-title">Current Status</h3>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-value status-badge" :style="getStatusStyle(selectedTaskForDetail?.current_status)">
                                    {{ selectedTaskForDetail?.current_status || 'Pending' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Description (full width) -->
                    <div class="task-detail-section task-detail-description">
                        <h3 class="task-detail-section-title">Description</h3>
                        <div class="task-detail-field-full">
                            <span class="task-detail-value">{{ selectedTaskForDetail?.description || '-' }}</span>
                        </div>
                    </div>

                    <div class="task-detail-actions">
                        <button class="task-detail-btn task-detail-btn-edit" @click="startEditTask">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Edit Task
                        </button>
                        <button class="task-detail-btn task-detail-btn-delete" @click="openDeleteConfirm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            Delete Task
                        </button>
                    </div>
                </div>

                <!-- Edit Mode -->
                <div v-else class="task-detail-edit">
                    <div class="task-detail-compact-grid">
                        <!-- Basic Information -->
                        <div class="task-detail-compact-section">
                            <h3 class="task-detail-section-title">Basic Information</h3>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Topic</span>
                                <input type="text" class="task-detail-input" v-model="taskEditForm.topic" placeholder="Enter topic" />
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Section</span>
                                <select class="task-detail-select" v-model="taskEditForm.section">
                                    <option value="News">News</option>
                                    <option value="Opinion/Editorial">Opinion/Editorial</option>
                                    <option value="Feature">Feature</option>
                                    <option value="Sports">Sports</option>
                                    <option value="Literary">Literary</option>
                                    <option value="DevComm">DevComm</option>
                                </select>
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Article Type</span>
                                <select class="task-detail-select" v-model="taskEditForm.article_type">
                                    <option value="">Select Type</option>
                                    <option v-for="type in articleTypeOptions" :key="type" :value="type">{{ type }}</option>
                                </select>
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Medium</span>
                                <select class="task-detail-select" v-model="taskEditForm.medium">
                                    <option value="English">English</option>
                                    <option value="Filipino">Filipino</option>
                                </select>
                            </div>
                        </div>

                        <!-- Assignments -->
                        <div class="task-detail-compact-section">
                            <h3 class="task-detail-section-title">Assignments</h3>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Writer Assigned</span>
                                <input type="text" class="task-detail-input" v-model="taskEditForm.writer_assigned" placeholder="Enter writer name" />
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Artist Assigned</span>
                                <input type="text" class="task-detail-input" v-model="taskEditForm.artist_assigned" placeholder="Enter artist name" />
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Media Type</span>
                                <select class="task-detail-select" v-model="taskEditForm.media_type">
                                    <option value="Graphic/s">Graphic/s</option>
                                    <option value="Photo/s">Photo/s</option>
                                </select>
                            </div>
                        </div>

                        <!-- Priority & Deadline -->
                        <div class="task-detail-compact-section">
                            <h3 class="task-detail-section-title">Priority & Deadline</h3>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Priority</span>
                                <select class="task-detail-select" v-model="taskEditForm.priority">
                                    <option value="">Select Priority</option>
                                    <option value="Low">Low</option>
                                    <option value="Moderate">Moderate</option>
                                    <option value="High">High</option>
                                    <option value="Urgent">Urgent</option>
                                </select>
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Deadline Date</span>
                                <input type="date" class="task-detail-input" v-model="taskEditForm.deadline" />
                            </div>
                            <div class="task-detail-compact-field">
                                <span class="task-detail-label">Deadline Time</span>
                                <input type="time" class="task-detail-input" v-model="taskEditForm.deadline_time" />
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="task-detail-compact-section">
                            <h3 class="task-detail-section-title">Current Status</h3>
                            <div class="task-detail-compact-field">
                                <select class="task-detail-select" v-model="taskEditForm.current_status">
                                    <option value="Not Started">Not Started</option>
                                    <option value="Started">Started</option>
                                    <option value="Section Editor">Section Editor</option>
                                    <option value="Copyreader">Copyreader</option>
                                    <option value="Associate Editor">Associate Editor</option>
                                    <option value="Editor-in-Chief">Editor-in-Chief</option>
                                    <option value="For Layouting">For Layouting</option>
                                    <option value="Scratched">Scratched</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Description (full width) -->
                    <div class="task-detail-section task-detail-description">
                        <h3 class="task-detail-section-title">Description</h3>
                        <div class="task-detail-field-full">
                            <textarea class="task-detail-textarea" v-model="taskEditForm.description" placeholder="Enter description" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="task-detail-actions">
                        <button class="task-detail-btn task-detail-btn-cancel" @click="cancelEditTask">
                            Cancel
                        </button>
                        <button class="task-detail-btn task-detail-btn-save" @click="saveTaskEdit" :disabled="taskEditSaving">
                            {{ taskEditSaving ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                    <p v-if="taskEditError" class="task-detail-error">{{ taskEditError }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="isDeleteConfirmModalOpen" class="delete-confirm-modal-overlay" @click.self="cancelDeleteTask">
        <div class="delete-confirm-modal-card">
            <div class="delete-confirm-header">
                <h2 class="delete-confirm-title">Delete Task?</h2>
                <button class="delete-confirm-close" @click="cancelDeleteTask">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div class="delete-confirm-content">
                <div class="delete-confirm-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                </div>
                <p class="delete-confirm-message">
                    Are you sure you want to delete <strong>"{{ selectedTaskForDetail?.topic || 'this task' }}"</strong>? This action cannot be undone.
                </p>
            </div>

            <div class="delete-confirm-actions">
                <button class="delete-confirm-btn delete-confirm-btn-cancel" @click="cancelDeleteTask">
                    Cancel
                </button>
                <button class="delete-confirm-btn delete-confirm-btn-delete" @click="confirmDeleteTask" :disabled="deleteConfirmSaving">
                    {{ deleteConfirmSaving ? 'Deleting...' : 'Delete Task' }}
                </button>
            </div>
            <p v-if="deleteConfirmError" class="delete-confirm-error">{{ deleteConfirmError }}</p>
        </div>
    </div>

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
            <p v-if="saveArticleError" style="margin: 12px 0 0; color: #c62828; font-size: 12px; text-align: right; font-weight: 600;">{{ saveArticleError }}</p>
            <p v-if="saveArticleSuccess" style="margin: 12px 0 0; color: #16a34a; font-size: 12px; text-align: right; font-weight: 600;">Article saved successfully! You can continue editing.</p>
        </div>
    </div>

    
</div>
</template>

<script setup>
import { onMounted, ref, computed, watch } from 'vue';
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
const saveArticleError = ref('');
const saveArticleSuccess = ref(false);
const isTaskDetailModalOpen = ref(false);
const selectedTaskForDetail = ref(null);
const isEditingTask = ref(false);
const taskEditError = ref('');
const taskEditSaving = ref(false);
const isDeleteConfirmModalOpen = ref(false);
const deleteConfirmError = ref('');
const deleteConfirmSaving = ref(false);
const taskEditForm = ref({
    topic: '',
    section: '',
    article_type: '',
    medium: '',
    writer_assigned: '',
    artist_assigned: '',
    media_type: '',
    priority: '',
    deadline: '',
    deadline_time: '',
    description: '',
    current_status: ''
});
const articleForm = ref({
    headline: '',
    author: '',
    content: ''
});

const articleTypeMap = {
    'News': ['Special Report', 'Full News', 'News Bit', 'News Features', 'In-Depth News'],
    'Opinion/Editorial': ['Opinion', 'Spark Agent', 'Letter to the Editor', 'Editorial'],
    'Feature': ['Sci-Tech', 'General Feature'],
    'DevComm': ['Feature-Style'],
    'Sports': ['News', 'News Feature', 'Opinion'],
    'Literary': ['Poem/Tula', 'Short Story/Maikling Kwento', 'Flash Fiction/Dagli', 'Screenplay'],
};

const articleTypeOptions = computed(() => {
    return articleTypeMap[taskEditForm.value.section] || [];
});

// Reset article_type whenever section changes in edit form
watch(() => taskEditForm.value.section, () => {
    taskEditForm.value.article_type = '';
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
        'Not Started': { background: '#f1f5f9', color: '#475569' },
        'Started': { background: '#dbeafe', color: '#1d4ed8' },
        'Section Editor': { background: '#dbeafe', color: '#1d4ed8' },
        'Copyreader': { background: '#fef08a', color: '#a16207' },
        'Associate Editor': { background: '#e0e7ff', color: '#4338ca' },
        'Editor-in-Chief': { background: '#dcfce7', color: '#15803d' },
        'For Layouting': { background: '#f3e8ff', color: '#7c3aed' },
        'Scratched': { background: '#fee2e2', color: '#dc2626' },
        'Pending': { background: '#f1f5f9', color: '#475569' },
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
    saveArticleError.value = '';
    saveArticleSuccess.value = false;

    // Populate form fields using DOM
    document.getElementById('articleHeadline').value = entry.topic || '';
    document.getElementById('articleAuthor').value = entry.writer_assigned || '';

    // Load existing article content if available
    const existingContent = entry.article_content || '';
    document.getElementById('articleContentEditable').innerHTML = existingContent;

    // Show modal using DOM
    document.getElementById('uploadModal').style.display = 'flex';
};

const openTaskDetail = (entry) => {
    selectedTaskForDetail.value = entry;
    taskEditForm.value = {
        topic: entry.topic || '',
        section: entry.section || '',
        article_type: entry.article_type || '',
        medium: entry.medium || '',
        writer_assigned: entry.writer_assigned || '',
        artist_assigned: entry.artist_assigned || '',
        media_type: entry.media_type || '',
        priority: entry.priority || '',
        deadline: entry.deadline || '',
        deadline_time: entry.deadline_time || '',
        description: entry.description || '',
        current_status: entry.current_status || 'Not Started'
    };
    isEditingTask.value = false;
    taskEditError.value = '';
    isTaskDetailModalOpen.value = true;
};

const closeTaskDetailModal = () => {
    isTaskDetailModalOpen.value = false;
    selectedTaskForDetail.value = null;
    taskEditError.value = '';
};

const startEditTask = () => {
    isEditingTask.value = true;
    taskEditError.value = '';
    // Populate form with current entry values
    if (selectedTaskForDetail.value) {
        taskEditForm.value = {
            topic: selectedTaskForDetail.value.topic || '',
            section: selectedTaskForDetail.value.section || '',
            article_type: selectedTaskForDetail.value.article_type || '',
            medium: selectedTaskForDetail.value.medium || '',
            writer_assigned: selectedTaskForDetail.value.writer_assigned || '',
            artist_assigned: selectedTaskForDetail.value.artist_assigned || '',
            media_type: selectedTaskForDetail.value.media_type || '',
            priority: selectedTaskForDetail.value.priority || '',
            deadline: selectedTaskForDetail.value.deadline || '',
            deadline_time: selectedTaskForDetail.value.deadline_time || '',
            description: selectedTaskForDetail.value.description || '',
            current_status: selectedTaskForDetail.value.current_status || 'Not Started'
        };
    }
};

const cancelEditTask = () => {
    isEditingTask.value = false;
    taskEditError.value = '';
    // Reset form to current entry values
    if (selectedTaskForDetail.value) {
        taskEditForm.value = {
            topic: selectedTaskForDetail.value.topic || '',
            section: selectedTaskForDetail.value.section || '',
            article_type: selectedTaskForDetail.value.article_type || '',
            medium: selectedTaskForDetail.value.medium || '',
            writer_assigned: selectedTaskForDetail.value.writer_assigned || '',
            artist_assigned: selectedTaskForDetail.value.artist_assigned || '',
            media_type: selectedTaskForDetail.value.media_type || '',
            priority: selectedTaskForDetail.value.priority || '',
            deadline: selectedTaskForDetail.value.deadline || '',
            deadline_time: selectedTaskForDetail.value.deadline_time || '',
            description: selectedTaskForDetail.value.description || '',
            current_status: selectedTaskForDetail.value.current_status || 'Not Started'
        };
    }
};

const saveTaskEdit = async () => {
    if (!selectedTaskForDetail.value) return;

    taskEditSaving.value = true;
    taskEditError.value = '';

    try {
        const response = await fetch(`/api/monitoring-sheets/${route.params.monitoringSheet}/entries/${selectedTaskForDetail.value.id}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('sparky_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                topic: taskEditForm.value.topic,
                section: taskEditForm.value.section,
                article_type: taskEditForm.value.article_type,
                medium: taskEditForm.value.medium,
                writer_assigned: taskEditForm.value.writer_assigned,
                artist_assigned: taskEditForm.value.artist_assigned,
                media_type: taskEditForm.value.media_type,
                priority: taskEditForm.value.priority,
                deadline: taskEditForm.value.deadline,
                deadline_time: taskEditForm.value.deadline_time,
                description: taskEditForm.value.description,
                current_status: taskEditForm.value.current_status
            })
        });

        if (response.ok) {
            // Update local entry
            const updatedEntry = await response.json();
            Object.assign(selectedTaskForDetail.value, updatedEntry);
            // Reload entries to ensure consistency
            await loadEntries();
            isEditingTask.value = false;
        } else {
            const errorData = await response.json().catch(() => ({}));
            console.error('Failed to update task:', errorData);
            taskEditError.value = errorData.message || errorData.error || 'Failed to update task. Please try again.';
        }
    } catch (error) {
        console.error('Error updating task:', error);
        taskEditError.value = 'An error occurred. Please try again.';
    } finally {
        taskEditSaving.value = false;
    }
};

const openDeleteConfirm = () => {
    if (!selectedTaskForDetail.value) return;
    isDeleteConfirmModalOpen.value = true;
    deleteConfirmError.value = '';
};

const confirmDeleteTask = async () => {
    if (!selectedTaskForDetail.value) return;

    deleteConfirmSaving.value = true;
    deleteConfirmError.value = '';

    try {
        const response = await fetch(`/api/monitoring-sheets/${route.params.monitoringSheet}/entries/${selectedTaskForDetail.value.id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('sparky_token')}`,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            // Reload entries
            await loadEntries();
            closeTaskDetailModal();
            isDeleteConfirmModalOpen.value = false;
        } else {
            console.error('Failed to delete task');
            deleteConfirmError.value = 'Failed to delete task. Please try again.';
        }
    } catch (error) {
        console.error('Error deleting task:', error);
        deleteConfirmError.value = 'An error occurred. Please try again.';
    } finally {
        deleteConfirmSaving.value = false;
    }
};

const cancelDeleteTask = () => {
    isDeleteConfirmModalOpen.value = false;
    deleteConfirmError.value = '';
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
    saveArticleError.value = '';
    saveArticleSuccess.value = false;
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
    saveArticleError.value = '';
    saveArticleSuccess.value = false;
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
            saveArticleSuccess.value = true;
            // Clear success message after 3 seconds
            setTimeout(() => {
                saveArticleSuccess.value = false;
            }, 3000);
        } else {
            console.error('Failed to save article');
            saveArticleError.value = 'Failed to save article. Please try again.';
        }
    } catch (error) {
        console.error('Error saving article:', error);
        saveArticleError.value = 'An error occurred while saving the article.';
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

    /* Task Detail Modal Styles */
    .task-detail-modal-overlay {
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

    .task-detail-modal-card {
        background: #ffffff;
        border-radius: 24px;
        width: 100%;
        max-width: 65vw;
        padding: 20px 24px;
        box-sizing: border-box;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalPopIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .task-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
    }

    .task-detail-title {
        color: #1d6bf3;
        font-size: 24px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.5px;
        font-family: 'Manrope', -apple-system, sans-serif;
    }

    .task-detail-close {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .task-detail-close:hover {
        background: #f1f5f9;
    }

    .task-detail-content {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .task-detail-compact-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 12px;
    }

    .task-detail-compact-section {
        background: #f8fafc;
        border-radius: 10px;
        padding: 14px;
        border: 1px solid #e2e8f0;
    }

    .task-detail-compact-field {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 10px;
    }

    .task-detail-compact-field:last-child {
        margin-bottom: 0;
    }

    @media (max-width: 1024px) {
        .task-detail-compact-grid {
            grid-template-columns: 1fr;
        }
    }

    .task-detail-section {
        background: #f8fafc;
        border-radius: 10px;
        padding: 14px;
        border: 1px solid #e2e8f0;
    }

    .task-detail-description {
        margin-top: 12px;
    }

    .task-detail-section-title {
        color: #1e293b;
        font-size: 14px;
        font-weight: 700;
        margin: 0 0 10px 0;
        font-family: 'Manrope', sans-serif;
        letter-spacing: -0.3px;
    }

    .task-detail-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .task-detail-field-full {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .task-detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
    }

    .task-detail-label {
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        font-family: 'Manrope', sans-serif;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .task-detail-value {
        font-size: 15px;
        color: #0f172a;
        font-family: 'Manrope', sans-serif;
        font-weight: 500;
        line-height: 1.5;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
    }

    .task-detail-input {
        width: 100%;
        height: 36px;
        padding: 0 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        color: #0f172a;
        box-sizing: border-box;
        outline: none;
        background: #ffffff;
        font-family: 'Manrope', sans-serif;
        transition: all 0.2s;
    }

    .task-detail-input:focus {
        border-color: #1d6bf3;
        box-shadow: 0 0 0 3px rgba(29, 107, 243, 0.12);
    }

    .task-detail-select {
        width: 100%;
        height: 36px;
        padding: 0 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        color: #0f172a;
        box-sizing: border-box;
        outline: none;
        background: #ffffff;
        cursor: pointer;
        font-family: 'Manrope', sans-serif;
        transition: all 0.2s;
    }

    .task-detail-select:focus {
        border-color: #1d6bf3;
        box-shadow: 0 0 0 3px rgba(29, 107, 243, 0.12);
    }

    .task-detail-textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: #0f172a;
        box-sizing: border-box;
        outline: none;
        background: #ffffff;
        font-family: 'Manrope', sans-serif;
        transition: all 0.2s;
        resize: vertical;
        min-height: 60px;
    }

    .task-detail-textarea:focus {
        border-color: #1d6bf3;
        box-shadow: 0 0 0 3px rgba(29, 107, 243, 0.12);
    }

    .task-detail-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 2px solid #e2e8f0;
    }

    .task-detail-btn {
        padding: 14px 28px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 700;
        font-size: 15px;
        font-family: 'Manrope', sans-serif;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
    }

    .task-detail-btn-edit {
        background: #2563eb;
        color: white;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
    }

    .task-detail-btn-edit:hover {
        background: #1d4ed8;
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.4);
    }

    .task-detail-btn-delete {
        background: #ef4444;
        color: white;
        box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
    }

    .task-detail-btn-delete:hover {
        background: #dc2626;
        box-shadow: 0 6px 18px rgba(239, 68, 68, 0.4);
    }

    .task-detail-btn-cancel {
        background: white;
        color: #475569;
        border: 1.5px solid #e2e8f0;
    }

    .task-detail-btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .task-detail-btn-save {
        background: #2563eb;
        color: white;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
    }

    .task-detail-btn-save:hover {
        background: #1d4ed8;
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.4);
    }

    .task-detail-btn:disabled {
        background-color: #93c5fd;
        cursor: wait;
        opacity: 0.7;
        transform: none;
        box-shadow: none;
    }

    .task-detail-error {
        margin: 12px 0 0;
        color: #c62828;
        font-size: 13px;
        text-align: right;
        font-weight: 600;
    }

    /* Scratched row highlighting */
    .scratched-row {
        background-color: #fee2e2 !important;
    }

    .scratched-row:hover {
        background-color: #fecaca !important;
    }

    /* Delete Confirmation Modal Styles */
    .delete-confirm-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(5px);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        box-sizing: border-box;
    }

    .delete-confirm-modal-card {
        background: #ffffff;
        border-radius: 24px;
        width: 100%;
        max-width: 400px;
        padding: 28px 32px;
        box-sizing: border-box;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .delete-confirm-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #e2e8f0;
    }

    .delete-confirm-title {
        color: #1d6bf3;
        font-size: 22px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.5px;
        font-family: 'Manrope', -apple-system, sans-serif;
    }

    .delete-confirm-close {
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

    .delete-confirm-close:hover {
        background: #f1f5f9;
    }

    .delete-confirm-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .delete-confirm-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #fef2f2;
    }

    .delete-confirm-message {
        font-size: 15px;
        color: #0f172a;
        text-align: center;
        font-family: 'Manrope', sans-serif;
        line-height: 1.5;
        margin: 0;
    }

    .delete-confirm-message strong {
        color: #ef4444;
        font-weight: 700;
    }

    .delete-confirm-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
    }

    .delete-confirm-btn {
        padding: 12px 24px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 700;
        font-size: 15px;
        font-family: 'Manrope', sans-serif;
        transition: all 0.2s;
        border: none;
    }

    .delete-confirm-btn-cancel {
        background: white;
        color: #475569;
        border: 1.5px solid #e2e8f0;
    }

    .delete-confirm-btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .delete-confirm-btn-delete {
        background: #ef4444;
        color: white;
        box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
    }

    .delete-confirm-btn-delete:hover {
        background: #dc2626;
        box-shadow: 0 6px 18px rgba(239, 68, 68, 0.4);
    }

    .delete-confirm-btn:disabled {
        background-color: #fca5a5;
        cursor: wait;
        opacity: 0.7;
        transform: none;
        box-shadow: none;
    }

    .delete-confirm-error {
        margin: 12px 0 0;
        color: #c62828;
        font-size: 13px;
        text-align: center;
        font-weight: 600;
    }
</style>
