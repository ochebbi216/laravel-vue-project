
<template>
    <div class="bgim2"></div>

    <div class="container vh-100">
        <div class="row align-items-center h-100">
            <div class="col-md-5 mx-auto">
                <div class="jumbotron" style="background-color: rgba(255, 255, 255, 0.217);">
                    <h1 class="text-center text-white">Register</h1>
                    <form @submit.prevent="handleSubmit">

                        <div class="form-group">
                            <label for="name" class="text-white">Name</label>
                            <input id="name" type="text" class="form-control rounded-pill" v-model="user.name" required
                                autofocus>
                        </div>

                        <div class="form-group">
                            <label for="email" class="text-white">Email Address</label>
                            <input id="email" type="email" class="form-control rounded-pill" v-model="user.email" required>
                        </div>

                        <div class="form-group">
                            <label for="password" class="text-white">Password</label>
                            <input id="password" type="password" class="form-control rounded-pill" v-model="user.password"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="text-white">Phone Number</label>
                            <input id="phone" type="number" class="form-control rounded-pill" v-model="user.phone" required>
                        </div>

                        <div class="form-group">
                            <label for="adresse" class="text-white">Address</label>
                            <input id="adresse" type="text" class="form-control rounded-pill" v-model="user.adresse"
                                required>
                        </div>
                        <div class="float-right">
                        <router-link class="text-white " :to="{ name: 'Login' }">Have an account?</router-link>
                        </div>
                        <br>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill">&nbsp; Register &nbsp;</button>
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
const toast = useToast();
const router = useRouter();
const user = ref({});

const handleSubmit = async () => {
    try {
        const response = await axios.post('http://localhost:8000/api/user/register', user.value);
        console.log(response);
        // Assuming that upon successful registration, you want to redirect to the login page.
        router.push({ name: 'Login' });
        toast.success("Your Account was created successfully!");
    } catch (err) {
        console.error(err.response.data); // Log the error response from server
        toast.error(err.response.data.error);
    }
};
</script>
  
<style>
/* Background image styles */
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
}
</style>