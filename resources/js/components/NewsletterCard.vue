<template>
  <section class="newsletter-section">
    <div class="newsletter-card">
      <div class="newsletter-left">
        <h2 class="newsletter-title">Subscribe To Our Newsletter</h2>
        <p class="newsletter-desc">
          Be the first to know about the latest stories, special issues, and publication highlights. Stay connected with insights, features, and updates that matter to community.
        </p>
      </div>
      <div class="newsletter-right">
        <h4 class="newsletter-stay-updated">Stay up to date</h4>

        <!-- Signed out: leave an email, then create the account first (subscribing happens afterwards, from this card) -->
        <template v-if="!isLoggedIn">
          <form class="newsletter-form" @submit.prevent="goToSignUp">
            <input type="email" v-model="email" placeholder="Enter your email" class="newsletter-input" maxlength="255" required>
            <button type="submit" class="btn-subscribe">Subscribe</button>
          </form>
          <p class="newsletter-feedback newsletter-hint">We'll take you to sign up first. Once you're in, you can subscribe from this card.</p>
        </template>

        <!-- Signed in: one button that toggles the subscription, each way behind a confirmation -->
        <button v-else-if="status === 'loading'" type="button" class="btn-subscribe" disabled>Loading…</button>
        <button v-else-if="status === 'subscribed'" type="button" class="btn-subscribe btn-unsubscribe" @click="openConfirm">Unsubscribe</button>
        <button v-else type="button" class="btn-subscribe" @click="openConfirm">Subscribe</button>

        <p v-if="isLoggedIn && status === 'subscribed'" class="newsletter-feedback">You're subscribed{{ userEmail ? ` as ${userEmail}` : '' }}.</p>
        <p v-else-if="isLoggedIn && status === 'unsubscribed'" class="newsletter-feedback newsletter-hint">You're not subscribed yet.</p>
        <p v-if="notice" class="newsletter-feedback">{{ notice }}</p>
        <p v-if="loadError" class="newsletter-feedback error">{{ loadError }}</p>
      </div>
    </div>

    <Teleport to="body">
      <div v-if="confirming" class="newsletter-modal-overlay" @click.self="closeConfirm">
        <div class="newsletter-modal" role="dialog" aria-modal="true" aria-labelledby="newsletter-modal-title">
          <div class="newsletter-modal-icon" :class="{ danger: isSubscribed }" aria-hidden="true">
            <svg v-if="!isSubscribed" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.7 3A6 6 0 0 1 18 8a21.3 21.3 0 0 0 .6 5"/><path d="M17 17H3s3-2 3-9a4.67 4.67 0 0 1 .3-1.7"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/><path d="m2 2 20 20"/></svg>
          </div>
          <h3 id="newsletter-modal-title" class="newsletter-modal-title">
            {{ isSubscribed ? 'Unsubscribe from the newsletter?' : 'Subscribe to the newsletter?' }}
          </h3>
          <p class="newsletter-modal-text">
            <template v-if="isSubscribed">
              You'll stop receiving TheSPARK's newsletter{{ userEmail ? ` at ${userEmail}` : '' }}. You can subscribe again anytime.
            </template>
            <template v-else>
              We'll send the latest stories, special issues, and publication highlights{{ userEmail ? ` to ${userEmail}` : '' }}. You can unsubscribe anytime.
            </template>
          </p>
          <p v-if="actionError" class="newsletter-modal-error">{{ actionError }}</p>
          <div class="newsletter-modal-actions">
            <button type="button" class="newsletter-modal-cancel" :disabled="saving" @click="closeConfirm">Cancel</button>
            <button type="button" class="newsletter-modal-confirm" :class="{ danger: isSubscribed }" :disabled="saving" @click="confirm">
              {{ saving ? 'Please wait…' : (isSubscribed ? 'Yes, unsubscribe' : 'Yes, subscribe') }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const isLoggedIn = ref(!!localStorage.getItem('sparky_token'));
const status = ref(isLoggedIn.value ? 'loading' : 'unsubscribed'); // loading | subscribed | unsubscribed
const isSubscribed = computed(() => status.value === 'subscribed');

// The signed-in user's email from the saved profile, or an empty string.
const userEmail = (() => {
  try {
    return JSON.parse(localStorage.getItem('sparky_user') || '{}').email || '';
  } catch {
    return '';
  }
})();

const confirming = ref(false);
const saving = ref(false);
const actionError = ref('');
const loadError = ref('');
const notice = ref('');

// Sends a request to the newsletter API with the sign-in token.
const request = (path, method = 'GET') => fetch(`/api/newsletter/${path}`, {
  method,
  headers: { Accept: 'application/json', Authorization: `Bearer ${localStorage.getItem('sparky_token')}` },
});

onMounted(async () => {
  document.addEventListener('keydown', onKeydown);
  if (!isLoggedIn.value) return;

  try {
    const response = await request('me');
    if (response.status === 401) {
      // The saved sign-in has expired: treat the visitor as signed out
      isLoggedIn.value = false;
      status.value = 'unsubscribed';
      return;
    }
    if (!response.ok) throw new Error();
    const body = await response.json();
    status.value = body.subscribed ? 'subscribed' : 'unsubscribed';
  } catch {
    status.value = 'unsubscribed';
    loadError.value = "We couldn't check your subscription. Please refresh the page.";
  }
});

onBeforeUnmount(() => document.removeEventListener('keydown', onKeydown));

// Closes the confirmation dialog when Escape is pressed (not while saving).
const onKeydown = (event) => {
  if (event.key === 'Escape' && confirming.value && !saving.value) closeConfirm();
};

// Subscribing needs an account: a signed-out visitor is sent to sign up first, with their email filled in
const email = ref('');
// Sends a signed-out visitor to the sign-up page with the typed email filled in.
const goToSignUp = () => {
  const trimmed = email.value.trim();
  if (!trimmed) return;

  router.push({ path: '/signup', query: { email: trimmed } });
};

// Opens the subscribe / unsubscribe confirmation dialog.
const openConfirm = () => {
  actionError.value = '';
  notice.value = '';
  confirming.value = true;
};

// Closes the confirmation dialog (not while saving).
const closeConfirm = () => {
  if (saving.value) return;
  confirming.value = false;
};

// Subscribes or unsubscribes (depending on the current state) and updates the card.
const confirm = async () => {
  saving.value = true;
  actionError.value = '';

  try {
    const response = await request(isSubscribed.value ? 'me/unsubscribe' : 'me/subscribe', 'POST');
    const body = await response.json().catch(() => ({}));
    if (!response.ok) {
      actionError.value = body.message || 'Something went wrong. Please try again.';
      return;
    }
    status.value = body.subscribed ? 'subscribed' : 'unsubscribed';
    notice.value = body.message || '';
    confirming.value = false;
  } catch {
    actionError.value = 'Could not connect to the server. Please try again.';
  } finally {
    saving.value = false;
  }
};
</script>

<style scoped>
/* The form spans the full width of the block (as wide as the note below it): the input takes what the button leaves */
.newsletter-form {
  width: 100%;
}

@media (min-width: 641px) {
  .newsletter-form .newsletter-input {
    flex: 1 1 auto;
    width: auto;
    min-width: 0;
  }

  .newsletter-form .btn-subscribe {
    flex: 0 0 auto;
  }
}

.btn-unsubscribe {
  background-color: #ffffff;
  color: #dc2626;
  border: 1.5px solid #fecaca;
  box-shadow: none;
}

.btn-unsubscribe:hover {
  background-color: #fef2f2;
}

.newsletter-hint {
  color: #64748b;
  font-weight: 500;
}

.newsletter-modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 3000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background: rgba(15, 23, 42, 0.5);
}

