<template>
    <div class="bgim2"></div>

    <div class="container vh-100 ">
        <div class="row align-items-center h-100">
            <div class="col-md-5 mx-auto">
                <div class="jumbotron" style="background-color: rgba(255, 255, 255, 0.217);">
                    <h1 class="text-center text-white">Login</h1>
                    <form @submit.prevent="handleLogin">
                        <div class="form-group">
                            <label for="email" class="text-white">Email Address</label>
                            <input id="email" type="email" class="form-control rounded-pill " v-model="user.email" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-white">Password</label>
                            <input id="password" type="password" class="form-control rounded-pill " v-model="user.password"
                                required>
                        </div>
                        <div class="float-right">
                            <router-link class="text-white" :to="{ name: 'Register' }">Not registered yet?</router-link>
                        </div>
                        <br>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill"> &nbsp; Login &nbsp; </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import axios from 'axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import apiConfig from '../../../apiConfig';

const toast = useToast();
// const isLoading = ref(false)

const router = useRouter();
const user = ref({ email: '', password: '' }); // initialize the reactive object with properties

const handleLogin = async () => {
    // isLoading.value = true;

    try {
        const res = await axios.post(`${apiConfig.API_BASE_URL}/user/login`, user.value);
        
        if(res.data.user.banned){
            toast.error("Your account has been banned!");
            return;
        }
        // Save the user's information and token in localStorage
        localStorage.setItem('user', JSON.stringify(res.data.user));
        localStorage.setItem('token', res.data.token);
        
        // Redirect the user after login
        router.push({ name: 'Acceuil' }); // assuming the route name after login is 'Dashboard'
    } catch (err) {
        console.error(err);
        toast.error("Invalid credentials");

    }
};
</script>
  
<style>
.bgim2 {
    background-image: url('../../../../../public/userAssets/images/bg_4.jpg');
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
}</style>