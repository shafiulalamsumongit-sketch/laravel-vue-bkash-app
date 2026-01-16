<template>


      <div class="max-w-md mx-auto mt-10">
        <h1 class="max-w-sm mx-auto text-2xl font-bold mb-5">{{ $t("Register") }}</h1>
        <form @submit.prevent="register" class="max-w-sm mx-auto">


 <div class="mb-5">
                <input v-model="name" type="text" id="name"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    :placeholder="$t('Name')" required />
            </div>

            <div class="mb-5">
                <input v-model="email" type="email" id="email"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    :placeholder="$t('Email')" required />
            </div>
            <div class="mb-5">
                <input v-model="password" type="password" id="password"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    :placeholder="$t('Password')" required />
            </div>

<div class="mb-5">
                <input v-model="password_confirmation" type="password" id="password_confirmation"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    :placeholder="$t('Confirm_Password')" required />
            </div>




            <button type="submit"
                class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
              {{  $t('Submit') }}
            </button>
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