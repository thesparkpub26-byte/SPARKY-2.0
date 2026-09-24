<template>
  <div class="profile-page">
    <header class="profile-header">
      <div class="profile-brand">
        <img src="/assets/Spark_Logo.png" alt="TheSpark logo" class="profile-brand-logo">
        <div>
          <strong>TheSpark</strong>
          <span>Publication</span>
        </div>
      </div>
      <button type="button" class="profile-return" @click="returnToDashboard">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="m15 18-6-6 6-6" />
        </svg>
        Return
      </button>
    </header>

    <main class="profile-main">
      <!-- Profile Card (Covers >= 70% of screen width) -->
      <div class="profile-card">

        <!-- Left Column: Avatar & Overview -->
        <div class="profile-left">
          <div
            class="avatar-wrapper"
            :class="{ 'is-editable': isEditing }"
            @click="isEditing && triggerFileInput()"
          >
            <img
              v-if="avatarPreview || user.profile_picture_url"
              :src="avatarPreview || user.profile_picture_url"
              class="avatar-img"
              alt="Profile Picture"
            />
            <div v-else class="avatar-placeholder">
              {{ initials }}
            </div>

            <div v-if="isEditing" class="avatar-edit-overlay">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                <circle cx="12" cy="13" r="4"/>
              </svg>
              <span>Change Photo</span>
            </div>
          </div>

          <input
            ref="fileInput"
            type="file"
            accept="image/*"
            class="hidden-input"
            @change="onFileChange"
          />

          <div class="user-sidebar-info">
            <h2 class="user-display-name">{{ user.name }}</h2>
            <p class="user-display-email">{{ user.email }}</p>
          </div>

          <div v-if="isEditing" class="photo-hint">
            Click on photo above to choose a new image
          </div>
        </div>

        <!-- Vertical Divider -->
        <div class="profile-divider"></div>

        <!-- Right Column: Info & Actions -->
        <div class="profile-right">

          <!-- View Mode -->
          <div v-if="!isEditing" class="view-mode">
            <div class="section-header">
              <h1 class="section-title">My Profile</h1>
              <p class="section-subtitle">Manage your personal account details and preferences.</p>
            </div>

            <div class="info-grid">
              <div class="info-block">
                <span class="info-label">Full Name</span>
                <span class="info-value">{{ user.name }}</span>
              </div>
              <div class="info-block">
                <span class="info-label">Email Address</span>
                <span class="info-value">{{ user.email }}</span>
              </div>
              <div class="info-block full-width">
                <span class="info-label">Member Since</span>
                <span class="info-value">{{ joinDate }}</span>
              </div>
            </div>

            <div class="action-row">
              <button class="btn-edit" @click="startEdit">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit Profile
              </button>
              <button class="btn-delete" @click="showDeleteConfirm = true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6"/><path d="M14 11v6"/>
                  <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
                Delete Account
              </button>
            </div>
          </div>

          <!-- Edit Mode -->
          <div v-else class="edit-mode">
            <div class="section-header">
              <h1 class="section-title">Edit Profile</h1>
              <p class="section-subtitle">Update your profile details or security information.</p>
            </div>

            <div v-if="errorMsg" class="alert-error">{{ errorMsg }}</div>
            <div v-if="successMsg" class="alert-success">{{ successMsg }}</div>

            <form @submit.prevent="saveProfile" class="edit-form">
              <div class="form-group">
                <label>Full Name</label>
                <input v-model="form.name" type="text" placeholder="Your full name" required />
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>New Password <span class="optional">(leave blank to keep)</span></label>
                  <div class="input-password-wrap">
                    <input
                      v-model="form.password"
                      :type="showPw ? 'text' : 'password'"
                      placeholder="New password (min 8 chars)"
                    />
                    <button type="button" class="eye-toggle" @click="showPw = !showPw">
                      <svg v-if="!showPw" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                      <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                  </div>
                </div>

                <div class="form-group">
                  <label>Confirm Password</label>
                  <div class="input-password-wrap">
                    <input
                      v-model="form.password_confirmation"
                      :type="showConfirm ? 'text' : 'password'"
                      placeholder="Repeat new password"
                    />
                    <button type="button" class="eye-toggle" @click="showConfirm = !showConfirm">
                      <svg v-if="!showConfirm" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                      <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                  </div>
                </div>
              </div>

              <p v-if="pwMismatch" class="mismatch-msg">Passwords do not match.</p>

              <div class="form-actions">
                <button type="submit" class="btn-save" :disabled="loading || pwMismatch">
                  {{ loading ? 'Saving…' : 'Save Changes' }}
                </button>
                <button type="button" class="btn-cancel" @click="cancelEdit">Cancel</button>
              </div>
            </form>
          </div>

        </div>

      </div>
    </main>

    <!-- Delete Confirm Modal -->
    <div v-if="showDeleteConfirm" class="profile-delete-modal-overlay" @click.self="showDeleteConfirm = false">
      <div class="profile-delete-modal-card">
        <div class="modal-icon-danger">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
          </svg>
        </div>
        <h3>Delete Account?</h3>
        <p>This action is <strong>permanent</strong> and cannot be undone. All your data will be erased immediately.</p>
        <div v-if="deleteError" class="alert-error" style="margin-bottom: 16px; text-align:left;">{{ deleteError }}</div>
        <div class="modal-actions">
          <button class="btn-confirm-delete" :disabled="deleteLoading" @click="confirmDelete">
            {{ deleteLoading ? 'Deleting…' : 'Yes, Delete My Account' }}
          </button>
          <button class="btn-modal-cancel" @click="showDeleteConfirm = false">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// ── Load user from localStorage ───────────────────────────────────────────────
