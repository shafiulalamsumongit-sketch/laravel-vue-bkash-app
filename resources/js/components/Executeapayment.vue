<template>
    <div class="max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-5">{{ $t("Execute_Payment") }}</h1>
        <template v-if="error != ''">
            <div class="bg-red-500 hover:bg-red-600 px-4 py-1 rounded">
                {{ message }}
            </div>
        </template>
        <template v-if="success != ''">        
            <div class="bg-green-200">
                <table
                    class="table-fixed border-separate border border-green-900"
                >
                    <thead>
                        <tr>
                            <th class="border border-green-600 w-1/4">
                                
                            </th>
                            <th class="border border-green-600 w-3/4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-green-600">
                                PaymentID
                            </td>
                            <td class="border border-green-600">
                                {{ apiData.paymentID }}
                            </td>
                        </tr> 
                        <tr>
                            <td class="border border-green-600">
                                trxId
                            </td>
                            <td class="border border-green-600">
                                {{ apiData.trxID }}
                            </td>
                        </tr>                  
                    </tbody>
                </table>
            </div>
            <br/>
            <div class="bg-green-500 hover:bg-green-600 px-4 py-1 rounded">
                {{ message }}
            </div>
        </template>
    </div>
</template>
<script>
import axios from "axios";
import { isLoggedIn, logout } from "@/utils/auth";
export default {
    data() {
        return {
            apiData: null,
            loading: true,
            message: "",
            error: "",
            success: "",
        };
    },
    methods: {
        async redirectDashboard() {
            this.$router.push("/dashboard");
        },
        async fetchPaymmentStatus(paymentID) {
            const token = localStorage.getItem("token");
            const res = await axios.post(
                "/api/payment/status-payment",
                { payment_id: paymentID },
                {
                    headers: { Authorization: `Bearer ${token}` },
                }
            );
            this.error = "";
            this.success = "";
            this.message = "";
            this.message = res.data.statusMessage;
            if (res.data.statusCode == "error") {
                this.error = res.data.statusCode;
            } else if (res.data.statusCode == "found") {
                this.success = res.data.statusCode;
                this.message = res.data;
                this.apiData = res.data.statusMessage;
            }
        },
    },
    mounted() {
        const token = localStorage.getItem("token");
        const urlParams = new URLSearchParams(window.location.search);
        const paymentID = urlParams.get("paymentID");
        const status = urlParams.get("status");
        const signature = urlParams.get("signature");
        if (status == "failure") {
            this.error = "error";
            this.message ="There are problems while processing . Please try again later.";
        } else if (status == "cancel") {
            this.error = "error";
            this.message = "Payment process canceled. Please try again later.";
        } else {
            //this.fetchPaymmentStatus(paymentID);
        }
        this.fetchPaymmentStatus(paymentID);
    },
};
</script>
