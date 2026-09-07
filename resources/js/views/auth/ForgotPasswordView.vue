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
        <h2>Reset Password</h2>
        <p class="subtitle">Please enter your registered email address to<br>receive a password recovery link.</p>
        
        <form @submit.prevent="handleForgot">
          <div class="input-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" v-model="email" placeholder="youremail@thesparkpub.com" required autofocus>
          </div>
          
          <button type="submit" class="submit-btn">Continue</button>
        </form>
        
        <p class="spam-notice">Can't find it? Check your spam or junk folder.</p>

        <div class="return-container">
          <router-link to="/login" class="return-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="return-icon"><path d="m15 18-6-6 6-6"/></svg>
            Return to Sign In
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const email = ref('');
const router = useRouter();

const handleForgot = () => {
  router.push('/otp');
};
</script>
