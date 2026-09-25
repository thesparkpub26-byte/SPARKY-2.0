<template>
  <Teleport to="body">
    <div class="pam-backdrop" @click.self="$emit('close')">
      <div class="pam-card" role="dialog" aria-modal="true" :aria-label="person.name">
        <button type="button" class="pam-close" aria-label="Close" @click="$emit('close')">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
            <line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
          </svg>
        </button>

        <header class="pam-header">
          <img v-if="person.avatar" :src="person.avatar" :alt="person.name" class="pam-avatar">
          <span v-else class="pam-avatar pam-initials">{{ initials }}</span>
          <h2 class="pam-name">{{ person.name }}</h2>
          <p v-if="person.role" class="pam-role">{{ person.role }}</p>
        </header>

        <div class="pam-body">
          <h3 class="pam-heading">
            {{ mode === 'contributor' ? 'Contributed to' : 'Articles by ' + firstName }}
            <span v-if="total" class="pam-count">{{ total }}</span>
          </h3>

          <div v-if="loading && !articles.length" class="pam-state">Loading…</div>
          <div v-else-if="failed && !articles.length" class="pam-state">Couldn't load the articles. Please try again.</div>

          <ul v-else class="pam-list">
            <li v-for="a in articles" :key="a.id">
              <button type="button" class="pam-item" @click="open(a)">
                <img v-if="a.image" :src="a.image" alt="" class="pam-thumb" loading="lazy">
                <span v-else class="pam-thumb pam-thumb-empty"></span>
                <span class="pam-item-text">
                  <span class="pam-item-title">{{ a.title }}</span>
                  <span class="pam-item-meta">
                    <span v-if="a.badge" class="pam-badge">{{ a.badge }}</span>
                    {{ a.date }}
                  </span>
                </span>
              </button>
            </li>
          </ul>

          <button v-if="hasMore" type="button" class="pam-more" :disabled="loading" @click="load">
            {{ loading ? 'Loading…' : 'Show more' }}
          </button>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';

// A byline's mini profile: photo, name and the published articles they wrote (mode "author")
// or contributed artwork / photos to (mode "contributor").
const props = defineProps({
  person: { type: Object, required: true },
  mode: { type: String, default: 'author' },
});
const emit = defineEmits(['close']);
const router = useRouter();

const articles = ref([]);
const page = ref(0);
const lastPage = ref(1);
const total = ref(0);
const loading = ref(false);
const failed = ref(false);

// Only after the first page has arrived (page is 0 until then)
const hasMore = computed(() => page.value > 0 && page.value < lastPage.value);
const firstName = computed(() => (props.person.name || '').split(' ')[0]);
const initials = computed(() => (props.person.name || '').split(' ').map((p) => p[0]).join('').toUpperCase().slice(0, 2) || '?');

const load = async () => {
  if (loading.value) return;
  loading.value = true;
  failed.value = false;
  try {
    const res = await fetch(`/api/reader/people/${props.person.id}?as=${props.mode}&page=${page.value + 1}`, { headers: { Accept: 'application/json' } });
    if (!res.ok) throw new Error('failed');
    const body = await res.json();
    articles.value.push(...body.data);
    page.value = body.current_page;
    lastPage.value = body.last_page;
    total.value = body.total;
  } catch {
    failed.value = true;
  } finally {
    loading.value = false;
  }
};

const open = (article) => {
  emit('close');
  router.push(`/article/${article.id}`);
};

const onKeydown = (event) => {
  if (event.key === 'Escape') emit('close');
};

onMounted(() => {
  document.addEventListener('keydown', onKeydown);
  load();
});
onBeforeUnmount(() => document.removeEventListener('keydown', onKeydown));
</script>

<style scoped>
.pam-backdrop {
  position: fixed;
  inset: 0;
  z-index: 1500;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  background: rgba(15, 23, 42, 0.55);
  backdrop-filter: blur(3px);
}

.pam-card {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 100%;
  max-width: 460px;
  max-height: min(88vh, 720px);
  max-height: min(88dvh, 720px);
  background: #ffffff;
  border-radius: 28px;
  box-shadow: 0 24px 64px rgba(15, 23, 42, 0.3);
  overflow: hidden;
}

.pam-close {
  position: absolute;
  top: 14px;
  right: 14px;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border: 0;
  border-radius: 50%;
  background: #f1f5f9;
  color: #334155;
  cursor: pointer;
}

.pam-close:hover { background: #e2e8f0; }

.pam-header {
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 32px 24px 18px;
  text-align: center;
  border-bottom: 1px solid #eef2f7;
}

.pam-avatar {
  width: 88px;
  height: 88px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #dbeafe;
}

.pam-initials {
  display: flex;
  align-items: center;
  justify-content: center;
  background: #eff6ff;
  color: #1d4ed8;
  font-size: 28px;
  font-weight: 800;
}

.pam-name {
  margin: 14px 0 2px;
  font-size: 20px;
  font-weight: 800;
  color: #0f172a;
}

.pam-role {
  margin: 0;
  font-size: 13px;
  font-weight: 600;
  color: #64748b;
}

.pam-body {
  flex: 1;
  min-height: 0;
  overflow-y: auto;
  overscroll-behavior: contain;
  padding: 18px 20px 24px;
}

.pam-heading {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 0 0 12px;
  font-size: 13px;
  font-weight: 800;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  color: #475569;
}

.pam-count {
  padding: 1px 8px;
  border-radius: 999px;
  background: #eff6ff;
  color: #1d4ed8;
  font-size: 11px;
}

.pam-state {
  padding: 24px 0;
  text-align: center;
  font-size: 14px;
  color: #94a3b8;
}

.pam-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin: 0;
  padding: 0;
  list-style: none;
}

.pam-item {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  padding: 8px;
  border: 1px solid #eef2f7;
  border-radius: 16px;
  background: #f8fafc;
  text-align: left;
  cursor: pointer;
  transition: background-color 0.15s ease, border-color 0.15s ease;
}

.pam-item:hover {
  background: #f1f5f9;
  border-color: #cbd5e1;
}

.pam-thumb {
  flex-shrink: 0;
  width: 64px;
  height: 64px;
  border-radius: 12px;
  object-fit: cover;
  background: #e2e8f0;
}

.pam-item-text {
  display: flex;
  flex-direction: column;
  gap: 4px;
  min-width: 0;
}

.pam-item-title {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  font-size: 14px;
  font-weight: 700;
  line-height: 1.3;
  color: #0f172a;
}

.pam-item-meta {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
  color: #64748b;
}

.pam-badge {
  padding: 1px 8px;
  border-radius: 999px;
  background: #dbeafe;
  color: #1d4ed8;
  font-size: 11px;
  font-weight: 700;
}

.pam-more {
  display: block;
  margin: 14px auto 0;
  padding: 10px 22px;
  border: 1.5px solid #1d6bf3;
  border-radius: 999px;
  background: #ffffff;
  color: #1d6bf3;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
}

.pam-more:disabled { opacity: 0.6; cursor: default; }

@media (max-width: 480px) {
  .pam-backdrop {
    align-items: flex-end;
    padding: 0;
  }

  .pam-card {
    max-width: none;
    max-height: 86vh;
    max-height: 86dvh;
    border-radius: 24px 24px 0 0;
  }
}
</style>
