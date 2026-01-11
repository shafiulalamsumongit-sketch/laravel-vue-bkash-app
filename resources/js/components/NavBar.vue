<template>
    <nav class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <router-link to="/" class="text-xl font-bold text-sky-400">
                    {{ $t("MyApp") }}
                </router-link>
                
                <!-- Language Switcher -->
                <language-switcher />

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <!-- Guest -->
                    <template v-if="!isLoggedIn">
                        <router-link to="/login" class="hover:text-sky-400">
                            {{ $t("Login") }}
                        </router-link>
                        <router-link to="/register" class="hover:text-sky-400">
                            {{ $t("Register") }}
                        </router-link>
                    </template>

                    <!-- Auth -->
                    <template v-else>
                        <router-link to="/dashboard" class="hover:text-sky-400">
                            {{ $t("Dashboard") }}
                        </router-link>
                        <router-link to="/profile" class="hover:text-sky-400">
                            {{ $t("Profile") }}
                        </router-link>
                        <button
                            @click="handleLogout"
                            class="bg-red-500 hover:bg-red-600 px-4 py-1 rounded"
                        >
                            {{ $t("Logout") }}
                        </button>
                    </template>
                </div>

                <!-- Mobile button -->
                <button
                    @click="open = !open"
                    class="md:hidden focus:outline-none"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div v-if="open" class="md:hidden bg-gray-800 px-4 py-4 space-y-3">
            <template v-if="!isLoggedIn">
                <router-link @click="open = false" to="/login" class="block">
                    {{ $t("Login") }}</router-link
                >
                <router-link @click="open = false" to="/register" class="block">
                    {{ $t("Register") }}</router-link
                >
            </template>

            <template v-else>
                <router-link @click="open = false" to="/dashboard" class="block"
                    >Dashboard</router-link
                >
                <router-link @click="open = false" to="/profile" class="block"
                    >Profile</router-link
                >
                <button
                    @click="handleLogout"
                    class="w-full text-left text-red-400"
                >
                    Logout
                </button>
            </template>
        </div>
    </nav>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { isLoggedIn, logout } from "@/utils/auth";

const router = useRouter();
const open = ref(false);

const handleLogout = () => {
    logout();
    open.value = false;
    router.push("/login");
};
</script>
