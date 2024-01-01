import { createApp } from 'vue';
import { createRouter, createWebHistory } from "vue-router";
import { routes } from "./routes";
// import 'bootstrap/dist/css/bootstrap.min.css'
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
            next("/admin/login");
        } else {
            next(); // The user is authenticated, so the navigation can proceed
        }
    } else if (token && (to.path === '/admin/login' || to.path === '/admin/register')) {
        next("/"); // Redirect authenticated user to home if trying to access login/register
    } else {
        next(); // Either the route does not require authentication, or no user is logged in
    }
});
app.use(router).use(Toast);
app.mount("#app");
