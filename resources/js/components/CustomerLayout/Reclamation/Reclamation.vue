<template>
        <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class=" spinner-border " style="color: rgb(0, 150, 136);"></div>
        </div>
    <div v-else>
        <div class="hero-wrap bgrec">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text d-flex align-itemd-center justify-content-center">
                    <div class="col-md-9 ftco-animate text-center d-flex align-items-end justify-content-center">
                        <div class="text">
                            <h3 class="mb-4 bread">Complaints List </h3>
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
                                <div class="container-fluid text-center  table-responsive">
                                    <table class="table table-striped shadow " >
                                        <thead>
                                            <tr>
                                                <th>Reservation ID</th>
                                                <th>Message</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="reclamations.length === 0">
                                            <td colspan="5">No such complaints added yet.</td>
                                            </tr>                                            
                                            <tr v-for="rec in reclamations" :key="rec.id" >
                                                <td>{{ rec.reservation_id }}</td>
                                                <td v-if="rec.message.length > 15">{{ rec.message.slice(0, 15) }} ...</td>
                                                <td v-else >{{ rec.message }}</td>
                                                <td>
                                                    <span v-if="!rec.status" class="badge bg-danger"
                                                        style="color: aliceblue;">Not readed</span>
                                                    <span v-else class="badge "
                                                        style="background-color: rgb(1, 177, 1);color: aliceblue;">Complete</span>
                                                </td>
                                                <td>{{ formatDate(rec.created_at) }}</td>
                                                <td><button class="btn btn-outline-warning rounded mx-2" @click="Details(rec.id)">
                                                    <i class="fa-solid fa-circle-info"></i> Details</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <span class=" text-danger">* W'll contact you to understand more your complaints and try to fix them soon as possible &#128512;</span>

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
const user = ref({});
import apiConfig from '../../../apiConfig';
// Immediately attempt to parse the user data from localStorage
const storedUser = localStorage.getItem('user');
if (storedUser) {
  user.value = JSON.parse(storedUser);
}
const id_user = user.value.id

const isLoading = ref(true)
const reclamations = ref([]);

const formatDate = (datetime) => {
    return datetime.replace('T', ' ').slice(0, -8);
};

const fetchReclamations = async () => {
    await axios.get(`${apiConfig.API_BASE_URL}/reclamationHistory/${id_user}`)
        .then(res => {
            reclamations.value = res.data;
            isLoading.value = false;
        })
        .catch(error => {
            console.error('Error fetching reclamations:', error);
        })
};

const Details = (id) => {
    router.push({ name: 'DetailsReclamation', params: { id } });
    
};

onMounted(() => {
    fetchReclamations();
});
</script>

<style lang="css" >
.bgrec{
    background-image: url('../../../../../public/userAssets/images/complaint.jpg');

}
</style>