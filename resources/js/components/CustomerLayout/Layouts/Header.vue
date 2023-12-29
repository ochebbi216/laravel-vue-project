<template>
	<div>
		<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
			<div class="container">
				<a class="navbar-brand" href="index.html">Saha<span>Beach</span> </a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
					aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="oi oi-menu"></span> Menu
				</button>

				<div class="collapse navbar-collapse" id="ftco-nav">
					<ul class="navbar-nav ml-lg-5" >
						<li class="nav-item"><router-link :to="{ name: 'Acceuil' }" class="nav-link" style="font-size: 16px;"> Home</router-link></li>
						<li class="nav-item"><router-link :to="{ name: 'Room' }" class="nav-link" style="font-size: 16px;">Our Rooms</router-link></li>
						<li class="nav-item"><router-link :to="{ name: 'Reservation' }" class="nav-link" style="font-size: 16px;">Reservations</router-link></li>
						<li class="nav-item"><router-link :to="{ name: 'Reclamation' }" class="nav-link" style="font-size: 16px;">Complaints</router-link></li>
						<li class="nav-item"><a href="about.html" class="nav-link" style="font-size: 16px;">About Us</a></li>

					</ul>
					<ul class="navbar-nav float-right" style="margin-left: 10%;">

						<li v-if="!token" class="nav-item">
							<router-link :to="{ name: 'Register' }" class="nav-link" style="font-size: 16px;"> Register</router-link>
						</li>
						<li v-if="!token" class="nav-item">
							<router-link :to="{ name: 'Login' }" class="nav-link" style="font-size: 16px;"> Login</router-link>
						</li>
						<li v-else class="nav-item">
							<a href="#" @click="logout" class="nav-link" style="font-size: 16px;margin-left: 15%;">Logout </a>
						</li>
					</ul>
				</div>
			</div>
		</nav>


		<!-- loader -->
		<!-- <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div> -->


	</div>
</template>

<script setup>
import { ref, onMounted ,computed} from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const token = ref(localStorage.getItem('token'));
const user = ref({});
// Immediately attempt to parse the user data from localStorage
const storedUser = localStorage.getItem('user');
if (storedUser) {
  user.value = JSON.parse(storedUser);
}
// Create a computed property to reactively update the username in the template
const userName = user.value.name

const logout = async () => {
  try {
    const response = await axios.post('http://localhost:8000/api/user/logout', null, {
      headers: {
        'Authorization': `Bearer ${token.value}`
      }
    });
	
    console.log(response);
  } catch (err) {
    console.error(err);
    alert("Error during logout");
  } finally {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    token.value = ''; // Reset the token
    router.push({ name: 'Login' }); // Redirect to login
  }
};



</script>
<style></style>