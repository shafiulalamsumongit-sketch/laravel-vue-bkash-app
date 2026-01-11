import { createRouter, createWebHistory } from "vue-router";
import Login from "../components/Login.vue";
import Register from "../components/Register.vue";
import Dashboard from "../components/Dashboard.vue";
import Profile from "../components/Profile.vue";

//export function isAuthenticated() {
function isAuthenticated() {
  return !!localStorage.getItem('token')
}

const routes = [
  {
    path: '/',
    redirect: () => {
      return isAuthenticated() ? '/dashboard' : '/login'
    }
  },
  {
    path: '/login',
    component: Login,
    meta: { guest: true }
  },
  {
    path: '/register',
    component: Register,
    meta: { guest: true }
  },
  {
    path: '/dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/profile',
    component: Profile,
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

/**
 * GLOBAL AUTH GUARD
 */
router.beforeEach((to, from, next) => {
  // If route requires login
  if (to.meta.requiresAuth && !isAuthenticated()) {
    next('/login')
  }
  // If route is guest-only and user is logged in
  else if (to.meta.guest && isAuthenticated()) {
    next('/dashboard')
  }
  else {
    next()
  }
})

export default router;