<template>

    <div class="bgiadd"></div>
        <div class="hero-wrap ">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text d-flex align-itemd-center justify-content-center">
                    <div class="col-md-9 ftco-animate text-center d-flex align-items-end justify-content-center">
                        <div class="text">
                            <h3 class="mb-4 bread">No need more than 2 min to get your Reservation ! </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-5 mx-auto">
                <br> 
                <div class="jumbotron" style="background-color: rgba(255, 255, 255, 0);">
                    <h1 class="text-center text-white">Start A New Adventure</h1>
                    <form @submit.prevent="addPayment">
                        <!--  -->
                        <div class="form-group">
                            <label for="datein" class="text-white">From:</label>
                            <input id="datein" type="date" class="form-control rounded" v-model="reservation.checkin" :min="minCheckinDate" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="dateout" class="text-white">To:</label>
                            <input id="dateout" type="date" class="form-control rounded" v-model="reservation.checkout" :min="minCheckoutDate" required>
                        </div>

                        <div class="form-group">
                            <label for="Adults" class="text-white">N° Adults </label>
                            <input id="Adults" type="number" class="form-control rounded" v-model="reservation.nbadulte"  min="1" max="5" required>
                        </div>

                        <div class="form-group">
                            <label for="Childs" class="text-white"> N° Childs:</label>
                            <input id="Childs" type="number" class="form-control rounded" v-model="reservation.nbenfants"  required>
                        </div>
                        <div class="form-group">
                            <label class="text-white">Pension:</label>
                            <select class="form-control rounded" v-model="reservation.pension" id="pension">
                                <option value="lpd">LPD</option>
                                <option value="demi-pension">Demi Pension</option>
                                <option value="pension-complete">Pension Complete</option>
                                <option value="all-inclusive">All Inclusive</option>
                            </select>
                        </div>
                        <br>

                        <div v-if="!show" class="text-center mt-4">
                            <button type="button" @click="openPayment" class="btn btn-primary rounded-pill">&nbsp; Next <i class="fa-solid fa-arrow-right"></i>&nbsp;</button>
                        </div>

                        <div v-if="show" class="text-center mb-2">
                            <button type="button" @click="openPayment" class="btn btn-primary rounded-pill">&nbsp; Recalculate <i class="fa-solid fa-arrow-right"></i>&nbsp;</button> 
                            <h5 class="card-title text-white mb-2">Amount to be paid: <span class="text-success"><b>{{amount}}</b></span> TND</h5>
                        </div>
                        <br>
                        <div class="form-text rounded bg-light " id="card-element" v-show="show"></div>
                        <!-- <div v-if="show">
                        <div  class="form-text rounded bg-light " id="card-element" ></div>
                        </div> -->
                        <div v-if="show" class="text-center mt-4">
                            <button type="submit"  class="btn btn-primary rounded-pill">&nbsp; Finish <i class="fa-solid fa-arrow-right"></i>&nbsp;</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref, computed, onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { loadStripe } from '@stripe/stripe-js';
import { differenceInDays, parseISO } from 'date-fns';
import { useToast } from 'vue-toastification';
const toast = useToast();
const today = new Date();
const tomorrow = new Date(today);
tomorrow.setDate(tomorrow.getDate() + 1);
const stripePromise = loadStripe('pk_test_51OM8GkJ0i7lN6KEIfNiMN5F9ZRLSkdm1zelTqp17SXx8NsdIpEA63pk7Qm1J9kpkwUwhmWUcITDLEukpjk2cpBML00s5I5yqKI');
const router = useRouter();
const route = useRoute();
const show = ref(false);
const stripe = ref(null);
const user = ref({});
// get user data from localStorage
const storedUser = localStorage.getItem('user');
if (storedUser) {
  user.value = JSON.parse(storedUser);
}

const roomType = ref('');
const amount = ref(0);
let cardElement; // Declare cardElement as a global variable

const roomId = route.params.roomId;

