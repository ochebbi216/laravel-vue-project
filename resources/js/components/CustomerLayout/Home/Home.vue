<template>

    <div >
		<div class="hero bgi ">
			<section class="home-slider ">
				<div class="slider-item ">
					<div class="overlay"></div>
					<div class="container">
						<div class="row no-gutters slider-text align-items-center justify-content-end">
							<div class="col-md-6">
								<div style="margin-top: 39%;">
									<h3 style="color: rgb(255, 234, 172);">More than a hotel... an experience</h3>
									<h1 style="color: whitesmoke;" class="mb-3">Hotel for the whole family, all year round.
									</h1>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="slider-item ">
					<div class="overlay"></div>
					<div class="container">
						<div class="row no-gutters slider-text align-items-center justify-content-end">
							<div class="col-md-6 ">
								<div class="text">
									<h3 style="color: rgb(255, 234, 172);">Saha Beach Hotel &amp; Resort</h3>
									<h1 style="color: whitesmoke;" class="mb-3">It feels like staying in your own home.</h1>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>

		<section class="ftco-booking ftco-section ftco-no-pt ftco-no-pb">
			<div class="container">
				<div class="row no-gutters">
					<div class="col-lg-12">
						<form @submit.prevent="getRooms" class="booking-form" style="background-color: rgb(248, 247, 247);">
							<div class="row">
								<div class="col-md d-flex py-md-4">
									<div class="form-group align-self-stretch d-flex align-items-end">
										<div class="wrap align-self-stretch py-3 px-4">
											<label for="checkin_date" style="font-size: 18px;">Check-in Date</label>
											<input type="date" id="checkin_date" class="form-control checkin_date"
												v-model="req.checkin" placeholder="Check-in date">
										</div>
									</div>
								</div>
								<div class="col-md d-flex py-md-4">
									<div class="form-group align-self-stretch d-flex align-items-end">
										<div class="wrap align-self-stretch py-3 px-4">
											<label for="checkout_date" style="font-size: 17px;">Check-out </label>
											<input type="date" id="checkout_date" class="form-control checkout_date"
												v-model="req.checkout" placeholder="Check-out date">
										</div>
									</div>
								</div>
								<div class="col-md d-flex py-md-4">
									<div class="form-group align-self-stretch d-flex align-items-end">
										<div class="wrap align-self-stretch py-3 px-4">
											<label for="room_type" style="font-size: 17px;">Room</label>
											<div class="form-field">
												<div class="select-wrap">
													<div class="icon"><span class="ion-ios-arrow-down"></span></div>
													<select id="room_type" class="form-control" v-model="req.room_type">
														<option value="Suite">Suite (up to 5 person)</option>
														<option value="Single">Single (only 1 person)</option>
														<option value="Double">Double (up to 2 person)</option>
														<option value="Triple">Triple (up to 3 person)</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md d-flex">
									<div class="form-group d-flex align-self-stretch">
										<button type="submit"
											class="btn btn-primary py-5 py-md-3 px-4 align-self-stretch d-block">
											<span>Check Availability <small>Best Price Guaranteed!</small></span>
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>

		<!-- Result View -->
		<div class="row no-gutters mt-5" v-if="show">

			<div class="col-lg-12 text-center">
				<h2 class="mt-5 mb-4" style="font-family: Verdana, Geneva, Tahoma, sans-serif;"><span
						style="color: rgb(33, 204, 142);">{{ rooms.length }}</span> properties found:</h2>
			</div>
			<div v-if="rooms.length === 1" class="col-lg-6 offset-lg-3">
				<div href="#" class="room-wrap d-md-flex">
					<a @click.prevent="Details(rooms[0].id)" class="img"
						:style="{ 'background-image': 'url(' + rooms[0].image + ')' }"></a>
					<div class="half d-flex align-items-center">
						<div class="text p-4 text-center">
							<!-- Use dynamic rendering for the stars. Here we assume 'getRoomStars' returns an array with the number of stars. -->
							<p class="star mb-0">
								<span v-for="star in getRoomStars(rooms[0].room_type)" :key="star"
									class="ion-ios-star"></span>
							</p>
							<p class="mb-0">
								<span class="price mr-1">From {{ getRoomPrice(rooms[0].room_type) }} DT</span>
								<span class="per">per night</span>
							</p>
							<h3 class="mb-3">{{ rooms[0].room_type }}</h3>
							<p class="pt-1"><a href="#" @click.prevent="Details(rooms[0].id)"
									class="btn-custom px-3 py-2 rounded">View Details <i class="fa-solid fa-circle-info"></i></a></p>

							<p class="pt-1">
								<a href="#" @click.prevent="ToReservation(rooms[0].id)" class="btn-info active px-3 py-2 rounded">Get Room Now! <i
										class="fa-solid fa-circle-arrow-right"></i></a>
							</p>
						</div>
					</div>
				</div>
			</div>

			<div v-else v-for="(room, index) in rooms" :key="room.id" class="col-lg-6 offset-lg-3">
				<div class="room-wrap d-md-flex">
					<a @click.prevent="Details(room.id)" :class="{ 'order-md-last': index % 2 === 1 }" class="img"
						:style="{ 'background-image': 'url(' + room.image + ')' }"></a>
					<div
						:class="[index % 2 === 1 ? 'half right-arrow d-flex align-items-center' : 'half left-arrow d-flex align-items-center']">
						<div class="text p-4 text-center">
							<!-- Use dynamic rendering for the stars. Here we assume 'getRoomStars' returns an array with the number of stars. -->
							<p class="star mb-0">
								<span v-for="star in getRoomStars(room.room_type)" :key="star" class="ion-ios-star"></span>
							</p>
							<p class="mb-0">
								<span class="price mr-1">From {{ getRoomPrice(room.room_type) }} DT</span>
								<span class="per">per night</span>
							</p>
							<h3 class="mb-3"><a href="#">{{ room.room_type }}</a></h3>
							<p class="pt-1"><a @click.prevent="Details(room.id)" class="btn-custom px-3 py-2 rounded">View
									Details <span class="ion-ios-arrow-forward"></span></a></p>
							<p class="pt-1">
								<a href="" @click.prevent="ToReservation(room.id)" class="btn-info active px-3 py-2 rounded">Get Room Now! <i
										class="fa-solid fa-circle-arrow-right"></i></a>
							</p>
						</div>
					</div>
				</div>
			</div>
			<section class="ftco-section">
				<div class="container table-responsive">

					<table class="table table-hover">
						<thead>
							<tr>
								<th class="">Room Type</th>
								<th class="text-center">LPD</th>
								<th class="text-center">Demi Pension</th>
								<th class="text-center">Pension Complete</th>
								<th class="text-center">All Inclusive</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th class="">Single</th>
								<td class="text-center ">80 DT</td>
								<td class="text-center">100 DT</td>
								<td class="text-center">150 DT</td>
								<td class="text-center">200 DT</td>
							</tr>
							<tr>
								<th class="">Double</th>
								<td class="text-center">100 DT</td>
								<td class="text-center">120 DT</td>
								<td class="text-center">170 DT</td>
								<td class="text-center">220 DT</td>
							</tr>
							<tr>
								<th class="">Triple</th>
								<td class="text-center">120 DT</td>
								<td class="text-center">140 DT</td>
								<td class="text-center">190 DT</td>
								<td class="text-center">240 DT</td>
							</tr>
							<tr>
								<th class="">Suite</th>
								<td class="text-center">160 DT</td>
								<td class="text-center">180 DT</td>
								<td class="text-center">230 DT</td>
								<td class="text-center">300 DT</td>
							</tr>
							<!-- Add other room types as needed -->
						</tbody>
					</table>
				</div>
			</section>



		</div>

		<section class="ftco-section ">
			<div class="container">
				<div class="row justify-content-center mb-5 pb-3">
					<div class="col-md-7 heading-section text-center ">
						<span class="subheading">Welcome to Saha Beach Hotel</span>
						<h2 class="mb-4">You'll Never Want To Leave</h2>
					</div>
				</div>
				<div class="row d-flex">
					<div class="col-md pr-md-1 d-flex align-self-stretch ">
						<div class="media block-6 services py-4 d-block text-center">
							<div class="d-flex justify-content-center">
								<div class="icon d-flex align-items-center justify-content-center">
									<span class="flaticon-reception-bell"></span>
								</div>
							</div>
							<div class="media-body">
								<h3 class="heading mb-3">Friendly Service</h3>
							</div>
						</div>
					</div>
					<div class="col-md px-md-1 d-flex align-self-stretch ">
						<div class="media block-6 services active py-4 d-block text-center">
							<div class="d-flex justify-content-center">
								<div class="icon d-flex align-items-center justify-content-center">
									<span class="flaticon-serving-dish"></span>
								</div>
							</div>
							<div class="media-body">
								<h3 class="heading mb-3">Get Breakfast</h3>
							</div>
						</div>
					</div>
					<div class="col-md px-md-1 d-flex align-sel Searchf-stretch ">
						<div class="media block-6 services py-4 d-block text-center">
							<div class="d-flex justify-content-center">
								<div class="icon d-flex align-items-center justify-content-center">
									<span class="flaticon-car"></span>
								</div>
							</div>
							<div class="media-body">
								<h3 class="heading mb-3">Transfer Services</h3>
							</div>
						</div>
					</div>
					<div class="col-md px-md-1 d-flex align-self-stretch ">
						<div class="media block-6 services py-4 d-block text-center">
							<div class="d-flex justify-content-center">
								<div class="icon d-flex align-items-center justify-content-center">
									<span class="flaticon-spa"></span>
								</div>
							</div>
							<div class="media-body">
								<h3 class="heading mb-3">Suits &amp; SPA</h3>
							</div>
						</div>
					</div>
					<div class="col-md pl-md-1 d-flex align-self-stretch ">
						<div class="media block-6 services py-4 d-block text-center">
							<div class="d-flex justify-content-center">
								<div class="icon d-flex align-items-center justify-content-center">
									<span class="ion-ios-bed"></span>
								</div>
							</div>
							<div class="media-body">
								<h3 class="heading mb-3">Cozy Rooms</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="ftco-section ftco-wrap-about ftco-no-pt ftco-no-pb ">
			<div class="container ">
				<div class="row no-gutters">
					<div class="col-md-7 order-md-last d-flex">
						<div class="bgi2 img img-1 mr-md-2  "></div>
						<div class="bgi3 img img-2 ml-md-2  "></div>
					</div>
					<div class="col-md-5 wrap-about pb-md-3  pr-md-5 pb-md-5 pt-md-4">
						<div class="heading-section mb-4 my-5 my-md-0">
							<span class="subheading">About Saha Beach Hotel</span>
							<h2 class="mb-4">Saha Beach Hotel the Most Recommended Hotel All Over the World</h2>
						</div>
						<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there
							live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics,
							a large language ocean.</p>
						<p><a href="#" class="btn btn-secondary rounded">Reserve Your Room Now</a></p>
					</div>
				</div>
			</div>
		</section>

		<section class="testimony-section">
			<div class="container">
				<div class="row no-gutters justify-content-center">
					<div class="col-md-5 d-flex">
						<div class="testimony-img bgi4"></div>
					</div>
					<div class="col-md-7 py-5 pl-md-5">
						<div class="py-md-5">
							<div class="heading-section mb-4">
								<span class="subheading">Testimony</span>
								<h2 class="mb-0">Happy Customer</h2>
							</div>
							<div class="carousel-testimony  ">
								<div class="item">
									<div class="testimony-wrap pb-4">
										<div class="text">
											<p class="mb-4">A small river named Duden flows by their place and supplies it
												with the necessary regelialia. It is a paradisematic country, in which
												roasted parts of sentences fly into your mouth.</p>
										</div>
										<div class="d-flex">
											<div class="user-img bgi5">
											</div>
											<div class="pos ml-3">
												<p class="name">Gerald Hodson</p>
												<span class="position">Businessman</span>
											</div>
										</div>
									</div>
								</div>
								<div class="item">
									<div class="testimony-wrap pb-4">
										<div class="text">
											<p class="mb-4">A small river named Duden flows by their place and supplies it
												with the necessary regelialia. It is a paradisematic country, in which
												roasted parts of sentences fly into your mouth.</p>
										</div>
										<div class="d-flex">
											<div class="user-img bgi6">
											</div>
											<div class="pos ml-3">
												<p class="name">Gerald Hodson</p>
												<span class="position">Businessman</span>
											</div>
										</div>
									</div>
								</div>
								<div class="item">
									<div class="testimony-wrap pb-4">
										<div class="text">
											<p class="mb-4">A small river named Duden flows by their place and supplies it
												with the necessary regelialia. It is a paradisematic country, in which
												roasted parts of sentences fly into your mouth.</p>
										</div>
										<div class="d-flex">
											<div class="user-img bgi7">
											</div>
											<div class="pos ml-3">
												<p class="name">Gerald Hodson</p>
												<span class="position">Businessman</span>
											</div>
										</div>
									</div>
								</div>
								<div class="item">
									<div class="testimony-wrap pb-4">
										<div class="text">
											<p class="mb-4">A small river named Duden flows by their place and supplies it
												with the necessary regelialia. It is a paradisematic country, in which
												roasted parts of sentences fly into your mouth.</p>
										</div>
										<div class="d-flex">
											<div class="user-img bgi8">
											</div>
											<div class="pos ml-3">
												<p class="name">Gerald Hodson</p>
												<span class="position">Businessman</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="ftco-section ftco-no-pb ftco-room">
			<div class="container-fluid px-0">
				<div class="row no-gutters justify-content-center mb-5 pb-3">
					<div class="col-md-7 heading-section text-center ">
						<span class="subheading">Saha Beach Rooms</span>
						<h2 class="mb-4">Hotel Master's Rooms</h2>
					</div>
				</div>
				<div class="row no-gutters">
					<div class="col-lg-6">
						<div class="room-wrap d-md-flex ">
							<a href="#" class="img bgimg1"></a>
							<div class="half left-arrow d-flex align-items-center">
								<div class="text p-4 text-center">
									<p class="star mb-0"><span class="ion-ios-star"></span><span
											class="ion-ios-star"></span><span class="ion-ios-star"></span></p>
									<p class="mb-0"><span class="price mr-1">Starting at 80.00 DT</span> <span
											class="per">per night</span></p>
									<h3 class="mb-3"><a href="rooms.html">Single Room</a></h3>
									<p class="pt-1"><a href="room-single.html" class="btn-custom px-3 py-2 rounded">View
											Details <span class="icon-long-arrow-right"></span></a></p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="room-wrap d-md-flex ">
							<a href="#" class="img bgimg2"></a>
							<div class="half left-arrow d-flex align-items-center">
								<div class="text p-4 text-center">
									<p class="star mb-0"><span class="ion-ios-star"></span><span
											class="ion-ios-star"></span><span class="ion-ios-star"></span><span
											class="ion-ios-star"></span></p>
									<p class="mb-0"><span class="price mr-1">Starting at 100.00 DT</span> <span
											class="per">per night</span></p>
									<h3 class="mb-3"><a href="rooms.html">Double Room</a></h3>
									<p class="pt-1"><a href="room-single.html" class="btn-custom px-3 py-2 rounded">View
											Details <span class="icon-long-arrow-right"></span></a></p>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="room-wrap d-md-flex ">
							<a href="#" class="img order-md-last bgimg3"></a>
							<div class="half right-arrow d-flex align-items-center">
								<div class="text p-4 text-center">
									<p class="star mb-0"><span class="ion-ios-star"></span><span
											class="ion-ios-star"></span><span class="ion-ios-star"></span><span
											class="ion-ios-star"></span></p>
									<p class="mb-0"><span class="price mr-1">Starting at 120.00 DT</span> <span
											class="per">per night</span></p>
									<h3 class="mb-3"><a href="rooms.html">Triple Room</a></h3>
									<p class="pt-1"><a href="room-single.html" class="btn-custom px-3 py-2 rounded">View
											Details <span class="icon-long-arrow-right"></span></a></p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="room-wrap d-md-flex ">
							<a href="#" class="img order-md-last bgimg4"></a>
							<div class="half right-arrow d-flex align-items-center">
								<div class="text p-4 text-center">
									<p class="star mb-0"><span class="ion-ios-star"></span><span
											class="ion-ios-star"></span><span class="ion-ios-star"></span><span
											class="ion-ios-star"></span><span class="ion-ios-star"></span></p>
									<p class="mb-0"><span class="price mr-1">Starting at 120.00 DT</span> <span
											class="per">per night</span></p>
									<h3 class="mb-3"><a href="rooms.html">Suite</a></h3>
									<p class="pt-1"><a href="room-single.html" class="btn-custom px-3 py-2 rounded">View
											Details <span class="icon-long-arrow-right"></span></a></p>
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
import apiConfig from '../../../apiConfig';
import axios from 'axios';
const req = ref({});
const rooms = ref([]);
const router = useRouter();
const show = ref(false);
// const isLoading = ref(false)

