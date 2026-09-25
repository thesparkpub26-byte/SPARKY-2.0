<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <div v-if="loading" class="skeleton-article" role="status" aria-label="Loading article">
        <span class="skeleton skeleton-line skeleton-line--title"></span>
        <span class="skeleton skeleton-line skeleton-line--medium"></span>
        <div class="skeleton skeleton-image"></div>
        <span v-for="n in 6" :key="n" class="skeleton skeleton-line" :class="n % 3 === 0 ? 'skeleton-line--medium' : 'skeleton-line--wide'"></span>
      </div>
      <div v-else-if="notFound" class="article-not-found">
        <h1 class="section-headline">Article not found</h1>
        <p class="section-subtext">It may have been removed or isn't published yet.</p>
        <router-link to="/" class="btn-load-more">Back to home</router-link>
      </div>

      <template v-else>
        <!-- 2 Column Article & Sidebar Grid -->
        <div class="article-main-grid" style="margin-top: 24px;">
          <!-- Left Article Content -->
          <article class="article-content-wrapper">
            <!-- Title -->
            <h1 class="article-title">{{ article.title }}</h1>

            <!-- Category & Date Header -->
            <div class="article-meta-header">
              <span v-if="article.category" class="badge-category">{{ article.category }}</span>
              <span class="article-date-text">{{ formatLongDate(article.published_at) }}</span>
            </div>

            <!-- Featured Image (the first media upload) -->
            <img v-if="body.cover" :src="body.cover" :alt="article.title" :class="['article-main-cover', { 'photo-uncropped': isLiterary }]">

            <!-- Author Info Row -->
            <div class="article-author-card">
              <!-- Opens the author's profile card (their photo, name and articles) -->
              <component
                :is="article.author ? 'button' : 'div'"
                class="author-left-info"
                :class="{ 'is-clickable': article.author }"
                :type="article.author ? 'button' : undefined"
                :title="article.author ? 'View articles by ' + article.author.name : undefined"
                @click="article.author && openPerson(article.author, 'author')"
              >
                <img v-if="article.author?.avatar" :src="article.author.avatar" :alt="article.author.name" class="author-avatar-img">
                <svg v-else width="44" height="44" viewBox="0 0 24 24" fill="#cbd5e1" class="author-avatar-img">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/>
                </svg>
                <div class="author-details-text">
                  <h4>{{ article.author?.name || 'TheSPARK' }}</h4>
                  <p>{{ article.author?.role || 'Staff Writer' }}</p>
                </div>
              </component>

              <!-- View Contributors dropdown: the PJ / artist assigned to the article -->
              <div class="contributors-wrapper" ref="contributorsRoot">
                <button class="btn-view-contributors" type="button" :aria-expanded="contributorsOpen" @click="contributorsOpen = !contributorsOpen">
                  View Contributors
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :style="{ transform: contributorsOpen ? 'rotate(180deg)' : '', transition: 'transform 0.2s' }">
                    <path d="m6 9 6 6 6-6"/>
                  </svg>
                </button>
                <div v-if="contributorsOpen" class="contributors-popover">
                  <p class="contributors-popover-title">Photojournalist / Artist</p>
                  <p v-if="!article.contributors.length" class="contributors-empty">No contributors credited yet.</p>
                  <button v-for="person in article.contributors" :key="person.id" type="button" class="contributor-row" :title="'View what ' + person.name + ' contributed to'" @click="openPerson(person, 'contributor')">
                    <img v-if="person.avatar" :src="person.avatar" :alt="person.name" class="contributor-avatar">
                    <span v-else class="contributor-avatar contributor-initials">{{ initialsOf(person.name) }}</span>
                    <div class="contributor-text">
                      <span class="contributor-name">{{ person.name }}</span>
                      <span class="contributor-role">{{ person.role }}</span>
                    </div>
                  </button>
                </div>
              </div>
            </div>

            <!-- Article Body: paragraphs with the other media uploads placed among them -->
            <div class="article-body-content">
              <template v-for="(block, idx) in body.blocks" :key="idx">
                <img v-if="block.type === 'image'" :src="block.src" :alt="article.title" :class="['article-inline-img', { 'photo-uncropped': isLiterary }]">
                <component :is="block.tag" v-else v-html="block.html" />
              </template>
            </div>

            <!-- Engagement Pill Bar -->
            <div class="article-engagement-bar">
              <div class="engagement-stats">
                <span class="stat-item">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                  {{ reads }} {{ reads === 1 ? 'Read' : 'Reads' }}
                </span>
                <button type="button" class="stat-item stat-button" :class="{ active: commentsOpen }" @click="commentsOpen = !commentsOpen">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                  </svg>
                  {{ commentsCount }} {{ commentsCount === 1 ? 'Comment' : 'Comments' }}
                </button>
                <button type="button" class="stat-item stat-button" :class="{ active: liked }" :title="liked ? 'Remove your like' : 'Like this article'" :aria-pressed="liked" @click="toggleLike">
                  <svg width="16" height="16" viewBox="0 0 24 24" :fill="liked ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                  </svg>
                  {{ likesCount }} {{ likesCount === 1 ? 'Like' : 'Likes' }}
                </button>
                <button type="button" class="stat-item stat-button" :class="{ active: bookmarked }" :title="bookmarked ? 'Remove from saved articles' : 'Save for later'" :aria-pressed="bookmarked" @click="toggleBookmark">
                  <svg width="16" height="16" viewBox="0 0 24 24" :fill="bookmarked ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2">
                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                  </svg>
                  {{ bookmarked ? 'Saved' : 'Save' }}
                </button>
                <button type="button" class="stat-item stat-button" title="Copy the link to this article" @click="shareArticle">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                  </svg>
                  {{ shares }} {{ shares === 1 ? 'Share' : 'Shares' }}
                </button>
                <span aria-live="polite"><span v-if="copied" class="copy-toast">Link copied!</span></span>
              </div>

              <form v-if="isLoggedIn" class="comment-input-form" @submit.prevent="submitComment">
                <input type="text" v-model="commentText" placeholder="Write a comment..." class="comment-input-field" maxlength="1000" required>
                <button type="submit" class="btn-send-comment" title="Send Comment" :disabled="posting">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="22" y1="2" x2="11" y2="13"/>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                  </svg>
                </button>
              </form>
              <div v-else class="comment-input-form comment-login-note">
                <router-link to="/login">Log in</router-link> to write a comment.
              </div>
            </div>
            <p v-if="commentError" class="comment-error">{{ commentError }}</p>

            <!-- Comments list (opens when the comment count is clicked) -->
            <div v-if="commentsOpen" class="article-comments-panel">
              <h3 class="comments-heading">Comments ({{ commentsCount }})</h3>
              <p v-if="commentsLoading" class="comments-empty">Loading comments…</p>
              <p v-else-if="!comments.length" class="comments-empty">No comments yet. Be the first to comment!</p>
              <div v-for="c in comments" :key="c.id" class="comment-item">
                <img v-if="c.user?.avatar" :src="c.user.avatar" :alt="c.user.name" class="contributor-avatar">
                <span v-else class="contributor-avatar contributor-initials">{{ initialsOf(c.user?.name) }}</span>
                <div class="comment-main">
                  <div class="comment-head">
                    <span class="comment-author">{{ c.user?.name || 'Reader' }}</span>
                    <span class="comment-date">{{ formatDateTime(c.created_at) }}<template v-if="c.edited"> &middot; edited</template></span>
                  </div>

                  <form v-if="editingId === c.id" class="comment-edit-form" @submit.prevent="saveEdit(c)">
                    <input v-model="editText" class="comment-input-field" maxlength="1000" required>
                    <button type="submit" class="comment-save-btn" :disabled="savingEdit">{{ savingEdit ? 'Saving…' : 'Save' }}</button>
                    <button type="button" class="comment-delete-cancel" @click="editingId = null">Cancel</button>
                  </form>
                  <p v-else class="comment-body">{{ c.body }}</p>

                  <!-- Anyone signed in can flag someone else's comment for the editors -->
                  <div v-if="isLoggedIn && !isOwn(c) && editingId !== c.id" class="comment-moderation">
                    <span v-if="reportedIds.includes(c.id)" class="comment-delete-ask">Reported. Thank you.</span>
                    <template v-else-if="reportingId === c.id">
                      <select v-model="reportReason" class="comment-report-select" aria-label="Reason">
                        <option value="spam">Spam</option>
                        <option value="abusive">Abusive or hateful</option>
                        <option value="misleading">Misleading</option>
                        <option value="other">Something else</option>
                      </select>
                      <button type="button" class="comment-delete-confirm" :disabled="reporting" @click="reportComment(c)">{{ reporting ? 'Sending…' : 'Report' }}</button>
                      <button type="button" class="comment-delete-cancel" @click="reportingId = null">Cancel</button>
                    </template>
                    <button v-else type="button" class="comment-delete-btn" @click="reportingId = c.id; reportReason = 'spam'">
                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                      Report
                    </button>
                  </div>

                  <!-- The author can edit / delete their own comment; the EIC (or an admin) can delete any -->
                  <div v-if="editingId !== c.id && (isOwn(c) || canModerate)" class="comment-moderation">
                    <template v-if="deletingId === c.id">
                      <span class="comment-delete-ask">Delete this comment?</span>
                      <button type="button" class="comment-delete-confirm" :disabled="deleting" @click="deleteComment(c)">{{ deleting ? 'Deleting…' : 'Delete' }}</button>
                      <button type="button" class="comment-delete-cancel" @click="deletingId = null">Cancel</button>
                    </template>
                    <template v-else>
                      <button v-if="isOwn(c)" type="button" class="comment-edit-btn" @click="startEdit(c)">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        Edit
                      </button>
                      <button type="button" class="comment-delete-btn" @click="deletingId = c.id">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        Delete
                      </button>
                    </template>
                  </div>
                </div>
              </div>

              <button v-if="commentsHasMore && !commentsLoading" type="button" class="comments-load-more" :disabled="commentsLoadingMore" @click="loadComments(true)">
                {{ commentsLoadingMore ? 'Loading…' : 'Load more comments' }}
              </button>
            </div>
          </article>

          <!-- Right Sidebar: Popular Now (same as the Categories page) -->
          <PopularSidebar />
        </div>

        <!-- Related Posts Section: other articles in the same category -->
        <section v-if="article.related.length" class="popular-section" style="margin-top: 56px;">
          <h2 class="section-headline">Related posts</h2>
          <p class="section-subtext">More from {{ article.category || 'the CSPC community' }}</p>

          <div class="articles-grid">
            <ArticleCard v-for="art in article.related" :key="art.id" :article="art" />
          </div>
        </section>
      </template>

      <!-- Subscribe To Our Newsletter Section -->
      <NewsletterCard />

      <!-- Footer Section -->
      <Footer />
    </main>

    <PersonArticlesModal
      v-if="personModal"
      :person="personModal.person"
      :mode="personModal.mode"
      @close="personModal = null"
    />
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Navbar from '../../components/Navbar.vue';
import SkeletonCards from '../../components/SkeletonCards.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import ArticleCard from '../../components/ArticleCard.vue';
import PopularSidebar from '../../components/PopularSidebar.vue';
import PersonArticlesModal from '../../components/PersonArticlesModal.vue';
import { buildArticleBody } from '../../utils/articleContent';

