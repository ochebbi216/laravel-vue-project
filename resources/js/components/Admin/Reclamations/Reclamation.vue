<template>
        <Header></Header>
    <div>
        <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class=" spinner-border " style="color: rgb(0, 150, 136);"></div>
        </div>
        <div v-else class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="mt-5">
                                <h4 class="card-title float-left mt-2">All Reclamations</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table">
                            <div class="card-body booking-card">
                                <div class="table-responsive">
                                    <table class="datatable table table-stripped table table-hover table-center mb-0">

                                        <thead>
                                            <!-- L'en-tête du tableau avec les noms des colonnes -->
                                            <tr>
                                                <th>Reservation ID</th>
                                                <th>Customer</th>
                                                <th>Message</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Actions</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Boucle sur chaque chambre, en utilisant 'v-for' pour créer une ligne par chambre -->
                                            <tr v-for="rec in reclamations" :key="rec.id">
                                                <td>{{ rec.reservation_id }}</td>
                                                <td>{{ rec.user?.name }}</td>
                                                <td>{{ rec.message }}</td>
                                                <td>
                                                    <span v-if="!rec.status" class="badge bg-danger"
                                                        style="color: aliceblue;">Not readed</span>
                                                    <span v-else class="badge "
                                                        style="background-color: rgb(1, 177, 1);color: aliceblue;">Complete</span>
                                                </td>
                                                <td>{{ formatDate(rec.created_at) }}</td>
                                                <td><button class="btn btn-outline-warning mx-2" @click="Details(rec.id)"><i
                                                            class="fa-regular fa-pen-to-square"></i> Details</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Header from "../../../layouts/Header.vue";
const router = useRouter();

const isLoading = ref(true)
const reclamations = ref([]);

const formatDate = (datetime) => {
    return datetime.replace('T', ' ').slice(0, -8);
};

const fetchReclamations = async () => {
    await axios.get('http://localhost:8000/api/reclamation')
        .then(res => {
            reclamations.value = res.data;
            isLoading.value = false;
        })
        .catch(error => {
            console.error('Error fetching reclamations:', error);
        })
};
const updatestatusReclamations = async (id) => {
    await axios.put(`http://localhost:8000/api/reclamations/${id}`)
        .then(res => {
            console.log(res);
        })
        .catch(error => {
            console.error('Error fetching reclamations:', error);
        })
};
const Details = (id) => {
    updatestatusReclamations(id);
    router.push({ name: 'detailsrec', params: { id } });
    
};

onMounted(() => {
    fetchReclamations();
});
</script>

<style scoped>

</style>