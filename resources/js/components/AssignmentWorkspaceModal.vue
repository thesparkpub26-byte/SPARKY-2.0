<template>
    <div class="workspace-modal-overlay" v-if="isOpen" @click.self="closeModal">
        <div class="workspace-modal-card">
            
            <!-- Modal Header -->
            <div class="modal-hdr">
                <div class="modal-hdr-left">
                    <h2 class="modal-blue-title">Assignment Workspace</h2>
                    <span v-if="saveFeedback" class="save-feedback-badge">{{ saveFeedback }}</span>
                </div>
                <button class="modal-x-btn" @click="closeModal" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Article Information -->
            <div class="article-meta-hdr">
                <span v-if="!allowSectionEdit" class="section-pill-badge">{{ (task.section && typeof task.section === 'object') ? (task.section.name || 'News') : (task.section || 'News') }}</span>
                <div v-else class="section-edit-dropdown" @click.stop>
                    <button type="button" class="section-edit-trigger" @click="isSectionMenuOpen = !isSectionMenuOpen">
                        <span>{{ selectedSectionLabel }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="{ rotated: isSectionMenuOpen }"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div v-if="isSectionMenuOpen" class="section-edit-menu">
                        <button
                            v-for="sec in canonicalSectionOptions"
                            :key="sec.id"
                            type="button"
                            class="section-edit-option"
                            :class="{ selected: selectedSectionId === sec.id }"
                            @click="selectedSectionId = sec.id; isSectionMenuOpen = false;"
                        >
                            {{ sec.name }}
                        </button>
                        <p v-if="canonicalSectionOptions.length === 0" class="section-edit-empty">No sections available.</p>
                    </div>
                </div>
                <h1 class="article-main-title">{{ task.title || 'Untitled Assignment' }}</h1>
            </div>

            <!-- Navigation Tabs Header -->
            <div class="workspace-tabs-nav">
                <button class="tab-nav-btn" :class="{ active: currentTab === 'content' }" @click="currentTab = 'content'">
                    Article Content
                </button>
                <button class="tab-nav-btn" :class="{ active: currentTab === 'visuals' }" @click="currentTab = 'visuals'">
                    Visual Assets
                    <span class="tab-asset-count" v-if="mediaPreviews.length || thumbnailPreview">
                        {{ (thumbnailPreview ? 1 : 0) + mediaPreviews.length }}
                    </span>
                </button>
                <button v-if="!allowSectionEdit" class="tab-nav-btn" :class="{ active: currentTab === 'details' }" @click="currentTab = 'details'">
                    Details
                </button>
            </div>

            <!-- TAB 1: ARTICLE CONTENT -->
            <div class="tab-content-body" v-if="currentTab === 'content'">
                <!-- Headline Input Bar -->
                <div class="headline-bar-card">
                    <label class="headline-label">Article Headline</label>
                    <input 
                        type="text" 
                        v-model="articleHeadline" 
                        class="headline-input" 
                        placeholder="Enter a captivating headline for your article..."
                    />
                </div>

                <div class="editor-container-card">
                    <!-- Rich Text Toolbar -->
                    <div class="rich-toolbar">
                        <select class="toolbar-select" @change="applyFormatBlock($event.target.value)">
                            <option value="p">Paragraph</option>
                            <option value="h1">Heading 1</option>
                            <option value="h2">Heading 2</option>
                            <option value="h3">Heading 3</option>
                        </select>
                        <div class="toolbar-divider"></div>
                        <button class="tool-btn" type="button" title="Bold" @click="formatDoc('bold')"><b>B</b></button>
                        <button class="tool-btn" type="button" title="Italic" @click="formatDoc('italic')"><i>I</i></button>
                        <button class="tool-btn" type="button" title="Underline" @click="formatDoc('underline')"><u>U</u></button>
                        <button v-if="isCopyreader" class="tool-btn" type="button" title="Highlight" @click="formatDoc('hiliteColor', '#fef08a')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 11-6 6v3h3l6-6"/><path d="m22 12-4.6 4.6a2 2 0 0 1-2.8 0l-5.2-5.2a2 2 0 0 1 0-2.8L14 4"/></svg>
                        </button>
                        <button v-if="isCopyreader" class="tool-btn" type="button" title="Remove Highlight" @click="formatDoc('hiliteColor', 'transparent')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 11-6 6v3h3l6-6"/><path d="m22 12-4.6 4.6a2 2 0 0 1-2.8 0l-5.2-5.2a2 2 0 0 1 0-2.8L14 4"/></svg><line x1="3" y1="3" x2="21" y2="21"/>
                        </button>
                        <div class="toolbar-divider"></div>
                        <button class="tool-btn" type="button" title="Align Left" @click="formatDoc('justifyLeft')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                        </button>
                        <button class="tool-btn" type="button" title="Align Center" @click="formatDoc('justifyCenter')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="10" x2="6" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="18" y1="18" x2="6" y2="18"/></svg>
                        </button>
                        <button class="tool-btn" type="button" title="Align Right" @click="formatDoc('justifyRight')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="21" y1="10" x2="7" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="7" y2="18"/></svg>
                        </button>
                        <div class="toolbar-divider"></div>
                        <button class="tool-btn" type="button" title="Insert Link" @click="promptLink">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        </button>
                        <div class="toolbar-divider"></div>
                        <button class="tool-btn" type="button" title="Undo" @click="formatDoc('undo')">↶</button>
                        <button class="tool-btn" type="button" title="Redo" @click="formatDoc('redo')">↷</button>
                    </div>

                    <!-- Article Text Editor Area -->
                    <div 
                        ref="editorRef"
                        class="article-text-body" 
                        contenteditable="true"
                        @input="handleEditorInput"
                        placeholder="Write your article draft here..."
                    ></div>

                    <!-- Author & Live Word Count Bar -->
                    <div class="editor-footer-bar">
                        <div class="author-pill">
                            <img :src="authorAvatar" class="author-avatar" :alt="authorName" />
                            <span>{{ authorName }}</span>
                        </div>
                        <span class="word-count-text">{{ wordCount }} {{ wordCount === 1 ? 'word' : 'words' }}</span>
                    </div>
                </div>

                <!-- Action Footer Buttons for Article Content -->
                <div class="content-actions-footer">
                    <div class="actions-left">
                        <span class="status-hint-text" v-if="task.status">Status: <strong>{{ task.status }}</strong></span>
                    </div>
                    <div class="actions-right">
                        <button type="button" class="btn-secondary-pill" @click="saveProgress">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            Save
                        </button>
                        <button v-if="!allowSectionEdit" type="button" class="btn-outline-pill" @click="saveAsDraft">
                            Save as Draft
                        </button>
                        <button type="button" class="btn-blue-pill" @click="currentTab = 'visuals'">
                            Next
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- TAB 2: VISUAL ASSETS -->
            <div class="tab-content-body" v-if="currentTab === 'visuals'">
                <div class="visuals-section-card">
                    <!-- Thumbnail Box -->
                    <div class="asset-group">
                        <div class="asset-hdr">
                            <div>
                                <h4 class="asset-title">Thumbnail</h4>
                                <p class="asset-subtitle">This image will represent your article in the publication.</p>
                            </div>
                            <div class="assignee-pill" v-if="collaboratorArtist">
                                <img v-if="collaboratorArtistUser" :src="collaboratorArtistAvatar" class="artist-avatar" :alt="collaboratorArtist" />
                                <span v-else class="artist-icon">🎨</span>
                                <span>{{ collaboratorArtist }}</span>
                            </div>
                            <div class="assignee-pill" v-else>
                                <span class="artist-icon">🎨</span>
                                <span>Photojournalist / Artist</span>
                            </div>
                        </div>

                        <!-- Thumbnail Upload Area / Preview -->
                        <div v-if="thumbnailPreview" class="preview-card-container">
                            <div class="thumbnail-preview-box">
                                <img :src="thumbnailPreview" alt="Thumbnail Preview" class="thumbnail-img" />
                                <span v-if="isThumbnailFromArtist" class="artist-asset-badge" title="Submitted by the artist — can't be removed here">From Artist</span>
                                <div v-else class="preview-actions-overlay">
                                    <button type="button" class="preview-action-btn danger" @click="removeThumbnail" title="Remove">Remove</button>
                                </div>
                            </div>
                        </div>
                        <!-- Thumbnail uploading spinner -->
                        <div v-if="isUploadingThumbnail" class="media-uploading-overlay">
                            <span class="upload-spinner"></span>
                            <span>Uploading thumbnail...</span>
                        </div>
                        <div 
                            v-else-if="!thumbnailPreview"
                            class="dashed-dropzone" 
                            @dragover.prevent 
                            @drop.prevent="handleThumbnailDrop"
                            @click="triggerThumbnailInput"
                        >
                            <input 
                                type="file" 
                                ref="thumbnailInputRef" 
                                accept="image/*" 
                                class="hidden-file-input" 
                                @change="onThumbnailSelected"
                            />
                            <button type="button" class="btn-blue-pill-small" @click.stop="triggerThumbnailInput">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/><polyline points="16 16 12 12 8 16"/></svg>
                                Browse File
                            </button>
                            <p class="dropzone-text"><strong>Drag your file here or browse</strong><br><span class="sub">Max file size up to 10 MB (JPEG, PNG, WEBP)</span></p>
                        </div>

                    </div>

                    <div class="divider-line"></div>

                    <!-- Media Uploads Box (Up to 3 Photos) -->
                    <div class="asset-group">
                        <div class="asset-hdr">
                            <div>
                                <h4 class="asset-title">Media Uploads ({{ mediaPreviews.length }}/3)</h4>
                                <p class="asset-subtitle">Upload photos, graphics, and other media files related to this article.</p>
                            </div>
                            <div class="assignee-pill" v-if="collaboratorArtist">
                                <img v-if="collaboratorArtistUser" :src="collaboratorArtistAvatar" class="artist-avatar" :alt="collaboratorArtist" />
                                <span v-else class="artist-icon">📷</span>
                                <span>{{ collaboratorArtist }}</span>
                            </div>
                            <div class="assignee-pill" v-else>
                                <span class="artist-icon">📷</span>
                                <span>Photojournalist / Artist</span>
                            </div>
                        </div>

                        <!-- Media Preview Grid -->
                        <div v-if="mediaPreviews.length > 0" class="media-grid-container">
                            <div
                                v-for="(media, idx) in mediaPreviews"
                                :key="idx"
                                class="media-preview-card"
                            >
                                <img :src="media.url" :alt="media.name || 'Media Upload'" class="media-preview-img" />
                                <span v-if="isMediaFromArtist(media.url)" class="artist-asset-badge" title="Submitted by the artist — can't be removed here">From Artist</span>
                                <button v-else type="button" class="remove-media-circle" @click="removeMedia(idx)" title="Remove Photo">
                                    &times;
                                </button>
                                <span class="media-name-tag">{{ media.name || `Photo ${idx + 1}` }}</span>
                            </div>
                        </div>

                        <!-- Uploading Spinner -->
                        <div v-if="isUploadingMedia" class="media-uploading-overlay">
                            <span class="upload-spinner"></span>
                            <span>Uploading...</span>
                        </div>

                        <!-- Media Dropzone (if < 3) -->
                        <div 
                            v-if="mediaPreviews.length < 3 && !isUploadingMedia"
                            class="dashed-dropzone" 
                            :class="{ 'compact-dropzone': mediaPreviews.length > 0 }"
                            @dragover.prevent 
                            @drop.prevent="handleMediaDrop"
                            @click="triggerMediaInput"
                        >
                            <input 
                                type="file" 
                                ref="mediaInputRef" 
                                accept="image/*" 
                                multiple
                                class="hidden-file-input" 
                                @change="onMediaSelected"
                            />
                            <button type="button" class="btn-grey-pill-small" @click.stop="triggerMediaInput">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/><polyline points="16 16 12 12 8 16"/></svg>
                                {{ mediaPreviews.length > 0 ? 'Add More Media' : 'Browse File' }}
                            </button>
                            <p class="dropzone-text">
                                <strong>Drag your photos here or browse</strong><br>
                                <span class="sub">Upload up to 3 photos (Max 10 MB each)</span>
                            </p>
                        </div>
                    </div>

                    <!-- Footer Action Bar for Visual Assets -->
                    <div class="visuals-actions-footer">
                        <button type="button" class="btn-secondary-pill" @click="currentTab = 'content'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                            Back
                        </button>
                        <div class="actions-right">
                            <template v-if="allowSectionEdit">
                                <button type="button" class="btn-blue-pill-action" @click="saveProgress">
                                    Save
                                </button>
                            </template>
                            <template v-else>
                                <button type="button" class="btn-outline-pill" @click="saveAsDraft">
                                    Save as Draft
                                </button>
                                <template v-if="isCopyreader">
                                    <button type="button" class="btn-return-pill-ws" @click="openCopyreaderConfirm('return')" :disabled="copyreaderDone">
                                        Return to Writer
                                    </button>
                                    <button type="button" class="btn-blue-pill-action" @click="openCopyreaderConfirm('endorse')" :disabled="copyreaderDone">
                                        {{ copyreaderDone ? 'Already Handled' : 'Submit to Editor-In-Chief' }}
                                    </button>
                                </template>
                                <button v-else type="button" class="btn-blue-pill-action" @click="isSubmitModalOpen = true" :disabled="alreadySubmitted">
                                    {{ alreadySubmitted ? 'Already Submitted' : 'Submit for Review' }}
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: DETAILS -->
            <div class="tab-content-body" v-if="currentTab === 'details' && !allowSectionEdit">
                <div class="details-container-card">
                    
                    <!-- Workflow Steps Header -->
                    <div class="workflow-badge-row" v-if="isCopyreader">
                        <span class="workflow-step-pill">1. Writer Draft</span>
                        <span class="workflow-arrow">&rarr;</span>
                        <span class="workflow-step-pill">2. Section Editor</span>
                        <span class="workflow-arrow">&rarr;</span>
                        <span class="workflow-step-pill active">3. Copyreader</span>
                        <span class="workflow-arrow">&rarr;</span>
                        <span class="workflow-step-pill">4. EIC Approval</span>
                    </div>
                    <div class="workflow-badge-row" v-else-if="!isSectionEditor">
                        <span class="workflow-step-pill">1. Writer Draft</span>
                        <span class="workflow-arrow">&rarr;</span>
                        <span class="workflow-step-pill active">2. Section Editor</span>
                        <span class="workflow-arrow">&rarr;</span>
                        <span class="workflow-step-pill">3. Copyreader</span>
                        <span class="workflow-arrow">&rarr;</span>
                        <span class="workflow-step-pill">4. EIC Approval</span>
                    </div>
                    <div class="workflow-badge-row" v-else>
                        <span class="workflow-step-pill">1. Section Editor Draft</span>
                        <span class="workflow-arrow">&rarr;</span>
                        <span class="workflow-step-pill active">2. Copyreader</span>
                        <span class="workflow-arrow">&rarr;</span>
                        <span class="workflow-step-pill">3. EIC Approval</span>
                    </div>

                    <!-- 1. Section Editor Notes (Only shown if NOT section editor) -->
                    <div class="notes-card-box" v-if="!isSectionEditor">
                        <div class="notes-hdr-row">
                            <h4 class="notes-hdr">
                                <span class="role-icon-circle blue">SE</span>
                                Section Editor Notes
                            </h4>
                            <span class="review-step-label">Initial Review</span>
                        </div>
                        <div class="notes-body-text" v-if="sectionEditorNotes">
                            {{ sectionEditorNotes }}
                        </div>
                        <div class="notes-content-empty" v-else>
                            Notes and revisions from the Section Editor will appear here once submitted for review.
                        </div>
                    </div>

                    <!-- 2. Copyreader Notes -->
                    <div class="notes-card-box">
                        <div class="notes-hdr-row">
                            <h4 class="notes-hdr">
                                <span class="role-icon-circle purple">CR</span>
                                Copyreader Notes
                            </h4>
                            <span class="review-step-label">Copyreading & Style</span>
                        </div>
                        <div class="notes-body-text" v-if="copyreaderNotes">
                            {{ copyreaderNotes }}
                        </div>
                        <div class="notes-content-empty" v-else>
                            Notes and suggestions from the Copyreader will appear here during editorial review.
                        </div>
                    </div>

                    <!-- 3. EIC Notes -->
                    <div class="notes-card-box">
                        <div class="notes-hdr-row">
                            <h4 class="notes-hdr">
                                <span class="role-icon-circle amber">EIC</span>
                                Editor-in-Chief Notes
                            </h4>
                            <span class="review-step-label">Final Approval</span>
                        </div>
                        <div class="notes-body-text" v-if="eicNotes">
                            {{ eicNotes }}
                        </div>
                        <div class="notes-content-empty" v-else>
                            Notes from the Editor-in-Chief will appear here once endorsed by the editorial desk.
                        </div>
                    </div>

                    <!-- Footer Action Bar for Details -->
                    <div class="visuals-actions-footer">
                        <button type="button" class="btn-secondary-pill" @click="currentTab = 'visuals'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                            Back to Assets
                        </button>
                        <div class="actions-right">
                            <button type="button" class="btn-outline-pill" @click="saveAsDraft">
                                Save as Draft
                            </button>
                            <template v-if="isCopyreader">
                                <button type="button" class="btn-return-pill-ws" @click="openCopyreaderConfirm('return')" :disabled="copyreaderDone">
                                    Return to Writer
                                </button>
                                <button type="button" class="btn-blue-pill-action" @click="openCopyreaderConfirm('endorse')" :disabled="copyreaderDone">
                                    {{ copyreaderDone ? 'Already Handled' : 'Submit to Editor-In-Chief' }}
                                </button>
                            </template>
                            <button v-else type="button" class="btn-blue-pill-action" @click="isSubmitModalOpen = true" :disabled="alreadySubmitted">
                                {{ alreadySubmitted ? 'Already Submitted' : 'Submit for Review' }}
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- SUBMIT FOR REVIEW CONFIRMATION MODAL -->
        <div class="submodal-overlay" v-if="isSubmitModalOpen" @click.self="isSubmitModalOpen = false">
            <div class="submodal-card">
                <div class="submit-icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </div>

                <h3 class="submodal-title">{{ isSectionEditor ? 'Submit this article for Copyreader review?' : 'Submit this article for Section Editor review?' }}</h3>
                <p class="submodal-desc" v-if="isSectionEditor">
                    Your article <strong>"{{ articleHeadline || task.title }}"</strong> along with thumbnail and media assets will be sent directly to the <strong>Copyreader</strong> for review.
                </p>
                <p class="submodal-desc" v-else>
                    Your article <strong>"{{ articleHeadline || task.title }}"</strong> along with thumbnail and media assets will be sent to the <strong>{{ sectionEditorTitle }}</strong> for review.
                </p>

                <div class="submodal-actions">
                    <button type="button" class="btn-grey-pill" @click="isSubmitModalOpen = false" :disabled="isSubmitting">Cancel</button>
                    <button type="button" class="btn-blue-pill btn-full-width" @click="confirmSubmit" :disabled="isSubmitting">
                        {{ isSubmitting ? 'Submitting...' : 'Yes, Submit for Review' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- COPYREADER ACTION CONFIRMATION MODAL -->
        <div class="submodal-overlay" v-if="isCopyreaderModalOpen" @click.self="closeCopyreaderModal">
            <div class="submodal-card">
                <div :class="copyreaderAction === 'endorse' ? 'submit-icon-circle' : 'return-icon-circle'">
                    <svg v-if="copyreaderAction === 'endorse'" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></svg>
                </div>

                <h3 class="submodal-title">{{ copyreaderAction === 'endorse' ? 'Submit to Editor-in-Chief?' : 'Return to Writer?' }}</h3>
                <p class="submodal-desc" v-if="copyreaderAction === 'endorse'">
                    <strong>"{{ articleHeadline || task.title }}"</strong> along with your edits will be sent to the <strong>Editor-in-Chief</strong> for final approval.
                </p>
                <template v-else>
                    <p class="submodal-desc" style="margin-bottom: 14px;">Provide revision notes for the writer. They'll be notified and the article goes back to draft.</p>
                    <div class="ws-field-group">
                        <label class="ws-field-label">Revision Notes <span style="color: #dc2626;">*</span></label>
                        <textarea v-model="copyreaderReturnNotes" class="ws-textarea" rows="4" maxlength="800" placeholder="E.g. Please fix the grammar in paragraph 2..."></textarea>
                    </div>
                </template>

                <p v-if="copyreaderError" class="ws-error-msg">{{ copyreaderError }}</p>

                <div class="submodal-actions">
                    <button type="button" class="btn-grey-pill" @click="closeCopyreaderModal" :disabled="isCopyreaderActing">Cancel</button>
                    <button type="button" class="btn-blue-pill btn-full-width" @click="confirmCopyreaderAction" :disabled="isCopyreaderActing || (copyreaderAction === 'return' && !copyreaderReturnNotes.trim())">
                        {{ isCopyreaderActing ? 'Submitting...' : (copyreaderAction === 'endorse' ? 'Yes, Submit to EIC' : 'Yes, Return to Writer') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- SUBMISSION SUCCESS MODAL -->
        <div class="submodal-overlay" v-if="isSuccessModalOpen">
            <div class="submodal-card">
                <div class="success-icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>

                <h3 class="submodal-title">{{ copyreaderSuccessMessage ? 'Done!' : 'Your submission is successful!' }}</h3>
                <p class="submodal-desc" v-if="copyreaderSuccessMessage">{{ copyreaderSuccessMessage }}</p>
                <p class="submodal-desc" v-else-if="isSectionEditor">Your article has been submitted and will be reviewed directly by the Copyreader.</p>
                <p class="submodal-desc" v-else>The {{ sectionEditorTitle }} will review your draft and notify you if any revisions are needed before passing it to the Copyreader.</p>

                <div class="submodal-actions">
                    <button type="button" class="btn-grey-pill" @click="closeAllModals('done')">Done</button>
                    <button type="button" class="btn-blue-pill btn-full-width" @click="closeAllModals('submissions')">{{ isCopyreader ? 'Back to My Tasks' : 'View Submissions' }}</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { requireOk, followUp, followUpNote } from '../utils/http';
import { ref, computed, watch, onMounted, nextTick } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    taskData: {
        type: Object,
        default: () => ({})
    },
    allowSectionEdit: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close', 'task-submitted', 'task-saved-as-draft', 'view-submissions']);

const currentTab = ref('content');
const isSubmitModalOpen = ref(false);
const isSuccessModalOpen = ref(false);
const isSubmitting = ref(false);

const isCopyreaderModalOpen = ref(false);
const copyreaderAction = ref('endorse'); // 'endorse' | 'return'
const copyreaderReturnNotes = ref('');
const isCopyreaderActing = ref(false);
const copyreaderError = ref('');
const copyreaderSuccessMessage = ref('');
const saveFeedback = ref('');

// Editor & Headline State
const editorRef = ref(null);
const articleHeadline = ref('');
const articleContent = ref('');

// Visual Assets State
const thumbnailInputRef = ref(null);
const mediaInputRef = ref(null);
const thumbnailPreview = ref('');
const mediaPreviews = ref([]);
const isUploadingMedia = ref(false);
const isUploadingThumbnail = ref(false);

// Visuals the artist submitted for this task are read-only here — the writer can view them
// but shouldn't remove or replace what the artist provided.
const artistThumbnailUrl = ref('');
const artistMediaUrls = ref(new Set());
const isThumbnailFromArtist = computed(() => !!artistThumbnailUrl.value && thumbnailPreview.value === artistThumbnailUrl.value);
const isMediaFromArtist = (url) => artistMediaUrls.value.has(url);

const task = computed(() => props.taskData || {});

const alreadySubmitted = computed(() => ['submitted', 'completed'].includes(task.value?.status));

// User info from local storage
const currentUser = computed(() => {
    try {
        return JSON.parse(localStorage.getItem('sparky_user') || '{}');
    } catch {
        return {};
    }
});

const authorName = computed(() => {
    if ((isCopyreader.value || props.allowSectionEdit) && task.value?.writer?.name) {
        return task.value.writer.name;
    }
    return currentUser.value?.name || task.value?.assignee?.name || 'Staff Writer';
});

const authorAvatar = computed(() => {
    if ((isCopyreader.value || props.allowSectionEdit) && task.value?.writer) {
        return task.value.writer.avatar || `https://api.dicebear.com/7.x/lorelei/svg?seed=${encodeURIComponent(task.value.writer.name || 'Writer')}&backgroundColor=dbeafe`;
    }
    if (currentUser.value?.profile_picture) {
        return '/storage/' + currentUser.value.profile_picture;
    }
    if (currentUser.value?.profile_picture_url) {
        return currentUser.value.profile_picture_url;
    }
    return `https://api.dicebear.com/7.x/lorelei/svg?seed=${encodeURIComponent(authorName.value)}&backgroundColor=dbeafe`;
});

const collaboratorArtist = computed(() => {
    // Try multiple possible data sources for the artist name
    if (task.value?.mediaArtist) return task.value.mediaArtist;
    if (task.value?.artist_assigned) return task.value.artist_assigned;
    if (task.value?.raw?.artist_assigned) return task.value.raw.artist_assigned;

    // Check notes field for "Media Artist:" pattern
    const notesToCheck = task.value?.raw?.notes || task.value?.notes;
    if (notesToCheck && typeof notesToCheck === 'string') {
        const patterns = [
            /Media Artist:\s*([^|\n]+)/i,
            /Artist:\s*([^|\n]+)/i,
            /Photojournalist:\s*([^|\n]+)/i
        ];

        for (const pattern of patterns) {
            const match = notesToCheck.match(pattern);
            if (match && match[1]?.trim()) {
                return match[1].trim();
            }
        }
    }

    return null;
});

// Users list for finding artist profile picture
const allUsers = ref([]);

// Section/category editing (EIC-only, via allowSectionEdit).
// The `sections` table has stale/duplicate rows, so the dropdown is always
// built from this fixed canonical list rather than whatever's in the DB.
const CANONICAL_SECTIONS = ['News', 'Opinion', 'Editorial', 'Feature', 'Sci-Tech', 'DevCom', 'Sports', 'Literary', 'Video'];
const allSections = ref([]);
const selectedSectionId = ref(null);

const canonicalSectionOptions = computed(() => {
    return CANONICAL_SECTIONS
        .map(name => allSections.value.find(sec => sec.name === name))
        .filter(Boolean);
});

const isSectionMenuOpen = ref(false);
const selectedSectionLabel = computed(() => {
    return canonicalSectionOptions.value.find(sec => sec.id === selectedSectionId.value)?.name || 'Select section';
});

onMounted(() => {
    window.addEventListener('click', () => { isSectionMenuOpen.value = false; });
});

const collaboratorArtistUser = computed(() => {
    if (!collaboratorArtist.value) return null;
    return allUsers.value.find(u => u.name === collaboratorArtist.value);
});

const collaboratorArtistAvatar = computed(() => {
    if (collaboratorArtistUser.value?.profile_picture) {
        return '/storage/' + collaboratorArtistUser.value.profile_picture;
    }
    if (collaboratorArtistUser.value?.profile_picture_url) {
        return collaboratorArtistUser.value.profile_picture_url;
    }
    return `https://api.dicebear.com/7.x/lorelei/svg?seed=${encodeURIComponent(collaboratorArtist.value || 'artist')}&backgroundColor=dbeafe`;
});

const sectionName = computed(() => {
    const s = task.value?.section;
    if (typeof s === 'object' && s !== null) return s.name || 'News';
    return s || 'News';
});

const sectionEditorTitle = computed(() => {
    const sec = sectionName.value.toLowerCase();
    if (sec.includes('news')) return 'News Section Editor';
    if (sec.includes('feature')) return 'Feature Section Editor';
    if (sec.includes('opinion') || sec.includes('devcomm')) return 'Section Editor';
    if (sec.includes('sports')) return 'Sports Section Editor';
    return `${sectionName.value} Section Editor`;
});

const isCopyreader = computed(() => {
    const roles = [currentUser.value?.secondary_role, currentUser.value?.tertiary_role];
    return roles.includes('Copy Editor') || roles.includes('Copyreader');
});

const isSectionEditor = computed(() => {
    if (isCopyreader.value) return false;
    const role = (currentUser.value?.role || '').toLowerCase();
    const secRole = (currentUser.value?.secondary_role || '').toLowerCase();
    return role === 'section_editor' || role === 'eic' || secRole.includes('editor');
});

const copyreaderDone = computed(() => ['completed', 'returned'].includes(task.value?.status));

// Editorial Notes
const parseNotesField = (notes, key) => {
    if (!notes || typeof notes !== 'string') return '';
    const match = notes.match(new RegExp(`${key}:\\s*([^|]+)`, 'i'));
    return match ? match[1].trim() : '';
};

// Which review stage (Section Editor / Copyreader / EIC) most recently sent this
// task back for revision, so its note lands in the matching box below.
const returnedByRole = computed(() => task.value?.raw?.returned_by_role || task.value?.returned_by_role || '');

const taskRevisionNotes = computed(() => {
    const notes = task.value?.raw?.notes || task.value?.notes;
    return parseNotesField(notes, 'Revision Notes');
});

const sectionEditorNotes = computed(() => {
    if (returnedByRole.value === 'section_editor' && taskRevisionNotes.value) return taskRevisionNotes.value;
    return task.value?.sectionEditorNotes || task.value?.editorNotes || null;
});

const copyreaderNotes = computed(() => {
    if (returnedByRole.value === 'copyreader' && taskRevisionNotes.value) return taskRevisionNotes.value;
    return task.value?.copyreaderNotes || task.value?.copyNotes || null;
});

const eicNotes = computed(() => {
    if (returnedByRole.value === 'eic' && taskRevisionNotes.value) return taskRevisionNotes.value;
    return task.value?.eicNotes || null;
});

// Live Word Count calculation
const wordCount = computed(() => {
    const rawHeadline = (articleHeadline.value || '').trim();
    const cleanBody = (articleContent.value || '')
        .replace(/<[^>]*>/g, ' ')
        .replace(/&nbsp;/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();
    
    const combined = (rawHeadline + ' ' + cleanBody).trim();
    if (!combined) return 0;
    return combined.split(/\s+/).filter(Boolean).length;
});

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
        console.warn('Could not fetch users list', e);
    }
};

// Fetch sections from API (only needed when allowSectionEdit is enabled)
const fetchSections = async () => {
    try {
        const response = await fetch('/api/sections', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('sparky_token')}`,
                'Accept': 'application/json'
            }
        });
        if (response.ok) {
            const data = await response.json();
            allSections.value = Array.isArray(data) ? data : (data.sections || []);
        }
    } catch (e) {
        console.warn('Could not fetch sections list', e);
    }
};

// Watch task data to initialize workspace
watch(() => props.isOpen, async (newVal) => {
    if (newVal) {
        currentTab.value = 'content';
        saveFeedback.value = '';
        articleHeadline.value = task.value?.title || '';
        isCopyreaderModalOpen.value = false;
        copyreaderError.value = '';
        copyreaderSuccessMessage.value = '';

        // Load article content from backend if article exists
        const articleId = task.value?.article_id || task.value?.raw?.article_id || task.value?.article?.id;
        let articleData = null;
        let currentSectionName = null;
        if (articleId) {
            try {
                const token = localStorage.getItem('sparky_token');
                const response = await fetch(`/api/articles/${articleId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    articleData = await response.json();
                    articleHeadline.value = articleData.title || task.value?.title || '';
                    articleContent.value = articleData.content || '';
                    thumbnailPreview.value = articleData.cover_image || '';
                    currentSectionName = articleData.section?.name || null;
                }
            } catch (e) {
                console.warn('Could not load article:', e);
            }
        } else {
            // Load initial content from task
            const initialBody = task.value?.content || task.value?.article?.content || task.value?.articleDesc || task.value?.description || '';
            articleContent.value = initialBody;
        }

        nextTick(() => {
            if (editorRef.value) {
                editorRef.value.innerHTML = articleContent.value || '<p>Start typing your article content here...</p>';
            }
        });

        // Initialize assets if available (fallback for media)
        if (!thumbnailPreview.value) {
            thumbnailPreview.value = task.value?.cover_image || task.value?.thumbnail || '';
        }
        // Load persisted media files from article backend data (storage URLs)
        const persistedMedia = articleData?.media_files || task.value?.media_files || task.value?.media;
        if (Array.isArray(persistedMedia) && persistedMedia.length > 0) {
            mediaPreviews.value = persistedMedia.map(item =>
                typeof item === 'string' ? { url: item, name: item.split('/').pop(), size: null } : item
            );
        } else {
            mediaPreviews.value = [];
        }

        // What the artist submitted for this task (from either task source) is locked — the
        // writer can see it in the workspace but can't remove or swap it out.
        const notesToParse = task.value?.notes || task.value?.raw?.notes || '';
        artistThumbnailUrl.value = parseNotesField(notesToParse, 'Thumbnail');
        const artistMediaField = parseNotesField(notesToParse, 'Media Uploads');
        artistMediaUrls.value = new Set(
            artistMediaField ? artistMediaField.split(',').map(u => u.trim()).filter(Boolean) : []
        );


        // Debug: log task data to see what we're working with
        console.log('Task data in AssignmentWorkspaceModal:', task.value);
        console.log('Task notes:', task.value?.notes);
        console.log('Task raw notes:', task.value?.raw?.notes);
        console.log('Collaborator artist extracted:', collaboratorArtist.value);

        // Fetch users for artist profile picture
        fetchUsers();
        if (props.allowSectionEdit) {
            await fetchSections();
            const matched = allSections.value.find(sec => sec.name === currentSectionName);
            selectedSectionId.value = matched?.id ?? null;
        }
    }
}, { immediate: true });

// Watch for tab changes to restore editor content
watch(currentTab, (newTab) => {
    if (newTab === 'content') {
        nextTick(() => {
            if (editorRef.value && articleContent.value) {
                editorRef.value.innerHTML = articleContent.value;
            }
        });
    }
});

const handleEditorInput = () => {
    if (editorRef.value) {
        articleContent.value = editorRef.value.innerHTML;
    }
};

const formatDoc = (cmd, val = null) => {
    document.execCommand(cmd, false, val);
    handleEditorInput();
};

const applyFormatBlock = (tag) => {
    if (tag) {
        document.execCommand('formatBlock', false, `<${tag}>`);
        handleEditorInput();
    }
};

const promptLink = () => {
    const url = prompt('Enter the link URL (e.g. https://...):', 'https://');
    if (url) {
        formatDoc('createLink', url);
    }
};

// Thumbnail handlers
const triggerThumbnailInput = () => {
    if (thumbnailInputRef.value) thumbnailInputRef.value.click();
};

const uploadThumbnailFile = async (file) => {
    if (!file || !file.type.startsWith('image/')) return;
    isUploadingThumbnail.value = true;
    try {
        const token = localStorage.getItem('sparky_token');
        const formData = new FormData();
        formData.append('files[]', file);
        const response = await fetch('/api/articles/upload-media', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: formData
        });
        if (response.ok) {
            const data = await response.json();
            thumbnailPreview.value = data.urls[0] || '';
        } else {
            const err = await response.json().catch(() => ({}));
            alert('Failed to upload thumbnail: ' + (err.message || 'Please try again.'));
        }
    } catch (e) {
        console.error('Thumbnail upload error:', e);
        alert('Failed to upload thumbnail. Please check your connection and try again.');
    } finally {
        isUploadingThumbnail.value = false;
        if (thumbnailInputRef.value) thumbnailInputRef.value.value = '';
    }
};

