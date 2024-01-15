<template>
  <Header></Header>
  <div>
    <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 100vh;">
      <div class="spinner-border" style="color: rgb(0, 150, 136);"></div>
    </div>
    <div v-else class="page-wrapper bg">
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
                        <td v-if="room.description.length > 15">{{ room.description.slice(0, 15) }}...</td>
                        <td v-else>{{ room.description }}</td>
                        <td>
                          <span v-if="isAvailable(room.id)" class="badge"
                            style="background-color: rgb(1, 177, 1); color: aliceblue;">Yes</span>
                          <span v-else class="badge bg-danger" style="color: aliceblue;">No</span>
                        </td>
                        <!-- Boutons pour éditer et supprimer la chambre correspondante -->
                        <td ><button v-if="isAvailable(room.id)" class="btn btn-outline-warning mx-2" @click="editRoom(room.id)"><i
                              class="fa-regular fa-pen-to-square"></i> Update</button>
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
  <div :class="{ 'modal-open': deleteModalVisible }">
    <div class="modal" :class="{ 'show': deleteModalVisible }">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Delete Room</h5>
            <button type="button" class="btn-close" @click="cancelDelete"></button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete this room?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary " @click="cancelDelete">Cancel</button>
            <button type="button" class="btn btn-danger " @click="confirmDelete()">Delete</button>
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
import Header from "../../../layouts/Header.vue";

const deleteModalVisible = ref(false);
const selectedRoomId = ref(null);
const isLoading = ref(true)

const rooms = ref([]);
const reservations = ref([]);
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
const fetchReservations = async () => {
  await axios.get('http://localhost:8000/api/reservations')
    .then(res => {
      reservations.value = res.data;
    })
    .catch(error => {
      console.error('Error fetching reservations:', error);
    })
};

onMounted(() => {
  getRooms();
  fetchReservations();
});
const isAvailable = (roomId) => {
  const currentDate = new Date();

  // Look for a reservation that overlaps with the current date for the given room ID
  const roomUnavailable = reservations.value.some((reservation) => {
    if (reservation.deleted_at === null) {
      return reservation.id_room === roomId &&
        new Date(reservation.checkin) <= currentDate &&
        currentDate <= new Date(reservation.checkout);
    }
  });

  // If there's no overlapping reservation, the room is available
  return !roomUnavailable;
};
const editRoom = (id) => {
  router.push({ name: 'editroom', params: { id } });
};

// const deleteRoom = async (id) => {
//   // if (window.confirm("Are You sure ?")) {
//     try {
//       await axios.delete(`http://localhost:8000/api/rooms/${id}`);
//       getRooms(); 
//     } catch (error) {
//       console.log(error);
//     }
// };
const showDeleteModal = (roomId) => {
  selectedRoomId.value = roomId;
  deleteModalVisible.value = true;
};

const cancelDelete = () => {
  deleteModalVisible.value = false;
};

const confirmDelete = async () => {
  const roomIdToDelete = selectedRoomId.value;
  
  if (roomIdToDelete) {
    try {
      await axios.delete(`http://localhost:8000/api/rooms/${roomIdToDelete}`);
      getRooms(); // Refresh the list after deletion
      deleteModalVisible.value = false;
    } catch (error) {
      console.log(error);
    }
  }
};
</script>
  
<style lang="css" scoped>
.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  /* z-index: 1000; */
  justify-content: center;
  align-items: center;
}

.modal.show {
  display: flex;
}

.modal-dialog {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
  max-width: 400px;
  width: 80%;
  padding: 20px;
  position: relative;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 10px;
  border-bottom: 1px solid #ccc;
}

.modal-title {
  font-size: 1.25rem;
  font-weight: bold;
}

.modal-body {
  margin-bottom: 20px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
}

.btn-close {
  background: none;
  border: none;
  font-size: 1.25rem;
  cursor: pointer;
}
</style>