const route = useRoute();
const router = useRouter();
const jsonHeaders = { Accept: 'application/json' };

const article = ref(null);
const loading = ref(true);
const notFound = ref(false);
const reads = ref(0);
const shares = ref(0);
const likesCount = ref(0);
const liked = ref(false);
const bookmarked = ref(false);
const commentsCount = ref(0);

// ── Article ──────────────────────────────────────────────────────────────────
// Literary pieces (a Literary article, or one by a Literary writer) show their artwork whole, not cropped to a banner
const isLiterary = computed(() => /literary/i.test(`${article.value?.category || ''} ${article.value?.author?.role || ''}`));

const body = computed(() => article.value ? buildArticleBody(article.value.content, article.value.media) : { cover: null, blocks: [] });

const formatLongDate = (iso) => iso
  ? new Date(iso).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
  : '';

const formatDateTime = (iso) => iso
  ? new Date(iso).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' })
  : '';

const initialsOf = (name = '') => name.split(' ').map((p) => p[0]).join('').toUpperCase().slice(0, 2) || '?';

const loadArticle = async () => {
  loading.value = true;
  notFound.value = false;
  commentsOpen.value = false;
  contributorsOpen.value = false;
  personModal.value = null;
  comments.value = [];
  commentsHasMore.value = false;
  commentError.value = '';
  try {
    // The token (when there is one) lets the server say whether this reader already liked / saved it
    const res = await fetch(`/api/reader/articles/${route.params.id}`, { headers: authJson() });
    if (!res.ok) throw new Error('not found');
    article.value = await res.json();
    reads.value = article.value.reads;
    shares.value = article.value.shares;
    likesCount.value = article.value.likes_count || 0;
    liked.value = Boolean(article.value.liked);
    bookmarked.value = Boolean(article.value.bookmarked);
    commentsCount.value = article.value.comments_count;
    document.querySelector('.reader-page')?.scrollTo({ top: 0 });
    document.title = `${article.value.title} | TheSPARK`;
    recordOpen();
  } catch {
    article.value = null;
    notFound.value = true;
    document.title = 'Article not found | TheSPARK';
  } finally {
    loading.value = false;
  }
};

