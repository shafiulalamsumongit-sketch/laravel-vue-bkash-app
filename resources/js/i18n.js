import { createI18n } from "vue-i18n";
import axios from "axios";

const i18n = createI18n({
    legacy: false,
    locale: localStorage.getItem("locale") || "en",
    fallbackLocale: "en",
    messages: {},
});

export async function setLocale(locale) {
    // already loaded
    if (!i18n.global.availableLocales.includes(locale)) {
        const { data } = await axios.get(`/translations/${locale}`);
        i18n.global.setLocaleMessage(locale, data);
    }
    i18n.global.locale.value = locale;
    localStorage.setItem("locale", locale);
}

export default i18n;
