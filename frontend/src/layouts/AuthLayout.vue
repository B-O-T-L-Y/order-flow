<script setup lang="ts">
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";
</script>

<template>
  <Header/>
  <main class="pt-6 bg-white dark:bg-gray-900 antialiased h-full min-h-screen">
    <RouterView/>
  </main>
  <Footer/>
</template>