// Every time the page is opened counts as one read
const recordOpen = async () => {
  const id = article.value.id;
  try {
    const res = await fetch(`/api/reader/articles/${id}/read`, { method: 'POST', headers: jsonHeaders });
    if (res.ok && article.value?.id === id) reads.value = (await res.json()).reads;
  } catch {
    // The count just isn't bumped if the request fails.
  }
  fetch('/api/analytics/page-view', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', ...jsonHeaders },
    body: JSON.stringify({
      page_path: window.location.pathname,
      page_title: article.value.title,
      article_id: id,
    }),
  }).catch(() => {
    // Analytics must never block article reading.
  });
};

// ── Contributors dropdown ────────────────────────────────────────────────────
const contributorsOpen = ref(false);
// The author / contributor whose profile card is open
const personModal = ref(null);
const openPerson = (person, mode) => {
  contributorsOpen.value = false;
  personModal.value = { person, mode };
};
const contributorsRoot = ref(null);

const closeContributors = (event) => {
  if (contributorsOpen.value && contributorsRoot.value && !contributorsRoot.value.contains(event.target)) {
    contributorsOpen.value = false;
  }
};

// ── Shares ───────────────────────────────────────────────────────────────────
const copied = ref(false);
let copiedTimer = null;
let sharing = false;