const onThumbnailSelected = (e) => {
    const file = e.target.files[0];
    if (file) uploadThumbnailFile(file);
};

const handleThumbnailDrop = (e) => {
    const file = e.dataTransfer.files[0];
    if (file) uploadThumbnailFile(file);
};

const removeThumbnail = () => {
    thumbnailPreview.value = '';
    if (thumbnailInputRef.value) thumbnailInputRef.value.value = '';
};

// Media handlers (up to 3 photos)
const triggerMediaInput = () => {
    if (mediaInputRef.value) mediaInputRef.value.click();
};

const onMediaSelected = (e) => {
    const files = Array.from(e.target.files);
    addMediaFiles(files);
};

const handleMediaDrop = (e) => {
    const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
    addMediaFiles(files);
};

const addMediaFiles = async (files) => {
    const remainingSlots = 3 - mediaPreviews.value.length;
    if (remainingSlots <= 0) return;

    const filesToUpload = files.slice(0, remainingSlots);
    if (filesToUpload.length === 0) return;

    isUploadingMedia.value = true;
    try {
        const token = localStorage.getItem('sparky_token');
        const formData = new FormData();
        filesToUpload.forEach(file => formData.append('files[]', file));

        const response = await fetch('/api/articles/upload-media', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
                // No Content-Type header — browser sets it automatically with boundary for FormData
            },
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            data.urls.forEach((url, i) => {
                if (mediaPreviews.value.length < 3) {
                    mediaPreviews.value.push({
                        name: filesToUpload[i]?.name || url.split('/').pop(),
                        size: filesToUpload[i]?.size || null,
                        url
                    });
                }
            });
        } else {
            const err = await response.json().catch(() => ({}));
            alert('Failed to upload image(s): ' + (err.message || 'Please try again.'));
        }
    } catch (e) {
        console.error('Media upload error:', e);
        alert('Failed to upload image(s). Please check your connection and try again.');
    } finally {
        isUploadingMedia.value = false;
        if (mediaInputRef.value) mediaInputRef.value.value = '';
    }
};

