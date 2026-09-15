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
      <button class="back-btn" @click="$router.push('/')">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="back-icon"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5v0a5.5 5.5 0 0 1-5.5 5.5H11"/></svg>
        Back
      </button>
      <div class="form-container">
        <h2>Welcome Back!</h2>
        <p class="subtitle">Access your account and continue your reading or<br>publishing journey.</p>
        
        <button class="google-btn" @click.prevent>
          <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google Logo" class="google-icon">
          Continue with <strong>Google</strong>
        </button>
        
        <div class="divider">
          <span>or</span>
        </div>
        
        <form @submit.prevent="handleLogin">
          <div class="input-group">
            <label for="email">Your Email</label>
            <input type="email" id="email" v-model="email" placeholder="youremail@thesparkpub.com" required>
          </div>
          
          <div class="input-group">
            <label for="password">Password</label>
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
          </div>
          
          <div class="forgot-password">
            <router-link to="/forgot-password">Forgot Password?</router-link>
          </div>
          
          <div v-if="errorMsg" class="error-msg">{{ errorMsg }}</div>

          <button type="submit" class="submit-btn" :disabled="loading">
            {{ loading ? 'Signing in…' : 'Sign In' }}
          </button>
        </form>
        
        <p class="signup-text">Don't have an account yet? <router-link to="/signup">Sign Up</router-link></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const showPassword = ref(false);
const errorMsg = ref('');
const loading = ref(false);
const router = useRouter();

// Role → destination mapping
const roleDashboard = {
  admin:          '/admin',
  eic:            '/eic',
  section_editor: '/editor',
  staff_writer:   '/writer',
  staff_artist:   '/artist',
  reader:         '/',   // Readers go to the public reader portal
};

const handleLogin = async () => {
  errorMsg.value = '';
  loading.value = true;

  try {
    const response = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ email: email.value, password: password.value }),
    });

    const data = await response.json();

    if (!response.ok) {
      // Show validation error from API
      errorMsg.value = data.message || (data.errors?.email?.[0]) || 'Invalid credentials.';
      return;
    }

    // Persist auth data
    localStorage.setItem('sparky_token', data.token);
    localStorage.setItem('sparky_user', JSON.stringify(data.user));

    // Redirect to role-appropriate dashboard
    const destination = roleDashboard[data.user.role] || '/';
    router.push(destination);

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
