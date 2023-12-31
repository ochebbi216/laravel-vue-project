import Acceuil from './components/CustomerLayout/Home/Home.vue';
import Login from './components/CustomerLayout/Auth/Login.vue';
import Register from './components/CustomerLayout/Auth/Register.vue';
import Room from './components/CustomerLayout/Rooms/Room.vue';
import Reservation from './components/CustomerLayout/Reservations/Reservation.vue';
import Payment from './components/CustomerLayout/Payment/Payment.vue';
import Reclamation from './components/CustomerLayout/Reclamation/Reclamation.vue';
import RoomDetails from './components/CustomerLayout/Rooms/RoomDetails.vue';
import AddReservation from './components/CustomerLayout/Reservations/AddResevation.vue';
export const user_routes = [
    {
        name:'Acceuil',
        path:'/',
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
        path:'/resevervation',
        component:Reservation
    },
    {
        path: '/addreservation/:roomId', // You can still pass the room ID for reference
        name: 'AddReservation',
        component: AddReservation,
        props: route => ({ room: route.params.room }) // Pass the room object as a prop
    },
    {
        name:'Payment',
        path:'/payment',
        component: Payment
    },
    {
        name:'Reclamation',
        path:'/reclamation',
        component:Reclamation
    },


];