import { createApp } from 'vue';
import { createRouter, createWebHistory } from "vue-router";
import { user_routes } from "./user-routes";
// import 'bootstrap/dist/css/bootstrap.min.css'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import App from './userApp.vue';
let app = createApp(App)
const router = createRouter({
    history: createWebHistory(),
    routes: user_routes,
})
router.beforeEach((to, from, next) => {
    let token = localStorage.getItem('token');
    if (to.matched.some(record => record.meta.isAuthUser)) {
        if (!token) {
            next("/login");
        } else {
            next(); // The user is authenticated, so the navigation can proceed
        }
    } else if (token && (to.path === '/login' || to.path === '/register')) {
        next("/"); // Redirect authenticated user to home if trying to access login/register
    } else {
        next(); // Either the route does not require authentication, or no user is logged in
    }
});
app.use(router);
app.use(Toast);
app.mount("#userapp")