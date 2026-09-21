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
        <h2>Create Your Account</h2>
        <p class="subtitle">Join the community as a reader or contributor and<br>start exploring stories.</p>
        
        <form @submit.prevent="handleSignUp">
          <div class="input-group">
            <label for="name">Name</label>
            <input type="text" id="name" v-model="name" placeholder="Full Name" required>
          </div>
          
          <div class="input-group">
            <label for="email">Your Email</label>
            <input type="email" id="email" v-model="email" placeholder="youremail@thesparkpub.com" required>
          </div>
          
          <div class="input-group password-group">
            <label for="password">Create Password</label>
            <div class="password-wrapper">
              <input :type="showPassword ? 'text' : 'password'" id="password" v-model="password" placeholder="Password" required>
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
              <input :type="showConfirmPassword ? 'text' : 'password'" id="confirm-password" v-model="confirmPassword" placeholder="Confirm Password" required>
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
          
          <div v-if="errorMsg" class="error-msg">{{ errorMsg }}</div>

        <button type="submit" class="submit-btn" :disabled="isMismatch || loading">
            {{ loading ? 'Sending code…' : 'Sign Up' }}
          </button>
        </form>
        
        <p class="signup-text">Already have an account? <router-link to="/login">Sign In</router-link></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';

const name = ref('');
const email = ref('');
const password = ref('');
const confirmPassword = ref('');
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const errorMsg = ref('');
const loading = ref(false);
const router = useRouter();

const isMismatch = computed(() => {
  return confirmPassword.value.length > 0 && password.value !== confirmPassword.value;
});

const handleSignUp = async () => {
  if (isMismatch.value) return;

  errorMsg.value = '';
  loading.value = true;

  try {
    const response = await fetch('/api/register/send-otp', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: confirmPassword.value,
      }),
    });

    const data = await response.json();

    if (!response.ok) {
      // Show first validation error found
      const firstError = data.errors
        ? Object.values(data.errors)[0][0]
        : data.message;
      errorMsg.value = firstError || 'Something went wrong. Please try again.';
      return;
    }

    // Save email so OtpView can use it for verification
    sessionStorage.setItem('otp_email', email.value);
    sessionStorage.setItem('otp_name', name.value);

    router.push('/otp');

  } catch (err) {
    errorMsg.value = 'Could not connect to the server. Please try again.';
  } finally {
    loading.value = false;
  }
};

</script>

<style scoped>
.error-msg {
  background: rgba(239, 68, 68, 0.12);
  border: 1px solid rgba(239, 68, 68, 0.4);
  color: #fca5a5;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 0.875rem;
  margin-bottom: 12px;
  text-align: center;
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
