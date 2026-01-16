<template>
    <div class="max-w-md mx-auto mt-10">
        <h1 class="max-w-sm mx-auto text-2xl font-bold mb-5">{{ $t("Login") }}</h1>
        <form @submit.prevent="login" class="max-w-sm mx-auto">
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
            <button type="submit"
                class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                 {{  $t('Submit') }}
            </button>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import { useRoute } from "vue-router";

import { login } from "@/utils/auth";
const route = useRoute();

export default {
    data() {
        return {
            email: "",
            password: "",
        };
    },
    methods: {
        async login() {
            try {
                const res = await axios.post("/api/login", {
                    email: this.email,
                    password: this.password,
                });
                //localStorage.setItem("token", res.data.access_token);
                login(res.data.access_token);
                this.$router.push("/dashboard");
            } catch (err) {
                alert(err.response.data.message || "Login failed");
            }
        },
    },
};
</script>
