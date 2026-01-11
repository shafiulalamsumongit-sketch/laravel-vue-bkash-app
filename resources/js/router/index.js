import { createRouter, createWebHistory } from "vue-router";
import Login from "../components/Login.vue";
import Register from "../components/Register.vue";
import Dashboard from "../components/Dashboard.vue";
import Profile from "../components/Profile.vue";
import Executeagreement from "../components/Executeagreement.vue";

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
  },
  {
    path: '/execute-agreement',
    component: Executeagreement
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})


router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !isAuthenticated()) {
    next('/login')
  }
  else if (to.meta.guest && isAuthenticated()) {
    next('/dashboard')
  }
  else {
    next()
  }
})

export default router;