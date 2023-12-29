<template>
    <div>
        <div class="hero-wrap" :style="{ backgroundImage: 'url(../../../userAssets/images/bg_2.jpg)' }">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text d-flex align-itemd-center justify-content-center">
          <div class="col-md-9  text-center d-flex align-items-end justify-content-center">
          	<div class="text">
	            <p class="breadcrumbs mb-2"><span class="mr-2"><a href="">Home</a></span> <span class="mr-2"><a href="">Rooms</a></span> <span>Details</span></p>
	            <h1 class="mb-4 bread">Rooms Details</h1>
            </div>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
          	<div v-if="room" class="row">
          		<div class="col-md-12 ">
          			<div class="single-slider ">
          				<div class="item">
          					<div class="room-img" :style="{ 'background-image': 'url(' + room.image + ')' }"></div>
          				</div>
          				
          			</div>
          		</div>
          		<div class="col-md-12 room-single mt-4 mb-5 ">
          			<h2 class="mb-4"> Room {{room.room_type}}</h2>
    						<p><h5>Description: </h5>{{ room.description }}</p>
    						<div class="d-md-flex mt-5 mb-5">
    							<ul class="list">
	    							<li><span>Max:</span> {{MaxPlaceRoom(room.room_type)}} Persons</li>
	    							<li><span>Size:</span> {{RoomSize(room.room_type)}} m2</li>
	    						</ul>
	    						<ul class="list ml-md-5">
	    							<li><span>View:</span> Sea View</li>
	    							<li><span>Bed:</span> {{MaxPlaceRoom(room.room_type)}}</li>
	    						</ul>
    						</div>
                            <button class="btn btn-primary rounded mx-2">Get This Room Now !</button>
                        </div>

          	</div>
          </div> <!-- .col-md-8 -->
          <div class="col-lg-4 sidebar  pl-md-5">

            <div class="sidebar-box ">
              <div class="categories">
                <h3>Categories</h3>
                <li><a href="#">Single <span>({{ categories.Single }})</span></a></li>
                <li><a href="#">Double <span>({{ categories.Double }})</span></a></li>
                <li><a href="#">Triple <span>({{ categories.Triple }})</span></a></li>
                <li><a href="#">Suite <span>({{ categories.Suite }})</span></a></li>

              </div>
            </div>

            <div class="sidebar-box ">
              <h3>Recent Blog</h3>
              <div class="block-21 mb-4 d-flex">
                <a class="blog-img mr-4" ></a>
                <div class="text">
                  <h3 class="heading"><a href="#">Even the all-powerful Pointing has no control about the blind texts</a></h3>
                  <div class="meta">
                    <div><a href="#"><span class="icon-calendar"></span> Oct 30, 2019</a></div>
                    <div><a href="#"><span class="icon-person"></span> Admin</a></div>
                    <div><a href="#"><span class="icon-chat"></span> 19</a></div>
                  </div>
                </div>
              </div>
              <div class="block-21 mb-4 d-flex">
                <a class="blog-img mr-4" ></a>
                <div class="text">
                  <h3 class="heading"><a href="#">Even the all-powerful Pointing has no control about the blind texts</a></h3>
                  <div class="meta">
                    <div><a href="#"><span class="icon-calendar"></span> Oct 30, 2019</a></div>
                    <div><a href="#"><span class="icon-person"></span> Admin</a></div>
                    <div><a href="#"><span class="icon-chat"></span> 19</a></div>
                  </div>
                </div>
              </div>
              <div class="block-21 mb-4 d-flex">
                <a class="blog-img mr-4" ></a>
                <div class="text">
                  <h3 class="heading"><a href="#">Even the all-powerful Pointing has no control about the blind texts</a></h3>
                  <div class="meta">
                    <div><a href="#"><span class="icon-calendar"></span> Oct 30, 2019</a></div>
                    <div><a href="#"><span class="icon-person"></span> Admin</a></div>
                    <div><a href="#"><span class="icon-chat"></span> 19</a></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> <!-- .section -->
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';

const room = ref(null);
const categories = ref({
  Single: 0,
  Double: 0,
  Triple: 0,
  Suite: 0
});
const route = useRoute();
const isLoading = ref(true)

const loadRoomDetails = async () => {
    try {
        const res = await axios.get(`http://localhost:8000/api/rooms/${route.params.id}`);
        room.value = res.data;
    } catch (err) {
        console.error(err);
    }
};

const loadCategoryCounts = async () => {
    const cats = ['Single', 'Double', 'Triple', 'Suite'];
    for (const cat of cats) {
        try {
            const res = await axios.get(`http://localhost:8000/api/getroomsbycat/${cat}`);
            categories.value[cat] = res.data.length; 
        } catch (err) {
            console.error(err);
        }
    }
};

function MaxPlaceRoom(type) {
  switch (type) {
    case 'Single':
      return 1;
    case 'Double':
      return 2;
    case 'Triple':
      return 3;
    case 'Suite':
      return 5;
    default:
      return 0;
  }
}
function RoomSize(type) {
  switch (type) {
    case 'Single':
      return 35;
    case 'Double':
      return 40;
    case 'Triple':
      return 45;
    case 'Suite':
      return 100;
    default:
      return 0;
  }
}
onMounted(() => {
  loadRoomDetails();
  loadCategoryCounts();
});
</script>

<style lang="css" >

</style>