const storedUser = JSON.parse(localStorage.getItem('sparky_user') || '{}');
const user = ref({ ...storedUser });

const token = localStorage.getItem('sparky_token');

const returnToDashboard = () => {
  const dashboardByRole = {
    admin: '/admin',
    eic: '/eic',
    section_editor: '/editor',
    staff_writer: '/writer',
    staff_artist: '/artist',
    staff_broadcaster: '/broadcaster',
  };
  router.push(dashboardByRole[user.value.role] || '/');
};

// Refresh user data from server on mount to get the server-computed profile_picture_url
onMounted(async () => {
  try {
    const res = await fetch('/api/me', {
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
    });
    if (res.ok) {
      const fresh = await res.json();
      user.value = fresh;
      localStorage.setItem('sparky_user', JSON.stringify(fresh));
    }
  } catch { /* keep localStorage data */ }
});


// ── Computed ──────────────────────────────────────────────────────────────────
const initials = computed(() => {
  const parts = (user.value.name || '?').split(' ');
  return parts.map(p => p[0]).join('').toUpperCase().slice(0, 2);
});

const joinDate = computed(() => {
  if (!user.value.created_at) return '—';
  return new Date(user.value.created_at).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
});

// ── Edit state ────────────────────────────────────────────────────────────────
const isEditing   = ref(false);
const loading     = ref(false);
const errorMsg    = ref('');
const successMsg  = ref('');
const showPw      = ref(false);
const showConfirm = ref(false);
const avatarPreview = ref(null);
const selectedFile  = ref(null);
const fileInput     = ref(null);
const showDeleteConfirm = ref(false);
const deleteLoading     = ref(false);

const form = ref({ name: '', password: '', password_confirmation: '' });

const pwMismatch = computed(() =>
  form.value.password_confirmation.length > 0 &&
  form.value.password !== form.value.password_confirmation
);

const startEdit = () => {
  form.value = { name: user.value.name, password: '', password_confirmation: '' };
  errorMsg.value = '';
  successMsg.value = '';
  avatarPreview.value = null;
  selectedFile.value = null;
  isEditing.value = true;
};

const cancelEdit = () => {
  isEditing.value = false;
  avatarPreview.value = null;
};