// Resolves to true only if the browser really put the text on the clipboard
const copyToClipboard = async (text) => {
  try {
    await navigator.clipboard.writeText(text);
    return true;
  } catch {
    // Older browsers, or a page that isn't https: try the old way
    const field = document.createElement('textarea');
    field.value = text;
    field.style.position = 'fixed';
    field.style.opacity = '0';
    document.body.appendChild(field);
    field.select();
    let ok = false;
    try {
      ok = document.execCommand('copy');
    } catch {
      ok = false;
    }
    field.remove();
    return ok;
  }
};

const shareArticle = async () => {
  if (sharing) return;
  sharing = true;
  try {
    // The address people paste into Messenger or Facebook: the server puts the title and picture in it
    const link = `${window.location.origin}/article/${article.value.id}`;

    if (!(await copyToClipboard(link))) {
      // Nothing could be copied automatically, so let the reader copy it themselves
      window.prompt('Copy this link:', link);
      return;
    }

    copied.value = true;
    clearTimeout(copiedTimer);
    copiedTimer = setTimeout(() => { copied.value = false; }, 2000);

    try {
      const res = await fetch(`/api/reader/articles/${article.value.id}/share`, { method: 'POST', headers: jsonHeaders });
      if (res.ok) shares.value = (await res.json()).shares;
    } catch {
      // The link is still copied even if the count can't be updated.
    }
  } finally {
    sharing = false;
  }
};

// ── Comments ─────────────────────────────────────────────────────────────────
const comments = ref([]);
const commentsOpen = ref(false);
const commentsLoading = ref(false);
const commentsLoadingMore = ref(false);
const commentsHasMore = ref(false);
const commentText = ref('');
const commentError = ref('');
const posting = ref(false);

const token = () => localStorage.getItem('sparky_token');
const isLoggedIn = computed(() => Boolean(token() && localStorage.getItem('sparky_user')));
const authJson = () => (token() ? { ...jsonHeaders, Authorization: `Bearer ${token()}` } : jsonHeaders);

// ── Likes and saved articles (signed-in readers) ─────────────────────────────
const applyEngagement = (body) => {
  likesCount.value = body.likes_count;
  liked.value = body.liked;
  bookmarked.value = body.bookmarked;
};

const toggleEngagement = async (path, isOn) => {
  if (!isLoggedIn.value) { router.push('/login'); return; }

  try {
    const res = await fetch(`/api/reader/articles/${article.value.id}/${path}`, { method: isOn ? 'DELETE' : 'POST', headers: authJson() });
    if (res.ok) applyEngagement(await res.json());
    else commentError.value = 'Could not update that. Please try again.';
  } catch {
    commentError.value = 'Could not connect to the server. Please try again.';
  }
};
const toggleLike = () => toggleEngagement('like', liked.value);
const toggleBookmark = () => toggleEngagement('bookmark', bookmarked.value);

// ── Reporting a comment ──────────────────────────────────────────────────────
const reportingId = ref(null);
const reportReason = ref('spam');
const reporting = ref(false);
const reportedIds = ref([]);

const reportComment = async (comment) => {
  reporting.value = true;
  try {
    const res = await fetch(`/api/reader/comments/${comment.id}/report`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', ...authJson() },
      body: JSON.stringify({ reason: reportReason.value }),
    });
    if (res.ok) {
      reportedIds.value.push(comment.id);
      reportingId.value = null;
    } else {
      const body = await res.json().catch(() => ({}));
      commentError.value = body.message || 'Could not send your report. Please try again.';
    }
  } catch {
    commentError.value = 'Could not connect to the server. Please try again.';
  } finally {
    reporting.value = false;
  }
};

