<template>
    <div class="max-w-md mx-auto mt-10">
        <h1 class="max-w-sm mx-auto text-2xl font-bold mb-5">{{ $t("Payment") }}</h1>
        <form class="max-w-sm mx-auto" @submit.prevent="paymentSubmit">
            <select id="order_id" v-model="order_id"
                class="mb-4 block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                <option value="">Choose an order</option>
                <option value="ord_1">Order 1</option>
                <option value="ord_2">Order 2</option>
                <option value="ord_3">Order 3</option>
                <option value="ord_4">Order 4</option>
                <option value="ord_5">Order 5</option>
                <option value="ord_6">Order 6</option>
                <option value="ord_7">Order 7</option>
                <option value="ord_8">Order 8</option>
            </select>
            <input v-model="amount" type="number" aria-describedby="helper-text-explanation"
                class="block w-full mb-3  px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                :placeholder="$t('Amount')" required />
            <button :disabled="isSubmitting" class="bg-blue-500 text-white p-2 w-full">
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
            order_id: "",
            amount: "",
            isSubmitting: false,
        };
    },
    methods: {
        async paymentSubmit() {
            if (this.amount == "") {
                alert("Amount empty");
            } else if (this.order_id == "") {
                alert("Select an order");
            } else {
                if (this.isSubmitting) {
                    return;
                }
                this.isSubmitting = true; // Disable the button
                this.isSubmitting = false;
                const res = await axios.post(
                    "/api/payment/payment-with-agreement",
                    {
                        amount: this.amount, order_id: this.order_id,
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
