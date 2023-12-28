<template>
    <div>
        <div class="hero-wrap bgroom">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text d-flex align-itemd-center justify-content-center">
                    <div class="col-md-9 ftco-animate text-center d-flex align-items-end justify-content-center">
                        <div class="text">
                            <h1 class="mb-4 bread">Rooms</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="ftco-section ftco-no-pb ftco-room">
            <div class="container-fluid px-0">
                <div class="row no-gutters justify-content-center mb-5 pb-3">
                    <div class="col-md-7 heading-section text-center ">
                        <span class="subheading">Saha Beach Rooms</span>
                        <h2 class="mb-4">Hotel Master's Rooms</h2>
                    </div>
                </div>
                <div class="row no-gutters">
                    <div v-for="(room, index) in availableRooms" :key="room.id" class="col-lg-6">
                        <div class="room-wrap d-md-flex">
                            <!-- Use dynamic class binding to alternate image positions -->
                            <a :href="room.detailsLink" :class="{ 'order-md-last': index % 2 >= 1 }" class="img" 
                                :style="{ 'background-image': 'url(' + room.image + ')' }"></a>
                            <!-- Use dynamic class binding to alternate arrow styles -->
                            <div :class="[index % 2 >= 1 ? 'half right-arrow d-flex align-items-center' : 'half left-arrow d-flex align-items-center']">
                                <div class="text p-4 text-center">
                                    <p class="star mb-0">
                                        <!-- Render stars -->
                                        <span class="ion-ios-star" v-for="n in getRoomStars(room.room_type)" :key="n"></span>
                                    </p>
                                    <p class="mb-0">
                                        <!-- Show placeholders for illustration, adjust as needed -->
                                        <span class="price mr-1">From {{ getRoomPrice(room.room_type) }} DT</span> <span class="per">per night</span>
                                    </p>
                                    <h3 class="mb-3"><a :href="room.detailsLink">{{ room.room_type }}</a></h3>
                                    <p class="pt-1">
                                        <a :href="room.detailsLink" class="btn-custom px-3 py-2 rounded">View Details <i class="fa-solid fa-arrow-right"></i></a>
                                    </p>
                                    <p class="pt-1">
                                        <a :href="room.bookingLink" class="btn-info active px-3 py-2 rounded">Book Room Now!
                                            <i class="fa-solid fa-arrow-right"></i></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

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
const availableRooms = computed(() => {
    return rooms.value.filter(room => isAvailable(room.id));
});
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
function getRoomPrice(type) {
  switch (type) {
    case 'Single':
      return 80;
    case 'Double':
      return 100;
    case 'Triple':
      return 120;
    case 'Suite':
      return 160;
    default:
      return 0;
  }
}
function getRoomStars(type) {
  switch (type) {
    case 'Single':
      return 3;
    case 'Double':
    case 'Triple':
      return 4;
    case 'Suite':
      return 5;
    default:
      return 0;
  }
}
</script>

<style lang="css">
.bgroom {
    background-image: url('../../../../../public/userAssets/images/room-6.jpg');
}
</style>