// src/utils/auth.js
import { ref } from "vue";

export const isLoggedIn = ref(!!localStorage.getItem("token"));
export const isWalletExists = ref(!!localStorage.getItem("wallet-exists"));

isWalletExists.value = false;

export function setWalletExists(value) {
    localStorage.setItem("wallet-exists", value);
    isWalletExists.value = value;
}

export function login(token) {
    localStorage.setItem("token", token);
    isLoggedIn.value = true;
}

export function logout() {
    localStorage.removeItem("token");
    isLoggedIn.value = false;
}

export async function processAggreement() {
    const token = localStorage.getItem("token");
    const res = await axios.get("/api/wallet/create-aggreement", {
        headers: { Authorization: `Bearer ${token}` },
    });
    if (res.data.bkashURL != "") {
        window.location.href = res.data.bkashURL;
    }else{
       alert(err.response.data.message || "Invalid - Failed");
    }
}