const id_user = user.value.id
const reservation = ref({
  id_room: roomId,
  id_user: id_user ,
  checkin: '',
  checkout: '',
  nbadulte: 1,
  nbenfants: 0,
  pension: 'lpd',

});


const validateOccupancy = () => {
  const totalPeople = reservation.value.nbadulte + reservation.value.nbenfants;
  let maxOccupancy;

  switch (roomType.value) {
    case 'Single':
      maxOccupancy = 1;
      break;
    case 'Double':
      maxOccupancy = 2;
      break;
    case 'Triple':
      maxOccupancy = 3;
      break;
    case 'Suite':
      maxOccupancy = 5;
      break;
    default:
      toast.error("Invalid room type selected.");
      return false;
  }

  if (totalPeople > maxOccupancy) {
    toast.error(`Maximum occupancy for ${roomType.value} room is ${maxOccupancy} people.`);
    return false;
  }

  return true;
};

const openPayment = () => {
    show.value = true;
    calculateAmount(); // Calculate amount when opening payment

}
const dailyRates = {
    'Single': {
        'lpd': 80,  // Lower Pension Daily
        'demi-pension': 100,  // Half-board
        'pension-complete': 150,  // Full-board
        'all-inclusive': 200,  // All-inclusive
    },
    'Double': {
        'lpd': 100,
        'demi-pension': 120,
        'pension-complete': 170,
        'all-inclusive': 220,
    },
    'Triple': {
        'lpd': 120,
        'demi-pension': 140,
        'pension-complete': 190,
        'all-inclusive': 240,
    },
    'Suite': {
        'lpd': 160,
        'demi-pension': 180,
        'pension-complete': 230,
        'all-inclusive': 300,
    },
    // Add more room types if necessary
};

const roomDetails = ref({});

onMounted(async () => {
    try {
        const response = await axios.get(`http://localhost:8000/api/rooms/${roomId}`);
        roomDetails.value = response.data;
        roomType.value = roomDetails.value.room_type; // Assign room type from fetched data
    } catch (error) {
        console.error('Error fetching room details:', error);
    }
    stripe.value = await stripePromise;
    const elements = stripe.value.elements();
    cardElement = elements.create('card'); // Affecter cardElement
    cardElement.mount('#card-element');


});

const calculateAmount = () => {
  if (reservation.value.checkin && reservation.value.checkout && reservation.value.pension) {
    const checkIn = parseISO(reservation.value.checkin);
    const checkOut = parseISO(reservation.value.checkout);
    const duration = differenceInDays(checkOut, checkIn);
    const rateKey = reservation.value.pension; 
    const dailyRate = dailyRates[roomType.value][rateKey];
    amount.value = duration * dailyRate * reservation.value.nbadulte;
  }
};

const addPayment = async () => {
    // const { token, error } = await stripe.value.createToken(cardElement);
    // if (error) {
    //     console.error(error);
    // } else {
    //     // Envoie le token au serveur pour traiter le paiement
    //     const response = await axios.post('http://localhost:8000/api/createpayment', {
    //         token: token.id,
    //         amount: amount.value * 100, // Convertir le montant en cents
    //         email: user.value.email
    //     });
    //     if (response.data.message) {
    //         console.log(response.data.message);
    //         // Le paiement est réussi
    //         alert(response.data.message);

    //     } else {

    //         console.error(response.data.error);
    //     }
    // }
    try {
        validateOccupancy();
        await axios.post('http://localhost:8000/api/reservations', reservation.value);
        toast.success("Reservation was added successfully");
        router.push('/reservation');

    } catch (error) {
        console.error(error);
        toast.error("This Room not available between those CheckIn and CheckOut ");
    }
};
</script>
<!-- // Initialiser le chargement de Stripe et la création du champ de carte
// onMounted(async () => {
//     stripe.value = await stripePromise;
//     const elements = stripe.value.elements();
// }); -->

<style lang="css" scoped>
.bgiadd {
    background-image: url('../../../../../public/userAssets/images/nature3.jpg');
    opacity: 0.9;
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

#card-element {
    height: 50px; /* Adjust the height as needed */
    padding-top: 17px;
}
</style>