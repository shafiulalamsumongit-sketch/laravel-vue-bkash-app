<template>
    <nav class="bg-neutral-primary fixed w-full z-20 top-0 start-0 border-b border-default">      
        
        <!-- <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <router-link to="/" class="text-xl font-bold text-sky-400">
                    {{ $t("MyApp") }}
                </router-link>
                <template v-if="isLoggedIn">
                    <button @click="handleAggreement" class="bg-red-500 hover:bg-red-600 px-4 py-1 rounded">
                        {{ $t("Create_Aggreement") }}
                    </button>
                    <button @click="handleTransaction" class="bg-green-500 hover:bg-green-600 px-4 py-1 rounded">
                        {{ $t("Transaction") }}
                    </button>
                    <button @click="handlePayment" class="bg-green-500 hover:bg-green-600 px-4 py-1 rounded">
                        {{ $t("Create_Payment") }}
                    </button>
                    <button @click="handleRefund" class="bg-red-500 hover:bg-red-600 px-4 py-1 rounded">
                        {{ $t("Refund") }}
                    </button>
                </template>
                <language-switcher />
                <div class="hidden md:flex items-center space-x-6">
                    <template v-if="!isLoggedIn">
                        <router-link to="/login" class="hover:text-sky-400">
                            {{ $t("Login") }}
                        </router-link>
                        <router-link to="/register" class="hover:text-sky-400">
                            {{ $t("Register") }}
                        </router-link>
                    </template>
                    <template v-else>
                        <router-link to="/dashboard" class="hover:text-sky-400">
                            {{ $t("Dashboard") }}
                        </router-link>
                        <router-link to="/profile" class="hover:text-sky-400">
                            {{ $t("Profile") }}
                        </router-link>
                        <button @click="handleLogout" class="bg-red-500 hover:bg-red-600 px-4 py-1 rounded">
                            {{ $t("Logout") }}
                        </button>
                    </template>
                </div>
                <button @click="open = !open" class="md:hidden focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>         
        <div v-if="open" class="md:hidden bg-gray-800 px-4 py-4 space-y-3">
            <template v-if="!isLoggedIn">
                <router-link @click="open = false" to="/login" class="block">
                    {{ $t("Login") }}</router-link>
                <router-link @click="open = false" to="/register" class="block">
                    {{ $t("Register") }}</router-link>
            </template>
            <template v-else>
                <router-link @click="open = false" to="/dashboard" class="block">
                    {{ $t("Dashboard") }}</router-link>
                <router-link @click="open = false" to="/profile" class="block">
                    {{ $t("Profile") }}</router-link>
                <button @click="handleLogout" class="w-full text-left text-red-400">
                    {{ $t("Logout") }}
                </button>
            </template>
        </div>-->

        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="#" @click.prevent="handleDashboard" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="https://flowbite.com/docs/images/logo.svg" class="h-7" alt="Flowbite Logo" />
                <span class="self-center text-xl text-heading font-semibold whitespace-nowrap">Agni</span>
            </a>
            <div class="inline-flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <template v-if="!isLoggedIn">
                    <button @click="handleLogin" type="button"
                        class="text-white bg-success hover:bg-brand-strong box-border border m-1 border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-3 py-2 focus:outline-none">
                        {{ $t("Login") }}
                    </button>
                    <button @click="handleRegister" type="button"
                        class="text-white bg-success hover:bg-brand-strong box-border border m-1 border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-3 py-2 focus:outline-none">
                        {{ $t("Register") }}
                    </button>
                </template>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-cta">
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-default rounded-base bg-neutral-secondary-soft md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-neutral-primary">
                    <li>
                        <router-link @click="open = false" to="/dashboard"
                            class="hover:underline block py-2 px-3 text-black bg-brand rounded md:bg-transparent md:text-fg-brand md:p-0">
                            {{ $t("MyApp") }}</router-link>
                    </li>

                    <li>
                        <router-link to="/dashboard" @click.prevent="handleAggreement"
                            class="hover:underline block py-2 px-3 text-white bg-brand rounded md:bg-transparent md:text-fg-brand md:p-0">
                            {{ $t("Create_Aggreement") }}
                        </router-link>
                    </li>

                    <li>
                        <router-link to="/dashboard" @click.prevent="handleTransaction"
                            class="hover:underline block py-2 px-3 text-white bg-brand rounded md:bg-transparent md:text-fg-brand md:p-0">
                            {{ $t("Transaction") }}
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/dashboard" @click.prevent="handlePayment"
                            class="hover:underline block py-2 px-3 text-white bg-brand rounded md:bg-transparent md:text-fg-brand md:p-0">
                            {{ $t("Payment") }}
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/dashboard" @click.prevent="handleRefund"
                            class="hover:underline py-2 px-3 text-white bg-brand rounded md:bg-transparent md:text-fg-brand md:p-0">
                            {{ $t("Refund") }}
                        </router-link>
                    </li>



<template v-if="!isLoggedIn">
                <router-link  @click.prevent="handleLogin" to="/login" class="block">
                    {{ $t("Login") }}
                    </router-link>
                <router-link  @click.prevent="handleRegister" to="/register" class="block">
                    {{ $t("Register") }}</router-link>
            </template>
            <template v-else>
                <button @click.prevent="handleLogout"  class="w-full text-left text-red-400">
                    {{ $t("Logout") }}
                </button>
            </template>


                    <!-- <li>
                        <a href="#"
                            class="block py-2 px-3 text-heading rounded hover:bg-neutral-tertiary md:hover:bg-transparent md:border-0 md:hover:text-fg-brand md:p-0 md:dark:hover:bg-transparent">About</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block py-2 px-3 text-heading rounded hover:bg-neutral-tertiary md:hover:bg-transparent md:border-0 md:hover:text-fg-brand md:p-0 md:dark:hover:bg-transparent">Services</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block py-2 px-3 text-heading rounded hover:bg-neutral-tertiary md:hover:bg-transparent md:border-0 md:hover:text-fg-brand md:p-0 md:dark:hover:bg-transparent">Contact</a>
                    </li> -->
                    <language-switcher />
                </ul>
            </div>
        </div>
    </nav>

    <br />
    <!-- or, for compatibility with XHTML, you might see -->
    <br /><br />
    <!-- or, for compatibility with XHTML, you might see -->
    <br /><br />
    <!-- or, for compatibility with XHTML, you might see -->
    <br />
</template>
<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import {
    processAggreement,
    isWalletExists,
    isLoggedIn,
    login,
    logout,
} from "@/utils/auth";
const router = useRouter();
const open = ref(false);
const resAggreement = ref([]);
const handleDashboard = () => {
    open.value = false;
    router.push("/dashboard");
};
const handleLogin = () => {
    open.value = false;
    router.push("/login");
};
const handleRegister = () => {
    open.value = false;
    router.push("/register");
};
const handleLogout = () => {
    logout();
    open.value = false;
    router.push("/login");
};
function handleAggreement() {
    processAggreement();
}
function handlePayment() {
    router.push("/process-payment");
}
function handleRefund() {
    router.push("/process-refund");
}
function handleTransaction() {
    router.push("/transaction-histories");
}
</script>