const removeMedia = (index) => {
    mediaPreviews.value.splice(index, 1);
};

// Save handlers
// Media/artist tasks (illustration, photography, layout) are assigned alongside a
// writing task but don't get an article until the writer saves — link them to the
// article by matching their title once it exists, so collaboratorArtist can find them
// via article_id instead of the fragile title-matching fallback.
const linkSiblingArtistTask = async (articleId, token) => {
    try {
        const res = await fetch('/api/tasks', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        if (!res.ok) return;
        const allTasks = await res.json();
        if (!Array.isArray(allTasks)) return;

        const cleanTitle = (t) => (t || '')
            .replace(/\s*\([^)]*(visuals|video|graphics|photo|illustration|pj)[^)]*\)/i, '')
            .trim()
            .toLowerCase();
        const baseTitle = cleanTitle(task.value?.title || articleHeadline.value);
        if (!baseTitle) return;

        const sibling = allTasks.find(t =>
            ['illustration', 'photography', 'layout'].includes(t.type) &&
            !t.article_id &&
            cleanTitle(t.title) === baseTitle
        );

        if (sibling) {
            await fetch(`/api/tasks/${sibling.id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ article_id: articleId })
            });
        }
    } catch (e) {
        console.warn('Could not link sibling artist task:', e);
    }
};

const saveProgress = async () => {
    try {
        const token = localStorage.getItem('sparky_token');
        if (!token) {
            console.error('No authentication token found');
            return;
        }

        // Prepare article data
        const articleData = {
            title: articleHeadline.value || task.value.title,
            content: articleContent.value,
            word_count: wordCount.value,
            cover_image: thumbnailPreview.value,
            media_files: mediaPreviews.value.map(m => m.url),
            section_id: props.allowSectionEdit
                ? selectedSectionId.value
                : (task.value.section?.id || task.value.raw?.section_id || null),
            type: 'article'
        };

        let articleId = task.value.article_id || task.value.raw?.article_id || task.value.article?.id;

        // Create or update the article
        if (articleId) {
            // Update existing article
            const updateRes = await fetch(`/api/articles/${articleId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(articleData)
            });
            if (!updateRes.ok) {
                const errBody = await updateRes.json().catch(() => ({}));
                throw new Error(errBody.message || `Server error ${updateRes.status}`);
            }
        } else {
            // Create new article
            const articleResponse = await fetch('/api/articles', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(articleData)
            });
            if (!articleResponse.ok) {
                const errBody = await articleResponse.json().catch(() => ({}));
                throw new Error(errBody.message || `Server error ${articleResponse.status}`);
            }
            const newArticle = await articleResponse.json();
            articleId = newArticle.id;

            // Link the article to the task
            if (task.value.id) {
                await fetch(`/api/tasks/${task.value.id}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ article_id: articleId })
                });
            }
            // Update local task with article_id
            if (task.value) {
                task.value.article_id = articleId;
            }
        }

        await linkSiblingArtistTask(articleId, token);

        saveFeedback.value = '✓ Progress saved';
        setTimeout(() => {
            saveFeedback.value = '';
        }, 3000);
    } catch (err) {
        console.error('Error saving progress:', err);
        saveFeedback.value = `✗ Save failed: ${err.message}`;
        setTimeout(() => {
            saveFeedback.value = '';
        }, 4000);
    }
};

const saveAsDraft = async () => {
    try {
        const token = localStorage.getItem('sparky_token');
        if (!token) {
            console.error('No authentication token found');
            return;
        }

        // Prepare article data
        const articleData = {
            title: articleHeadline.value || task.value.title,
            content: articleContent.value,
            word_count: wordCount.value,
            cover_image: thumbnailPreview.value,
            media_files: mediaPreviews.value.map(m => m.url),
            section_id: props.allowSectionEdit
                ? selectedSectionId.value
                : (task.value.section?.id || task.value.raw?.section_id || null),
            type: 'article'
        };

        let articleId = task.value.article_id || task.value.raw?.article_id || task.value.article?.id;

        // Create or update the article
        if (articleId) {
            // Update existing article
            const articleResponse = await fetch(`/api/articles/${articleId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(articleData)
            });
            if (!articleResponse.ok) {
                const errBody = await articleResponse.json().catch(() => ({}));
                throw new Error(errBody.message || `Server error ${articleResponse.status}`);
            }
        } else {
            // Create new article
            const articleResponse = await fetch('/api/articles', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(articleData)
            });
            if (!articleResponse.ok) {
                const errBody = await articleResponse.json().catch(() => ({}));
                throw new Error(errBody.message || `Server error ${articleResponse.status}`);
            }
            const newArticle = await articleResponse.json();
            articleId = newArticle.id;

            // Link the article to the task
            if (task.value.id) {
                await fetch(`/api/tasks/${task.value.id}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        article_id: articleId,
                        status: 'in_progress'
                    })
                });
            }
        }

        // Update task status to in_progress
        if (task.value.id) {
            await fetch(`/api/tasks/${task.value.id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: 'in_progress'
                })
            });
        }

        await linkSiblingArtistTask(articleId, token);

        const payload = {
            ...task.value,
            title: articleHeadline.value || task.value.title,
            content: articleContent.value,
            word_count: wordCount.value,
            thumbnail: thumbnailPreview.value,
            media: mediaPreviews.value,
            status: 'in_progress',
            article_id: articleId
        };

        emit('task-saved-as-draft', payload);
        closeModal();
    } catch (err) {
        console.error('Error saving draft:', err);
        alert('Failed to save draft. Please try again.');
    }
};

