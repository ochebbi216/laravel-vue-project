<template>
	<div class="header">
		<div class="header-left">
			<a href="" class="logo"> <img src="../../../public/assets/img/hotel_logo.png" width="50" height="70"
					alt="logo"> <span class="logoclass">SAHA BEACH </span> </a>
			<a href="" class="logo logo-small"> <img src="../../../public/assets/img/hotel_logo.png" alt="Logo"
					width="30" height="30"> </a>
		</div>
		<a href="javascript:void(0);" id="toggle_btn"> <i class="fe fe-text-align-left"></i> </a>
		<a class="mobile_btn" id="mobile_btn"> <i class="fas fa-bars"></i> </a>
		<ul class="nav user-menu">
			<!-- <li class="nav-item dropdown noti-dropdown">
				<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"> <i class="fe fe-bell"></i> <span
						class="badge badge-pill">3</span> </a>
				<div class="dropdown-menu notifications">
					<div class="topnav-dropdown-header"> <span class="notification-title">Notifications</span> <a
							href="javascript:void(0)" class="clear-noti"> Clear All </a> </div>
					<div class="noti-content">
						<ul class="notification-list">
							<li class="notification-message">
								<a href="#">
									<div class="media"> <span class="avatar avatar-sm">
											<img class="avatar-img rounded-circle" alt="User Image"
												src="../../../public/assets/img/hotel_logo.png">
										</span>
										<div class="media-body">
											<p class="noti-details"><span class="noti-title">Carlson Tech</span> has
												approved <span class="noti-title">your estimate</span></p>
											<p class="noti-time"><span class="notification-time">4 mins ago</span> </p>
										</div>
									</div>
								</a>
							</li>
							<li class="notification-message">
								<a href="#">
									<div class="media"> <span class="avatar avatar-sm">
											<img class="avatar-img rounded-circle" alt="User Image"
												src="../../../public/assets/img/hotel_logo.png">
										</span>
										<div class="media-body">
											<p class="noti-details"><span class="noti-title">International Software
													Inc</span> has sent you a invoice in the amount of <span
													class="noti-title">$218</span></p>
											<p class="noti-time"><span class="notification-time">6 mins ago</span> </p>
										</div>
									</div>
								</a>
							</li>
							<li class="notification-message">
								<a href="#">
									<div class="media"> <span class="avatar avatar-sm">
											<img class="avatar-img rounded-circle" alt="User Image"
												src="../../../public/assets/img/hotel_logo.png">
										</span>
										<div class="media-body">
											<p class="noti-details"><span class="noti-title">John Hendry</span> sent a
												cancellation request <span class="noti-title">Apple iPhone
													XR</span></p>
											<p class="noti-time"><span class="notification-time">8 mins ago</span> </p>
										</div>
									</div>
								</a>
							</li>
							<li class="notification-message">
								<a href="#">
									<div class="media"> <span class="avatar avatar-sm">
											<img class="avatar-img rounded-circle" alt="User Image"
												src="../../../public/assets/img/hotel_logo.png">
										</span>
										<div class="media-body">
											<p class="noti-details"><span class="noti-title">Mercury Software
													Inc</span> added a new product <span class="noti-title">Apple
													MacBook Pro</span></p>
											<p class="noti-time"><span class="notification-time">12 mins ago</span> </p>
										</div>
									</div>
								</a>
							</li>
						</ul>
					</div>
					<div class="topnav-dropdown-footer"> <a href="#">View all Notifications</a> </div>
				</div>
			</li> -->
			<li class="nav-item dropdown has-arrow">
				<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
					<span class="user-img"><img class="rounded-circle" src="../../../public/assets/img/hotel_logo.png"
							width="31" alt="Soeng Souy"></span> </a>
				<div class="dropdown-menu">
					<div class="user-header">
						<div class="avatar avatar-sm"> <img src="../../../public/assets/img/hotel_logo.png" alt="User Image"
								class="avatar-img rounded-circle"> </div>
						<div class="user-text">
							<h6> &nbsp; </h6>
							<p class="text-muted mb-0">Administrator</p>
						</div>
					</div> 
					<a class="dropdown-item disabled" href="profile.html" >My Profile</a> 
					<!-- <a class="dropdown-item" href="settings.html">Account Settings</a> -->
					<a class="dropdown-item " @click="logout" href="#"  >Logout</a>
				</div>
			</li>
		</ul>
		<!-- <div class="top-nav-search">
			<form>
				<input type="text" class="form-control" placeholder="Search here">
				<button class="btn" type="submit"><i class="fas fa-search"></i></button>
			</form>
		</div> -->
	</div>
	<div class="sidebar" id="sidebar">
		<div class="sidebar-inner slimscroll">
			<div id="sidebar-menu" class="sidebar-menu">
				<ul>
					<li> <router-link to="/dashboard"><i class="fas fa-tachometer-alt"></i> 
						<span>Dashboard</span></router-link>
					
					</li>

					<li class="list-divider"></li>

					<li class="submenu"><router-link to="/reservations"><i class="fa-solid fa-hotel"></i>
							<span>Reservation</span></router-link>

					</li>
					<li class="submenu"><router-link to="/listuser"><i class="fa-solid fa-people-line"></i>
							<span>Customers</span></router-link>

					</li>
					<li class="submenu"> <router-link to="/rooms"><i class="fa-solid fa-bed"></i> <span> Rooms
							</span></router-link>

					</li>
					<li class="submenu"> <router-link to="/blackListUsers"><i
								class="fa-solid fa-skull-crossbones"></i><span>Black List Users</span> </router-link>

					</li>
					<li class="submenu"> <router-link to="/reclamations"><i
								class="fa-solid fa-person-circle-exclamation"></i><span>Reclamations</span> 
								<span
								v-if="reclamations.length !== 0" class="badge badge-pill text-white bg-danger">
								{{reclamations.length }}</span>
								</router-link>

					</li>


				</ul>
			</div>
		</div>
	</div>