const triggerFileInput = () => fileInput.value?.click();

const onFileChange = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  selectedFile.value = file;
  avatarPreview.value = URL.createObjectURL(file);
};

// ── Save profile ──────────────────────────────────────────────────────────────
const saveProfile = async () => {
  if (pwMismatch.value) return;
  errorMsg.value = '';
  successMsg.value = '';
  loading.value = true;

  try {
    const fd = new FormData();
    if (form.value.name !== user.value.name) fd.append('name', form.value.name);
    if (form.value.password) {
      fd.append('password', form.value.password);
      fd.append('password_confirmation', form.value.password_confirmation);
    }
    if (selectedFile.value) fd.append('profile_picture', selectedFile.value);

    const res = await fetch('/api/profile', {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
      body: fd,
    });

    const data = await res.json();
    if (!res.ok) {
      const firstErr = data.errors ? Object.values(data.errors)[0][0] : data.message;
      errorMsg.value = firstErr || 'Failed to update profile.';
      return;
    }

    // Update local state — use profile_picture_url directly from server accessor
    user.value = { ...data.user };
    localStorage.setItem('sparky_user', JSON.stringify(data.user));
    successMsg.value = 'Profile updated successfully!';
    isEditing.value = false;
    avatarPreview.value = null;

  } catch {
    errorMsg.value = 'Could not connect to the server.';
  } finally {
    loading.value = false;
  }
};

// ── Delete account ────────────────────────────────────────────────────────────
const deleteError = ref('');

const confirmDelete = async () => {
  deleteLoading.value = true;
  deleteError.value = '';
  try {
    const res = await fetch('/api/profile', {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
    });

    if (!res.ok) {
      const data = await res.json().catch(() => ({}));
      deleteError.value = data.message || `Server error (${res.status}). Please try again.`;
      deleteLoading.value = false;
      return;
    }

    // Success — clear session and redirect
    localStorage.removeItem('sparky_token');
    localStorage.removeItem('sparky_user');
    router.push('/');
  } catch (err) {
    deleteError.value = 'Could not connect to the server. Please check your connection.';
    deleteLoading.value = false;
  }
};
</script>

<style scoped>
/* ── Layout ─────────────────────────────────────────────────────────────── */
.profile-page {
  min-height: 100vh;
  background: linear-gradient(145deg, #f0f4ff 0%, #e8eeff 50%, #f5f7ff 100%);
  display: flex;
  flex-direction: column;
}

.profile-header {
  min-height: 76px;
  padding: 0 42px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #ffffff;
  border-bottom: 1px solid rgba(30, 64, 175, 0.08);
  box-sizing: border-box;
}

.profile-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #13213f;
}

.profile-brand-logo {
  width: 38px;
  height: 38px;
  object-fit: contain;
}

.profile-brand strong,
.profile-brand span {
  display: block;
}

.profile-brand strong {
  font-size: 17px;
  line-height: 1.1;
}

.profile-brand span {
  margin-top: 2px;
  color: #64748b;
  font-size: 10px;
}

.profile-return {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 10px 16px;
  border: 1px solid #dbe5ff;
  border-radius: 10px;
  background: #ffffff;
  color: #2459c4;
  font: inherit;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s, border-color 0.2s;
}

.profile-return:hover {
  background: #eff4ff;
  border-color: #b8ccff;
}

.profile-main {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 56px 32px 80px;
}

/* ── Card (Covers >= 70% of screen width) ────────────────────────────── */
.profile-card {
  background: #ffffff;
  border-radius: 24px;
  box-shadow: 0 16px 56px rgba(30, 64, 175, 0.1), 0 4px 16px rgba(0, 0, 0, 0.04);
  width: 75%;
  min-width: 70vw;
  max-width: 1100px;
  padding: 48px;
  border: 1px solid rgba(30, 64, 175, 0.08);
  display: flex;
  gap: 48px;
  align-items: flex-start;
}

