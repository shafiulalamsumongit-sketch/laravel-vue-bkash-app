<template>
    <div class="max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-5">Login</h1>
        <form @submit.prevent="login">
            <input
                v-model="email"
                type="email"
                placeholder="Email"
                class="border p-2 w-full mb-3"
            />
            <input
                v-model="password"
                type="password"
                placeholder="Password"
                class="border p-2 w-full mb-3"
            />
            <button class="bg-blue-500 text-white p-2 w-full">Login</button>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import { login } from "@/utils/auth";

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
                login(res.data.access_token); // ✔ updates navbar instantly
                this.$router.push("/dashboard");
            } catch (err) {
                alert(err.response.data.message || "Login failed");
            }
        },
    },
};
</script>
