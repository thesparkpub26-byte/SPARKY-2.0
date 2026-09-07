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
        <h2>Almost There</h2>
        <p class="subtitle">You're one step away. Enter the 6-digit code sent to<br>your email to get started.</p>
        
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
              @input="onInput(index, $event)"
              @keydown="onKeyDown(index, $event)"
              @paste="onPaste($event)"
            >
          </div>
          
          <button type="submit" class="submit-btn">Continue</button>
        </form>
        
        <p class="signup-text">Didn't receive the code? <a href="#" @click.prevent="resendCode">Request again</a></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const otp = ref(['', '', '', '', '', '']);
const otpRef = ref([]);
const router = useRouter();

const onInput = (index, event) => {
  if (event.target.value && index < 5) {
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
  const pasted = event.clipboardData.getData('text').slice(0, 6).split('');
  pasted.forEach((char, i) => {
    if (i < 6) otp.value[i] = char;
  });
  if (pasted.length > 0 && otpRef.value[Math.min(pasted.length, 5)]) {
    otpRef.value[Math.min(pasted.length, 5)].focus();
  }
};

const handleVerify = () => {
  router.push('/reset-password');
};

const resendCode = () => {
  alert('A new 6-digit code has been sent to your email.');
};
</script>
