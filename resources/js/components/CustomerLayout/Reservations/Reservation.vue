<template>
        <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class=" spinner-border " style="color: rgb(0, 150, 136);"></div>
        </div>
    <div v-else>
        <!-- <i class="fa-solid fa-circle-info"></i> -->
        <div class="hero-wrap bgres">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text d-flex align-itemd-center justify-content-center">
                    <div class="col-md-9 ftco-animate text-center d-flex align-items-end justify-content-center">
                        <div class="text">
                            <h3 class="mb-4 bread">Reservations List </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="ftco-section ftco-no-pb ftco-room">
            <div class="row justify-content-center" style="height: 100vh;">
                <div class="col-auto">
                    <div class=" card-table">
                        <div class="card-body booking-card">
                            <div class="container-fluid table-responsive">
                                <table class="table table-striped shadow text-center">
                                    <thead>
                                        <tr class="">
                                            <th>ID</th>
                                            <th>N° Room</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            <th>N° Adult</th>
                                            <th>N° Children</th>
                                            <th>Status</th>
                                            <th>Room</th>
                                            <th>Pension</th>
                                            <th>Value</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="reservations.length === 0">
                                            <td colspan="10">No such reservation added yet.</td>
                                        </tr>
                                        <tr class="text-center" v-for="reservation in reservations" :key="reservation.id">
                                            <td>{{ reservation.id }}</td>
                                            <td>{{ reservation.room?.room_number }}</td>
                                            <td>{{ reservation.checkin }}</td>
                                            <td>{{ reservation.checkout }}</td>
                                            <td>{{ reservation.nbadulte }}</td>
                                            <td>{{ reservation.nbenfants }}</td>
                                            <td>
                                                <span v-if="reservation.status === 'en attente'" class="badge bg-warning text-light">
                                                {{reservation.status }}</span>
                                                <span v-if="reservation.status === 'completed'" class="badge bg-success text-light">
                                                {{reservation.status }}</span>
                                                <span v-if="reservation.status === 'confirmed'" class="badge bg-success text-light">
                                                {{reservation.status }}</span>
                                                <span v-if="reservation.status === 'deleted'" class="badge bg-danger text-light">
                                                {{reservation.status }}</span>
                                                <span v-if="reservation.status === 'cancelled'" class="badge bg-danger text-light">
                                                {{reservation.status }}</span>
                                            </td>
                                            <td>{{ reservation.room_type }}</td>
                                            <td>{{ reservation.pension }}</td>
                                            <td>{{ reservation.total_cost }} DT</td>
                                            <td v-if="currentDate >= convertToDate(reservation.checkin)">
                                                <button @click="addrec(reservation.id)" type="submit" 
                                                class="btn btn-outline-warning rounded mx-2"><i class="fa-solid fa-plus"></i> Complaint</button>
                                            </td>
                                            <td v-else>
                                                <button type="button" @click="showMsj()"
                                                class="btn btn-outline-warning rounded mx-2" ><i class="fa-solid fa-plus" ></i>Complaint</button>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                            <span class=" text-danger">* If your reservation not confirmed within 12 hours or their status was cancelled or deleted, your money will refunded in 48 hours working days </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
const router = useRouter();
const isLoading = ref(true)
import { useToast } from 'vue-toastification';
const toast = useToast();
const reservations = ref([]);
const user = ref({});
const currentDate = new Date();
currentDate.setHours(0, 0, 0, 0);

const convertToDate = (dateString) => {
  const date = new Date(dateString);
  date.setHours(0, 0, 0, 0);
  return date;
};
// get user data from localStorage
const storedUser = localStorage.getItem('user');
if (storedUser) {
    user.value = JSON.parse(storedUser);
}
const id_user = user.value.id
const showMsj = () =>{
    toast.warning("You can conplain where your CheckIn start");
} 
const fetchReservations = async () => {
    await axios.get(`http://localhost:8000/api/reservationHistory/${id_user}`)
        .then(res => {
            reservations.value = res.data;
            isLoading.value = false;
        })
        .catch(error => {
            console.error('Error fetching reservations:', error);
        })
};
const addrec = (recId) => {
    router.push({ name: 'AddReclamation', params: { idres: recId } });
} 
onMounted(() => {
    fetchReservations();
});
</script>

<style lang="css">
.bgres{
    background-image: url('../../../../../public/userAssets/images/hotel-reservation.jpg');

}
</style>