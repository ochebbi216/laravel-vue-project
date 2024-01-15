<template>
        <Header></Header>
    <div class="page-wrapper">
        <div class="content container-fluid bg">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h2 v-if="reclamation" class="card-title text-center mt-2">Details Complaint ID: {{ reclamation.id }} </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <br>
                    <div v-if="reclamation">
                        <!-- <p><strong>Complaint ID :</strong> {{ reclamation.id }}</p> -->
                        <p><strong>Reservation ID:</strong> {{ reclamation.reservation_id }}</p>
                        <p><strong>Check In:</strong> {{ reclamation.reservation?.checkin }} </p>
                        <p><strong>Check Out:</strong> {{ reclamation.reservation?.checkout }} </p>
                        <p><strong>N° Adults:</strong> {{ reclamation.reservation.nbadulte }} </p>
                        <p><strong>N° childs:</strong> {{ reclamation.reservation.nbenfants }} </p>
                        <p><strong>Room type:</strong> {{ reclamation.reservation.room_type }} </p>
                        <p><strong>Pension:</strong> {{ reclamation.reservation.pension }} </p>
                        <p><strong>Price:</strong> {{ reclamation.reservation.total_cost }} DT </p>
                        <p><strong>Customer:</strong> {{ reclamation.user?.name }}</p>
                        <p><strong>Phone Number:</strong> <a href="tel:{{ reclamation.user?.phone }}">{{ reclamation.user?.phone }}</a> </p>
                        <p><strong>Email:</strong> <a :href="`mailto:${reclamation.user?.email}`">{{ reclamation.user?.email }}</a></p>
                        <p><strong>Adresse:</strong> {{ reclamation.user?.adresse }}</p>
                        <p><strong>Message:</strong> <span style="color: tomato;">{{ reclamation.message }}</span> </p>
                        <p><strong>Created at:</strong> {{ formatDate(reclamation.created_at) }}</p>
                    </div>
                    <div v-else>
                        <p>Aucune réclamation trouvée pour ce id.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';
import Header from "../layouts/Header.vue";

const reclamation = ref(null);
const route = useRoute();
const isLoading = ref(true)

const formatDate = (datetime) => {
    return datetime.replace('T', ' ').slice(0, -8);
};
const loadReclamationDetails = async () => {
    try {
        const res = await axios.get(`http://localhost:8000/api/reclamations/${route.params.id}`);
        reclamation.value = res.data;
    } catch (err) {
        console.error(err);
    }
};
onMounted(loadReclamationDetails);
</script>
  
<style scoped>
</style>