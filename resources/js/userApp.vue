<template>
    <Navbar v-if="!hideLayout"/>
    <router-view />
    <Footer v-if="!hideLayout"/>
</template>
  
  <script setup>
  import { computed } from 'vue';
  import { useRoute } from 'vue-router';
  import Navbar from './components/CustomerLayout/Layouts/Header.vue';
  import Footer from './components/CustomerLayout/Layouts/Footer.vue';
  
  const route = useRoute();
  const hideLayout = computed(() => route.meta.hideLayout);
  </script>