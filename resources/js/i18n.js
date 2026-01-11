import { createI18n } from "vue-i18n";
import axios from "axios";

const i18n = createI18n({
    legacy: false,
    locale: localStorage.getItem("locale") || "en",
    fallbackLocale: "en",
    messages: {},
});


axios.defaults.headers.common['X-Locale'] = localStorage.getItem('locale') || 'en';

function updateLocale(lang) {
    //localStorage.setItem('locale', lang)
    //axios.defaults.headers.common['X-Locale'] = lang
}

export async function setLocale(locale) {
    // already loaded
    axios.defaults.headers.common['X-Locale'] = locale; //updateLocale 
    if (!i18n.global.availableLocales.includes(locale)) {
        const { data } = await axios.get(`/translations/${locale}`);
        i18n.global.setLocaleMessage(locale, data);
    }
    i18n.global.locale.value = locale;
    localStorage.setItem("locale", locale);   
    
}

export default i18n;
