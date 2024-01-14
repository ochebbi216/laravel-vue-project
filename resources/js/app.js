import { createApp } from 'vue';
import { createRouter, createWebHistory } from "vue-router";
import { routes } from "./routes";
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/lara-light-green/theme.css'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import App from './App.vue'
let app = createApp(App)
const router = createRouter({
    history: createWebHistory('/admin'),
    routes: routes,
})
router.beforeEach((to, from, next) => {
    let token = localStorage.getItem('admintoken');
    if (to.matched.some(record => record.meta.isAuthAdmin)) {
        if (!token) {
            next("/login");
        } else {
            next(); // The user is authenticated, so the navigation can proceed
        }
    } else if (token && (to.path === '/login' || to.path === '/register')) {
        next("/dashboard"); // Redirect authenticated user to home if trying to access login/register
    } else {
        next(); // Either the route does not require authentication, or no user is logged in
    }
});
app.use(router).use(PrimeVue).use(Toast);
app.mount("#app");