// The Editor-in-Chief (and admins) can delete any comment while browsing the reader site
const canModerate = computed(() => {
  try {
    return ['eic', 'admin'].includes(JSON.parse(localStorage.getItem('sparky_user') || '{}').role) && Boolean(token());
  } catch {
    return false;
  }
});
const deletingId = ref(null);
const deleting = ref(false);

// A signed-in reader can edit and delete their own comments
const currentUserId = computed(() => {
  try {
    return JSON.parse(localStorage.getItem('sparky_user') || '{}').id ?? null;
  } catch {
    return null;
  }
});
const isOwn = (comment) => Boolean(token()) && currentUserId.value !== null && comment.user?.id === currentUserId.value;

const editingId = ref(null);
const editText = ref('');
const savingEdit = ref(false);

const startEdit = (comment) => {
  deletingId.value = null;
  editingId.value = comment.id;
  editText.value = comment.body;
  commentError.value = '';
};

const saveEdit = async (comment) => {
  const text = editText.value.trim();
  if (!text || savingEdit.value) return;
  savingEdit.value = true;
  commentError.value = '';
  try {
    const res = await fetch(`/api/reader/comments/${comment.id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${token()}`, ...jsonHeaders },
      body: JSON.stringify({ body: text }),
    });
    const data = await res.json().catch(() => ({}));
    if (res.status === 401) throw new Error('Your session has expired. Please log in again.');
    if (!res.ok) throw new Error((data.errors ? Object.values(data.errors)[0]?.[0] : data.message) || 'Could not save your changes.');
    const idx = comments.value.findIndex((c) => c.id === comment.id);
    if (idx !== -1) comments.value[idx] = data.comment;
    editingId.value = null;
  } catch (e) {
    commentError.value = e.message;
  } finally {
    savingEdit.value = false;
  }
};

const deleteComment = async (comment) => {
  deleting.value = true;
  commentError.value = '';
  try {
    const res = await fetch(`/api/reader/comments/${comment.id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${token()}`, ...jsonHeaders },
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(data.message || 'Could not delete the comment.');
    comments.value = comments.value.filter((c) => c.id !== comment.id);
    commentsCount.value = data.comments_count;
    deletingId.value = null;
  } catch (e) {
    commentError.value = e.message;
    deletingId.value = null;
  } finally {
    deleting.value = false;
  }
};

// Three comments at a time; "Load more" continues after the ones already shown
const loadComments = async (more = false) => {
  if (more) commentsLoadingMore.value = true;
  else commentsLoading.value = true;
  try {
    const offset = more ? comments.value.length : 0;
    const res = await fetch(`/api/reader/articles/${article.value.id}/comments?offset=${offset}`, { headers: jsonHeaders });
    if (res.ok) {
      const page = await res.json();
      const known = new Set(comments.value.map((c) => c.id));
      comments.value = more ? [...comments.value, ...page.data.filter((c) => !known.has(c.id))] : page.data;
      commentsHasMore.value = page.has_more;
    }
  } finally {
    commentsLoading.value = false;
    commentsLoadingMore.value = false;
  }
};

watch(commentsOpen, (open) => {
  if (open && article.value) loadComments();
});

const submitComment = async () => {
  const text = commentText.value.trim();
  if (!text || posting.value) return;
  posting.value = true;
  commentError.value = '';
  try {
    const res = await fetch(`/api/reader/articles/${article.value.id}/comments`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${token()}`, ...jsonHeaders },
      body: JSON.stringify({ body: text }),
    });
    const data = await res.json().catch(() => ({}));
    if (res.status === 401) throw new Error('Your session has expired. Please log in again to comment.');
    if (!res.ok) throw new Error((data.errors ? Object.values(data.errors)[0]?.[0] : data.message) || 'Could not post your comment.');
    commentText.value = '';
    commentsCount.value = data.comments_count;
    comments.value.unshift(data.comment);
    commentsOpen.value = true;
  } catch (e) {
    commentError.value = e.message;
  } finally {
    posting.value = false;
  }
};

// Navigating between articles (related posts, sidebar) reuses this page, so reload on id change
watch(() => route.params.id, (id, oldId) => {
  if (id && id !== oldId) loadArticle();
});

onMounted(() => {
  loadArticle();
  document.addEventListener('click', closeContributors);
});

onUnmounted(() => {
  document.removeEventListener('click', closeContributors);
  clearTimeout(copiedTimer);
});
</script>
