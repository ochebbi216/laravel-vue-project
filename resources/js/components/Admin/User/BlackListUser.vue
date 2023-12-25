<template>
        <Header></Header>
    <div>
        <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class=" spinner-border " style="color: rgb(0, 150, 136);;"></div>
        </div>
        <div v-else  class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left mt-2">Black List </h4>
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
                                            <!-- <th>Remove from Blacklist</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="user in bannedUsers" :key="user.id">
                                            <td>{{ user.name }}</td>
                                            <td>{{ user.email }}</td>
                                            <td>{{ user.phone }}</td>
                                            <td>{{ user.adresse }}</td>
                                            <!-- <td>
                                                <button 
                                                    @click="removeFromBlacklist(user)" 
                                                    class="btn btn-success">
                                                    Unban
                                                </button>
                                                </td> -->
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
    <!-- <br><br><br> -->
</template>
  
<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import Header from "../../../layouts/Header.vue";

const bannedUsers = ref([]);
const isLoading = ref(true)

const fetchUsers = async () => {
    try {
        const response = await axios.get('http://localhost:8000/api/users');
        bannedUsers.value = response.data.filter(user => user.banned === 1);
        isLoading.value = false; 

    } catch (error) {
        console.error('There was a problem fetching the user list:', error);
    }
};

onMounted(fetchUsers);
</script>
  
<style>/* Add your CSS here */</style>