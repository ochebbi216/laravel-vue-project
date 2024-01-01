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
                            <input id="email" type="email" class="form-control rounded-pill " v-model="admin.email" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-white">Password</label>
                            <input id="password" type="password" class="form-control rounded-pill " v-model="admin.password"
                                required>
                        </div>
                        <!-- <div class="float-right">
                            <router-link class="text-white" :to="{ name: 'Register' }">Not registered yet?</router-link>
                        </div> -->
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
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
const toast = useToast();
// const isLoading = ref(false)

const router = useRouter();
const admin = reactive({ email: '', password: '' }); // initialize the reactive object with properties

const handleLogin = async () => {
    try {
        const res = await axios.post('http://localhost:8000/api/admin/login', admin);
        if (res.data.status) {
            localStorage.setItem('admintoken', res.data.admintoken);
            toast.success(res.data.message);
            router.push({ name: 'dashboard' });
        } else {
            toast.error(res.data.error || "Login failed");
        }
    } catch (err) {
        console.error(err);
        toast.error(err.response?.data?.error || "Invalid credentials");
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