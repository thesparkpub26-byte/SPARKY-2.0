<template>
  <div class="container">
    <!-- Left Section -->
    <div class="left-section">
      <div class="logo-container">
        <img src="/assets/White_Spark_Logo.png" alt="The SPARK Logo" class="logo">
        <h1>TheSPARK</h1>
        <p>Truth knows no limits</p>
      </div>
    </div>

    <!-- Right Section -->
    <div class="right-section">
      <div class="form-container">
        <h2>Create a secure password</h2>
        <p class="subtitle">Changing password for <strong>{{ email }}</strong><br>Use at least 8 characters, with a mix of letters, numbers, and symbols.</p>

        <form @submit.prevent="handleReset">
          <div class="input-group password-group">
            <label for="password">New Password</label>
            <div class="password-wrapper">
              <input :type="showPassword ? 'text' : 'password'" id="password" v-model="password" placeholder="Enter password" minlength="8" required autofocus>
              <svg class="eye-icon" @click="showPassword = !showPassword" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path v-if="!showPassword" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle v-if="!showPassword" cx="12" cy="12" r="3"></circle>
                <g v-else>
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                  <line x1="1" y1="1" x2="23" y2="23"></line>
                </g>
              </svg>
            </div>
            <div class="password-wrapper confirm-wrapper">
              <input :type="showConfirmPassword ? 'text' : 'password'" id="confirm-password" v-model="confirmPassword" placeholder="Confirm password" required>
              <svg class="eye-icon" @click="showConfirmPassword = !showConfirmPassword" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path v-if="!showConfirmPassword" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle v-if="!showConfirmPassword" cx="12" cy="12" r="3"></circle>
                <g v-else>
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                  <line x1="1" y1="1" x2="23" y2="23"></line>
                </g>
              </svg>
            </div>
            <p v-if="isMismatch" style="color: #d93025; font-size: 13px; font-weight: 500; margin-top: 10px;">Passwords do not match.</p>
          </div>

          <p v-if="errorMsg" class="form-error">{{ errorMsg }}</p>

          <button type="submit" class="submit-btn" :disabled="isMismatch || loading">{{ loading ? 'Saving…' : 'Continue' }}</button>
        </form>

        <div class="return-container">
          <router-link to="/login" class="return-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="return-icon">
              <path d="m15 18-6-6 6-6" />
            </svg>
            Return to Sign In
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const password = ref('');
const confirmPassword = ref('');
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const loading = ref(false);
const errorMsg = ref('');
const router = useRouter();

// Saved by the two steps before this one (ForgotPasswordView, then the code step)
const email = sessionStorage.getItem('reset_email') || '';
const resetToken = sessionStorage.getItem('reset_token') || '';

// This page only makes sense right after entering a valid code
onMounted(() => {
  if (!email || !resetToken) router.replace('/forgot-password');
});

const isMismatch = computed(() => {
  return confirmPassword.value.length > 0 && password.value !== confirmPassword.value;
});

// Saves the new password using the reset token, then returns to sign-in.
const handleReset = async () => {
  if (isMismatch.value || loading.value) return;

  errorMsg.value = '';
  loading.value = true;

  try {
    const response = await fetch('/api/password/reset', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({
        email,
        reset_token: resetToken,
        password: password.value,
        password_confirmation: confirmPassword.value,
      }),
    });
    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
      errorMsg.value = data.errors?.password?.[0] || data.message || 'Could not update your password. Please try again.';
      return;
    }

    sessionStorage.removeItem('reset_email');
    sessionStorage.removeItem('reset_token');
    router.push({ path: '/login', query: { reset: '1' } });
  } catch {
    errorMsg.value = 'Could not connect to the server. Please try again.';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.form-error {
  color: #d93025;
  font-size: 13px;
  font-weight: 500;
  margin: 10px 0 0;
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
