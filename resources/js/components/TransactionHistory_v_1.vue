<template>
    <h1 class="text-left text-2xl font-bold m-8">{{ $t("Transaction") }}</h1>
    <hr class="my-4 h-px border-t-0 bg-gray-300 m-8">
    </hr>
    
    <div class="relative overflow-x-auto bg-neutral-primary shadow-xs rounded-base border border-default m-8">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="text-sm text-body border-b border-default">
                <tr>
                    <th scope="col" class="px-6 py-3 bg-neutral-secondary-soft font-medium">
                        <b>Trx_iD</b>
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        <b>Amount</b>
                    </th>
                    <th scope="col" class="px-6 py-3 bg-neutral-secondary-soft font-medium">
                        <b>Transaction Status</b>
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        <b>Credited Amount</b>
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        <b>Date</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-default" v-for="transaction in transactions" :key="transaction.id">
                    <th scope="row"
                        class="px-6 py-4 font-medium text-heading whitespace-nowrap bg-neutral-secondary-soft">
                        <b> {{ transaction.trx_iD }}</b>
                    </th>
                    <td class="px-6 py-4">
                        {{ transaction.amount }}
                    </td>
                    <td class="px-6 py-4 bg-neutral-secondary-soft">
                        {{ transaction.transaction_status }}
                    </td>
                    <td class="px-6 py-4">
                        {{ transaction.credited_amount }}
                    </td>
                    <td class="px-6 py-4">
                        {{ transaction.created_at }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <div>
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <!-- Pagination -->
                    <div class="flex justify-center mt-6">
                        <nav class="inline-flex rounded-md shadow-sm">
                            <!-- Previous -->
                            <button @click="changePage(pagination.current_page - 1)"
                                :disabled="!pagination.prev_page_url"
                                class="px-3 py-2 border border-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100">
                                Prev
                            </button>
                            <!-- Page Numbers -->
                            <button v-for="page in pagination.last_page" :key="page" @click="changePage(page)"
                                class="px-3 py-2 border border-gray-300 text-sm font-medium" :class="page === pagination.current_page
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-white text-gray-700 hover:bg-gray-100'
                                    ">
                                {{ page }}
                            </button>
                            <!-- Next -->
                            <button @click="changePage(pagination.current_page + 1)"
                                :disabled="!pagination.next_page_url"
                                class="px-3 py-2 border border-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100">
                                Next
                            </button>
                        </nav>
                    </div>
                    <div class="flex justify-center mt-6">
                        <!-- Centered container -->
                        <div class="flex justify-center items-center">
                            <!-- Download Button -->
                            <a href="#" @click.prevent="downloadTransactionsPdf"
                                class="flex items-center gap-2 px-6 py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition-colors duration-200">
                                <!-- Download Icon (Optional) -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Statement
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>

import { ref, onMounted } from "vue";
import axios from "axios";
import Pagination from "@/components/Pagination.vue";

const token = localStorage.getItem("token");
const currentPage = ref(1);
const lastPage = ref(1);
const transactions = ref([]);
const pagination = ref({});
const page = ref(1);

const fetchTransactions = async () => {
    const res = await axios.get(
        `/api/transaction/histories?page=${page.value}`,
        {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        }
    );
    transactions.value = res.data.transactions.data;
    pagination.value = res.data.transactions;
};

const downloadTransactionsPdf = async () => {
    const headers = {
        Authorization: `Bearer ${token}`,
    };
    const response = await fetch("/api/transaction/download", {
        method: "GET",
        headers: headers,
    });
    const blob = await response.blob();
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.href = url;
    link.download = "invoice.pdf";
    document.body.appendChild(link);
    link.click();
    link.remove();
    window.URL.revokeObjectURL(url);
};

const changePage = (p) => {
    page.value = p;
    fetchTransactions();
};

const fetchTransac1tions1 = async (page = 1) => {
    currentPage.value = page;
    axios
        .get(`/api/transaction/histories?page=${page}`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        })
        .then((response) => {
            console.log(response.data);
            //transactions.value = response.data.transactions;
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
        });
};

fetchTransactions();

</script>