const closeModal = () => {
    emit('close');
};

const confirmSubmit = async () => {
    isSubmitting.value = true;
    try {
        const token = localStorage.getItem('sparky_token');
        
        // Ensure article content is saved to database first
        try {
            await saveProgress();
        } catch (saveErr) {
            console.warn('Could not auto-save progress before submit:', saveErr);
        }

        if (token && task.value && task.value.id) {
            await fetch(`/api/tasks/${task.value.id}/submit`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    notes: `Submitted by ${authorName.value}. Headline: ${articleHeadline.value || task.value.title}`,
                    word_count: wordCount.value
                })
            }).catch(e => console.error(e));
        }

        const artId = task.value?.article_id || task.value?.raw?.article_id;
        if (token && artId) {
            await fetch(`/api/articles/${artId}/submit`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }).catch(e => console.error(e));
        }
    } catch (err) {
        console.error('Error submitting task:', err);
    } finally {
        isSubmitting.value = false;
        isSubmitModalOpen.value = false;
        copyreaderSuccessMessage.value = '';
        isSuccessModalOpen.value = true;

        emit('task-submitted', {
            ...task.value,
            title: articleHeadline.value || task.value.title,
            content: articleContent.value,
            word_count: wordCount.value,
            thumbnail: thumbnailPreview.value,
            media: mediaPreviews.value,
            status: 'submitted'
        });
    }
};

