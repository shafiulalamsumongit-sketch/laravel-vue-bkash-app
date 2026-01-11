<template>
  <div class="max-w-md mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-5">{{ $t("Register") }}</h1>
    <form @submit.prevent="register">
      <input v-model="name" type="text" :placeholder="$t('Name')"  class="border p-2 w-full mb-3"/>
      <input v-model="email" type="email" :placeholder="$t('Email')"   class="border p-2 w-full mb-3"/>
      <input v-model="password" type="password" :placeholder="$t('Password')"   class="border p-2 w-full mb-3"/>
      <input v-model="password_confirmation" type="password" :placeholder="$t('Confirm_Password')"  class="border p-2 w-full mb-3"/>
      <button class="bg-green-500 text-white p-2 w-full">{{ $t("Register") }}</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }
  },
  methods: {
    async register() {
      try {
        await axios.post('/api/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        });
        alert('Registration successful');
        this.$router.push('/login');
      } catch (err) {
        alert(err.response.data.message || 'Registration failed');
      }
    }
  }
}
</script>
