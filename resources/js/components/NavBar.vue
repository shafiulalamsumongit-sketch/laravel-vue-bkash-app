<template>
    <nav class="bg-neutral-primary fixed w-full z-20 top-0 start-0 border-b border-default">
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
                    <template v-if="isLoggedIn">
                        <li>
                            <router-link @click="open = false" to="/dashboard"
                                class="hover:underline block py-2 px-3 text-black bg-brand rounded md:bg-transparent md:text-fg-brand md:p-0">
                                {{ $t("Dashboard") }}</router-link>
                        </li>
                        <li>
                            <router-link to="/execute-agreement" @click.prevent="handleAggreement"
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
                                {{ $t("Create_Payment") }}
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/dashboard" @click.prevent="handleRefund"
                                class="hover:underline py-2 px-3 text-white bg-brand rounded md:bg-transparent md:text-fg-brand md:p-0">
                                {{ $t("Refund") }}
                            </router-link>
                        </li>
                    </template>
                    <language-switcher />
                    <template v-if="isLoggedIn">
                        <li>
                            <router-link to="/login" @click.prevent="handleLogout"
                                class="hover:underline py-2 px-3 text-white bg-brand rounded md:bg-transparent md:text-fg-brand md:p-0">
                                {{ $t("Logout") }}
                            </router-link>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>
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