const openCopyreaderConfirm = (action) => {
    copyreaderAction.value = action;
    copyreaderReturnNotes.value = '';
    copyreaderError.value = '';
    isCopyreaderModalOpen.value = true;
};

const closeCopyreaderModal = () => {
    if (isCopyreaderActing.value) return;
    isCopyreaderModalOpen.value = false;
};

const confirmCopyreaderAction = async () => {
    if (copyreaderAction.value === 'return' && !copyreaderReturnNotes.value.trim()) {
        copyreaderError.value = 'Please provide revision notes.';
        return;
    }

    isCopyreaderActing.value = true;
    copyreaderError.value = '';

    try {
        const authToken = localStorage.getItem('sparky_token');

        // Persist the copyreader's edits first
        try {
            await saveProgress();
        } catch (saveErr) {
            console.warn('Could not auto-save progress before copyreader action:', saveErr);
        }

        const articleId = task.value?.article_id || task.value?.raw?.article_id || task.value?.article?.id;
        const editingTaskId = task.value?.id;

        if (copyreaderAction.value === 'endorse') {
            if (!articleId) throw new Error('Missing article ID.');

            const res = await fetch(`/api/articles/${articleId}/endorse`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${authToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ editor_notes: '' })
            });
            if (!res.ok) {
                const d = await res.json().catch(() => ({}));
                throw new Error(d.message || 'Failed to submit to the Editor-in-Chief.');
            }

            const problems = [];
            if (editingTaskId) {
                problems.push(await followUp('Your copyreading task could not be marked complete.', () => fetch(`/api/tasks/${editingTaskId}/complete`, {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                })));
            }

            copyreaderSuccessMessage.value = `"${articleHeadline.value || task.value.title}" has been sent to the Editor-in-Chief for final approval.` + followUpNote(problems);
        } else {
            // Find the writer's original writing task via the article's tasks
            let writingTaskId = null;
            if (articleId) {
                const artRes = await fetch(`/api/articles/${articleId}`, {
                    headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                });
                if (artRes.ok) {
                    const artData = await artRes.json();
                    writingTaskId = (artData.tasks || []).find(t => t.type === 'writing')?.id || null;
                }
            }

            // Sending the writer's task back is what tells them about the revision, so it must work
            if (writingTaskId) {
                await requireOk(await fetch(`/api/tasks/${writingTaskId}/return`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ notes: copyreaderReturnNotes.value.trim() })
                }), 'Could not return the task to the writer.');
            }

            if (articleId) {
                await requireOk(await fetch(`/api/articles/${articleId}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: 'draft' })
                }), 'The writer was notified, but the article could not be moved back to draft.');
            }

            const problems = [];
            if (editingTaskId) {
                problems.push(await followUp('Your copyreading task could not be marked complete.', () => fetch(`/api/tasks/${editingTaskId}/complete`, {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                })));
            }

            copyreaderSuccessMessage.value = `"${articleHeadline.value || task.value.title}" has been returned to the writer with your revision notes.` + followUpNote(problems);
        }

        isCopyreaderModalOpen.value = false;
        isSuccessModalOpen.value = true;

        emit('task-submitted', {
            ...task.value,
            title: articleHeadline.value || task.value.title,
            content: articleContent.value,
            status: 'completed'
        });
    } catch (e) {
        copyreaderError.value = e.message || 'An error occurred.';
    } finally {
        isCopyreaderActing.value = false;
    }
};

const closeAllModals = (action) => {
    isSuccessModalOpen.value = false;
    copyreaderSuccessMessage.value = '';
    closeModal();
    if (action === 'submissions') {
        emit('view-submissions');
    }
};
</script>

<style scoped>
.workspace-modal-overlay,
.submodal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(6px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    box-sizing: border-box;
}

.workspace-modal-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 1040px;
    max-height: 90vh;
    padding: 36px 40px;
    box-sizing: border-box;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    text-align: left;
    font-family: 'Manrope', sans-serif;
}

@keyframes popIn {
    from { opacity: 0; transform: scale(0.97) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-hdr {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}

.modal-hdr-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.modal-blue-title {
    color: #1d6bf3;
    font-size: 26px;
    font-weight: 800;
    margin: 0;
    letter-spacing: -0.5px;
}

.save-feedback-badge {
    background: #dcfce7;
    color: #15803d;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-x-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 6px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.modal-x-btn:hover {
    background: #f1f5f9;
}

.article-meta-hdr {
    margin-bottom: 18px;
}

.section-pill-badge {
    background-color: #dbeafe;
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
    margin-bottom: 8px;
}

.section-edit-dropdown {
    position: relative;
    display: inline-block;
    margin-bottom: 8px;
}

.section-edit-trigger {
    background-color: #dbeafe;
    color: #1e40af;
    padding: 4px 10px 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    outline: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.15s;
}

.section-edit-trigger:hover {
    background-color: #bfdbfe;
}

.section-edit-trigger svg {
    transition: transform 0.15s;
    flex-shrink: 0;
}

.section-edit-trigger svg.rotated {
    transform: rotate(180deg);
}

.section-edit-menu {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 16px 32px -8px rgba(0, 0, 0, 0.25);
    padding: 6px;
    width: 180px;
    z-index: 20;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.section-edit-option {
    background: none;
    border: none;
    text-align: left;
    padding: 8px 10px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    transition: background 0.15s;
}

.section-edit-option:hover {
    background: #f1f5f9;
}

.section-edit-option.selected {
    background: #dbeafe;
    color: #1e40af;
    font-weight: 800;
}

.section-edit-empty {
    margin: 4px 8px;
    font-size: 12px;
    color: #94a3b8;
}

.article-main-title {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    line-height: 1.3;
    letter-spacing: -0.4px;
}

.workspace-tabs-nav {
    display: flex;
    gap: 32px;
    border-bottom: 1.5px solid #e2e8f0;
    margin-bottom: 24px;
}

.tab-nav-btn {
    background: none;
    border: none;
    padding: 12px 0;
    font-size: 15px;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    position: relative;
    font-family: inherit;
    transition: color 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tab-nav-btn.active {
    color: #1d6bf3;
}

.tab-nav-btn.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background-color: #1d6bf3;
    border-radius: 3px;
}

.tab-asset-count {
    background: #1d6bf3;
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 10px;
}

.tab-content-body {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Headline Input */
.headline-bar-card {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.headline-label {
    font-size: 13px;
    font-weight: 700;
    color: #475569;
}

.headline-input {
    width: 100%;
    padding: 14px 18px;
    border: 1.5px solid #cbd5e1;
    border-radius: 16px;
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    box-sizing: border-box;
    font-family: inherit;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.headline-input:focus {
    border-color: #1d6bf3;
    box-shadow: 0 0 0 3px rgba(29, 107, 243, 0.15);
}

/* Editor Container */
.editor-container-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1.5px solid #e2e8f0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.rich-toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.toolbar-select {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 5px 10px;
    font-size: 13px;
    font-family: inherit;
    color: #0f172a;
    background: #ffffff;
}

.toolbar-divider {
    width: 1px;
    height: 20px;
    background: #cbd5e1;
    margin: 0 4px;
}

.tool-btn {
    background: none;
    border: none;
    padding: 6px 9px;
    font-size: 14px;
    color: #475569;
    cursor: pointer;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
}

.tool-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
}

.article-text-body {
    padding: 24px;
    min-height: 260px;
    max-height: 480px;
    overflow-y: auto;
    font-size: 15px;
    color: #1e293b;
    line-height: 1.7;
    outline: none;
    background: #ffffff;
    font-family: inherit;
}

.article-text-body:empty:before {
    content: attr(placeholder);
    color: #94a3b8;
}

.editor-footer-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 24px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.author-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    padding: 4px 14px 4px 6px;
    border-radius: 24px;
    border: 1px solid #e2e8f0;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
}

.author-avatar {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    object-fit: cover;
}

.word-count-text {
    font-size: 13px;
    color: #64748b;
    font-weight: 700;
}

/* Action Footers */
.content-actions-footer,
.visuals-actions-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
}

.actions-left {
    display: flex;
    align-items: center;
}

.status-hint-text {
    font-size: 13px;
    color: #64748b;
    text-transform: capitalize;
}

.actions-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-secondary-pill {
    background-color: #f1f5f9;
    color: #334155;
    border: none;
    padding: 12px 22px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-family: inherit;
    transition: all 0.2s;
}

.btn-secondary-pill:hover {
    background-color: #e2e8f0;
    color: #0f172a;
}

.btn-outline-pill {
    background-color: transparent;
    color: #1d6bf3;
    border: 1.5px solid #1d6bf3;
    padding: 11px 22px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.2s;
}

.btn-outline-pill:hover {
    background-color: #eff6ff;
}

.btn-blue-pill {
    background-color: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 12px 26px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: inherit;
    box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3);
    transition: all 0.2s;
}

.btn-blue-pill:hover {
    background-color: #1557b0;
    box-shadow: 0 6px 18px rgba(29, 107, 243, 0.4);
}

.btn-blue-pill-action {
    background-color: #1d6bf3;
    color: #ffffff;
    border: none;
    padding: 12px 28px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    box-shadow: 0 4px 14px rgba(29, 107, 243, 0.3);
    transition: all 0.2s;
}

.btn-blue-pill-action:hover:not(:disabled) {
    background-color: #1557b0;
    box-shadow: 0 6px 18px rgba(29, 107, 243, 0.4);
}

.btn-blue-pill-action:disabled {
    background-color: #94a3b8;
    box-shadow: none;
    cursor: not-allowed;
}

.btn-return-pill-ws {
    background-color: #f59e0b;
    color: #ffffff;
    border: none;
    padding: 12px 28px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
    transition: all 0.2s;
}

.btn-return-pill-ws:hover:not(:disabled) {
    background-color: #d97706;
    box-shadow: 0 6px 18px rgba(245, 158, 11, 0.4);
}

.btn-return-pill-ws:disabled {
    background-color: #94a3b8;
    box-shadow: none;
    cursor: not-allowed;
}

/* Upload spinner overlay */
.media-uploading-overlay {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px;
    background: #f0f6ff;
    border: 1.5px dashed #93c5fd;
    border-radius: 12px;
    color: #1d6bf3;
    font-size: 14px;
    font-weight: 500;
    margin-top: 8px;
}

.upload-spinner {
    width: 18px;
    height: 18px;
    border: 2.5px solid #bfdbfe;
    border-top-color: #1d6bf3;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    display: inline-block;
    flex-shrink: 0;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Visual Assets Styling */
.visuals-section-card,
.details-container-card {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.asset-group {
    background: #f8fafc;
    border-radius: 20px;
    padding: 24px;
    border: 1px solid #e2e8f0;
}

.asset-hdr {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.asset-title {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 4px 0;
}

.asset-subtitle {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}

.assignee-pill {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    padding: 5px 14px;
    border-radius: 20px;
    border: 1px solid #cbd5e1;
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
}

.artist-icon {
    font-size: 14px;
}

.artist-avatar {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    object-fit: cover;
}

.hidden-file-input {
    display: none;
}

.dashed-dropzone {
    border: 2px dashed #cbd5e1;
    border-radius: 18px;
    background: #ffffff;
    padding: 32px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s, background-color 0.2s;
}

.dashed-dropzone:hover {
    border-color: #1d6bf3;
    background-color: #f8faff;
}

.dashed-dropzone.compact-dropzone {
    padding: 20px;
}

.dropzone-text {
    font-size: 13.5px;
    color: #0f172a;
    margin: 0;
    line-height: 1.45;
}

.dropzone-text .sub {
    font-size: 12px;
    color: #94a3b8;
}

.btn-blue-pill-small {
    background-color: #1d6bf3;
    color: white;
    border: none;
    padding: 9px 20px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    font-family: inherit;
}

.btn-grey-pill-small {
    background-color: #475569;
    color: white;
    border: none;
    padding: 9px 20px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    font-family: inherit;
}

.preview-card-container {
    display: flex;
    justify-content: center;
}

.thumbnail-preview-box {
    position: relative;
    max-width: 420px;
    width: 100%;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.thumbnail-img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}

.preview-actions-overlay {
    position: absolute;
    bottom: 0;
    inset-inline: 0;
    background: linear-gradient(transparent, rgba(15, 23, 42, 0.85));
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding: 12px 16px;
}

.preview-action-btn {
    background: #ffffff;
    color: #0f172a;
    border: none;
    padding: 6px 14px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
}

.preview-action-btn.danger {
    background: #fee2e2;
    color: #dc2626;
}

/* Media Grid */
.media-grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 12px;
}

.media-preview-card {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.media-preview-img {
    width: 100%;
    height: 140px;
    object-fit: cover;
    display: block;
}

.remove-media-circle {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(15, 23, 42, 0.7);
    color: #ffffff;
    border: none;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.remove-media-circle:hover {
    background: #ef4444;
}

.artist-asset-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(15, 23, 42, 0.75);
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 12px;
    letter-spacing: 0.02em;
}

.media-name-tag {
    display: block;
    padding: 8px 12px;
    font-size: 11.5px;
    font-weight: 600;
    color: #475569;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.divider-line {
    height: 1px;
    background: #e2e8f0;
}

/* Details Tab Styling */
.workflow-badge-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    padding: 14px 20px;
    background: #f8fafc;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
}

.workflow-step-pill {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #64748b;
    padding: 4px 12px;
    border-radius: 14px;
    font-size: 12px;
    font-weight: 700;
}

.workflow-step-pill.active {
    background: #eff6ff;
    border-color: #1d6bf3;
    color: #1d6bf3;
}

.workflow-arrow {
    color: #94a3b8;
    font-weight: 700;
    font-size: 14px;
}

.notes-card-box {
    background: #f8fafc;
    border-radius: 20px;
    padding: 24px;
    border: 1px solid #e2e8f0;
}

.notes-hdr-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.notes-hdr {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.review-step-label {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    background: #ffffff;
    padding: 3px 10px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.role-icon-circle {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 800;
    color: #ffffff;
}

.role-icon-circle.blue {
    background-color: #1d6bf3;
}

.role-icon-circle.purple {
    background-color: #8b5cf6;
}

.role-icon-circle.amber {
    background-color: #f59e0b;
}

.notes-body-text {
    background: #ffffff;
    border-radius: 14px;
    padding: 16px 20px;
    font-size: 14px;
    color: #334155;
    line-height: 1.6;
    border: 1px solid #e2e8f0;
    white-space: pre-wrap;
}

.notes-content-empty {
    background: #ffffff;
    border-radius: 14px;
    padding: 28px 20px;
    font-size: 13.5px;
    color: #64748b;
    text-align: center;
    border: 1px solid #f1f5f9;
}

/* Sub-modals */
.submodal-card {
    background: #ffffff;
    border-radius: 28px;
    width: 100%;
    max-width: 440px;
    padding: 36px;
    text-align: center;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: popIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    font-family: 'Manrope', sans-serif;
}

.submit-icon-circle {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background-color: #1d6bf3;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px auto;
}

.return-icon-circle {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background-color: #f59e0b;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px auto;
}

.ws-field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    text-align: left;
    margin-bottom: 10px;
}

.ws-field-label {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.ws-textarea {
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
    font-size: 14px;
    color: #1e293b;
    width: 100%;
    resize: vertical;
    outline: none;
    box-sizing: border-box;
    font-family: inherit;
    transition: border-color 0.15s;
}

.ws-textarea:focus {
    border-color: #2563eb;
}

.ws-error-msg {
    color: #dc2626;
    font-size: 13px;
    margin: 0 0 12px;
    padding: 8px 12px;
    background: #fef2f2;
    border-radius: 8px;
    border: 1px solid #fecaca;
    text-align: left;
}

.success-icon-circle {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background-color: #dcfce7;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px auto;
}

.submodal-title {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 10px 0;
    line-height: 1.35;
}

.submodal-desc {
    font-size: 14px;
    color: #64748b;
    line-height: 1.5;
    margin: 0 0 26px 0;
}

.submodal-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-grey-pill {
    background-color: #f1f5f9;
    color: #475569;
    border: none;
    padding: 13px;
    width: 100%;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
}

.btn-grey-pill:hover {
    background-color: #e2e8f0;
}

.btn-blue-pill.btn-full-width {
    width: 100%;
    justify-content: center;
    padding: 13px;
}
</style>
