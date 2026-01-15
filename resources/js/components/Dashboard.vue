<template>
  <div class="max-w-md mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-5">{{ $t('Dashboard') }}</h1>
    <p> {{ $t("Welcome") }}, {{ user.name }}</p>   

<transaction-history />
    
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
    this.user = res.data['user']; //set user

    //console.log(res.data);
  },
  methods: {
    async logout() {
      const token = localStorage.getItem('token');
      await axios.post('/api/logout', {}, {
        headers: { Authorization: `Bearer ${token}` }
      });
      logout();
      this.$router.push('/login');
    }
  }
}
</script>
