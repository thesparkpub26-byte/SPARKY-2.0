import { createRouter, createWebHistory } from 'vue-router';
import { STAFF_ROLES, dashboardFor, verifiedUser } from '../utils/session';
import { rememberPage, trackLastPage } from '../utils/returnTo';

// Reader views: the home page ships with the app, every other page is fetched the first time it is visited
import ReaderHome from '../views/reader/ReaderHome.vue';
const ArticleView = () => import('../views/reader/ArticleView.vue');
const CategoryView = () => import('../views/reader/CategoryView.vue');
const VideosView = () => import('../views/reader/VideosView.vue');
const GalleryView = () => import('../views/reader/GalleryView.vue');
const PublishedIssuesView = () => import('../views/reader/PublishedIssuesView.vue');
const PrivacyPolicyView = () => import('../views/reader/PrivacyPolicyView.vue');
const TermsView = () => import('../views/reader/TermsView.vue');
const UnsubscribeView = () => import('../views/reader/UnsubscribeView.vue');
const SearchView = () => import('../views/reader/SearchView.vue');
const SavedView = () => import('../views/reader/SavedView.vue');
const NotFoundView = () => import('../views/reader/NotFoundView.vue');

// Auth Views
const LoginView = () => import('../views/auth/LoginView.vue');
const SignUpView = () => import('../views/auth/SignUpView.vue');
const ForgotPasswordView = () => import('../views/auth/ForgotPasswordView.vue');
const ResetPasswordView = () => import('../views/auth/ResetPasswordView.vue');
const OtpView = () => import('../views/auth/OtpView.vue');

// Staff pages: a reader's browser never downloads the dashboards' code at all.
const AdminDashboard = () => import('../views/dashboards/AdminDashboard.vue');
const EditorInChiefDashboard = () => import('../views/dashboards/EditorInChiefDashboard.vue');
const SectionEditorDashboard = () => import('../views/dashboards/SectionEditorDashboard.vue');
const StaffWriterDashboard = () => import('../views/dashboards/StaffWriterDashboard.vue');
const StaffArtistDashboard = () => import('../views/dashboards/StaffArtistDashboard.vue');
const StaffBroadcasterDashboard = () => import('../views/dashboards/StaffBroadcasterDashboard.vue');
const MonitoringSheetView = () => import('../views/dashboards/MonitoringSheetView.vue');
const FileStorageView = () => import('../views/dashboards/FileStorageView.vue');
const ProfileView = () => import('../views/ProfileView.vue');
const BookletViewer = () => import('../views/BookletViewer.vue');

// A dashboard belongs to exactly one role: `roles` lists who may open it.
const dashboard = (path, name, component, roles) => ({ path, name, component, meta: { requiresStaff: true, roles } });

