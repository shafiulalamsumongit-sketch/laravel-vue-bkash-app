// src/utils/auth.js
import { ref } from 'vue'

export const isLoggedIn = ref(!!localStorage.getItem('token'))

export function login(token) {
  localStorage.setItem('token', token)
  isLoggedIn.value = true
}

export function logout() {
  localStorage.removeItem('token')
  isLoggedIn.value = false
}
