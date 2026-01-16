<template>
    <div class="max-w-md mx-auto mt-10">
        <h1 class="max-w-sm mx-auto text-2xl font-bold mb-5">
            {{ $t("Refund") }}
        </h1>
        <form class="max-w-sm mx-auto" @submit.prevent="paymentSubmit">
            <input v-model="paymentID" type="text" aria-describedby="helper-text-explanation"
                class="block w-full mb-3 px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                :placeholder="$t('paymentID')" required />
            <input v-model="trxId" type="text" aria-describedby="helper-text-explanation"
                class="block w-full mb-3 px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                :placeholder="$t('trxId')" required />
            <button :disabled="isSubmitting" class="bg-blue-500 text-white p-2 w-full">
                {{ isSubmitting ? "Submitting..." : $t("Submit") }}
            </button>
        </form>
        <template v-if="success != ''">
            <div class="bg-green-500 hover:bg-green-600 px-4 py-1 rounded">
                {{ apiData }}
            </div>
        </template>
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
            apiData: "",
            success: "",
            paymentID: "",
            trxId: "",
            isSubmitting: false,
        };
    },
    methods: {
        async paymentSubmit() {
            this.success = "";

            if (this.paymentID == "") {
                alert("paymentID empty");
            } else if (this.trxId == "") {
                alert("trxId empty");
            } else {
                if (this.isSubmitting) {
                    return;
                }
                this.isSubmitting = true; // Disable the button
                const res = await axios.post(
                    "/api/refund",
                    {
                        payment_id: this.paymentID,
                        trx_id: this.trxId,
                    },
                    {
                        headers: { Authorization: `Bearer ${token}` },
                    }
                );
                this.success = "success";
                this.apiData = res;
                this.isSubmitting = false;
            }
        },
    },
};
</script>
