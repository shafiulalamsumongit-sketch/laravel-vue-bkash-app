<template>
  <div class="max-w-md mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-5">{{ $t('Dashboard') }}</h1>
    <p>Welcome, {{ user.name }}</p>
    <button @click="logout" class="bg-red-500 text-white p-2 mt-3">Logout</button>
  </div>
</template>

<script>
import axios from 'axios';
import { isLoggedIn, logout } from "@/utils/auth";

export default {
  data() {
    return {
      user: {}
    }
  },
  async created() {
    const token = localStorage.getItem('token');
    const res = await axios.get('/api/user', {
      headers: { Authorization: `Bearer ${token}` }
    });
    this.user = res.data; //set user
  },
  methods: {
    async logout() {
      const token = localStorage.getItem('token');
      await axios.post('/api/logout', {}, {
        headers: { Authorization: `Bearer ${token}` }
      });
      logout();
      //localStorage.removeItem('token');
      this.$router.push('/login');
    }
  }
}
</script>
