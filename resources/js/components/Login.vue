<template>
    <div class="max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-5"> {{ $t("Login") }}</h1>
        <form @submit.prevent="login">
            <input
                v-model="email"
                type="email"
                :placeholder="$t('Email')"
                class="border p-2 w-full mb-3"
            />
            <input
                v-model="password"
                type="password"
                :placeholder="$t('Password')"
                class="border p-2 w-full mb-3"
            />
            <button class="bg-blue-500 text-white p-2 w-full"> {{ $t("Login") }}</button>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import { useRoute } from 'vue-router'

import { login } from "@/utils/auth";
const route = useRoute()

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
