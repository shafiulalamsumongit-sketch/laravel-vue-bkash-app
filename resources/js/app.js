import "./bootstrap";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";

import NavBar from "./components/NavBar.vue"; // Import the component
import LanguageSwitcher from "./components/LanguageSwitcher.vue"; // Import the component
import i18n, { setLocale } from "./i18n";

(async () => {
   
    const app = createApp(App);
    app.component("navbar", NavBar);
    app.component("language-switcher", LanguageSwitcher);
    app.use(i18n);
    app.use(router);
    setLocale(i18n.global.locale.value); // load initial language
    app.mount("#app");
})();
