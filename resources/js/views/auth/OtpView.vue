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
        <h2>{{ isReset ? 'Check Your Email' : 'Almost There' }}</h2>
        <p class="subtitle">
          {{ isReset ? "If that email has an account, we've sent a 6-digit code to" : "You're one step away. Enter the 6-digit code sent to" }}<br>
          <strong class="otp-email-highlight">{{ maskedEmail }}</strong>
        </p>
        
        <form @submit.prevent="handleVerify">
          <div class="otp-container">
            <input 
              v-for="(digit, index) in otp" 
              :key="index"
              ref="otpRef"
              type="text" 
              maxlength="1" 
              v-model="otp[index]"
              class="otp-input"
              :class="{ 'otp-error': errorMsg }"
              @input="onInput(index, $event)"
              @keydown="onKeyDown(index, $event)"
              @paste="onPaste($event)"
            >
          </div>

          <div v-if="errorMsg" class="error-msg">{{ errorMsg }}</div>
          <div v-if="successMsg" class="success-msg">{{ successMsg }}</div>
          
          <button type="submit" class="submit-btn" :disabled="loading || otp.join('').length < 6">
            {{ loading ? 'Verifying…' : 'Continue' }}
          </button>
        </form>
        
        <p class="signup-text">
          Didn't receive the code?
          <a href="#" @click.prevent="resendCode" :class="{ disabled: resendCooldown > 0 }">
            {{ resendCooldown > 0 ? `Resend in ${resendCooldown}s` : 'Request again' }}
          </a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { lastPage } from '../../utils/returnTo';

const otp = ref(['', '', '', '', '', '']);
const otpRef = ref([]);
const router = useRouter();
// /otp?mode=reset is the forgot-password flow; otherwise it confirms a new sign-up
const isReset = useRoute().query.mode === 'reset';
const errorMsg = ref('');
const successMsg = ref('');
const loading = ref(false);
const resendCooldown = ref(0);

// Retrieve the email saved by SignUpView (or by ForgotPasswordView)
const email = sessionStorage.getItem(isReset ? 'reset_email' : 'otp_email') || '';

// Mask the email for display: e.g. em***@my.cspc.edu.ph
const maskedEmail = computed(() => {
  if (!email) return 'your email';
  const [local, domain] = email.split('@');
  const visible = local.slice(0, 2);
  return `${visible}***@${domain}`;
});

// Redirect away if there's nothing pending
onMounted(() => {
  if (!email) router.replace(isReset ? '/forgot-password' : '/signup');
});

// Role → destination mapping (same as login)
const roleDashboard = {
  admin:             '/admin',
  eic:               '/eic',
  section_editor:    '/editor',
  staff_writer:      '/writer',
  staff_artist:      '/artist',
  staff_broadcaster: '/broadcaster',
  reader:            '/',   // Readers land on the public portal
};

const onInput = (index, event) => {
  // Only allow digits
  otp.value[index] = event.target.value.replace(/\D/g, '').slice(-1);
  event.target.value = otp.value[index];
  if (otp.value[index] && index < 5) {
    otpRef.value[index + 1].focus();
  }
};

const onKeyDown = (index, event) => {
  if (event.key === 'Backspace' && !otp.value[index] && index > 0) {
    otpRef.value[index - 1].focus();
  }
};

const onPaste = (event) => {
  event.preventDefault();
  const pasted = event.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6).split('');
  pasted.forEach((char, i) => { if (i < 6) otp.value[i] = char; });
  if (pasted.length > 0 && otpRef.value[Math.min(pasted.length, 5)]) {
    otpRef.value[Math.min(pasted.length, 5)].focus();
  }
};

const handleVerify = async () => {
  const code = otp.value.join('');
  if (code.length < 6) return;

  errorMsg.value = '';
  loading.value = true;

  try {
    if (isReset) {
      const response = await fetch('/api/password/verify', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ email, otp: code }),
      });
      const data = await response.json();

      if (!response.ok) {
        errorMsg.value = data.message || 'Invalid or expired code.';
        otp.value = ['', '', '', '', '', ''];
        otpRef.value[0]?.focus();
        return;
      }

      sessionStorage.setItem('reset_token', data.reset_token);
      router.push('/reset-password');
      return;
    }

    const response = await fetch('/api/register/verify-otp', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ email, otp: code }),
    });

    const data = await response.json();

    if (!response.ok) {
      errorMsg.value = data.message || 'Invalid or expired code.';
      // Clear inputs on wrong code
      otp.value = ['', '', '', '', '', ''];
      otpRef.value[0]?.focus();
      return;
    }

    // Save auth data
    localStorage.setItem('sparky_token', data.token);
    localStorage.setItem('sparky_user', JSON.stringify(data.user));
    sessionStorage.removeItem('otp_email');
    sessionStorage.removeItem('otp_name');

    // Back to the page they were looking at before signing up; their dashboard only if there wasn't one
    const destination = lastPage() || roleDashboard[data.user.role] || '/';
    router.push(destination);

  } catch (err) {
    errorMsg.value = 'Could not connect to the server. Please try again.';
  } finally {
    loading.value = false;
  }
};

let cooldownTimer = null;

const resendCode = async () => {
  if (resendCooldown.value > 0 || !email) return;

  errorMsg.value = '';
  successMsg.value = '';

  try {
    // A reset code is re-sent by asking for a new one
    const response = await fetch(isReset ? '/api/password/forgot' : '/api/register/resend-otp', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ email }),
    });

    const data = await response.json();

    if (!response.ok) {
      errorMsg.value = data.message || 'Could not resend code.';
      return;
    }

    successMsg.value = 'A new code has been sent to your email!';
    otp.value = ['', '', '', '', '', ''];
    otpRef.value[0]?.focus();

    // 60-second cooldown before they can resend again
    resendCooldown.value = 60;
    cooldownTimer = setInterval(() => {
      resendCooldown.value--;
      if (resendCooldown.value <= 0) {
        clearInterval(cooldownTimer);
        successMsg.value = '';
      }
    }, 1000);

  } catch (err) {
    errorMsg.value = 'Could not connect to the server.';
  }
};

onUnmounted(() => { if (cooldownTimer) clearInterval(cooldownTimer); });
</script>

<style scoped>
.otp-email-highlight {
  color: #1e293b;
  font-weight: 700;
  background: rgba(255,255,255,0.85);
  padding: 2px 10px;
  border-radius: 6px;
  font-size: 0.95rem;
}

.otp-input.otp-error {
  border-color: rgba(239, 68, 68, 0.7) !important;
  box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2) !important;
}

.error-msg {
  background: rgba(239, 68, 68, 0.12);
  border: 1px solid rgba(239, 68, 68, 0.4);
  color: #fca5a5;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 0.875rem;
  margin: 12px 0;
  text-align: center;
}

.success-msg {
  background: rgba(16, 185, 129, 0.12);
  border: 1px solid rgba(16, 185, 129, 0.4);
  color: #6ee7b7;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 0.875rem;
  margin: 12px 0;
  text-align: center;
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

a.disabled {
  opacity: 0.5;
  cursor: not-allowed;
  pointer-events: none;
}
</style>