.newsletter-modal {
  width: 100%;
  max-width: 400px;
  padding: 28px 26px 22px;
  border-radius: 22px;
  background: #ffffff;
  box-shadow: 0 24px 60px rgba(15, 23, 42, 0.3);
  text-align: center;
  font-family: inherit;
}

.newsletter-modal-icon {
  width: 56px;
  height: 56px;
  margin: 0 auto 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #dbeafe;
  color: #1d6bf3;
}

.newsletter-modal-icon.danger {
  background: #fee2e2;
  color: #dc2626;
}

.newsletter-modal-title {
  margin: 0 0 8px;
  font-size: 19px;
  font-weight: 800;
  color: #0f172a;
}

.newsletter-modal-text {
  margin: 0;
  font-size: 14px;
  line-height: 1.6;
  color: #64748b;
  overflow-wrap: anywhere;
}

.newsletter-modal-error {
  margin: 12px 0 0;
  font-size: 13px;
  font-weight: 600;
  color: #dc2626;
}

.newsletter-modal-actions {
  display: flex;
  gap: 10px;
  margin-top: 22px;
}

.newsletter-modal-actions button {
  flex: 1;
  padding: 12px 16px;
  border-radius: 999px;
  font-family: inherit;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: background-color 0.2s, transform 0.2s;
}

.newsletter-modal-actions button:disabled {
  opacity: 0.6;
  cursor: default;
}

.newsletter-modal-cancel {
  border: 1.5px solid #e2e8f0;
  background: #ffffff;
  color: #475569;
}

.newsletter-modal-cancel:hover:not(:disabled) {
  background: #f8fafc;
}

.newsletter-modal-confirm {
  border: none;
  background: #1d6bf3;
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(29, 107, 243, 0.25);
}

.newsletter-modal-confirm:hover:not(:disabled) {
  background: #1557c0;
}

.newsletter-modal-confirm.danger {
  background: #dc2626;
  box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
}

.newsletter-modal-confirm.danger:hover:not(:disabled) {
  background: #b91c1c;
}
</style>
