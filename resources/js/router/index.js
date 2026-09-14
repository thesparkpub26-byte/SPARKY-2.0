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

const routes = [
  // Reader Portal
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

  // Dashboards
  { path: '/admin', name: 'AdminDashboard', component: AdminDashboard },
  { path: '/eic', name: 'EditorInChiefDashboard', component: EditorInChiefDashboard },
  { path: '/editor', name: 'SectionEditorDashboard', component: SectionEditorDashboard },
  { path: '/writer', name: 'StaffWriterDashboard', component: StaffWriterDashboard },
  { path: '/artist', name: 'StaffArtistDashboard', component: StaffArtistDashboard },
  { path: '/monitoring-sheet', name: 'MonitoringSheetView', component: MonitoringSheetView },
  { path: '/monitoring_sheet_fullscreen.html', redirect: '/monitoring-sheet' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 };
  }
});

export default router;
