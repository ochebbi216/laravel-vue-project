<template>
        <Header></Header>
    <div class="page-wrapper">
        <div class="content container-fluid bg">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h2 v-if="reclamation" class="card-title float-left mt-2"> Reclamation : {{ reclamation.id }} </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h4>Détails de la Réclamation:</h4>
                    <div v-if="reclamation">
                        <p><strong>ID de la réclamation :</strong> {{ reclamation.id }}</p>
                        <p><strong>ID de la réservation :</strong> {{ reclamation.reservation_id }}</p>
                        <p><strong>ID de l'utilisateur :</strong> {{ reclamation.user?.name }}</p>
                        <p><strong>Message :</strong> {{ reclamation.message }}</p>
                        <p><strong>Date de création :</strong> {{ formatDate(reclamation.created_at) }}</p>
                        <!-- Ajoutez d'autres détails selon vos besoins -->
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
import Header from "../../../layouts/Header.vue";

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