const getRooms = async () => {
	// isLoading.value = true;
	try {
		const response = await axios.get(`${apiConfig.API_BASE_URL}/checkAvailability`, {
			params: req.value
		});
		// isLoading.value = false;
		rooms.value = response.data.available_rooms;
		show.value = true;
		console.log(response.data)
	} catch (error) {
		console.log(error);
	}
};

const Details = (id) => {
	router.push({ name: 'RoomDetails', params: { id } });
};

const ToReservation = (roomId) => {
	router.push({ name: 'AddReservation',params:{roomId} });
}
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

<style lang="css" >
.bgi {
	background-image: url('../../../../../public/userAssets/images/bg_1.jpg');
}

.bgi2 {
	background-image: url('../../../../../public/userAssets/images/about-1.jpg');
}

.bgi3 {
	background-image: url('../../../../../public/userAssets/images/about-2.jpg');

}

.bgi4 {
	background-image: url('../../../../../public/userAssets/images/testimony-img.jpg');

}

.bgi5 {
	background-image: url('../../../../../public/userAssets/images/person_1.jpg');
}

.bgi6 {
	background-image: url('../../../../../public/userAssets/images/person_2.jpg');

}

.bgi7 {
	background-image: url('../../../../../public/userAssets/images/person_3.jpg');

}

.bgi8 {
	background-image: url('../../../../../public/userAssets/images/person_4.jpg');

}

.bgimg1 {
	background-image: url('../../../../../public/userAssets/images/room-1.jpg');

}

.bgimg2 {
	background-image: url('../../../../../public/userAssets/images/doubleroom.jpg');

}

.bgimg3 {
	background-image: url('../../../../../public/userAssets/images/tripleroom.jpg');

}

.bgimg4 {
	background-image: url('../../../../../public/userAssets/images/suite.png');

}
</style>