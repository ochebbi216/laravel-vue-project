import Acceuil from './components/CustomerLayout/Home/Home.vue';
import Login from './components/CustomerLayout/Auth/Login.vue';
import Register from './components/CustomerLayout/Auth/Register.vue';
import Room from './components/CustomerLayout/Rooms/Room.vue';
import Reservation from './components/CustomerLayout/Reservations/Reservation.vue';
import Reclamation from './components/CustomerLayout/Reclamation/Reclamation.vue';
import RoomDetails from './components/CustomerLayout/Rooms/RoomDetails.vue';
import AddReservation from './components/CustomerLayout/Reservations/AddResevation.vue';
import DetailsReclamation from './components/CustomerLayout/Reclamation/DetailsReclamation.vue';
import AddReclamation from './components/CustomerLayout/Reclamation/AddReclamation.vue';
// import NotFound from './layouts/NotFound.vue';
export const user_routes = [
    {
        name:'Acceuil',
        path:'/home',
        component:Acceuil,

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
    },
    {
        name: 'Room',
        path:'/room',
        component:Room
    },
    {
        name:'RoomDetails',
        path:'/roomdetails/:id',
        component:RoomDetails
    },
    {
        name:'Reservation',
        path:'/reservation',
        component:Reservation,
        meta : {isAuthUser:true }
    },
    {
        name: 'AddReservation',
        path: '/addreservation/:roomId', 
        component: AddReservation,
        meta : {isAuthUser:true }
    },
    {
        name:'Reclamation',
        path:'/reclamation',
        component:Reclamation,
        meta : {isAuthUser:true }
    },
    {
        name:'DetailsReclamation',
        path:'/reclamation/:id',
        component:DetailsReclamation,
        meta : {isAuthUser:true }
    },
    {
        name: 'AddReclamation',
        path: '/addeclamation/:idres', 
        component: AddReclamation,
        meta : {isAuthUser:true }
    },
    // { 
    //     path: '/:catchAll(.*)', 
    //     component: NotFound 
    // }
];