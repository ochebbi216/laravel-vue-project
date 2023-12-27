import { createApp } from 'vue';
import { createRouter, createWebHistory } from "vue-router";
import { user_routes } from "./user-routes";
// import 'bootstrap/dist/css/bootstrap.min.css'

import App from './userApp.vue';
let app = createApp(App)
const router = createRouter({
    history: createWebHistory(),
    routes: user_routes,
})
app.use(router);
app.mount("#user-app")