const routes = [
  // Reader Portal (accessible to everyone)
  { path: '/', name: 'ReaderHome', component: ReaderHome },
  { path: '/article/:id', name: 'ArticleView', component: ArticleView },
  { path: '/article', redirect: '/' },
  { path: '/categories', name: 'CategoryView', component: CategoryView },
  { path: '/videos', name: 'VideosView', component: VideosView },
  { path: '/gallery', name: 'GalleryView', component: GalleryView },
  { path: '/issues', name: 'PublishedIssuesView', component: PublishedIssuesView },
  { path: '/search', name: 'SearchView', component: SearchView },
  { path: '/saved', name: 'SavedView', component: SavedView, meta: { requiresAuth: true } },
  { path: '/privacy-policy', name: 'PrivacyPolicyView', component: PrivacyPolicyView },
  { path: '/terms', name: 'TermsView', component: TermsView },
  { path: '/unsubscribe/:token', name: 'UnsubscribeView', component: UnsubscribeView },

  // Authentication
  { path: '/login', name: 'LoginView', component: LoginView },
  { path: '/signup', name: 'SignUpView', component: SignUpView },
  { path: '/forgot-password', name: 'ForgotPasswordView', component: ForgotPasswordView },
  { path: '/reset-password', name: 'ResetPasswordView', component: ResetPasswordView },
  { path: '/otp', name: 'OtpView', component: OtpView },

  // Dashboards: one role each, checked against the server (see the guard below)
  dashboard('/admin', 'AdminDashboard', AdminDashboard, ['admin']),
  dashboard('/eic', 'EditorInChiefDashboard', EditorInChiefDashboard, ['eic']),
  dashboard('/editor', 'SectionEditorDashboard', SectionEditorDashboard, ['section_editor']),
  dashboard('/writer', 'StaffWriterDashboard', StaffWriterDashboard, ['staff_writer']),
  dashboard('/artist', 'StaffArtistDashboard', StaffArtistDashboard, ['staff_artist']),
  dashboard('/broadcaster', 'StaffBroadcasterDashboard', StaffBroadcasterDashboard, ['staff_broadcaster']),

  // Shared staff tools (any publication staff)
  { path: '/monitoring-sheet', name: 'MonitoringSheetView', component: MonitoringSheetView, meta: { requiresStaff: true } },
  { path: '/monitoring-sheet/:monitoringSheet', name: 'MonitoringSheetDetailView', component: MonitoringSheetView, meta: { requiresStaff: true } },
  { path: '/monitoring_sheet_fullscreen.html', redirect: '/monitoring-sheet' },
  { path: '/storage/:entryId', name: 'FileStorageView', component: FileStorageView, meta: { requiresStaff: true } },

  // Profile (any logged-in user)
  { path: '/profile', name: 'ProfileView', component: ProfileView, meta: { requiresAuth: true } },

  // Booklet viewer (public — readers open published issues from the Published Issues pages)
  { path: '/booklet/:id', name: 'BookletViewer', component: BookletViewer },

  // Anything else
  { path: '/:pathMatch(.*)*', name: 'NotFoundView', component: NotFoundView },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 };
  }
});

// ── Navigation Guard ──────────────────────────────────────────────────────────
// Protected pages ask the server who is signed in instead of believing the copy in localStorage, which
// anyone can edit. Someone who isn't allowed is sent somewhere they are allowed: a staff member who types
// another role's address lands on their own dashboard.
router.beforeEach(async (to) => {
  const isAuthPage = to.name === 'LoginView' || to.name === 'SignUpView';
  const isProtected = Boolean(to.meta.requiresAuth || to.meta.requiresStaff);
  if (!isProtected && !isAuthPage) return true;

  const user = await verifiedUser();

  if (isProtected && !user) {
    // They wanted this page: after signing in they should land on it
    rememberPage(to);
    return { name: 'LoginView' };
  }

  if (to.meta.requiresStaff && !STAFF_ROLES.includes(user.role)) {
    return { name: 'ReaderHome' };
  }

  if (to.meta.roles && !to.meta.roles.includes(user.role)) {
    return { path: dashboardFor(user.role) };
  }

  // Logged-in staff visiting /login or /signup → straight to their dashboard
  if (isAuthPage && user && STAFF_ROLES.includes(user.role)) {
    return { path: dashboardFor(user.role) };
  }

  return true;
});

trackLastPage(router);

// ── Page titles (the browser tab, history and bookmarks) ─────────────────────
// Article pages set their own title once the article has loaded.
const SITE = 'TheSPARK';
const TITLES = {
  ReaderHome: null, // just the site name
  CategoryView: 'Categories',
  VideosView: 'Videos',
  GalleryView: 'Gallery',
  PublishedIssuesView: 'Published Issues',
  PrivacyPolicyView: 'Privacy Policy',
  TermsView: 'Terms & Conditions',
  SearchView: 'Search',
  SavedView: 'Saved Articles',
  UnsubscribeView: 'Unsubscribe',
  LoginView: 'Sign In',
  SignUpView: 'Sign Up',
  ForgotPasswordView: 'Reset Password',
  ResetPasswordView: 'Reset Password',
  OtpView: 'Verify Your Email',
  ProfileView: 'My Profile',
  NotFoundView: 'Page not found',
};

router.afterEach((to) => {
  if (to.name === 'ArticleView') return;

  let title = TITLES[to.name];
  if (to.name === 'CategoryView' && to.query.category) title = String(to.query.category);
  if (title === undefined) title = 'Dashboard'; // staff pages

  document.title = title ? `${title} | ${SITE}` : 'The Spark - Official Publication';
});

export default router;
