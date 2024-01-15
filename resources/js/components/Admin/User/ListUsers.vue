<template>
    <Header></Header>
    <div>
        <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class=" spinner-border " style="color: rgb(0, 150, 136);;"></div>
        </div>
        <div v-else  class="page-wrapper bg">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="mt-5">
                                <h4 class="card-title float-left mt-2">All Users</h4>
                                <router-link  to="/blackListUsers" class="btn btn-dark float-right "><i class="fa-solid fa-skull-crossbones"></i> Black List</router-link>

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
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="user in users" :key="user.id">
                                                <td>{{ user.name }}</td>
                                                <td>{{ user.email }}</td>
                                                <td>{{ user.phone }}</td>
                                                <td>{{ user.adresse }}</td>
                                                <td>
                                                    <span v-if="user.banned" class="badge bg-danger"
                                                        style="color: aliceblue;">Banned</span>
                                                    <span v-else class="badge "
                                                        style="background-color: rgb(1, 177, 1);color: aliceblue;">Active</span>
                                                </td>
                                                <td>
                                                    <button v-if="!user.banned" @click="toggleBanStatus(user)"
                                                        class="btn btn-danger">
                                                        <i class="fa-solid fa-ban"></i> Ban
                                                    </button>
                                                    <button v-if="user.banned" @click="toggleBanStatus(user)"
                                                        class="btn btn-info">
                                                        <i class="fa-solid fa-dove"></i> Unban
                                                    </button>
                                                </td>
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
import Header from "../layouts/Header.vue";
import axios from 'axios';

const isLoading = ref(true)
const users = ref([]);

const fetchUsers = async () => {
    try {
        const response = await axios.get('http://localhost:8000/api/users');
        users.value = response.data;
        isLoading.value = false; 

    } catch (error) {
        console.error('There was a problem fetching the user list:', error);
    }
};

const toggleBanStatus = async (user) => {
    try {
        const response = await axios.put(`http://localhost:8000/api/users/${user.id}/toggle-ban`);
        user.banned = !user.banned;
        console.log(response.data.message);
    } catch (error) {
        console.error('There was an error updating the ban status:', error);
    }
};

onMounted(fetchUsers);
</script>

<style>/* Add your CSS here */</style>