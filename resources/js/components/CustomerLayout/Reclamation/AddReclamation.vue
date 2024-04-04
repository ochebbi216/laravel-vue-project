<template>
    <div>
        <div class="bgiaddrec"></div>
        <div class="hero-wrap ">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text d-flex align-itemd-center justify-content-center">
                    <div class="col-md-9 ftco-animate text-center d-flex align-items-end justify-content-center">
                        <div class="text">
                            <h3 class="mb-4 bread">Adding Reclamation..</h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-5 mx-auto">
                <br> 
                <div class="jumbotron" style="background-color: rgba(255, 255, 255, 0);">
                    <h1 class="text-center text-white">Feel free to ask us:</h1>
                    <div class="mt-5">
                            <h4 v-if="id_reservation" class="card-title text-white mt-2">Reservation ID: {{ id_reservation }} </h4>
                        </div>
                    <form @submit.prevent="addReclamation">

                        <div class="form-group">
                            <h5 for="complaint" style="color: rgb(0, 255, 170);"> Enter your complaint here:</h5>
                            <textarea class="form-control" name="complaint" id="complaint" cols="30" rows="10" v-model="reclamation.message"  required></textarea>
                        </div>
                        <br>
                        <div class="text-center mt-4">
                            <button type="submit"  class="btn btn-primary rounded-pill">&nbsp; Finish <i class="fa-solid fa-arrow-right"></i>&nbsp;</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script setup>

import axios from 'axios'
import { ref, computed, onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';
const router = useRouter();
const route = useRoute();
const user = ref({});
const toast = useToast();
const storedUser = localStorage.getItem('user');
if (storedUser) {
    user.value = JSON.parse(storedUser);
}
const id_user = user.value.id
const id_reservation = route.params.idres;
import apiConfig from '../../../apiConfig';
const reclamation = ref({
reservation_id: id_reservation,
user_id: id_user,
message: ''
});

const addReclamation = async () =>{
    try {
        await axios.post(`${apiConfig.API_BASE_URL}/reclamation`,reclamation.value)
        toast.success("Complaint was added successfully");
        router.push('/reclamation');

    } catch (error) {
        console.log(error);
        toast.error("there is an error occurred")
    }
}

</script>

<style lang="css" >
.bgiaddrec {
    background-image: url('../../../../../public/userAssets/images/Complaints.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;
    background-size: cover;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: -1;
}

/* Ensure this .container class does not conflict with Bootstrap's .container class */
.container.vh-100 {
    position: relative;
    z-index: 2;
    /* Higher than the background image */
}
</style>