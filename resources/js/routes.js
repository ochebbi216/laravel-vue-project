import Dashboard from './components/Admin/Dashboard/Dashboard.vue';
import Rooms from './components/Admin/Rooms/ListRooms.vue';
import Reservations from './components/Admin/Reservations/ListReservations.vue';
import AddRoom from './components/Admin/Rooms/AddRoom.vue';
import EditRoom from './components/Admin/Rooms/EditRoom.vue';
import ListUser from './components/Admin/User/ListUsers.vue';
import BlackListUsers from './components/Admin/User/BlackListUser.vue';
import Reclamation from './components/Admin/Reclamations/Reclamation.vue';
import DetaisReclamation from'./components/Admin/Reclamations/DetailsReclamation.vue';

export const routes = [
    {
        name: 'dashboard',
        path: '/',
        component: Dashboard
    },
    {
        name: 'rooms',
        path: '/rooms',
        component: Rooms
    },
    {
        name: 'addroom',
        path: '/addroom',
        component: AddRoom
    },
    {
        name: 'editroom',
        path: '/editroom/:id',
        component: EditRoom
    },
    {
        name: 'reservations',
        path: '/reservations',
        component: Reservations
    },
    {
        name: 'listuser',
        path: '/listuser',
        component: ListUser
    },
    {
        name: 'blackListUsers',
        path: '/blackListUsers',
        component: BlackListUsers
    },
    {
        name: 'reclamations',
        path: '/reclamations',
        component: Reclamation
    },
    {
        name: 'detailsrec',
        path: '/Detailsrec/:id',
        component: DetaisReclamation
    },

];