</template>

<script setup>

import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useToast } from 'vue-toastification';
const toast = useToast();
const router = useRouter();
const token = ref(localStorage.getItem('admintoken'));
const reclamations = ref([]);

// const user = ref({});
// // get data from localStorage
// const storedUser = localStorage.getItem('user');
// if (storedUser) {
//   user.value = JSON.parse(storedUser);
// }
// const userName = user.value.name

const logout = async () => {
  try {
    const response = await axios.post('http://localhost:8000/api/admin/logout', null, {
      headers: {
        'Authorization': `Bearer ${token.value}`
      }
    });
	
    console.log(response);
  } catch (err) {
    console.error(err);
	toast.error("Error during logout");

  } finally {
    localStorage.removeItem('admintoken');
    // localStorage.removeItem('user');
    token.value = ''; // Reset the token
    router.push({ name: 'Login' }); // Redirect to login
  }
};



const fetchReclamations = async () => {
	await axios.get('http://localhost:8000/api/reclamation')
		.then(res => {
			reclamations.value = res.data.filter(reclamation_reservations => reclamation_reservations.status === 0);
		})
		.catch(error => {
			console.error('Error fetching reclamations:', error);
		})
};

onMounted(() => {
	fetchReclamations();
});
</script>

<style>

.bg {
    background-color: rgb(248, 249, 250);
}

/* @import "../../../public/assets/css/bootstrap.min.css";
@import "../../../public/assets/css/style.css";
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css";
@import "https://cdn.oesmith.co.uk/morris-0.5.1.css";
@import "../../../public/assets/plugins/fontawesome/css/fontawesome.min.css";
@import "../../../public/assets/plugins/fontawesome/css/all.min.css";
@import "../../../public/assets/css/feathericon.min.css";
@import "../../../public/assets/plugins/morris/morris.css"; */
</style>