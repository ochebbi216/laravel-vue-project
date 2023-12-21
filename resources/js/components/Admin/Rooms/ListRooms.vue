<template>
    <div>
        <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class=" spinner-border " style="color: rgb(0, 150, 136);"></div>
        </div>
        <div v-else  class="page-wrapper">
    <div class="content container-fluid">
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <div class="mt-5">
              <h4 class="card-title float-left mt-2">All Rooms</h4>
              <router-link to="/addroom" class="btn btn-primary float-right veiwbutton"><i
                  class="fa-solid fa-circle-plus"></i> Add Room</router-link>
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
                      <th>Image</th>
                      <th>Room Number</th>
                      <th>Room Type</th>
                      <th>Description</th>
                      <th>Disponible</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Boucle sur chaque chambre, en utilisant 'v-for' pour créer une ligne par chambre -->
                    <tr v-for="room in rooms" :key="room.id">
                      <td><img :src="room.image" width="100" height="80" /> </td>
                      <td>{{ room.room_number }}</td>
                      <td>{{ room.room_type }}</td>
                      <td>{{ room.description }}</td>
                      <td>
                        <span v-if="!room.disponible" class="badge bg-danger" style="color: aliceblue;">No</span>
                        <span v-else class="badge " style="background-color: rgb(1, 177, 1);color: aliceblue;">Yes</span>
                      </td>
                      <!-- Boutons pour éditer et supprimer la chambre correspondante -->
                      <td><button class="btn btn-outline-warning mx-2" @click="editRoom(room.id)"><i
                            class="fa-regular fa-pen-to-square"></i> Update</button>
                        <button class="btn btn-outline-danger mx-2" @click="deleteRoom(room.id)"> <i
                            class="fa-regular fa-trash-can"></i> Delete</button>
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
import { useRouter } from 'vue-router';
import axios from 'axios';
const isLoading = ref(true)

const rooms = ref([]);
const router = useRouter();

const getRooms = async () => {
  await axios.get("http://localhost:8000/api/rooms")
    .then(res => {
      rooms.value = res.data;
      isLoading.value = false; 

    })
    .catch(error => {
      console.log(error);
    });
};

onMounted(() => {
  getRooms();
});

const editRoom = (id) => {
  router.push({ name: 'editroom', params: { id } });
};

const deleteRoom = async (id) => {
  try {
    await axios.delete(`http://localhost:8000/api/rooms/${id}`);
    getRooms();  // Refresh the list after deletion
  } catch (error) {
    console.log(error);
  }
};
</script>
  
<style lang="css" scoped>
/* Add your SCSS here */
</style>