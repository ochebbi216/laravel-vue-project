<template>
    <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class=" spinner-border " style="color: rgb(33, 204, 122);"></div>
    </div>    
    <div v-else>
        <div class="hero-wrap bgrec">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text d-flex align-itemd-center justify-content-center">
                    <div class="col-md-9 ftco-animate text-center d-flex align-items-end justify-content-center">
                        <div class="text">
                            <h3 class="mb-4 bread">Complaint Details </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<div class="row justify-content-center" style="height: 100vh;">
    <div class="col-auto">
        <div v-if="reclamation">
            <table class="table table-borderless ">
                <tbody>
                    <tr>
                        <th>Complaint ID</th>
                        <td>{{ reclamation.id }}</td>
                    </tr>
                    <tr>
                        <th>Reservation ID</th>
                        <td>{{ reclamation.reservation_id }}</td>
                    </tr>
                    <tr>
                        <th>Check In</th>
                        <td>{{ reclamation.reservation?.checkin }}</td>
                    </tr>
                    <tr>
                        <th>Check Out</th>
                        <td>{{ reclamation.reservation?.checkout }}</td>
                    </tr>
                    <tr>
                        <th>Adults Number</th>
                        <td>{{ reclamation.reservation.nbadulte }}</td>
                    </tr>
                    <tr>
                        <th>childs Number</th>
                        <td>{{ reclamation.reservation.nbenfants }}</td>
                    </tr>
                    <tr>
                        <th>Room type</th>
                        <td>{{ reclamation.reservation.room_type }}</td>
                    </tr>
                    <tr>
                        <th>Pension</th>
                        <td>{{ reclamation.reservation.pension }}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>{{ reclamation.reservation.total_cost }}</td>
                    </tr>
                    <!-- <tr>
                        <th>Customer</th>
                        <td>{{ reclamation.user?.name }}</td>
                    </tr> -->
                    <tr>
                        <th>Message</th>
                        <td style="color: tomato;">{{ reclamation.message }}</td>
                    </tr>
                    <tr>
                        <th>Created at</th>
                        <td>{{ formatDate(reclamation.created_at) }}</td>
                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else>
            <p>Aucune réclamation trouvée pour ce id.</p>
        </div>
    </div>
</div>
    </div>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';
import apiConfig from '../../../apiConfig';

const reclamation = ref(null);
const route = useRoute();
const isLoading = ref(true)

const formatDate = (datetime) => {
return datetime.replace('T', ' ').slice(0, -8);
};
const loadReclamationDetails = async () => {
try {
    const res = await axios.get(`${apiConfig.API_BASE_URL}/reclamations/${route.params.id}`);
    reclamation.value = res.data;
    isLoading.value = false;
} catch (err) {
    console.error(err);
}
};
onMounted(loadReclamationDetails);
</script>

<style scoped>
</style>