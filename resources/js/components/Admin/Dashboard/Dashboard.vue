<template >

    <div>
        <Header></Header>
        <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class=" spinner-border " style="color: rgb(0, 150, 136);"></div>
        </div>
        <div v-else class="">

            <div class="page-wrapper">
                <div class="content container-fluid bg">
                    <div class="page-header ">
                        <div class="row  align-items-center">
                            <div class="col">
                                <div class="mt-5">
                                    <h4 class="card-title float-left mt-2">Dashboard</h4>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card board1 fill">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <div>
                                            <h3 class="card_widget_header">{{ reservations.length }}</h3>
                                            <h6 class="text-muted">Total Reservations</h6>
                                        </div>
                                        <div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#009688" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-reservation">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                            <path d="M16 14l-4 4-4-4"></path>
                                            </svg>
                                        </span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card board1 fill">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <div>
                                            <h3 class="card_widget_header">{{rooms.length}}</h3>
                                            <h6 class="text-muted">Total Rooms</h6>
                                        </div>
                                        <div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted">
                                            <i class="fa-solid fa-arrow-right-to-city"></i>
                                            </span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card board1 fill">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <div>
                                            <h3 class="card_widget_header">{{ reclamations.length }}</h3>
                                            <h6 class="text-muted">Total Complaints</h6>
                                        </div>
                                        <div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted">
                                            <i class="fa-solid fa-circle-exclamation"></i>
                                            </span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card board1 fill">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <div>
                                            <h3 class="card_widget_header">{{ tot_revenu }} DT</h3>
                                            <h6 class="text-muted">total revenue</h6>
                                        </div>
                                        <div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewbox="0 0 24 24" fill="none" stroke="#009688" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-dollar-sign">
                                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                </svg></span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card board1 fill">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <div>
                                            <h3 class="card_widget_header">{{ actual_res.length }}</h3>
                                            <h6 class="text-muted">Actual Reservations </h6>
                                        </div>
                                        <div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#009688" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-actual-reservation">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <polyline points="9 12 12 15 20 7"></polyline>
                                            </svg>
                                            </span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card board1 fill">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <div>
                                            <h3 class="card_widget_header">{{availableRoomsCount}}</h3>
                                            <h6 class="text-muted">Available Rooms</h6>
                                        </div>
                                        <div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted">
                                            <i class="fa-solid fa-bed"></i>

                                            </span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card board1 fill">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <div>
                                            <h3 class="card_widget_header">{{users.length}}</h3>
                                            <h6 class="text-muted">Total Customers </h6>
                                        </div>
                                        <div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted">
                                            <i class="fa-solid fa-users"></i>
                                        </span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card board1 fill">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <div>
                                            <h3 class="card_widget_header">{{blacklist.length}}</h3>
                                            <h6 class="text-muted">Black List Customers</h6>
                                        </div>
                                        <div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted">
                                            <i class="fa-solid fa-skull-crossbones"></i>
                                        </span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4 class="card-title">VISITORS</h4>
                            </div>
                            <div class="card-body">
                                <div id="line-chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4 class="card-title">ROOMS BOOKED</h4>
                            </div>
                            <div class="card-body">
                                <div id="donut-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div></template>

<script setup>
import Header from "../layouts/Header.vue";
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';
const reclamations = ref([]);
const reservations = ref([]);
const rooms = ref([]);
const users = ref([]);
const blacklist = ref([]);
const tot_revenu = ref(0);
const actual_res = ref([])
const isLoading = ref(false);
const fetchReclamations = async () => {
	await axios.get('http://localhost:8000/api/reclamation')
		.then(res => {
			// reclamations.value = res.data.filter(reclamation_reservations => reclamation_reservations.status === 0);
            reclamations.value = res.data;
		})
		.catch(error => {
			console.error('Error fetching reclamations:', error);
		})
};
const getRooms = async () => {
  await axios.get("http://localhost:8000/api/rooms")
    .then(res => {
      rooms.value = res.data;
    //   isLoading.value = false; 

    })
    .catch(error => {
      console.log(error);
    });
};
const fetchReservations = async () => {
    try {
        const res = await axios.get('http://localhost:8000/api/reservations');
        reservations.value = res.data;

        let totalRevenue = 0;
        reservations.value.forEach(reservation => {
             totalRevenue += reservation.total_cost;
        });

        tot_revenu.value = totalRevenue; 
        const currentDate = new Date();
        actual_res.value = reservations.value.filter(reservation => {
            const checkinDate = new Date(reservation.checkin);
            const checkoutDate = new Date(reservation.checkout);
            return checkinDate <= currentDate && checkoutDate >= currentDate;
        });
    } catch (error) {
        console.error('Error fetching reservations:', error);
    }
};
const fetchUsers = async () => {
    try {
        const response = await axios.get('http://localhost:8000/api/users');
        users.value = response.data;
        blacklist.value = response.data.filter(users => users.banned === 1);


    } catch (error) {
        console.error('There was a problem fetching the user list:', error);
    }
};
onMounted(async () => {
    isLoading.value = true; // Set isLoading to true when the component is mounted

    // Wait for all async operations to complete
    await Promise.all([
        fetchReservations(),
        getRooms(),
        fetchReclamations(),
        fetchUsers()
    ]);

    isLoading.value = false; // Set isLoading to false after all operations are complete
});
const isAvailable = (roomId) => {
  const currentDate = new Date();
  return !reservations.value.some((reservation) => {
    return reservation.id_room === roomId &&
           reservation.deleted_at === null &&
           new Date(reservation.checkin) <= currentDate &&
           currentDate <= new Date(reservation.checkout);
  });
};

const availableRoomsCount = computed(() => {
  return rooms.value.filter(room => isAvailable(room.id)).length;
});
</script>

<style ></style>