<template>
        <Header></Header>
    <div >
        <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class=" spinner-border " style="color: rgb(0, 150, 136);"></div>
        </div>
        <div v-else  class="page-wrapper bg" >
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="mt-5">
                                <h4 class="card-title float-left mt-2">All reservation</h4>
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
                                            <tr class="text-center">
                                                <th>ID</th>
                                                <th>Customer</th>
                                                <th>N°Room</th>
                                                <th>Check In</th>
                                                <th>Check Out</th>
                                                <th>N°Adult</th>
                                                <th>N°Children</th>
                                                <th>Status</th>
                                                <th>Room</th>
                                                <th>Cost</th>
                                                <th>Pension</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="reservations.length === 0">
                                                <td colspan="10">Aucune réservation pour le moment.</td>
                                            </tr>
                                            <tr class="text-center" v-for="reservation in reservations" :key="reservation.id">
                                                <td>{{ reservation.id }}</td>
                                                <td>{{ reservation.user?.name }}</td>
                                                <td v-if="reservation.room?.room_number!= null">{{ reservation.room?.room_number }}</td>
                                                <td v-else class="text-muted">Not Found</td>
                                                <td>{{ reservation.checkin }}</td>
                                                <td>{{ reservation.checkout }}</td>
                                                <td>{{ reservation.nbadulte }}</td>
                                                <td>{{ reservation.nbenfants }}</td>
                                                <td> 
                                                    <select v-if="canDeleteReservation(reservation)" class="form-control" style="width:max-content" v-model="reservation.status">
                                                        <option :value="reservation.status" :disabled="true"> {{
                                                            reservation.status }}</option>
                                                        <option value="confirmed">Confirmed</option>
                                                        <option value="completed">Completed</option>
                                                        <option value="cancelled">Cancelled</option>
                                                    </select>
                                                    <span v-else>{{reservation.status }}</span>
                                                </td>
                                                <td>{{ reservation.room_type }}</td>
                                                <td>{{ reservation.pension }}</td>
                                                <td>{{ reservation.total_cost }} DT</td>
                                                <td>
                                                    <button v-if="canDeleteReservation(reservation)"
                                                        @click="showDeleteModal(reservation.id)"
                                                        class="btn btn-outline-danger mx-2"><i class="fa-regular fa-trash-can"></i></button>
                                                    <span v-else class="text-danger"><b>Not Dispo </b></span>&nbsp; 
                                                    <button v-if="canDeleteReservation(reservation)" class="btn btn-light veiwbutton"
                                                        @click="saveReservation(reservation)"> <i class="fa-solid fa-check"></i></button>
                                                    
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
            <h5 class="modal-title">Delete Reservation</h5>
            <button type="button" class="btn-close" @click="cancelDelete"></button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete this Reservation ?</p>
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
import apiConfig from '../../../apiConfig';
import { ref,onMounted } from 'vue';
import axios from 'axios';
import Header from "../layouts/Header.vue";
const deleteModalVisible = ref(false);
const isLoading = ref(true)
const reservations = ref([]);
const selectedResId = ref(null);

const completStatusReservation = (res) => {
    const currentDate = new Date();
    const checkOutDate = new Date(res.checkout);
     if(checkOutDate < currentDate){
        if (res.status === 'confirmed') {
            res.status = 'completed';
            updateReservationStatus(res.id, 'completed');
        }
    }
}

const canDeleteReservation = (reservation) => {
    const currentDate = new Date();
    const checkInDate = new Date(reservation.checkin);
    const checkOutDate = new Date(reservation.checkout);
    const delDate = reservation.deleted_at ? new Date(reservation.deleted_at) : null;
    completStatusReservation(reservation);
    if (!delDate && checkInDate > currentDate) {
        return true; 
    } else if (checkInDate <= currentDate || checkOutDate < currentDate || reservation.status == 'deleted' ) {

        return false; 
    }
    else if (delDate !== null){
        if (reservation.status !== 'deleted') {
            reservation.status = 'deleted';
            updateReservationStatus(reservation.id, 'deleted');
        }
        return false;
    }
    return true; // Default: return true to handle other cases
};

const fetchReservations = async () => {
    await axios.get(`${apiConfig.API_BASE_URL}/reservations`)
        .then(res => {
            reservations.value = res.data;
            isLoading.value = false; 
        })
        .catch(error => {
            console.error('Error fetching reservations:', error);
        })
};
onMounted(() => {
    fetchReservations();
});
const updateReservationStatus = async (reservationId, status) => {
    await axios.put(`${apiConfig.API_BASE_URL}/reservations/${reservationId}`, { status })
        .then(() => {
            console.log('Status updated successfully.');
        })
        .catch(error => {
            console.error('Error while updating the reservation status:', error);
            // Handle error appropriately
        });
};
const showDeleteModal = (resId) => {
  selectedResId.value = resId;
  deleteModalVisible.value = true;
};

const cancelDelete = () => {
  deleteModalVisible.value = false;
};

const confirmDelete = async () => {
  const resIdToDelete = selectedResId.value;

  if (resIdToDelete) {
    updateReservationStatus(resIdToDelete, 'deleted');

    try{
        await axios.delete(`${apiConfig.API_BASE_URL}/reservations/${resIdToDelete}`);
        fetchReservations();
        deleteModalVisible.value = false;
    } catch (error) {
      fetchReservations();
      deleteModalVisible.value = false;
      console.log(error);
    }
  }
};

const saveReservation = async (reservation) => {
    const originalStatus = reservation.status;
    await axios.put(`${apiConfig.API_BASE_URL}/reservations/${reservation.id}`, { status: reservation.status })
        .then(() => {
            console.log('Status updated successfully.');
            window.location.reload(); 
        })
        .catch(error => {
            console.error('Error while updating the reservation status:', error);
            if (error.response && error.response.data) {
                console.error('Validation errors:', error.response.data.errors);
            }
            // Reverting state in case of failure
            reservation.status = originalStatus;
        });
};
</script>
<style scoped>
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
  max-width: 430px;
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