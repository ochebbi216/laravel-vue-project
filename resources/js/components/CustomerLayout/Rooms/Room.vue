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
                <div class="text-center">
                    <button class="btn pt-1" @click="openFilter"><i class="fa-solid fa-filter"></i> Filter <i v-if="!show"
                            class="fa-solid fa-angle-down"></i> <i v-else class="fa-solid fa-angle-up"></i> </button>
                </div>
                <br>
                <br>
                <div v-if="show" class="text-center  border-dark">
                    <!-- Corrected Bootstrap classes -->
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" id="singleCheck" value="Single"
                            v-model="selectedCategories">
                        <label class="form-check-label" for="singleCheck">Single</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" id="doubleCheck" value="Double"
                            v-model="selectedCategories">
                        <label class="form-check-label" for="doubleCheck">Double</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" id="tripleCheck" value="Triple"
                            v-model="selectedCategories">
                        <label class="form-check-label" for="tripleCheck">Triple</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" id="suiteCheck" value="Suite"
                            v-model="selectedCategories">
                        <label class="form-check-label" for="suiteCheck">Suite</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" id="availableCheck" @change="toggleAvailable">
                        <label class="form-check-label" for="availableCheck">Available Only</label>
                    </div>
                </div>
                <br>
                <div class="row no-gutters">
                    <div v-for="(room, index) in filteredRooms" :key="room.id" class="col-lg-6">
                        <div class="room-wrap d-md-flex">
                            <!-- Use dynamic class binding to alternate image positions -->
                            <a :href="room.detailsLink" :class="{ 'order-md-last': index % 2 >= 1 }" class="img"
                                :style="{ 'background-image': 'url(' + room.image + ')' }"></a>
                            <!-- Use dynamic class binding to alternate arrow styles -->
                            <div
                                :class="[index % 2 >= 1 ? 'half right-arrow d-flex align-items-center' : 'half left-arrow d-flex align-items-center']">
                                <div class="text p-4 text-center">
                                    <p class="star mb-0">
                                        <!-- Render stars -->
                                        <span class="ion-ios-star" v-for="n in getRoomStars(room.room_type)"
                                            :key="n"></span>
                                    </p>
                                    <p class="mb-0">
                                        <!-- Show placeholders for illustration, adjust as needed -->
                                        <span class="price mr-1">From {{ getRoomPrice(room.room_type) }} DT</span> <span
                                            class="per">per night</span>
                                    </p>
                                    <h3 class="mb-3"><a href="">{{ room.room_type }}</a></h3>

                                    <p class="pt-1">
                                        <a @click="Details(room.id)" href="" class="btn-custom px-3 py-2 rounded"> View
                                            Details <i class="fa-solid fa-circle-info"></i></a>
                                    </p>
                                    <p v-if="!isAvailable(room.id)" class="pt-1 text-danger">
                                        Available from <span>{{ getNextAvailableDate(room.id) }}</span>
                                    </p>
                                    <p v-else class="pt-1">
                                        <a href="" class="btn-info active px-3 py-2 rounded">Book Room Now!
                                            <i class="fa-solid fa-circle-arrow-right"></i></a>
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
const selectedCategories = ref(['Single', 'Double', 'Triple', 'Suite']);
const onlyAvailable = ref(false);
// const isLoading = ref(true)
const rooms = ref([]);
const reservations = ref([]);
const router = useRouter();
let show = ref(false);
const openFilter = () => {
    show.value = !show.value;
}

const getRooms = async () => {
    await axios.get("http://localhost:8000/api/rooms")
        .then(res => {
            rooms.value = res.data;
            // isLoading.value = false;

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
const toggleAvailable = () => {
    onlyAvailable.value = !onlyAvailable.value;

    if (!onlyAvailable.value && selectedCategories.value.includes('available')) {
        // If Available is unchecked, remove it from the selected categories.
        selectedCategories.value = selectedCategories.value.filter(c => c !== 'available');
    } else if (onlyAvailable.value && !selectedCategories.value.includes('available')) {
        // If Available is checked, ensure to track it in selected categories to maintain the UX consistency.
        selectedCategories.value.push('available');
    }
};
const getNextAvailableDate = (roomId) => {
    let nextAvailableDate = new Date(); // Start with the current date

    for (let reservation of reservations.value.filter(r => r.id_room === roomId)) {
        const checkoutDate = new Date(reservation.checkout);
        if (checkoutDate > nextAvailableDate) {
            nextAvailableDate = checkoutDate;
        }
    }

    // Add one day to the last checkout date
    nextAvailableDate.setDate(nextAvailableDate.getDate() + 1);

    return nextAvailableDate.toISOString().slice(0, 10); // Format date as 'YYYY-MM-DD'
};

const filteredRooms = computed(() => {
    let roomsFilteredByAvailability = onlyAvailable.value
        ? rooms.value.filter(room => isAvailable(room.id))
        : rooms.value;

    if (selectedCategories.value.includes('available')) {
        // Remove 'available' keyword from room types as it's not a room type
        selectedCategories.value = selectedCategories.value.filter(category => category !== 'available');
    }

    // If no categories are selected, or the 'All' option is selected, return rooms based on the availability
    return selectedCategories.value.length === 0
        ? roomsFilteredByAvailability
        : roomsFilteredByAvailability.filter(room => selectedCategories.value.includes(room.room_type));
});
const availableRooms = computed(() => {
    return rooms.value;
    // return rooms.value.filter(room => isAvailable(room.id));

});
const Rooms = computed(() => {
    return rooms.value;
    // return rooms.value.filter(room => isAvailable(room.id));

});
const Details = (id) => {
    router.push({ name: 'RoomDetails', params: { id } });

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