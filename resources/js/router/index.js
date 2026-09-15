import { createRouter, createWebHistory } from 'vue-router';

// Reader Views
import ReaderHome from '../views/reader/ReaderHome.vue';
import ArticleView from '../views/reader/ArticleView.vue';
import CategoryView from '../views/reader/CategoryView.vue';
import GalleryView from '../views/reader/GalleryView.vue';
import PublishedIssuesView from '../views/reader/PublishedIssuesView.vue';

// Auth Views
import LoginView from '../views/auth/LoginView.vue';
import SignUpView from '../views/auth/SignUpView.vue';
import ForgotPasswordView from '../views/auth/ForgotPasswordView.vue';
import ResetPasswordView from '../views/auth/ResetPasswordView.vue';
import OtpView from '../views/auth/OtpView.vue';

// Dashboard Views
import AdminDashboard from '../views/dashboards/AdminDashboard.vue';
import EditorInChiefDashboard from '../views/dashboards/EditorInChiefDashboard.vue';
import SectionEditorDashboard from '../views/dashboards/SectionEditorDashboard.vue';
import StaffWriterDashboard from '../views/dashboards/StaffWriterDashboard.vue';
import StaffArtistDashboard from '../views/dashboards/StaffArtistDashboard.vue';
import MonitoringSheetView from '../views/dashboards/MonitoringSheetView.vue';
import ProfileView from '../views/ProfileView.vue';

// Staff roles that can access dashboards
const STAFF_ROLES = ['admin', 'eic', 'section_editor', 'staff_writer', 'staff_artist'];

const routes = [
  // Reader Portal (accessible to everyone)
  { path: '/', name: 'ReaderHome', component: ReaderHome },
  { path: '/article', name: 'ArticleView', component: ArticleView },
  { path: '/categories', name: 'CategoryView', component: CategoryView },
  { path: '/gallery', name: 'GalleryView', component: GalleryView },
  { path: '/issues', name: 'PublishedIssuesView', component: PublishedIssuesView },

  // Authentication
  { path: '/login', name: 'LoginView', component: LoginView },
  { path: '/signup', name: 'SignUpView', component: SignUpView },
  { path: '/forgot-password', name: 'ForgotPasswordView', component: ForgotPasswordView },
  { path: '/reset-password', name: 'ResetPasswordView', component: ResetPasswordView },
  { path: '/otp', name: 'OtpView', component: OtpView },

  // Dashboards (staff only — protected by navigation guard below)
  { path: '/admin',   name: 'AdminDashboard',          component: AdminDashboard,          meta: { requiresStaff: true } },
  { path: '/eic',     name: 'EditorInChiefDashboard',  component: EditorInChiefDashboard,  meta: { requiresStaff: true } },
  { path: '/editor',  name: 'SectionEditorDashboard',  component: SectionEditorDashboard,  meta: { requiresStaff: true } },
  { path: '/writer',  name: 'StaffWriterDashboard',    component: StaffWriterDashboard,    meta: { requiresStaff: true } },
  { path: '/artist',  name: 'StaffArtistDashboard',    component: StaffArtistDashboard,    meta: { requiresStaff: true } },
  { path: '/monitoring-sheet', name: 'MonitoringSheetView', component: MonitoringSheetView, meta: { requiresStaff: true } },
  { path: '/monitoring-sheet/:monitoringSheet', name: 'MonitoringSheetDetailView', component: MonitoringSheetView, meta: { requiresStaff: true } },
  { path: '/monitoring_sheet_fullscreen.html', redirect: '/monitoring-sheet' },

  // Profile (any logged-in user)
  { path: '/profile', name: 'ProfileView', component: ProfileView, meta: { requiresAuth: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 };
  }
});

// ── Navigation Guard ──────────────────────────────────────────────────────────
router.beforeEach((to) => {
  const raw = localStorage.getItem('sparky_user');
  const user = raw ? JSON.parse(raw) : null;
  const role = user?.role || null;

  // Route requires any logged-in user
  if (to.meta.requiresAuth && !user) {
    return { name: 'LoginView' };
  }

  // Route requires staff access
  if (to.meta.requiresStaff) {
    if (!user) return { name: 'LoginView' };
    if (!STAFF_ROLES.includes(role)) return { name: 'ReaderHome' };
  }

  // Logged-in staff visiting /login or /signup → redirect to dashboard
  if ((to.name === 'LoginView' || to.name === 'SignUpView') && user && STAFF_ROLES.includes(role)) {
    const dashMap = {
      admin: 'AdminDashboard', eic: 'EditorInChiefDashboard',
      section_editor: 'SectionEditorDashboard',
      staff_writer: 'StaffWriterDashboard', staff_artist: 'StaffArtistDashboard',
    };
    return { name: dashMap[role] };
  }
});

export default router;
