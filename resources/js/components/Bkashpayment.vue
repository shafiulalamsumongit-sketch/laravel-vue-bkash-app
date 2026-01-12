<template>
    <div class="max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-5">{{ $t("Payment") }}</h1>
        <form @submit.prevent="paymentSubmit">
            <input
                v-model="amount"
                type="number"
                :placeholder="$t('Amount')"
                class="border p-2 w-full mb-3"
            />
            <button
                :disabled="isSubmitting"
                class="bg-blue-500 text-white p-2 w-full"
            >
                {{ isSubmitting ? "Submitting..." : $t("Submit") }}
            </button>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import { useRoute } from "vue-router";
import { login } from "@/utils/auth";
const route = useRoute();
const token = localStorage.getItem("token");
export default {
    data() {
        return {
            amount: "",
            isSubmitting: false,
        };
    },
    methods: {
        async paymentSubmit() {
            if (this.amount == "") {
                alert("Amount empty");
            } else {
                if (this.isSubmitting) {
                    return;
                }
                this.isSubmitting = true; // Disable the button
                const res = await axios.post(
                    "/api/payment/payment-with-agreement",
                    {
                        amount: this.amount,
                    },
                    {
                        headers: { Authorization: `Bearer ${token}` },
                    }
                );
                if (res.data.statusCode == "error") {
                    alert(res.data.statusMessage);
                } else {
                    window.location.href = res.data.bkashURL;
                }
                this.isSubmitting = false;
            }
        },
    },
};
</script>
