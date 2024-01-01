import Dashboard from './components/Admin/Dashboard/Dashboard.vue';
import Rooms from './components/Admin/Rooms/ListRooms.vue';
import Reservations from './components/Admin/Reservations/ListReservations.vue';
import AddRoom from './components/Admin/Rooms/AddRoom.vue';
import EditRoom from './components/Admin/Rooms/EditRoom.vue';
import ListUser from './components/Admin/User/ListUsers.vue';
import BlackListUsers from './components/Admin/User/BlackListUser.vue';
import Reclamation from './components/Admin/Reclamations/Reclamation.vue';
import DetaisReclamation from'./components/Admin/Reclamations/DetailsReclamation.vue';
import Login from './components/Admin/Auth/Login.vue';
// import NotFound from './layouts/NotFound.vue';

export const routes = [
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard,
        meta : {isAuthAdmin:true }
    },
    {
        name: 'rooms',
        path: '/rooms',
        component: Rooms,
        meta : {isAuthAdmin:true }

    },
    {
        name: 'addroom',
        path: '/addroom',
        component: AddRoom,
        meta : {isAuthAdmin:true }

    },
    {
        name: 'editroom',
        path: '/editroom/:id',
        component: EditRoom,
        meta : {isAuthAdmin:true }

    },
    {
        name: 'reservations',
        path: '/reservations',
        component: Reservations,
        meta : {isAuthAdmin:true }

    },
    {
        name: 'listuser',
        path: '/listuser',
        component: ListUser,
        meta : {isAuthAdmin:true }

    },
    {
        name: 'blackListUsers',
        path: '/blackListUsers',
        component: BlackListUsers,
        meta : {isAuthAdmin:true }

    },
    {
        name: 'reclamations',
        path: '/reclamations',
        component: Reclamation,
        meta : {isAuthAdmin:true }

    },
    {
        name: 'detailsrec',
        path: '/Detailsrec/:id',
        component: DetaisReclamation,
        meta : {isAuthAdmin:true }

    },
    {
        name:'Login',
        path :'/login',
        component: Login,
    },
    // { 
    //     path: '/:catchAll(.*)', 
    //     component: NotFound 
    // }

];