/* ── Left Column: Avatar & Summary ────────────────────────────────────── */
.profile-left {
  width: 260px;
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding-top: 8px;
}

.avatar-wrapper {
  position: relative;
  width: 140px;
  height: 140px;
  border-radius: 50%;
  margin-bottom: 20px;
  box-shadow: 0 8px 24px rgba(30, 64, 175, 0.15);
  transition: transform 0.2s;
}

.avatar-wrapper.is-editable {
  cursor: pointer;
}
.avatar-wrapper.is-editable:hover {
  transform: scale(1.03);
}

.avatar-img {
  width: 140px;
  height: 140px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #bfdbfe;
}

.avatar-placeholder {
  width: 140px;
  height: 140px;
  border-radius: 50%;
  background: linear-gradient(135deg, #1e40af, #3b82f6);
  color: #ffffff;
  font-size: 46px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 4px solid #bfdbfe;
  letter-spacing: 2px;
}

.avatar-edit-overlay {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  background: rgba(30, 64, 175, 0.75);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 6px;
  color: white;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  backdrop-filter: blur(2px);
}

.hidden-input { display: none; }

.user-sidebar-info {
  width: 100%;
}

.user-display-name {
  font-size: 22px;
  font-weight: 800;
  color: #0f172a;
  margin: 0 0 6px;
  word-break: break-word;
}

.user-display-email {
  font-size: 14px;
  color: #64748b;
  margin: 0;
  word-break: break-all;
}

.photo-hint {
  margin-top: 14px;
  font-size: 12px;
  color: #2563eb;
  background: #eff6ff;
  padding: 6px 12px;
  border-radius: 8px;
  font-weight: 500;
}

/* ── Vertical Divider ────────────────────────────────────────────────────── */
.profile-divider {
  width: 1px;
  align-self: stretch;
  background: linear-gradient(to bottom, transparent, #e2e8f0 20%, #e2e8f0 80%, transparent);
}

/* ── Right Column: Info & Actions ────────────────────────────────────────── */
.profile-right {
  flex: 1;
  min-width: 0;
}

.section-header {
  margin-bottom: 32px;
}

.section-title {
  font-size: 26px;
  font-weight: 800;
  color: #0f172a;
  margin: 0 0 6px;
}

.section-subtitle {
  font-size: 14px;
  color: #64748b;
  margin: 0;
}

/* ── View Mode Grid ──────────────────────────────────────────────────────── */
.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 36px;
}

.info-block {
  background: #f8faff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 18px 22px;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.info-block.full-width {
  grid-column: 1 / -1;
}

.info-label {
  font-size: 11px;
  font-weight: 700;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: 1.2px;
}

.info-value {
  font-size: 16px;
  font-weight: 600;
  color: #1e293b;
}

/* ── Action Buttons ──────────────────────────────────────────────────────── */
.action-row {
  display: flex;
  gap: 14px;
}

.btn-edit {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  background: linear-gradient(135deg, #1e40af, #3b82f6);
  color: white;
  border: none;
  border-radius: 12px;
  padding: 14px 28px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(30, 64, 175, 0.25);
  transition: opacity 0.2s, transform 0.15s;
}
.btn-edit:hover { opacity: 0.92; transform: translateY(-1px); }

.btn-delete {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  background: #ffffff;
  color: #dc2626;
  border: 1.5px solid #fecaca;
  border-radius: 12px;
  padding: 14px 24px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, border-color 0.2s;
}
.btn-delete:hover { background: #fff5f5; border-color: #f87171; }

/* ── Edit Form ───────────────────────────────────────────────────────────── */
.edit-mode {
  display: flex;
  flex-direction: column;
}

.edit-form {
  display: flex;
  flex-direction: column;
  gap: 22px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  font-size: 13px;
  font-weight: 600;
  color: #374151;
}

.optional {
  font-weight: 400;
  color: #94a3b8;
  font-size: 12px;
}

.form-group input {
  width: 100%;
  padding: 12px 16px;
  border: 1.5px solid #e2e8f0;
  border-radius: 10px;
  font-size: 15px;
  color: #1e293b;
  background: #f9fafb;
  transition: border-color 0.2s, box-shadow 0.2s;
  box-sizing: border-box;
}
.form-group input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
  background: #ffffff;
}

.input-password-wrap {
  position: relative;
  display: flex;
  align-items: center;
}
.input-password-wrap input { padding-right: 48px; }

.eye-toggle {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  cursor: pointer;
  color: #94a3b8;
  padding: 0;
  display: flex;
  align-items: center;
}

.mismatch-msg {
  font-size: 13px;
  color: #dc2626;
  margin: -6px 0 0;
}

.form-actions {
  display: flex;
  gap: 14px;
  margin-top: 8px;
}

.btn-save {
  flex: 1;
  background: linear-gradient(135deg, #1e40af, #3b82f6);
  color: white;
  border: none;
  border-radius: 12px;
  padding: 14px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(30, 64, 175, 0.25);
  transition: opacity 0.2s;
}
.btn-save:disabled { opacity: 0.6; cursor: not-allowed; box-shadow: none; }
.btn-save:not(:disabled):hover { opacity: 0.92; }

.btn-cancel {
  background: #f1f5f9;
  color: #475569;
  border: none;
  border-radius: 12px;
  padding: 14px 28px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
}
.btn-cancel:hover { background: #e2e8f0; }

/* ── Alerts ──────────────────────────────────────────────────────────────── */
.alert-error, .alert-success {
  border-radius: 10px;
  padding: 12px 16px;
  font-size: 14px;
  margin-bottom: 20px;
  text-align: center;
}
.alert-error {
  background: rgba(239, 68, 68, 0.08);
  border: 1px solid rgba(239, 68, 68, 0.3);
  color: #dc2626;
}
.alert-success {
  background: rgba(16, 185, 129, 0.08);
  border: 1px solid rgba(16, 185, 129, 0.3);
  color: #059669;
}

/* ── Delete Modal ────────────────────────────────────────────────────────── */
.profile-delete-modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.55);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
}

.profile-delete-modal-card {
  background: white;
  border-radius: 20px;
  padding: 40px 36px;
  max-width: 420px;
  width: 100%;
  text-align: center;
  box-shadow: 0 24px 64px rgba(0, 0, 0, 0.18);
}

.modal-icon-danger {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: #fff5f5;
  border: 2px solid #fecaca;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  color: #dc2626;
}

.profile-delete-modal-card h3 {
  font-size: 22px;
  font-weight: 800;
  color: #0f172a;
  margin: 0 0 12px;
}

.profile-delete-modal-card p {
  color: #64748b;
  font-size: 14px;
  line-height: 1.6;
  margin: 0 0 28px;
}

.modal-actions {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.btn-confirm-delete {
  background: #dc2626;
  color: white;
  border: none;
  border-radius: 12px;
  padding: 14px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}
.btn-confirm-delete:hover:not(:disabled) { background: #b91c1c; }
.btn-confirm-delete:disabled { opacity: 0.6; cursor: not-allowed; }

.btn-modal-cancel {
  background: #f1f5f9;
  color: #475569;
  border: none;
  border-radius: 12px;
  padding: 14px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
}
.btn-modal-cancel:hover { background: #e2e8f0; }

/* Responsive break for mobile screens */
@media (max-width: 868px) {
  .profile-header {
    min-height: 68px;
    padding: 0 18px;
  }

  .profile-return {
    padding: 9px 12px;
  }

  .profile-card {
    flex-direction: column;
    width: 90%;
    min-width: unset;
    padding: 32px 24px;
    gap: 32px;
  }

  .profile-left {
    width: 100%;
  }

  .profile-divider {
    width: 100%;
    height: 1px;
    background: linear-gradient(to right, transparent, #e2e8f0 20%, #e2e8f0 80%, transparent);
  }

  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>

