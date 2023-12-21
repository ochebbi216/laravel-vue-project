<template>
	<div class="header">
		<div class="header-left">
			<a href="index.html" class="logo"> <img src="../../../public/assets/img/hotel_logo.png" width="50" height="70"
					alt="logo"> <span class="logoclass">HOTEL</span> </a>
			<a href="index.html" class="logo logo-small"> <img src="../../../public/assets/img/hotel_logo.png" alt="Logo"
					width="30" height="30"> </a>
		</div>
		<a href="javascript:void(0);" id="toggle_btn"> <i class="fe fe-text-align-left"></i> </a>
		<a class="mobile_btn" id="mobile_btn"> <i class="fas fa-bars"></i> </a>
		<ul class="nav user-menu">
			<li class="nav-item dropdown noti-dropdown">
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
												src="../../../public/assets/img/profiles/avatar-02.jpg">
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
												src="../../../public/assets/img/profiles/avatar-11.jpg">
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
												src="../../../public/assets/img/profiles/avatar-17.jpg">
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
												src="../../../public/assets/img/profiles/avatar-13.jpg">
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
			</li>
			<li class="nav-item dropdown has-arrow">
				<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"> <span class="user-img"><img
							class="rounded-circle" src="../../../public/assets/img/profiles/avatar-01.jpg" width="31"
							alt="Soeng Souy"></span> </a>
				<div class="dropdown-menu">
					<div class="user-header">
						<div class="avatar avatar-sm"> <img src="../../../public/assets/img/profiles/avatar-01.jpg"
								alt="User Image" class="avatar-img rounded-circle"> </div>
						<div class="user-text">
							<h6>Soeng Souy</h6>
							<p class="text-muted mb-0">Administrator</p>
						</div>
					</div> <a class="dropdown-item" href="profile.html">My Profile</a> <a class="dropdown-item"
						href="settings.html">Account Settings</a> <a class="dropdown-item" href="login.html">Logout</a>
				</div>
			</li>
		</ul>
		<div class="top-nav-search">
			<form>
				<input type="text" class="form-control" placeholder="Search here">
				<button class="btn" type="submit"><i class="fas fa-search"></i></button>
			</form>
		</div>
	</div>
	<div class="sidebar" id="sidebar">
		<div class="sidebar-inner slimscroll">
			<div id="sidebar-menu" class="sidebar-menu">
				<ul>
					<li> <a href="#"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a> </li>

					<li class="list-divider"></li>

					<li class="submenu"><router-link to="/reservations"><i class="fa-solid fa-hotel"></i> <span>Booking</span></router-link>

					</li>
					<li class="submenu"><router-link to="/listuser"><i class="fa-solid fa-people-line"></i> <span>Customers</span></router-link>

					</li>
					<li class="submenu"> <router-link to="/rooms"><i class="fa-solid fa-bed"></i> <span> Rooms </span></router-link>

					</li>
					<li class="submenu"> <router-link to="/blackListUsers"><i class="fa-solid fa-skull-crossbones"></i><span>Black List Users</span> </router-link>

					</li>
					<li class="submenu"> <router-link to="/reclamations"><i class="fa-solid fa-person-circle-exclamation"></i><span>Reclamations</span> <span v-if="reclamations.length !== 0 " class="badge badge-pill text-danger">{{ reclamations.length }}</span></router-link>
						
					</li>


				</ul>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref,onMounted } from 'vue';
import axios from 'axios';

const reclamations = ref([]);

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

<style lang="css" scoped></style>