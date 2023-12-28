import Acceuil from './components/CustomerLayout/Home/Home.vue';
import Login from './components/CustomerLayout/Auth/Login.vue';
import Register from './components/CustomerLayout/Auth/Register.vue';

export const user_routes = [
    {
        name:'Acceuil',
        path:'/',
        component:Acceuil,
        meta:{isAuthUser:true}
    },
    {
        name:'Login',
        path:'/login',
        component:Login,
        meta: { hideLayout: true }
    },
    {
        name:'Register',
        path:'/register',
        component:Register,
        meta: { hideLayout: true }
    }

];